<?php
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../config/db_config.php';

use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(dirname(__DIR__));
$dotenv->load();

$baseUrl = $_ENV['BASE_URL'];

session_start();
date_default_timezone_set('Asia/Bangkok');
$email = $_POST['email'] ?? '';
$password = $_POST['password'] ?? '';

if (!empty($email) && !empty($password)) {

    $sql = 'SELECT * FROM user WHERE email = :email';
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(":email", $email);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {

        if (password_verify($password, $user['password'])) {

            // สร้าง Session
            $_SESSION['checklogin'] = true;
            $_SESSION['email'] = $user['email'];
            $_SESSION['id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $user['role'];

            // Remember Token
            $token = bin2hex(random_bytes(16));
            $expireTime = date("Y-m-d H:i:s", time() + (60 * 45));

            // Save token ลง DB
            $stmt = $conn->prepare("
                UPDATE user 
                SET remember_token = :token, token_expire = :expire 
                WHERE id = :id
            ");
            $stmt->bindParam(":token", $token);
            $stmt->bindParam(":expire", $expireTime);
            $stmt->bindParam(":id", $user['id']);
            $stmt->execute();

            // ลบ cookie เก่า (ถ้ามี)
            if (!empty($_COOKIE['remember_token'])) {
                setcookie('remember_token', '', time() - (60 * 45), '/');
            }

            // สร้าง Cookie ใหม่
            setcookie(
                'remember_token',
                $token,
                time() + (60 * 45),
                '/',
                '',
                false,
                true
            );

            // Redirect หลัง login สำเร็จ
            header("Location: {$baseUrl}/dashboard");
            exit;
        } else {
            $_SESSION['message'] = 'User or password invalid';
            header("Location: {$baseUrl}/dashboard/login.php");
            exit;
        }
    } else {
        $_SESSION['message'] = 'Username not found';
        header("Location: {$baseUrl}/dashboard/login.php");
        exit;
    }
} else {
    $_SESSION['message'] = 'User or password required';
    header("Location: {$baseUrl}/dashboard/login.php");
    exit;
}
