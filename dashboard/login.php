<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../config/db_config.php';

use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(dirname(__DIR__));
$dotenv->load();


$baseUrl = $_ENV['BASE_URL'];

session_start();

if (isset($_SESSION['checklogin'])) {
    header("Location: {$baseUrl}/dashboard/index.php");
    exit;
}

?>

<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>เข้าสู่ระบบ - ฐานข้อมูลการฝึกงานนักศึกษา</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="min-h-screen bg-white relative flex flex-col">
    <?php if (!empty($_SESSION['message'])): ?>
        <div class="alert alert-warning alert-dismissible fade show my-4" role="alert">
            <?php echo $_SESSION['message']; ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            <?php unset($_SESSION['message']); ?>
        </div>
    <?php endif; ?>
    <!-- Navbar -->
    <!-- <?php include dirname(__DIR__) . '/components/navbar.php'; ?> -->

    <!-- Layout -->
    <div class="flex min-h-screen">
        <!-- Sidebar -->


        <!-- Main Content -->
        <!-- <div class="flex-1 flex relative"> -->
        <div class="mx-auto w-full grid lg:grid-cols-[8fr,9fr]">
            <!-- Background -->
            <img src="../public/images/login_page.jpg" alt="background" class="absolute inset-0 w-full h-full object-cover" />

            <!-- Login Box -->
            <div class="relative z-10 bg-white p-12 shadow-xl flex flex-col justify-center space-y-8">
                <h1 class="text-6xl font-bold text-sky-500 text-center">เข้าสู่ระบบ</h1>
                <p class="text-2xl text-gray-400 text-center">
                    ฐานข้อมูลการฝึกงานนักศึกษา มหาวิทยาลัยสวนดุสิต
                </p>

                <form action="<?php echo $baseUrl . '/actions/login_form.php'; ?>" method="POST" class="flex flex-col space-y-6 w-full max-w-lg mx-auto">

                    <input
                        type="email"
                        name="email"
                        placeholder="Email address"
                        class="w-full border border-gray-400 rounded-lg px-5 py-2 text-lg text-gray-500 focus:ring-2 focus:ring-sky-400 focus:outline-none" />
                    <input
                        type="password"
                        placeholder="Password"
                        name="password"
                        class="w-full border border-gray-400 rounded-lg px-5 py-2 text-lg text-gray-500 focus:ring-2 focus:ring-sky-400 focus:outline-none" />
                    <button
                        type="submit"
                        class="bg-sky-500 text-white font-bold text-2xl py-2 rounded-lg hover:bg-sky-600 transition">
                        เข้าสู่ระบบ
                    </button>
                </form>
            </div>
        </div>
    </div>
</body>

</html>