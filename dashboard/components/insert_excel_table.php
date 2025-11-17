<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/../../vendor/autoload.php';
require_once __DIR__ . '/../../config/db_config.php';

use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(dirname(dirname(__DIR__)));
$dotenv->load();

// if (!isset($_POST['submit'])) {
//     unset($_SESSION['invalid_rows']);
// }

$baseUrl = $_ENV['BASE_URL'] ?? '';
?>

<section class="mt-4">
    <style>
        td.cell-contact {
            white-space: normal;
            word-wrap: break-word;
            word-break: break-word;
        }
    </style>

    <?php if (isset($_SESSION['inserted_data'])): ?>
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
            <h1 class="text-2xl font-semibold text-gray-800">ข้อมูลสถานที่ฝึกงาน</h1>
        </div>

        <div class="bg-white shadow rounded-xl mb-6">
            <div class="flex items-center justify-between px-4 py-3 border-b border-gray-200">
                <div class="flex items-center gap-2 text-gray-700">
                    <i class="fas fa-table"></i>
                    <span class="font-medium">ข้อมูลที่นำเข้า (จาก Excel)</span>
                </div>
            </div>

            <div class="p-4">
                <div class="overflow-x-auto no-scrollbar">
                    <table id="internshipTable" class="min-w-full text-sm text-left text-gray-700">
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
                                <th class="px-3 py-2 font-semibold">MOU</th>
                                <th class="px-3 py-2 font-semibold">ข้อมูลการติดต่อ</th>
                                <th class="px-3 py-2 font-semibold">คะแนน</th>
                                <th class="px-3 py-2 font-semibold">สังกัด</th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-gray-100">
                            <!-- Output the valid rows -->
                            <?php if (count($_SESSION['inserted_data']) > 0): ?>
                                <?php foreach ($_SESSION['inserted_data'] as $i => $row): ?>
                                    <tr>
                                        <td><?= $i + 1 ?></td>
                                        <td><?= htmlspecialchars($row['organization']) ?></td>
                                        <td><?= htmlspecialchars($row['province']) ?></td>
                                        <td><?= htmlspecialchars($row['faculty']) ?></td>
                                        <td><?= htmlspecialchars($row['program']) ?></td>
                                        <td><?= htmlspecialchars($row['major']) ?></td>
                                        <td><?= htmlspecialchars($row['year']) ?></td>
                                        <td><?= htmlspecialchars($row['total_student']) ?></td>
                                        <td><?= htmlspecialchars($row['mou_status']) ?></td>
                                        <td class="cell-contact"><?= htmlspecialchars($row['contact']) ?></td>
                                        <td><?= htmlspecialchars($row['score']) ?></td>
                                        <td><?= htmlspecialchars($row['affiliation']) ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>

                    <?php
                    unset($_SESSION['inserted_data']);
                    unset($_SESSION['message']);
                    ?>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <!-- Output the invalid rows -->
    <?php if (!isset($_POST['submit'])): ?>
        <?php if (isset($_SESSION['invalid_rows']) && count($_SESSION['invalid_rows']) > 0): ?>
            <div class="bg-white shadow rounded-xl mb-6">
                <div class="flex items-center justify-between px-4 py-3 border-b border-gray-200">
                    <div class="flex items-center gap-2 text-gray-700">
                        <i class="fas fa-exclamation-triangle text-yellow-500"></i>
                        <span class="font-medium">ข้อมูลที่ไม่ถูกต้อง (ไม่สามารถบันทึกได้)</span>
                    </div>
                </div>

                <div class="p-4">
                    <div class="overflow-x-auto no-scrollbar">
                        <table id="invalidTable" class="min-w-full text-sm text-left text-gray-700">
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
                                    <th class="px-3 py-2 font-semibold">MOU</th>
                                    <th class="px-3 py-2 font-semibold">ข้อมูลการติดต่อ</th>
                                    <th class="px-3 py-2 font-semibold">คะแนน</th>
                                    <th class="px-3 py-2 font-semibold">สังกัด</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                <?php foreach ($_SESSION['invalid_rows'] as $i => $row):

                                ?>
                                    <tr>
                                        <td><?= $i + 1 ?></td>
                                        <td><?= htmlspecialchars($row['organization'] ?? '-') ?></td>
                                        <td><?= htmlspecialchars($row['province'] ?? '-') ?></td>
                                        <td><?= htmlspecialchars($row['faculty'] ?? '-') ?></td>
                                        <td><?= htmlspecialchars($row['program'] ?? '-') ?></td>
                                        <td><?= htmlspecialchars($row['major'] ?? '-') ?></td>
                                        <td><?= htmlspecialchars($row['year'] ?? '-') ?></td>
                                        <td><?= htmlspecialchars($row['total_student'] ?? '-') ?></td>
                                        <td><?= htmlspecialchars($row['mou_status'] ?? '-') ?></td>
                                        <td class="cell-contact"><?= htmlspecialchars($row['contact'] ?? '-') ?></td>
                                        <td><?= htmlspecialchars($row['score'] ?? '-') ?></td>
                                        <td><?= htmlspecialchars($row['affiliation'] ?? '-') ?></td>

                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>


                    </div>
                </div>
            </div>
            <a
                href="<?php echo $baseUrl; ?>/dashboard/actions/edit_excel_form.php"
                class="inline-block pl-3 pr-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white font-medium text-sm rounded-lg shadow focus:ring-2 focus:ring-blue-400 focus:outline-none transition-all duration-200">
                <i class="fa-solid fa-download mr-2"></i>ดาวน์โหลด Excel ข้อมูลที่ไม่ถูกต้อง

            </a>
        <?php endif; ?>
    <?php endif; ?>
</section>

<script>
    $(function() {
        $('#internshipTable').DataTable({
            pageLength: 10,
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

        $('#invalidTable').DataTable({
            pageLength: 10,
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
    });
</script>