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

$email = trim($_POST['email'] ?? '');
$username = trim($_POST['username'] ?? '');
$password = trim($_POST['password'] ?? '');
$role = trim($_POST['role'] ?? 'user');

if ($email === '' || $username === '' || $password === '') {
    echo json_encode([
        'success' => false,
        'message' => 'กรุณากรอกอีเมล, ชื่อผู้ใช้ และรหัสผ่านให้ครบถ้วน'
    ]);
    exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo json_encode([
        'success' => false,
        'message' => 'รูปแบบอีเมลไม่ถูกต้อง'
    ]);
    exit;
}

$allowedRoles = ['user', 'admin'];
if (!in_array($role, $allowedRoles, true)) {
    $role = 'user';
}

try {
    $sqlCheck = "SELECT id FROM user WHERE email = :email LIMIT 1";
    $stmtCheck = $conn->prepare($sqlCheck);
    $stmtCheck->bindParam(':email', $email, PDO::PARAM_STR);
    $stmtCheck->execute();

    if ($stmtCheck->fetch(PDO::FETCH_ASSOC)) {
        echo json_encode([
            'success' => false,
            'message' => 'อีเมลนี้มีผู้ใช้งานแล้ว'
        ]);
        exit;
    }

    $passwordHash = password_hash($password, PASSWORD_DEFAULT);

    $sqlInsert = "
        INSERT INTO user (email, username, password, role)
        VALUES (:email, :username, :password, :role)
    ";
    $stmtInsert = $conn->prepare($sqlInsert);
    $stmtInsert->bindParam(':email', $email, PDO::PARAM_STR);
    $stmtInsert->bindParam(':username', $username, PDO::PARAM_STR);
    $stmtInsert->bindParam(':password', $passwordHash, PDO::PARAM_STR);
    $stmtInsert->bindParam(':role', $role, PDO::PARAM_STR);

    $stmtInsert->execute();

    echo json_encode([
        'success' => true,
        'message' => 'เพิ่มผู้ใช้สำเร็จ'
    ]);
    exit;
} catch (PDOException $e) {
    echo json_encode([
        'success' => false,
        'message' => 'Database error: ' . $e->getMessage()
    ]);
    exit;
}
