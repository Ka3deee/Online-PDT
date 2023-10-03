
<!DOCTYPE html>
<?php session_start(); ?>
<html lang="en">
<head>
  <title>PDT Application : PO Receiving</title>
  <meta charset="UTF-8">
    <meta name="description" content="">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <link rel="shortcut icon" href="../images/favicon.ico"/>
    <link rel="bookmark" href="../images/favicon.ico"/>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <link rel="stylesheet" href="../bootstrap-3.4.1-dist/css/bootstrap.min.css">
  <!---   Content Styles -->
  <link href="../mycss.css" rel="stylesheet">
  <link href="../css/modify.css" rel="stylesheet">
</head>

<?php
if(isset($_POST['btnsetuser'])){
  //get user ID
  include 'connect.php';
  $userId = $_POST['textUserID'];
  $user_pass = $_POST['textpass'];
  $str_code = $_SESSION['Storecode'];
  $user_pass = md5($user_pass);
  $check_user = "SELECT * FROM `user_tbl` Where Active != 0 and user_EEno='$userId' and user_pass='$user_pass' and store_code='$str_code'";
  $hasrows = $conn->query($check_user);		
	if ($hasrows->rowCount() > 0) {
		$rows = $hasrows->fetch(PDO::FETCH_ASSOC);
		$_SESSION['user_id'] = $userId;
		$_SESSION['full_name'] = $rows['user_fname'].' '.$rows['user_mname'].' '.$rows['user_lname'];
		?>
		<script>
		alert("User ID added Successfully!");
		window.location.href = "PORrcv.php";
		</script>
	  <?php		
	} else {
		unset( $_SESSION['user_id']);
		unset($_SESSION['full_name']);
		?>
		<script>
		alert("Invalid Username or Password!, Please Try again");
		window.location.href = "userID.php";
		</script>
	  <?php
	}
}

?>  
<body>
<form method = "POST" Action = "userID.php">
<div class="container text-center">
	
	<img src="../resources/lcc.jpg" style="width: 90px; height: 70px;">
	<h4>PO Receiving : Set User ID</h4>
	<h5 class="semi-visible">v2.0.0</h5>
    <br><br>
		<div class="row">
		  <div class="col-xs-12">
			<label for="ex1">User ID</label>

			<input required maxlength="10" onkeypress="if ( isNaN(this.value + String.fromCharCode(event.keyCode) )) return false;" style = "text-align:center !important;"  class="form-control input-lg" name="textUserID"  id="textUserID" type="text" autofocus >
					
		  </div>
		  <div class="col-xs-12">
			<label for="ex1">Password</label>
			<input required style = "text-align:center !important;"  class="form-control input-lg" name="textpass"  id="textpass" type="password"  >			
		  </div>
		  
		</div>
	  <br>
	<div>
		<button type="submit" class="btn btn-primary btn-lg btn-block" name = "btnsetuser"><span class="glyphicon glyphicon-ok"></span> Save</button>
		<button type="button" class="btn btn-primary btn-lg btn-block" onclick = "window.location.href='PORrcv.php'"><span class="glyphicon glyphicon-log-out"></span> Back</button>
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