<?php
require_once __DIR__ . '/../../vendor/autoload.php';
require_once __DIR__ . '/../../config/db_config.php';

use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(dirname(dirname(__DIR__)));
$dotenv->load();

$baseUrl = $_ENV['BASE_URL'] ?? '';
?>

<section class="mt-4">
    <!-- ตารางข้อมูลผู้ใช้ -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
        <h1 class="text-2xl font-semibold text-gray-800">
            ข้อมูลผู้ใช้ระบบ
        </h1>
    </div>

    <div class="bg-white shadow rounded-xl mb-6">
        <div class="flex items-center justify-between px-4 py-3 border-b border-gray-200">
            <div class="flex items-center gap-2 text-gray-700">
                <i class="fas fa-users"></i>
                <span class="font-medium">รายการผู้ใช้</span>
            </div>

            <!-- ปุ่มเปิด Add Modal -->
            <button
                id="openAddUserModal"
                type="button"
                class="inline-flex items-center px-3 py-1.5 text-sm font-medium rounded-lg bg-emerald-600 hover:bg-emerald-700 text-white transition">
                + เพิ่มผู้ใช้
            </button>
        </div>

        <div class="p-4">
            <div class="overflow-x-auto no-scrollbar">
                <table
                    id="userTable"
                    class="min-w-full text-sm text-left text-gray-700">
                    <thead class="bg-gray-50 border-b border-gray-200">
                        <tr>
                            <th class="px-3 py-2 font-semibold">NO.</th>
                            <th class="px-3 py-2 font-semibold">อีเมล</th>
                            <th class="px-3 py-2 font-semibold">ชื่อผู้ใช้</th>
                            <th class="px-3 py-2 font-semibold">สิทธิ์</th>
                            <th class="px-3 py-2 font-semibold">วันที่สร้าง</th>
                            <th class="px-3 py-2 font-semibold text-center"></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100"></tbody>
                </table>
            </div>
        </div>
    </div>
</section>

<!-- Add User Modal -->
<div
    id="addUserModal"
    class="fixed inset-0 z-50 hidden bg-black/50 items-center justify-center px-2">
    <div class="bg-white rounded-xl shadow-lg w-full max-w-xl">
        <form
            method="post"
            action="./actions/add_user.php"
            id="addUserForm"
            class="flex flex-col max-h-[90vh] bg-white shadow-md rounded px-8 pt-6 pb-8">
            <div class="flex items-center justify-between pb-4 border-b border-gray-200">
                <h5 class="text-lg font-semibold">เพิ่มผู้ใช้</h5>
                <button
                    type="button"
                    data-close-modal="add-user"
                    class="text-gray-400 hover:text-gray-600 transition">
                    <span class="text-xl leading-none">&times;</span>
                </button>
            </div>

            <div class="pt-4 space-y-4 overflow-y-auto">
                <div>
                    <label class="block text-gray-700 text-sm font-bold mb-2">อีเมล</label>
                    <input
                        type="email"
                        name="email"
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                        required>
                </div>

                <div>
                    <label class="block text-gray-700 text-sm font-bold mb-2">ชื่อผู้ใช้</label>
                    <input
                        type="text"
                        name="username"
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                        required>
                </div>

                <div>
                    <label class="block text-gray-700 text-sm font-bold mb-2">รหัสผ่าน</label>
                    <input
                        type="password"
                        name="password"
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                        required>
                </div>

                <div>
                    <label class="block text-gray-700 text-sm font-bold mb-2">สิทธิ์</label>
                    <select
                        name="role"
                        id="add-role"
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                        required>
                        <option value="user" selected>user</option>
                        <option value="admin">admin</option>
                    </select>
                </div>
            </div>

            <div class="flex justify-end gap-2 pt-4 mt-4 border-t border-gray-200">
                <button
                    type="button"
                    data-close-modal="add-user"
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

