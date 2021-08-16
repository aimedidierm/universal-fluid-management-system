<?php
require '../php-includes/connect.php';
require 'php-includes/check-login.php';

?>
<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<meta name="viewport" content="width=device-width, initial-scale=1">

<title>UFM - Admin Tanks</title>
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
        <b>Number</b>
      </td>
      <td>
        <b>Country</b>
      </td>
      <td>
        <b>Province</b>
      </td>
      <td>
        <b>District</b>
      </td>
      <td>
        <b>Sector</b>
      </td>
      <td>
        <b>Cell</b>
      </td>
      <td>
        <b>Description</b>
      </td>
      <td>

      </td>
    </tr>

      <?php
      $sql = "SELECT * FROM tanks";
      $stmt = $db->prepare($sql);
      $stmt->execute();
      if ($stmt->rowCount() > 0) {
          $count = 1;
          while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
              
              ?>
          <tr>
              <td><?php print $count?></td>
              <td><?php print $row['names']?></td>
              <td><?php print $row['ip']?></td>
              <td><?php print $row['country']?></td>
              <td><?php print $row['province']?></td>
              <td><?php print $row['district']?></td>
              <td><?php print $row['sector']?></td>
              <td><?php print $row['cell']?></td>
              <td><?php print $row['description']?></td>
              <td>
              <b><a href="#" class='btn btn-danger btn-sm' data-toggle="modal" data-target="#delete" id="<?php echo $id = $row["id"]?>">Delete</a></b>
              </td>
          </tr>
              <?php
              $count++;
          }
      }else{
          echo "<tr>
              <td colspan='10'>No Tanks </td>
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
            <h4 class="modal-title" id="myModalLabel">Are you sure you want to create new Tank?</h4>
        </div>
        <div class="modal-body">
        <?php
        if(isset($_POST['add'])){
          $manager_email = $_POST['manager_email'];
          $sql = "SELECT id, email FROM manager WHERE email = ? limit 1";
          $stmt = $db->prepare($sql);
          $stmt->execute(array($manager_email));
          $row = $stmt->fetch(PDO::FETCH_ASSOC);
          $m_id = $row['id'];
          $names = $_POST['names'];
          $number = $_POST['number'];
          $country = $_POST['country'];
          $province = $_POST['province'];
          $district = $_POST['district'];
          $sector = $_POST['sector'];
          $cell = $_POST['cell'];
          $description = $_POST['description'];
          $sql ="INSERT INTO tanks (names, ip, country, province, district, sector, cell, description, manager_id) VALUES (?,?,?,?,?,?,?,?,?)";
          $stm = $db->prepare($sql);
          if ($stm->execute(array($names, $number, $country, $province, $district, $sector, $cell, $description, $m_id))) {
            print "<script>alert('your Tank added');window.location.assign('tanks.php')</script>";
          }
        }
     ?>
        <form role="form" method="post" >
          <fieldset>
            <label>Names </label>
            <input class="form-control" name="names" type="text" required><br>
            <label>Number </label>
            <input class="form-control" name="number" type="text" required><br>
            <label>country </label>
            <input class="form-control" name="country" type="text" required><br>
            <label>Province </label>
            <input class="form-control" name="province" type="text" required><br>
            <label>District </label>
            <input class="form-control" name="district" type="text" required><br>
            <label>Sector </label>
            <input class="form-control" name="sector" type="text" required><br>
            <label>Cell </label>
            <input class="form-control" name="cell" type="text" required><br>
            <label>Description </label>
            <input class="form-control" name="description" type="text" required><br>
            <label>Manager Email</label>
            <input class="form-control" name="manager_email" type="text" required><br>
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
        $sql = "SELECT * FROM tanks WHERE id = ? limit 1";
        $stmt = $db->prepare($sql);
        $stmt->execute(array($id));
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if(isset($_POST['delete'])){
          $sql ="DELETE FROM tanks WHERE id = ?";
          $stm = $db->prepare($sql);
          if ($stm->execute(array($id))) {
              print "<script>alert('Tank deleted');window.location.assign('tanks.php')</script>";

          }
          }
        ?>
        <form role="form" method="post">
          <fieldset>
            <label>Names </label>
            <input class="form-control" id="disabledInput" type="text" placeholder="<?php echo $row['names'] ?>" disabled><br>
            <label>Cell </label>
            <input class="form-control" id="disabledInput" type="text" placeholder="<?php echo $row['cell'] ?>" disabled><br>
            <label>Description </label>
            <input class="form-control" id="disabledInput" type="text" placeholder="<?php echo $row['description'] ?>" disabled><br>
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