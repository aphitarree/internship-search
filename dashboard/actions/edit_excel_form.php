<?php
session_start();
require_once __DIR__ . '/../../vendor/autoload.php';
require_once __DIR__ . '/../../config/db_config.php';

use Dotenv\Dotenv;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

$dotenv = Dotenv::createImmutable(dirname(dirname(__DIR__)));
$dotenv->load();

$baseUrl = $_ENV['BASE_URL'] ?? '';

// Create spreadsheet
$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();

// Excel header columns
$headers = [
    'หน่วยงาน',
    'จังหวัด',
    'คณะ / โรงเรียน',
    'หลักสูตร',
    'สาขาวิชา',
    'ปีการศึกษา',
    'สังกัด',
    'จำนวนที่รับ',
    'MOU',
    'ข้อมูลการติดต่อ',
    'คะแนน'
];

// Write header row
$sheet->fromArray($headers, NULL, 'A1');



// Write data or placeholder
if (isset($_SESSION['invalid_rows']) && count($_SESSION['invalid_rows']) > 0) {

    $rowNum = 2; // Start writing at row 2

    foreach ($_SESSION['invalid_rows'] as $row) {
        $sheet->fromArray([
            $row['organization'] ?? '',
            $row['province'] ?? '',
            $row['faculty'] ?? '',
            $row['program'] ?? '',
            $row['major'] ?? '',
            $row['year'] ?? '',
            $row['affiliation'] ?? '',
            $row['total_student'] ?? '',
            $row['mou_status'] ?? '',
            $row['contact'] ?? '',
            $row['score'] ?? ''
        ], NULL, "A{$rowNum}");

        $rowNum++;
    }
} else {
    // No data – show message
    $sheet->setCellValue('A2', 'ไม่มีข้อมูลผิดพลาดใน Session');
    $sheet->mergeCells('A2:K2');

    $sheet->getStyle('A2')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
}

// Auto-size columns
foreach (range('A', 'K') as $col) {
    $sheet->getColumnDimension($col)->setAutoSize(true);
}

// Row height (header)
$sheet->getStyle('A1:K1')->getFont()->setBold(true);
$sheet->getStyle('A1:K1')->getFill()->setFillType(Fill::FILL_SOLID)
    ->getStartColor()->setARGB('FFE2E8F0');
$sheet->getStyle('A1:k1')->getAlignment()->setHorizontal('center');

$sheet->getStyle('F')->getAlignment()->setHorizontal('center');
$sheet->getStyle('G')->getAlignment()->setHorizontal('center');
$sheet->getStyle('H')->getAlignment()->setHorizontal('center');
$sheet->getStyle('I')->getAlignment()->setHorizontal('center');
$sheet->getStyle('K')->getAlignment()->setHorizontal('center');
$sheet->getColumnDimension('A')->setAutoSize(false);
$sheet->getColumnDimension('A')->setWidth(50);
$sheet->getColumnDimension('B')->setAutoSize(false);
$sheet->getColumnDimension('B')->setWidth(25);
$sheet->getColumnDimension('E')->setAutoSize(false);
$sheet->getColumnDimension('E')->setWidth(40);
$sheet->getColumnDimension('F')->setAutoSize(false);
$sheet->getColumnDimension('F')->setWidth(20);
// Output Excel file
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment; filename="internship_report (ข้อมูลที่ไม่ถูกต้อง).xlsx"');
header('Cache-Control: max-age=0');

$writer = new Xlsx($spreadsheet);
$writer->save('php://output');
exit;
