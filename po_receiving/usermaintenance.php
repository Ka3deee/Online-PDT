
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
<div class="container text-center">
	
	<img src="../resources/lcc.jpg" style="width: 90px; height: 70px;">
	<h4>PO Receiving : User Maintenance</h4>
	<h5 class="semi-visible">v2.0.0</h5>
    <br><br>
		<div class="row">
		  <div class="col-xs-12">
			<?php
			include 'connect.php';
			$str_code = $_SESSION['Storecode'];
			$getusers_query = "SELECT * FROM `user_tbl` Where Active != 0 and store_code='$str_code'";
			?>
			<table class="table table-bordered" style = "text-align:left;font-size:12pt;">
				<thead>
					<tr >
						<th style = "border:none;"colspan = "3"><b>Store: <?php echo $str_code; ?></b></th>
						<th style = "border:none;"><a class="btn btn-primary padding-bottom-xs pull-right" href = "javascript:openaddmod('myModal')"> <span class="glyphicon glyphicon-plus"></span><span class="glyphicon glyphicon-user"></span> New User</a>	
						</th>
					</tr>
				</thead>	
			</table>
			<?php
			$result1 = $conn->query($getusers_query);		
			// remove finish Scanned PO from the list
			if($result1->rowCount() > 0){
				?><table class="table table-bordered" style = "text-align:left;font-size:12pt;">
				
				<tr>
					<th>EE No.</th>
					<th>Full Name</th>
					<th>Action</th>
				</tr>
				</thead>
				<tbody><?php
				$counter = 0;
			   while($rowd = $result1->fetch(PDO::FETCH_ASSOC)){
					
					 ?><tr>
						<td><?php echo $rowd["user_EEno"]; ?></td>
						<td><?php echo $rowd["user_fname"]; ?> <?php echo $rowd["user_mname"]; ?> <?php echo $rowd["user_lname"]; ?></td>

						<td> <button type="button" class="btn btn-sm btn-primary" onclick = "up_user('<?php echo $rowd['user_id']; ?>','<?php echo $rowd['user_EEno']; ?>','<?php echo $rowd['user_fname']; ?>','<?php echo $rowd['user_mname']; ?>','<?php echo $rowd['user_lname']; ?>','<?php echo $rowd['user_pass']; ?>')" >Update</button>
						<button  type="button" class="btn btn-sm btn-danger" onclick = "del_user('<?php echo $rowd['user_id']; ?>')" >Remove</button>
						</td>
					</tr><?php
					$counter++;
			   }          
			   ?>
			   </tbody>
				</table>
			   <?php
			}
			?>
			
			
			</div>
		  
		</div>
	  <br>
	<div>
		<button type="button" class="btn btn-primary btn-lg btn-block" onclick = "window.location.href='PORrcv.php'"><span class="glyphicon glyphicon-log-out"></span>  Back to Menu</button>
	</div>
</div>
</form>
<div id="preloader">
    <div class="caviar-load">
		</div>
</div>
<div id="myModal" class="modal" style = "text-align:center;padding:30px;">
  <!-- Modal content -->
  <div class="modal-content">
    <div class="modal-header">
      <span class="close" onclick = "closeaddmod('myModal')">&times;</span>
      <h4><b><span class="glyphicon glyphicon-user"></span> Add New User</b></h4>
    </div>
    <div class="modal-body ">
		<form method = "POST" Action = "usermaintenance/adduser.php">
		<div class = "row ">
		<div class="col-xs-12">
				<label for="ex1">EE no</label>
				<input maxlength="10" onkeypress="if ( isNaN(this.value + String.fromCharCode(event.keyCode) )) return false;" required class="form-control text-center" style = "color:black;"  id="EEno" name="EEno"  type="text">
			</div>
			<div class="col-xs-12">
				<label for="ex2">First Name</label>
				<input required class="form-control text-center" style = "color:black;" name="fname"  id="fname"   type="text">
			</div>
			<div class="col-xs-12">
				<label for="ex3">Middle Name</label>
				<input required class="form-control text-center" style = "color:black;" name="mname" id="mname"   type="text">
			</div>
			<div class="col-xs-12">
				<label for="ex2">Last Name</label>
				<input required class="form-control text-center" style = "color:black;"  name="lname"  id="lname"   type="text">
			</div>
			<div class="col-xs-12">
				<label for="ex2">Password</label>
				<input required class="form-control text-center" style = "color:black;" name="pass" id="pass"   type="text">
			</div>
		</div>
		<hr>
		<button type="submit" class="btn btn-primary btn-lg btn-block" name = "btnsaveuser"><span class="glyphicon glyphicon-ok"></span> Save</button>
		</form>
	</div>
  </div>

