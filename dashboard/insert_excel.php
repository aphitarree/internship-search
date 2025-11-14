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

    <title>เพิ่มข้อมูล Excel</title>

    <!-- Font Awesome -->
    <link href="../vendor/fortawesome/font-awesome/css/all.min.css" rel="stylesheet" type="text/css">

    <!-- Tailwind (CDN) -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- DataTables + jQuery (CDN)  -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.min.css">
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>

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
    <div id="wrapper" class="min-h-screen flex flex-col md:flex-row">

        <!-- Sidebar -->
        <?php include_once './components/sidebar.php'; ?>

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="flex-1 flex flex-col min-h-screen">

            <!-- Main Content -->
            <div id="content" class="flex-1 flex flex-col">

                <!-- Topbar -->
                <?php include_once './components/dashboard_navbar.php'; ?>

                <form
                    action="<?php echo $baseUrl; ?>/dashboard/actions/insert_excel_form.php"
                    method="POST"
                    enctype="multipart/form-data"
                    class="container mx-auto px-4 lg:px-8 mt-4">

                    <section>
                        <h1 class="text-2xl font-semibold text-gray-800 mb-5">
                            <i class="fas fa-file-excel text-green-600 text-2xl"></i>
                            เพิ่มฐานข้อมูลจาก Excel
                        </h1>

                        <!-- ปุ่มอัปโหลด -->
                        <main class="flex items-center gap-3">
                            <input
                                type="file"
                                name="excel_file"
                                id="excel_file"
                                accept=".xlsx,.xls,.csv"
                                required
                                class="block w-64 text-sm text-gray-700 bg-gray-50 border border-gray-300 rounded-md cursor-pointer focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-blue-600 file:hover:bg-blue-700 file:text-white transition" />

                            <button
                                id="uploadBtn"
                                type="submit"
                                name="submit"
                                class="hidden flex items-center gap-2 text-sm bg-blue-600 hover:bg-blue-700 text-white font-medium px-3 py-2 rounded-md shadow-sm transition focus:outline-none focus:ring-2 focus:ring-indigo-400 focus:ring-offset-1">
                                <i class="fas fa-upload"></i>
                                อัปโหลดไฟล์
                            </button>
                        </main>

                        <script>
                            const fileInput = document.getElementById('excel_file');
                            const uploadBtn = document.getElementById('uploadBtn');

                            fileInput.addEventListener('change', function() {
                                if (fileInput.files.length > 0) {
                                    uploadBtn.classList.remove('hidden');
                                } else {
                                    uploadBtn.classList.add('hidden');
                                }
                            });
                        </script>
                    </section>

                    <!-- Insert guide notice -->
                    <section class="mt-6 rounded-md border-l-4 border-amber-500 bg-amber-50 p-4 text-sm text-amber-800">
                        <p class="font-semibold">ข้อควรระวัง:</p>
                        <ul class="mt-2 list-disc space-y-1 pl-5">
                            <li>รองรับไฟล์เฉพาะ Excel เท่านั้น (.xlsx, .xls, และ .csv) โปรดจัดรูปแบบให้ถูกต้องตามคู่มือก่อนอัปโหลด</li>
                            <li>แถวแรกต้องเป็น Header และมีชื่อคอลัมน์ที่ถูกต้อง</li>
                            <li>ข้อมูลต้องเริ่มที่แถวที่ 2 เป็นต้นไป</li>
                        </ul>
                    </section>
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