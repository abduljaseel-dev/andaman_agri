<?php
declare(strict_types=1);

/*
|--------------------------------------------------------------------------
| DEPARTMENT OF AGRICULTURE - SEED BOOKING ADMINISTRATION
|--------------------------------------------------------------------------
| Place beside db.php. Requires MySQLi connection: $conn
|--------------------------------------------------------------------------
*/

if (session_status() !== PHP_SESSION_ACTIVE) {
    $https = !empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off';

    session_set_cookie_params([
        'httponly' => true,
        'secure' => $https,
        'samesite' => 'Strict',
        'path' => '/'
    ]);

    session_start();
}

require_once __DIR__ . '/db.php';

if (!isset($conn) || !($conn instanceof mysqli)) {
    exit('Database connection error. Please check db.php.');
}

$conn->set_charset('utf8mb4');

function e($value): string
{
    return htmlspecialchars(
        (string)$value,
        ENT_QUOTES | ENT_SUBSTITUTE,
        'UTF-8'
    );
}

function csrf_token(): string
{
    if (empty($_SESSION['seed_admin_csrf'])) {
        $_SESSION['seed_admin_csrf'] = bin2hex(random_bytes(32));
    }

    return $_SESSION['seed_admin_csrf'];
}

function verify_csrf(): void
{
    $token = (string)($_POST['csrf_token'] ?? '');

    if (
        $token === '' ||
        !hash_equals(
            (string)($_SESSION['seed_admin_csrf'] ?? ''),
            $token
        )
    ) {
        http_response_code(419);
        exit('Your session has expired. Refresh the page and try again.');
    }
}

function logged_in(): bool
{
    return !empty($_SESSION['seed_admin_id']);
}

function redirect_admin(): never
{
    header('Location: admin.php');
    exit;
}

function flash(string $message, string $type = 'success'): void
{
    $_SESSION['seed_admin_message'] = [
        'text' => $message,
        'type' => $type
    ];
}

function status_text(string $value): string
{
    return ucfirst(str_replace('_', ' ', $value));
}

/*
|--------------------------------------------------------------------------
| LOGIN
|--------------------------------------------------------------------------
*/

