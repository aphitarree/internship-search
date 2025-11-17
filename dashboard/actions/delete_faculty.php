<?php
// actions/delete_user.php
require_once __DIR__ . '/../../vendor/autoload.php';
require_once __DIR__ . '/../../config/db_config.php';

header('Content-Type: application/json; charset=utf-8');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Method not allowed']);
    exit;
}

$id = isset($_POST['id']) ? (int)$_POST['id'] : 0;
if ($id <= 0) {
    echo json_encode(['success' => false, 'message' => 'ID ไม่ถูกต้อง']);
    exit;
}

try {
    // ตรวจสอบ relation ถ้ามี FK หรือใช้ที่อื่น ให้ทำ logic ตรวจสอบก่อนลบ
    $check = $conn->prepare("SELECT COUNT(*) FROM faculty_program_major WHERE id = :id");
    $check->execute([':id' => $id]);
    if ($check->fetchColumn() == 0) {
        echo json_encode(['success' => false, 'message' => 'ไม่พบข้อมูลที่จะลบ']);
        exit;
    }

    $stmt = $conn->prepare("DELETE FROM faculty_program_major WHERE id = :id");
    $stmt->execute([':id' => $id]);

    echo json_encode(['success' => true, 'message' => 'ลบข้อมูลเรียบร้อย']);
    exit;
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'เกิดข้อผิดพลาด: ' . $e->getMessage()]);
    exit;
}
