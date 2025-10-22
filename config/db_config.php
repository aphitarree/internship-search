<?php
require_once __DIR__ . '/vendor/autoload.php';

use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();

// Use the config variable from .env file
define('SERVER_NAME', $_ENV['DB_SERVER_NAME']);
define('PORT',        $_ENV['DB_PORT'] ?? '3306');
define('DB_NAME',     $_ENV['DB_NAME']);
define('DB_USERNAME', $_ENV['DB_USERNAME']);
define('DB_PASSWORD', $_ENV['DB_PASSWORD']);

try {
	// Connect database server
	$dsnServerOnly = "mysql:host=" . SERVER_NAME . ";port=" . PORT . ";charset=utf8mb4";
	$conn = new PDO($dsnServerOnly, DB_USERNAME, DB_PASSWORD, [
		PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
	]);

	// Create a database
	$dbName = DB_NAME;
	$conn->exec("CREATE DATABASE IF NOT EXISTS {$dbName} CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
	// echo "Database {$dbName} created or already exists.<br>";

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

	$sqlUser = "
    CREATE TABLE `user` (
		`id` int(11) NOT NULL,
		`email` varchar(100) NOT NULL,
		`username` varchar(100) NOT NULL,
		`password` varchar(100) NOT NULL,
		`role` enum('admin','user') NOT NULL DEFAULT 'user'
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;
	";

	$conn->exec($sqlInternshipHistory);
	// echo "Table `internship_history` created or already exists.<br>";

	$conn->exec($sqlAccessLogs);
	// echo "Table `access_logs` created or already exists.<br>";

	$conn->exec($sqlUser);
	// echo "Table `access_logs` created or already exists.<br>";

	// echo "<hr><strong>Setup completed successfully!</strong>";
} catch (PDOException $e) {
	// echo "<strong>Error:</strong> " . htmlspecialchars($e->getMessage());
}
