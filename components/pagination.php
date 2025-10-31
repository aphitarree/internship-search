<?php
function render_pagination($current_page, $total_pages, $base_url)
{
  // Don't render if there's only one page
  if ($total_pages <= 1) {
    return;
  }
?>
  <section class="w-full flex justify-center items-center gap-4 mt-10">
    <!-- Previous Page -->
    <a href="<?= $base_url ?>&page=<?= max(1, $current_page - 1) ?>" class="flex items-center gap-2 px-4 py-2 rounded-md <?= $current_page <= 1 ? 'text-gray-400 bg-gray-100 cursor-not-allowed' : 'hover:bg-gray-100' ?>">
      <img src="./public/images/left-arrow.png" alt="left arrow" class="w-4 h-4" />
      <span>ก่อนหน้า</span>
    </a>

    <!-- Page Numbers -->
    <div class="flex items-center gap-2">
      <?php for ($i = 1; $i <= $total_pages; $i++) : ?>
        <a href="<?= $base_url ?>&page=<?= $i ?>" class="px-4 py-2 rounded-md <?= $i == $current_page ? 'bg-blue-500 text-white' : 'hover:bg-gray-100' ?>">
          <?= $i ?>
        </a>
      <?php endfor; ?>
    </div>

    <!-- Next Page -->
    <a href="<?= $base_url ?>&page=<?= min($total_pages, $current_page + 1) ?>" class="flex items-center gap-2 px-4 py-2 rounded-md <?= $current_page >= $total_pages ? 'text-gray-400 bg-gray-100 cursor-not-allowed' : 'hover:bg-gray-100' ?>">
      <span>ถัดไป</span>
      <img src="./public/images/right-arrow.png" alt="right arrow" class="w-4 h-4" />
    </a>
  </section>
<?php
}

// Render the pagination component
render_pagination($page, $total_pages, $base_url);
?>