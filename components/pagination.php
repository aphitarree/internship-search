<?php
function renderPagination($currentPage, $totalPages, $baseUrl) {
    // Don't render if there's only one page
    if ($totalPages <= 1) {
        return;
    }
?>
    <section class="w-full flex justify-center items-center gap-4 mt-10">


        <!-- Page Numbers -->
        <div class="flex items-center gap-2">
            <!-- First Page -->
            <a
                href="<?= $baseUrl ?>&page=1"
                class="px-4 py-2 rounded-md <?= 1 == $currentPage ? 'bg-blue-500 text-white pointer-events-none' : 'hover:bg-gray-100' ?>">
                <?= '<<' ?>
            </a>

            <?php if ($currentPage <= 2): ?>
                <a href="<?= $baseUrl ?>&page=<?= 1 ?>" class="px-4 py-2 rounded-md <?= $currentPage == 1 ? 'bg-blue-500 text-white pointer-events-none' : 'hover:bg-gray-100' ?>">
                    <?= 1 ?>
                </a>
                <?php for ($i = 2; $i <= 5; $i++) : ?>
                    <a href="<?= $baseUrl ?>&page=<?= $i ?>" class="px-4 py-2 rounded-md <?= $i == $currentPage ? 'bg-blue-500 text-white pointer-events-none' : 'hover:bg-gray-100' ?>">
                        <?= $i ?>
                    </a>
                <?php endfor; ?>
            <?php endif; ?>

            <?php if ($currentPage >= 3 && $currentPage < $totalPages - 2): ?>
                <?php for ($i = $currentPage - 2; $i <= $currentPage - 1; $i++) : ?>
                    <a href="<?= $baseUrl ?>&page=<?= $i ?>" class="px-4 py-2 rounded-md <?= $i == $currentPage ? 'bg-blue-500 text-white pointer-events-none' : 'hover:bg-gray-100' ?>">
                        <?= $i ?>
                    </a>
                <?php endfor; ?>
                <?php for ($i = $currentPage; $i <= $currentPage + 2; $i++) : ?>
                    <a href="<?= $baseUrl ?>&page=<?= $i ?>" class="px-4 py-2 rounded-md <?= $i == $currentPage ? 'bg-blue-500 text-white pointer-events-none' : 'hover:bg-gray-100' ?>">
                        <?= $i ?>
                    </a>
                <?php endfor; ?>
            <?php endif; ?>

            <?php if ($currentPage >= $totalPages - 2): ?>
                <?php for ($i = $totalPages - 4; $i <= $totalPages; $i++) : ?>
                    <a href="<?= $baseUrl ?>&page=<?= $i ?>" class="px-4 py-2 rounded-md <?= $i == $currentPage ? 'bg-blue-500 text-white pointer-events-none' : 'hover:bg-gray-100' ?>">
                        <?= $i ?>
                    </a>
                <?php endfor; ?>
            <?php endif; ?>
            <!-- Last Page -->
            <a href="<?= $baseUrl ?>&page=<?= $totalPages ?>" class="px-4 py-2 rounded-md <?= $currentPage == $totalPages ? 'bg-blue-500 text-white pointer-events-none' : 'hover:bg-gray-100' ?>">
                <?= '>>' ?>
            </a>
        </div>
    </section>
<?php
}

// Render the pagination component
renderPagination($page, $totalPages, $baseUrl);
?>