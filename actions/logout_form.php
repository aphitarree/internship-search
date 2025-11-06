<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../config/db_config.php';

use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(dirname(__DIR__));
$dotenv->load();


$baseUrl = $_ENV['BASE_URL'];

session_start();

// 1. ลบ Cookie remember_token
if (isset($_COOKIE['remember_token'])) {
    setcookie('remember_token', '', time() - 3600, '/', '', false, true);
    // ตั้งเวลาให้หมดอายุย้อนหลัง → Cookie ถูกลบ
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
