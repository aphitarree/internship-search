<?php
require_once __DIR__ . '/vendor/autoload.php';
$conn = require __DIR__ . '/config/db_config.php';

$mpdf = new \Mpdf\Mpdf([
    'mode' => 'UTF-8',
    'format' => 'A4',
    'orientation' => 'L',
    'margin_left' => 10,
    'margin_right' => 10,
    'margin_bottom' => 16,
    'margin_header' => 9,
    'margin_footer' => 9,
]);

// ✅ Use PDO properly
$sql = "SELECT * FROM internship_stats";
$stmt = $conn->query($sql);
$result = $stmt->fetchAll(PDO::FETCH_ASSOC);

ob_start();
?>
<!DOCTYPE html>
<html lang="th">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <style>
        body,
        table,
        th,
        td,
        h2 {
            font-family: "garuda", sans-serif;
        }

        table {
            border-collapse: collapse;
            width: 100%;
            table-layout: fixed;
            /* ✅ บังคับให้ใช้ความกว้างที่กำหนด */
        }

        th,
        td {
            border: 1px solid #000;
            padding: 8px;
            text-align: center;
            word-wrap: break-word;
        }

        th {
            background-color: #f2f2f2;
            font-weight: bold;
        }

        th:nth-child(1),
        td:nth-child(1) {
            width: 70px;
        }

        th:nth-child(2),
        td:nth-child(2) {
            width: 150px;
        }

        th:nth-child(3),
        td:nth-child(3) {
            width: 150px;
        }

        th:nth-child(4),
        td:nth-child(4) {
            width: 150px;
        }

        th:nth-child(5) {
            width: 150px;
            text-align: center;
        }

        td:nth-child(5) {
            width: 150px;
            text-align: left;
        }

        th:nth-child(6),
        td:nth-child(6) {
            width: 100px;
        }

        th:nth-child(7),
        td:nth-child(7) {
            width: 200px;
        }

        th:nth-child(8),
        td:nth-child(8) {
            width: 100px;
        }

        th:nth-child(9),
        td:nth-child(9) {
            width: 100px;
        }


        h2 {
            text-align: center;
            margin-bottom: 20px;
        }
    </style>
</head>

<body>
    <h2>รายงานสถานประกอบการฝึกงาน</h2>
    <table>
        <thead>
            <tr>
                <th>ลำดับ</th>
                <th>คณะ</th>
                <th>สาขา</th>
                <th>หลักสูตร</th>
                <th>ชื่อบริษัท</th>
                <th>จังหวัด</th>
                <th>ตำแหน่ง</th>
                <th>ปีการศึกษา</th>
                <!-- <th>จำนวน<br />นักศึกษา</th> -->
                <th>จำนวน(คน)</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($result as $row) { ?>
                <tr>
                    <td><?= htmlspecialchars($row['id']) ?></td>
                    <td><?= htmlspecialchars($row['major_id']) ?></td>
                    <td><?= htmlspecialchars($row['organization']) ?></td>
                    <td><?= htmlspecialchars($row['organization']) ?></td>
                    <td><?= htmlspecialchars($row['organization']) ?></td>
                    <td><?= htmlspecialchars($row['province']) ?></td>
                    <td><?= htmlspecialchars($row['position']) ?></td>
                    <td><?= htmlspecialchars($row['year']) ?></td>
                    <td><?= htmlspecialchars($row['total_student']) ?></td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
</body>

</html>

<?php
$html = ob_get_clean();
$mpdf->WriteHTML($html);
$mpdf->Output('internship_report.pdf', 'I');
