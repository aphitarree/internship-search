<?php
?>

<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="icon" type="image/png" href="./public/images/favicon.ico">

    <title>คลังประวัติการฝึกงาน</title>

    <!-- Font Awesome -->
    <link href="./vendor/fortawesome/font-awesome/css/all.min.css" rel="stylesheet" type="text/css">

    <!-- Tailwind CDN -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Choices.js (CDN) -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/choices.js/public/assets/styles/choices.min.css" />
    <script src="https://cdn.jsdelivr.net/npm/choices.js/public/assets/scripts/choices.min.js"></script>

    <!-- Datatables (CDN) -->
    <link rel="stylesheet" href="https://cdn.datatables.net/2.3.4/css/dataTables.dataTables.min.css">
    <script src="https://code.jquery.com/jquery-3.7.1.js"></script>
    <script src="https://cdn.datatables.net/2.3.4/js/dataTables.min.js"></script>

    <link rel="stylesheet" href="css/globals.css">
</head>

<body class="bg-white text-gray-900">
    <!-- Navigator bar -->
    <?php include_once './components/navbar.php'; ?>

    <!-- Banner -->
    <section class="relative w-full">
        <img src="public/images/background-1.jpg" alt="Banner" class="w-full h-[185px] object-cover" />
        <div class="absolute inset-0 bg-black/25"></div>
        <h1 class="absolute inset-0 flex items-center justify-center text-white text-2xl md:text-3xl font-semibold text-center">
            ฐานข้อมูลเครือข่ายความร่วมมือในการฝึกงาน มหาวิทยาลัยสวนดุสิต
        </h1>
    </section>

    <section class="md:container md:mx-auto">
        <main class="mx-auto w-full max-w-[1900px] px-4 py-2 mt-4 grid grid-cols-1 gap-6 lg:grid-cols-2 lg:gap-8">
            <section class="flex flex-col justify-center md:text-center lg:text-left">
                <h1 class="text-5xl leading-[1.3] my-5 font-bold">
                    <span class="inline-block">ฐานข้อมูล</span><span class="inline-block">เครือข่าย</span><span class="inline-block">ความร่วมมือ</span><span class="inline-block">ในการฝึกงาน</span>
                </h1>

                <h2 class="text-4xl leading-[1.3] text-sky-500 mb-5">
                    รวมข้อมูลฝึกงานมหาวิทยาลัยสวนดุสิต
                </h2>
                <p class="text-2xl leading-[2]">
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