</div>
<!-- Update Modal content -->
<div id="updateModal" class="modal" style = "text-align:center;padding:30px;">
  <!-- Modal content -->
  <div class="modal-content">
    <div class="modal-header">
      <span class="close" onclick = "closeaddmod('updateModal')">&times;</span>
      <h4><b>-User Details-</b></h4>
    </div>
    <div class="modal-body ">
		<form method = "POST" Action = "usermaintenance/updateuser.php">
		<div class = "row ">
		<div class="col-xs-12">
			<input class="form-control text-center" style = "color:black;"  id="uuser_id" name="uuser_id"  type="hidden">
				<label for="ex1">EE no</label>
				<input class="form-control text-center"  style = "color:black;"  id="uEEno" name="uEEno"  type="text">
			</div>
			<div class="col-xs-12">
				<label for="ex2">First Name</label>
				<input class="form-control text-center" style = "color:black;" name="ufname"  id="ufname"   type="text">
			</div>
			<div class="col-xs-12">
				<label for="ex3">Middle Name</label>
				<input class="form-control text-center" style = "color:black;" name="umname" id="umname"   type="text">
			</div>
			<div class="col-xs-12">
				<label for="ex2">Last Name</label>
				<input class="form-control text-center" style = "color:black;"  name="ulname"  id="ulname"   type="text">
			</div>
			<div class="col-xs-12">
				<label for="ex2">Enter New Password</label>
				<input class="form-control text-center" style = "color:black;" name="upass" id="upass"   type="password">
			</div>
		</div>
		<hr>
		<button type="submit" class="btn btn-primary btn-lg btn-block" name = "btnupdateuser">Update Info</button>
		</form>
	</div>
  </div>

</div>
<!-- Delete modal -->
<div id="delModal" class="modal" style = "text-align:center;padding:30px;">
  <!-- Modal content -->
  <div class="modal-content">
    <div class="modal-header">
      <span class="close" onclick = "closeaddmod('delModal')">&times;</span>
      <h4><b>-Remove User-</b></h4>
    </div>
    <div class="modal-body ">
		<form method = "POST" Action = "usermaintenance/removeuser.php">
		<input class="form-control text-center" style = "color:black;"  id="duser_id" name="duser_id"  type="hidden">
		<h5>Are you sure to remove User? <br> This action cannot be Undone</h5>
		<hr>
		<button type="submit" class="btn btn-danger btn-lg btn-block" name = "btnremoveuser">Yes! Remove User</button>
		</form>
	</div>
</div>

</div>
</body>
	
 <!-- Jquery-2.2.4 js -->
	<script>
	function openaddmod(myModal){
		var modal = document.getElementById(myModal);
		modal.style.display = "block";
	}
	function closeaddmod(myModal){
		var modal = document.getElementById(myModal);
		modal.style.display = "none";
	}
	function myFunction(id,iid) {
	  var x = document.getElementById(iid);
	  if (x.type === "password") {
		x.type = "text";
		document.getElementById(id).className = "glyphicon glyphicon-eye-close";
	  } else {
		x.type = "password";
		document.getElementById(id).className = "glyphicon glyphicon-eye-open";
	  }
	}
	function del_user(id){		
		document.getElementById('duser_id').value = id;
		openaddmod('delModal');
	}
	function up_user(id,eeno,fn,mn,ln,pas){
		document.getElementById('uuser_id').value = id;
		document.getElementById('uEEno').value = eeno;
		document.getElementById('ufname').value = fn;
		document.getElementById('umname').value = mn;
		document.getElementById('ulname').value = ln;
		openaddmod('updateModal');
	}
	</script>
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