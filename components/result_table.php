<?php
?>

<!-- Data table -->
<section class="mx-auto max-w-[1708px] px-4 mt-10">
    <script type="module" defer>
        const table = document.querySelector('section table');
        const headerCheckbox = table.querySelector('thead input[type="checkbox"]');
        const rowCheckboxes = Array.from(table.querySelectorAll('tbody input[type="checkbox"]'));

        const updateHeaderState = () => {
            const total = rowCheckboxes.length;
            const checked = rowCheckboxes.filter(cb => cb.checked).length;

            headerCheckbox.indeterminate = checked > 0 && checked < total;
            headerCheckbox.checked = checked === total;
        }

        headerCheckbox.addEventListener('change', () => {
            rowCheckboxes.forEach(cb => cb.checked = headerCheckbox.checked);
            headerCheckbox.indeterminate = false;
        });

        rowCheckboxes.forEach(cb => {
            cb.addEventListener('change', updateHeaderState);
        });

        updateHeaderState();
    </script>

    <div class="w-full overflow-x-auto rounded-sm border">
        <table class="min-w-[1000px] w-full">
            <thead class="bg-gray-200">
                <tr class="text-center text-[18px] font-semibold">
                    <th class="w-[52px] p-2">
                        <input type="checkbox" class="h-4 w-4" />
                    </th>
                    <th class="w-[65px] p-2">NO.</th>
                    <th class="w-[350px] p-2">บริษัท</th>
                    <th class="w-[200px] p-2">จังหวัด</th>
                    <th class="w-[180px] p-2">ตำแหน่ง</th>
                    <th class="w-[180px] p-2">คณะ</th>
                    <th class="w-[180px] p-2">หลักสูตร</th>
                    <th class="w-[180px] p-2">สาขา</th>
                    <th class="w-[120px] p-2">ปีการศึกษา</th>
                    <th class="w-[130px] p-2">จำนวนที่รับ</th>
                </tr>
            </thead>
            <tbody class="">
                <!-- Row 1 -->
                <tr class="border-t">
                    <td class="p-2 text-center">
                        <input type="checkbox" class="h-4 w-4" />
                    </td>
                    <td class="p-2 text-center">1</td>
                    <td class="p-2 text-center">บริษัท 1</td>
                    <td class="p-2 text-center">กรุงเทพมหานคร</td>
                    <td class="p-2 text-center">ตำแหน่ง</td>
                    <td class="p-2 text-center">นิเทศศาสตร์</td>
                    <td class="p-2 text-center">นิเทศศาสตรบัณฑิต</td>
                    <td class="p-2 text-center">วารสารสนเทศ</td>
                    <td class="p-2 text-center">2568</td>
                    <td class="p-2 text-center">5</td>
                </tr>
                <!-- Row 2 -->
                <tr class="border-t">
                    <td class="p-2 text-center">
                        <input type="checkbox" class="h-4 w-4" />
                    </td>
                    <td class="p-2 text-center">2</td>
                    <td class="p-2 text-center">บริษัท 1</td>
                    <td class="p-2 text-center">กรุงเทพมหานคร</td>
                    <td class="p-2 text-center">ตำแหน่ง</td>
                    <td class="p-2 text-center">นิเทศศาสตร์</td>
                    <td class="p-2 text-center">นิเทศศาสตรบัณฑิต</td>
                    <td class="p-2 text-center">วารสารสนเทศ</td>
                    <td class="p-2 text-center">2568</td>
                    <td class="p-2 text-center">5</td>
                </tr>
                <!-- Row 3 -->
                <tr class="border-t">
                    <td class="p-2 text-center">
                        <input type="checkbox" class="h-4 w-4" />
                    </td>
                    <td class="p-2 text-center">3</td>
                    <td class="p-2 text-center">บริษัท 1</td>
                    <td class="p-2 text-center">กรุงเทพมหานคร</td>
                    <td class="p-2 text-center">ตำแหน่ง</td>
                    <td class="p-2 text-center">นิเทศศาสตร์</td>
                    <td class="p-2 text-center">นิเทศศาสตรบัณฑิต</td>
                    <td class="p-2 text-center">วารสารสนเทศ</td>
                    <td class="p-2 text-center">2568</td>
                    <td class="p-2 text-center">5</td>
                </tr>
                <!-- Row 4 -->
                <tr class="border-t">
                    <td class="p-2 text-center">
                        <input type="checkbox" class="h-4 w-4" />
                    </td>
                    <td class="p-2 text-center">4</td>
                    <td class="p-2 text-center">บริษัท 1</td>
                    <td class="p-2 text-center">กรุงเทพมหานคร</td>
                    <td class="p-2 text-center">ตำแหน่ง</td>
                    <td class="p-2 text-center">นิเทศศาสตร์</td>
                    <td class="p-2 text-center">นิเทศศาสตรบัณฑิต</td>
                    <td class="p-2 text-center">วารสารสนเทศ</td>
                    <td class="p-2 text-center">2568</td>
                    <td class="p-2 text-center">5</td>
                </tr>
                <!-- Row 5 -->
                <tr class="border-t">
                    <td class="p-2 text-center">
                        <input type="checkbox" class="h-4 w-4" />
                    </td>
                    <td class="p-2 text-center">4</td>
                    <td class="p-2 text-center">บริษัท 1</td>
                    <td class="p-2 text-center">กรุงเทพมหานคร</td>
                    <td class="p-2 text-center">ตำแหน่ง</td>
                    <td class="p-2 text-center">นิเทศศาสตร์</td>
                    <td class="p-2 text-center">นิเทศศาสตรบัณฑิต</td>
                    <td class="p-2 text-center">วารสารสนเทศ</td>
                    <td class="p-2 text-center">2568</td>
                    <td class="p-2 text-center">5</td>
                </tr>
                <!-- Row 6 -->
                <tr class="border-t">
                    <td class="p-2 text-center">
                        <input type="checkbox" class="h-4 w-4" />
                    </td>
                    <td class="p-2 text-center">4</td>
                    <td class="p-2 text-center">บริษัท 1</td>
                    <td class="p-2 text-center">กรุงเทพมหานคร</td>
                    <td class="p-2 text-center">ตำแหน่ง</td>
                    <td class="p-2 text-center">นิเทศศาสตร์</td>
                    <td class="p-2 text-center">นิเทศศาสตรบัณฑิต</td>
                    <td class="p-2 text-center">วารสารสนเทศ</td>
                    <td class="p-2 text-center">2568</td>
                    <td class="p-2 text-center">5</td>
                </tr>
                <!-- Row 7 -->
                <tr class="border-t">
                    <td class="p-2 text-center">
                        <input type="checkbox" class="h-4 w-4" />
                    </td>
                    <td class="p-2 text-center">4</td>
                    <td class="p-2 text-center">บริษัท 1</td>
                    <td class="p-2 text-center">กรุงเทพมหานคร</td>
                    <td class="p-2 text-center">ตำแหน่ง</td>
                    <td class="p-2 text-center">นิเทศศาสตร์</td>
                    <td class="p-2 text-center">นิเทศศาสตรบัณฑิต</td>
                    <td class="p-2 text-center">วารสารสนเทศ</td>
                    <td class="p-2 text-center">2568</td>
                    <td class="p-2 text-center">5</td>
                </tr>
                <!-- Row 8 -->
                <tr class="border-t">
                    <td class="p-2 text-center">
                        <input type="checkbox" class="h-4 w-4" />
                    </td>
                    <td class="p-2 text-center">4</td>
                    <td class="p-2 text-center">บริษัท 1</td>
                    <td class="p-2 text-center">กรุงเทพมหานคร</td>
                    <td class="p-2 text-center">ตำแหน่ง</td>
                    <td class="p-2 text-center">นิเทศศาสตร์</td>
                    <td class="p-2 text-center">นิเทศศาสตรบัณฑิต</td>
                    <td class="p-2 text-center">วารสารสนเทศ</td>
                    <td class="p-2 text-center">2568</td>
                    <td class="p-2 text-center">5</td>
                </tr>
                <!-- Row 9 -->
                <tr class="border-t">
                    <td class="p-2 text-center">
                        <input type="checkbox" class="h-4 w-4" />
                    </td>
                    <td class="p-2 text-center">4</td>
                    <td class="p-2 text-center">บริษัท 1</td>
                    <td class="p-2 text-center">กรุงเทพมหานคร</td>
                    <td class="p-2 text-center">ตำแหน่ง</td>
                    <td class="p-2 text-center">นิเทศศาสตร์</td>
                    <td class="p-2 text-center">นิเทศศาสตรบัณฑิต</td>
                    <td class="p-2 text-center">วารสารสนเทศ</td>
                    <td class="p-2 text-center">2568</td>
                    <td class="p-2 text-center">5</td>
                </tr>
                <!-- Row 10 -->
                <tr class="border-t">
                    <td class="p-2 text-center">
                        <input type="checkbox" class="h-4 w-4" />
                    </td>
                    <td class="p-2 text-center">4</td>
                    <td class="p-2 text-center">บริษัท 1</td>
                    <td class="p-2 text-center">กรุงเทพมหานคร</td>
                    <td class="p-2 text-center">ตำแหน่ง</td>
                    <td class="p-2 text-center">นิเทศศาสตร์</td>
                    <td class="p-2 text-center">นิเทศศาสตรบัณฑิต</td>
                    <td class="p-2 text-center">วารสารสนเทศ</td>
                    <td class="p-2 text-center">2568</td>
                    <td class="p-2 text-center">5</td>
                </tr>
            </tbody>
        </table>
    </div>
</section>