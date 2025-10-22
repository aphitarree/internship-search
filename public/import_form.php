require_once(__DIR__ . '/../src/tracker.php');
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Import Excel Data</title>
    <style>
        body { font-family: sans-serif; line-height: 1.6; padding: 20px; }
        .container { max-width: 600px; margin: auto; border: 1px solid #ccc; padding: 20px; border-radius: 5px; }
        input[type="file"] { border: 1px solid #ccc; padding: 10px; width: 100%; }
        input[type="submit"] { background-color: #007bff; color: white; padding: 10px 20px; border: none; border-radius: 5px; cursor: pointer; font-size: 16px; }
        input[type="submit"]:hover { background-color: #0056b3; }
        .notice { background-color: #f8d7da; color: #721c24; padding: 10px; border: 1px solid #f5c6cb; border-radius: 5px; margin-top: 20px; }
    </style>
</head>
<body>

<div class="container">
    <h2>นำเข้าข้อมูลจากไฟล์ Excel</h2>
    <p>เลือกไฟล์ Excel (.xlsx) ที่มีข้อมูลตามรูปแบบที่กำหนด</p>

    <?php
    // Display success or error messages from the process_excel.php redirect
    if (isset($_GET['status'])) {
        if ($_GET['status'] == 'success') {
            echo '<p style="color:green;"><strong>นำเข้าข้อมูลสำเร็จ!</strong></p>';
        } else if ($_GET['status'] == 'error') {
            echo '<p style="color:red;"><strong>เกิดข้อผิดพลาด:</strong> ' . htmlspecialchars($_GET['message']) . '</p>';
        }
    }
    ?>

    <form action="process_excel.php" method="post" enctype="multipart/form-data">
        <p>
            <label for="excelFile">เลือกไฟล์ Excel:</label><br>
            <input type="file" name="excelFile" id="excelFile" accept=".xlsx, .xls" required>
        </p>
        <p>
            <input type="submit" value="เริ่มนำเข้าข้อมูล" name="submit">
        </p>
    </form>

    <div class="notice">
        <strong>ข้อควรระวัง:</strong>
        <ul>
            <li>ไฟล์ต้องเป็น .xlsx หรือ .xls</li>
            <li>แถวแรก (Row 1) ต้องเป็น Header และมีชื่อคอลัมน์ที่ถูกต้อง</li>
            <li>ข้อมูลต้องเริ่มที่แถวที่ 2 (Row 2) เป็นต้นไป</li>
        </ul>
    </div>
</div>

</body>
</html>
