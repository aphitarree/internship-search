
<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/../../vendor/autoload.php';
require_once __DIR__ . '/../../config/db_config.php';

use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(dirname(dirname(__DIR__)));
$dotenv->load();

header('Content-Type: application/json; charset=utf-8');

// DataTables ส่งมาได้ทั้ง GET / POST (เราใช้ POST ใน JS)
$request = $_POST ?: $_GET;

// พารามิเตอร์หลักของ DataTables
$draw   = isset($request['draw']) ? (int)$request['draw'] : 0;
$start  = isset($request['start']) ? (int)$request['start'] : 0;
$length = isset($request['length']) ? (int)$request['length'] : 10;

$searchValue = $request['search']['value'] ?? '';

// mapping index column -> field จริงใน DB
$columnsMap = [
    0 => 'internship_stats.id',              // NO. (ไม่ค่อยใช้ sort)
    1 => 'internship_stats.organization',
    2 => 'internship_stats.province',
    3 => 'faculty_program_major.faculty',
    4 => 'faculty_program_major.program',
    5 => 'faculty_program_major.major',
    6 => 'internship_stats.year',
    7 => 'internship_stats.total_student',
    8 => 'internship_stats.contact',
    9 => 'internship_stats.score',
    10 => 'internship_stats.id',             // ปุ่มจัดการ
];

$orderColumnIndex = isset($request['order'][0]['column']) ? (int)$request['order'][0]['column'] : 0;
$orderDir         = isset($request['order'][0]['dir']) && strtolower($request['order'][0]['dir']) === 'desc'
    ? 'DESC'
    : 'ASC';

$orderColumn = $columnsMap[$orderColumnIndex] ?? 'internship_stats.id';

// base FROM ใช้ร่วมกัน
$baseFrom = "
    FROM internship_stats
    INNER JOIN faculty_program_major
        ON internship_stats.major_id = faculty_program_major.id
";

// -------- recordsTotal (ไม่กรอง search) --------
$sqlTotal = "SELECT COUNT(*) " . $baseFrom;
$stmtTotal = $conn->query($sqlTotal);
$recordsTotal = (int)$stmtTotal->fetchColumn();

// -------- เงื่อนไข search --------
$where = '';
$params = [];

if ($searchValue !== '') {
    $where = "
        WHERE
            internship_stats.organization    LIKE :search
            OR internship_stats.province     LIKE :search
            OR internship_stats.contact      LIKE :search
            OR internship_stats.score        LIKE :search
            OR internship_stats.year         LIKE :search
            OR faculty_program_major.faculty LIKE :search
            OR faculty_program_major.program LIKE :search
            OR faculty_program_major.major   LIKE :search
    ";
    $params[':search'] = '%' . $searchValue . '%';
}

// -------- recordsFiltered (หลัง search) --------
$sqlFiltered = "SELECT COUNT(*) " . $baseFrom . ' ' . $where;
$stmtFiltered = $conn->prepare($sqlFiltered);

foreach ($params as $key => $value) {
    $stmtFiltered->bindValue($key, $value, PDO::PARAM_STR);
}

$stmtFiltered->execute();
$recordsFiltered = (int)$stmtFiltered->fetchColumn();

// -------- ดึงข้อมูลจริง --------
$sqlData = "
    SELECT
        internship_stats.id,
        faculty_program_major.faculty,
        faculty_program_major.program,
        faculty_program_major.major,
        internship_stats.organization,
        internship_stats.province,
        internship_stats.contact,
        internship_stats.score,
        internship_stats.year,
        internship_stats.total_student
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
        'id'            => (int)$row['id'],
        'organization'  => $row['organization'],
        'province'      => $row['province'],
        'faculty'       => $row['faculty'],
        'program'       => $row['program'],
        'major'         => $row['major'],
        'year'          => $row['year'],
        'total_student' => $row['total_student'],
        'contact'       => $row['contact'],
        'score'         => $row['score'],
    ];
}

// -------- ส่งกลับ format DataTables --------
echo json_encode([
    'draw'            => $draw,
    'recordsTotal'    => $recordsTotal,
    'recordsFiltered' => $recordsFiltered,
    'data'            => $data,
], JSON_UNESCAPED_UNICODE);
exit;
