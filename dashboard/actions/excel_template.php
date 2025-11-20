<?php
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

// Header labels
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

// Write header to XLSX
$sheet->fromArray($headers, NULL, 'A1');


// Auto-size columns
foreach (range('A', 'K') as $col) {
    $sheet->getColumnDimension($col)->setAutoSize(true);
}

// Row height (optional)

// Style header row
$sheet->getStyle('A1:k1')->getAlignment()->setHorizontal('center');
$sheet->getStyle('A1:k1')->getFont()->setBold(true);
$sheet->getStyle('A1:k1')->getFill()->setFillType(Fill::FILL_SOLID)
    ->getStartColor()->setARGB('FFE2E8F0');

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
// Output file
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment; filename="แบบฟอร์มเก็บข้อมูลนักศึกษาฝึกงาน.xlsx"');
header('Cache-Control: max-age=0');

$writer = new Xlsx($spreadsheet);
$writer->save('php://output');
exit;
