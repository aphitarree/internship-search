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

<!-- DataTables CSS -->
<link
    rel="stylesheet"
    href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.min.css">

<!-- Choices.js CSS สำหรับ dropdown สวย ๆ + searchable -->
<link
    rel="stylesheet"
    href="https://cdn.jsdelivr.net/npm/choices.js/public/assets/styles/choices.min.css" />

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
                <table
                    id="internshipTable"
                    class="min-w-full text-sm text-left text-gray-700">
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
                        <label class="block text-gray-700 text-sm font-bold mb-2">บริษัท</label>
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
                        <label class="block text-gray-700 text-sm font-bold mb-2">คณะ</label>
                        <select
                            name="faculty"
                            id="add-faculty"
                            class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                            required>
                            <option value="">-เลือกคณะ-</option>
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
                        <label class="block text-gray-700 text-sm font-bold mb-2">สาขา</label>
                        <select
                            name="major"
                            id="add-major"
                            class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                            required>
                            <option value="">-เลือกสาขา-</option>
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-gray-700 text-sm font-bold mb-2">ปีการศึกษา</label>
                        <select
                            name="year"
                            id="add-year"
                            class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                            required>
                            <option value="">-เลือกปีการศึกษา-</option>
                        </select>
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
                        <label class="block text-gray-700 text-sm font-bold mb-2">คณะ</label>
                        <select
                            name="faculty"
                            id="edit-faculty"
                            class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                            required>
                            <option value="">-เลือกคณะ-</option>
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
                        <label class="block text-gray-700 text-sm font-bold mb-2">สาขา</label>
                        <select
                            name="major"
                            id="edit-major"
                            class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                            required>
                            <option value="">-เลือกสาขา-</option>
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-gray-700 text-sm font-bold mb-2">ปีการศึกษา</label>
                        <select
                            name="year"
                            id="edit-year"
                            class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                            required>
                            <option value="">-เลือกปีการศึกษา-</option>
                        </select>
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

<!-- jQuery + DataTables JS -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>

<!-- Choices.js JS -->
<script src="https://cdn.jsdelivr.net/npm/choices.js/public/assets/scripts/choices.min.js"></script>

