<section class="mx-auto max-w-[1708px] px-4 mt-8 text-right">
    <form action="actions/report_excel.php" method="GET" target="_blank">
        <!-- ส่งค่าตัวกรอง (filter) ปัจจุบันไปด้วย -->
        <input type="hidden" name="faculty" value="<?= htmlspecialchars($_GET['faculty'] ?? '') ?>">
        <input type="hidden" name="program" value="<?= htmlspecialchars($_GET['program'] ?? '') ?>">
        <input type="hidden" name="major" value="<?= htmlspecialchars($_GET['major'] ?? '') ?>">
        <input type="hidden" name="province" value="<?= htmlspecialchars($_GET['province'] ?? '') ?>">
        <input type="hidden" name="academic-year" value="<?= htmlspecialchars($_GET['academic-year'] ?? '') ?>">

        <button type="submit"
            class="bg-green-500 hover:bg-green-600 text-white text-lg px-6 py-2 rounded-md shadow-md transition">
            📊 ดาวน์โหลด Excel (CSV)
        </button>
    </form>
</section>