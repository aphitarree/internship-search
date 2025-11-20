<?php
?>

<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="icon" type="image/png" href="./public/images/favicon.ico">

    <title>รวมข้อมูลแหล่งฝึกงาน</title>

    <!-- Font Awesome -->
    <link href="./vendor/fortawesome/font-awesome/css/all.min.css" rel="stylesheet" type="text/css">

    <!-- Tailwind-->
    <link rel="stylesheet" href="./public/css/tailwind.css">

    <!-- Choices.js-->
    <link rel="stylesheet" href="./public/css/choices.min.css" />
    <script src="./public/js/choices.min.js" defer></script>

    <!-- Datatables-->
    <link rel="stylesheet" href="./public/css/dataTables.dataTables.min.css">
    <script src="./public/js/jquery-3.7.1.js" defer></script>
    <script src="./public/js/dataTables.min.js" defer></script>

    <!-- Thai Sarabun font -->
    <link href="https://fonts.googleapis.com/css2?family=Sarabun:wght@200;300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Sarabun', sans-serif;
        }
    </style>
</head>

<body class="bg-white text-gray-900">
    <!-- Navigator bar -->
    <?php include_once './components/navbar.php'; ?>

    <!-- Banner -->
    <section class="relative w-full">
        <img src="public/images/background-1.jpg" alt="Banner" class="w-full h-[185px] object-cover" />
        <div class="absolute inset-0 bg-black/25"></div>
        <style>
            .h1-title {
                line-height: 1.65;
            }
        </style>
        <h1 class="h1-title absolute inset-0 flex items-center justify-center text-white text-2xl md:text-3xl font-semibold text-center">
            ฐานข้อมูลเครือข่ายความร่วมมือในการฝึกงานนักศึกษา มหาวิทยาลัยสวนดุสิต
        </h1>


    </section>

    <section class="md:container md:mx-auto">
        <main class="mx-auto w-full max-w-[1900px] px-4 py-2 mt-4 grid grid-cols-1 gap-6 lg:grid-cols-2 lg:gap-8">
            <section class="flex flex-col justify-center md:text-center lg:text-left">
                <style>
                    /* sm (Tailwind) */
                    .h2-title {
                        font-size: 1.65rem;
                        line-height: 1.65;
                        text-align: left;
                    }

                    .h3-title {
                        font-size: 1.45rem;
                        line-height: 1.65;
                        text-align: left;
                    }

                    .p-description {
                        font-size: 1rem;
                        line-height: 2;
                        text-align: left;
                    }

                    /* md (Tailwind) */
                    @media (min-width: 768px) {
                        .h2-title {
                            font-size: 2rem;
                            line-height: 1.65;
                            text-align: left;
                        }

                        .h3-title {
                            font-size: 1.5rem;
                            line-height: 1.65;
                            text-align: left;
                        }

                        .p-description {
                            font-size: 1.25rem;
                            line-height: 2;
                            text-align: left;
                        }
                    }

                    /* size > md (Tailwind) */
                    @media (min-width: 1024px) {
                        .h2-title {
                            font-size: 2.45rem;
                            line-height: 1.6;
                        }

                        .h3-title {
                            font-size: 2rem;
                            line-height: 1.5;
                            margin-top: 0.5rem;
                        }

                        .p-description {
                            font-size: 1.55rem;
                            line-height: 1.8;
                            margin-top: 1rem;
                        }
                    }
                </style>
                <h2 class="h2-title font-bold mb-3">
                    <span class="inline-block">
                        ฐานข้อมูลเครือข่ายความร่วมมือในการฝึกงานนักศึกษา
                    </span>
                </h2>

                <h3 class="h3-title text-sky-500 mb-3">
                    รวมข้อมูลแหล่งฝึกงานมหาวิทยาลัยสวนดุสิต
                    </h2>
                    <p class="p-description">
                        เป็นแหล่งรวมข้อมูลการฝึกงานที่เกี่ยวข้อง ประกอบด้วย หน่วยงาน สถานประกอบการ และจังหวัด จำแนกตามปีการศึกษา พร้อมข้อมูลประกอบการตัดสินใจอื่น ๆ เพื่อช่วยให้นักศึกษามหาวิทยาลัยสวนดุสิต สามารถค้นหาสถานที่ฝึกงานได้สะดวกยิ่งขึ้น
                    </p>
            </section>
            <div>
                <?php include_once './components/chart.php' ?>
                <?php include_once './components/access_stats.php' ?>
            </div>
        </main>

        <!-- Filters -->
        <?php include_once './components/filter_search.php' ?>

        <!-- Datatables -->
        <?php include_once './components/datatables.php' ?>

        <section class="flex flex-row justify-center items-center gap-4 md:flex-row md:gap-6 my-6">
            <!-- Download pdf report -->
            <?php include_once './components/pdf_report_button.php' ?>

            <!-- Excel Button-->
            <?php include_once './components/excel_report_button.php' ?>
        </section>
    </section>

    <!-- Keep log -->
    <?php include_once './components/log.php'; ?>
    <!-- Footer bar -->
    <?php include_once './components/footer.php'; ?>
</body>

</html>