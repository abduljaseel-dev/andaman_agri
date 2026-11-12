<?php
declare(strict_types=1);

/*
|--------------------------------------------------------------------------
| SEED BOOKING - APPLICATION SUBMISSION API
|--------------------------------------------------------------------------
| Registered farmer verification:
|   Disabled until the official Department API is connected.
|
| Unregistered farmer applications:
|   Saved immediately for subsequent document verification.
|
| Location validation:
|   District -> Tehsil -> Village: Census directory
|   Post Office -> PIN: District-level postal directory
|
| Existing farmer_registrations records are never modified.
|--------------------------------------------------------------------------
*/

header('Content-Type: application/json; charset=utf-8');
header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
header('X-Content-Type-Options: nosniff');

require_once __DIR__ . '/db.php';

/* --------------------------------------------------------------------------
   JSON RESPONSE
-------------------------------------------------------------------------- */

function json_response(array $payload, int $httpCode = 200): void
{
    http_response_code($httpCode);

    echo json_encode(
        $payload,
        JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_INVALID_UTF8_SUBSTITUTE
    );

    exit;
}

if (!isset($conn) || !($conn instanceof mysqli)) {
    json_response([
        'status' => 'error',
        'message' => 'Database connection is not available.'
    ], 500);
}

$conn->set_charset('utf8mb4');

/* --------------------------------------------------------------------------
   INPUT HELPERS
-------------------------------------------------------------------------- */

function post_text(string $key, int $maxLength): string
{
    $value = $_POST[$key] ?? '';

    if (!is_string($value)) {
        return '';
    }

    $value = trim($value);

    if (function_exists('mb_substr')) {
        return mb_substr($value, 0, $maxLength, 'UTF-8');
    }

    return substr($value, 0, $maxLength);
}

function valid_choice(string $value, array $allowed): bool
{
    return in_array($value, $allowed, true);
}

/**
 * Normalize identity numbers so harmless formatting differences do not
 * create separate identities (for example, spaces or hyphens).
 */
function normalize_identity_number(string $value): string
{
    $value = strtoupper(trim($value));
    $normalized = preg_replace('/[\\s.-]+/u', '', $value);

    return is_string($normalized) ? $normalized : '';
}

/**
 * Check whether an unregistered application already exists for this
 * identity-card type and normalized identity-card number.
 */
function identity_application_exists(
    mysqli $conn,
    string $idCardType,
    string $normalizedNumber
): bool {
    $sql = "
        SELECT id
        FROM seed_booking_applications
        WHERE farmer_path = 'unregistered'
          AND UPPER(TRIM(id_card_type)) = ?
          AND REPLACE(
                REPLACE(
                    REPLACE(
                        REPLACE(UPPER(TRIM(id_card_number)), ' ', ''),
                    '-', ''),
                '.', ''),
              CHAR(9), '') = ?
        LIMIT 1
    ";

    $stmt = $conn->prepare($sql);

    if (!$stmt) {
        throw new RuntimeException('Unable to prepare identity verification query.');
    }

    try {
        $normalizedType = strtoupper(trim($idCardType));
        $stmt->bind_param('ss', $normalizedType, $normalizedNumber);

        if (!$stmt->execute()) {
            throw new RuntimeException('Unable to verify the identity card number.');
        }

        $result = $stmt->get_result();

        return $result !== false && $result->num_rows > 0;
    } finally {
        $stmt->close();
    }
}

/**
 * Serialize submissions for the same identity number. This closes the
 * simultaneous-request gap that a SELECT-only duplicate check would leave.
 */
function acquire_identity_submission_lock(mysqli $conn, string $lockName): bool
{
    $stmt = $conn->prepare('SELECT GET_LOCK(?, 10)');

    if (!$stmt) {
        throw new RuntimeException('Unable to prepare identity submission lock.');
    }

    try {
        $stmt->bind_param('s', $lockName);

        if (!$stmt->execute()) {
            throw new RuntimeException('Unable to secure the application submission.');
        }

        $result = $stmt->get_result();
        $row = $result ? $result->fetch_row() : null;

        return isset($row[0]) && (int)$row[0] === 1;
    } finally {
        $stmt->close();
    }
}

