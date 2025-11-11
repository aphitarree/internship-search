<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require_once __DIR__ . '/../../vendor/autoload.php';
require_once __DIR__ . '/../../config/db_config.php';

use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(dirname(dirname(__DIR__)));
$dotenv->load();

$baseUrl = $_ENV['BASE_URL'] ?? '';


?>

<section>
    <!-- ตารางข้อมูลฝึกงาน -->
    <!-- Page Heading -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
        <h1 class="text-2xl font-semibold text-gray-800">
            ข้อมูลสถานที่ฝึกงาน
        </h1>
        <button
            type="button"
            class="inline-flex items-center px-4 py-2 text-sm font-medium rounded-lg shadow-sm bg-indigo-600 hover:bg-indigo-700 text-white transition">
            <i class="fas fa-download mr-2 text-sm"></i>
            Generate Report
        </button>
    </div>

    <div class="bg-white shadow rounded-xl mb-6">
        <div class="flex items-center justify-between px-4 py-3 border-b border-gray-200">
            <div class="flex items-center gap-2 text-gray-700">
                <i class="fas fa-table"></i>
                <span class="font-medium">ข้อมูลการฝึกงาน</span>
            </div>

            <!-- ปุ่มเปิด Add Modal -->
            <button
                id="openAddInternshipModal"
                type="button"
                class="inline-flex items-center px-3 py-1.5 text-sm font-medium rounded-lg bg-emerald-600 hover:bg-emerald-700 text-white transition">
                + เพิ่มข้อมูลฝึกงาน
            </button>
        </div>

        <div class="p-4">
            <div class="overflow-x-auto no-scrollbar">
                <table class="min-w-full text-sm text-left text-gray-700">
                    <thead class="bg-gray-50 border-b border-gray-200">
                        <tr>
                            <th class="px-3 py-2 font-semibold">NO.</th>
                            <th class="px-3 py-2 font-semibold">บริษัท</th>
                            <th class="px-3 py-2 font-semibold">จังหวัด</th>
                            <th class="px-3 py-2 font-semibold">คณะ</th>
                            <th class="px-3 py-2 font-semibold">หลักสูตร</th>
                            <th class="px-3 py-2 font-semibold">สาขา</th>
                            <th class="px-3 py-2 font-semibold">ปีการศึกษา</th>
                            <th class="px-3 py-2 font-semibold">จำนวนที่รับ</th>
                            <th class="px-3 py-2 font-semibold">ข้อมูลการติดต่อ</th>
                            <th class="px-3 py-2 font-semibold">คะแนน</th>
                            <th class="px-3 py-2 font-semibold text-center">จัดการ</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        <?php


                        if (isset($_SESSION['massge'])) {
                            echo "<p>{$_SESSION['massge']}</p>";
                        }

                        if (isset($_SESSION['inserted_data']) && count($_SESSION['inserted_data']) > 0):
                        ?>
                            <table border="1" cellpadding="8">
                                <thead>
                                    <tr>
                                        <th>Organization</th>
                                        <th>Province</th>
                                        <th>Faculty</th>
                                        <th>Program</th>
                                        <th>Major</th>
                                        <th>Year</th>
                                        <th>Total Student</th>
                                        <th>Contact</th>
                                        <th>Score</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($_SESSION['inserted_data'] as $row): ?>
                                        <tr>
                                            <td><?= htmlspecialchars($row['organization']); ?></td>
                                            <td><?= htmlspecialchars($row['province']); ?></td>
                                            <td><?= htmlspecialchars($row['faculty']); ?></td>
                                            <td><?= htmlspecialchars($row['program']); ?></td>
                                            <td><?= htmlspecialchars($row['major']); ?></td>
                                            <td><?= htmlspecialchars($row['year']); ?></td>
                                            <td><?= htmlspecialchars($row['total_student']); ?></td>
                                            <td><?= htmlspecialchars($row['contact']); ?></td>
                                            <td><?= htmlspecialchars($row['score']); ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        <?php
                        else:
                            echo "<p>ไม่มีข้อมูลที่นำเข้าใหม่</p>";
                        endif;

                        // ✅ เคลียร์ session เพื่อไม่ให้ข้อมูลค้าง
                        unset($_SESSION['inserted_data']);
                        unset($_SESSION['massge']);
                        ?>

                        <?php if (empty($internRecords)): ?>
                            <tr>
                                <td colspan="11" class="px-3 py-6 text-center text-gray-500">
                                    ยังไม่มีข้อมูลฝึกงาน
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</section>

