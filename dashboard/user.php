<?php
require_once __DIR__ . '/../includes/auth.php';

?>
<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="icon" type="image/png" href="../public/images/favicon.ico">

    <title>จัดการข้อมูลผู้ใช้</title>

    <!-- Font Awesome -->
    <link href="../vendor/fortawesome/font-awesome/css/all.min.css" rel="stylesheet" type="text/css">

    <!-- Tailwind (CDN) -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- DataTables + jQuery (CDN)  -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.min.css">
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>

    <!-- Choices.js (CDN) -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/choices.js/public/assets/styles/choices.min.css" />

    <style>
        .no-scrollbar::-webkit-scrollbar {
            display: none;
        }

        .no-scrollbar {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }

        .dataTables_wrapper input {
            margin-bottom: 0.5rem;
        }
    </style>
</head>

<body id="page-top" class="bg-gray-100 text-gray-800">
    <div id="wrapper" class="min-h-screen flex flex-col lg:flex-row">

        <!-- Sidebar -->
        <?php include_once './components/sidebar.php'; ?>

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="flex-1 flex flex-col min-h-screen">

            <!-- Main Content -->
            <div id="content" class="flex-1 flex flex-col">

                <!-- Topbar -->
                <?php include_once './components/dashboard_navbar.php'; ?>

                <!-- Begin Page Content -->
                <div class="container mx-auto px-4 py-6 lg:px-8 lg:py-8">
                    <?php include_once './components/user_table.php' ?>
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