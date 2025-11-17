<?php
require_once __DIR__ . '/../../vendor/autoload.php';
require_once __DIR__ . '/../../config/db_config.php';

use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(dirname(dirname(__DIR__)));
$dotenv->load();

$baseUrl = $_ENV['BASE_URL'] ?? '';
?>
<section class="bg-gray-100 p-6">
    <section class="mt-4">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
            <h1 class="text-2xl font-semibold text-gray-800">ข้อมูลคณะในระบบ</h1>
        </div>

        <div class="bg-white shadow rounded-xl mb-6">
            <div class="flex items-center justify-between px-4 py-3 border-b border-gray-200">
                <div class="flex items-center gap-2 text-gray-700">
                    <i class="fas fa-university"></i>
                    <span class="font-medium">รายการคณะ</span>
                </div>

                <button id="openAddModal" type="button"
                    class="inline-flex items-center px-3 py-1.5 text-sm font-medium rounded-lg bg-emerald-600 hover:bg-emerald-700 text-white transition">
                    + เพิ่มสาขา
                </button>
            </div>

            <div class="p-4">
                <div class="overflow-x-auto no-scrollbar">
                    <table id="facultyTable" class="min-w-full text-sm text-left text-gray-700 w-full">
                        <thead class="bg-gray-50 border-b border-gray-200">
                            <tr>
                                <th class="px-3 py-2 font-semibold">NO.</th>
                                <th class="px-3 py-2 font-semibold">คณะ</th>
                                <th class="px-3 py-2 font-semibold">หลักสูตร</th>
                                <th class="px-3 py-2 font-semibold">สาขา</th>
                                <th class="px-3 py-2 font-semibold text-center"></th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100"></tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>

    <!-- Add Modal -->
    <div id="addModal" class="fixed inset-0 z-50 hidden bg-black/50 items-center justify-center px-2">
        <div class="bg-white rounded-xl shadow-lg w-full max-w-xl">
            <form id="addForm" action="./actions/add_faculty.php" method="post" class="flex flex-col max-h-[90vh] px-8 pt-6 pb-8">
                <div class="flex items-center justify-between pb-4 border-b border-gray-200">
                    <h5 class="text-lg font-semibold">เพิ่มคณะ / หลักสูตร / สาขา</h5>
                    <button type="button" data-close="add" class="text-gray-400 hover:text-gray-600">&times;</button>
                </div>

                <div class="pt-4 space-y-4">
                    <div>
                        <label class="block text-gray-700 text-sm font-bold mb-2">คณะ</label>
                        <input type="text" name="faculty" required class="shadow border rounded w-full py-2 px-3 text-gray-700" />
                    </div>

                    <div>
                        <label class="block text-gray-700 text-sm font-bold mb-2">หลักสูตร</label>
                        <input type="text" name="program" required class="shadow border rounded w-full py-2 px-3 text-gray-700" />
                    </div>

                    <div>
                        <label class="block text-gray-700 text-sm font-bold mb-2">สาขา</label>
                        <input type="text" name="major" required class="shadow border rounded w-full py-2 px-3 text-gray-700" />
                    </div>
                </div>

                <div class="flex justify-end gap-2 pt-4 mt-4 border-t border-gray-200">
                    <button type="button" data-close="add" class="px-4 py-2 text-sm font-bold rounded border border-gray-300 text-gray-700">ยกเลิก</button>
                    <button type="submit" class="px-4 py-2 text-sm font-bold rounded bg-blue-500 hover:bg-blue-700 text-white">บันทึก</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Edit Modal -->
    <div id="editModal" class="fixed inset-0 z-50 hidden bg-black/50 items-center justify-center px-2">
        <div class="bg-white rounded-xl shadow-lg w-full max-w-xl">
            <form id="editForm" action="./actions/edit_faculty.php" method="post" class="flex flex-col max-h-[90vh] px-8 pt-6 pb-8">
                <div class="flex items-center justify-between pb-4 border-b border-gray-200">
                    <h5 class="text-lg font-semibold">แก้ไขข้อมูล</h5>
                    <button type="button" data-close="edit" class="text-gray-400 hover:text-gray-600">&times;</button>
                </div>

                <div class="pt-4 space-y-4">
                    <input type="hidden" name="id" id="edit-id" />

                    <div>
                        <label class="block text-gray-700 text-sm font-bold mb-2">คณะ</label>
                        <input type="text" name="faculty" id="edit-faculty" required class="shadow border rounded w-full py-2 px-3 text-gray-700" />
                    </div>

                    <div>
                        <label class="block text-gray-700 text-sm font-bold mb-2">หลักสูตร</label>
                        <input type="text" name="program" id="edit-program" required class="shadow border rounded w-full py-2 px-3 text-gray-700" />
                    </div>

                    <div>
                        <label class="block text-gray-700 text-sm font-bold mb-2">สาขา</label>
                        <input type="text" name="major" id="edit-major" required class="shadow border rounded w-full py-2 px-3 text-gray-700" />
                    </div>
                </div>

                <div class="flex justify-end gap-2 pt-4 mt-4 border-t border-gray-200">
                    <button type="button" data-close="edit" class="px-4 py-2 text-sm font-bold rounded border border-gray-300 text-gray-700">ยกเลิก</button>
                    <button type="submit" name="update" class="px-4 py-2 text-sm font-bold rounded bg-blue-500 hover:bg-blue-700 text-white">อัปเดต</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Delete Modal -->
    <div id="deleteModal" class="fixed inset-0 z-50 hidden bg-black/50 items-center justify-center px-2">
        <div class="bg-white rounded-xl shadow-lg w-full max-w-md">
            <div class="px-6 pt-6 pb-4 border-b border-gray-200 flex items-center justify-between">
                <h5 class="text-lg font-semibold text-red-600">ยืนยันการลบ</h5>
                <button type="button" data-close="delete" class="text-gray-400 hover:text-gray-600">&times;</button>
            </div>

            <div class="px-6 py-4">
                <p class="text-sm text-gray-700">
                    ต้องการลบรายการนี้หรือไม่?
                    <br>
                    <span class="text-xs text-gray-500">สาขา: <span id="delete-major-label" class="font-medium text-gray-800"></span></span>
                </p>
            </div>

            <div class="px-6 py-4 border-t border-gray-200 flex justify-end gap-2">
                <button type="button" data-close="delete" class="px-4 py-2 text-sm font-bold rounded border border-gray-300 text-gray-700">ยกเลิก</button>
                <button type="button" id="confirmDeleteBtn" class="px-4 py-2 text-sm font-bold rounded bg-red-600 hover:bg-red-700 text-white">ลบ</button>
            </div>
        </div>
    </div>

    <!-- jQuery + DataTables -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>

    <script>
        function escapeHtml(text) {
            return $('<div>').text(text == null ? '' : text).html();
        }

        $(function() {
            const $addModal = $('#addModal');
            const $editModal = $('#editModal');
            const $deleteModal = $('#deleteModal');
            const $deleteLabel = $('#delete-major-label');

            let deleteId = null;

            const dt = $('#facultyTable').DataTable({
                processing: true,
                serverSide: true,
                pageLength: 10,
                lengthMenu: [5, 10, 25, 50],
                ajax: {
                    url: './actions/fetch_faculty.php',
                    type: 'POST'
                },
                columns: [{
                        data: null,
                        render: function(data, type, row, meta) {
                            return meta.row + meta.settings._iDisplayStart + 1;
                        }
                    },
                    {
                        data: 'faculty'
                    },
                    {
                        data: 'program'
                    },
                    {
                        data: 'major'
                    },
                    {
                        data: null,
                        orderable: false,
                        searchable: false,
                        render: function(data, type, row) {
                            return `
                            <div class="flex gap-2">
                                <button type="button" class="btn-edit px-3 py-2 text-xs font-bold rounded-md bg-blue-600 hover:bg-blue-700 text-white"
                                    data-id="${row.id}"
                                    data-faculty="${escapeHtml(row.faculty)}"
                                    data-program="${escapeHtml(row.program)}"
                                    data-major="${escapeHtml(row.major)}"
                                >แก้ไข</button>

                                <button type="button" class="btn-delete px-3 py-2 text-xs font-bold rounded-md bg-red-600 hover:bg-red-700 text-white"
                                    data-id="${row.id}"
                                    data-major="${escapeHtml(row.major)}"
                                >ลบ</button>
                            </div>
                        `;
                        }
                    }
                ],
                createdRow: function(row, data) {
                    $(row).attr('data-id', data.id);
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

            // Open add modal
            $('#openAddModal').on('click', function() {
                $('#addForm')[0].reset();
                $addModal.removeClass('hidden').addClass('flex');
            });

            // Close modals
            $('[data-close="add"]').on('click', function() {
                $addModal.addClass('hidden').removeClass('flex');
            });
            $('[data-close="edit"]').on('click', function() {
                $editModal.addClass('hidden').removeClass('flex');
            });
            $('[data-close="delete"]').on('click', function() {
                $deleteModal.addClass('hidden').removeClass('flex');
                deleteId = null;
                $deleteLabel.text('');
            });

            $addModal.on('click', function(e) {
                if (e.target === this) $addModal.addClass('hidden').removeClass('flex');
            });
            $editModal.on('click', function(e) {
                if (e.target === this) $editModal.addClass('hidden').removeClass('flex');
            });
            $deleteModal.on('click', function(e) {
                if (e.target === this) $deleteModal.addClass('hidden').removeClass('flex');
                deleteId = null;
                $deleteLabel.text('');
            });

            // Submit add (AJAX)
            $('#addForm').on('submit', async function(e) {
                e.preventDefault();
                const form = this;
                const fd = new FormData(form);
                try {
                    const resp = await fetch(form.action, {
                        method: 'POST',
                        body: fd,
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    });
                    const text = await resp.text();
                    let result;
                    try {
                        result = JSON.parse(text);
                    } catch {
                        alert('ตอบกลับไม่ใช่ JSON');
                        return;
                    }
                    if (!result.success) {
                        alert(result.message || 'เกิดข้อผิดพลาด');
                        return;
                    }
                    dt.ajax.reload(null, false);
                    form.reset();
                    $addModal.addClass('hidden').removeClass('flex');
                } catch (err) {
                    console.error(err);
                    alert('ไม่สามารถเชื่อมต่อเซิร์ฟเวอร์ได้');
                }
            });

            // Open edit modal
            $(document).on('click', '.btn-edit', function() {
                const $btn = $(this);
                $('#edit-id').val($btn.data('id'));
                $('#edit-faculty').val($btn.data('faculty'));
                $('#edit-program').val($btn.data('program'));
                $('#edit-major').val($btn.data('major'));
                $editModal.removeClass('hidden').addClass('flex');
            });

            // Submit edit (AJAX)
            $('#editForm').on('submit', async function(e) {
                e.preventDefault();
                const form = this;
                const fd = new FormData(form);
                try {
                    const resp = await fetch(form.action, {
                        method: 'POST',
                        body: fd,
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    });
                    const text = await resp.text();
                    let result;
                    try {
                        result = JSON.parse(text);
                    } catch {
                        alert('ตอบกลับไม่ใช่ JSON');
                        return;
                    }
                    if (!result.success) {
                        alert(result.message || 'เกิดข้อผิดพลาด');
                        return;
                    }
                    dt.ajax.reload(null, false);
                    $editModal.addClass('hidden').removeClass('flex');
                } catch (err) {
                    console.error(err);
                    alert('ไม่สามารถเชื่อมต่อเซิร์ฟเวอร์ได้');
                }
            });

            // Open delete modal
            $(document).on('click', '.btn-delete', function() {
                const $btn = $(this);
                deleteId = $btn.data('id');
                $deleteLabel.text($btn.data('major') || '');
                $deleteModal.removeClass('hidden').addClass('flex');
            });

            // Confirm delete
            $('#confirmDeleteBtn').on('click', async function() {
                if (!deleteId) {
                    alert('ไม่พบรหัสสำหรับลบ');
                    return;
                }
                try {
                    const fd = new FormData();
                    fd.append('id', deleteId);
                    const resp = await fetch('./actions/delete_faculty.php', {
                        method: 'POST',
                        body: fd,
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    });
                    const text = await resp.text();
                    let result;
                    try {
                        result = JSON.parse(text);
                    } catch {
                        alert('ตอบกลับไม่ใช่ JSON');
                        return;
                    }
                    if (!result.success) {
                        alert(result.message || 'เกิดข้อผิดพลาด');
                        return;
                    }
                    dt.ajax.reload(null, false);
                    $deleteModal.addClass('hidden').removeClass('flex');
                    deleteId = null;
                    $deleteLabel.text('');
                } catch (err) {
                    console.error(err);
                    alert('ไม่สามารถเชื่อมต่อเซิร์ฟเวอร์ได้');
                }
            });
        });
    </script>
</section>