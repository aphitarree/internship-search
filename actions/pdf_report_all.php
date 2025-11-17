<?php
ini_set('max_execution_time', '300');
ini_set('memory_limit', '4096M');
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../config/db_config.php';

use Mpdf\Config\ConfigVariables;
use Mpdf\Config\FontVariables;

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Method not allowed']);
    exit;
}

$faculty = $_POST['faculty'] ?? null;
$program = $_POST['program'] ?? null;
$major = $_POST['major'] ?? null;
$province = $_POST['province'] ?? null;
$academicYear = $_POST['academic-year'] ?? null;

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

$mpdf->SetTitle('รายงานประวัติการฝึกงาน');

$mpdf->defaultfooterline = 0;
$mpdf->setFooter('
    <div style="font-family: sarabun, 
                sans-serif; font-size: 14pt; 
                font-style: normal;
                border-top: none;">
                {PAGENO} / {nbpg}
    </div>
');

$sql = "
    SELECT
        faculty_program_major.faculty,
        faculty_program_major.program,
        faculty_program_major.major,
        internship_stats.organization,
        internship_stats.province,
        internship_stats.year,
        internship_stats.total_student,
        internship_stats.mou_status,
        internship_stats.contact,
        internship_stats.affiliation,
        internship_stats.score
    FROM internship_stats INNER JOIN faculty_program_major ON internship_stats.major_id = faculty_program_major.id
    ORDER BY internship_stats.year DESC
";

$stmt = $conn->query($sql);
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Get all company values into a new array
$allCompany = array_column($rows, 'organization');

// Count the unique company values
$uniqueCompanyCount = count(array_unique($allCompany));

// Get all province values into a new array
$allProvince = array_column($rows, 'province');

// Count the unique province values
$uniqueProvinceCount = count(array_unique($allProvince));

// Get all students values into a new array
$Student = array_column($rows, 'total_student');

// Count all students amount
$allStudent = $totalStudents = array_sum(array_column($rows, 'total_student'));

// Set to Bangkok timezone
date_default_timezone_set('Asia/Bangkok');

$thaiMonths = [
    1 => 'มกราคม',
    'กุมภาพันธ์',
    'มีนาคม',
    'เมษายน',
    'พฤษภาคม',
    'มิถุนายน',
    'กรกฎาคม',
    'สิงหาคม',
    'กันยายน',
    'ตุลาคม',
    'พฤศจิกายน',
    'ธันวาคม'
];

// Get the current date
$day = date('j'); // Day
$month = $thaiMonths[(int)date('n')]; // Thai month (words)
$year = date('Y') + 543; // Convert to B.E.

// Join the words
$thaiDate = "$day $month $year";

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

        p {
            font-family: "sarabun", sans-serif;
            line-height: 0.75;
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
            width: 150px;
        }


        th:nth-child(4),
        td:nth-child(4) {
            width: 150px;
        }

        td:nth-child(5) {
            width: 150px;
            text-align: left;
        }

        th:nth-child(6),
        td:nth-child(6) {
            width: 100px;
        }

        th:nth-child(8),
        td:nth-child(8) {
            width: 68px;
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

        .report-header {
            width: 80%;
            margin: 0 auto 10px auto;
            border-collapse: collapse;
            text-align: left;
        }

        .report-header td {
            vertical-align: top;
            border: none;
            padding: 4px 10px;
            font-family: "sarabun", sans-serif;
            font-size: 14pt;
        }

        .report-header .left {
            width: 35%;
            text-align: left;
            padding-left: 25px;
            white-space: nowrap;
        }

        .report-header .right {
            width: 15%;
            text-align: left;
            white-space: nowrap;
        }
    </style>
</head>

<body>

    <h1>รายงานประวัติการฝึกงาน</h1>
    <table class="report-header">
        <tr>
            <td class="left">
                <p><b>วันที่พิมพ์รายงาน:</b> <?= $thaiDate; ?></p>
                <p><b>ผลลัพธ์การค้นหา:</b> <?= count($rows); ?> การค้นหา</p>
                <p><b>ตัวกรอง:</b>
                    <b>คณะ:</b> <?= htmlspecialchars($faculty ?: 'ทั้งหมด') ?>,
                    <b>หลักสูตร:</b> <?= htmlspecialchars($program ?: 'ทั้งหมด') ?>,
                    <b>สาขา:</b> <?= htmlspecialchars($major ?: 'ทั้งหมด') ?>,
                    <b>จังหวัด:</b> <?= htmlspecialchars($province ?: 'ทั้งหมด') ?>,
                    <b>ปีการศึกษา:</b> <?= htmlspecialchars($academicYear ?: 'ทั้งหมด') ?>
                </p>
            </td>
            <td class="right">
                <p><b>จำนวนบริษัท:</b> <?= $uniqueCompanyCount; ?> บริษัท</p>
                <p><b>จำนวนนักศึกษา:</b> <?= $allStudent; ?> คน</p>
            </td>
        </tr>
    </table>
    <table>
        <thead>
            <tr>
                <th class="text-center">ลำดับ</th>
                <th>ชื่อบริษัท</th>
                <th>จังหวัด</th>
                <th>คณะ</th>
                <th>หลักสูตร</th>
                <th>สาขา</th>
                <th class="text-center">ปีการศึกษา</th>
                <th class="text-center">จำนวน&nbsp;(คน)</th>
                <th class="text-center">MOU</th>
                <th>ข้อมูลการติดต่อ</th>
                <th>คะแนน</th>
                <th>สังกัด</th>
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
                    <td class="text-center"><?= htmlspecialchars($row['mou_status']) ?></td>
                    <td class="text-left"><?= htmlspecialchars($row['contact']) ?></td>
                    <td class="text-center"><?= htmlspecialchars($row['score']) ?></td>
                    <td class="text-left"><?= htmlspecialchars($row['affiliation']) ?></td>
                </tr>

                <?php
                if (($index + 1) % 50 === 0) {
                    echo "<!--CHUNK_BREAK-->";
                }
                ?>
            <?php endforeach ?>
        </tbody>
    </table>
</body>

</html>

<?php
$html = ob_get_clean();
$chunks = explode('<!--CHUNK_BREAK-->', $html);
foreach ($chunks as $chunk) {
    if (trim($chunk) === '') {
        continue;
    }
    $mpdf->WriteHTML($chunk);
}

$mpdf->Output('internship_report.pdf', 'I');
