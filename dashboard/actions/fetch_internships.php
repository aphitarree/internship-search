
<?php
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

// Argument that sent form the datatables
$draw   = isset($request['draw']) ? (int)$request['draw'] : 0;
$start  = isset($request['start']) ? (int)$request['start'] : 0;
$length = isset($request['length']) ? (int)$request['length'] : 10;

// Search query from user
$searchValue = $request['search']['value'] ?? '';

// Map index to real column (field) in the database
$columnsMap = [
    0 => 'internship_stats.id',
    1 => 'internship_stats.organization',
    2 => 'internship_stats.province',
    3 => 'faculty_program_major.faculty',
    4 => 'faculty_program_major.program',
    5 => 'faculty_program_major.major',
    6 => 'internship_stats.year',
    7 => 'internship_stats.total_student',
    8 => 'internship_stats.mou_status',
    9 => 'internship_stats.contact',
    10 => 'internship_stats.score',
    10 => 'internship_stats.created_at',
];

$orderColumnIndex = isset($request['order'][0]['column']) ? (int)$request['order'][0]['column'] : 0;
$orderDir = null;
if (isset($request['order'][0]['dir']) && strtolower($request['order'][0]['dir']) === 'desc') {
    $orderDir = 'DESC';
} else {
    $orderDir = 'ASC';
}

$orderColumn = $columnsMap[$orderColumnIndex] ?? 'internship_stats.id';

$baseFrom = "
    FROM internship_stats
    INNER JOIN faculty_program_major
        ON internship_stats.major_id = faculty_program_major.id
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
            internship_stats.organization LIKE :search
            OR internship_stats.province LIKE :search
            OR faculty_program_major.faculty LIKE :search
            OR faculty_program_major.program LIKE :search
            OR faculty_program_major.major LIKE :search
            OR internship_stats.contact LIKE :search
            OR CAST(internship_stats.total_student AS CHAR)  LIKE :search
            OR internship_stats.year LIKE :search
            OR CAST(internship_stats.year AS CHAR)  LIKE :search
            OR CAST(internship_stats.score AS CHAR) LIKE :search
            OR CAST(internship_stats.created_at AS CHAR) LIKE :search
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
        internship_stats.id,
        internship_stats.organization,
        internship_stats.province,
        faculty_program_major.faculty,
        faculty_program_major.program,
        faculty_program_major.major,
        internship_stats.year,
        internship_stats.total_student,
        internship_stats.mou_status,
        internship_stats.contact,
        internship_stats.score,
        internship_stats.created_at
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
        'organization' => $row['organization'],
        'province' => $row['province'],
        'faculty' => $row['faculty'],
        'program' => $row['program'],
        'major' => $row['major'],
        'year' => $row['year'],
        'total_student' => $row['total_student'],
        'mou_status' => $row['mou_status'],
        'contact' => $row['contact'],
        'score' => $row['score'],
        'created_at' => $row['created_at'],
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
