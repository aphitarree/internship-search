<?php
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../config/db_config.php';

use Mpdf\Config\ConfigVariables;
use Mpdf\Config\FontVariables;

$defaultConfig = (new ConfigVariables())->getDefaults();
$fontDirs = $defaultConfig['fontDir'];

$defaultFontConfig = (new FontVariables())->getDefaults();
$fontData = $defaultFontConfig['fontdata'];

$mpdf = new \Mpdf\Mpdf([
    'mode' => 'UTF-8',
    'format' => 'A4',
    'orientation' => 'L',
    'margin_left' => 10,
    'margin_right' => 10,
    'margin_bottom' => 16,
    'margin_header' => 9,
    'margin_footer' => 9,

    'fontDir' => array_merge($fontDirs, [dirname(__DIR__) . '/public/assets/fonts']),
    'fontdata' => $fontData + [
        'sarabun' => [
            'R'  => 'THSarabunNew.ttf',
            'B'  => 'THSarabunNew Bold.ttf',
            'I'  => 'THSarabunNew Italic.ttf',
            'BI' => 'THSarabunNew BoldItalic.ttf',
        ],
    ],
    'default_font' => 'sarabun',
    'autoScriptToLang' => true,
    'autoLangToFont'   => true,

]);

$sql = "
    SELECT faculty_program_major.faculty, faculty_program_major.program, faculty_program_major.major, internship_stats.
    organization, internship_stats.province, internship_stats.contact, internship_stats.score, internship_stats.year, internship_stats.total_student
    FROM internship_stats INNER JOIN faculty_program_major ON internship_stats.major_id = faculty_program_major.id
    ORDER BY internship_stats.year DESC
";
$stmt = $conn->query($sql);
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

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
        h1 {
            font-family: "sarabun", sans-serif;
        }

        h1 {
            text-align: center;
            margin-bottom: 10px;
        }

        table {
            border-collapse: collapse;
            width: 100%;
            table-layout: fixed;
        }

        th,
        td {
            border: 1.15px solid #000;
            padding-top: 5px;
            padding-bottom: 2px;

            text-align: center;
            word-wrap: break-word;
        }

        th {
            background-color: #f2f2f2;
            font-weight: bold;
        }

        th:nth-child(2),
        td:nth-child(2) {
            width: 150px;
        }

        th:nth-child(3),
        td:nth-child(3) {
            width: 85px;
        }

        th:nth-child(4),
        td:nth-child(4) {
            width: 150px;
        }

        td:nth-child(5) {
            width: 132px;
            text-align: left;
        }

        th:nth-child(6),
        td:nth-child(6) {
            width: 100px;
        }

        /* th:nth-child(7),
        td:nth-child(7) {} */

        th:nth-child(9),
        td:nth-child(9) {
            width: 68px;
        }

        th:nth-child(10),
        td:nth-child(10) {
            width: 40px;
        }

        .text-left {
            text-align: left;
            padding-left: 0.3rem;
            padding-right: 0.3rem;
        }

        .text-center {
            text-align: center;
            padding-left: 0.3rem;
            padding-right: 0.3rem;
        }
    </style>
</head>

<body>
    <h1>รายงานประวัติการฝึกงาน</h1>
    <table>
        <thead>
            <tr>
                <th class="text-center">ลำดับ</th>
                <th>บริษัท</th>
                <th>จังหวัด</th>
                <th>คณะ</th>
                <th>หลักสูตร</th>
                <th>สาขา</th>
                <th class="text-center">ปีการศึกษา</th>
                <th class="text-center">จำนวน&nbsp;(คน)</th>
                <th>ข้อมูลการติดต่อ</th>
                <th>คะแนน</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($rows as $index => $row): ?>
                <tr>
                    <td><?= htmlspecialchars($index + 1) ?></td>
                    <td class="text-left"><?= htmlspecialchars($row['organization']) ?></td>
                    <td class="text-left"><?= htmlspecialchars($row['province']) ?></td>
                    <td class="text-left"><?= htmlspecialchars($row['faculty']) ?></td>
                    <td class="text-left"><?= htmlspecialchars($row['program']) ?></td>
                    <td class="text-left"><?= htmlspecialchars($row['major']) ?></td>
                    <td><?= htmlspecialchars($row['year']) ?></td>
                    <td class="text-center"><?= htmlspecialchars($row['total_student']) ?></td>
                    <td class="text-left"><?= htmlspecialchars($row['contact']) ?></td>
                    <td class="text-center"><?= htmlspecialchars($row['score']) ?></td>
                </tr>
            <?php endforeach ?>
        </tbody>
    </table>
</body>

</html>

<?php
$html = ob_get_clean();
$mpdf->WriteHTML($html);
$mpdf->Output('internship_report.pdf', 'I');
