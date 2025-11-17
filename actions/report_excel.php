<?php
require_once __DIR__ . '/../config/db_config.php';
require_once __DIR__ . '/../vendor/autoload.php';

use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(dirname(__DIR__));
$dotenv->load();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Method not allowed']);
    exit;
}

// Get the url search queries
$faculty = $_GET['faculty'] ?? null;
$program = $_GET['program'] ?? null;
$major = $_GET['major'] ?? null;
$province = $_GET['province'] ?? null;
$academicYear = $_GET['academic-year'] ?? null;

// Build the WHERE clause
$whereClause = [];
$params = [];

if ($faculty) {
    $whereClause[] = 'faculty_program_major.faculty = :faculty';
    $params[':faculty'] = htmlspecialchars($faculty);
}
if ($program) {
    $whereClause[] = 'faculty_program_major.program = :program';
    $params[':program'] = htmlspecialchars($program);
}
if ($major) {
    $whereClause[] = 'faculty_program_major.major = :major';
    $params[':major'] = htmlspecialchars($major);
}
if ($province) {
    $whereClause[] = 'internship_stats.province = :province';
    $params[':province'] = htmlspecialchars($province);
}
if ($academicYear) {
    $whereClause[] = 'internship_stats.year = :academic_year';
    $params[':academic_year'] = htmlspecialchars($academicYear);
}

$whereSql = '';
if (!empty($whereClause)) {
    $whereSql = 'WHERE ' . implode(' AND ', $whereClause);
}

// Fetch data from the database
$sql = "
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
        internship_stats.score
    FROM internship_stats
    LEFT JOIN faculty_program_major ON internship_stats.major_id = faculty_program_major.id
    $whereSql
    ORDER BY internship_stats.id DESC
";

$stmt = $conn->prepare($sql);
$stmt->execute($params);
$data = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Set headers to force the browser to download as a CSV file
header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename=internship_report.csv');

// Write the csv file from the browser
$output = fopen('php://output', 'w');

fwrite($output, "\xEF\xBB\xBF");

// CSV column names
fputcsv($output, ['บริษัท', 'จังหวัด', 'คณะ', 'หลักสูตร', 'สาขา', 'ปีการศึกษา', 'จำนวนที่รับ', 'MOU', 'ข้อมูลการติดต่อ', 'คะแนน']);

// Write the data to the CSV file
foreach ($data as $row) {
    fputcsv($output, [
        $row['organization'],
        $row['province'],
        $row['faculty'],
        $row['program'],
        $row['major'],
        $row['year'],
        $row['total_student'],
        $row['mou_status'],
        $row['contact'],
        $row['score'],
    ]);
}

fclose($output);
exit;