function release_identity_submission_lock(mysqli $conn, string $lockName): void
{
    $stmt = $conn->prepare('SELECT RELEASE_LOCK(?)');

    if ($stmt) {
        try {
            $stmt->bind_param('s', $lockName);
            $stmt->execute();
        } finally {
            $stmt->close();
        }
    }
}

/**
 * Check a directory record using a prepared SELECT query.
 */
function directory_record_exists(
    mysqli $conn,
    string $sql,
    string $types,
    array $params
): bool {
    $stmt = $conn->prepare($sql);

    if (!$stmt) {
        throw new RuntimeException('Unable to prepare location validation query.');
    }

    try {
        $stmt->bind_param($types, ...$params);

        if (!$stmt->execute()) {
            throw new RuntimeException('Unable to validate the selected location.');
        }

        $result = $stmt->get_result();

        return $result !== false && $result->num_rows > 0;

    } finally {
        $stmt->close();
    }
}

/* --------------------------------------------------------------------------
   FILE UPLOAD HELPERS
-------------------------------------------------------------------------- */

function upload_error_message(int $code): string
{
    switch ($code) {
        case UPLOAD_ERR_INI_SIZE:
        case UPLOAD_ERR_FORM_SIZE:
            return 'The uploaded file is too large.';

        case UPLOAD_ERR_PARTIAL:
            return 'A file was only partially uploaded. Please try again.';

        case UPLOAD_ERR_NO_FILE:
            return 'Both identity document and land record files are required.';

        default:
            return 'A file upload failed. Please try again.';
    }
}

/**
 * Validate and securely store one PDF/JPEG/PNG document.
 */
function store_document(
    string $field,
    string $label,
    string $uploadDirectory
): string {
    if (!isset($_FILES[$field]) || !is_array($_FILES[$field])) {
        throw new RuntimeException($label . ' is required.');
    }

    $file = $_FILES[$field];

    $error = (int)($file['error'] ?? UPLOAD_ERR_NO_FILE);

    if ($error !== UPLOAD_ERR_OK) {
        throw new RuntimeException(upload_error_message($error));
    }

    $size = (int)($file['size'] ?? 0);
    $tmpName = (string)($file['tmp_name'] ?? '');

    if ($size <= 0 || $size > 5 * 1024 * 1024) {
        throw new RuntimeException(
            $label . ' must be greater than 0 bytes and no larger than 5 MB.'
        );
    }

    if ($tmpName === '' || !is_uploaded_file($tmpName)) {
        throw new RuntimeException('Invalid upload received for ' . $label . '.');
    }

    $finfo = new finfo(FILEINFO_MIME_TYPE);
    $mime = $finfo->file($tmpName);

    $allowed = [
        'application/pdf' => 'pdf',
        'image/jpeg' => 'jpg',
        'image/png' => 'png'
    ];

    if (!isset($allowed[$mime])) {
        throw new RuntimeException(
            $label . ' must be a PDF, JPG or PNG file.'
        );
    }

    if (
        !is_dir($uploadDirectory) &&
        !mkdir($uploadDirectory, 0750, true) &&
        !is_dir($uploadDirectory)
    ) {
        throw new RuntimeException(
            'The document storage directory could not be created.'
        );
    }

    // Prevent direct browsing of uploaded documents on Apache.
    $protectionFile = $uploadDirectory . DIRECTORY_SEPARATOR . '.htaccess';

    if (!is_file($protectionFile)) {
        $protectionRules =
            "Options -Indexes\n" .
            "<IfModule mod_authz_core.c>\n" .
            "Require all denied\n" .
            "</IfModule>\n" .
            "<IfModule !mod_authz_core.c>\n" .
            "Deny from all\n" .
            "</IfModule>\n";

        if (file_put_contents($protectionFile, $protectionRules) === false) {
            throw new RuntimeException(
                'Could not protect the document storage directory.'
            );
        }
    }

    $indexFile = $uploadDirectory . DIRECTORY_SEPARATOR . 'index.php';

    if (!is_file($indexFile)) {
        if (file_put_contents(
            $indexFile,
            "<?php http_response_code(404); exit;\n"
        ) === false) {
            throw new RuntimeException(
                'Could not protect the document storage directory.'
            );
        }
    }

    $generatedName = bin2hex(random_bytes(20)) . '.' . $allowed[$mime];

    $destination = $uploadDirectory . DIRECTORY_SEPARATOR . $generatedName;

    if (!move_uploaded_file($tmpName, $destination)) {
        throw new RuntimeException('Could not securely save ' . $label . '.');
    }

    return 'private_uploads/seed_booking/' . $generatedName;
}

