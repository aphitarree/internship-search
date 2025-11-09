    <form action="actions/report_excel.php" method="GET" target="_blank">
        <!-- ส่งค่าตัวกรอง (filter) ปัจจุบันไปด้วย -->
        <input type="hidden" name="faculty" value="<?= htmlspecialchars($_GET['faculty'] ?? '') ?>">
        <input type="hidden" name="program" value="<?= htmlspecialchars($_GET['program'] ?? '') ?>">
        <input type="hidden" name="major" value="<?= htmlspecialchars($_GET['major'] ?? '') ?>">
        <input type="hidden" name="province" value="<?= htmlspecialchars($_GET['province'] ?? '') ?>">
        <input type="hidden" name="academic-year" value="<?= htmlspecialchars($_GET['academic-year'] ?? '') ?>">

        <button type="submit"
            class="bg-green-500 flex h-11 rounded-md text-white hover:bg-sky-600 px-4 text-center justify-center items-center">
            ดาวน์โหลด Excel (.csv)
        </button>
    </form>