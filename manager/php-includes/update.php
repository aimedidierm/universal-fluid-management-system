<?php
require '../../php-includes/connect.php';
    $names = $_POST['name'];
    $phone = $_POST['mob'];
    $password = $_POST['password'];
    $cpassword = md5($password);
    $sql ="UPDATE manager SET names = ?, phone = ? WHERE password = ?";
    $stm = $db->prepare($sql);
    if ($stm->execute(array($names, $phone, $cpassword))) {
    print "<script>alert('Your data updated');window.location.assign('../account.php')</script>";
      }
?>