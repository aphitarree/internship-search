<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if (ob_get_length()) ob_end_clean();
header('Content-Type: application/json; charset=utf-8');

$conn = require_once __DIR__ . '/../config/db_config.php';

$response = ['status' => 'error', 'message' => 'มีบางอย่างผิดพลาด'];

try {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        throw new Exception('Invalid request method.');
    }

    $isUseful = $_POST['is_useful'] ?? null;
    $commentRaw = $_POST['comment'] ?? '';

    if (empty($isUseful)) throw new Exception('กรุณาระบุว่ามีประโยชน์หรือไม่');
    if (!in_array($isUseful, ['มีประโยชน์', 'ไม่มีประโยชน์'])) throw new Exception('ข้อมูล "is_useful" ไม่ถูกต้อง');
    if (mb_strlen($commentRaw, 'UTF-8') > 200) throw new Exception('ข้อเสนอแนะต้องไม่เกิน 200 ตัวอักษร');

    $comment = empty($commentRaw) ? null : $commentRaw;
    $ipAddress = $_SERVER['REMOTE_ADDR'] ?? 'UNKNOWN';
    $userId = $_SESSION['user_id'] ?? null;

    $sqlCheck = "SELECT id FROM feedback 
                  WHERE ip_address = :ip_address 
                  AND created_at >= NOW() - INTERVAL 1 HOUR
                  LIMIT 1";
    $stmtCheck = $conn->prepare($sqlCheck);
    $stmtCheck->bindParam(':ip_address', $ipAddress);
    $stmtCheck->execute();

    if ($stmtCheck->rowCount() > 0) {
        throw new Exception('คุณได้ส่ง Feedback ไปแล้ว กรุณารอสักครู่ก่อนส่งใหม่อีกครั้ง');
    }

    $sql = "INSERT INTO feedback (is_useful, comment, ip_address) 
            VALUES (:is_useful, :comment, :ip_address)";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':is_useful', $isUseful, PDO::PARAM_STR);
    $stmt->bindValue(':comment', $comment, $comment === null ? PDO::PARAM_NULL : PDO::PARAM_STR);
    $stmt->bindParam(':ip_address', $ipAddress, PDO::PARAM_STR);
    // $stmt->bindValue(':user_id', $userId, $userId === null ? PDO::PARAM_NULL : PDO::PARAM_INT);

    if ($stmt->execute()) {
        $response['status'] = 'success';
        $response['message'] = 'ขอบคุณสำหรับข้อเสนอแนะ!';
    } else {
        throw new Exception('เกิดข้อผิดพลาดในการบันทึกข้อมูล');
    }
} catch (Exception $e) {
    $response['message'] = $e->getMessage();
}

echo json_encode($response);
exit;
