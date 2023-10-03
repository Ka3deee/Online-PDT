
<!DOCTYPE html>
<?php session_start(); ?>
<html lang="en">
<head>
  <title>PDT Application : PO Receiving App</title>
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
if(isset($_POST['btnsetuser'])){
  //get user ID
  $userId = $_POST['textUserID'];
  $_SESSION['user_id'] = $userId;
  ?>
    <script>
      alert("User ID added Successfully!");
      window.location.href = "userID.php";
    </script>
  <?php
}

?>  
<body>
<form method = "POST" Action = "userID.php">
<div class="container" style = "padding-top:10px;text-align:center;">
	<img src="../resources/lcc.jpg" style="width: 120px; height: 100%;">
		<div class="row">
		  <div class="col-xs-12">
			<label for="ex1">Set User ID</label>

			<input required value = "<?php if(isset($_SESSION['user_id']) ){ echo $_SESSION['user_id']; } ?>"style = "text-align:center !important;"  class="form-control input-lg" name="textUserID"  id="textUserID" type="text" autofocus >			
		  </div>
		  
		</div>
	  <br>
	<div class="container text-center">
		<button type="submit" class="btn btn-primary btn-lg btn-block" name = "btnsetuser">Set User ID</button>
		<button type="button" class="btn btn-primary btn-lg btn-block" onclick = "window.location.href='PORrcv.php'"><span class="glyphicon glyphicon-log-out"></span>  Back to Menu</button>
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