<!-- Edit User Modal -->
<div
    id="editUserModal"
    class="fixed inset-0 z-50 hidden bg-black/50 items-center justify-center px-2">
    <div class="bg-white rounded-xl shadow-lg w-full max-w-xl">
        <form
            method="post"
            action="./actions/edit_user.php"
            class="flex flex-col max-h-[90vh] bg-white shadow-md rounded px-8 pt-6 pb-8"
            id="editUserForm">
            <div class="flex items-center justify-between pb-4 border-b border-gray-200">
                <h5 class="text-lg font-semibold">แก้ไขผู้ใช้</h5>
                <button
                    type="button"
                    data-close-modal="edit-user"
                    class="text-gray-400 hover:text-gray-600 transition">
                    <span class="text-xl leading-none">&times;</span>
                </button>
            </div>

            <div class="pt-4 space-y-4 overflow-y-auto">
                <input type="hidden" name="id" id="edit-id">

                <div>
                    <label class="block text-gray-700 text-sm font-bold mb-2">อีเมล</label>
                    <input
                        type="email"
                        name="email"
                        id="edit-email"
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                        required>
                </div>

                <div>
                    <label class="block text-gray-700 text-sm font-bold mb-2">ชื่อผู้ใช้</label>
                    <input
                        type="text"
                        name="username"
                        id="edit-username"
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                        required>
                </div>

                <div>
                    <label class="block text-gray-700 text-sm font-bold mb-2">
                        รหัสผ่านใหม่ (ถ้าไม่เปลี่ยนให้เว้นว่าง)
                    </label>
                    <input
                        type="password"
                        name="password"
                        id="edit-password"
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                </div>

                <div>
                    <label class="block text-gray-700 text-sm font-bold mb-2">สิทธิ์</label>
                    <select
                        name="role"
                        id="edit-role"
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                        required>
                        <option value="user">user</option>
                        <option value="admin">admin</option>
                    </select>
                </div>
            </div>

            <div class="flex justify-end gap-2 pt-4 mt-4 border-t border-gray-200">
                <button
                    type="button"
                    data-close-modal="edit-user"
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

