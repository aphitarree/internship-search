<?php
require_once __DIR__ . '/../vendor/autoload.php';

use Dotenv\Dotenv;

// โหลดค่า .env
$dotenv = Dotenv::createImmutable(dirname(__DIR__));
$dotenv->load();

// ข้อมูลเชื่อมต่อฐานข้อมูล
$dbServerName = $_ENV['DB_SERVER_NAME'];
$dbName       = $_ENV['DB_NAME'];
$dbUsername   = $_ENV['DB_USERNAME'];
$dbPassword   = $_ENV['DB_PASSWORD'];

try {
    $dsn = "mysql:host={$dbServerName};charset=utf8mb4";
    $conn = new PDO($dsn, $dbUsername, $dbPassword);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    $conn->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);

    // ใช้ฐานข้อมูลเดียวกับที่มีอยู่แล้ว
    $conn->exec("CREATE DATABASE IF NOT EXISTS {$dbName} CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
    $conn->exec("USE {$dbName}");

    // --------- สร้างตาราง feedback ----------
    $sqlFeedback = "
    CREATE TABLE IF NOT EXISTS `feedback` (
        `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        `is_useful` ENUM('มีประโยชน์', 'ไม่มีประโยชน์') NOT NULL COMMENT 'ประเมินว่ามีประโยชน์หรือไม่',
        `comment` VARCHAR(200) DEFAULT NULL COMMENT 'คอมเมนต์เพิ่มเติม (จำกัด 200 ตัวอักษร)',
        `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP COMMENT 'วันที่บันทึกข้อมูล'
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
    ";

    // สร้างตาราง
    $conn->exec($sqlFeedback);

    echo "✅ Table 'feedback' created successfully.<br>";

} catch (PDOException $e) {
    echo "❌ Database error: " . htmlspecialchars($e->getMessage());
}
