<?php
require_once __DIR__ . '/../config/db_config.php';

// if (session_status() === PHP_SESSION_NONE) {
// }

$userId = $_SESSION['user_id'] ?? NULL;
$ipAddress = $_SERVER['REMOTE_ADDR'] ?? 'UNKNOWN';
$userAgent = $_SERVER['HTTP_USER_AGENT'] ?? 'UNKNOWN';

$sqlCheck =    "SELECT id FROM access_logs 
                WHERE ip_address = :ip_address 
                AND created_at >= NOW() - INTERVAL 1 HOUR
                LIMIT 1";

$stmtCheck = $conn->prepare($sqlCheck);
$stmtCheck->bindParam(':ip_address', $ipAddress);
$stmtCheck->execute();

if ($stmtCheck->rowCount() == 0) {
    $sqlInsert =   "INSERT INTO access_logs (user_id, ip_address, user_agent) 
                    VALUES (:user_id, :ip_address, :user_agent)";

    $stmtInsert = $conn->prepare($sqlInsert);
    $stmtInsert->bindParam(':user_id', $userId);
    $stmtInsert->bindParam(':ip_address', $ipAddress);
    $stmtInsert->bindParam(':user_agent', $userAgent);
    $stmtInsert->execute();
}