if (!logged_in()) {

    $error = '';

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {

        verify_csrf();

        $username = trim((string)($_POST['username'] ?? ''));
        $password = (string)($_POST['password'] ?? '');

        $stmt = $conn->prepare(
            "SELECT id, username, password_hash, is_active
             FROM seed_booking_admins
             WHERE username = ?
             LIMIT 1"
        );

        $stmt->bind_param('s', $username);
        $stmt->execute();

        $admin = $stmt->get_result()->fetch_assoc();

        $stmt->close();

        if (
            $admin &&
            (int)$admin['is_active'] === 1 &&
            password_verify($password, $admin['password_hash'])
        ) {

            session_regenerate_id(true);

            $_SESSION['seed_admin_id'] = (int)$admin['id'];
            $_SESSION['seed_admin_username'] = $admin['username'];
            $_SESSION['seed_admin_csrf'] = bin2hex(random_bytes(32));

            $update = $conn->prepare(
                "UPDATE seed_booking_admins
                 SET last_login_at = NOW()
                 WHERE id = ?"
            );

            $adminId = (int)$admin['id'];
            $update->bind_param('i', $adminId);
            $update->execute();
            $update->close();

            redirect_admin();

        } else {
            $error = 'Invalid username or password.';
        }
    }

    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Administration Login | Seed Booking Portal</title>

        <style>
            * { box-sizing: border-box; }

            body {
                margin: 0;
                min-height: 100vh;
                display: flex;
                align-items: center;
                justify-content: center;
                padding: 20px;
                background:
                    radial-gradient(circle at top left, #dcefe1, transparent 45%),
                    linear-gradient(135deg, #edf4ee, #e5eee7);
                font-family: "Segoe UI", Arial, sans-serif;
                color: #20382b;
            }

            .login-card {
                width: 100%;
                max-width: 440px;
                background: #fff;
                border: 1px solid #dce8df;
                border-radius: 18px;
                padding: 38px;
                box-shadow: 0 20px 60px rgba(21, 65, 41, .10);
            }

            .brand-mark {
                width: 62px;
                height: 62px;
                border-radius: 16px;
                background: #e5f3e8;
                color: #17663d;
                display: grid;
                place-items: center;
                font-size: 27px;
                font-weight: 800;
                margin-bottom: 22px;
            }

            .eyebrow {
                color: #39815a;
                font-size: 11px;
                font-weight: 800;
                letter-spacing: 1.7px;
                text-transform: uppercase;
            }

            h1 {
                font-size: 25px;
                margin: 9px 0;
                color: #173c29;
            }

            .subtitle {
                color: #738178;
                line-height: 1.6;
                margin-bottom: 28px;
            }

            label {
                display: block;
                font-weight: 650;
                font-size: 13px;
                margin: 17px 0 7px;
            }

            input {
                width: 100%;
                padding: 13px 14px;
                border: 1px solid #d6e1d9;
                border-radius: 9px;
                outline: none;
                font-size: 14px;
            }

            input:focus {
                border-color: #39815a;
                box-shadow: 0 0 0 3px rgba(57, 129, 90, .10);
            }

            button {
                width: 100%;
                padding: 14px;
                margin-top: 25px;
                border: 0;
                border-radius: 9px;
                background: #17663d;
                color: #fff;
                font-weight: 750;
                font-size: 14px;
                cursor: pointer;
            }

            button:hover { background: #104d2e; }

            .error {
                padding: 12px;
                border-radius: 8px;
                background: #fff0ee;
                color: #a13730;
                font-size: 13px;
            }

            .footer {
                margin-top: 25px;
                padding-top: 18px;
                border-top: 1px solid #edf1ed;
                color: #87938a;
                text-align: center;
                font-size: 11px;
                line-height: 1.6;
            }
        </style>
    </head>

    <body>

    <main class="login-card">

        <div class="brand-mark">A</div>

        <div class="eyebrow">Department of Agriculture</div>

        <h1>Administration Portal</h1>

        <p class="subtitle">
            Sign in to manage seed booking applications,
            document verification and seed distribution.
        </p>

        <?php if ($error !== ''): ?>
            <div class="error"><?= e($error) ?></div>
        <?php endif; ?>

        <form method="POST">

            <input
                type="hidden"
                name="csrf_token"
                value="<?= e(csrf_token()) ?>"
            >

            <label for="username">Administrator Username</label>

            <input
                id="username"
                type="text"
                name="username"
                autocomplete="username"
                required
                autofocus
            >

            <label for="password">Password</label>

            <input
                id="password"
                type="password"
                name="password"
                autocomplete="current-password"
                required
            >

            <button type="submit">Sign In to Dashboard</button>

        </form>

        <div class="footer">
            Department of Agriculture<br>
            Andaman and Nicobar Islands Administration
        </div>

    </main>

    </body>
    </html>
    <?php
    exit;
}

/*
|--------------------------------------------------------------------------
| LOGOUT
|--------------------------------------------------------------------------
*/

if (
    $_SERVER['REQUEST_METHOD'] === 'POST' &&
    ($_POST['action'] ?? '') === 'logout'
) {
    verify_csrf();

    $_SESSION = [];

    if (ini_get('session.use_cookies')) {
        $params = session_get_cookie_params();

        setcookie(
            session_name(),
            '',
            time() - 42000,
            $params['path'],
            $params['domain'],
            $params['secure'],
            $params['httponly']
        );
    }

    session_destroy();

    header('Location: admin.php');
    exit;
}

/*
|--------------------------------------------------------------------------
| DOCUMENT VIEWER
|--------------------------------------------------------------------------
*/

if (($_GET['mode'] ?? '') === 'document') {

    $id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
    $type = (string)($_GET['type'] ?? '');

    if (!$id || !in_array($type, ['identity', 'land'], true)) {
        http_response_code(400);
        exit('Invalid document request.');
    }

    $column = $type === 'identity'
        ? 'identity_document_path'
        : 'land_record_path';

    $stmt = $conn->prepare(
        "SELECT $column AS document_path
         FROM seed_booking_applications
         WHERE id = ?
         LIMIT 1"
    );

    $stmt->bind_param('i', $id);
    $stmt->execute();

    $record = $stmt->get_result()->fetch_assoc();
    $stmt->close();

    $relativePath = str_replace(
        '\\',
        '/',
        (string)($record['document_path'] ?? '')
    );

    $allowedPrefixes = [
        'private_uploads/seed_booking_docs/',
        'private_uploads/seed_booking/'
    ];

    $validPrefix = false;

    foreach ($allowedPrefixes as $prefix) {
        if (str_starts_with($relativePath, $prefix)) {
            $validPrefix = true;
            break;
        }
    }

    if (!$validPrefix || str_contains($relativePath, '..')) {
        http_response_code(404);
        exit('Document not found.');
    }

    $base = realpath(__DIR__ . '/private_uploads');
    $file = realpath(__DIR__ . '/' . $relativePath);

    if (
        !$base ||
        !$file ||
        !str_starts_with($file, $base . DIRECTORY_SEPARATOR) ||
        !is_file($file)
    ) {
        http_response_code(404);
        exit('Document file is unavailable.');
    }

    $mime = (new finfo(FILEINFO_MIME_TYPE))->file($file);

    if (!in_array($mime, [
        'application/pdf',
        'image/jpeg',
        'image/png'
    ], true)) {
        http_response_code(415);
        exit('Unsupported document format.');
    }

    header('X-Content-Type-Options: nosniff');
    header('Cache-Control: private, no-store');
    header('Content-Type: ' . $mime);
    header(
        'Content-Disposition: inline; filename="' .
        basename($file) . '"'
    );

    readfile($file);
    exit;
}

/*
|--------------------------------------------------------------------------
| ADMIN ACTIONS
|--------------------------------------------------------------------------
*/

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    verify_csrf();

    $id = filter_input(INPUT_POST, 'application_id', FILTER_VALIDATE_INT);
    $action = (string)($_POST['action'] ?? '');
    $note = trim((string)($_POST['note'] ?? ''));

    if (!$id || mb_strlen($note) > 2000) {
        flash('Invalid application or remarks.', 'error');
        redirect_admin();
    }

    if ($action === 'review') {

        $decision = (string)($_POST['decision'] ?? '');

        if (!in_array($decision, ['verified', 'rejected'], true)) {

            flash('Invalid document review status.', 'error');

        } else {

            $adminId = (int)$_SESSION['seed_admin_id'];

            $stmt = $conn->prepare(
                "UPDATE seed_booking_applications
                 SET document_review_status = ?,
                     document_review_note = ?,
                     reviewed_by = ?,
                     reviewed_at = NOW()
                 WHERE id = ?"
            );

            $stmt->bind_param(
                'ssii',
                $decision,
                $note,
                $adminId,
                $id
            );

            if ($stmt->execute() && $stmt->affected_rows >= 0) {
                flash('Document review updated successfully.');
            } else {
                flash('Unable to update document review.', 'error');
            }

            $stmt->close();
        }

    } elseif ($action === 'delivery') {

        $deliveryStatus = (string)($_POST['delivery_status'] ?? '');

        if (!in_array($deliveryStatus, ['pending', 'delivered'], true)) {

            flash('Invalid delivery status.', 'error');

        } else {

            $sql = "
                UPDATE seed_booking_applications
                SET seed_delivery_status = ?,
                    seed_delivered_at = " .
                    ($deliveryStatus === 'delivered' ? 'NOW()' : 'NULL') . ",
                    seed_delivery_note = ?
                WHERE id = ?
            ";

            $stmt = $conn->prepare($sql);

            $stmt->bind_param(
                'ssi',
                $deliveryStatus,
                $note,
                $id
            );

            if ($stmt->execute()) {
                flash('Seed delivery status updated successfully.');
            } else {
                flash('Unable to update seed delivery status.', 'error');
            }

            $stmt->close();
        }

    } else {

        flash('Invalid administrative action.', 'error');
    }

    redirect_admin();
}

/*
|--------------------------------------------------------------------------
| FILTERS AND PAGINATION
|--------------------------------------------------------------------------
*/

$search = trim((string)($_GET['q'] ?? ''));
$district = trim((string)($_GET['district'] ?? ''));
$review = trim((string)($_GET['review'] ?? ''));
$delivery = trim((string)($_GET['delivery'] ?? ''));

$page = max(1, (int)($_GET['page'] ?? 1));
$perPage = 20;

$where = [];
$types = '';
$params = [];

if ($search !== '') {
    $where[] = "(
        application_no LIKE ?
        OR farmer_name LIKE ?
        OR id_card_number LIKE ?
    )";

    $like = '%' . $search . '%';

    $types .= 'sss';
    array_push($params, $like, $like, $like);
}

if ($district !== '') {
    $where[] = 'district = ?';
    $types .= 's';
    $params[] = $district;
}

if (in_array($review, ['pending', 'verified', 'rejected'], true)) {
    $where[] = 'document_review_status = ?';
    $types .= 's';
    $params[] = $review;
}

if (in_array($delivery, ['pending', 'delivered'], true)) {
    $where[] = 'seed_delivery_status = ?';
    $types .= 's';
    $params[] = $delivery;
}

$whereSql = $where
    ? ' WHERE ' . implode(' AND ', $where)
    : '';

$countStmt = $conn->prepare(
    "SELECT COUNT(*) AS total
     FROM seed_booking_applications
     $whereSql"
);

if ($types !== '') {
    $countStmt->bind_param($types, ...$params);
}

$countStmt->execute();

$totalApplications = (int)$countStmt
    ->get_result()
    ->fetch_assoc()['total'];

$countStmt->close();

$totalPages = max(1, (int)ceil($totalApplications / $perPage));
$page = min($page, $totalPages);
$offset = ($page - 1) * $perPage;

$sql = "
    SELECT
        id,
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
        identity_document_path,
        land_record_path,
        application_status,
        document_review_status,
        document_review_note,
        reviewed_at,
        submitted_at,
        seed_delivery_status,
        seed_delivered_at,
        seed_delivery_note
    FROM seed_booking_applications
    $whereSql
    ORDER BY submitted_at DESC, id DESC
    LIMIT ? OFFSET ?
";

$stmt = $conn->prepare($sql);

$listTypes = $types . 'ii';
$listParams = $params;
$listParams[] = $perPage;
$listParams[] = $offset;

$stmt->bind_param($listTypes, ...$listParams);
$stmt->execute();

$applications = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

$stmt->close();

/*
|--------------------------------------------------------------------------
| DASHBOARD STATISTICS
|--------------------------------------------------------------------------
*/

$statsResult = $conn->query("
    SELECT
        COUNT(*) AS total,
        COALESCE(SUM(document_review_status = 'pending'), 0) AS pending_review,
        COALESCE(SUM(document_review_status = 'verified'), 0) AS verified,
        COALESCE(SUM(document_review_status = 'rejected'), 0) AS rejected,
        COALESCE(SUM(seed_delivery_status = 'pending'), 0) AS pending_delivery,
        COALESCE(SUM(seed_delivery_status = 'delivered'), 0) AS delivered
    FROM seed_booking_applications
");

$stats = $statsResult->fetch_assoc();

$message = $_SESSION['seed_admin_message'] ?? null;
unset($_SESSION['seed_admin_message']);

$baseQuery = $_GET;
unset($baseQuery['page']);

?>

<!DOCTYPE html>
<html lang="en">

<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">

<title>Seed Booking Administration</title>

<style>

:root {
    --green: #17663d;
    --green-dark: #10472c;
    --green-soft: #e8f3eb;
    --background: #f3f6f4;
    --surface: #ffffff;
    --border: #e2e9e4;
    --text: #26372d;
    --muted: #78867d;
    --red: #a63b32;
    --shadow: 0 4px 18px rgba(26, 62, 39, .045);
}

* {
    box-sizing: border-box;
}

body {
    margin: 0;
    background: var(--background);
    color: var(--text);
    font-family: "Segoe UI", Arial, sans-serif;
    font-size: 14px;
}

a {
    text-decoration: none;
}

button,
input,
select,
textarea {
    font: inherit;
}

.layout {
    min-height: 100vh;
    display: flex;
}

.sidebar {
    width: 250px;
    flex-shrink: 0;
    background: #143e29;
    color: #fff;
    padding: 25px 17px;
    display: flex;
    flex-direction: column;
}

.brand {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 0 9px 27px;
    border-bottom: 1px solid rgba(255,255,255,.12);
}

.brand-icon {
    width: 43px;
    height: 43px;
    border-radius: 11px;
    display: grid;
    place-items: center;
    background: rgba(255,255,255,.13);
    font-size: 21px;
    font-weight: 800;
}

.brand strong {
    display: block;
    font-size: 14px;
}

.brand small {
    display: block;
    margin-top: 4px;
    color: #b7d4c0;
    font-size: 11px;
}

.nav-label {
    margin: 27px 10px 10px;
    color: #9cbdab;
    font-size: 10px;
    font-weight: 800;
    letter-spacing: 1.5px;
    text-transform: uppercase;
}

.nav-link {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 12px;
    border-radius: 8px;
    color: #d8e9dd;
    margin-bottom: 5px;
    font-size: 13px;
}

.nav-link.active,
.nav-link:hover {
    background: rgba(255,255,255,.13);
    color: white;
}

.nav-icon {
    width: 20px;
    text-align: center;
    font-size: 16px;
}

.sidebar-footer {
    margin-top: auto;
    padding: 16px 9px 0;
    border-top: 1px solid rgba(255,255,255,.12);
    color: #b7d4c0;
    font-size: 11px;
    line-height: 1.7;
}

.main {
    flex: 1;
    min-width: 0;
}

.topbar {
    min-height: 76px;
    padding: 15px 35px;
    background: #fff;
    border-bottom: 1px solid var(--border);
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 15px;
}

.topbar h1 {
    margin: 0;
    font-size: 20px;
    color: #203b2b;
}

.topbar p {
    margin: 5px 0 0;
    color: var(--muted);
    font-size: 12px;
}

.user-area {
    display: flex;
    align-items: center;
    gap: 12px;
}

.avatar {
    width: 38px;
    height: 38px;
    border-radius: 50%;
    display: grid;
    place-items: center;
    background: var(--green-soft);
    color: var(--green);
    font-weight: 800;
}

.user-info strong {
    display: block;
    font-size: 12px;
}

.user-info small {
    display: block;
    margin-top: 3px;
    color: var(--muted);
}

.logout-form {
    margin-left: 12px;
}

.logout-btn {
    border: 1px solid var(--border);
    background: white;
    color: #53665a;
    padding: 9px 12px;
    border-radius: 7px;
    cursor: pointer;
    font-size: 12px;
}

.logout-btn:hover {
    background: #fff1ef;
    color: var(--red);
}

.content {
    max-width: 1500px;
    padding: 28px 35px 45px;
    margin: auto;
}

.page-heading {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 15px;
    margin-bottom: 23px;
}

.page-heading h2 {
    margin: 0;
    font-size: 19px;
}

.page-heading p {
    color: var(--muted);
    margin: 6px 0 0;
    font-size: 12px;
}

.stats {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 15px;
}

.stat-card {
    background: var(--surface);
    border: 1px solid var(--border);
    border-radius: 11px;
    padding: 19px;
    box-shadow: var(--shadow);
}

.stat-top {
    display: flex;
    align-items: center;
    justify-content: space-between;
    color: var(--muted);
    font-size: 12px;
}

.stat-icon {
    width: 34px;
    height: 34px;
    display: grid;
    place-items: center;
    border-radius: 9px;
    background: var(--green-soft);
    color: var(--green);
    font-size: 16px;
}

.stat-card strong {
    display: block;
    font-size: 27px;
    margin-top: 13px;
    color: #243b2d;
}

.stat-card small {
    display: block;
    color: var(--muted);
    margin-top: 5px;
    font-size: 11px;
}

.panel {
    background: white;
    border: 1px solid var(--border);
    border-radius: 11px;
    padding: 19px;
    margin-top: 20px;
    box-shadow: var(--shadow);
}

.panel-title {
    margin: 0 0 16px;
    font-size: 14px;
}

.filters {
    display: grid;
    grid-template-columns: 2fr 1fr 1fr 1fr auto;
    align-items: end;
    gap: 12px;
}

.field label {
    display: block;
    font-size: 11px;
    font-weight: 750;
    color: #53645a;
    margin-bottom: 7px;
}

.field input,
.field select {
    width: 100%;
    padding: 11px;
    border: 1px solid #dce5de;
    border-radius: 7px;
    outline: none;
    background: #fff;
    color: var(--text);
}

.field input:focus,
.field select:focus {
    border-color: #4a9567;
    box-shadow: 0 0 0 3px rgba(74,149,103,.09);
}

.btn {
    display: inline-flex;
    justify-content: center;
    align-items: center;
    padding: 10px 13px;
    border: 0;
    border-radius: 7px;
    background: var(--green);
    color: white;
    font-weight: 700;
    font-size: 12px;
    cursor: pointer;
}

.btn:hover {
    background: var(--green-dark);
}

.btn.light {
    background: #eaf3ec;
    color: var(--green);
}

.btn.light:hover {
    background: #d9ebde;
}

.btn.red {
    background: #a63b32;
}

.btn.gray {
    background: #68766d;
}

.btn.small {
    padding: 8px 10px;
    font-size: 11px;
}

.message {
    padding: 13px 16px;
    border-radius: 8px;
    margin: 18px 0;
    font-size: 13px;
}

.message.success {
    background: #e8f5eb;
    color: #17663d;
    border: 1px solid #d0e9d6;
}

.message.error {
    background: #fff0ee;
    color: #a13730;
    border: 1px solid #f1d4d0;
}

.results-heading {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin: 25px 0 12px;
    gap: 10px;
}

.results-heading h3 {
    margin: 0;
    font-size: 15px;
}

.results-heading span {
    color: var(--muted);
    font-size: 12px;
}

.application {
    background: white;
    border: 1px solid var(--border);
    border-radius: 12px;
    margin-bottom: 16px;
    box-shadow: var(--shadow);
    overflow: hidden;
}

.application-header {
    padding: 17px 19px;
    display: flex;
    justify-content: space-between;
    gap: 12px;
    flex-wrap: wrap;
    border-bottom: 1px solid var(--border);
}

.application-header h3 {
    margin: 0;
    font-size: 15px;
    color: var(--green);
}

.application-header small {
    display: block;
    margin-top: 6px;
    color: var(--muted);
    font-size: 11px;
}

.status-group {
    display: flex;
    gap: 7px;
    align-items: center;
    flex-wrap: wrap;
}

.pill {
    display: inline-block;
    padding: 6px 9px;
    border-radius: 20px;
    background: #eff2f0;
    color: #53645a;
    font-size: 10px;
    font-weight: 750;
}

.pill.good {
    background: #e4f5e8;
    color: #17663d;
}

.pill.bad {
    background: #fff0ed;
    color: #a33b32;
}

.pill.pending {
    background: #fff5df;
    color: #946615;
}

.application-body {
    padding: 18px 19px;
}

.details {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 19px 15px;
}

.detail small {
    display: block;
    color: var(--muted);
    font-size: 10px;
    margin-bottom: 6px;
}

.detail strong {
    display: block;
    font-size: 12px;
    overflow-wrap: anywhere;
    line-height: 1.5;
}

.section-label {
    margin: 22px 0 10px;
    font-size: 11px;
    font-weight: 800;
    color: #516258;
    text-transform: uppercase;
    letter-spacing: .6px;
}

.documents {
    display: flex;
    flex-wrap: wrap;
    gap: 9px;
}

.no-document {
    color: #9a7860;
    font-size: 11px;
    padding: 8px 0;
}

.actions {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 13px;
    margin-top: 19px;
}

.action-box {
    background: #f8faf8;
    border: 1px solid var(--border);
    border-radius: 9px;
    padding: 15px;
}

.action-box h4 {
    margin: 0 0 12px;
    font-size: 12px;
}

.action-box textarea {
    width: 100%;
    min-height: 65px;
    padding: 10px;
    border: 1px solid #dce5de;
    border-radius: 7px;
    resize: vertical;
    outline: none;
    font-size: 12px;
}

.action-box textarea:focus {
    border-color: #4a9567;
}

.action-buttons {
    display: flex;
    flex-wrap: wrap;
    gap: 8px;
    margin-top: 9px;
}

.action-meta {
    color: var(--muted);
    font-size: 10px;
    margin-top: 11px;
}

.empty {
    background: white;
    border: 1px dashed #cbd9ce;
    padding: 45px 20px;
    text-align: center;
    border-radius: 11px;
    color: var(--muted);
}

.empty strong {
    display: block;
    color: #3c5645;
    margin-bottom: 7px;
}

.pagination {
    display: flex;
    justify-content: center;
    align-items: center;
    gap: 13px;
    margin: 25px 0;
}

.pagination a {
    background: white;
    border: 1px solid var(--border);
    padding: 9px 13px;
    border-radius: 7px;
    color: var(--green);
    font-size: 12px;
}

.pagination span {
    color: var(--muted);
    font-size: 12px;
}

@media(max-width: 1100px) {
    .sidebar { width: 210px; }
    .stats { grid-template-columns: repeat(2, 1fr); }
    .filters { grid-template-columns: repeat(2, 1fr); }
    .details { grid-template-columns: repeat(2, 1fr); }
}

@media(max-width: 760px) {
    .layout { display: block; }
    .sidebar { display: none; }
    .topbar { padding: 15px 18px; }
    .content { padding: 22px 15px; }
    .actions { grid-template-columns: 1fr; }
}

@media(max-width: 480px) {
    .stats { grid-template-columns: 1fr 1fr; gap: 9px; }
    .stat-card { padding: 13px; }
    .stat-card strong { font-size: 23px; }
    .filters { grid-template-columns: 1fr; }
    .details { grid-template-columns: 1fr 1fr; gap: 15px 10px; }
    .user-info { display: none; }
    .topbar h1 { font-size: 16px; }
    .application-header { padding: 14px; }
    .application-body { padding: 14px; }
}

</style>

</head>

<body>

<div class="layout">

    <aside class="sidebar">

        <div class="brand">
            <div class="brand-icon">A</div>
            <div>
                <strong>AGRICULTURE</strong>
                <small>Seed Booking Portal</small>
            </div>
        </div>

        <div class="nav-label">Administration</div>

        <a href="admin.php" class="nav-link active">
            <span class="nav-icon">▦</span>
            Dashboard
        </a>

        <a href="#applications" class="nav-link">
            <span class="nav-icon">▤</span>
            Applications
        </a>

        <a href="#applications" class="nav-link">
            <span class="nav-icon">✓</span>
            Document Review
        </a>

        <a href="#applications" class="nav-link">
            <span class="nav-icon">◷</span>
            Seed Distribution
        </a>

        <div class="sidebar-footer">
            Department of Agriculture<br>
            Andaman and Nicobar Islands<br><br>
            Official Administration Portal
        </div>

    </aside>

    <div class="main">

        <header class="topbar">

            <div>
                <h1>Administration Dashboard</h1>
                <p>Seed Booking Management System</p>
            </div>

            <div class="user-area">

                <div class="avatar">
                    <?= e(strtoupper(substr(
                        (string)($_SESSION['seed_admin_username'] ?? 'A'),
                        0,
                        1
                    ))) ?>
                </div>

                <div class="user-info">
                    <strong><?= e($_SESSION['seed_admin_username'] ?? 'Administrator') ?></strong>
                    <small>Administrator</small>
                </div>

                <form method="POST" class="logout-form">
                    <input
                        type="hidden"
                        name="csrf_token"
                        value="<?= e(csrf_token()) ?>"
                    >
                    <input type="hidden" name="action" value="logout">

                    <button class="logout-btn" type="submit">
                        Logout
                    </button>
                </form>

            </div>

        </header>

        <main class="content">

            <section class="page-heading">

                <div>
                    <h2>Overview</h2>
                    <p>Monitor applications, verify documents and manage seed delivery.</p>
                </div>

            </section>

            <section class="stats">

                <div class="stat-card">
                    <div class="stat-top">
                        Total Applications
                        <span class="stat-icon">▤</span>
                    </div>
                    <strong><?= (int)($stats['total'] ?? 0) ?></strong>
                    <small>All submitted applications</small>
                </div>

                <div class="stat-card">
                    <div class="stat-top">
                        Pending Verification
                        <span class="stat-icon">◷</span>
                    </div>
                    <strong><?= (int)($stats['pending_review'] ?? 0) ?></strong>
                    <small>Awaiting document review</small>
                </div>

                <div class="stat-card">
                    <div class="stat-top">
                        Documents Verified
                        <span class="stat-icon">✓</span>
                    </div>
                    <strong><?= (int)($stats['verified'] ?? 0) ?></strong>
                    <small>Successfully verified</small>
                </div>

                <div class="stat-card">
                    <div class="stat-top">
                        Seeds Delivered
                        <span class="stat-icon">◆</span>
                    </div>
                    <strong><?= (int)($stats['delivered'] ?? 0) ?></strong>
                    <small>Delivery marked completed</small>
                </div>

            </section>

            <?php if (is_array($message) && !empty($message['text'])): ?>
                <div class="message <?= ($message['type'] ?? '') === 'error' ? 'error' : 'success' ?>">
                    <?= e($message['text']) ?>
                </div>
            <?php endif; ?>

            <section class="panel">

                <h3 class="panel-title">Search & Filter Applications</h3>

                <form method="GET" class="filters">

                    <div class="field">
                        <label>Search</label>
                        <input
                            type="text"
                            name="q"
                            value="<?= e($search) ?>"
                            placeholder="Application number, farmer or ID"
                        >
                    </div>

                    <div class="field">
                        <label>District</label>
                        <select name="district">
                            <option value="">All Districts</option>

                            <?php foreach ([
                                'South Andaman',
                                'North & Middle Andaman',
                                'Nicobar'
                            ] as $d): ?>

                                <option
                                    value="<?= e($d) ?>"
                                    <?= $district === $d ? 'selected' : '' ?>
                                >
                                    <?= e($d) ?>
                                </option>

                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="field">
                        <label>Document Review</label>
                        <select name="review">
                            <option value="">All Statuses</option>

                            <?php foreach (['pending', 'verified', 'rejected'] as $v): ?>
                                <option
                                    value="<?= e($v) ?>"
                                    <?= $review === $v ? 'selected' : '' ?>
                                >
                                    <?= e(status_text($v)) ?>
                                </option>
                            <?php endforeach; ?>

                        </select>
                    </div>

                    <div class="field">
                        <label>Seed Delivery</label>
                        <select name="delivery">
                            <option value="">All Statuses</option>
                            <option value="pending" <?= $delivery === 'pending' ? 'selected' : '' ?>>
                                Pending
                            </option>
                            <option value="delivered" <?= $delivery === 'delivered' ? 'selected' : '' ?>>
                                Delivered
                            </option>
                        </select>
                    </div>

                    <button type="submit" class="btn">
                        Apply Filters
                    </button>

                </form>

            </section>

            <div class="results-heading" id="applications">

                <h3>Submitted Applications</h3>

                <span>
                    Showing <?= count($applications) ?>
                    of <?= $totalApplications ?> records
                </span>

            </div>

            <?php if (!$applications): ?>

                <div class="empty">
                    <strong>No applications found</strong>
                    Try changing your search or filter criteria.
                </div>

            <?php endif; ?>

            <?php foreach ($applications as $app): ?>

                <article class="application">

                    <div class="application-header">

                        <div>
                            <h3><?= e($app['application_no']) ?></h3>
                            <small>
                                Submitted on
                                <?= e($app['submitted_at'] ?? 'Not recorded') ?>
                            </small>
                        </div>

                        <div class="status-group">

                            <span class="pill">
                                <?= e($app['application_status'] ?: 'Submitted') ?>
                            </span>

                            <span class="pill <?= $app['document_review_status'] === 'verified' ? 'good' : ($app['document_review_status'] === 'rejected' ? 'bad' : 'pending') ?>">
                                Documents:
                                <?= e(status_text($app['document_review_status'])) ?>
                            </span>

                            <span class="pill <?= $app['seed_delivery_status'] === 'delivered' ? 'good' : 'pending' ?>">
                                Seeds:
                                <?= e(status_text($app['seed_delivery_status'])) ?>
                            </span>

                        </div>

                    </div>

                    <div class="application-body">

                        <div class="details">

                            <div class="detail">
                                <small>Farmer Name</small>
                                <strong><?= e($app['farmer_name'] ?: '—') ?></strong>
                            </div>

                            <div class="detail">
                                <small>Farmer Category</small>
                                <strong><?= e(status_text($app['farmer_path'])) ?></strong>
                            </div>

                            <div class="detail">
                                <small>Identity Type</small>
                                <strong><?= e($app['id_card_type'] ?: '—') ?></strong>
                            </div>

                            <div class="detail">
                                <small>Identity Number</small>
                                <strong><?= e($app['id_card_number'] ?: '—') ?></strong>
                            </div>

                            <div class="detail">
                                <small>District</small>
                                <strong><?= e($app['district']) ?></strong>
                            </div>

                            <div class="detail">
                                <small>Booking Type</small>
                                <strong><?= e($app['booking_type']) ?></strong>
                            </div>

                            <div class="detail">
                                <small>Season</small>
                                <strong><?= e($app['season']) ?></strong>
                            </div>

                            <div class="detail">
                                <small>Crop / Item</small>
                                <strong><?= e($app['crop']) ?></strong>
                            </div>

                            <div class="detail" style="grid-column: 1 / -1;">
                                <small>Farmer Address</small>
                                <strong><?= e($app['farmer_location'] ?: '—') ?></strong>
                            </div>

                        </div>

                        <div class="section-label">Supporting Documents</div>

                        <div class="documents">

                            <?php if (!empty($app['identity_document_path'])): ?>

                                <a
                                    class="btn light"
                                    target="_blank"
                                    rel="noopener"
                                    href="admin.php?mode=document&id=<?= (int)$app['id'] ?>&type=identity"
                                >
                                    View Identity Document
                                </a>

                            <?php else: ?>

                                <span class="no-document">Identity document unavailable</span>

                            <?php endif; ?>

                            <?php if (!empty($app['land_record_path'])): ?>

                                <a
                                    class="btn light"
                                    target="_blank"
                                    rel="noopener"
                                    href="admin.php?mode=document&id=<?= (int)$app['id'] ?>&type=land"
                                >
                                    View Land Record
                                </a>

                            <?php else: ?>

                                <span class="no-document">Land record unavailable</span>

                            <?php endif; ?>

                        </div>

                        <div class="actions">

                            <form method="POST" class="action-box">

                                <h4>Document Verification</h4>

                                <input
                                    type="hidden"
                                    name="csrf_token"
                                    value="<?= e(csrf_token()) ?>"
                                >

                                <input
                                    type="hidden"
                                    name="application_id"
                                    value="<?= (int)$app['id'] ?>"
                                >

                                <input type="hidden" name="action" value="review">

                                <textarea
                                    name="note"
                                    maxlength="2000"
                                    placeholder="Enter verification remarks..."
                                ><?= e($app['document_review_note'] ?? '') ?></textarea>

                                <div class="action-buttons">

                                    <button
                                        type="submit"
                                        name="decision"
                                        value="verified"
                                        class="btn"
                                    >
                                        Verify Documents
                                    </button>

                                    <button
                                        type="submit"
                                        name="decision"
                                        value="rejected"
                                        class="btn red"
                                    >
                                        Reject Documents
                                    </button>

                                </div>

                                <?php if (!empty($app['reviewed_at'])): ?>
                                    <div class="action-meta">
                                        Last reviewed:
                                        <?= e($app['reviewed_at']) ?>
                                    </div>
                                <?php endif; ?>

                            </form>

                            <form
                                method="POST"
                                class="action-box"
                                onsubmit="return this.delivery_status.value !== 'delivered' || confirm('Confirm that seeds have physically been handed over to this farmer?')"
                            >

                                <h4>Seed Delivery Management</h4>

                                <input
                                    type="hidden"
                                    name="csrf_token"
                                    value="<?= e(csrf_token()) ?>"
                                >

                                <input
                                    type="hidden"
                                    name="application_id"
                                    value="<?= (int)$app['id'] ?>"
                                >

                                <input type="hidden" name="action" value="delivery">

                                <textarea
                                    name="note"
                                    maxlength="2000"
                                    placeholder="Enter delivery remarks or quantity..."
                                ><?= e($app['seed_delivery_note'] ?? '') ?></textarea>

                                <div class="action-buttons">

                                    <button
                                        type="submit"
                                        name="delivery_status"
                                        value="delivered"
                                        class="btn"
                                    >
                                        Mark as Delivered
                                    </button>

                                    <?php if ($app['seed_delivery_status'] === 'delivered'): ?>

                                        <button
                                            type="submit"
                                            name="delivery_status"
                                            value="pending"
                                            class="btn gray"
                                            onclick="return confirm('Reset delivery status to Pending?')"
                                        >
                                            Reset to Pending
                                        </button>

                                    <?php endif; ?>

                                </div>

                                <?php if (!empty($app['seed_delivered_at'])): ?>
                                    <div class="action-meta">
                                        Delivered on:
                                        <?= e($app['seed_delivered_at']) ?>
                                    </div>
                                <?php endif; ?>

                            </form>

                        </div>

                    </div>

                </article>

            <?php endforeach; ?>

            <?php if ($totalPages > 1): ?>

                <nav class="pagination">

                    <?php if ($page > 1): ?>
                        <a href="?<?= e(http_build_query(array_merge($baseQuery, ['page' => $page - 1]))) ?>">
                            Previous
                        </a>
                    <?php endif; ?>

                    <span>
                        Page <?= $page ?> of <?= $totalPages ?>
                    </span>

                    <?php if ($page < $totalPages): ?>
                        <a href="?<?= e(http_build_query(array_merge($baseQuery, ['page' => $page + 1]))) ?>">
                            Next
                        </a>
                    <?php endif; ?>

                </nav>

            <?php endif; ?>

        </main>

    </div>

</div>

</body>
</html>
