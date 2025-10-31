<?php
?>
<!-- Bar chart -->
<!-- <section class="flex flex-col items-center gap-4 sm:gap-6 lg:gap-8 h-64 sm:h-80 md:h-96 lg:h-[600px]"> -->
<!-- <section class="flex flex-col items-center gap-4 sm:gap-6 lg:gap-8 min-h-screen"> -->
<section class="flex flex-col items-center gap-4 sm:gap-6 lg:gap-8 h-full w-full">
    <h2 class="text-2xl sm:text-3xl md:text-4xl lg:text-5xl font-semibold text-center">
        จำนวนการเข้าชม (คน)
    </h2>


    <!-- Chart container ที่ยืดเต็มความสูง -->
    <div class="w-full max-w-[820px] flex-1 relative">
        <canvas id="chart" class="w-full h-full"></canvas>
    </div>

    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script type="module" defer>
        const values = [20, 10, 90, 53, 80, 20, 40];
        const dates = ['6 ม.ค. 65', '7 ม.ค. 65', '8 ม.ค. 65', '9 ม.ค. 65', '10 ม.ค. 65', '11 ม.ค. 65', '12 ม.ค. 65'];

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
                        'rgba(251, 191, 36, 0.9)' // amber
                        :
                        'rgba(14, 165, 233, 0.6)' // sky
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
                        backgroundColor: 'rgba(0, 0, 0, 0.8)',
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
                            stepSize: 20,
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