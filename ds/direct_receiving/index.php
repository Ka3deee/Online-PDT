
<!DOCTYPE html>
<?php
session_start();
include ("opt/redirect.php");
?>
<html lang="en">
<head>
  <title>PDT Application DS : Drirect Receiving</title>
  <meta charset="UTF-8">
    <meta name="description" content="">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <link rel="shortcut icon" href="../../images/favicon.ico"/>
    <link rel="bookmark" href="../../images/favicon.ico"/>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  
  <link rel="stylesheet" href="../../bootstrap-3.4.1-dist/css/bootstrap.min.css">
  <!---   Content Styles -->
  <link href="../../mycss.css" rel="stylesheet">
  <style>
  .fontTitle{
	  font-size:12pt;
	  font-weight:bold;
  }
  .btn {
    font-weight:bold;
  }
  </style>
</head>  
<body >
	<div class="container-fluid text-center">
		<img src="../../resources/lcc.jpg" style="width: 90px; height: 70px;">
		<h5 class = "fontTitle" style = "font-style:italic">Welcome: <?php echo $_SESSION['wms_status_user'];?></h5>
		<h4 class = "fontTitle">Truck Receiving Direct</h4>
        <div class="container-fluid ">
        <button type="button" class="btn btn-primary  btn-block btn-md" onclick = "fn_start()" id = "btnstart">START</button>
        <hr>
        <div class="row" style = "text-align:left;">
          <!-- AR Ref -->
          <div class="col-xs-12">
          <label for="ex1">AR Ref</label>
          <input 
          
          onkeypress="if ( isNaN(this.value + String.fromCharCode(event.keyCode) )) return false;"
          class="form-control input-md"
          id="txtar_ref"
          type="text" 
          <?php 
          if (isset($_SESSION['new_ar'])){
              ?>value = "<?php echo $_SESSION['new_ar'];?>"<?php
          }       
          ?>
           >
          </div>
          <!-- Plate Number -->
          <div class="col-xs-12">
          <label for="ex1">Plate Number</label>
          <input 
          
          class="form-control input-md" 
          id="txtplates" 
          type="text" 
          <?php 
          if (isset($_SESSION['plate_num'])){
              ?>value = "<?php echo $_SESSION['plate_num'];?>"<?php
          }       
          ?>
           >
            
        </div>
        </div>
        <hr>
        <button type="button" id = "btnadd" class="btn btn-primary  btn-block btn-md" onclick = "check_complete()" >ADD PO</button>
        <button type="button" id = "btnconfirm"   class="btn btn-primary  btn-block btn-md" onclick = "confirm_ar()" >Confirm Finish</button>
        <button type="button" id = "btnview" onclick = "window.location.href='drt_rcv_dashboard_a.php'" class="btn btn-primary btn-block btn-md">View Dashboard</button>		
	  
		
		<button type="button" class="btn btn-primary  btn-block btn-md" onclick = "clearsession()"><span class="glyphicon glyphicon-log-out"></span> Back to Menu</button>
		 </div>
    </div>
<div id="preloader">
        <div class="caviar-load"></div>
</div>

</body>
	<!-- Page Functions -->
    <script src="js/direct_rcv.js"></script>
    <!-- Jquery-2.2.4 js -->
    <script src="../../js/jquery/jquery-2.2.4.min.js"></script>
    <!-- Popper js -->
    <script src="../../js/bootstrap/popper.min.js"></script>
    <!-- Bootstrap-4 js -->
    <script src="../../js/bootstrap/bootstrap.min.js"></script>
    <!-- All Plugins js -->
    <script src="../../js/others/plugins.js"></script>
    <!-- Active JS -->
    <script src="../../js/active.js"></script>
</html>