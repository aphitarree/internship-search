<?php
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require_once __DIR__ . '/../../vendor/autoload.php';
require_once __DIR__ . '/../../config/db_config.php';


use PhpOffice\PhpSpreadsheet\IOFactory;
use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(dirname(dirname(__DIR__)));
$dotenv->load();

$baseUrl = $_ENV['BASE_URL'];
if (isset($_POST['submit'])) {

    $FileTmpPath = $_FILES['excel_file']['tmp_name'];
    $FileName = $_FILES['excel_file']['name'];
    $FileExtension = pathinfo($FileName, PATHINFO_EXTENSION);

    $allowed_extension = ['csv', 'xls', 'xlsx'];

    if (in_array($FileExtension, $allowed_extension)) {
        $spreadsheet = IOFactory::load($FileTmpPath);
        $data = $spreadsheet->getActiveSheet()->toArray();

        $count = 0;
        foreach ($data as $row) {
            if ($count > 0) {
                $organization = trim($row[0]);
                $province = trim($row[1]);
                $faculty = trim($row[2]);
                $program = trim($row[3]);
                $major = trim($row[4]);
                $year = trim($row[5]);
                $total_student = trim($row[6]);
                $contact = trim($row[7]);
                $score = trim($row[8]);

                $sql_major = "SELECT id FROM faculty_program_major 
                      WHERE faculty = :faculty AND program = :program AND major = :major 
                      LIMIT 1";
                $stmt_major = $conn->prepare($sql_major);
                $stmt_major->bindParam(':faculty', $faculty);
                $stmt_major->bindParam(':program', $program);
                $stmt_major->bindParam(':major', $major);
                $stmt_major->execute();
                $major_row = $stmt_major->fetch(PDO::FETCH_ASSOC);

                if ($major_row) {
                    $major_id = $major_row['id'];
                } else {
                    $_SESSION['invalid_rows'][] = "{$organization} / {$province} / {$faculty} / {$program} / {$major} / {$year} / {$total_student} / {$contact} / {$score}";
                    continue;
                }

                // ✅ Insert ข้อมูล
                $sql = 'INSERT INTO internship_stats 
                (organization, province, major_id, year, total_student, contact, score)
                VALUES (:organization, :province, :major_id, :year, :total_student, :contact, :score)';
                $stmt = $conn->prepare($sql);
                $stmt->bindParam(':organization', $organization);
                $stmt->bindParam(':province', $province);
                $stmt->bindParam(':major_id', $major_id);
                $stmt->bindParam(':year', $year);
                $stmt->bindParam(':total_student', $total_student);
                $stmt->bindParam(':contact', $contact);
                $stmt->bindParam(':score', $score);
                $stmt->execute();

                $_SESSION['inserted_data'][] = [
                    'organization' => $organization,
                    'province' => $province,
                    'faculty' => $faculty,
                    'program' => $program,
                    'major' => $major,
                    'year' => $year,
                    'total_student' => $total_student,
                    'contact' => $contact,
                    'score' => $score
                ];

                $msg = true;
            } else {
                $count = 1;
            }
        }

        if (!empty($_SESSION['invalid_rows'])) {
            $invalid_list = implode("<br>", $_SESSION['invalid_rows']);
            $_SESSION['message'] = "⚠️ ข้อมูลไม่ถูกต้อง:<br>" . $invalid_list;
        }

        $_SESSION['massge'] = isset($msg)
            ? "✅ Successfully Imported"
            : "⚠️ Not Imported";

        header("Location: {$baseUrl}/dashboard/insert_excel.php");
        exit;
    }
}
