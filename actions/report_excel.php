<?php
require_once __DIR__ . '/../config/db_config.php';
require_once __DIR__ . '/../vendor/autoload.php';

use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(dirname(__DIR__));
$dotenv->load();

// รับตัวกรองจาก query string
$faculty = $_GET['faculty'] ?? null;
$program = $_GET['program'] ?? null;
$major = $_GET['major'] ?? null;
$province = $_GET['province'] ?? null;
$academicYear = $_GET['academic-year'] ?? null;

// สร้าง WHERE clause เหมือนใน index.php
$whereClause = [];
$params = [];

if ($faculty) {
    $whereClause[] = 'fpm.faculty = :faculty';
    $params[':faculty'] = htmlspecialchars($faculty);
}
if ($program) {
    $whereClause[] = 'fpm.program = :program';
    $params[':program'] = htmlspecialchars($program);
}
if ($major) {
    $whereClause[] = 'fpm.major = :major';
    $params[':major'] = htmlspecialchars($major);
}
if ($province) {
    $whereClause[] = 'stats.province = :province';
    $params[':province'] = htmlspecialchars($province);
}
if ($academicYear) {
    $whereClause[] = 'stats.year = :academic_year';
    $params[':academic_year'] = htmlspecialchars($academicYear);
}

$whereSql = '';
if (!empty($whereClause)) {
    $whereSql = 'WHERE ' . implode(' AND ', $whereClause);
}

// ดึงข้อมูลจากฐานข้อมูล
$sql = "
    SELECT
        stats.organization AS company_name,
        stats.province,
        stats.position AS job_title,
        fpm.faculty AS faculty_name,
        fpm.program AS program_name,
        fpm.major AS major_name,
        stats.year AS academic_year,
        stats.total_student AS internship_count
    FROM internship_stats stats
    LEFT JOIN faculty_program_major fpm ON stats.major_id = fpm.id
    $whereSql
    ORDER BY stats.id DESC
";

$stmt = $conn->prepare($sql);
$stmt->execute($params);
$data = $stmt->fetchAll(PDO::FETCH_ASSOC);

// ตั้งค่าหัวไฟล์ให้เบราว์เซอร์ดาวน์โหลดเป็น CSV
header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename=internship_report.csv');

// เขียน CSV ออกไป
$output = fopen('php://output', 'w');

fwrite($output, "\xEF\xBB\xBF");
// เขียนหัวตาราง
fputcsv($output, ['บริษัท', 'จังหวัด', 'ตำแหน่ง', 'คณะ', 'หลักสูตร', 'สาขา', 'ปีการศึกษา', 'จำนวนที่รับ']);

// เขียนข้อมูล
foreach ($data as $row) {
    fputcsv($output, [
        $row['company_name'],
        $row['province'],
        $row['job_title'],
        $row['faculty_name'],
        $row['program_name'],
        $row['major_name'],
        $row['academic_year'],
        $row['internship_count']
    ]);
}

fclose($output);
exit;
