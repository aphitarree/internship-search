<?php
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../../vendor/autoload.php';
require_once __DIR__ . '/../../config/db_config.php';

use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(dirname(dirname(__DIR__)));
$dotenv->load();

header('Content-Type: application/json; charset=utf-8');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode([
        'success' => false,
        'message' => 'Invalid request method'
    ]);
    exit;
}

// Input form data
$id = isset($_POST['id']) ? (int)$_POST['id'] : 0;
$organization = trim($_POST['organization'] ?? '');
$province = trim($_POST['province'] ?? '');
$faculty = trim($_POST['faculty'] ?? '');
$program = trim($_POST['program'] ?? '');
$major = trim($_POST['major'] ?? '');
$yearInput = trim($_POST['year'] ?? '');
$totalStudentInput = trim($_POST['total_student'] ?? '');
$mouStatus = trim($_POST['mou_status'] ?? '');
$score = trim($_POST['score'] ?? '');
$contact = trim($_POST['contact'] ?? '');

if ($id <= 0) {
    echo json_encode([
        'success' => false,
        'message' => 'Invalid ID'
    ]);
    exit;
}

try {
    // Fetch current year, total_student, mou_status for prevent inserting null value to the database
    $stmtCurrentData = $conn->prepare("
        SELECT year, total_student, mou_status
        FROM internship_stats
        WHERE id = :id
        LIMIT 1
    ");
    $stmtCurrentData->bindParam(':id', $id, PDO::PARAM_INT);
    $stmtCurrentData->execute();
    $currentData = $stmtCurrentData->fetch(PDO::FETCH_ASSOC);

    if (!$currentData) {
        echo json_encode([
            'success' => false,
            'message' => 'ไม่พบข้อมูลที่ต้องการแก้ไข'
        ]);
        exit;
    }

    // If yearInput is null then insert the current year data from the database
    if ($yearInput !== '' && ctype_digit($yearInput)) {
        $year = (int)$yearInput;
    } else {
        $year = (int)$currentData['year'];
    }

    // If totalStudentInput is null then insert the current total student data from the database
    if ($totalStudentInput !== '' && ctype_digit($totalStudentInput)) {
        $totalStudent = (int)$totalStudentInput;
    } else {
        $totalStudent = (int)$currentData['total_student'];
    }

    // If mouStatus is null then insert the current total student data from the database
    if ($mouStatus === '') {
        $mouStatusToSave = $currentData['mou_status'];
    } else {
        $mouStatusToSave = $mouStatus;
    }

    // If the faculty, program, and major is null eject the insertion
    if ($faculty === '' || $program === '' || $major === '') {
        echo json_encode([
            'success' => false,
            'message' => 'กรุณาเลือกคณะ / หลักสูตร / สาขาให้ครบ'
        ]);
        exit;
    }

    // Fetch the id from the input faculty, program, and major
    $sqlMajor = "
        SELECT id
        FROM faculty_program_major
        WHERE faculty = :faculty
            AND program = :program
            AND major = :major
        LIMIT 1
    ";
    $stmtMajor = $conn->prepare($sqlMajor);
    $stmtMajor->bindParam(':faculty', $faculty, PDO::PARAM_STR);
    $stmtMajor->bindParam(':program', $program, PDO::PARAM_STR);
    $stmtMajor->bindParam(':major',   $major,   PDO::PARAM_STR);
    $stmtMajor->execute();
    $majorRow = $stmtMajor->fetch(PDO::FETCH_ASSOC);

    // If the major doesn't exist, eject
    if (!$majorRow) {
        echo json_encode([
            'success' => false,
            'message' => 'ไม่พบข้อมูลคณะ/หลักสูตร/สาขาที่เลือกในระบบ'
        ]);
        exit;
    }

    $majorId = (int)$majorRow['id'];

    // Update the internship_stats record data
    $sql = "
        UPDATE internship_stats
        SET
            major_id       = :major_id,
            organization   = :organization,
            province       = :province,
            year           = :year,
            total_student  = :total_student,
            mou_status     = :mou_status,
            score          = :score,
            contact        = :contact
        WHERE id = :id
        LIMIT 1
    ";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':major_id', $majorId, PDO::PARAM_INT);
    $stmt->bindParam(':organization', $organization, PDO::PARAM_STR);
    $stmt->bindParam(':province', $province, PDO::PARAM_STR);
    $stmt->bindParam(':year', $year, PDO::PARAM_INT);
    $stmt->bindParam(':total_student', $totalStudent, PDO::PARAM_INT);
    $stmt->bindParam(':mou_status', $mouStatusToSave, PDO::PARAM_STR);
    if ($score === '') {
        $stmt->bindValue(':score', null, PDO::PARAM_NULL);
    } else {
        $stmt->bindParam(':score', $score, PDO::PARAM_STR);
    }
    $stmt->bindParam(':contact', $contact, PDO::PARAM_STR);
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();

    echo json_encode([
        'success' => true,
        'data' => [
            'id' => $id,
            'organization' => $organization,
            'province' => $province,
            'faculty' => $faculty,
            'program' => $program,
            'major' => $major,
            'year' => $year,
            'total_student' => $totalStudent,
            'mou_status' => $mouStatusToSave,
            'score' => $score === '' ? null : $score,
            'contact' => $contact,
        ]
    ]);
    exit;
} catch (PDOException $e) {
    echo json_encode([
        'success' => false,
        'message' => 'Database error: ' . $e->getMessage()
    ]);
    exit;
}
