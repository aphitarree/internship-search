<?php
/**
 * tracker.php
 *
 * This script tracks website visits on a daily basis.
 * Include this file at the top of every page you want to track.
 */

// 1. Include the central database connection file.
// require_once ensures the file is included only once and will cause a fatal error if it's missing.
require_once 'db_config.php';

// The $pdo object is now available from db_config.php

try {
    // 2. Get today's date in YYYY-MM-DD format
    $today = date('Y-m-d');

    // 3. Prepare and execute the query to update the visitor count
    $sql = "
        INSERT INTO `daily_visits` (`visit_date`, `visit_count`) 
        VALUES (:visit_date, 1)
        ON DUPLICATE KEY UPDATE `visit_count` = `visit_count` + 1;
    ";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['visit_date' => $today]);

} catch (PDOException $e) {
    // Optional: Log the error instead of showing it to the user.
    // error_log("Tracker Error: " . $e->getMessage());
}

// No need to close the connection here, it can be left open for other scripts.
// $pdo = null;
