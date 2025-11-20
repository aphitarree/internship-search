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

// ดึงข้อมูลคณะ หลักสูตรสาขามาแทนข้อมูลดิบเก่าของที่เขียนไว้ในตัวแปร JavaScript
$facultyMap = [];

try {
    $sql = "
        SELECT faculty, program, major
        FROM faculty_program_major
        ORDER BY faculty, major, program
    ";
    $stmt = $conn->query($sql);

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $facultyName = $row['faculty'];
        $majorName   = $row['major'];
        $programName = $row['program'];

        if (!isset($facultyMap[$facultyName])) {
            $facultyMap[$facultyName] = [];
        }

        $facultyMap[$facultyName][$majorName] = $programName;
    }
} catch (Exception $e) {
    echo "DB Error: " . $e->getMessage();
}
?>

<!-- Datatables CSS -->
<link rel="stylesheet" href="../public/css/jquery.dataTables.min.css">

<!-- Choices.js CSS -->
<link rel="stylesheet" href="../public/css/choices.min.css" />

<!-- Datatables Button -->
<link rel="stylesheet" href="../public/css/jquery.dataTables.min.css">
<link rel="stylesheet" href="../public/css/buttons.dataTables.min.css">

<section class="mt-4">
    <style>
        table,
        thead,
        tr,
        th,
        td {
            border-width: 1px;
        }

        /* ทำให้คอลัมน์ข้อมูลการติดต่อขึ้นบรรทัดใหม่เมื่อข้อความยาว */
        td.cell-contact {
            white-space: normal;
            word-wrap: break-word;
            word-break: break-word;
        }

        #internshipTable_length {
            padding-top: 0.15rem;
        }

        #internshipTable_length {
            padding-right: 0.5rem;
        }
    </style>

    <!-- ตารางข้อมูลฝึกงาน -->
    <!-- Page Heading -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
        <h1 class="text-2xl font-semibold text-gray-800">
            ข้อมูลสถานที่ฝึกงาน
        </h1>
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

        <div class="p-2">
            <div class="overflow-x-auto no-scrollbar">
                <table
                    id="internshipTable"
                    class="min-w-full text-sm text-left text-gray-700">
                    <thead class="bg-gray-50 border-b border-gray-200">
                        <tr>
                            <th class="px-3 py-2 font-semibold !text-center">NO.</th>
                            <th class="px-3 py-2 font-semibold !text-center">หน่วยงาน</th>
                            <th class="px-3 py-2 font-semibold !text-center">จังหวัด</th>
                            <th class="px-3 py-2 font-semibold !text-center">คณะ / โรงเรียน</th>
                            <th class="px-3 py-2 font-semibold !text-center">หลักสูตร</th>
                            <th class="px-3 py-2 font-semibold !text-center">สาขาวิชา</th>
                            <th class="px-3 py-2 font-semibold !text-center">ปีการศึกษา</th>
                            <th class="px-3 py-2 font-semibold !text-center">สังกัด</th>
                            <th class="px-3 py-2 font-semibold !text-center">จำนวนที่รับ</th>
                            <th class="px-3 py-2 font-semibold !text-center">MOU</th>
                            <th class="px-3 py-2 font-semibold !text-center">ข้อมูลการติดต่อ</th>
                            <th class="px-3 py-2 font-semibold !text-center">คะแนน</th>
                            <th class="px-3 py-2 font-semibold !text-center">วันที่สร้าง</th>
                            <th class="px-3 py-2 font-semibold !text-center"></th>
                        </tr>
                    </thead>
                    <!-- server-side จะเติม tbody เอง -->
                    <tbody class="divide-y divide-gray-100"></tbody>
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
        <form
            method="post"
            action="./actions/add_internship.php"
            id="addForm"
            class="flex flex-col max-h-[90vh] bg-white shadow-md rounded px-8 pt-6 pb-8">
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
                        <label class="block text-gray-700 text-sm font-bold mb-2">หน่วยงาน</label>
                        <input
                            type="text"
                            name="organization"
                            class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                            required>
                    </div>
                    <div>
                        <label class="block text-gray-700 text-sm font-bold mb-2">จังหวัด</label>
                        <select
                            name="province"
                            id="add-province"
                            class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                            required>
                            <option value="">-เลือกจังหวัด-</option>
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-gray-700 text-sm font-bold mb-2">คณะ / โรงเรียน</label>
                        <select
                            name="faculty"
                            id="add-faculty"
                            class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                            required>
                            <option value="">-เลือกคณะ / โรงเรียน-</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-gray-700 text-sm font-bold mb-2">หลักสูตร</label>
                        <select
                            name="program"
                            id="add-program"
                            class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                            required>
                            <option value="">-เลือกหลักสูตร-</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-gray-700 text-sm font-bold mb-2">สาขาวิชา</label>
                        <select
                            name="major"
                            id="add-major"
                            class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                            required>
                            <option value="">-เลือกสาขาวิชา-</option>
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <div>
                        <label class="block text-gray-700 text-sm font-bold mb-2">ปีการศึกษา</label>
                        <input
                            type="number"
                            name="year"
                            id="add-year"
                            class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                            required>
                    </div>
                    <div>
                        <label class="block text-gray-700 text-sm font-bold mb-2">MOU</label>
                        <select
                            name="mou_status"
                            id="add-mou"
                            class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                            required>
                            <option value="">-เลือกสถานะ MOU-</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-gray-700 text-sm font-bold mb-2">จำนวนที่รับ</label>
                        <input
                            type="number"
                            name="total_student"
                            min="0"
                            class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                            required>
                    </div>
                    <div>
                        <label class="block text-gray-700 text-sm font-bold mb-2">คะแนน (0 - 5)</label>
                        <input
                            type="text"
                            name="score"
                            placeholder="- หรือ 0–5"
                            oninput="validateScore(this)"
                            class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                            required>

                        <script>
                            function validateScore(input) {
                                const value = input.value.trim();

                                if (value === "" || value === "-") return;

                                if (isNaN(value)) {
                                    input.value = "";
                                    return;
                                }

                                const num = parseFloat(value);
                                if (num < 0 || num > 5) {
                                    input.value = "";
                                }
                            }
                        </script>
                    </div>
                    <div>
                        <label class="block text-gray-700 text-sm font-bold mb-2">สังกัด</label>
                        <select
                            name="affiliation"
                            id="add-affiliation"
                            class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                            required>
                            <option value="">-เลือกสังกัด-</option>
                        </select>
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
            method="post"
            action="./actions/edit_internship.php"
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
                        <label class="block text-gray-700 text-sm font-bold mb-2">หน่วยงาน</label>
                        <input
                            type="text"
                            name="organization"
                            id="edit-organization"
                            class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                            required>
                    </div>
                    <div>
                        <label class="block text-gray-700 text-sm font-bold mb-2">จังหวัด</label>
                        <select
                            name="province"
                            id="edit-province"
                            class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                            required>
                            <option value="">-เลือกจังหวัด-</option>
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-gray-700 text-sm font-bold mb-2">คณะ / โรงเรียน</label>
                        <select
                            name="faculty"
                            id="edit-faculty"
                            class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                            required>
                            <option value="">-เลือกคณะ / โรงเรียน-</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-gray-700 text-sm font-bold mb-2">หลักสูตร</label>
                        <select
                            name="program"
                            id="edit-program"
                            class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                            required>
                            <option value="">-เลือกหลักสูตร-</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-gray-700 text-sm font-bold mb-2">สาขาวิชา</label>
                        <select
                            name="major"
                            id="edit-major"
                            class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                            required>
                            <option value="">-เลือกสาขาวิชา-</option>
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
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
                        <label class="block text-gray-700 text-sm font-bold mb-2">MOU</label>
                        <select
                            name="mou_status"
                            id="edit-mou"
                            class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                            required>
                            <option value="">-เลือกสถานะ MOU-</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-gray-700 text-sm font-bold mb-2">จำนวนที่รับ</label>
                        <input
                            type="number"
                            name="total_student"
                            min="0"
                            id="edit-total_student"
                            class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                            required>
                    </div>
                    <div>
                        <label class="block text-gray-700 text-sm font-bold mb-2">คะแนน (0 - 5)</label>
                        <input
                            type="text"
                            name="score"
                            id="edit-score"
                            placeholder="- หรือ 0–5"
                            oninput="validateScore(this)"
                            class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                            required>

                        <script>
                            function validateScore(input) {
                                const value = input.value.trim();

                                if (value === "" || value === "-") return;

                                if (isNaN(value)) {
                                    input.value = "";
                                    return;
                                }

                                const num = parseFloat(value);
                                if (num < 0 || num > 5) {
                                    input.value = "";
                                }
                            }
                        </script>
                    </div>
                    <div>
                        <label class="block text-gray-700 text-sm font-bold mb-2">สังกัด</label>
                        <select
                            name="affiliation"
                            id="edit-affiliation"
                            class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                            <option value="">-เลือกสังกัด-</option>
                        </select>
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