/* --------------------------------------------------------------------------
   REQUEST METHOD
-------------------------------------------------------------------------- */

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Allow: POST');

    json_response([
        'status' => 'error',
        'message' => 'Only POST requests are accepted.'
    ], 405);
}

$action = post_text('action', 40);

/* --------------------------------------------------------------------------
   CHECK WHETHER AN IDENTITY CARD HAS ALREADY BEEN USED
-------------------------------------------------------------------------- */

if ($action === 'check_duplicate_identity') {
    $checkIdCardType = post_text('id_card_type', 50);
    $checkIdCardNumber = post_text('id_card_number', 100);

    if (!valid_choice($checkIdCardType, [
        'Aadhaar',
        'Voter ID',
        'Driving Licence',
        'Other Government ID'
    ])) {
        json_response([
            'status' => 'error',
            'message' => 'Please select a valid identity card type.'
        ], 422);
    }

    $normalizedCheckNumber = normalize_identity_number($checkIdCardNumber);

    if ($normalizedCheckNumber === '') {
        json_response([
            'status' => 'error',
            'message' => 'Please enter the identity card number.'
        ], 422);
    }

    try {
        $alreadyUsed = identity_application_exists(
            $conn,
            $checkIdCardType,
            $normalizedCheckNumber
        );

        json_response([
            'status' => $alreadyUsed ? 'duplicate' : 'available',
            'message' => $alreadyUsed
                ? 'An application has already been submitted using this Identity Card Number. Multiple applications using the same identity number are not permitted.'
                : 'This identity card number is available for application.'
        ]);
    } catch (Throwable $e) {
        error_log('Seed booking identity check: ' . $e->getMessage());

        json_response([
            'status' => 'error',
            'message' => 'Unable to verify the identity card number right now. Please try again.'
        ], 500);
    }
}

/* --------------------------------------------------------------------------
   PUBLIC APPLICATION STATUS LOOKUP
   Search by application number OR identity-card type + number.
-------------------------------------------------------------------------- */

