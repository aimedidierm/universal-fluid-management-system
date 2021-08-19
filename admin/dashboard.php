<?php
require '../php-includes/connect.php';
require 'php-includes/check-login.php';

?>
<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<meta name="viewport" content="width=device-width, initial-scale=1">

<title>UFM - Admin Dashboard</title>
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
<!-- Styles -->
<style>
#chartdiv {
  width: 25%;
  height: 400px;
}

</style>

<!-- Resources -->
<script src="https://cdn.amcharts.com/lib/4/core.js"></script>
<script src="https://cdn.amcharts.com/lib/4/charts.js"></script>
<script src="https://cdn.amcharts.com/lib/4/themes/animated.js"></script>

<?php
$sql = "SELECT s.tank_id, s.level, s.volume, t.names, t.id FROM status AS s JOIN tanks AS t WHERE t.id = s.tank_id limit 1";
$stmt = $db->prepare($sql);
$stmt->execute(array($tank_id));
$row = $stmt->fetch(PDO::FETCH_ASSOC);
$level = $row['level'];
$volume = $row['volume'];
$names = $row['names'];
if(isset($_POST['open'])){
  // Gufungura amazi
}
if(isset($_POST['close'])){
  // Gufunga amazi
}
?>
<!-- Chart code -->
<script>
am4core.ready(function() {

// Themes begin
am4core.useTheme(am4themes_animated);
// Themes end

// Create chart instance
var chart = am4core.create("chartdiv", am4charts.XYChart3D);

chart.titles.create().text = "Universal fluid management system";

// Add data

chart.data = [{
  "category": '<?php echo $names ?>',
  "value1": '<?php echo $level ?>',
  "value2": '<?php echo 100 - $level ?>'
}];

// Create axes
var categoryAxis = chart.xAxes.push(new am4charts.CategoryAxis());
categoryAxis.dataFields.category = "category";
categoryAxis.renderer.grid.template.location = 0;
categoryAxis.renderer.grid.template.strokeOpacity = 0;

var valueAxis = chart.yAxes.push(new am4charts.ValueAxis());
valueAxis.renderer.grid.template.strokeOpacity = 0;
valueAxis.min = -10;
valueAxis.max = 110;
valueAxis.strictMinMax = true;
valueAxis.renderer.baseGrid.disabled = true;
valueAxis.renderer.labels.template.adapter.add("text", function(text) {
  if ((text > 100) || (text < 0)) {
    return "";
  }
  else {
    return text + "%";
  }
})

// Create series
var series1 = chart.series.push(new am4charts.ConeSeries());
series1.dataFields.valueY = "value1";
series1.dataFields.categoryX = "category";
series1.columns.template.width = am4core.percent(80);
series1.columns.template.fillOpacity = 0.9;
series1.columns.template.strokeOpacity = 1;
series1.columns.template.strokeWidth = 2;

var series2 = chart.series.push(new am4charts.ConeSeries());
series2.dataFields.valueY = "value2";
series2.dataFields.categoryX = "category";
series2.stacked = true;
series2.columns.template.width = am4core.percent(80);
series2.columns.template.fill = am4core.color("#000");
series2.columns.template.fillOpacity = 0.1;
series2.columns.template.stroke = am4core.color("#000");
series2.columns.template.strokeOpacity = 0.2;
series2.columns.template.strokeWidth = 2;

}); // end am4core.ready()
</script>

<!-- HTML -->
<div id="chartdiv">
</div>
<div>
  <h1>
  <?php
  $total = $level/100 * $volume;
  echo $total;
  ?>
  L
  </h1>
  <form role="form" method="post" >
    <fieldset>
    <input  type="submit"  value="Open"  name="open" class="btn btn-primary"/>
    <input  type="submit"  value="Close" name="close" class="btn btn-danger"/>
    </fieldset>
  </form>
</div>
<br>
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