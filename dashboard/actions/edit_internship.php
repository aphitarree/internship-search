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

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode([
        'success' => false,
        'message' => 'Invalid request method'
    ]);
    exit;
}

// รับค่าจากฟอร์ม
$id           = isset($_POST['id']) ? (int)$_POST['id'] : 0;

$organization = trim($_POST['organization'] ?? '');
$province     = trim($_POST['province'] ?? '');
$faculty      = trim($_POST['faculty'] ?? '');
$program      = trim($_POST['program'] ?? '');
$major        = trim($_POST['major'] ?? '');
$yearRaw      = trim($_POST['year'] ?? '');
$totalRaw     = trim($_POST['total_student'] ?? '');
$score        = trim($_POST['score'] ?? '');
$contact      = trim($_POST['contact'] ?? '');

if ($id <= 0) {
    echo json_encode([
        'success' => false,
        'message' => 'Invalid ID'
    ]);
    exit;
}

try {
    // ดึงค่าปัจจุบันจาก DB มาก่อน
    $stmtCurrent = $conn->prepare("
        SELECT year, total_student
        FROM internship_stats
        WHERE id = :id
        LIMIT 1
    ");
    $stmtCurrent->bindParam(':id', $id, PDO::PARAM_INT);
    $stmtCurrent->execute();
    $current = $stmtCurrent->fetch(PDO::FETCH_ASSOC);

    if (!$current) {
        echo json_encode([
            'success' => false,
            'message' => 'ไม่พบข้อมูลที่ต้องการแก้ไข'
        ]);
        exit;
    }

    // year
    if ($yearRaw !== '' && ctype_digit($yearRaw)) {
        $year = (int)$yearRaw;
    } else {
        $year = (int)$current['year'];
    }

    // total_student
    if ($totalRaw !== '' && ctype_digit($totalRaw)) {
        $totalStudent = (int)$totalRaw;
    } else {
        $totalStudent = (int)$current['total_student'];
    }

    // หา major_id จาก faculty + program + major
    if ($faculty === '' || $program === '' || $major === '') {
        echo json_encode([
            'success' => false,
            'message' => 'กรุณาเลือกคณะ / หลักสูตร / สาขาให้ครบ'
        ]);
        exit;
    }

    $stmtMajor = $conn->prepare("
        SELECT id
        FROM faculty_program_major
        WHERE faculty = :faculty
          AND program = :program
          AND major   = :major
        LIMIT 1
    ");
    $stmtMajor->bindParam(':faculty', $faculty, PDO::PARAM_STR);
    $stmtMajor->bindParam(':program', $program, PDO::PARAM_STR);
    $stmtMajor->bindParam(':major',   $major,   PDO::PARAM_STR);
    $stmtMajor->execute();
    $majorRow = $stmtMajor->fetch(PDO::FETCH_ASSOC);

    if (!$majorRow) {
        echo json_encode([
            'success' => false,
            'message' => 'ไม่พบข้อมูลคณะ/หลักสูตร/สาขาที่เลือกในระบบ'
        ]);
        exit;
    }

    $majorId = (int)$majorRow['id'];

    // อัปเดตตาราง internship_stats (รวม major_id)
    $sql = "
        UPDATE internship_stats
        SET
            major_id       = :major_id,
            organization   = :organization,
            province       = :province,
            year           = :year,
            total_student  = :total_student,
            score          = :score,
            contact        = :contact
        WHERE id = :id
        LIMIT 1
    ";

    $stmt = $conn->prepare($sql);

    $stmt->bindParam(':major_id',      $majorId,      PDO::PARAM_INT);
    $stmt->bindParam(':organization',  $organization, PDO::PARAM_STR);
    $stmt->bindParam(':province',      $province,     PDO::PARAM_STR);
    $stmt->bindParam(':year',          $year,         PDO::PARAM_INT);
    $stmt->bindParam(':total_student', $totalStudent, PDO::PARAM_INT);

    if ($score === '') {
        $stmt->bindValue(':score', null, PDO::PARAM_NULL);
    } else {
        $stmt->bindParam(':score', $score, PDO::PARAM_STR);
    }

    $stmt->bindParam(':contact',       $contact,      PDO::PARAM_STR);
    $stmt->bindParam(':id',            $id,           PDO::PARAM_INT);

    $stmt->execute();

    echo json_encode([
        'success' => true,
        'data' => [
            'id'            => $id,
            'organization'  => $organization,
            'province'      => $province,
            'faculty'       => $faculty,
            'program'       => $program,
            'major'         => $major,
            'year'          => $year,
            'total_student' => $totalStudent,
            'score'         => $score === '' ? null : $score,
            'contact'       => $contact,
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
