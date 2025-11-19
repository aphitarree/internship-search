<?php
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../config/db_config.php';

use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(dirname(__DIR__));
$dotenv->load();


$baseUrl = $_ENV['BASE_URL'];

session_start();

// 1. ลบ Cookie remember_token
if (isset($_COOKIE['remember_token'])) {
    $stmt = $conn->prepare("UPDATE user SET remember_token = :token, token_expire = :expire WHERE id = :id");
    $stmt->bindParam(":token", $token);
    $stmt->bindParam(":expire", $expireTime);
    $stmt->bindParam(":id", $_SESSION['id']);
    $stmt->execute();
    $stmt = null;
}

if (isset($_SESSION['id'])) {
    $stmt = $conn->prepare("UPDATE user SET remember_token = NULL WHERE id = :id");
    $stmt->bindParam(":id", $_SESSION['id']);
    $stmt->execute();
    $stmt = null;
}

unset($_SESSION['checklogin']);
unset($_SESSION['email']);
unset($_SESSION['id']);
unset($_SESSION['username']);

if (isset($_COOKIE[session_name()])) {
    setcookie(session_name(), '', time() - 3600, '/');
}

session_destroy();
header("Location: {$baseUrl}/dashboard/login.php");

exit;
