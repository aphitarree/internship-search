<?php
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../config/db_config.php';

use Dotenv\Dotenv;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Fill;

// Load ENV
$dotenv = Dotenv::createImmutable(dirname(__DIR__));
$dotenv->load();

// Get queries from URL
$faculty = $_GET['faculty'] ?? null;
$program = $_GET['program'] ?? null;
$major = $_GET['major'] ?? null;
$province = $_GET['province'] ?? null;
$academicYear = $_GET['academic-year'] ?? null;

// Build WHERE clause
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

$whereSql = !empty($whereClause)
    ? 'WHERE ' . implode(' AND ', $whereClause)
    : '';

// Pull data
$sql = "
    SELECT
        internship_stats.organization,
        internship_stats.province,
        faculty_program_major.faculty,
        faculty_program_major.program,
        faculty_program_major.major,
        internship_stats.year,
        internship_stats.affiliation,
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

// Create new Spreadsheet
$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();

// --------------------------------------------------
// 1) TITLE ROW (รายงาน)
// --------------------------------------------------
$title = 'รายงานฐานข้อมูลเครือข่ายความร่วมมือในการฝึกงานนักศึกษา มหาวิทยาลัยสวนดุสิต';

$sheet->setCellValue('A1', $title);

// Merge A1 → K1 (11 columns)
$sheet->mergeCells('A1:K1');

// Style Title
$sheet->getStyle('A1')->getFont()->setBold(true)->setSize(16);
$sheet->getStyle('A1')->getAlignment()->setHorizontal('center');

// --------------------------------------------------
// 2) COLUMN HEADERS
// --------------------------------------------------
$headers = [
    'บริษัท',
    'จังหวัด',
    'คณะ',
    'หลักสูตร',
    'สาขา',
    'ปีการศึกษา',
    'สังกัด',
    'จำนวนที่รับ',
    'MOU',
    'ข้อมูลการติดต่อ',
    'คะแนน'
];

$sheet->fromArray($headers, NULL, 'A2');

// Style header row
$sheet->getStyle('A2:K2')->getFont()->setBold(true);
$sheet->getStyle('A2:K2')->getFill()->setFillType(Fill::FILL_SOLID)
    ->getStartColor()->setARGB('FFE2E8F0');

$sheet->fromArray($data, NULL, 'A3');

// Auto-size columns B-K
foreach (range('B', 'K') as $col) {
    $sheet->getColumnDimension($col)->setAutoSize(true);
}

// Fix specific column
$sheet->getColumnDimension('A')->setAutoSize(false);
$sheet->getColumnDimension('A')->setWidth(50);
$sheet->getColumnDimension('E')->setAutoSize(false);
$sheet->getColumnDimension('E')->setWidth(40);

header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment; filename="internship_report.xlsx"');
header('Cache-Control: max-age=0');

$writer = new Xlsx($spreadsheet);
$writer->save('php://output');
exit;
