<?php 
session_start();
if (isset($_SESSION['id']) && $_SESSION['login_type']=='User') {
	
}else{
	echo "<script>alert('Access Denied');window.location.assign('../index.php')</script>";
}



 ?>