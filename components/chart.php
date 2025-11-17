<?php
require_once __DIR__ . '/../config/db_config.php';

$sql = "
    SELECT DATE(created_at) AS visit_date, COUNT(*) AS total
    FROM access_logs
    WHERE created_at >= CURDATE() - INTERVAL 6 DAY
    GROUP BY DATE(created_at)
    ORDER BY DATE(created_at) ASC
";
$stmt = $conn->query($sql);
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

$dates = [];
$values = [];

for ($i = 6; $i >= 0; $i--) {
    $day = date('Y-m-d', strtotime("-$i days"));
    $thai_months = ['ม.ค.', 'ก.พ.', 'มี.ค.', 'เม.ย.', 'พ.ค.', 'มิ.ย.', 'ก.ค.', 'ส.ค.', 'ก.ย.', 'ต.ค.', 'พ.ย.', 'ธ.ค.'];
    $day_j = date('j', strtotime($day));
    $month = $thai_months[date('n', strtotime($day)) - 1];
    $year = date('y', strtotime($day)); // ปีสองหลัก
    $dates[] = "$day_j $month $year";

    $found = false;
    foreach ($rows as $row) {
        if ($row['visit_date'] == $day) {
            $values[] = (int)$row['total'];
            $found = true;
            break;
        }
    }
    if (!$found) $values[] = 0;
}

$js_dates = json_encode($dates);
$js_values = json_encode($values);
?>

<!-- Chart Section -->
<section class="flex flex-col items-center gap-4 sm:gap-6 lg:gap-8 w-full">
    <h2 class="text-2xl sm:text-3xl md:text-4xl lg:text-5xl font-semibold text-center">
        สถิติผู้ใช้งาน (คน)
    </h2>

    <div class="relative w-full h-[300px] max-w-[820px]">
        <canvas id="chart" class="w-full h-full"></canvas>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script type="module" defer>
        const values = <?= $js_values ?>;
        const dates = <?= $js_dates ?>;

        const barChart = document.getElementById('chart').getContext('2d');

        new Chart(barChart, {
            type: 'bar',
            data: {
                labels: dates,
                datasets: [{
                    label: 'จำนวนเข้าชม (คน)',
                    data: values,
                    backgroundColor: values.map((_, i) =>
                        i === values.length - 1 ?
                        'rgba(251, 191, 36, 0.9)' : // ไฮไลต์วันล่าสุด
                        'rgba(14, 165, 233, 0.6)' // วันอื่น
                    ),
                    borderColor: 'rgba(14, 165, 233, 1)',
                    borderWidth: 0,
                    borderRadius: 3
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        backgroundColor: 'rgba(0,0,0,0.8)',
                        titleFont: {
                            size: 14
                        },
                        bodyFont: {
                            size: 13
                        },
                        padding: 10
                    }
                },
                scales: {
                    x: {
                        ticks: {
                            font: {
                                size: 14
                            },
                            maxRotation: 60,
                            minRotation: 45
                        },
                        grid: {
                            display: false
                        }
                    },
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1,
                            font: {
                                size: 13
                            }
                        },
                        grid: {
                            color: 'rgba(0,0,0,0.05)'
                        }
                    }
                }
            }
        });
    </script>
</section>