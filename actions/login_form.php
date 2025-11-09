<?php
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../config/db_config.php';

use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(dirname(__DIR__));
$dotenv->load();

$baseUrl = $_ENV['BASE_URL'];

session_start();

$email = $_POST['email'];
$password = $_POST['password'];

if (!empty($email) && !empty($password)) {
    $sql = 'SELECT * FROM user WHERE email = :email';
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(":email", $email);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        if (password_verify($password, $user['password'])) {

            $_SESSION['checklogin'] = true;
            $_SESSION['email'] = $user['email'];
            $_SESSION['id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $user['role'];

            $token = bin2hex(random_bytes(16));
            // setcookie('remember_token', $token, time() + (30 * 24 * 60 * 60), '/', '', false, true);
            // 1 Hour cookie
            setcookie('remember_token', $token, time() + (60 * 60), '/', '', false, true);
            $stmt = $conn->prepare("UPDATE user SET remember_token='{$token}' WHERE id = :id");
            $stmt->bindParam(":id", $user['id']);
            $stmt->execute();

            if (!empty($_COOKIE['remember_token'])) {
                setcookie('remember_token', '', time() - 3600, '/');
            }

            if ($user['role'] === 'admin') {
                header("Location: {$baseUrl}/admin-page.php");
            } else {
                // header("Location: {$baseUrl}/index.php");
                header("Location: {$baseUrl}/dashboard/index.php");
            }
            exit;
        } else {
            $_SESSION['message'] = 'User or password invalid';
            header("Location: {$baseUrl}/login.php");
            exit;
        }
    } else {
        $_SESSION['message'] = 'Username not found';
        header("Location: {$baseUrl}/login.php");
        exit;
    }
} else {
    $_SESSION['message'] = 'User or password required';
    header("Location: {$baseUrl}/login.php");
    exit;
}