<script>
    // helper escape HTML เวลาเอาข้อความไปใส่ใน data-* / innerHTML
    function escapeHtml(text) {
        return $('<div>').text(text == null ? '' : text).html();
    }

    // ข้อมูล mapping คณะ → สาขา → หลักสูตร
    const facultyMajorsPrograms = {
        "คณะครุศาสตร์": {
            "การศึกษาปฐมวัย": "หลักสูตรศึกษาศาสตรบัณฑิต",
            "การประถมศึกษา": "หลักสูตรศึกษาศาสตรบัณฑิต",
        },
        "คณะวิทยาศาสตร์และเทคโนโลยี": {
            "เทคโนโลยีสารสนเทศ": "หลักสูตรวิทยาศาสตรบัณฑิต",
            "สิ่งแวดล้อมเมืองและอุตสาหกรรม": "หลักสูตรวิทยาศาสตรบัณฑิต",
            "วิทยาศาสตร์เครื่องสำอาง": "หลักสูตรวิทยาศาสตรบัณฑิต",
            "อาชีวอนามัยและความปลอดภัย": "หลักสูตรวิทยาศาสตรบัณฑิต",
            "เทคโนโลยีเคมี": "หลักสูตรวิทยาศาสตรบัณฑิต",
            "วิทยาการคอมพิวเตอร์": "หลักสูตรวิทยาศาสตรบัณฑิต",
            "คณิตศาสตร์": "หลักสูตรศึกษาศาสตรบัณฑิต",
            "ฟิสิกส์": "หลักสูตรศึกษาศาสตรบัณฑิต",
            "ความมั่นคงปลอดภัยไซเบอร์": "หลักสูตรวิทยาศาสตรบัณฑิต",
        },
        "คณะวิทยาการจัดการ": {
            "การบัญชี": "หลักสูตรบัญชีบัณฑิต",
            "การเงิน": "หลักสูตรบริหารธุรกิจบัณฑิต",
            "การตลาด": "หลักสูตรบริหารธุรกิจบัณฑิต",
            "การจัดการ": "หลักสูตรการจัดการบัณฑิต",
            "การบริการลูกค้า": "หลักสูตรบริหารธุรกิจบัณฑิต",
            "การจัดการธุรกิจค้าปลีก": "หลักสูตรบริหารธุรกิจบัณฑิต",
            "การจัดการทรัพยากรมนุษย์": "หลักสูตรบริหารธุรกิจบัณฑิต",
            "คอมพิวเตอร์ธุรกิจ": "หลักสูตรบริหารธุรกิจบัณฑิต",
            "ธุรกิจระหว่างประเทศ": "หลักสูตรบริหารธุรกิจบัณฑิต",
            "นิเทศศาสตร์": "หลักสูตรนิเทศศาสตรบัณฑิต",
            "เลขานุการทางการแพทย์": "หลักสูตรบริหารธุรกิจบัณฑิต",
        },
        "คณะมนุษยศาสตร์และสังคมศาสตร์": {
            "ภาษาอังกฤษธุรกิจ": "หลักสูตรศิลปศาสตรบัณฑิต",
            "จิตวิทยาอุตสาหกรรมและองค์การ": "หลักสูตรศิลปศาสตรบัณฑิต",
            "ภาษาไทย": "หลักสูตรศิลปศาสตรบัณฑิต",
            "ภาษาอังกฤษ": "หลักสูตรศิลปศาสตรบัณฑิต",
            "ภาษาจีน": "หลักสูตรศิลปศาสตรบัณฑิต",
            "ศิลปศึกษา": "หลักสูตรศึกษาศาสตรบัณฑิต",
            "บรรณารักษศาสตร์และสารสนเทศศาสตร์": "หลักสูตรศิลปศาสตรบัณฑิต",
            "นิติศาสตร์": "หลักสูตรนิติศาสตรบัณฑิต",
        },
        "คณะพยาบาลศาสตร์": {
            "พยาบาลศาสตร์": "หลักสูตรพยาบาลศาสตรบัณฑิต"
        },
        "โรงเรียนการท่องเที่ยวและการบริการ": {
            "การท่องเที่ยว": "หลักสูตรศิลปศาสตรบัณฑิต",
            "ธุรกิจการโรงแรม": "หลักสูตรศิลปศาสตรบัณฑิต",
            "ธุรกิจการบิน": "หลักสูตรศิลปศาสตรบัณฑิต",
            "ออกแบบนิทรรศการและการแสดง": "หลักสูตรศิลปศาสตรบัณฑิต",
            "การจัดการงานบริการ (นานาชาติ)": "หลักสูตรศิลปศาสตรบัณฑิต",
        },
        "โรงเรียนการเรือน": {
            "เทคโนโลยีการแปรรูปอาหาร": "หลักสูตรวิทยาศาสตรบัณฑิต",
            "เทคโนโลยีการประกอบอาหารและบริการ": "หลักสูตรวิทยาศาสตรบัณฑิต",
            "คหกรรมศาสตร์": "หลักสูตรศิลปศาสตรบัณฑิต",
            "โภชนการและการประกอบอาหาร": "หลักสูตรวิทยาศาสตรบัณฑิต",
        }
    };

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

    const academicYears = [
        "2568",
        "2567",
        "2566",
        "2565",
        "2564",
        "2563"
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
        const yearSelect = document.getElementById(prefix + '-year');

        if (!facultySelect || !majorSelect || !programSelect || !provinceSelect || !yearSelect) {
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
            searchPlaceholderValue: "พิมพ์เพื่อค้นหาคณะ...",
            sorter: sortChoice,
        });
        const majorChoices = new Choices(majorSelect, {
            searchEnabled: true,
            itemSelectText: "",
            searchPlaceholderValue: "พิมพ์เพื่อค้นหาสาขา...",
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
        const yearChoices = new Choices(yearSelect, {
            searchEnabled: true,
            itemSelectText: "",
            searchPlaceholderValue: "พิมพ์เพื่อค้นหาปีการศึกษา...",
            sorter: sortChoice,
        });

        const populateFaculties = (list) => {
            facultyChoices.clearStore();
            facultyChoices.setChoices(
                [{
                    value: "",
                    label: "-เลือกคณะ-",
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
                    label: "-เลือกสาขา-",
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

        const populateYears = (list) => {
            yearChoices.clearStore();
            yearChoices.setChoices(
                [{
                    value: "",
                    label: "-เลือกปีการศึกษา-",
                    selected: true,
                    disabled: false
                }].concat(
                    list.map(year => ({
                        value: year,
                        label: year
                    }))
                ),
                "value", "label", true
            );
        };

        // initial populate
        populateFaculties(allFaculties);
        populateMajors(allMajors);
        populatePrograms(allPrograms);
        populateProvinces(provinces);
        populateYears(academicYears);

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

        function resetValues() {
            facultyChoices.setChoiceByValue('');
            majorChoices.setChoiceByValue('');
            programChoices.setChoiceByValue('');
            provinceChoices.setChoiceByValue('');
            yearChoices.setChoiceByValue('');
        }

        return {
            facultyChoices,
            majorChoices,
            programChoices,
            provinceChoices,
            yearChoices,
            resetValues
        };
    }

    $(function() {
        const $addModal = $('#addInternshipModal');
        const $editModal = $('#editInternshipModal');
        const $editForm = $('#editForm');

        const addDropdowns = setupDropdownGroup('add');
        const editDropdowns = setupDropdownGroup('edit');

        const dt = $('#internshipTable').DataTable({
            processing: true,
            serverSide: true,
            pageLength: 10,
            lengthMenu: [5, 10, 25, 50],
            ajax: {
                url: './actions/fetch_internships.php',
                type: 'POST'
            },
            columns: [{
                data: null,
                render: function(data, type, row, meta) {
                    return meta.row + meta.settings._iDisplayStart + 1;
                }
            }, {
                data: 'organization'
            }, {
                data: 'province'
            }, {
                data: 'faculty'
            }, {
                data: 'program'
            }, {
                data: 'major'
            }, {
                data: 'year'
            }, {
                data: 'total_student'
            }, {
                data: 'contact'
            }, {
                data: 'score'
            }, {
                data: null,
                orderable: false,
                searchable: false,
                render: function(data, type, row) {
                    return `
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
                                data-contact="${escapeHtml(row.contact)}"
                                data-score="${escapeHtml(row.score ?? '')}"
                            >
                                แก้ไข
                            </button>
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
                $(cells[8]).addClass('cell-contact');
                $(cells[9]).addClass('cell-score');
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

            $('#edit-id').val($btn.data('id'));
            $('#edit-organization').val($btn.data('organization'));
            $('#edit-total_student').val($btn.data('total_student'));
            $('#edit-contact').val($btn.data('contact'));
            $('#edit-score').val($btn.data('score'));

            const faculty = $btn.data('faculty') || '';
            const major = $btn.data('major') || '';
            const program = $btn.data('program') || '';
            const province = $btn.data('province') || '';
            const year = $btn.data('year') != null ? String($btn.data('year')) : '';

            if (editDropdowns) {
                if (province) {
                    editDropdowns.provinceChoices.setChoiceByValue(province);
                } else {
                    editDropdowns.provinceChoices.setChoiceByValue('');
                }

                if (year) {
                    editDropdowns.yearChoices.setChoiceByValue(year);
                } else {
                    editDropdowns.yearChoices.setChoiceByValue('');
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

        // Submit Edit (AJAX)
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
    });
</script>