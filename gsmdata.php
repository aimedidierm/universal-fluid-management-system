<?php 

require 'php-includes/connect.php';

$per= $_GET['per'];

echo $per;

$sql ="UPDATE status SET level = ? WHERE tank_id = '1'";
$stm = $db->prepare($sql);
$stm->execute(array($per));
?>