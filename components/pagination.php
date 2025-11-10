<?php
function renderPagination($currentPage, $totalPages, $baseUrl, $totalRecords) {
    if ($totalPages <= 1) {
        return;
    }
?>

    <?php if ($currentPage == $totalPages): ?>
        <p class="text-center mt-7">
            รายการที่ <?= ($currentPage - 1) * 10 + 1 ?> ถึง <?= $totalRecords ?> จากทั้งหมด <?= $totalRecords ?> รายการ
        </p>
    <?php else: ?>
        <p class="text-center mt-7">
            รายการที่ <?= ($currentPage - 1) * 10 + 1 ?> ถึง <?= $currentPage * 10 ?> จากทั้งหมด <?= $totalRecords ?> รายการ
        </p>
    <?php endif; ?>

    <!-- ✅ ใช้ nav + aria-label เพื่อให้สกรีนรีดเดอร์รู้ว่าเป็นการแบ่งหน้า -->
    <nav aria-label="การแบ่งหน้า">
        <section class="w-full flex justify-center items-center gap-4 mt-3">
            <div class="flex items-center gap-2">
                <!-- ปุ่มหน้าแรก -->
                <a
                    href="<?= $baseUrl ?>&page=1"
                    aria-label="ไปหน้าแรก"
                    class="px-4 py-2 rounded-md <?= 1 == $currentPage ? 'bg-blue-500 text-white pointer-events-none' : 'hover:bg-gray-100' ?>">
                    <?= '&laquo;&laquo;' ?>
                </a>

                <!-- ปุ่มย้อนกลับ -->
                <a
                    href="<?= $baseUrl ?>&page=<?= $currentPage - 1 ?>"
                    aria-label="ย้อนกลับ"
                    class="px-4 py-2 rounded-md <?= 1 == $currentPage ? 'bg-blue-500 text-white pointer-events-none' : 'hover:bg-gray-100' ?>">
                    <?= '&laquo;' ?>
                </a>

                <?php
                // ฟังก์ชันสร้างลิงก์หน้า พร้อม aria
                $renderPageLink = function ($i, $currentPage, $baseUrl) {
                    $isCurrent = $i == $currentPage;
                    $aria = $isCurrent ? 'aria-current="page"' : 'aria-label="ไปหน้า ' . $i . '"';
                    $classes = 'px-4 py-2 rounded-md ' . ($isCurrent ? 'bg-blue-500 text-white pointer-events-none' : 'hover:bg-gray-100');
                    echo '<a href="' . $baseUrl . '&page=' . $i . '" ' . $aria . ' class="' . $classes . '">' . $i . '</a>';
                };
                ?>

                <?php if ($totalPages <= 5): ?>
                    <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                        <?php $renderPageLink($i, $currentPage, $baseUrl); ?>
                    <?php endfor; ?>
                <?php else: ?>
                    <?php if ($currentPage <= 2): ?>
                        <?php for ($i = 1; $i <= 5; $i++): ?>
                            <?php $renderPageLink($i, $currentPage, $baseUrl); ?>
                        <?php endfor; ?>
                    <?php elseif ($currentPage >= 3 && $currentPage < $totalPages - 2): ?>
                        <?php for ($i = $currentPage - 2; $i <= $currentPage + 2; $i++): ?>
                            <?php $renderPageLink($i, $currentPage, $baseUrl); ?>
                        <?php endfor; ?>
                    <?php else: ?>
                        <?php for ($i = $totalPages - 4; $i <= $totalPages; $i++): ?>
                            <?php $renderPageLink($i, $currentPage, $baseUrl); ?>
                        <?php endfor; ?>
                    <?php endif; ?>
                <?php endif; ?>

                <!-- ปุ่มถัดไป -->
                <a
                    href="<?= $baseUrl ?>&page=<?= $currentPage + 1 ?>"
                    aria-label="ไปหน้าถัดไป"
                    class="px-4 py-2 rounded-md <?= $currentPage == $totalPages ? 'bg-blue-500 text-white pointer-events-none' : 'hover:bg-gray-100' ?>">
                    <?= '&raquo;' ?>
                </a>

                <!-- ปุ่มหน้าสุดท้าย -->
                <a
                    href="<?= $baseUrl ?>&page=<?= $totalPages ?>"
                    aria-label="ไปหน้าสุดท้าย"
                    class="px-4 py-2 rounded-md <?= $currentPage == $totalPages ? 'bg-blue-500 text-white pointer-events-none' : 'hover:bg-gray-100' ?>">
                    <?= '&raquo;&raquo;' ?>
                </a>
            </div>
        </section>
    </nav>

<?php
}

renderPagination($page, $totalPages, $baseUrl, $totalRecords);
?>