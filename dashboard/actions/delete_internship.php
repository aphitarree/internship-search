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

$id = isset($_POST['id']) ? (int)$_POST['id'] : 0;

if ($id <= 0) {
    echo json_encode([
        'success' => false,
        'message' => 'Invalid ID'
    ]);
    exit;
}

try {
    $stmtCheck = $conn->prepare("SELECT id FROM internship_stats WHERE id = :id LIMIT 1");
    $stmtCheck->execute([':id' => $id]);
    $row = $stmtCheck->fetch(PDO::FETCH_ASSOC);

    if (!$row) {
        echo json_encode([
            'success' => false,
            'message' => 'ไม่พบข้อมูลที่ต้องการลบ'
        ]);
        exit;
    }

    $stmtDel = $conn->prepare("DELETE FROM internship_stats WHERE id = :id LIMIT 1");
    $stmtDel->execute([':id' => $id]);

    echo json_encode([
        'success' => true,
        'message' => 'ลบข้อมูลสำเร็จ'
    ]);
    exit;
} catch (PDOException $e) {
    echo json_encode([
        'success' => false,
        'message' => 'Database error: ' . $e->getMessage()
    ]);
    exit;
}
