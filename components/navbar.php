<?php
require_once __DIR__ . '/../vendor/autoload.php';

use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(dirname(__DIR__));
$dotenv->load();

$baseUrl = $_ENV['BASE_URL'];
$baseDashboardUrl = $_ENV['BASE_URL'] . '/dashboard' ?? '';

$protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https://" : "http://";
$host = $_SERVER['HTTP_HOST'];
$requestUri = $_SERVER['REQUEST_URI'];

$fullUrl = $protocol . $host . $requestUri;
?>

<header class="w-full bg-white border-b shadow-sm relative z-50">
    <nav
        class="container mx-auto flex flex-col flex-1 md:flex-row sm:items-center sm:justify-between gap-4 md:gap-4 p-4">
        <div class="flex items-center justify-between">
            <!-- Logo -->
            <?php if ($fullUrl === $baseDashboardUrl . '/login.php'): ?>
                <a href="https://regis.dusit.ac.th/" class="flex items-center space-x-3">
                    <img src="<?= $baseUrl . '/public/images/SDU Logo.png' ?>" alt="SDU" class="h-11 w-auto" />
                    <span class="text-xl md:text-2xl font-semibold text-gray-900">
                        สำนักส่งเสริมวิชาการและงานทะเบียน
                    </span>
                </a>
            <?php elseif (str_starts_with($fullUrl, $baseUrl)): ?>
                <a href="https://regis.dusit.ac.th/" class="flex items-center space-x-3">
                    <img src="./public/images/SDU Logo.png" alt="SDU" class="h-11 w-auto" />
                    <span class="text-xl md:text-2xl font-semibold text-gray-900">
                        สำนักส่งเสริมวิชาการและงานทะเบียน
                    </span>
                </a>
            <?php endif; ?>

            <!-- Hamburger -->
            <button id="menu-toggle" class="inline-flex items-center p-2 w-10 h-10 justify-center text-gray-500 rounded-lg md:hidden hover:bg-gray-100 focus:outline-none">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" />
                </svg>
            </button>
        </div>

        <!-- Menu -->
        <div id="navbar" class="hidden w-full md:flex md:w-auto flex-col md:flex-row md:space-x-8">
            <ul class="flex flex-col items-start sm:items-center justify-center md:flex-row font-medium p-2 md:p-0 md:mt-0 bg-gray-50 md:bg-white border border-gray-100 md:border-0 rounded-lg md:space-x-8">
                <!-- Dropdown: เกี่ยวกับสำนัก -->
                <li class="relative">
                    <button data-dropdown="dropdown-about" class="dropdown-btn flex items-center py-2 px-3 text-gray-900 hover:text-blue-700 w-full">
                        เกี่ยวกับสำนัก
                        <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>
                    <ul id="dropdown-about" class="dropdown hidden absolute left-0 mt-2 bg-white border rounded-lg shadow-md w-56 z-20">
                        <li><a href="https://regis.dusit.ac.th/main/?page_id=30" class="block px-4 py-2 hover:bg-gray-100">ปรัชญา วิสัยทัศน์ พันธกิจ</a></li>
                        <li><a href="https://regis.dusit.ac.th/main/?page_id=42" class="block px-4 py-2 hover:bg-gray-100">บุคลากร</a></li>
                        <li><a href="https://regis.dusit.ac.th/main/?page_id=2630#" class="block px-4 py-2 hover:bg-gray-100">เบอร์โทรศัพท์ หน่วยงาน</a></li>
                        <li><a href="https://regis.dusit.ac.th/main/?page_id=6282" class="block px-4 py-2 hover:bg-gray-100">สายตรงผู้อำนวยการ</a></li>
                    </ul>
                </li>

                <!-- Dropdown: คณะ/โรงเรียน -->
                <li class="relative">
                    <button data-dropdown="dropdown-faculty" class="dropdown-btn flex items-center py-2 px-3 text-gray-900 hover:text-blue-700 w-full">
                        คณะ/โรงเรียน
                        <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>
                    <ul id="dropdown-faculty" class="dropdown hidden absolute left-0 mt-2 bg-white border rounded-lg shadow-md w-56 z-20">
                        <li><a href="https://education.dusit.ac.th/" class="block px-4 py-2 hover:bg-gray-100">คณะครุศาสตร์</a></li>
                        <li><a href="https://scitech.dusit.ac.th/" class="block px-4 py-2 hover:bg-gray-100">คณะวิทยาศาสตร์และเทคโนโลยี</a></li>
                        <li><a href="https://m-sci.dusit.ac.th/home/" class="block px-4 py-2 hover:bg-gray-100">คณะวิทยาการจัดการ</a></li>
                        <li><a href="http://human.dusit.ac.th/main/" class="block px-4 py-2 hover:bg-gray-100">คณะมนุษย์ศาสตร์และสังคมศาสตร์</a></li>
                        <li><a href="http://nurse.dusit.ac.th/" class="block px-4 py-2 hover:bg-gray-100">คณะพยาบาลศาสตร์</a></li>
                        <li><a href="http://food.dusit.ac.th/main/" class="block px-4 py-2 hover:bg-gray-100">โรงเรียนการเรือน</a></li>
                        <li><a href="http://thmdusit.dusit.ac.th/" class="block px-4 py-2 hover:bg-gray-100">โรงเรียนการท่องเที่ยวและการบริการ</a></li>
                        <li><a href="http://slp.dusit.ac.th/" class="block px-4 py-2 hover:bg-gray-100">โรงเรียนกฎหมายและการเมือง</a></li>
                        <li><a href="http://www.graduate.dusit.ac.th/" class="block px-4 py-2 hover:bg-gray-100">บัณฑิตวิทยาลัย</a></li>
                    </ul>
                </li>

                <!-- Dropdown: อาจารย์/บุคลากร -->
                <li class="relative">
                    <button data-dropdown="dropdown-staff" class="dropdown-btn flex items-center py-2 px-3 text-gray-900 hover:text-blue-700 w-full">
                        อาจารย์/บุคลากร
                        <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>
                    <ul id="dropdown-staff" class="dropdown hidden absolute left-0 mt-2 bg-white border rounded-lg shadow-md w-56 z-20">
                        <li><a href="https://regis.dusit.ac.th/main/?page_id=1737" class="block px-4 py-2 hover:bg-gray-100">สาระน่ารู้</a></li>
                        <li><a href="#" class="block px-4 py-2 hover:bg-gray-100">ประเมิน</a></li>
                        <li><a href="https://regis.dusit.ac.th/main/?page_id=389" class="block px-4 py-2 hover:bg-gray-100">แบบฟอร์มสำหรับอาจารย์/เจ้าหน้าที่</a></li>
                    </ul>
                </li>

                <!-- Dropdown: นักศึกษา -->
                <li class="relative">
                    <button data-dropdown="dropdown-student" class="dropdown-btn flex items-center py-2 px-3 text-gray-900 hover:text-blue-700 w-full">
                        นักศึกษา
                        <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>
                    <ul id="dropdown-student" class="dropdown hidden absolute left-0 mt-2 bg-white border rounded-lg shadow-md w-56 z-20">
                        <li><a href="https://regis.dusit.ac.th/main/?p=10234" class="block px-4 py-2 hover:bg-gray-100">บริการ</a></li>
                        <li><a href="https://regis.dusit.ac.th/main/?page_id=1646" class="block px-4 py-2 hover:bg-gray-100">สาระน่ารู้</a></li>
                        <li><a href="https://regis.dusit.ac.th/main/?page_id=15464" class="block px-4 py-2 hover:bg-gray-100">โครงสร้างหลักสูตร</a></li>
                        <li><a href="https://regis.dusit.ac.th/main/?page_id=442" class="block px-4 py-2 hover:bg-gray-100">แบบฟอร์มสำหรับนักศึกษา</a></li>
                        <li><a href="https://regis.dusit.ac.th/main/?page_id=2494" class="block px-4 py-2 hover:bg-gray-100">รายชื่อผู้สำเร็จการศึกษา</a></li>
                        <li><a href="https://regis.dusit.ac.th/main/?page_id=2553" class="block px-4 py-2 hover:bg-gray-100">รายชื่อผู้พ้นสภาพนักศึกษา</a></li>
                    </ul>
                </li>

                <!-- Dropdown: ข้อมูลเผยแพร่ -->
                <li class="relative">
                    <button data-dropdown="dropdown-news" class="dropdown-btn flex items-center py-2 px-3 text-gray-900 hover:text-blue-700 w-full">
                        ข้อมูลเผยแพร่
                        <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>
                    <ul id="dropdown-news" class="dropdown hidden absolute left-0 mt-2 bg-white border rounded-lg shadow-md w-64 z-20">
                        <li><a href="https://docs.google.com/forms/d/e/1FAIpQLSfn7zBBg5J1ASqPXmO9SKojlY2e5lhUr4kHnJ8AldpPZRE4MQ/viewform" class="block px-4 py-2 hover:bg-gray-100">แบบประเมินความพึงพอใจ</a></li>
                        <li><a href="https://regis.dusit.ac.th/main/?page_id=56" class="block px-4 py-2 hover:bg-gray-100">เกี่ยวกับงานประกันคุณภาพ</a></li>
                        <li><a href="https://regis.dusit.ac.th/main/?page_id=15143" class="block px-4 py-2 hover:bg-gray-100">การเปิดเผยข้อมูลสาธารณะ (OIT)</a></li>
                        <li><a href="https://regis.dusit.ac.th/main/?page_id=7805" class="block px-4 py-2 hover:bg-gray-100">ข้อมูลจำนวนนักศึกษา</a></li>
                        <li><a href="https://regis.dusit.ac.th/main/?page_id=9267" class="block px-4 py-2 hover:bg-gray-100">สรุปองค์ความรู้กิจกรรมสนับสนุนด้านวิชาการ</a></li>
                        <li><a href="https://regis.dusit.ac.th/main/?page_id=17326" class="block px-4 py-2 hover:bg-gray-100">ความปลอดภัยและสิ่งแวดล้อม</a></li>
                        <li><a href="https://regis.dusit.ac.th/main/?page_id=4918" class="block px-4 py-2 hover:bg-gray-100">แนวปฏิบัติ PDPA</a></li>
                    </ul>
                </li>

            </ul>
        </div>
    </nav>
</header>

<script>
    document.getElementById('menu-toggle').addEventListener('click', () => {
        document.getElementById('navbar').classList.toggle('hidden');
    });

    document.querySelectorAll('.dropdown-btn').forEach((btn) => {
        btn.addEventListener('click', (e) => {
            const dropdownId = btn.getAttribute('data-dropdown');
            const dropdown = document.getElementById(dropdownId);
            const isHidden = dropdown.classList.contains('hidden');

            document.querySelectorAll('.dropdown').forEach(d => d.classList.add('hidden'));

            if (isHidden) dropdown.classList.remove('hidden');
            else dropdown.classList.add('hidden');

            e.stopPropagation();
        });
    });

    document.addEventListener('click', () => {
        document.querySelectorAll('.dropdown').forEach(d => d.classList.add('hidden'));
    });
</script>