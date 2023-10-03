
<!DOCTYPE html>
<?php session_start(); ?>
<html lang="en">
<head>
  <title>PDT Application : Online Price Verifier</title>
  <meta charset="UTF-8">
    <meta name="description" content="">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <link rel="shortcut icon" href="../images/favicon.ico"/>
    <link rel="bookmark" href="../images/favicon.ico"/>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
 <link rel="stylesheet" href="../bootstrap-3.4.1-dist/css/bootstrap.min.css">
  <!---   Content Styles -->
  <link href="../mycss.css" rel="stylesheet">
  <style>
  .fontTitle{
	  font-size:10pt;
	  font-weight:bold;
  }
  </style>
</head>
<?php
if(isset($_POST['btnsetsbu'])){
  //get user ID
  $sbu = $_POST['txtsbu'];
  $_SESSION['Price_sbu'] = $sbu;
  ?>
    <script>
      alert("SBU added Successfully!");
      window.location.href = "setSBU.php";
    </script>
  <?php
}

?>  
<body>
<form method = "POST" Action = "setSBU.php">
<div class="container" style = "padding-top:10px;text-align:center;">
	<img src="../resources/lcc.jpg" style="width: 120px; height: 100%;">
		<div class="row">
		  <div class="col-xs-12">
			<label for="ex1">Set SBU</label>
      <select class="form-control input-lg" id="txtsbu" name="txtsbu">
      <option selected disable value = "None">Please Choose SBU</option>
      <option <?php if(isset($_SESSION['Price_sbu'])){ if($_SESSION['Price_sbu'] == "SMR") echo "selected"; }?> value = "SMR">SMR</option>
      <option <?php if(isset($_SESSION['Price_sbu'])){ if($_SESSION['Price_sbu'] == "DS") echo "selected"; }?> value = "DS">DS</option>
      </select>
		 </div>
		  
		</div>
	  <br>
	<div class="container text-center">
		<button type="submit" class="btn btn-primary btn-lg btn-block" name = "btnsetsbu" >Set SBU</button>
		<button type="button" class="btn btn-primary btn-lg btn-block" onclick = "window.location.href='priceverifier.php'"><span class="glyphicon glyphicon-log-out"></span> Back to Menu</button>
	</div>
</div>
</form>
<div id="preloader">
        <div class="caviar-load">
		</div>
		
</div>
</body>
	
 <!-- Jquery-2.2.4 js -->
    <script src="../js/jquery/jquery-2.2.4.min.js"></script>
    <!-- Popper js -->
    <script src="../js/bootstrap/popper.min.js"></script>
    <!-- Bootstrap-4 js -->
    <script src="../js/bootstrap/bootstrap.min.js"></script>
    <!-- All Plugins js -->
    <script src="../js/others/plugins.js"></script>
    <!-- Active JS -->
    <script src="../js/active.js"></script>

</html>