if ($action === 'check_application_status') {
    $applicationNumber = strtoupper(post_text('application_no', 40));
    $statusIdCardType = post_text('id_card_type', 50);
    $statusIdCardNumber = post_text('id_card_number', 100);

    try {
        if ($applicationNumber !== '') {
            $stmt = $conn->prepare(
                "SELECT application_no, booking_type, district, season, crop,
                        application_status, document_review_status,
                        document_review_note, seed_delivery_status,
                        seed_delivery_note, submitted_at
                 FROM seed_booking_applications
                 WHERE UPPER(application_no) = ?
                 LIMIT 1"
            );

            if (!$stmt) {
                throw new RuntimeException('Unable to prepare application status query.');
            }

            $stmt->bind_param('s', $applicationNumber);
        } else {
            if (!valid_choice($statusIdCardType, [
                'Aadhaar',
                'Voter ID',
                'Driving Licence',
                'Other Government ID'
            ]) || $statusIdCardNumber === '') {
                json_response([
                    'status' => 'error',
                    'message' => 'Enter an application number, or select the identity card type and enter its number.'
                ], 422);
            }

            $normalizedStatusNumber = normalize_identity_number($statusIdCardNumber);

            if ($normalizedStatusNumber === '') {
                json_response([
                    'status' => 'error',
                    'message' => 'Please enter a valid identity card number.'
                ], 422);
            }

            $normalizedStatusType = strtoupper(trim($statusIdCardType));

            $stmt = $conn->prepare(
                "SELECT application_no, booking_type, district, season, crop,
                        application_status, document_review_status,
                        document_review_note, seed_delivery_status,
                        seed_delivery_note, submitted_at
                 FROM seed_booking_applications
                 WHERE farmer_path = 'unregistered'
                   AND UPPER(TRIM(id_card_type)) = ?
                   AND REPLACE(
                         REPLACE(
                           REPLACE(
                             REPLACE(UPPER(TRIM(id_card_number)), ' ', ''),
                           '-', ''),
                         '.', ''),
                       CHAR(9), '') = ?
                 ORDER BY id DESC
                 LIMIT 1"
            );

            if (!$stmt) {
                throw new RuntimeException('Unable to prepare identity status query.');
            }

            $stmt->bind_param('ss', $normalizedStatusType, $normalizedStatusNumber);
        }

        if (!$stmt->execute()) {
            $stmt->close();
            throw new RuntimeException('Unable to retrieve the application status.');
        }

        $result = $stmt->get_result();
        $application = $result ? $result->fetch_assoc() : null;
        $stmt->close();

        if (!$application) {
            json_response([
                'status' => 'not_found',
                'message' => 'No application was found. Please check the details and try again.'
            ], 404);
        }

        json_response([
            'status' => 'success',
            'application' => [
                'application_no' => $application['application_no'],
                'booking_type' => $application['booking_type'],
                'district' => $application['district'],
                'season' => $application['season'],
                'crop' => $application['crop'],
                'application_status' => $application['application_status'],
                'document_review_status' => $application['document_review_status'],
                'document_review_note' => $application['document_review_note'],
                'seed_delivery_status' => $application['seed_delivery_status'],
                'seed_delivery_note' => $application['seed_delivery_note'],
                'submitted_at' => $application['submitted_at']
            ]
        ]);

    } catch (Throwable $e) {
        error_log('Seed booking application status lookup: ' . $e->getMessage());

        json_response([
            'status' => 'error',
            'message' => 'Unable to retrieve the application status right now. Please try again.'
        ], 500);
    }
}

/* --------------------------------------------------------------------------
   REGISTERED FARMER API - TEMPORARILY UNAVAILABLE
-------------------------------------------------------------------------- */

if ($action === 'verify_registered_farmer') {
    json_response([
        'status' => 'service_unavailable',
        'message' =>
            'Registered farmer verification is temporarily unavailable because ' .
            'the Department verification API has not yet been connected. ' .
            'Please try again later or choose the unregistered farmer option.'
    ], 503);
}

/* --------------------------------------------------------------------------
   RETIRED OTP WORKFLOW
-------------------------------------------------------------------------- */

if ($action === 'request_otp') {
    json_response([
        'status' => 'service_unavailable',
        'message' =>
            'The old OTP booking process has been discontinued. ' .
            'Please use the current farmer application workflow.'
    ], 410);
}

/* --------------------------------------------------------------------------
   VALIDATE ACTION
-------------------------------------------------------------------------- */

if ($action !== 'submit_booking') {
    json_response([
        'status' => 'error',
        'message' => 'Invalid request action.'
    ], 400);
}

/* --------------------------------------------------------------------------
   BOOKING DETAILS
-------------------------------------------------------------------------- */

$bookingType = post_text('booking_type', 40);
$district = post_text('district', 80);
$season = post_text('season', 60);
$crop = post_text('crop', 180);
$farmerPath = post_text('farmer_path', 20);

if (!valid_choice($bookingType, [
    'free_minikit',
    'subsidized_seed'
])) {
    json_response([
        'status' => 'error',
        'message' => 'Please select a valid booking category.'
    ], 422);
}