<!-- Delete Confirm Modal -->
<div
    id="deleteConfirmModal"
    class="fixed inset-0 z-50 hidden bg-black/50 items-center justify-center px-2">
    <div class="bg-white rounded-xl shadow-lg w-full max-w-md">
        <div class="px-6 pt-6 pb-4 border-b border-gray-200 flex items-center justify-between">
            <h5 class="text-lg font-semibold text-red-600">
                ยืนยันการลบข้อมูล
            </h5>
            <button
                type="button"
                data-close-modal="delete"
                class="text-gray-400 hover:text-gray-600 transition">
                <span class="text-xl leading-none">&times;</span>
            </button>
        </div>

        <div class="px-6 py-4">
            <p class="text-sm text-gray-700">
                คุณต้องการลบข้อมูลสถานที่ฝึกงานรายการนี้หรือไม่?
                <br>
                <span class="text-xs text-gray-500">
                    เมื่อยืนยันแล้วจะไม่สามารถกู้คืนได้
                </span>
            </p>
        </div>

        <div class="px-6 py-4 border-t border-gray-200 flex justify-end gap-2">
            <button
                type="button"
                data-close-modal="delete"
                class="px-4 py-2 text-sm font-bold rounded border border-gray-300 text-gray-700 hover:bg-gray-50">
                ยกเลิก
            </button>
            <button
                type="button"
                id="confirmDeleteBtn"
                class="px-4 py-2 text-sm font-bold rounded bg-red-600 hover:bg-red-700 text-white">
                ลบข้อมูล
            </button>
        </div>
    </div>
