<?php
function renderPagination($currentPage, $totalPages, $baseUrl, $totalRecords) {
    // Don't render if there's only one page
    if ($totalPages <= 1) {
        return;
    }
?>

    <?php if ($currentPage == $totalPages): ?>
        <p
            class="text-center mt-7">รายการที่ <?= ($currentPage - 1) * 10 + 1 ?> ถึง <?= $totalRecords ?> จากทั้งหมด <?= $totalRecords ?> รายการ
        </p>
    <?php else: ?>
        <p
            class="text-center mt-7">รายการที่ <?= ($currentPage - 1) * 10 + 1 ?> ถึง <?= $currentPage * 10 ?> จากทั้งหมด <?= $totalRecords ?> รายการ
        </p>
    <?php endif; ?>

    <section class="w-full flex justify-center items-center gap-4 mt-3">
        <!-- Page Numbers -->

        <div class="flex items-center gap-2">
            <!-- First Page -->
            <a
                href="<?= $baseUrl ?>&page=1"
                class="px-4 py-2 rounded-md <?= 1 == $currentPage ? 'bg-blue-500 text-white pointer-events-none' : 'hover:bg-gray-100' ?>">
                <?= '<<' ?>
            </a>

            <?php if ($totalPages <= 5): ?>
                <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                    <a href="<?= $baseUrl ?>&page=<?= $i ?>" class="px-4 py-2 rounded-md <?= $i == $currentPage ? 'bg-blue-500 text-white pointer-events-none' : 'hover:bg-gray-100' ?>">
                        <?= $i ?>
                    </a>
                <?php endfor; ?>
            <?php else: ?>
                <!-- If page is the first 2 pages -->
                <?php if ($currentPage <= 2): ?>
                    <?php for ($i = 1; $i <= 5; $i++): ?>
                        <a href="<?= $baseUrl ?>&page=<?= $i ?>" class="px-4 py-2 rounded-md <?= $i == $currentPage ? 'bg-blue-500 text-white pointer-events-none' : 'hover:bg-gray-100' ?>">
                            <?= $i ?>
                        </a>
                    <?php endfor; ?>
                <?php endif; ?>

                <!-- If page is between the page 3 to the third-to-last page -->
                <?php if ($currentPage >= 3 && $currentPage < $totalPages - 2): ?>
                    <?php for ($i = $currentPage - 2; $i <= $currentPage - 1; $i++): ?>
                        <a href="<?= $baseUrl ?>&page=<?= $i ?>" class="px-4 py-2 rounded-md <?= $i == $currentPage ? 'bg-blue-500 text-white pointer-events-none' : 'hover:bg-gray-100' ?>">
                            <?= $i ?>
                        </a>
                    <?php endfor; ?>
                    <?php for ($i = $currentPage; $i <= $currentPage + 2; $i++): ?>
                        <a href="<?= $baseUrl ?>&page=<?= $i ?>" class="px-4 py-2 rounded-md <?= $i == $currentPage ? 'bg-blue-500 text-white pointer-events-none' : 'hover:bg-gray-100' ?>">
                            <?= $i ?>
                        </a>
                    <?php endfor; ?>
                <?php endif; ?>

                <!-- If page is the last 2 pages -->
                <?php if ($currentPage >= $totalPages - 2): ?>
                    <?php for ($i = $totalPages - 4; $i <= $totalPages; $i++): ?>
                        <a href="<?= $baseUrl ?>&page=<?= $i ?>" class="px-4 py-2 rounded-md <?= $i == $currentPage ? 'bg-blue-500 text-white pointer-events-none' : 'hover:bg-gray-100' ?>">
                            <?= $i ?>
                        </a>
                    <?php endfor; ?>
                <?php endif; ?>
            <?php endif; ?>

            <!-- Last Page -->
            <a
                href="<?= $baseUrl ?>&page=<?= $totalPages ?>" class="px-4 py-2 rounded-md <?= $currentPage == $totalPages ? 'bg-blue-500 text-white pointer-events-none' : 'hover:bg-gray-100' ?>">
                <?= '>>' ?>
            </a>
        </div>
    </section>
<?php
}

renderPagination($page, $totalPages, $baseUrl, $totalRecords);
?>