if (!valid_choice($district, [
    'South Andaman',
    'North & Middle Andaman',
    'Nicobar'
])) {
    json_response([
        'status' => 'error',
        'message' => 'Please select a valid district.'
    ], 422);
}

if (!valid_choice($season, [
    'Rabi 2026-27',
    'Kharif 2026'
])) {
    json_response([
        'status' => 'error',
        'message' => 'Please select a valid season.'
    ], 422);
}

$allowedCrops = [
    'Black Gram (Urad) - Minikit 4kg',
    'Green Gram (Moong) - Minikit 4kg',
    'Mustard (Rai/Toria) - Minikit 2kg',
    'Peas (Matar) - Minikit 10kg',
    'Paddy High Yield - 20kg Bag',
    'Island Vegetable Seed Kit'
];

if (!valid_choice($crop, $allowedCrops)) {
    json_response([
        'status' => 'error',
        'message' => 'Please select a valid crop variety.'
    ], 422);
}

/* --------------------------------------------------------------------------
   FARMER APPLICATION ROUTE
-------------------------------------------------------------------------- */

if ($farmerPath === 'registered') {
    json_response([
        'status' => 'service_unavailable',
        'message' =>
            'Registered farmer verification is temporarily unavailable. ' .
            'Applications through the registered farmer route cannot be submitted ' .
            'until the Department API is connected.'
    ], 503);
}

if ($farmerPath !== 'unregistered') {
    json_response([
        'status' => 'error',
        'message' => 'Please select a valid farmer application route.'
    ], 422);
}

/* --------------------------------------------------------------------------
   FARMER DETAILS
-------------------------------------------------------------------------- */

$farmerName = post_text('farmer_name', 150);
$idCardType = post_text('id_card_type', 50);
$idCardNumber = post_text('id_card_number', 100);

if ($farmerName === '' || $idCardNumber === '') {
    json_response([
        'status' => 'error',
        'message' => 'Please provide the farmer name and identity card number.'
    ], 422);
}

if (!valid_choice($idCardType, [
    'Aadhaar',
    'Voter ID',
    'Driving Licence',
    'Other Government ID'
])) {
    json_response([
        'status' => 'error',
        'message' => 'Please select a valid identity card type.'
    ], 422);
}

$normalizedIdCardNumber = normalize_identity_number($idCardNumber);

if ($normalizedIdCardNumber === '') {
    json_response([
        'status' => 'error',
        'message' => 'Please provide a valid identity card number.'
    ], 422);
}

/* --------------------------------------------------------------------------
   ADDRESS DETAILS - NO ISLAND / AREA FIELD
-------------------------------------------------------------------------- */

$farmerDistrict = post_text('farmer_district', 100);
$farmerTehsil = post_text('farmer_tehsil', 150);
$farmerVillage = post_text('farmer_village', 150);
$landSurveyNumber = post_text('land_survey_number', 100);
$landmark = post_text('landmark', 255);
$postOffice = post_text('post_office', 150);
$pincode = post_text('pincode', 6);

if ($farmerDistrict !== $district) {
    json_response([
        'status' => 'error',
        'message' => 'The farmer district must match the selected booking district.'
    ], 422);
}

if (
    $farmerTehsil === '' ||
    $farmerVillage === '' ||
    $landSurveyNumber === '' ||
    $postOffice === '' ||
    !preg_match('/^[0-9]{6}$/', $pincode)
) {
    json_response([
        'status' => 'error',
        'message' =>
            'Please provide the Tehsil, Village, Land Survey Number, ' .
            'Post Office and a valid 6-digit PIN code.'
    ], 422);
}

/* --------------------------------------------------------------------------
   VERIFY DISTRICT -> TEHSIL -> VILLAGE
-------------------------------------------------------------------------- */

