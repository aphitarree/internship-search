<?php
require_once __DIR__ . '/../config/db_config.php';

$sqlYears = "
    SELECT DISTINCT YEAR(created_at) AS year 
    FROM access_logs 
    ORDER BY year DESC
";
$stmtYears = $conn->query($sqlYears);
$years = $stmtYears->fetchAll(PDO::FETCH_COLUMN);

$currentYear = (int) date('Y');

$selectedYear = isset($_GET['year']) ? (int) $_GET['year'] : $currentYear;

if (!in_array($selectedYear, array_map('intval', $years), true)) {
    if (!empty($years)) {
        $selectedYear = (int) $years[0];
    } else {
        $selectedYear = $currentYear;
    }
}

$sqlToday = "
    SELECT COUNT(ip_address) AS total_today 
    FROM access_logs 
    WHERE DATE(created_at) = CURDATE()
        AND YEAR(created_at) = :year
";
$stmtToday = $conn->prepare($sqlToday);
$stmtToday->execute([':year' => $selectedYear]);
$row_today = $stmtToday->fetch(PDO::FETCH_ASSOC);
$totalToday = $row_today['total_today'] ?? 0;

// Last 7 days (ของปีที่เลือก)
$sql7days = "
    SELECT COUNT(ip_address) AS total_7days 
    FROM access_logs 
    WHERE created_at >= NOW() - INTERVAL 7 DAY
      AND YEAR(created_at) = :year
";
$stmt7days = $conn->prepare($sql7days);
$stmt7days->execute([':year' => $selectedYear]);
$row_7days = $stmt7days->fetch(PDO::FETCH_ASSOC);
$total7days = $row_7days['total_7days'] ?? 0;

// Accumulated (สะสมทั้งปีที่เลือก)
$sqlAll = "
    SELECT COUNT(ip_address) AS total_all 
    FROM access_logs
    WHERE YEAR(created_at) = :year
";
$stmtAll = $conn->prepare($sqlAll);
$stmtAll->execute([':year' => $selectedYear]);
$rowAll = $stmtAll->fetch(PDO::FETCH_ASSOC);
$totalAll = $rowAll['total_all'] ?? 0;

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

<!-- เลือกปี -->

<section class="flex flex-col justify-center items-center">
    <!-- Website access statistics -->
    <aside class="flex flex-col items-center justify-center mt-4">
        <!-- แสดงว่าตอนนี้ดูสถิติของปีไหน -->
        <div class="mb-2 text-sm sm:text-base text-gray-600">
            แสดงสถิติของปี:
            <span class="font-semibold">
                <?= htmlspecialchars($selectedYear + 543, ENT_QUOTES, 'UTF-8') ?>
            </span>
        </div>

        <div class="w-full grid grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6 items-stretch">
            <!-- Today  -->
            <div
                class="col-span-2 lg:col-span-1 h-full flex flex-col justify-center content-center w-full bg-sky-400 text-white rounded-[20px] shadow-md px-6 py-6 text-center">
                <div id="today" class="text-3xl sm:text-5xl md:text-5xl font-bold mb-2">
                    <?= formatNumber($totalToday) ?>
                </div>
                <div class="text-xl sm:text-2xl md:text-2xl">
                    จำนวนการใช้งานวันนี้
                </div>
            </div>

            <!-- Last 7 days -->
            <div
                class="h-full flex flex-col justify-center content-center w-full bg-cyan-50 rounded-[20px] shadow-md px-6 py-6 text-center">
                <div id="last-seven-day" class="text-3xl sm:text-5xl md:text-5xl font-bold mb-2">
                    <?= formatNumber($total7days) ?>
                </div>
                <div class="text-xl sm:text-2xl md:text-2xl">
                    จำนวนการใช้งานย้อนหลัง 7 วัน
                </div>
            </div>

            <!-- Accumulated -->
            <div
                class="h-full flex flex-col justify-center content-center w-full bg-cyan-50 rounded-[20px] shadow-md px-6 py-6 text-center">
                <div id="totalAll" class="text-3xl sm:text-5xl md:text-5xl font-bold mb-2">
                    <?= formatNumber($totalAll) ?>
                </div>
                <div class="text-xl sm:text-2xl md:text-2xl">
                    จำนวนการใช้งานสะสม
                </div>
            </div>
        </div>
    </aside>

    <div class="w-full flex justify-end mt-4">
        <form method="GET" class="flex items-center gap-2">
            <label for="year" class="text-sm sm:text-base text-gray-700">
                เลือกปี:
            </label>
            <select
                id="year"
                name="year"
                onchange="this.form.submit()"
                class="border border-gray-300 rounded-lg px-3 py-2 text-sm sm:text-base focus:outline-none focus:ring-2 focus:ring-sky-400">
                <?php foreach ($years as $year): ?>
                    <option
                        value="<?= htmlspecialchars($year, ENT_QUOTES, 'UTF-8') ?>"
                        <?= ((int)$year === (int)$selectedYear) ? 'selected' : '' ?>>
                        <?= htmlspecialchars($year + 543, ENT_QUOTES, 'UTF-8') ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </form>
    </div>
</section>