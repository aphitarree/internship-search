<?php
// actions/add_user.php
require_once __DIR__ . '/../../vendor/autoload.php';
require_once __DIR__ . '/../../config/db_config.php';

header('Content-Type: application/json; charset=utf-8');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Method not allowed']);
    exit;
}

$faculty = trim($_POST['faculty'] ?? '');
$program = trim($_POST['program'] ?? '');
$major   = trim($_POST['major'] ?? '');

if ($faculty === '' || $program === '' || $major === '') {
    echo json_encode(['success' => false, 'message' => 'กรุณากรอกข้อมูลให้ครบทุกช่อง']);
    exit;
}

try {
    // ตรวจสอบซ้ำ (optional) — ป้องกันเพิ่มซ้ำ exact same row
    $check = $conn->prepare("SELECT COUNT(*) FROM faculty_program_major WHERE faculty = :faculty AND program = :program AND major = :major");
    $check->execute([':faculty' => $faculty, ':program' => $program, ':major' => $major]);
    if ($check->fetchColumn() > 0) {
        echo json_encode(['success' => false, 'message' => 'รายการนี้มีอยู่ในระบบแล้ว']);
        exit;
    }

    $stmt = $conn->prepare("INSERT INTO faculty_program_major (faculty, program, major) VALUES (:faculty, :program, :major)");
    $stmt->execute([':faculty' => $faculty, ':program' => $program, ':major' => $major]);

    echo json_encode(['success' => true, 'message' => 'เพิ่มข้อมูลเรียบร้อย']);
    exit;
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'เกิดข้อผิดพลาด: ' . $e->getMessage()]);
    exit;
}
