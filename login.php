<?php 
session_start();
require 'php-includes/connect.php';
extract($_POST);
$password=md5($password);

$query = "SELECT * FROM user WHERE email= ? AND password = ? limit 1";
$stmt = $db->prepare($query);
$stmt->execute(array($email, $password));
$rows = $stmt->fetchAll();
if ($stmt->rowCount()>0) {
	$_SESSION['code'] = $email;
	$_SESSION['id'] = session_id();
	$_SESSION['login_type'] = "User";

	echo "<script>alert('You Are Logged In');window.location.assign('user/dashboard.php')</script>";
}

$query = "SELECT * FROM manager WHERE email= ? AND password= ? limit 1";
$stmt = $db->prepare($query);
$stmt->execute(array($email, $password));
$rows = $stmt->fetchAll();
if ($stmt->rowCount()>0) {
	$_SESSION['code'] = $email;
	$_SESSION['id'] = session_id();
	$_SESSION['login_type'] = "Manager";

	echo "<script>alert('You Are Logged In');window.location.assign('manager/dashboard.php')</script>";
}

$query = "SELECT * FROM admin WHERE email= ? AND password = ? limit 1";
$stmt = $db->prepare($query);
$stmt->execute(array($email, $password));
$rows = $stmt->fetchAll();
if ($stmt->rowCount()>0) {
	$_SESSION['userid'] = $email;
	$_SESSION['id'] = session_id();
	$_SESSION['login_type'] = "Admin";

	echo "<script>alert('You Are Logged In');window.location.assign('admin/dashboard.php')</script>";
}else{
	echo "<script>alert('Your ID or Password is Wrong');window.location.assign('index.php')</script>";
}