try {
    $validTehsil = directory_record_exists(
        $conn,
        "SELECT id
         FROM seed_booking_village_directory
         WHERE district = ?
           AND tehsil = ?
           AND is_active = 1
         LIMIT 1",
        'ss',
        [$farmerDistrict, $farmerTehsil]
    );

    if (!$validTehsil) {
        json_response([
            'status' => 'error',
            'message' => 'The selected Tehsil is not available in the district directory.'
        ], 422);
    }

    $validVillage = directory_record_exists(
        $conn,
        "SELECT id
         FROM seed_booking_village_directory
         WHERE district = ?
           AND tehsil = ?
           AND village = ?
           AND is_active = 1
         LIMIT 1",
        'sss',
        [$farmerDistrict, $farmerTehsil, $farmerVillage]
    );

    if (!$validVillage) {
        json_response([
            'status' => 'error',
            'message' => 'The selected Village does not belong to the selected Tehsil and district.'
        ], 422);
    }

    /* ----------------------------------------------------------------------
       VERIFY POST OFFICE AND PIN CODE
       District-level validation only. No village relationship is assumed.
    ---------------------------------------------------------------------- */

    $validPostOffice = directory_record_exists(
        $conn,
        "SELECT id
         FROM seed_booking_post_office_directory
         WHERE district = ?
           AND post_office = ?
           AND pincode = ?
           AND is_active = 1
         LIMIT 1",
        'sss',
        [$farmerDistrict, $postOffice, $pincode]
    );

    if (!$validPostOffice) {
        json_response([
            'status' => 'error',
            'message' =>
                'The selected Post Office and PIN code do not match the ' .
                'postal directory for this district. Please select them again.'
        ], 422);
    }

} catch (Throwable $e) {

    error_log('Seed booking location validation: ' . $e->getMessage());

    json_response([
        'status' => 'error',
        'message' => 'Unable to validate the address against the location directories.'
    ], 500);
}

/* --------------------------------------------------------------------------
   BUILD BACKWARD-COMPATIBLE FULL ADDRESS
-------------------------------------------------------------------------- */

$addressParts = [
    'District: ' . $farmerDistrict,
    'Tehsil: ' . $farmerTehsil,
    'Village: ' . $farmerVillage,
    'Land Survey Number: ' . $landSurveyNumber
];

if ($landmark !== '') {
    $addressParts[] = 'Landmark: ' . $landmark;
}

$addressParts[] = 'Post Office: ' . $postOffice;
$addressParts[] = 'PIN Code: ' . $pincode;

$farmerLocation = implode(', ', $addressParts);

/* --------------------------------------------------------------------------
   DOCUMENT STORAGE
-------------------------------------------------------------------------- */

$uploadDirectory =
    __DIR__ .
    DIRECTORY_SEPARATOR . 'private_uploads' .
    DIRECTORY_SEPARATOR . 'seed_booking';

$storedFiles = [];

$identityLockName = 'seed_booking_' . substr(
    hash('sha256', strtoupper(trim($idCardType)) . '|' . $normalizedIdCardNumber),
    0,
    48
);
$identityLockAcquired = false;

