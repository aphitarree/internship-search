<!doctype html>
<html lang="th">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1" />
    <title>คลังประวัติการฝึกงาน</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdn.datatables.net/2.3.4/css/dataTables.dataTables.min.css">
</head>

<body class="bg-slate-50 text-slate-800 antialiased">
    <section class="mx-auto max-w-7xl px-4 py-8">
        <h1 class="text-2xl font-bold mb-6">คลังประวัติการฝึกงาน</h1>
        <div class="bg-white rounded-2xl shadow-sm ring-1 ring-slate-200 overflow-x-auto">
            <table id="myTable" class="min-w-full divide-y divide-slate-200">
                <thead>
                    <tr>
                        <th class="px-4 py-3 text-left text-sm">บริษัท</th>
                        <th class="px-4 py-3 text-left text-sm">จังหวัด</th>
                        <th class="px-4 py-3 text-left text-sm">ตำแหน่ง</th>
                        <th class="px-4 py-3 text-left text-sm">ปีการศึกษา</th>
                        <th class="px-4 py-3 text-left text-sm">จำนวนที่รับ</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </section>

    <script src="https://code.jquery.com/jquery-3.7.1.js"></script>
    <script src="https://cdn.datatables.net/2.3.4/js/dataTables.min.js"></script>
    <script>
        const table = new DataTable('#myTable', {
            serverSide: true,
            processing: true,
            ajax: {
                url: './actions/fetch_internships.php',
                type: 'POST',
                error: (xhr) => {
                    console.error('DT Ajax error:', xhr.status, xhr.responseText);
                    alert('โหลดข้อมูลไม่สำเร็จ (' + xhr.status + ')');
                }
            },
            lengthMenu: [
                [10, 25, 50, 100, -1],
                [10, 25, 50, 100, 'ทั้งหมด'],
            ],
            pageLength: 10,
            columns: [{
                data: 'organization',
            }, {
                data: 'province',
            }, {
                data: 'position',
            }, {
                data: 'year',
            }, {
                data: 'total_student',
            }],
            order: [
                [3, 'desc'],
            ],
            // เริ่มเรียงตามปี
            language: {
                search: 'ค้นหา:',
                lengthMenu: 'แสดง _MENU_ แถว',
                info: 'แสดง _START_–_END_ จาก _TOTAL_ แถว',
                infoEmpty: 'ไม่มีข้อมูลให้แสดง',
                zeroRecords: 'ไม่พบรายการที่ค้นหา',
                paginate: {
                    first: 'หน้าแรก',
                    previous: 'ก่อนหน้า',
                    next: 'ถัดไป',
                    last: 'หน้าสุดท้าย'
                }
            }
        });
    </script>
</body>

</html>