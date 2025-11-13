<?php
require_once __DIR__ . '/../config/db_config.php';

$sql_today = "SELECT COUNT(ip_address) AS total_today FROM access_logs WHERE DATE(created_at) = CURDATE()";
$stmt_today = $conn->query($sql_today);
$row_today = $stmt_today->fetch(PDO::FETCH_ASSOC);
$total_today = $row_today['total_today'] ?? 0;

$sql_7days = "SELECT COUNT(ip_address) AS total_7days FROM access_logs WHERE created_at >= NOW() - INTERVAL 7 DAY";
$stmt_7days = $conn->query($sql_7days);
$row_7days = $stmt_7days->fetch(PDO::FETCH_ASSOC);
$total_7days = $row_7days['total_7days'] ?? 0;

$sql_all = "SELECT COUNT(ip_address) AS total_all FROM access_logs";
$stmt_all = $conn->query($sql_all);
$row_all = $stmt_all->fetch(PDO::FETCH_ASSOC);
$total_all = $row_all['total_all'] ?? 0;

function formatNumber($number) {
    if ($number >= 1000000000) {
        return round($number / 1000000000, 1) . 'B';
    } elseif ($number >= 1000000) {
        return round($number / 1000000, 1) . 'M';
    } elseif ($number >= 1000) {
        return round($number / 1000, 1) . 'K';
    }
    return $number;
}
?>

<!-- Website access statistics -->
<aside class="flex flex-col items-center justify-center">
    <div class="w-full grid grid-cols-2 gap-4 sm:gap-6 lg:flex lg:flex-row md:flex md:mt-2 lg:items-center lg:gap-8">
        <!-- Today  -->
        <div class="content-center w-full min-h-[200px] max-w-[400px] bg-sky-400 text-white rounded-[20px] shadow-md px-6 py-6 text-center">
            <div id="today" class="text-3xl sm:text-5xl md:text-5xl font-bold mb-2"><?= formatNumber($total_today) ?></div>
            <div class="text-base sm:text-xl md:text-2xl">จำนวนการเข้าชมวันนี้</div>
        </div>

        <!-- Last 7 days -->
        <div class="content-center w-full min-h-[200px] max-w-[400px] bg-cyan-50 rounded-[20px] shadow-md px-6 py-6 text-center">
            <div id="last-seven-day" class="text-3xl sm:text-5xl md:text-5xl font-bold mb-2"><?= formatNumber($total_7days) ?></div>
            <div class="text-base sm:text-xl md:text-2xl">จำนวนการเข้าชมย้อนหลัง 7 วัน</div>
        </div>

        <!-- Accumulated -->
        <div class="content-center w-full min-h-[200px] max-w-[400px] bg-cyan-50 rounded-[20px] shadow-md px-6 py-6 text-center">
            <div id="totalAll" class="text-3xl sm:text-5xl md:text-5xl font-bold mb-2"><?= formatNumber($total_all) ?></div>
            <div class="text-base sm:text-xl md:text-2xl">จำนวนการเข้าชมสะสม</div>
        </div>
    </div>
</aside>