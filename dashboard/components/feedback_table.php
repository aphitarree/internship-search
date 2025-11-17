<?php
require_once __DIR__ . '/../../vendor/autoload.php';
require_once __DIR__ . '/../../config/db_config.php';

use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(dirname(dirname(__DIR__)));
$dotenv->load();

$baseUrl = $_ENV['BASE_URL'] ?? '';
?>
<section class="bg-gray-100">
    <section>
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
            <h1 class="text-2xl font-semibold text-gray-800">ข้อมูล Feedback จากผู้ใช้</h1>
        </div>

        <div class="bg-white shadow rounded-xl mb-6">
            <div class="flex items-center justify-between px-4 py-3 border-b border-gray-200">
                <div class="flex items-center gap-2 text-gray-700">
                    <i class="fas fa-comment-dots"></i>
                    <span class="font-medium">รายการ Feedback</span>
                </div>
            </div>

            <div class="p-4">
                <div class="overflow-x-auto no-scrollbar">
                    <table id="feedbackTable" class="min-w-full text-sm text-left text-gray-700 w-full">
                        <thead class="bg-gray-50 border-b border-gray-200">
                            <tr>
                                <th class="px-3 py-2 font-semibold">NO.</th>
                                <th class="px-3 py-2 font-semibold">มีประโยชน์หรือไม่</th>
                                <th class="px-3 py-2 font-semibold">ความคิดเห็น</th>
                                <th class="px-3 py-2 font-semibold">วันที่ส่ง</th>
                                <th class="px-3 py-2 font-semibold text-center">จัดการ</th> <!-- ✨ เพิ่มคอลัมน์ปุ่มลบ -->
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100"></tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>

    <!-- Modal ยืนยันการลบ -->
    <div
        id="deleteModal"
        class="fixed inset-0 z-50 hidden bg-black/50 items-center justify-center px-2">
        <div class="bg-white rounded-xl shadow-lg w-full max-w-md">
            <div class="px-6 pt-6 pb-4 border-b border-gray-200 flex items-center justify-between">
                <h5 class="text-lg font-semibold text-red-600">
                    ยืนยันการลบ Feedback
                </h5>
                <button
                    type="button"
                    data-close="delete"
                    class="text-gray-400 hover:text-gray-600 transition">
                    <span class="text-xl leading-none">&times;</span>
                </button>
            </div>

            <div class="px-6 py-4">
                <p class="text-sm text-gray-700">
                    คุณต้องการลบ Feedback นี้หรือไม่?
                    <br>
                    <span class="text-xs text-gray-500">
                        ความคิดเห็น: <span id="delete-feedback-label" class="font-medium"></span>
                    </span>
                    <br>
                    <span class="text-xs text-gray-500">
                        เมื่อยืนยันแล้วจะไม่สามารถกู้คืนได้
                    </span>
                </p>
            </div>

            <div class="px-6 py-4 border-t border-gray-200 flex justify-end gap-2">
                <button
                    type="button"
                    data-close="delete"
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

    <!-- jQuery + DataTables -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>

    <script>
        function escapeHtml(text) {
            return $('<div>').text(text == null ? '' : text).html();
        }

        $(function() {
            const $deleteModal = $('#deleteModal');
            const $deleteLabel = $('#delete-feedback-label');
            let deleteId = null;

            const dt = $('#feedbackTable').DataTable({
                processing: true,
                serverSide: true,
                pageLength: 10,
                lengthMenu: [5, 10, 25, 50],
                ajax: {
                    url: './actions/fetch_feedback.php',
                    type: 'POST'
                },
                columns: [{
                        data: null,
                        render: function(data, type, row, meta) {
                            return meta.row + meta.settings._iDisplayStart + 1;
                        }
                    },
                    {
                        data: 'is_useful',
                        render: function(data) {
                            return escapeHtml(data);
                        }
                    },
                    {
                        data: 'comment',
                        render: function(data) {
                            return escapeHtml(data);
                        }
                    },
                    {
                        data: 'created_at',
                        render: function(data) {
                            return escapeHtml(data);
                        }
                    },
                    {
                        data: null,
                        orderable: false,
                        searchable: false,
                        render: function(data, type, row) {
                            return `
                                <div class="flex justify-center">
                                    <button
                                        type="button"
                                        class="btn-delete px-3 py-2 text-xs font-bold rounded-md bg-red-600 hover:bg-red-700 text-white"
                                        data-id="${row.id}"
                                        data-comment="${escapeHtml(row.comment)}"
                                    >
                                        ลบ
                                    </button>
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

            // เปิด modal ลบ
            $(document).on('click', '.btn-delete', function() {
                const $btn = $(this);
                deleteId = $btn.data('id');
                const comment = $btn.data('comment') || '';
                $deleteLabel.text(comment.length > 50 ? comment.substring(0, 50) + '…' : comment);
                $deleteModal.removeClass('hidden').addClass('flex');
            });

            // ปิด modal ลบ
            $('[data-close="delete"]').on('click', function() {
                $deleteModal.addClass('hidden').removeClass('flex');
                deleteId = null;
                $deleteLabel.text('');
            });

            $deleteModal.on('click', function(e) {
                if (e.target === this) {
                    $deleteModal.addClass('hidden').removeClass('flex');
                    deleteId = null;
                    $deleteLabel.text('');
                }
            });

            // ยืนยันลบ
            $('#confirmDeleteBtn').on('click', async function() {
                if (!deleteId) {
                    alert('ไม่พบรหัสสำหรับลบ');
                    return;
                }

                const fd = new FormData();
                fd.append('id', deleteId);

                try {
                    const resp = await fetch('./actions/delete_feedback.php', {
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
                        alert(result.message || 'เกิดข้อผิดพลาดในการลบข้อมูล');
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