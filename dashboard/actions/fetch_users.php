<?php
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../../vendor/autoload.php';
require_once __DIR__ . '/../../config/db_config.php';

use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(dirname(dirname(__DIR__)));
$dotenv->load();

header('Content-Type: application/json; charset=utf-8');

if (!empty($_POST)) {
    $request = $_POST;
} else {
    $request = $_GET;
}

// พารามิเตอร์หลักของ DataTables
$draw   = isset($request['draw']) ? (int)$request['draw'] : 0;
$start  = isset($request['start']) ? (int)$request['start'] : 0;
$length = isset($request['length']) ? (int)$request['length'] : 10;

$searchValue = $request['search']['value'] ?? '';

// Map index to real column (field) in the database
$columnsMap = [
    0 => 'user.id',
    1 => 'user.email',
    2 => 'user.username',
];

$orderColumnIndex = isset($request['order'][0]['column']) ? (int)$request['order'][0]['column'] : 0;
$orderDir = null;
if (isset($request['order'][0]['dir']) && strtolower($request['order'][0]['dir']) === 'desc') {
    $orderDir = 'DESC';
} else {
    $orderDir = 'ASC';
}

$orderColumn = $columnsMap[$orderColumnIndex] ?? 'user.id';

$baseFrom = "
    FROM user
";

// Find total number of all records for display
$sqlTotal = "SELECT COUNT(*) " . $baseFrom;
$stmtTotal = $conn->query($sqlTotal);
$recordsTotal = (int)$stmtTotal->fetchColumn();

// Filter by conditions
$where = '';
$params = [];

if ($searchValue !== '') {
    $where = "
        WHERE
            user.email LIKE :search
            OR user.username LIKE :search
            OR user.role LIKE :search
    ";
    $params[':search'] = '%' . $searchValue . '%';
}

// Find total number of filtered records for display
$sqlFiltered = "SELECT COUNT(*) " . $baseFrom . ' ' . $where;
$stmtFiltered = $conn->prepare($sqlFiltered);

foreach ($params as $key => $value) {
    $stmtFiltered->bindValue($key, $value, PDO::PARAM_STR);
}

$stmtFiltered->execute();
$recordsFiltered = (int)$stmtFiltered->fetchColumn();

// Fetch the data
$sqlData = "
    SELECT
        user.id,
        user.email,
        user.username,
        user.role
    " . $baseFrom . ' ' . $where . "
    ORDER BY $orderColumn $orderDir
    LIMIT :start, :length
";

$stmtData = $conn->prepare($sqlData);

foreach ($params as $key => $value) {
    $stmtData->bindValue($key, $value, PDO::PARAM_STR);
}

$stmtData->bindValue(':start',  $start,  PDO::PARAM_INT);
$stmtData->bindValue(':length', $length, PDO::PARAM_INT);
$stmtData->execute();

$data = [];
while ($row = $stmtData->fetch(PDO::FETCH_ASSOC)) {
    $data[] = [
        'id' => (int)$row['id'],
        'email' => $row['email'],
        'username' => $row['username'],
        'role' => $row['role'],
    ];
}

// Return the data
echo json_encode([
    'draw'            => $draw,
    'recordsTotal'    => $recordsTotal,
    'recordsFiltered' => $recordsFiltered,
    'data'            => $data,
], JSON_UNESCAPED_UNICODE);
exit;
