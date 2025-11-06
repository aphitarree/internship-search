<?php
require_once __DIR__ . '/../vendor/autoload.php';
include_once __DIR__ . '/../index.php';

use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(dirname(__DIR__));
$dotenv->load();

$baseUrl = $_ENV['BASE_URL'];

$isSearchIsEmpty = false;
if (
    empty($faculty) &&
    empty($program) &&
    empty($major) &&
    empty($province) &&
    empty($academicYear)
) {
    $isSearchIsEmpty = true;
}

?>

<!-- Download report buttons -->
<section class="mx-auto max-w-[500px] px-4 mt-8 mb-16">
    <div class="flex items-center justify-center gap-6">
        <?php if (!$isSearchIsEmpty): ?>
            <form action="<?php echo $baseUrl; ?>/actions/report_filter.php" method="POST">
                <input type="hidden" name="faculty" value="<?= htmlspecialchars($faculty) ?>">
                <input type="hidden" name="program" value="<?= htmlspecialchars($program) ?>">
                <input type="hidden" name="major" value="<?= htmlspecialchars($major) ?>">
                <input type="hidden" name="province" value="<?= htmlspecialchars($province) ?>">
                <input type="hidden" name="academic-year" value="<?= htmlspecialchars($academicYear) ?>">
                <button
                    class="flex-1 h-11 rounded-md bg-slate-200 hover:bg-slate-300 px-4"
                    type="submit">
                    ดาวน์โหลดรายการที่เลือก (.pdf)
                </button>
            </form>
        <?php endif; ?>

        <button class="flex h-11 rounded-md bg-sky-500 text-white hover:bg-sky-600 px-4 text-center justify-center items-center">
            <a href="<?php echo $baseUrl; ?>/actions/report_all.php">ดาวน์โหลดทั้งหมด (.pdf)</a>
        </button>
    </div>
</section>