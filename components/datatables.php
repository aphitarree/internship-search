<section class="text-slate-800 antialiased mb-6">
    <style>
        table,
        thead,
        tr,
        th,
        td {
            border-width: 1px;
            text-align: center;
        }

        td .dt-type-numeric {
            text-align: center !important;
        }

        .dt-column-title {
            text-align: center;
        }

        .dt-search {
            margin-right: 0.75rem;
        }

        .dt-length {
            margin-left: 0.75rem;
        }

        .dt-info {
            margin-left: 0.75rem;
        }

        .dt-paging-button {
            margin-right: 0.5rem;
        }
    </style>

    <section class="mx-auto max-w-[1708px] px-4 mt-4">
        <div class="w-full overflow-x-auto rounded-sm border border-gray-300 shadow-sm bg-white">
            <table id="myTable" class="min-w-[1000px] w-full">
                <thead class="bg-gray-200">
                    <tr class="text-center text-[18px] font-semibold">
                        <th class="w-[65px] p-2">NO.</th>
                        <th class="w-[350px] p-2">บริษัท</th>
                        <th class="w-[200px] p-2">จังหวัด</th>
                        <th class="w-[180px] p-2">คณะ</th>
                        <th class="w-[180px] p-2">หลักสูตร</th>
                        <th class="w-[180px] p-2">สาขา</th>
                        <th class="w-[120px] p-2">ปีการศึกษา</th>
                        <th class="w-[130px] p-2">จำนวนที่รับ</th>
                        <th class="w-[130px] p-2">MOU</th>
                        <th class="w-[180px] p-2">ข้อมูลการติดต่อ</th>
                        <th class="w-[65px] p-2">คะแนน</th>
                        <th class="w-[65px] p-2">สังกัด</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </section>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const table = new DataTable('#myTable', {
                serverSide: true,
                processing: true,
                ajax: {
                    url: './actions/fetch_internships.php',
                    type: 'POST',
                    data: (data) => {
                        // Send the filter value that is get from the drop down to the fetch_internships.php
                        data.faculty = document.getElementById('faculty')?.value || '';
                        data.program = document.getElementById('program')?.value || '';
                        data.major = document.getElementById('major')?.value || '';
                        data.province = document.getElementById('province')?.value || '';
                        data['academic-year'] = document.getElementById('academic-year')?.value || '';
                    },
                    error: (xhr) => {
                        console.error('DT Ajax error:', xhr.status, xhr.responseText);
                        alert(`โหลดข้อมูลไม่สำเร็จ (${xhr.status})`);
                    },
                    dataSrc: (json) => json?.data ?? [],
                },
                lengthMenu: [
                    [10, 25, 50, 100, -1],
                    [10, 25, 50, 100, 'ทั้งหมด'],
                ],
                pageLength: 10,
                columns: [{
                        data: null,
                        className: 'text-center',
                        orderable: false,
                        searchable: false,
                        render: (data, type, row, meta) => meta.row + 1 + meta.settings._iDisplayStart,
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
                    },
                    {
                        data: 'affiliation',
                        className: '!text-center p-2'
                    },
                ],
                // order: [
                //     [6, 'desc'],
                //     [1, 'asc'],
                // ],
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
                        last: 'หน้าสุดท้าย',
                    },
                },
            });

            // When click search from the form (dropdown)
            const filterForm = document.getElementById('filter-form');
            if (filterForm) {
                filterForm.addEventListener('submit', (event) => {
                    // event.preventDefault();

                    const formData = new FormData(filterForm);
                    const params = new URLSearchParams(formData);

                    // Update the URL
                    const newUrl = `${window.location.pathname}?${params.toString()}`;
                    window.history.replaceState({}, '', newUrl);

                    // Fetch the AJAX from the dropdown values
                    if (typeof table !== 'undefined') {
                        table.ajax.reload();
                    }
                });
            }

            // Instantly change the value if the dropdown was selected
            // ['faculty', 'major', 'program', 'province', 'academic-year'].forEach((id) => {
            //     const element = document.getElementById(id);
            //     if (!element) return;
            //     element.addEventListener('change', () => {
            //         table.ajax.reload();
            //     });
            // });
        });
    </script>
</section>