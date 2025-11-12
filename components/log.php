<?php
require_once __DIR__ . '/../config/db_config.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$user_id = $_SESSION['user_id'] ?? NULL;
$ip_address = $_SERVER['REMOTE_ADDR'] ?? 'UNKNOWN';
$user_agent = $_SERVER['HTTP_USER_AGENT'] ?? 'UNKNOWN';

$sql_check =    "SELECT id FROM access_logs 
                WHERE ip_address = :ip_address 
                AND created_at >= NOW() - INTERVAL 1 second
                LIMIT 1";

$stmt_check = $conn->prepare($sql_check);
$stmt_check->bindParam(':ip_address', $ip_address);
$stmt_check->execute();

if ($stmt_check->rowCount() == 0) {
    $sql_insert =   "INSERT INTO access_logs (user_id, ip_address, user_agent) 
                    VALUES (:user_id, :ip_address, :user_agent)";

    $stmt_insert = $conn->prepare($sql_insert);
    $stmt_insert->bindParam(':user_id', $user_id);
    $stmt_insert->bindParam(':ip_address', $ip_address);
    $stmt_insert->bindParam(':user_agent', $user_agent);
    $stmt_insert->execute();
}
