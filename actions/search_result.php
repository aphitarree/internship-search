<?php

$faculty = $_POST['faculty'];
$major = $_POST['major'];
$program = $_POST['program'];
$province = $_POST['province'];
$academicYear = $_POST['academic-year'];

function inspect($value) {
  echo '<pre>';
  print_r($value);
  echo '</pre>';
}

inspect($_POST);

echo inspect($faculty);
echo inspect($major);
echo inspect($program);
echo inspect($province);
echo inspect($academicYear);


?>

<section>
  <h1>test</h1>
  <p><?= inspect($faculty) ?></p>
  <p><?= inspect($major) ?></p>
  <p><?= inspect($program) ?></p>
  <p><?= inspect($province) ?></p>
  <p><?= inspect($academicYear) ?></p>
</section>