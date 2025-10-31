<?php
// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);
require_once __DIR__ . '/../config/db_config.php';

$email = $_POST['email'];
$username = $_POST['username'];
$password = $_POST['password'];

$options = [
    'cost' => 13,
];
$hashPassword = password_hash($password, PASSWORD_BCRYPT, $options);

$sql = 'INSERT INTO user (email, username, password) VALUES (:email, :username, :password)';
$stmt = $conn->prepare($sql);
$stmt->bindParam(':email', $email);
$stmt->bindParam(':username', $username);
$stmt->bindParam(':password', $hashPassword);
$stmt->execute();

if ($stmt) {
    header("Location: index.php");
} else {
    header("Location: index.php");
}
