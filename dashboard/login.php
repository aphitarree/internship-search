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
    <!-- Font Awesome -->
    <link href="../vendor/fortawesome/font-awesome/css/all.min.css" rel="stylesheet" type="text/css">
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="relative flex flex-col min-h-screen">
    <!-- Navbar -->
    <?php include dirname(__DIR__) . '/components/navbar.php'; ?>

    <!-- Layout -->
    <div class="flex min-h-screen">
        <div class="mx-auto w-full grid lg:grid-cols-[8fr,9fr]">
            <!-- Background -->
            <img
                src="../public/images/login_page.jpg"
                alt="background"
                class="absolute inset-0 w-full h-full object-cover z-4" />

            <!-- Login Box -->
            <div class="flex">
                <div class="h-full w-24 bg-sky-500 z-10"></div>
                <div class="relative z-10 bg-white p-12 shadow-xl flex flex-col justify-center space-y-8">
                    <h1 class="text-5xl font-bold text-sky-500 text-center">เข้าสู่ระบบ</h1>
                    <p class="text-2xl text-gray-400 text-center">
                        <span class="inline-block">ฐานข้อมูล</span><span class="inline-block">การฝึกงาน</span><span class="inline-block">นักศึกษา</span><span class="inline-block">มหาวิทยาลัย</span><span class="inline-block">สวนดุสิต</span>
                    </p>

                    <form action="<?php echo $baseUrl . '/actions/login_form.php'; ?>" method="POST" class="flex flex-col space-y-6 w-full max-w-lg mx-auto">
                        <?php if (!empty($_SESSION['message'])): ?>
                            <div class=" rounded-md indent-[1rem] bg-amber-200 z-10 py-2" role="alert">
                                <?php echo $_SESSION['message']; ?>
                                <button type="button" class=""></button>
                                <?php unset($_SESSION['message']); ?>
                            </div>
                        <?php endif; ?>

                        <!-- Input email -->
                        <section class="relative w-full">
                            <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-500">
                                <i class="fa fa-envelope"></i>
                            </span>
                            <input
                                type="email"
                                name="email"
                                placeholder="Email address"
                                class="w-full border border-gray-400 rounded-lg pl-12 pr-5 py-2 text-lg text-gray-500 focus:ring-2 focus:ring-sky-400 focus:outline-none" />
                        </section>

                        <!-- Input password -->
                        <section class="relative w-full">
                            <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-500">
                                <i class="fa fa-lock"></i>
                            </span>
                            <input
                                type="password"
                                name="password"
                                placeholder="Password"
                                class="w-full border border-gray-400 rounded-lg pl-12 pr-5 py-2 text-lg text-gray-500 focus:ring-2 focus:ring-sky-400 focus:outline-none" />
                        </section>

                        <!-- Submit login button -->
                        <button
                            type="submit"
                            class="bg-sky-500 text-white text-2xl py-2 rounded-lg hover:bg-sky-600 transition">
                            เข้าสู่ระบบ
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>

</html>