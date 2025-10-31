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

    <style>
        table,
        thead,
        tr,
        th,
        td {
            border-width: 1px;
        }
    </style>

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
                <?php if (empty($results)) : ?>
                    <tr>
                        <td colspan="10" class="p-4 text-center text-gray-500">ไม่พบข้อมูล</td>
                    </tr>
                <?php else : ?>
                    <?php foreach ($results as $index => $row) : ?>
                        <tr class="border-t">
                            <td class="p-2 text-center">
                                <input type="checkbox" class="h-4 w-4" />
                            </td>
                            <td class="p-2 text-center"><?= $offset + $index + 1 ?></td>
                            <td class="p-2 text-left"><?= htmlspecialchars($row['company_name']) ?></td>
                            <td class="p-2 text-left"><?= htmlspecialchars($row['province']) ?></td>
                            <td class="p-2 text-left"><?= htmlspecialchars($row['job_title']) ?></td>
                            <td class="p-2 text-left"><?= htmlspecialchars($row['faculty_name']) ?></td>
                            <td class="p-2 text-left"><?= htmlspecialchars($row['program_name']) ?></td>
                            <td class="p-2 text-left"><?= htmlspecialchars($row['major_name']) ?></td>
                            <td class="p-2 text-center"><?= htmlspecialchars($row['academic_year']) ?></td>
                            <td class="p-2 text-center"><?= htmlspecialchars($row['internship_count']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</section>