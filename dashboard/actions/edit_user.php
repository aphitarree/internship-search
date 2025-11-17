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
$email = trim($_POST['email'] ?? '');
$username = trim($_POST['username'] ?? '');
$role = trim($_POST['role'] ?? '');
$password = trim($_POST['password'] ?? '');

if ($id <= 0) {
    echo json_encode([
        'success' => false,
        'message' => 'Invalid ID'
    ]);
    exit;
}

if ($email === '' || $username === '' || $role === '') {
    echo json_encode([
        'success' => false,
        'message' => 'กรุณากรอกอีเมล / ชื่อผู้ใช้ / บทบาทให้ครบถ้วน'
    ]);
    exit;
}

try {
    $stmtCheck = $conn->prepare("SELECT id FROM user WHERE id = :id LIMIT 1");
    $stmtCheck->execute([':id' => $id]);
    $exists = $stmtCheck->fetch(PDO::FETCH_ASSOC);

    if (!$exists) {
        echo json_encode([
            'success' => false,
            'message' => 'ไม่พบข้อมูลผู้ใช้ที่ต้องการแก้ไข'
        ]);
        exit;
    }

    if ($password !== '') {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        $sql = "
            UPDATE user
            SET
                email    = :email,
                username = :username,
                role     = :role,
                password = :password
            WHERE id = :id
            LIMIT 1
        ";

        $params = [
            ':email'    => $email,
            ':username' => $username,
            ':role'     => $role,
            ':password' => $hashedPassword,
            ':id'       => $id,
        ];
    } else {
        $sql = "
            UPDATE user
            SET
                email    = :email,
                username = :username,
                role     = :role
            WHERE id = :id
            LIMIT 1
        ";

        $params = [
            ':email'    => $email,
            ':username' => $username,
            ':role'     => $role,
            ':id'       => $id,
        ];
    }

    $stmt = $conn->prepare($sql);
    $stmt->execute($params);

    echo json_encode([
        'success' => true,
        'data' => [
            'id'       => $id,
            'email'    => $email,
            'username' => $username,
            'role'     => $role,
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
