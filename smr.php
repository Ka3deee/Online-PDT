<?php
session_start();
unset($_SESSION['store-code']);
unset($_SESSION['employee_no']);

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <title>New PDT Applications</title>
  <meta charset="UTF-8">
    <meta name="description" content="">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="shortcut icon" href="images/favicon.ico"/>
    <link rel="bookmark" href="images/favicon.ico"/>
  <link rel="stylesheet" href="bootstrap-3.4.1-dist/css/bootstrap.min.css">
  <!---   Content Styles -->
  <link href="mycss.css" rel="stylesheet">
  <link href="css/modify.css" rel="stylesheet">
</head>  
<body >
<br><br>
<div class="container">
	<div class="container text-center">
		<img src="resources/lcc.jpg" style="width: 90px; height: 100%;">
		<h4 class = ".fontTitle">SMR Applications</h4>    <br><br>
	  <button type="button" class="btn btn-primary btn-lg btn-block" onclick = "window.location.href='po_receiving/PORrcv.php'">PO Receiving</button>
		<button type="button" class="btn btn-primary btn-lg btn-block" onclick = "window.location.href='rtv_system/server_settings.php'">RTV System</button>
		<button type="button" onclick = "window.location.href='price_verifier/priceverifier.php'" class="btn btn-primary btn-lg btn-block">Price Verifier</button>
		<button type="button" onclick="window.location.href='trf_in/index.php'" class="btn btn-primary btn-lg btn-block">TRF Receiving <span class="trf-span">( In )</span></button>
    <button type="button" onclick = "window.location.href='trf_out/index.php'" class="btn btn-primary btn-lg btn-block">TRF Releasing <span class="trf-span">( Out )</span></button>
		<button type="button" class="btn btn-primary btn-lg btn-block" onclick = "alert('Coming Soon')">Shelf Tag Data Collection</button>
    
	  <button type="button" class="btn btn-primary btn-lg btn-block" onclick="window.location.href='index.php'">
      <span class="glyphicon glyphicon-log-out"></span> Back
    </button>
	</div>
</div>

<div id="preloader">
        <div class="caviar-load"></div>
</div>

 
</body>
 <!-- Jquery-2.2.4 js -->
    <script src="js/jquery/jquery-2.2.4.min.js"></script>
    <!-- Popper js -->
    <script src="js/bootstrap/popper.min.js"></script>
    <!-- Bootstrap-4 js -->
    <script src="js/bootstrap/bootstrap.min.js"></script>
    <!-- All Plugins js -->
    <script src="js/others/plugins.js"></script>
    <!-- Active JS -->
    <script src="js/active.js"></script>

</html>