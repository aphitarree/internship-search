<?php
function renderPagination($currentPage, $totalPages, $baseUrl) {
    // Don't render if there's only one page
    if ($totalPages <= 1) {
        return;
    }
?>
    <section class="w-full flex justify-center items-center gap-4 mt-10">
        <!-- Previous Page -->
        <a href="<?= $baseUrl ?>&page=<?= max(1, $currentPage - 1) ?>" class="flex items-center gap-2 px-4 py-2 rounded-md <?= $currentPage <= 1 ? 'text-gray-400 bg-gray-100 cursor-not-allowed' : 'hover:bg-gray-100' ?>">
            <img src="./public/images/left-arrow.png" alt="left arrow" class="w-4 h-4" />
            <span>ก่อนหน้า</span>
        </a>

        <!-- Page Numbers -->
        <div class="flex items-center gap-2">
            <?php for ($i = 1; $i <= $totalPages; $i++) : ?>
                <a href="<?= $baseUrl ?>&page=<?= $i ?>" class="px-4 py-2 rounded-md <?= $i == $currentPage ? 'bg-blue-500 text-white' : 'hover:bg-gray-100' ?>">
                    <?= $i ?>
                </a>
            <?php endfor; ?>
        </div>

        <!-- Next Page -->
        <a href="<?= $baseUrl ?>&page=<?= min($totalPages, $currentPage + 1) ?>" class="flex items-center gap-2 px-4 py-2 rounded-md <?= $currentPage >= $totalPages ? 'text-gray-400 bg-gray-100 cursor-not-allowed' : 'hover:bg-gray-100' ?>">
            <span>ถัดไป</span>
            <img src="./public/images/right-arrow.png" alt="right arrow" class="w-4 h-4" />
        </a>
    </section>
<?php
}

// Render the pagination component
renderPagination($page, $totalPages, $baseUrl);
?>