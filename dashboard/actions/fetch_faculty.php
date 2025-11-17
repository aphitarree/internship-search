<?php
// actions/fetch_users.php
require_once __DIR__ . '/../../vendor/autoload.php';
require_once __DIR__ . '/../../config/db_config.php';

header('Content-Type: application/json; charset=utf-8');

$request = $_POST ?: $_GET;

// DataTables params
$draw   = isset($request['draw']) ? (int)$request['draw'] : 0;
$start  = isset($request['start']) ? (int)$request['start'] : 0;
$length = isset($request['length']) ? (int)$request['length'] : 10;
$searchValue = $request['search']['value'] ?? '';

// map columns
$columnsMap = [
    0 => 'id',
    1 => 'faculty',
    2 => 'program',
    3 => 'major',
];

$orderColumnIndex = isset($request['order'][0]['column']) ? (int)$request['order'][0]['column'] : 0;
$orderDir = (isset($request['order'][0]['dir']) && strtolower($request['order'][0]['dir']) === 'desc') ? 'DESC' : 'ASC';
$orderColumn = $columnsMap[$orderColumnIndex] ?? 'id';

// base
$baseFrom = "FROM faculty_program_major";

// total
try {
    $sqlTotal = "SELECT COUNT(*) $baseFrom";
    $recordsTotal = (int)$conn->query($sqlTotal)->fetchColumn();
} catch (Exception $e) {
    echo json_encode(['error' => $e->getMessage()]);
    exit;
}

// where
$where = '';
$params = [];
if ($searchValue !== '') {
    $where = " WHERE faculty LIKE :search OR program LIKE :search OR major LIKE :search ";
    $params[':search'] = '%' . $searchValue . '%';
}

// filtered count
$sqlFiltered = "SELECT COUNT(*) $baseFrom $where";
$stmtFiltered = $conn->prepare($sqlFiltered);
foreach ($params as $k => $v) $stmtFiltered->bindValue($k, $v, PDO::PARAM_STR);
$stmtFiltered->execute();
$recordsFiltered = (int)$stmtFiltered->fetchColumn();

// data
$sqlData = "SELECT id, faculty, program, major $baseFrom $where ORDER BY $orderColumn $orderDir LIMIT :start, :length";
$stmt = $conn->prepare($sqlData);
foreach ($params as $k => $v) $stmt->bindValue($k, $v, PDO::PARAM_STR);
$stmt->bindValue(':start', $start, PDO::PARAM_INT);
$stmt->bindValue(':length', $length, PDO::PARAM_INT);
$stmt->execute();

$data = [];
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $data[] = [
        'id' => (int)$row['id'],
        'faculty' => $row['faculty'],
        'program' => $row['program'],
        'major' => $row['major'],
    ];
}

echo json_encode([
    'draw' => $draw,
    'recordsTotal' => $recordsTotal,
    'recordsFiltered' => $recordsFiltered,
    'data' => $data,
], JSON_UNESCAPED_UNICODE);
exit;
