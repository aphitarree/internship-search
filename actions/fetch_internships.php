<?php

declare(strict_types=1);

ini_set('display_errors', '0');
ini_set('log_errors', '1');
ini_set('error_log', __DIR__ . '/php-error.log');
header('Content-Type: application/json; charset=utf-8');

require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/config/db_config.php';

use Dotenv\Dotenv;

Dotenv::createImmutable(__DIR__)->load();

try {
    $draw        = isset($_POST['draw'])   ? (int)$_POST['draw']   : 0;
    $start       = isset($_POST['start'])  ? (int)$_POST['start']  : 0;
    $length      = isset($_POST['length']) ? (int)$_POST['length'] : 10;
    $searchValue = $_POST['search']['value'] ?? '';

    $columns = ['organization', 'province', 'position', 'year', 'total_student'];
    $orderColIndex = isset($_POST['order'][0]['column']) ? (int)$_POST['order'][0]['column'] : 0;
    $orderDir = in_array($_POST['order'][0]['dir'] ?? 'asc', ['asc', 'desc'], true) ? $_POST['order'][0]['dir'] : 'asc';
    $orderCol = $columns[$orderColIndex] ?? 'year';

    $where = ' WHERE 1 ';
    $params = [];
    if ($searchValue !== '') {
        $where .= ' AND (organization LIKE :s OR province LIKE :s OR position LIKE :s OR CAST(year AS CHAR) LIKE :s OR CAST(total_student AS CHAR) LIKE :s)';
        $params[':s'] = "%{$searchValue}%";
    }

    $recordsTotal = (int)$conn->query('SELECT COUNT(*) FROM internship_stats')->fetchColumn();

    $stmt = $conn->prepare("SELECT COUNT(*) FROM internship_stats {$where}");
    foreach ($params as $k => $v) $stmt->bindValue($k, $v);
    $stmt->execute();
    $recordsFiltered = (int)$stmt->fetchColumn();

    $sql = "SELECT organization, province, position, year, total_student
          FROM internship_stats {$where}
          ORDER BY {$orderCol} {$orderDir}";
    if ($length !== -1) {
        $sql .= " LIMIT :start, :length";
    }

    $stmt = $conn->prepare($sql);
    foreach ($params as $k => $v) $stmt->bindValue($k, $v);
    if ($length !== -1) {
        $stmt->bindValue(':start', $start, PDO::PARAM_INT);
        $stmt->bindValue(':length', $length, PDO::PARAM_INT);
    }
    $stmt->execute();
    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode([
        'draw' => $draw,
        'recordsTotal' => $recordsTotal,
        'recordsFiltered' => $recordsFiltered,
        'data' => $data,
    ], JSON_UNESCAPED_UNICODE);
    exit;
} catch (Throwable $e) {
    http_response_code(500);
    echo json_encode(['error' => true, 'message' => 'Server error'], JSON_UNESCAPED_UNICODE);
    error_log($e);
    exit;
}