</div>


<!-- Datatables-->
<script src="../public/js/jquery-3.7.1.min.js"></script>
<script src="../public/js/jquery.dataTables.min.js"></script>

<!-- Choices.js-->
<script src="../public/js/choices.min.js"></script>

<!-- Datatables Button -->
<script src="../public/js/dataTables.buttons.min.js"></script>
<script src="../public/js/buttons.colVis.min.js"></script>

<script>
    // Escape HTML when put the string to the data-... or innerHTML
    function escapeHtml(text) {
        return $('<div>').text(text == null ? '' : text).html();
    }

    // ข้อมูล mapping คณะ → สาขา → หลักสูตร
    const facultyMajorsPrograms = <?= json_encode($facultyMap, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES); ?>;

    const provinces = [
        "กรุงเทพมหานคร", "กระบี่", "กาญจนบุรี", "กาฬสินธุ์", "กำแพงเพชร",
        "ขอนแก่น", "จันทบุรี", "ฉะเชิงเทรา", "ชลบุรี", "ชัยนาท", "ชัยภูมิ",
        "ชุมพร", "เชียงราย", "เชียงใหม่", "ตรัง", "ตราด", "ตาก", "นครนายก",
        "นครปฐม", "นครพนม", "นครราชสีมา", "นครศรีธรรมราช", "นครสวรรค์",
        "นนทบุรี", "นราธิวาส", "น่าน", "บึงกาฬ", "บุรีรัมย์", "ปทุมธานี",
        "ประจวบคีรีขันธ์", "ปราจีนบุรี", "ปัตตานี", "พระนครศรีอยุธยา",
        "พะเยา", "พังงา", "พัทลุง", "พิจิตร", "พิษณุโลก", "เพชรบุรี",
        "เพชรบูรณ์", "แพร่", "ภูเก็ต", "มหาสารคาม", "มุกดาหาร", "แม่ฮ่องสอน",
        "ยโสธร", "ยะลา", "ร้อยเอ็ด", "ระนอง", "ระยอง", "ราชบุรี", "ลพบุรี",
        "ลำปาง", "ลำพูน", "เลย", "ศรีสะเกษ", "สกลนคร", "สงขลา", "สตูล",
        "สมุทรปราการ", "สมุทรสงคราม", "สมุทรสาคร", "สระแก้ว", "สระบุรี",
        "สิงห์บุรี", "สุโขทัย", "สุพรรณบุรี", "สุราษฎร์ธานี", "สุรินทร์",
        "หนองคาย", "หนองบัวลำภู", "อ่างทอง", "อำนาจเจริญ", "อุดรธานี",
        "อุตรดิตถ์", "อุทัยธานี", "อุบลราชธานี"
    ];

    const mouStatusOptions = [
        "มี",
        "ไม่มี",
        "ไม่ระบุ",
    ];

    const affiliationOptions = [
        "ภาครัฐ",
        "ภาคเอกชน",
        "รัฐวิสาหกิจ",
        "-",
    ];

    const sortChoice = (a, b) => {
        if (a.value === '' && b.value !== '') return -1;
        if (a.value !== '' && b.value === '') return 1;
        return a.label.localeCompare(b.label, 'th');
    };

    function setupDropdownGroup(prefix) {
        const facultySelect = document.getElementById(prefix + '-faculty');
        const majorSelect = document.getElementById(prefix + '-major');
        const programSelect = document.getElementById(prefix + '-program');
        const provinceSelect = document.getElementById(prefix + '-province');
        const mouSelect = document.getElementById(prefix + '-mou');
        const affiliationSelect = document.getElementById(prefix + '-affiliation');

        if (!facultySelect || !majorSelect || !programSelect || !provinceSelect || !mouSelect || !affiliationSelect) {
            console.warn('dropdown elements not found for prefix:', prefix);
            return null;
        }

        const allFaculties = Object.keys(facultyMajorsPrograms);
        const allMajors = Object.values(facultyMajorsPrograms)
            .flatMap(majorsObj => Object.keys(majorsObj));
        const allPrograms = [...new Set(
            Object.values(facultyMajorsPrograms)
            .flatMap(majorsObj => Object.values(majorsObj))
        )];

        const facultyChoices = new Choices(facultySelect, {
            searchEnabled: true,
            itemSelectText: "",
            searchPlaceholderValue: "พิมพ์เพื่อค้นหาคณะ / โรงเรียน...",
            sorter: sortChoice,
        });
        const majorChoices = new Choices(majorSelect, {
            searchEnabled: true,
            itemSelectText: "",
            searchPlaceholderValue: "พิมพ์เพื่อค้นหาสาขาวิชา...",
            sorter: sortChoice,
        });
        const programChoices = new Choices(programSelect, {
            searchEnabled: true,
            itemSelectText: "",
            searchPlaceholderValue: "พิมพ์เพื่อค้นหาหลักสูตร...",
            sorter: sortChoice,
        });
        const provinceChoices = new Choices(provinceSelect, {
            searchEnabled: true,
            itemSelectText: "",
            searchPlaceholderValue: "พิมพ์เพื่อค้นหาจังหวัด...",
            sorter: sortChoice,
        });
        const mouChoices = new Choices(mouSelect, {
            searchEnabled: false,
            itemSelectText: "",
            sorter: sortChoice,
        });
        const affiliationChoices = new Choices(affiliationSelect, {
            searchEnabled: false,
            itemSelectText: "",
            sorter: sortChoice,
        });

        const populateFaculties = (list) => {
            facultyChoices.clearStore();
            facultyChoices.setChoices(
                [{
                    value: "",
                    label: "-เลือกคณะ / โรงเรียน-",
                    selected: true,
                    disabled: false
                }].concat(
                    list.map(faculty => ({
                        value: faculty,
                        label: faculty
                    }))
                ),
                "value", "label", true
            );
        };

        const populateMajors = (list) => {
            majorChoices.clearStore();
            majorChoices.setChoices(
                [{
                    value: "",
                    label: "-เลือกสาขาวิชา-",
                    selected: true,
                    disabled: false
                }].concat(
                    list.map(major => ({
                        value: major,
                        label: major
                    }))
                ),
                "value", "label", true
            );
        };

        const populatePrograms = (list) => {
            programChoices.clearStore();
            programChoices.setChoices(
                [{
                    value: "",
                    label: "-เลือกหลักสูตร-",
                    selected: true,
                    disabled: false
                }].concat(
                    list.map(program => ({
                        value: program,
                        label: program
                    }))
                ),
                "value", "label", true
            );
        };

        const populateProvinces = (list) => {
            provinceChoices.clearStore();
            provinceChoices.setChoices(
                [{
                    value: "",
                    label: "-เลือกจังหวัด-",
                    selected: true,
                    disabled: false
                }].concat(
                    list.map(province => ({
                        value: province,
                        label: province,
                    }))
                ),
                "value", "label", true
            );
        };

        const populateMou = (list) => {
            mouChoices.clearStore();
            mouChoices.setChoices(
                [{
                    value: "",
                    label: "-เลือกสถานะ MOU-",
                    selected: true,
                    disabled: false,
                    placeholder: true
                }].concat(
                    list.map(status => ({
                        value: status,
                        label: status
                    }))
                ),
                "value", "label", true
            );
        };

        const populateAffiliation = (list) => {
            affiliationChoices.clearStore();
            affiliationChoices.setChoices(
                [{
                    value: "",
                    label: "-เลือกสังกัด-",
                    selected: true,
                    disabled: false,
                    placeholder: true
                }].concat(
                    list.map(affiliation => ({
                        value: affiliation,
                        label: affiliation
                    }))
                ),
                "value", "label", true
            );
        };

        const getMajorsAndFacultiesByProgram = (prog) => {
            const majorsOfProgram = [];
            const facultiesOfProgramSet = new Set();
            for (const [fac, majorsObj] of Object.entries(facultyMajorsPrograms)) {
                for (const [major, programName] of Object.entries(majorsObj)) {
                    if (programName === prog) {
                        majorsOfProgram.push(major);
                        facultiesOfProgramSet.add(fac);
                    }
                }
            }
            return {
                majorsOfProgram,
                facultiesOfProgram: [...facultiesOfProgramSet],
            };
        };

        // initial populate
        populateFaculties(allFaculties);
        populateMajors(allMajors);
        populatePrograms(allPrograms);
        populateProvinces(provinces);
        populateMou(mouStatusOptions);
        populateAffiliation(affiliationOptions);

        // เมื่อเปลี่ยน "คณะ"
        facultySelect.addEventListener('change', () => {
            const faculty = facultySelect.value;

            if (!faculty || !facultyMajorsPrograms[faculty]) {
                populateMajors(allMajors);
                populatePrograms(allPrograms);
                majorChoices.setChoiceByValue('');
                programChoices.setChoiceByValue('');
                return;
            }

            const majorsOfFaculty = Object.keys(facultyMajorsPrograms[faculty]);
            const programsOfFaculty = [...new Set(
                Object.values(facultyMajorsPrograms[faculty])
            )];

            populateMajors(majorsOfFaculty);
            populatePrograms(programsOfFaculty);
            majorChoices.setChoiceByValue('');
            programChoices.setChoiceByValue('');
        });

        // เมื่อเปลี่ยน "สาขา"
        majorSelect.addEventListener('change', () => {
            const major = majorSelect.value;
            if (!major) return;

            let foundFaculty = null;
            let foundProgram = null;

            for (const [fac, majorsObj] of Object.entries(facultyMajorsPrograms)) {
                if (major in majorsObj) {
                    foundFaculty = fac;
                    foundProgram = majorsObj[major];
                    break;
                }
            }

            if (foundFaculty) {
                facultyChoices.setChoiceByValue(foundFaculty);

                const majorsOfFaculty = Object.keys(facultyMajorsPrograms[foundFaculty]);
                const programsOfFaculty = [...new Set(
                    Object.values(facultyMajorsPrograms[foundFaculty])
                )];

                populateMajors(majorsOfFaculty);
                populatePrograms(programsOfFaculty);

                majorChoices.setChoiceByValue(major);
                if (foundProgram) {
                    programChoices.setChoiceByValue(foundProgram);
                }
            }
        });

        programSelect.addEventListener('change', () => {
            const prog = programSelect.value;
            const currentFaculty = facultySelect.value || '';

            if (!prog) {
                // ถ้ายกเลิกการเลือกหลักสูตร → reset majors ตามคณะปัจจุบัน หรือทั้งหมด
                if (currentFaculty && facultyMajorsPrograms[currentFaculty]) {
                    const majorsOfFaculty = Object.keys(facultyMajorsPrograms[currentFaculty]);
                    populateMajors(majorsOfFaculty);
                } else {
                    populateMajors(allMajors);
                }
                majorChoices.setChoiceByValue('');
                return;
            }

            const {
                majorsOfProgram,
                facultiesOfProgram
            } =
            getMajorsAndFacultiesByProgram(prog);

            // ถ้ามีคณะที่เลือกอยู่แล้ว และคณะนั้นมีโปรแกรมนี้
            if (currentFaculty && facultiesOfProgram.includes(currentFaculty)) {
                const majorsInCurrentFaculty = Object
                    .entries(facultyMajorsPrograms[currentFaculty])
                    .filter(([, programName]) => programName === prog)
                    .map(([major]) => major);

                populateMajors(majorsInCurrentFaculty);
                majorChoices.setChoiceByValue('');

                const programsOfFaculty = [...new Set(
                    Object.values(facultyMajorsPrograms[currentFaculty])
                )];
                populatePrograms(programsOfFaculty);
                programChoices.setChoiceByValue(prog);
            } else {
                // ถ้าไม่มีคณะ หรือคณะเดิมไม่มีโปรแกรมนี้ → filter คณะให้เหลือเฉพาะที่มีโปรแกรมนี้
                populateFaculties(facultiesOfProgram);
                facultyChoices.setChoiceByValue('');

                populateMajors(majorsOfProgram);
                majorChoices.setChoiceByValue('');

                // ทำให้ dropdown หลักสูตรเหลือเฉพาะโปรแกรมที่เลือก
                populatePrograms([prog]);
                programChoices.setChoiceByValue(prog);
            }
        });

        function resetValues() {
            facultyChoices.setChoiceByValue('');
            majorChoices.setChoiceByValue('');
            programChoices.setChoiceByValue('');
            provinceChoices.setChoiceByValue('');
            mouChoices.setChoiceByValue('');
            affiliationChoices.setChoiceByValue('');
        }

        return {
            facultyChoices,
            majorChoices,
            programChoices,
            provinceChoices,
            mouChoices,
            affiliationChoices,
            resetValues
        };
    }

    $(function() {
        const $addModal = $('#addInternshipModal');
        const $addForm = $('#addForm');
        const $editModal = $('#editInternshipModal');
        const $editForm = $('#editForm');

        const addDropdowns = setupDropdownGroup('add');
        const editDropdowns = setupDropdownGroup('edit');

        const dt = $('#internshipTable').DataTable({
            processing: true,
            serverSide: true,
            scrollX: false,
            dom: 'lBfrtip',
            buttons: [{
                extend: 'colvis',
                text: 'เลือกคอลัมน์',
                className: 'bg-gray-200 border border-gray-300 rounded px-3 py-1 text-sm hover:bg-gray-300'
            }],
            pageLength: 10,
            lengthMenu: [5, 10, 25, 50],
            ajax: {
                url: './actions/fetch_internships.php',
                type: 'POST'
            },
            columnDefs: [{
                targets: [9, 12],
                visible: false,
            }],
            columns: [{
                data: null,
                render: function(data, type, row, meta) {
                    return meta.row + meta.settings._iDisplayStart + 1;
                },
                className: 'text-center p-2'
            }, {
                data: 'organization',
                className: 'text-left p-2'
            }, {
                data: 'province',
                className: 'text-left p-2'
            }, {
                data: 'faculty',
                className: 'text-left p-2'
            }, {
                data: 'program',
                className: 'text-left p-2'
            }, {
                data: 'major',
                className: 'text-left p-2'
            }, {
                data: 'year',
                className: '!text-center p-2'
            }, {
                data: 'affiliation',
                className: '!text-center p-2'
            }, {
                data: 'total_student',
                className: '!text-center p-2'
            }, {
                data: 'mou_status',
                className: '!text-center p-2'
            }, {
                data: 'contact',
                className: 'text-left p-2'
            }, {
                data: 'score',
                className: '!text-center p-2'
            }, {
                data: 'created_at',
                className: '!text-center p-2'
            }, {
                data: null,
                orderable: false,
                searchable: false,
                render: function(data, type, row) {
                    return `
                <div class="flex gap-2">
                    <button
                        type="button"
                        class="whitespace-nowrap btn-edit inline-flex items-center px-3 py-2 text-xs font-bold rounded-md bg-blue-600 hover:bg-blue-700 text-white transition"
                        data-id="${row.id}"
                        data-organization="${escapeHtml(row.organization)}"
                        data-province="${escapeHtml(row.province)}"
                        data-faculty="${escapeHtml(row.faculty)}"
                        data-program="${escapeHtml(row.program)}"
                        data-major="${escapeHtml(row.major)}"
                        data-year="${escapeHtml(row.year)}"
                        data-total_student="${escapeHtml(row.total_student)}"
                        data-mou_status="${escapeHtml(row.mou_status)}"
                        data-affiliation="${escapeHtml(row.affiliation)}"
                        data-contact="${escapeHtml(row.contact)}"
                        data-score="${escapeHtml(row.score ?? '')}"
                    >
                        แก้ไข
                    </button>

                    <button
                        type="button"
                        class="whitespace-nowrap btn-delete inline-flex items-center px-3 py-2 text-xs font-bold rounded-md bg-red-600 hover:bg-red-700 text-white transition"
                        data-id="${row.id}"
                    >
                        ลบ
                    </button>
                </div>
            `;
                }
            }],
            createdRow: function(row, data) {
                const $row = $(row);
                $row.attr('data-row-id', data.id);

                const cells = $row.find('td');
                $(cells[1]).addClass('cell-organization');
                $(cells[2]).addClass('cell-province');
                $(cells[3]).addClass('cell-faculty');
                $(cells[4]).addClass('cell-program');
                $(cells[5]).addClass('cell-major');
                $(cells[6]).addClass('cell-year');
                $(cells[7]).addClass('cell-total-student');
                $(cells[8]).addClass('cell-mou-status');
                $(cells[9]).addClass('cell-contact');
                $(cells[10]).addClass('cell-score');
                $(cells[11]).addClass('cell-affiliation');
                $(cells[12]).addClass('cell-created-at');
            },
            language: {
                search: 'ค้นหา:',
                lengthMenu: 'แสดง _MENU_ แถวต่อหน้า',
                info: 'แสดง _START_ ถึง _END_ จากทั้งหมด _TOTAL_ แถว',
                infoEmpty: 'ไม่มีข้อมูล',
                infoFiltered: '(กรองจากทั้งหมด _MAX_ แถว)',
                zeroRecords: 'ไม่พบข้อมูลที่ค้นหา',
                paginate: {
                    first: 'หน้าแรก',
                    last: 'หน้าสุดท้าย',
                    next: 'ถัดไป',
                    previous: 'ก่อนหน้า'
                }
            }
        });

        // Add Modal
        $('#openAddInternshipModal').on('click', function() {
            if (addDropdowns && typeof addDropdowns.resetValues === 'function') {
                addDropdowns.resetValues();
            }
            $('#addInternshipModal form')[0].reset();
            $addModal.removeClass('hidden').addClass('flex');
        });

        $('[data-close-modal="add"]').on('click', function() {
            $addModal.addClass('hidden').removeClass('flex');
        });

        $addModal.on('click', function(e) {
            if (e.target === this) {
                $addModal.addClass('hidden').removeClass('flex');
            }
        });

        // Edit Modal
        $(document).on('click', '.btn-edit', function() {
            const $btn = $(this);

            const year = $btn.data('year') ?? '';
            const faculty = $btn.data('faculty') || '';
            const major = $btn.data('major') || '';
            const program = $btn.data('program') || '';
            const province = $btn.data('province') || '';
            const mouStatus = $btn.data('mou_status') || '';
            const affiliation = $btn.data('affiliation') || '';

            $('#edit-id').val($btn.data('id'));
            $('#edit-organization').val($btn.data('organization'));
            $('#edit-total_student').val($btn.data('total_student'));
            $('#edit-contact').val($btn.data('contact'));
            $('#edit-score').val($btn.data('score'));
            $('#edit-year').val(year);
            $('#edit-affiliation').val(affiliation);

            if (editDropdowns) {
                if (province) {
                    editDropdowns.provinceChoices.setChoiceByValue(province);
                } else {
                    editDropdowns.provinceChoices.setChoiceByValue('');
                }

                if (faculty) {
                    editDropdowns.facultyChoices.setChoiceByValue(faculty);

                    setTimeout(() => {
                        if (major) {
                            editDropdowns.majorChoices.setChoiceByValue(major);
                        }
                        if (program) {
                            editDropdowns.programChoices.setChoiceByValue(program);
                        }
                    }, 0);
                } else {
                    editDropdowns.facultyChoices.setChoiceByValue('');
                    editDropdowns.majorChoices.setChoiceByValue('');
                    editDropdowns.programChoices.setChoiceByValue('');
                }

                if (editDropdowns.mouChoices) {
                    if (mouStatus) {
                        editDropdowns.mouChoices.setChoiceByValue(mouStatus);
                    } else {
                        editDropdowns.mouChoices.setChoiceByValue('');
                    }
                }

                if (editDropdowns.affiliationChoices) {
                    editDropdowns.affiliationChoices.removeActiveItems();

                    if (affiliation) {
                        editDropdowns.affiliationChoices.setChoiceByValue(affiliation);
                    }
                }
            }

            $editModal.removeClass('hidden').addClass('flex');
        });

        $('[data-close-modal="edit"]').on('click', function() {
            $editModal.addClass('hidden').removeClass('flex');
        });

        $editModal.on('click', function(e) {
            if (e.target === this) {
                $editModal.addClass('hidden').removeClass('flex');
            }
        });

        // Submit edit (AJAX)
        $editForm.on('submit', async function(e) {
            e.preventDefault();

            const form = this;
            const formData = new FormData(form);

            try {
                const res = await fetch(form.action, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });

                const raw = await res.text();
                let result;

                try {
                    result = JSON.parse(raw);
                } catch (parseErr) {
                    console.warn('Response ไม่ใช่ JSON ล้วน', raw);
                    result = {
                        success: false,
                        message: 'Invalid JSON response'
                    };
                }

                if (result.success === false) {
                    alert(result.message || 'เกิดข้อผิดพลาดในการอัปเดตข้อมูล');
                    return;
                }

                dt.ajax.reload(null, false);

                $editModal.addClass('hidden').removeClass('flex');
            } catch (err) {
                console.error(err);
                alert('ไม่สามารถเชื่อมต่อเซิร์ฟเวอร์ได้');
            }
        });

        // Submit add (AJAX)
        $addForm.on('submit', async function(e) {
            e.preventDefault();

            const form = this;
            const formData = new FormData(form);

            try {
                const res = await fetch(form.action, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });

                const raw = await res.text();
                let result;

                try {
                    result = JSON.parse(raw);
                } catch (parseErr) {
                    console.warn('Response ไม่ใช่ JSON ล้วน', raw);
                    result = {
                        success: false,
                        message: 'Invalid JSON response'
                    };
                }

                if (result.success === false) {
                    alert(result.message || 'เกิดข้อผิดพลาดในการเพิ่มข้อมูลฝึกงาน');
                    return;
                }

                dt.ajax.reload(null, false);
                form.reset();

                if (addDropdowns && typeof addDropdowns.resetValues === 'function') {
                    addDropdowns.resetValues();
                }

                $addModal.addClass('hidden').removeClass('flex');
            } catch (err) {
                console.error(err);
                alert('ไม่สามารถเชื่อมต่อเซิร์ฟเวอร์ได้');
            }
        });

        // Delete internship data record
        const $deleteModal = $('#deleteConfirmModal');
        let deleteId = null;

        // Show the modal when clicking delete button
        $(document).on('click', '.btn-delete', function() {
            const id = $(this).data('id');

            if (!id) {
                alert('ไม่พบรหัสข้อมูลสำหรับลบ');
                return;
            }

            deleteId = id;
            $deleteModal.removeClass('hidden').addClass('flex');
        });

        // Close the modal when cancel
        $('[data-close-modal="delete"]').on('click', function() {
            $deleteModal.addClass('hidden').removeClass('flex');
            deleteId = null;
        });

        // Click outside the modal to close the modal
        $deleteModal.on('click', function(e) {
            if (e.target === this) {
                $deleteModal.addClass('hidden').removeClass('flex');
                deleteId = null;
            }
        });

        // If the delete button in the modal was clicked
        $('#confirmDeleteBtn').on('click', async function() {
            if (!deleteId) {
                alert('ไม่พบรหัสข้อมูลสำหรับลบ');
                return;
            }

            const formData = new FormData();
            formData.append('id', deleteId);

            try {
                const res = await fetch('./actions/delete_internship.php', {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });

                const raw = await res.text();
                let result;

                try {
                    result = JSON.parse(raw);
                } catch (e) {
                    console.warn('Response ไม่ใช่ JSON ล้วน', raw);
                    alert('รูปแบบข้อมูลตอบกลับไม่ถูกต้อง');
                    return;
                }

                if (!result.success) {
                    alert(result.message || 'เกิดข้อผิดพลาดในการลบข้อมูล');
                    return;
                }

                $deleteModal.addClass('hidden').removeClass('flex');
                deleteId = null;
                dt.ajax.reload(null, false);
            } catch (err) {
                console.error(err);
                alert('ไม่สามารถเชื่อมต่อเซิร์ฟเวอร์ได้');
            }
        });
    });
</script>