
<!DOCTYPE html>
<?php session_start(); ?>
<html lang="en">
<head>
  <title>PDT Application : RTV Releasing</title>
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
  <link href="../css/addedcss.css" rel="stylesheet">
</head>
<body>
<div class="container">
  <div class="container-fluid text-center">
		<img src="../resources/lcc.jpg" style="width: 90px; height: 70px;">
		<h4>RTV Releasing : Set User ID</h4>
		<h5 class="semi-visible">v2.0.0</h5>
    <br><br>
		<div class="row">
		  <div class="col-xs-12">
        <label for="textUserID">EE Number </label>
        <input required value = "<?php if(isset($_SESSION['rtv_userid']) ){ echo $_SESSION['rtv_userid']; } ?>"style = "text-align:center !important;"  class="form-control input-lg" name="textUserID" id="textUserID" type="text" autofocus >			
		  </div>
      <div class="col-xs-12">
        <label for="textpass">Password </label>
        <input required value = "<?php if(isset($_SESSION['rtv_pass']) ){ echo $_SESSION['rtv_pass']; } ?>"style = "text-align:center !important;"  class="form-control input-lg" name="textpass" id="textpass" type="password" autofocus >			
		  </div>
		</div>
    <div id = "loadingalert" style = "display:none;font-size:10pt;"  class="alert alert-info">
      <div class="loader"></div> <strong style = "margin-left:10px;">Please Wait.. Validating User..</strong> 
    </div>
	  <br>
	<div>
		<button type="button" class="btn btn-primary btn-lg btn-block" onclick = "Checkuser()" id = "btnsetuser"><span class="glyphicon glyphicon-ok"></span> Set</button>
		<button type="button" class="btn btn-primary btn-lg btn-block" onclick = "window.location.href='server_settings.php'"><span class="glyphicon glyphicon-log-out"></span> Back</button>
	</div>
</div>
<div id="preloader">
    <div class="caviar-load">
		</div>
</div>
</body>
<script>
      function Checkuser(){
       
        var eenum = document.getElementById('textUserID').value;
        var password = document.getElementById('textpass').value;
        if (eenum == "" || password == ""){
          alert("Fields Cannot be Empty, Please provide input..");
          return 0;
        }
        var response;
        const xhttp = new XMLHttpRequest();
        xhttp.onload = function() {
          response =  this.responseText;			
          document.getElementById('btnsetuser').disabled = false;
          document.getElementById('loadingalert').style.display = "none";
          if(response == "notfound"){	
            alert("User is not registered!");
            location.reload();			
          }else{
            alert("User Accepted!");
            alert("Batch Number: " + response);           
            window.location.href = 'server_settings.php';
          }			
        }
        document.getElementById('btnsetuser').disabled = true;
        document.getElementById('loadingalert').style.display = "block";
        xhttp.open("GET", "userfunction.php?eenum="+eenum+"&pass="+password);
        xhttp.send();
      }
    </script>
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