<!-- Delete User Modal -->
<div
    id="deleteUserModal"
    class="fixed inset-0 z-50 hidden bg-black/50 items-center justify-center px-2">
    <div class="bg-white rounded-xl shadow-lg w-full max-w-md">
        <div class="px-6 pt-6 pb-4 border-b border-gray-200 flex items-center justify-between">
            <h5 class="text-lg font-semibold text-red-600">
                ยืนยันการลบผู้ใช้
            </h5>
            <button
                type="button"
                data-close-modal="delete-user"
                class="text-gray-400 hover:text-gray-600 transition">
                <span class="text-xl leading-none">&times;</span>
            </button>
        </div>

        <div class="px-6 py-4">
            <p class="text-sm text-gray-700">
                คุณต้องการลบผู้ใช้ระบบนี้หรือไม่?
                <br>
                <span class="text-xs text-gray-500">
                    อีเมล: <span id="delete-user-email-label" class="font-medium text-gray-800"></span>
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
                data-close-modal="delete-user"
                class="px-4 py-2 text-sm font-bold rounded border border-gray-300 text-gray-700 hover:bg-gray-50">
                ยกเลิก
            </button>
            <button
                type="button"
                id="confirmDeleteUserBtn"
                class="px-4 py-2 text-sm font-bold rounded bg-red-600 hover:bg-red-700 text-white">
                ลบผู้ใช้
            </button>
        </div>
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

    $(function() {
        const $addModal = $('#addUserModal');
        const $addForm = $('#addUserForm');
        const $editModal = $('#editUserModal');
        const $editForm = $('#editUserForm');

        // ใช้ Choices.js กับ select role (ไม่บังคับ แต่ช่วยให้ UI สวยขึ้น)
        const addRoleSelect = document.getElementById('add-role');
        const editRoleSelect = document.getElementById('edit-role');

        let addRoleChoices = null;
        let editRoleChoices = null;

        if (addRoleSelect) {
            addRoleChoices = new Choices(addRoleSelect, {
                searchEnabled: false,
                itemSelectText: "",
            });
        }

        if (editRoleSelect) {
            editRoleChoices = new Choices(editRoleSelect, {
                searchEnabled: false,
                itemSelectText: "",
            });
        }

        const dt = $('#userTable').DataTable({
            processing: true,
            serverSide: true,
            pageLength: 10,
            lengthMenu: [5, 10, 25, 50],
            ajax: {
                url: './actions/fetch_users.php',
                type: 'POST'
            },
            columns: [{
                data: null,
                render: function(data, type, row, meta) {
                    return meta.row + meta.settings._iDisplayStart + 1;
                }
            }, {
                data: 'email'
            }, {
                data: 'username'
            }, {
                data: 'role'
            }, {
                data: null,
                orderable: false,
                searchable: false,
                render: function(data, type, row) {
                    return `
                        <div class="flex gap-2">
                            <button
                                type="button"
                                class="whitespace-nowrap btn-edit-user inline-flex items-center px-3 py-2 text-xs font-bold rounded-md bg-blue-600 hover:bg-blue-700 text-white transition"
                                data-id="${row.id}"
                                data-email="${escapeHtml(row.email)}"
                                data-username="${escapeHtml(row.username)}"
                                data-role="${escapeHtml(row.role)}"
                            >
                                แก้ไข
                            </button>

                            <button
                                type="button"
                                class="whitespace-nowrap btn-delete-user inline-flex items-center px-3 py-2 text-xs font-bold rounded-md bg-red-600 hover:bg-red-700 text-white transition"
                                data-id="${row.id}"
                                data-email="${escapeHtml(row.email)}"
                            >
                                ลบ
                            </button>
                        </div>
                    `;
                }
            }],
            createdRow: function(row, data) {
                $(row).attr('data-row-id', data.id);
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
        $('#openAddUserModal').on('click', function() {
            $('#addUserModal form')[0].reset();
            if (addRoleChoices) {
                addRoleChoices.setChoiceByValue('user');
            }
            $addModal.removeClass('hidden').addClass('flex');
        });

        $('[data-close-modal="add-user"]').on('click', function() {
            $addModal.addClass('hidden').removeClass('flex');
        });

        $addModal.on('click', function(e) {
            if (e.target === this) {
                $addModal.addClass('hidden').removeClass('flex');
            }
        });

        // Submit add user (AJAX)
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
                    alert(result.message || 'เกิดข้อผิดพลาดในการเพิ่มผู้ใช้');
                    return;
                }

                dt.ajax.reload(null, false);
                form.reset();

                if (addRoleChoices) {
                    addRoleChoices.setChoiceByValue('user');
                }

                $addModal.addClass('hidden').removeClass('flex');
            } catch (err) {
                console.error(err);
                alert('ไม่สามารถเชื่อมต่อเซิร์ฟเวอร์ได้');
            }
        });

        // Edit Modal
        $(document).on('click', '.btn-edit-user', function() {
            const $btn = $(this);

            $('#edit-id').val($btn.data('id'));
            $('#edit-email').val($btn.data('email'));
            $('#edit-username').val($btn.data('username'));
            $('#edit-password').val(''); // เคลียร์ช่องรหัสผ่านใหม่

            const role = $btn.data('role') || 'user';
            if (editRoleChoices) {
                editRoleChoices.setChoiceByValue(role);
            } else {
                $('#edit-role').val(role);
            }

            $editModal.removeClass('hidden').addClass('flex');
        });

        $('[data-close-modal="edit-user"]').on('click', function() {
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
                    alert(result.message || 'เกิดข้อผิดพลาดในการอัปเดตข้อมูลผู้ใช้');
                    return;
                }

                dt.ajax.reload(null, false);
                $editModal.addClass('hidden').removeClass('flex');
            } catch (err) {
                console.error(err);
                alert('ไม่สามารถเชื่อมต่อเซิร์ฟเวอร์ได้');
            }
        });

        // Delete user button
        const $deleteUserModal = $('#deleteUserModal');
        const $deleteUserEmailLabel = $('#delete-user-email-label');
        let deleteUserId = null;

        // Show the modal when clicking delete button
        $(document).on('click', '.btn-delete-user', function() {
            const id = $(this).data('id');
            const email = $(this).data('email');

            if (!id) {
                alert('ไม่พบรหัสผู้ใช้สำหรับลบ');
                return;
            }

            deleteUserId = id;
            $deleteUserEmailLabel.text(email || '');
            $deleteUserModal.removeClass('hidden').addClass('flex');
        });

        // Close the modal when cancel
        $('[data-close-modal="delete-user"]').on('click', function() {
            $deleteUserModal.addClass('hidden').removeClass('flex');
            deleteUserId = null;
            $deleteUserEmailLabel.text('');
        });

        // Click outside the modal to close the modal
        $deleteUserModal.on('click', function(e) {
            if (e.target === this) {
                $deleteUserModal.addClass('hidden').removeClass('flex');
                deleteUserId = null;
                $deleteUserEmailLabel.text('');
            }
        });

        // If the delete button in the modal was clicked
        $('#confirmDeleteUserBtn').on('click', async function() {
            if (!deleteUserId) {
                alert('ไม่พบรหัสผู้ใช้สำหรับลบ');
                return;
            }

            const formData = new FormData();
            formData.append('id', deleteUserId);

            try {
                const res = await fetch('./actions/delete_user.php', {
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
                    alert(result.message || 'เกิดข้อผิดพลาดในการลบผู้ใช้');
                    return;
                }

                // If successfully deleted then close the modal
                $deleteUserModal.addClass('hidden').removeClass('flex');
                deleteUserId = null;
                $deleteUserEmailLabel.text('');

                dt.ajax.reload(null, false);
            } catch (err) {
                console.error(err);
                alert('ไม่สามารถเชื่อมต่อเซิร์ฟเวอร์ได้');
            }
        });
    });
</script>