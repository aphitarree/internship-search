<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(dirname(dirname(__DIR__)));
$dotenv->load();
require_once __DIR__ . '/../../config/db_config.php';

// ถ้ามี session เก็บชื่อ user อยู่แล้ว สามารถเปลี่ยนตรงนี้ได้ภายหลัง
$id = $_SESSION['id'];
$sql = "
    SELECT username
    FROM user
    WHERE id = :id
";
$stmt = $conn->prepare($sql);
$stmt->bindParam(":id", $id);
$stmt->execute();
$user = $stmt->fetch(PDO::FETCH_ASSOC);
$userName = $user['username'];
?>

<nav class="flex items-center justify-between bg-white shadow-sm border-b border-gray-200 pt-2 pb-1 px-2 sm:px-4">
    <div class="flex items-center gap-3 flex-1 min-w-0">

        <!-- Mobile Sidebar Toggle -->
        <button
            id="sidebarToggleTop"
            type="button"
            class="inline-flex items-center justify-center w-9 h-9 rounded-full text-gray-500 hover:text-gray-700 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-indigo-500 lg:hidden">
            <i class="fa fa-bars text-sm"></i>
        </button>

        <!-- Mobile Brand (ข้อมูลจาก sidebar) -->
        <div class="flex items-center gap-2 md:hidden">
            <img
                src="../public/images/SDU Logo.png"
                alt="SDU"
                class="h-8 w-auto">
            <div class="flex flex-col leading-tight">
                <span class="text-sm font-semibold text-gray-800">
                    ระบบจัดการฐานข้อมูล
                </span>
            </div>
        </div>

        <!-- Home Link (ซ่อนบนจอเล็ก, โชว์บน sm ขึ้นไป) -->
        <a
            href="<?php echo $_ENV['BASE_URL']; ?>/"
            class="sm:inline-flex items-center gap-2 ml-2 text-sm text-gray-600 hover:text-gray-700 hover:bg-gray-100 px-3 py-2 rounded-md transition">
            <i class="fas fa-home text-base"></i>
            <span class="sm:inline">หน้าหลัก</span>
        </a>

    </div>

    <div class="px-2 lg:px-4 py-2 flex items-center justify-end gap-3">

        <!-- User Menu -->
        <div class="flex items-center gap-3">

            <!-- Divider -->
            <div class="hidden sm:block w-px h-7 bg-gray-200"></div>

            <!-- User Menu -->
            <div class="relative">
                <button
                    id="userMenuButton"
                    type="button"
                    class="flex items-center gap-2 rounded-full focus:outline-none focus:ring-2 focus:ring-indigo-500 px-2 py-1 hover:bg-gray-50">
                    <span class="hidden lg:inline text-sm text-gray-700">
                        <?= htmlspecialchars($userName) ?>
                    </span>
                    <img
                        src="../public/images/profile-pic.webp"
                        alt="User Avatar"
                        class="w-9 h-9 rounded-full border border-gray-200 object-cover">
                    <i class="fas fa-chevron-down text-[10px] text-gray-400"></i>
                </button>

                <!-- Dropdown -->
                <div
                    id="userMenuDropdown"
                    class="hidden absolute right-0 mt-2 w-48 bg-white border border-gray-200 rounded-xl shadow-lg z-50 py-1 text-sm">

                    <div class="my-1 border-t border-gray-100"></div>

                    <button
                        type="button"
                        data-logout-open="true"
                        class="w-full text-left flex items-center px-3 py-2 text-red-600 hover:bg-red-50">
                        <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                        <span>Logout</span>
                    </button>
                </div>
            </div>
        </div>
    </div>
</nav>

