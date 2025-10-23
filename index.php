<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$configPath = '/Applications/XAMPP/xamppfiles/htdocs/internship-search-system/config/db_config.php';
// echo '/config/db_config.php';
require __DIR__ . '/config/db_config.php';
// require $configPath;

// require __DIR__ . '/config/db_config.php';

//* การ fetch ข้อมูลใน Database มี 2 วิธี
//? 2. การใช้ผ่าน method prepare
// $id = 1;
// // $sql = $conn->prepare("SELECT * FROM internship_history WHERE id = :id");
// // $sql->bindParam(":id", $id);

// $sql = $conn->prepare("SELECT * FROM internship_history");
// $sql->execute();

// Query every row in the database
// while ($row = $sql->fetch()) {
// 	echo "<h3>" . $row['id'] . " " . $row['faculty'] . " " . $row['program'] . " " . $row['major'] . " " . $row['organization'] . " " . $row['province'] . " " . $row['position'] . " " . $row['year'] . " " . $row['total_student'] . "</h3>";
// }

// $rows = $sql->fetchAll();

// foreach ($rows as $row) {
// 	inspect($row);
// }
// for ($i = 0; $i < count($rows); $i++) {
// 	inspect($rows[$i]);
// }

// inspect(count($sql->fetchAll()));
// inspect($sql->fetchAll()[0]);
?>

<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Document</title>
</head>

<body>
	<h1>CRUD PDO</h1>
	<form action="insert.php" method="POST">
		<input type="text" name="faculty" placeholder="Enter your faculty...">
		<br />
		<input type="text" name="program" placeholder="Enter your program...">
		<br />
		<input type="text" name="major" placeholder="Enter your major...">
		<br />
		<input type="text" name="organization" placeholder="Enter your organization...">
		<br />
		<input type="text" name="province" placeholder="Enter your province...">
		<br />
		<input type="text" name="position" placeholder="Enter your position...">
		<br />
		<input type="text" name="year" placeholder="Enter your year...">
		<br />
		<input type="text" name="total_student" placeholder="Enter your total_student...">
		<br />
		<button type="submit" name="insertBtn">Insert Data</button>
	</form>

</body>

</html>