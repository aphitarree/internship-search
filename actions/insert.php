<?php
require_once __DIR__ . '/config/db_config.php';

if (isset($_POST['insertBtn'])) {
	$faculty = $_POST['faculty'];
	$program = $_POST['program'];
	$major = $_POST['major'];
	$organization = $_POST['organization'];
	$province = $_POST['province'];
	$position = $_POST['position'];
	$year = (int)$_POST['year'];
	$total_student = (int)$_POST['total_student'];


	$sql = $conn->prepare('INSERT INTO internship_history(faculty, program, major, organization, province, position, year, total_student) VALUES(:faculty, :program, :major, :organization, :province, :position, :year, :total_student)');
	$sql->bindParam(':faculty', $faculty);
	$sql->bindParam(':program', $program);
	$sql->bindParam(':major', $major);
	$sql->bindParam(':organization', $organization);
	$sql->bindParam(':province', $province);
	$sql->bindParam(':position', $position);
	$sql->bindParam(':year', $year);
	$sql->bindParam(':total_student', $total_student);
	$sql->execute();

	if ($sql) {
		header("location: index.php");
	} else {
		header("location: index.php");
	}
}
