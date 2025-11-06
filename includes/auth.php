<?php
require_once __DIR__ . '/../vendor/autoload.php';

use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(dirname(__DIR__));
$dotenv->load();

$baseUrl = $_ENV['BASE_URL'];


session_start();

if (!isset($_SESSION['checklogin']) || $_SESSION['checklogin'] !== true) {
    header("Location: {$baseUrl}/dashboard/login.php");
    exit;
}
