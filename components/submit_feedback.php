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

    $is_useful = $_POST['is_useful'] ?? null;
    $comment_raw = $_POST['comment'] ?? '';

    if (empty($is_useful)) throw new Exception('กรุณาระบุว่ามีประโยชน์หรือไม่');
    if (!in_array($is_useful, ['มีประโยชน์', 'ไม่มีประโยชน์'])) throw new Exception('ข้อมูล "is_useful" ไม่ถูกต้อง');
    if (mb_strlen($comment_raw, 'UTF-8') > 200) throw new Exception('ข้อเสนอแนะต้องไม่เกิน 200 ตัวอักษร');

    $comment = empty($comment_raw) ? null : $comment_raw;
    $ip_address = $_SERVER['REMOTE_ADDR'] ?? 'UNKNOWN';
    $user_id = $_SESSION['user_id'] ?? null;

    $sql_check = "SELECT id FROM feedback 
                  WHERE ip_address = :ip_address 
                  AND created_at >= NOW() - INTERVAL 1 HOUR
                  LIMIT 1";
    $stmt_check = $conn->prepare($sql_check);
    $stmt_check->bindParam(':ip_address', $ip_address);
    $stmt_check->execute();

    if ($stmt_check->rowCount() > 0) {
        throw new Exception('คุณเพิ่งส่ง feedback ไปเมื่อไม่นานนี้ โปรดลองอีกครั้งภายหลัง');
    }

    $sql = "INSERT INTO feedback (is_useful, comment, ip_address) 
            VALUES (:is_useful, :comment, :ip_address)";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':is_useful', $is_useful, PDO::PARAM_STR);
    $stmt->bindValue(':comment', $comment, $comment === null ? PDO::PARAM_NULL : PDO::PARAM_STR);
    $stmt->bindParam(':ip_address', $ip_address, PDO::PARAM_STR);
    // $stmt->bindValue(':user_id', $user_id, $user_id === null ? PDO::PARAM_NULL : PDO::PARAM_INT);

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
