<?php
require '../php-includes/connect.php';
require 'php-includes/check-login.php';

$sql = "SELECT *  FROM admin WHERE email= ? limit 1";
$stmt = $db->prepare($sql);
$stmt->execute(array($_SESSION['userid']));
$row = $stmt->fetch(PDO::FETCH_ASSOC);
$names = $row['names'];
$phone = $row['phone'];



?>
<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<meta name="viewport" content="width=device-width, initial-scale=1">

<title>UFM - Account Account</title>
<link  rel="stylesheet" href="../css/bootstrap.min.css"/>
 <link  rel="stylesheet" href="../css/bootstrap-theme.min.css"/>    
 <link rel="stylesheet" href="../css/main.css">
 <link  rel="stylesheet" href="../css/font.css">
 <script src="../js/jquery.js" type="text/javascript"></script>

 
  <script src="../js/bootstrap.min.js"  type="text/javascript"></script>
<link href='http://fonts.googleapis.com/css?family=Roboto:400,700,300' rel='stylesheet' type='text/css'>
</head>

<?php include_once 'php-includes/menu.php'; ?>



<div class="container"><!--container start-->
<div class="row">
<div class="col-md-12">

<div class="col-md-12"></div>

<div class="col-md-4 panel">
<!-- sign in form begins -->  
  <form class="form-horizontal" name="form" action="php-includes/update.php" method="POST">
<fieldset>


<!-- Text input-->
<div class="form-group">
  <label class="col-md-12 control-label" for="name"></label>  
  <div class="col-md-12">
  <input id="name" name="name" placeholder="Enter your name" class="form-control input-md" type="text" value="<?php echo $names ?>">
  </div>
</div>


<!-- Text input-->
<div class="form-group">
  <label class="col-md-12 control-label" for="mob"></label>  
  <div class="col-md-12">
  <input id="mob" name="mob" placeholder="Enter your mobile number" class="form-control input-md" type="number" value="<?php echo $phone ?>">
    
  </div>
</div>


<!-- Text input-->
<div class="form-group">
  <label class="col-md-12 control-label" for="password"></label>
  <div class="col-md-12">
    <input id="password" name="password" placeholder="Enter your password" class="form-control input-md" type="password">
    
  </div>
</div>
<!-- Button -->
<div class="form-group">
  <label class="col-md-12 control-label" for=""></label>
  <div class="col-md-12"> 
    <input  type="submit" class="sub" value="Update" class="btn btn-primary"/>
  </div>
</div>

</fieldset>
</form>
</div><!--col-md-6 end-->
</div></div>


</div>
</div>
</div>
</div>
<!--Footer start-->
<div class="row footer">
<div class="col-md-3 box">
<a href="#" target="_blank">2021 Universal fluid management system</a>
</div>
</div>
<!--footer end-->
</body>
</html>