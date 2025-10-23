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
	$dsn = "mysql:host=$dbServerName;dbname=$dbName;charset=utf8";

	// Connect the database
	$conn = new PDO($dsn, $dbUsername, $dbPassword);

	// Configure database connection options
	$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	$conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
	$conn->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
} catch (PDOException $e) {
	echo 'failed to connect' . $e->getMessage();
}
