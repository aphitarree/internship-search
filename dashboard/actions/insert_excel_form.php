<?php
session_start();
require_once __DIR__ . '/../../vendor/autoload.php';
require_once __DIR__ . '/../../config/db_config.php';

use PhpOffice\PhpSpreadsheet\IOFactory;
use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(dirname(dirname(__DIR__)));
$dotenv->load();

$baseUrl = $_ENV['BASE_URL'];

if (isset($_POST['submit'])) {
    unset($_SESSION['invalid_rows']);

    $fileTmpPath = $_FILES['excel_file']['tmp_name'];
    $fileName = $_FILES['excel_file']['name'];
    $fileExtension = pathinfo($fileName, PATHINFO_EXTENSION);

    $allowedExtensions = ['csv', 'xls', 'xlsx'];

    if (in_array($fileExtension, $allowedExtensions)) {
        $spreadsheet = IOFactory::load($fileTmpPath);
        $data = $spreadsheet->getActiveSheet()->toArray();

        $count = 0;
        foreach ($data as $row) {
            if ($count > 0) {
                $organization = trim($row[0] ?? '');
                $province = trim($row[1] ?? '');
                $faculty = trim($row[2] ?? '');
                $program = trim($row[3] ?? '');
                $major = trim($row[4] ?? '');
                $year = trim($row[5] ?? '');
                $totalStudent = trim($row[6] ?? '');
                $mouStatus = trim($row[7] ?? '');
                $contact = trim($row[8] ?? '');
                $score = trim($row[9] ?? '');
                $affiliation = trim($row[10] ?? '');
                if (
                    $organization === '' ||
                    $province === '' ||
                    $faculty === '' ||
                    $program === '' ||
                    $major === '' ||
                    $year === '' ||
                    $totalStudent === '' ||
                    $mouStatus === '' ||
                    $contact === '' ||
                    $score === '' ||
                    $affiliation === ''
                ) {
                    $_SESSION['invalid_rows'][] = [
                        'organization' => $organization,
                        'province' => $province,
                        'faculty' => $faculty,
                        'program' => $program,
                        'major' => $major,
                        'year' => $year,
                        'total_student' => $totalStudent,
                        'mou_status' => $mouStatus,
                        'contact' => $contact,
                        'score' => $score,
                        'affiliation' => $affiliation,
                        'error' => 'ข้อมูลไม่ครบ'
                    ];
                    continue;
                }
                $sqlMajor = "
                    SELECT id FROM faculty_program_major 
                    WHERE faculty = :faculty AND program = :program AND major = :major 
                    LIMIT 1
                ";
                $stmtMajor = $conn->prepare($sqlMajor);
                $stmtMajor->bindParam(':faculty', $faculty);
                $stmtMajor->bindParam(':program', $program);
                $stmtMajor->bindParam(':major', $major);
                $stmtMajor->execute();
                $majorRow = $stmtMajor->fetch(PDO::FETCH_ASSOC);

                if ($majorRow) {
                    $majorId = $majorRow['id'];
                } else {
                    $_SESSION['invalid_rows'][] = [
                        'organization' => $organization,
                        'province' => $province,
                        'faculty' => $faculty,
                        'program' => $program,
                        'major' => $major,
                        'year' => $year,
                        'total_student' => $totalStudent,
                        'mou_status' => $mouStatus,
                        'contact' => $contact,
                        'score' => $score,
                        'affiliation' => $affiliation,
                    ];
                    continue;
                }

                // Insert if the data is correct
                $sql = 'INSERT INTO internship_stats 
                (organization, province, major_id, year, total_student, mou_status, contact, score, affiliation)
                VALUES (:organization, :province, :major_id, :year, :total_student, :mou_status, :contact, :score , :affiliation)';
                $stmt = $conn->prepare($sql);
                $stmt->bindParam(':organization', $organization);
                $stmt->bindParam(':province', $province);
                $stmt->bindParam(':major_id', $majorId);
                $stmt->bindParam(':year', $year);
                $stmt->bindParam(':total_student', $totalStudent);
                $stmt->bindParam(':mou_status', $mouStatus);
                $stmt->bindParam(':contact', $contact);
                $stmt->bindParam(':score', $score);
                $stmt->bindParam(':affiliation', $affiliation);
                $stmt->execute();

                $_SESSION['inserted_data'][] = [
                    'organization' => $organization,
                    'province' => $province,
                    'faculty' => $faculty,
                    'program' => $program,
                    'major' => $major,
                    'year' => $year,
                    'total_student' => $totalStudent,
                    'mou_status' => $mouStatus,
                    'contact' => $contact,
                    'score' => $score,
                    'affiliation' => $affiliation
                ];

                $msg = true;
            } else {
                $count = 1;
            }
        }

        $_SESSION['message'] = isset($msg)
            ? "นำเข้าข้อมูลสำเร็จ"
            : "นำเข้าข้อมูลไม่สำเร็จ";

        header("Location: {$baseUrl}/dashboard/insert_excel.php");
        exit;
    }
}
