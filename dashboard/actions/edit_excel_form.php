<?php
require_once __DIR__ . '/../../vendor/autoload.php';
require_once __DIR__ . '/../../config/db_config.php';
session_start();

use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(dirname(dirname(__DIR__)));
$dotenv->load();

$baseUrl = $_ENV['BASE_URL'] ?? '';

// Set the filename for the wrong records file
header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename=internship_report (ข้อมูลที่ไม่ถูกต้อง).csv');

// Write the csv file
$output = fopen('php://output', 'w');
fwrite($output, "\xEF\xBB\xBF");

// Set the column name for the file
fputcsv($output, ['บริษัท', 'จังหวัด', 'คณะ', 'หลักสูตร', 'สาขา', 'ปีการศึกษา', 'จำนวนที่รับ', 'ข้อมูลการติดต่อ', 'คะแนน']);

// Write the wrong data to the file
if (isset($_SESSION['invalid_rows']) && count($_SESSION['invalid_rows']) > 0) {
    foreach ($_SESSION['invalid_rows'] as $row) {
        fputcsv($output, [
            $row['organization'] ?? '',
            $row['province'] ?? '',
            $row['faculty'] ?? '',
            $row['program'] ?? '',
            $row['major'] ?? '',
            $row['year'] ?? '',
            $row['total_student'] ?? '',
            $row['contact'] ?? '',
            $row['score'] ?? '',
        ]);
    }
} else {
    fputcsv($output, ['ไม่มีข้อมูลผิดพลาดใน Session']);
}


fclose($output);
// unset($_SESSION['invalid_rows']);
exit;
