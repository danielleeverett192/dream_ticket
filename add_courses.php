<?php
/*
session_start();

if(!isset($_SESSION['username']))
	die('You must be logged in to access this page<br/>'."<a href='login.html'>Click here</a> to log in");
*/
$db = json_decode(file_get_contents("../db.json"));
$dbh = new PDO('pgsql:host='.$db->{'host'}.';port='.$db->{'port'}.';dbname='.$db->{'dbname'}.';user='.$db->{'user'}.';password='.$db->{'password'})
	or die('Could not connect to database');

$course_code = $_GET['course_code'];
$title = $_GET['title'];

$query = 'SELECT course_code
			FROM courses
			WHERE course_code = :course_code';
$stmt = $dbh->prepare($query);
$stmt->bindParam(':course_code', $course_code);
$stmt->execute();
$row = $stmt->fetch();

$query2 = 'INSERT INTO courses (course_code, title)
				VALUES (:course_code, :title)';
$stmt2 = $dbh->prepare($query2);
$stmt2->bindParam(':course_code', $course_code);
$stmt2->bindParam(':title', $title);

if(!$row || strlen($course_code != 7 || strlen($title) > 60)) {
	if(!$row)
		echo 'That course already exists';
	if(strlen($course_code != 7))
		echo 'Not a valid course_code';
	if(strlen($title) > 60)
		echo 'Course title cannot exceed 60 characters';
}
else {
	$stmt2->execute();
	echo 'Course created!';
}

$dbh = null;