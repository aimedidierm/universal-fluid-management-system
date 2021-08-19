<?php
require '../php-includes/connect.php';
require 'php-includes/check-login.php';

?>
<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<meta name="viewport" content="width=device-width, initial-scale=1">

<title>UFM - Admin Managers</title>
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


<div class="panel"><div class="table-responsive">
  <table class="table table-striped title1">
    <tr>
      <td>
        <b>N</b>
      </td>
      <td>
        <b>Names</b>
      </td>
      <td>
        <b>Email</b>
      </td>
      <td>
        <b>Phone</b>
      </td>
      <td>

      </td>
    </tr>

      <?php
      $sql = "SELECT * FROM manager";
      $stmt = $db->prepare($sql);
      $stmt->execute();
      if ($stmt->rowCount() > 0) {
          $count = 1;
          while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
              
              ?>
          <tr>
              <td><?php print $count?></td>
              <td><?php print $row['names']?></td>
              <td><?php print $row['email']?></td>
              <td><?php print $row['phone']?></td>
              <td>
              <b><a href="#" class='btn btn-danger btn-sm' data-toggle="modal" data-target="#delete" id="<?php echo $id = $row["id"]?>">Delete</a></b>
              </td>
          </tr>
              <?php
              $count++;
          }
      }else{
          echo "<tr>
              <td colspan='4'>No users </td>
              <tr>";
      }
      ?>
  </table>
  <a href="#" class='btn btn-success btn-lg' data-toggle="modal" data-target="#add" >Add</a>
  <div class="modal fade" id="add" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            <h4 class="modal-title" id="myModalLabel">Are you sure you want to create new Manager?</h4>
        </div>
        <div class="modal-body">
        <?php
        if(isset($_POST['add'])){
          $names = $_POST['names'];
          $email = $_POST['email'];
          $phone = $_POST['phone'];
          $password = $_POST['password'];
          $cpassword = md5($password);
          $sql ="INSERT INTO manager (names, email, phone, password) VALUES (?,?,?,?)";
          $stm = $db->prepare($sql);
          if ($stm->execute(array($names, $email, $phone, $password))) {
            print "<script>alert('your manager added');window.location.assign('managers.php')</script>";
          }
        }
     ?>
        <form role="form" method="post" >
          <fieldset>
            <label>Names </label>
            <input class="form-control" name="names" type="text" required><br>
            <label>Email </label>
            <input class="form-control" name="email" type="email" required><br>
            <label>Phone </label>
            <input class="form-control" name="phone" type="number" required><br>
            <label>Password </label>
            <input class="form-control" name="password" type="password" required><br>
            <button type="submit" name="add" class="btn btn-lg btn-success btn-block">Create acount</button>
          </fieldset>
        </form>
        </div>
      </div>
    </div>
  </div>
  <div class="modal fade" id="delete" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            <h4 class="modal-title" id="myModalLabel">Are you sure you want to delete this User?</h4>
        </div>
        <div class="modal-body">
        <?php
        $sql = "SELECT * FROM manager WHERE id = ? limit 1";
        $stmt = $db->prepare($sql);
        $stmt->execute(array($id));
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if(isset($_POST['delete'])){
          $sql ="DELETE FROM manager WHERE id = ?";
          $stm = $db->prepare($sql);
          if ($stm->execute(array($id))) {
              print "<script>alert('Manager deleted');window.location.assign('managers.php')</script>";

          }
          }
        ?>
        <form role="form" method="post">
          <fieldset>
            <label>Email </label>
            <input class="form-control" id="disabledInput" type="text" placeholder="<?php echo $row['email'] ?>" disabled><br>
            <label>Names </label>
            <input class="form-control" id="disabledInput" type="text" placeholder="<?php echo $row['names'] ?>" disabled><br>
            <button type="submit" name="delete" class="btn btn-lg btn-danger btn-block">Delete</button>
          </fieldset>
        </form>
        </div>
      </div>
    </div>
  </div>
</div>
</div>

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