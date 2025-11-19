<?php
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../config/db_config.php';

use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(dirname(__DIR__));
$dotenv->load();

$baseUrl = $_ENV['BASE_URL'];


session_start();
date_default_timezone_set('Asia/Bangkok');
if (!isset($_SESSION['checklogin']) || !isset($_COOKIE['remember_token'])) {
    session_unset();
    session_destroy();
    header("Location: {$baseUrl}/dashboard/login.php");
    exit;
}

// ถ้าไม่มี session → ค่อยเช็ค cookie
if (!isset($_COOKIE['remember_token'])) {
    header("Location: {$baseUrl}/dashboard/login.php");
    exit;
}

$token = $_COOKIE['remember_token'];

// ดึงข้อมูล user ด้วย token
$stmt = $conn->prepare("SELECT * FROM user WHERE remember_token = :token");
$stmt->bindParam(":token", $token);
$stmt->execute();
$user = $stmt->fetch(PDO::FETCH_ASSOC);

// ถ้า token ไม่ตรง → บังคับ login ใหม่
if (!$user) {
    setcookie('remember_token', '', time() - (60 * 45), '/');
    header("Location: {$baseUrl}/dashboard/login.php");
    exit;
}

// เช็คว่า token หมดอายุหรือยัง
$currentTime = date("Y-m-d H:i:s");

if (strtotime($user['token_expire']) < time()) {
    // Token หมดอายุ
    setcookie('remember_token', '', time() - (60 * 45), '/');
    unset($_SESSION['checklogin']);
    unset($_SESSION['email']);
    unset($_SESSION['id']);
    unset($_SESSION['username']);

    // เคลียร์ token ที่หมดอายุใน DB (แนะนำ)
    $stmt = $conn->prepare("UPDATE user SET remember_token = NULL, token_expire = NULL WHERE id = :id");
    $stmt->bindParam(":id", $user['id']);
    $stmt->execute();

    header("Location: {$baseUrl}/dashboard/login.php");
    exit;
}

// ถ้ายังไม่หมดอายุ — ให้เข้า dashboard ได้
// สามารถเซ็ต session กลับได้ หากต้องการ