<!-- Logout Modal (Tailwind) -->
<div
    id="logoutModal"
    class="fixed inset-0 z-50 hidden bg-black/50 items-center justify-center px-4">
    <div class="bg-white rounded-xl shadow-2xl w-full max-w-md overflow-hidden">
        <div class="flex items-center justify-between px-5 py-3 border-b border-gray-200">
            <h5 class="text-lg font-semibold text-gray-800">Ready to Leave?</h5>
            <button
                type="button"
                class="text-gray-400 hover:text-gray-600 focus:outline-none"
                data-logout-close="true">
                <span class="text-2xl leading-none">&times;</span>
            </button>
        </div>
        <div class="px-5 py-4 text-sm text-gray-700">
            Select "Logout" below if you are ready to end your current session.
        </div>
        <div class="flex justify-end gap-2 px-5 py-3 border-t border-gray-200">
            <button
                type="button"
                class="px-4 py-2 text-sm font-medium rounded-lg border border-gray-300 text-gray-700 hover:bg-gray-50"
                data-logout-close="true">
                Cancel
            </button>
            <a
                href="<?php echo $baseUrl . '/actions/logout_form.php'; ?>"
                class="px-4 py-2 text-sm font-medium rounded-lg bg-indigo-600 text-white hover:bg-indigo-700">
                Logout
            </a>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Mobile search toggle (ถ้าไม่มี element ก็ไม่ทำอะไร)
        const mobileSearchToggle = document.getElementById('mobileSearchToggle');
        const mobileSearchPanel = document.getElementById('mobileSearchPanel');

        if (mobileSearchToggle && mobileSearchPanel) {
            mobileSearchToggle.addEventListener('click', (e) => {
                e.stopPropagation();
                mobileSearchPanel.classList.toggle('hidden');
                const expanded = mobileSearchToggle.getAttribute('aria-expanded') === 'true';
                mobileSearchToggle.setAttribute('aria-expanded', String(!expanded));
            });

            document.addEventListener('click', (e) => {
                if (!mobileSearchPanel.classList.contains('hidden')) {
                    if (!mobileSearchPanel.contains(e.target) && e.target !== mobileSearchToggle) {
                        mobileSearchPanel.classList.add('hidden');
                        mobileSearchToggle.setAttribute('aria-expanded', 'false');
                    }
                }
            });
        }

        // User dropdown toggle
        const userMenuButton = document.getElementById('userMenuButton');
        const userMenuDropdown = document.getElementById('userMenuDropdown');

        if (userMenuButton && userMenuDropdown) {
            userMenuButton.addEventListener('click', (e) => {
                e.stopPropagation();
                userMenuDropdown.classList.toggle('hidden');
            });

            document.addEventListener('click', (e) => {
                if (!userMenuDropdown.classList.contains('hidden')) {
                    if (!userMenuDropdown.contains(e.target) && e.target !== userMenuButton && !userMenuButton.contains(e.target)) {
                        userMenuDropdown.classList.add('hidden');
                    }
                }
            });
        }

        // Logout modal
        const logoutModal = document.getElementById('logoutModal');
        if (logoutModal) {
            document.querySelectorAll('[data-logout-open="true"]').forEach(btn => {
                btn.addEventListener('click', () => {
                    logoutModal.classList.remove('hidden');
                    logoutModal.classList.add('flex');
                    if (userMenuDropdown) userMenuDropdown.classList.add('hidden');
                });
            });

            document.querySelectorAll('[data-logout-close="true"]').forEach(btn => {
                btn.addEventListener('click', () => {
                    logoutModal.classList.add('hidden');
                    logoutModal.classList.remove('flex');
                });
            });

            // ปิดเมื่อคลิกพื้นหลัง
            logoutModal.addEventListener('click', (e) => {
                if (e.target === logoutModal) {
                    logoutModal.classList.add('hidden');
                    logoutModal.classList.remove('flex');
                }
            });
        }

        // Mobile sidebar toggle
        const sidebarToggleTop = document.getElementById('sidebarToggleTop');
        const sidebar = document.getElementById('sidebar');
        const sidebarBackdrop = document.getElementById('sidebarBackdrop');

        if (sidebarToggleTop && sidebar) {
            sidebarToggleTop.addEventListener('click', () => {
                const isOpen = !sidebar.classList.contains('-translate-x-full');

                if (isOpen) {
                    // ปิด
                    sidebar.classList.add('-translate-x-full');
                    if (sidebarBackdrop) sidebarBackdrop.classList.add('hidden');
                } else {
                    // เปิด
                    sidebar.classList.remove('-translate-x-full');
                    if (sidebarBackdrop) sidebarBackdrop.classList.remove('hidden');
                }
            });
        }

        // Close when clicking backdrop
        if (sidebarBackdrop && sidebar) {
            sidebarBackdrop.addEventListener('click', () => {
                sidebar.classList.add('-translate-x-full');
                sidebarBackdrop.classList.add('hidden');
            });
        }
    });
</script>