try {

    $identityLockAcquired = acquire_identity_submission_lock(
        $conn,
        $identityLockName
    );

    if (!$identityLockAcquired) {
        json_response([
            'status' => 'error',
            'message' => 'Another application using this identity number is being processed. Please wait a moment and try again.'
        ], 503);
    }

    // Final server-side duplicate check while holding the per-identity lock.
    if (identity_application_exists($conn, $idCardType, $normalizedIdCardNumber)) {
        release_identity_submission_lock($conn, $identityLockName);
        $identityLockAcquired = false;

        json_response([
            'status' => 'duplicate',
            'message' => 'An application has already been submitted using this Identity Card Number. Multiple applications using the same identity number are not permitted.'
        ], 409);
    }

    $identityPath = store_document(
        'identity_document',
        'Identity document',
        $uploadDirectory
    );

    $storedFiles[] =
        __DIR__ . DIRECTORY_SEPARATOR .
        str_replace('/', DIRECTORY_SEPARATOR, $identityPath);

    $landPath = store_document(
        'land_record',
        'Land record',
        $uploadDirectory
    );

    $storedFiles[] =
        __DIR__ . DIRECTORY_SEPARATOR .
        str_replace('/', DIRECTORY_SEPARATOR, $landPath);

    /* ----------------------------------------------------------------------
       GENERATE APPLICATION NUMBER AND SAVE APPLICATION
    ---------------------------------------------------------------------- */

    $applicationNo = '';
    $inserted = false;

    for ($attempt = 0; $attempt < 5 && !$inserted; $attempt++) {

        $applicationNo =
            'AN-RABI-' .
            date('Y') .
            '-' .
            strtoupper(bin2hex(random_bytes(4)));

        $sql = "
            INSERT INTO seed_booking_applications
            (
                application_no,
                booking_type,
                district,
                season,
                crop,
                farmer_path,
                farmer_name,
                id_card_type,
                id_card_number,
                farmer_location,
                farmer_district,
                farmer_area,
                farmer_tehsil,
                farmer_village,
                land_survey_number,
                landmark,
                post_office,
                pincode,
                identity_document_path,
                land_record_path,
                application_status,
                api_sync_status,
                document_review_status,
                submitted_at
            )
            VALUES
            (
                ?, ?, ?, ?, ?, 'unregistered',
                ?, ?, ?, ?, ?,
                NULL,
                ?, ?, ?, ?, ?, ?,
                ?, ?,
                'Submitted - Pending Document Verification',
                'pending',
                'pending',
                NOW()
            )
        ";

        $stmt = $conn->prepare($sql);

        if (!$stmt) {
            throw new RuntimeException(
                'Could not prepare the application database query.'
            );
        }

        $stmt->bind_param(
            'ssssssssssssssssss',
            $applicationNo,
            $bookingType,
            $district,
            $season,
            $crop,
            $farmerName,
            $idCardType,
            $idCardNumber,
            $farmerLocation,
            $farmerDistrict,
            $farmerTehsil,
            $farmerVillage,
            $landSurveyNumber,
            $landmark,
            $postOffice,
            $pincode,
            $identityPath,
            $landPath
        );

        if ($stmt->execute()) {

            $inserted = true;

        } elseif ((int)$stmt->errno === 1062) {

            $stmt->close();
            continue;

        } else {

            $dbMessage = $stmt->error;
            $stmt->close();

            throw new RuntimeException(
                'Database could not save the application: ' . $dbMessage
            );
        }

        $stmt->close();
    }

    if (!$inserted) {
        throw new RuntimeException(
            'Could not generate a unique application number. Please submit again.'
        );
    }

    /* Release the identity lock only after the application is saved. */
    if ($identityLockAcquired) {
        release_identity_submission_lock($conn, $identityLockName);
        $identityLockAcquired = false;
    }

    /* ----------------------------------------------------------------------
       SUCCESS RESPONSE
    ---------------------------------------------------------------------- */

    json_response([
        'status' => 'success',
        'application_no' => $applicationNo,
        'application_status' => 'Submitted - Pending Document Verification',
        'document_review_status' => 'pending',
        'api_sync_status' => 'pending',
        'message' =>
            'Your application details and supporting documents have been ' .
            'submitted successfully. Document verification will be conducted ' .
            'separately by the Department. This acknowledgement is not an approval.'
    ]);

} catch (Throwable $e) {

    if ($identityLockAcquired) {
        release_identity_submission_lock($conn, $identityLockName);
        $identityLockAcquired = false;
    }

    /* Remove uploaded files if the application was not saved. */

    foreach ($storedFiles as $path) {
        if (is_file($path)) {
            @unlink($path);
        }
    }

    error_log('Seed booking submission error: ' . $e->getMessage());

    json_response([
        'status' => 'error',
        'message' => $e instanceof RuntimeException
            ? $e->getMessage()
            : 'An unexpected error occurred while saving the application. Please try again.'
    ], 500);
}
