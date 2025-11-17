<?php
// actions/edit_user.php
require_once __DIR__ . '/../../vendor/autoload.php';
require_once __DIR__ . '/../../config/db_config.php';

header('Content-Type: application/json; charset=utf-8');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Method not allowed']);
    exit;
}

$id = isset($_POST['id']) ? (int)$_POST['id'] : 0;
$faculty = trim($_POST['faculty'] ?? '');
$program = trim($_POST['program'] ?? '');
$major   = trim($_POST['major'] ?? '');

if ($id <= 0 || $faculty === '' || $program === '' || $major === '') {
    echo json_encode(['success' => false, 'message' => 'ข้อมูลไม่ครบถ้วน']);
    exit;
}

try {
    // ตรวจสอบว่ามี id นี้จริงไหม
    $check = $conn->prepare("SELECT COUNT(*) FROM faculty_program_major WHERE id = :id");
    $check->execute([':id' => $id]);
    if ($check->fetchColumn() == 0) {
        echo json_encode(['success' => false, 'message' => 'ไม่พบข้อมูลที่จะแก้ไข']);
        exit;
    }

    // (optional) ตรวจสอบการซ้ำของค่าใหม่กับแถวอื่น
    $check2 = $conn->prepare("SELECT COUNT(*) FROM faculty_program_major WHERE faculty = :faculty AND program = :program AND major = :major AND id != :id");
    $check2->execute([':faculty' => $faculty, ':program' => $program, ':major' => $major, ':id' => $id]);
    if ($check2->fetchColumn() > 0) {
        echo json_encode(['success' => false, 'message' => 'มีรายการเดียวกันอยู่แล้วในระบบ']);
        exit;
    }

    $stmt = $conn->prepare("UPDATE faculty_program_major SET faculty = :faculty, program = :program, major = :major WHERE id = :id");
    $stmt->execute([':faculty' => $faculty, ':program' => $program, ':major' => $major, ':id' => $id]);

    echo json_encode(['success' => true, 'message' => 'อัปเดตข้อมูลเรียบร้อย']);
    exit;
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'เกิดข้อผิดพลาด: ' . $e->getMessage()]);
    exit;
}