<!-- Add Internship Modal -->
<div
    id="addInternshipModal"
    class="fixed inset-0 z-50 hidden bg-black/50 items-center justify-center px-2">
    <div class="bg-white rounded-xl shadow-lg w-full max-w-3xl">
        <form method="post" action="./actions/add_internship.php" class="flex flex-col max-h-[90vh] bg-white shadow-md rounded px-8 pt-6 pb-8">
            <div class="flex items-center justify-between pb-4 border-b border-gray-200">
                <h5 class="text-lg font-semibold">เพิ่มข้อมูลฝึกงาน</h5>
                <button
                    type="button"
                    data-close-modal="add"
                    class="text-gray-400 hover:text-gray-600 transition">
                    <span class="text-xl leading-none">&times;</span>
                </button>
            </div>

            <div class="pt-4 space-y-4 overflow-y-auto">
                <!-- ใส่ field ตามที่มีในตาราง -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-gray-700 text-sm font-bold mb-2">บริษัท</label>
                        <input
                            type="text"
                            name="organization"
                            class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                            required>
                    </div>
                    <div>
                        <label class="block text-gray-700 text-sm font-bold mb-2">จังหวัด</label>
                        <input
                            type="text"
                            name="province"
                            class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                            required>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-gray-700 text-sm font-bold mb-2">คณะ</label>
                        <input
                            type="text"
                            name="faculty"
                            class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                            required>
                    </div>
                    <div>
                        <label class="block text-gray-700 text-sm font-bold mb-2">หลักสูตร</label>
                        <input
                            type="text"
                            name="program"
                            class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                            required>
                    </div>
                    <div>
                        <label class="block text-gray-700 text-sm font-bold mb-2">สาขา</label>
                        <input
                            type="text"
                            name="major"
                            class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                            required>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-gray-700 text-sm font-bold mb-2">ปีการศึกษา</label>
                        <input
                            type="number"
                            name="year"
                            class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                            required>
                    </div>
                    <div>
                        <label class="block text-gray-700 text-sm font-bold mb-2">จำนวนที่รับ</label>
                        <input
                            type="number"
                            name="total_student"
                            class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                            required>
                    </div>
                    <div>
                        <label class="block text-gray-700 text-sm font-bold mb-2">คะแนน</label>
                        <input
                            type="text"
                            name="score"
                            class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                    </div>
                </div>

                <div>
                    <label class="block text-gray-700 text-sm font-bold mb-2">ข้อมูลการติดต่อ</label>
                    <textarea
                        name="contact"
                        rows="3"
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"></textarea>
                </div>
            </div>

            <div class="flex justify-end gap-2 pt-4 mt-4 border-t border-gray-200">
                <button
                    type="button"
                    data-close-modal="add"
                    class="px-4 py-2 text-sm font-bold rounded border border-gray-300 text-gray-700 hover:bg-gray-50">
                    ยกเลิก
                </button>
                <button
                    type="submit"
                    class="px-4 py-2 text-sm font-bold rounded bg-blue-500 hover:bg-blue-700 text-white focus:outline-none focus:shadow-outline">
                    บันทึก
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Edit Internship Modal -->
<div
    id="editInternshipModal"
    class="fixed inset-0 z-50 hidden bg-black/50 items-center justify-center px-2">
    <div class="bg-white rounded-xl shadow-lg w-full max-w-3xl">
        <form
            method="post" action="./actions/edit_internship.php"
            class="flex flex-col max-h-[90vh] bg-white shadow-md rounded px-8 pt-6 pb-8"
            id="editForm">
            <div class="flex items-center justify-between pb-4 border-b border-gray-200">
                <h5 class="text-lg font-semibold">แก้ไขข้อมูลฝึกงาน</h5>
                <button
                    type="button"
                    data-close-modal="edit"
                    class="text-gray-400 hover:text-gray-600 transition">
                    <span class="text-xl leading-none">&times;</span>
                </button>
            </div>

            <div class="pt-4 space-y-4 overflow-y-auto">
                <input type="hidden" name="id" id="edit-id">

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-gray-700 text-sm font-bold mb-2">บริษัท</label>
                        <input
                            type="text"
                            name="organization"
                            id="edit-organization"
                            class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                            required>
                    </div>
                    <div>
                        <label class="block text-gray-700 text-sm font-bold mb-2">จังหวัด</label>
                        <input
                            type="text"
                            name="province"
                            id="edit-province"
                            class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                            required>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-gray-700 text-sm font-bold mb-2">คณะ</label>
                        <input
                            type="text"
                            name="faculty"
                            id="edit-faculty"
                            class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                            required>
                    </div>
                    <div>
                        <label class="block text-gray-700 text-sm font-bold mb-2">หลักสูตร</label>
                        <input
                            type="text"
                            name="program"
                            id="edit-program"
                            class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                            required>
                    </div>
                    <div>
                        <label class="block text-gray-700 text-sm font-bold mb-2">สาขา</label>
                        <input
                            type="text"
                            name="major"
                            id="edit-major"
                            class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                            required>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-gray-700 text-sm font-bold mb-2">ปีการศึกษา</label>
                        <input
                            type="number"
                            name="year"
                            id="edit-year"
                            class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                            required>
                    </div>
                    <div>
                        <label class="block text-gray-700 text-sm font-bold mb-2">จำนวนที่รับ</label>
                        <input
                            type="number"
                            name="total_student"
                            id="edit-total_student"
                            class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                            required>
                    </div>
                    <div>
                        <label class="block text-gray-700 text-sm font-bold mb-2">คะแนน</label>
                        <input
                            type="text"
                            name="score"
                            id="edit-score"
                            class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                    </div>
                </div>

                <div>
                    <label class="block text-gray-700 text-sm font-bold mb-2">ข้อมูลการติดต่อ</label>
                    <textarea
                        name="contact"
                        id="edit-contact"
                        rows="3"
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"></textarea>
                </div>
            </div>

            <div class="flex justify-end gap-2 pt-4 mt-4 border-t border-gray-200">
                <button
                    type="button"
                    data-close-modal="edit"
                    class="px-4 py-2 text-sm font-bold rounded border border-gray-300 text-gray-700 hover:bg-gray-50">
                    ยกเลิก
                </button>
                <button
                    type="submit"
                    name="update"
                    class="px-4 py-2 text-sm font-bold rounded bg-blue-500 hover:bg-blue-700 text-white focus:outline-none focus:shadow-outline">
                    อัปเดต
                </button>
            </div>
        </form>
    </div>
</div>