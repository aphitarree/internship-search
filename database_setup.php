<?php
/**
 * database_setup.php
 *
 * This script connects to a MySQL database and sets up the necessary tables
 * for the internship management application.
 */

// 1. Include the credentials file.
require_once(__DIR__ . '/config/db_credentials.php');

// --- Main Execution ---
try {
    // 2. Connect to MySQL server (without selecting a database yet)
    $pdo = new PDO("mysql:host=$servername", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->exec("SET NAMES 'utf8mb4'");

    // 2. Create the database if it doesn't exist
    $pdo->exec("CREATE DATABASE IF NOT EXISTS `$dbname` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
    echo "Database '{$dbname}' created or already exists.<br>";

    // 3. Select the database
    $pdo->exec("USE `$dbname`");

    // 4. SQL to create the 'organizations' table
    $sqlOrganizations = "
    CREATE TABLE IF NOT EXISTS `organizations` (
        `id` INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        `faculty` VARCHAR(255) NOT NULL COMMENT 'คณะ/โรงเรียน',
        `program` VARCHAR(255) NOT NULL COMMENT 'หลักสูตร',
        `major` VARCHAR(255) NOT NULL COMMENT 'สาขาวิชา',
        `organization_name` VARCHAR(255) NOT NULL COMMENT 'ชื่อหน่วยงานที่รับฝึกประสบการณ์',
        `province` VARCHAR(100) NOT NULL COMMENT 'จังหวัด',
        `position_name` VARCHAR(255) NOT NULL COMMENT 'ตำแหน่งที่รับฝึกงาน',
        `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='ตารางเก็บข้อมูลหน่วยงานที่รับฝึกงาน';
    ";

    // 5. SQL to create the 'internship_stats' table
    $sqlInternshipStats = "
    CREATE TABLE IF NOT EXISTS `internship_stats` (
        `id` INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        `organization_id` INT(11) UNSIGNED NOT NULL COMMENT 'FK อ้างอิงไปยังตาราง organizations',
        `year_be` SMALLINT(4) NOT NULL COMMENT 'ปี พ.ศ. ที่ฝึกงาน',
        `student_count` INT(11) NOT NULL DEFAULT 0 COMMENT 'จำนวนนักศึกษาที่รับ',
        `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        UNIQUE KEY `org_year_unique` (`organization_id`, `year_be`),
        FOREIGN KEY (`organization_id`)
            REFERENCES `organizations`(`id`)
            ON DELETE CASCADE
            ON UPDATE CASCADE
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='ตารางเก็บสถิติจำนวนนักศึกษาฝึกงานในแต่ละปี';
    ";

    // 6. SQL to create the 'daily_visits' table
    $sqlDailyVisits = "
    CREATE TABLE IF NOT EXISTS `daily_visits` (
        `visit_date` DATE NOT NULL PRIMARY KEY COMMENT 'วันที่เข้าชม (YYYY-MM-DD)',
        `visit_count` INT(11) UNSIGNED NOT NULL DEFAULT 0 COMMENT 'จำนวนผู้เข้าชมในวันนั้น'
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='ตารางเก็บสถิติผู้เข้าชมเว็บไซต์รายวัน';
    ";

    // 7. Execute the table creation queries
    $pdo->exec($sqlOrganizations);
    echo "Table 'organizations' created or already exists.<br>";

    $pdo->exec($sqlInternshipStats);
    echo "Table 'internship_stats' created or already exists.<br>";

    $pdo->exec($sqlDailyVisits);
    echo "Table 'daily_visits' created or already exists.<br>";

    echo "<hr><strong>Setup completed successfully!</strong>";

} catch(PDOException $e) {
    // --- Error Handling ---
    echo "<strong>Error:</strong> " . $e->getMessage();
}

// Close the connection
$pdo = null;
