<?php
require_once __DIR__ . '/../vendor/autoload.php';

use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(dirname(__DIR__));
$dotenv->load();

$dbServerName = $_ENV['DB_SERVER_NAME'];
$dbName = $_ENV['DB_NAME'];
$dbUsername = $_ENV['DB_USERNAME'];
$dbPassword = $_ENV['DB_PASSWORD'];

try {
    $dsn = "mysql:host={$dbServerName};charset=utf8mb4";
    $conn = new PDO($dsn, $dbUsername, $dbPassword);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    $conn->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);

    // สร้างฐานข้อมูลถ้ายังไม่มี
    $conn->exec("CREATE DATABASE IF NOT EXISTS {$dbName} CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
    $conn->exec("USE {$dbName}");

    // ตารางคณะ/หลักสูตร/สาขา
    $sqlFaculty = "
    CREATE TABLE IF NOT EXISTS `faculty_program_major` (
        `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        `faculty` VARCHAR(255) NOT NULL COMMENT 'คณะ/โรงเรียน',
        `program` VARCHAR(255) NOT NULL COMMENT 'หลักสูตร',
        `major` VARCHAR(255) NOT NULL COMMENT 'สาขาวิชา',
        `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
    ";

    // ตารางหน่วยงานฝึกงาน
    $sqlStats = "
    CREATE TABLE IF NOT EXISTS `internship_stats` (
        `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        `organization` VARCHAR(255) NOT NULL COMMENT 'ชื่อหน่วยงานที่รับฝึกประสบการณ์',
        `province` VARCHAR(100) NOT NULL COMMENT 'จังหวัด',
        `major_id` INT UNSIGNED NOT NULL COMMENT 'FK to faculty_program_major',
        `year` SMALLINT UNSIGNED NOT NULL COMMENT 'ปี พ.ศ.',
        `total_student` INT UNSIGNED DEFAULT 0 COMMENT 'จำนวนผู้ฝึกงาน',
        `contact` VARCHAR(255) NOT NULL COMMENT 'ช่องทางการติดต่อ',
        `score` VARCHAR(5) NOT NULL COMMENT 'คะแนนความพึงพอใจ',
        `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (`major_id`) REFERENCES `faculty_program_major`(`id`) ON DELETE CASCADE
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
    ";

    // ตารางผู้ใช้
    $sqlUser = "
    CREATE TABLE IF NOT EXISTS `user` (
        `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        `email` VARCHAR(100) NOT NULL UNIQUE,
        `username` VARCHAR(100) NOT NULL UNIQUE,
        `password` VARCHAR(255) NOT NULL,
        `role` ENUM('admin','user') NOT NULL DEFAULT 'user',
        `remember_token` VARCHAR(100) DEFAULT NULL,
        `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
    ";

    // ตารางบันทึกการเข้าใช้งาน
    $sqlAccessLogs = "
    CREATE TABLE IF NOT EXISTS `access_logs` (
        `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        `user_id` INT UNSIGNED NULL COMMENT 'FK to user',
        `ip_address` VARCHAR(45) NOT NULL COMMENT 'IP ของผู้ใช้',
        `user_agent` VARCHAR(255) NOT NULL COMMENT 'User Agent',
        `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
        INDEX `idx_user_ip_time` (`user_id`, `ip_address`, `created_at`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
    ";

    $sqlFeedback = "
    CREATE TABLE IF NOT EXISTS `feedback` (
        `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        `is_useful` ENUM('มีประโยชน์', 'ไม่มีประโยชน์') NOT NULL COMMENT 'ประเมินว่ามีประโยชน์หรือไม่',
        `comment` VARCHAR(200) DEFAULT NULL COMMENT 'คอมเมนต์เพิ่มเติม (จำกัด 200 ตัวอักษร)',
        `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP COMMENT 'วันที่บันทึกข้อมูล'
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
    ";

    // สร้างตารางทั้งหมด
    $conn->exec($sqlFaculty);
    $conn->exec($sqlStats);
    $conn->exec($sqlUser);
    $conn->exec($sqlAccessLogs);
    $conn->exec($sqlFeedback);

    echo "<hr><strong>Setup completed successfully!</strong>";
} catch (PDOException $e) {
    echo "<strong>Error:</strong> " . htmlspecialchars($e->getMessage());
}
