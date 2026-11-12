<?php
declare(strict_types=1);

/*
|--------------------------------------------------------------------------
| SEED BOOKING - LOCATION OPTIONS API
|--------------------------------------------------------------------------
| Data sources:
| 1. seed_booking_village_directory
| 2. seed_booking_post_office_directory
|
| Address hierarchy:
| District -> Tehsil -> Village -> Post Office -> PIN Code
|
| IMPORTANT:
| Post offices are currently filtered by district only.
| No village-to-post-office relationship is assumed.
|--------------------------------------------------------------------------
*/

header('Content-Type: application/json; charset=utf-8');
header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
header('Pragma: no-cache');
header('X-Content-Type-Options: nosniff');

require_once __DIR__ . '/db.php';

function respond(int $status, array $data): never
{
    http_response_code($status);

    echo json_encode(
        $data,
        JSON_UNESCAPED_UNICODE | JSON_INVALID_UTF8_SUBSTITUTE
    );

    exit;
}

function getInput(string $key): string
{
    return trim((string)($_GET[$key] ?? ''));
}

/**
 * Convert frontend district values into database district names.
 */
function normalizeDistrict(string $district): string
{
    $key = strtolower(
        preg_replace('/[^a-z]/i', '', $district) ?? ''
    );

    return match ($key) {
        'southandaman' => 'South Andaman',
        'northmiddleandaman' => 'North & Middle Andaman',
        'nicobar' => 'Nicobar',
        default => ''
    };
}

/**
 * Execute a prepared SELECT query.
 */
function getOptions(
    mysqli $conn,
    string $sql,
    string $types = '',
    array $params = []
): array {
    $stmt = $conn->prepare($sql);

    if (!$stmt) {
        throw new RuntimeException('Unable to prepare location query.');
    }

    try {
        if ($types !== '') {
            $stmt->bind_param($types, ...$params);
        }

        if (!$stmt->execute()) {
            throw new RuntimeException('Unable to execute location query.');
        }

        $result = $stmt->get_result();

        if (!$result) {
            throw new RuntimeException('Unable to retrieve location records.');
        }

        return $result->fetch_all(MYSQLI_ASSOC);

    } finally {
        $stmt->close();
    }
}

/**
 * Check whether a database table exists.
 */
function tableExists(mysqli $conn, string $table): bool
{
    $sql = "
        SELECT COUNT(*) AS total
        FROM information_schema.tables
        WHERE table_schema = DATABASE()
          AND table_name = ?
    ";

    $rows = getOptions($conn, $sql, 's', [$table]);

    return (int)($rows[0]['total'] ?? 0) > 0;
}

try {
    if (!isset($conn) || !($conn instanceof mysqli)) {
        throw new RuntimeException('Database connection unavailable.');
    }

    $conn->set_charset('utf8mb4');

    $action = getInput('action');

    $districtInput = getInput('district');
    $district = normalizeDistrict($districtInput);

    if ($action !== 'districts' && $district === '') {
        respond(400, [
            'success' => false,
            'message' => 'Please select a valid district.',
            'options' => []
        ]);
    }

    if (!tableExists($conn, 'seed_booking_village_directory')) {
        throw new RuntimeException('Village directory table is missing.');
    }

    switch ($action) {

        /*
        |--------------------------------------------------------------------------
        | DISTRICTS
        |--------------------------------------------------------------------------
        */

        case 'districts':

            $sql = "
                SELECT DISTINCT district AS value
                FROM seed_booking_village_directory
                WHERE is_active = 1
                  AND district <> ''
                ORDER BY district ASC
            ";

            $options = getOptions($conn, $sql);

            break;


        /*
        |--------------------------------------------------------------------------
        | TEHSILS
        |--------------------------------------------------------------------------
        */

        case 'tehsils':

            $sql = "
                SELECT DISTINCT tehsil AS value
                FROM seed_booking_village_directory
                WHERE district = ?
                  AND is_active = 1
                  AND tehsil <> ''
                ORDER BY tehsil ASC
            ";

            $options = getOptions(
                $conn,
                $sql,
                's',
                [$district]
            );

            break;


        /*
        |--------------------------------------------------------------------------
        | VILLAGES
        |--------------------------------------------------------------------------
        */

        case 'villages':

            $tehsil = getInput('tehsil');

            if ($tehsil === '') {
                respond(400, [
                    'success' => false,
                    'message' => 'Tehsil is required.',
                    'options' => []
                ]);
            }

            $sql = "
                SELECT DISTINCT village AS value
                FROM seed_booking_village_directory
                WHERE district = ?
                  AND tehsil = ?
                  AND is_active = 1
                  AND village <> ''
                ORDER BY village ASC
            ";

            $options = getOptions(
                $conn,
                $sql,
                'ss',
                [$district, $tehsil]
            );

            break;


        /*
        |--------------------------------------------------------------------------
        | POST OFFICES AND PIN CODES
        |--------------------------------------------------------------------------
        | District-level options only.
        | No village mapping is assumed.
        |--------------------------------------------------------------------------
        */

        case 'post_offices':

            if (!tableExists($conn, 'seed_booking_post_office_directory')) {
                respond(503, [
                    'success' => false,
                    'message' => 'The post office directory has not yet been imported.',
                    'options' => []
                ]);
            }

            $sql = "
                SELECT DISTINCT
                    post_office AS value,
                    pincode
                FROM seed_booking_post_office_directory
                WHERE district = ?
                  AND is_active = 1
                  AND post_office <> ''
                  AND pincode REGEXP '^[0-9]{6}$'
                ORDER BY post_office ASC, pincode ASC
            ";

            $options = getOptions(
                $conn,
                $sql,
                's',
                [$district]
            );

            break;


        /*
        |--------------------------------------------------------------------------
        | LEGACY AREA ACTION
        |--------------------------------------------------------------------------
        | Area mappings are not currently verified.
        |--------------------------------------------------------------------------
        */

        case 'areas':

            $options = [];

            break;


        default:

            respond(400, [
                'success' => false,
                'message' => 'Invalid location action.',
                'options' => []
            ]);
    }

    respond(200, [
        'success' => true,
        'options' => $options
    ]);

} catch (Throwable $e) {

    error_log(
        'Seed Booking Location API: ' . $e->getMessage()
    );

    respond(500, [
        'success' => false,
        'message' => 'Unable to load location information.',
        'options' => []
    ]);
}
