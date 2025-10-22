<?php
/**
 * db_config.php
 *
 * Central database connection file.
 * This file establishes a PDO connection that other scripts can reuse.
 */

// 1. Include the credentials file.
require_once 'db_credentials.php';

// 2. PDO Connection ---
try {
    $pdo = new PDO("mysql:host=$servername;port=$port;dbname=$dbname;charset=utf8mb4", $username, $password);
    
    // Set the PDO error mode to exception
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

} catch(PDOException $e) {
    // If the connection fails, stop everything and display an error.
    die("ERROR: Could not connect to the database. " . $e->getMessage());
}
