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

    <?php if (!empty($_SESSION['message'])): ?>
        <div class="alert alert-warning alert-dismissible fade show" role="alert">
            <?php echo $_SESSION['message']; ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        <?php unset($_SESSION['message']); ?>
    <?php endif; ?>

    <div id="wrapper" class="min-h-screen flex">

        <!-- Sidebar -->
        <?php include_once './components/sidebar.php'; ?>

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="flex-1 flex flex-col min-h-screen">

            <!-- Main Content -->
            <div id="content" class="flex-1 flex flex-col">

                <!-- Topbar -->
                <?php include_once './components/dashboard_navbar.php'; ?>

                <form action="<?php echo $baseUrl; ?>/dashboard/actions/insert_excel_form.php"
                    method="POST"
                    enctype="multipart/form-data"
                    class="container mx-auto px-4 py-2 lg:px-8 lg:py-2">

                    <div>
                        <!-- หัวข้อ -->
                        <div class="">
                            <h2 class="text-lg font-semibold text-gray-700 mb-2">
                                <i class="fas fa-file-excel text-green-600 text-xl"></i>
                                เพิ่มฐานข้อมูลจาก Excel
                            </h2>
                        </div>

                        <!-- ปุ่มอัปโหลด -->
                        <div class="flex items-center gap-3">
                            <input
                                type="file"
                                name="excel_file"
                                id="excel_file"
                                accept=".xlsx,.xls"
                                required
                                class="block w-64 text-sm text-gray-700 bg-gray-50 border border-gray-300 rounded-md cursor-pointer focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-indigo-600 file:text-white hover:file:bg-indigo-700 transition" />

                            <button
                                type="submit"
                                name="submit"
                                class="flex items-center gap-2 text-sm bg-indigo-600 hover:bg-indigo-700 text-white font-medium px-3 py-2 rounded-md shadow-sm transition focus:outline-none focus:ring-2 focus:ring-indigo-400 focus:ring-offset-1">
                                <i class="fas fa-upload"></i>
                                อัปโหลดไฟล์
                            </button>
                        </div>
                    </div>

                    <!-- คำแนะนำเล็กๆ ใต้ input -->
                    <p class="mt-2 text-sm text-gray-500">
                        รองรับไฟล์เฉพาะ Excel เท่านั้น (.xlsx, .xls, และ .csv) โปรดจัดรูปแบบให้ถูกต้องตามคู่มือก่อนอัปโหลด
                    </p>
                </form>

                <div class="container mx-auto px-4 py-6 lg:px-8 lg:py-2">
                    <?php include_once './components/insert_excel_table.php' ?>
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