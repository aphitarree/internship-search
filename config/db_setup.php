<?php
require_once __DIR__ . '/../vendor/autoload.php';

use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(dirname(__DIR__));
$dotenv->load();

// Use the config variable from .env file
$dbServerName = $_ENV['DB_SERVER_NAME'];
$dbName = $_ENV['DB_NAME'];
$dbUsername = $_ENV['DB_USERNAME'];
$dbPassword = $_ENV['DB_PASSWORD'];

try {
  // Specify data source name (DSN)
  $dsn = "mysql:host={$dbServerName};charset=utf8mb4";

  $conn = new PDO($dsn, $dbUsername, $dbPassword);
  $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  $conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
  $conn->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);

  // Create a database
  $conn->exec("CREATE DATABASE IF NOT EXISTS {$dbName} CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");

  // Connect the database
  $conn->exec("USE {$dbName}");

  // Create internship_history table
  $sqlInternshipHistory = "
    CREATE TABLE IF NOT EXISTS `internship_history` (
		`id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
		`faculty` VARCHAR(255) NOT NULL COMMENT 'คณะ/โรงเรียน',
		`program` VARCHAR(255) NOT NULL COMMENT 'หลักสูตร',
		`major` VARCHAR(255) NOT NULL COMMENT 'สาขาวิชา',
		`organization` VARCHAR(255) NOT NULL COMMENT 'ชื่อหน่วยงานที่รับฝึกประสบการณ์',
		`province` VARCHAR(100) NOT NULL COMMENT 'จังหวัด',
		`position` VARCHAR(255) NOT NULL COMMENT 'ตำแหน่งที่รับฝึกงาน',
		`year` SMALLINT UNSIGNED NOT NULL COMMENT 'ปี พ.ศ. ที่ฝึกงาน',
		`total_student` INT UNSIGNED NOT NULL COMMENT 'จำนวนนักศึกษา',
		`created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
	COMMENT='ตารางเก็บข้อมูลหน่วยงานที่รับฝึกงาน';
	";

  // Create access_logs table
  $sqlAccessLogs = "
    CREATE TABLE IF NOT EXISTS `access_logs` (
		`id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
		`created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
	";

  // Create user table
  $sqlUser = "
    CREATE TABLE `user` (
		`id` int(11) NOT NULL,
		`email` varchar(100) NOT NULL,
		`username` varchar(100) NOT NULL,
		`password` varchar(100) NOT NULL,
		`role` enum('admin','user') NOT NULL DEFAULT 'user'
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;
	";

  // Create the tables
  $conn->exec($sqlInternshipHistory);
  $conn->exec($sqlAccessLogs);
  $conn->exec($sqlUser);

  echo "<hr><strong>Setup completed successfully!</strong>";
} catch (PDOException $e) {
  echo "<strong>Error:</strong> " . htmlspecialchars($e->getMessage());
}
