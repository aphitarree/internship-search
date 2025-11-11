<?php
require_once __DIR__ . '/../includes/auth.php';

?>
<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <title>Internship Dashboard</title>

    <!-- Font Awesome -->
    <link href="../vendor/fortawesome/font-awesome/css/all.min.css" rel="stylesheet" type="text/css">

    <!-- Tailwind (CDN) -->
    <script src="https://cdn.tailwindcss.com"></script>

    <style>
        /* ซ่อน scrollbar ของ table container ให้ดูเนียนขึ้น (แล้วแต่ชอบ) */
        .no-scrollbar::-webkit-scrollbar {
            display: none;
        }

        .no-scrollbar {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }
    </style>
</head>

<body id="page-top" class="bg-gray-100 text-gray-800">
    <div id="wrapper" class="min-h-screen flex">

        <!-- Sidebar -->
        <?php include_once './components/sidebar.php'; ?>

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="flex-1 flex flex-col min-h-screen">

            <!-- Main Content -->
            <div id="content" class="flex-1 flex flex-col">

                <!-- Topbar -->
                <?php include_once './components/dashboard_navbar.php'; ?>

                <!-- Begin Page Content -->


                <?php include_once './components/table_insert_excel.php' ?>


                <form action="<?php echo $baseUrl; ?>/dashboard/actions/insert_excel.php" method="POST" enctype="multipart/form-data">
                    <input type="file" name="excel_file" required>
                    <button
                        class="flex h-11 rounded-md bg-slate-200 hover:bg-slate-300 px-4 text-center justify-center items-center"
                        type="submit" name="submit">
                        เพิ่มฐานข้อมูลจาก Excel
                    </button>
                </form>
            </div>
        </div>

        <!-- Footer -->
        <?php include_once './components/footer.php'; ?>
    </div>
    </div>

    <!-- Scroll to Top Button-->
    <button
        type="button"
        onclick="window.scrollTo({ top: 0, behavior: 'smooth' });"
        class="fixed bottom-5 right-5 w-10 h-10 rounded-full bg-indigo-600 text-white shadow-lg flex items-center justify-center hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-400">
        <i class="fas fa-angle-up"></i>
    </button>



    <!-- JS -->
    <script src="../vendor/jquery/jquery.min.js"></script>


</body>

</html>