<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once 'db_config.php';
require_once 'tracker.php'; // Optional: to track visits to this page

// Fetch all organizations
try {
    $stmt = $pdo->query("SELECT id, faculty, program, major, organization_name, province, position_name FROM organizations ORDER BY organization_name");
    $organizations = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Fetch all unique years from internship_stats
    $stmtYears = $pdo->query("SELECT DISTINCT year_be FROM internship_stats ORDER BY year_be DESC");
    $years = $stmtYears->fetchAll(PDO::FETCH_COLUMN);

    // Fetch all internship stats and organize them by organization_id and year_be
    $stmtStats = $pdo->query("SELECT organization_id, year_be, student_count FROM internship_stats");
    $internshipStats = [];
    while ($row = $stmtStats->fetch(PDO::FETCH_ASSOC)) {
        $internshipStats[$row['organization_id']][$row['year_be']] = $row['student_count'];
    }

} catch (PDOException $e) {
    die("Error fetching data: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ข้อมูลหน่วยงานฝึกประสบการณ์</title>
    <style>
        body { font-family: sans-serif; line-height: 1.6; padding: 20px; }
        .container { max-width: 1200px; margin: auto; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        .no-data { text-align: center; color: #888; margin-top: 50px; }
    </style>
</head>
<body>

<div class="container">
    <h2>ข้อมูลหน่วยงานที่รับฝึกประสบการณ์</h2>

    <?php if (empty($organizations)): ?>
        <p class="no-data">ยังไม่มีข้อมูลในระบบ กรุณานำเข้าข้อมูลจาก Excel ก่อน</p>
    <?php else: ?>
        <table>
            <thead>
                <tr>
                    <th>คณะ/โรงเรียน</th>
                    <th>หลักสูตร</th>
                    <th>สาขาวิชา</th>
                    <th>ชื่อหน่วยงานที่รับฝึกประสบการณ์</th>
                    <th>จังหวัด</th>
                    <th>ตำแหน่งที่รับฝึกงาน</th>
                    <?php foreach ($years as $year): ?>
                        <th>จำนวนนักศึกษา ปี <?= $year ?></th>
                    <?php endforeach; ?>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($organizations as $org): ?>
                    <tr>
                        <td><?= htmlspecialchars($org['faculty']) ?></td>
                        <td><?= htmlspecialchars($org['program']) ?></td>
                        <td><?= htmlspecialchars($org['major']) ?></td>
                        <td><?= htmlspecialchars($org['organization_name']) ?></td>
                        <td><?= htmlspecialchars($org['province']) ?></td>
                        <td><?= htmlspecialchars($org['position_name']) ?></td>
                        <?php foreach ($years as $year): ?>
                            <td>
                                <?=
                                    isset($internshipStats[$org['id']][$year]) 
                                    ? htmlspecialchars($internshipStats[$org['id']][$year]) 
                                    : '-'
                                ?>
                            </td>
                        <?php endforeach; ?>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</div>

</body>
</html>
