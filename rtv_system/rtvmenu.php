
<!DOCTYPE html>
<?php
session_start();
include('connect.php');
?>
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
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
  <link href="../css/rtv.css" rel="stylesheet">
  <!---   Content Styles -->
  <link href="../mycss.css" rel="stylesheet">
  <link href="../css/modify.css" rel="stylesheet">
  <link href="../css/addedcss.css" rel="stylesheet">
  <style>
  .fontTitle{
	  font-size:10pt,
	  font-weight:bold;
  }
  .user{
	  background:red;
  }
  </style>
</head>  
<body >

<div class="container">
  <div class="container-fluid text-center">
		<img src="../resources/lcc.jpg" style="width: 90px; height: 70px;">
		<h4>RTV Releasing : Scan Items</h4>
		<h5 class="semi-visible">v2.0.0</h5>
    <br><br>
    <button type="button" class="btn btn-primary  btn-block" onclick = "window.location.href='scanboitems.php'" >Scan B.O Items</button>
    <button type="button" class="btn btn-primary  btn-block" onclick = "window.location.href='viewitems.php'" >View B.O Items</button>
    <button type="button" class="btn btn-primary  btn-block" onclick = "uploaddata()" >Upload B.O Items</button>
		<button type="button" class="btn btn-primary  btn-block" onclick = "window.location.href='server_settings.php'"><span class="glyphicon glyphicon-log-out"></span> Back</button>

	</div>
</div>

<div id="preloader">
        <div class="caviar-load"></div>
</div> 
<!-- The Modal alert -->
<div id="myalert" class="modal" style = "text-align:center;padding:30px;">
  <!-- Modal content -->
  <div >
	
    <div class="modal-body">
	<div class="well alert alert-info " style = 'font-size:10pt;'>
	
		<strong id = 'title1'>Upload Success!</strong><br>
    <b id = 'details'></b><br>
    <b id = 'details1'></b><br>
    <b id = 'details3'></b>
		<hr>
		<button type="button" onclick = "window.location.href = 'userID.php';"class="btn btn-default">Ok</button>
	</div>
    </div>
  </div>
</div>
</body>
    <script>
      function uploaddata(){   
        if (confirm('Upload data to server?. .\n Press Ok to continue!')){
          var xhttp1 = new XMLHttpRequest();
            xhttp1.onload = function() {
              if(this.responseText == "failed"){
                alert('Error While Uploading data. \n Please try again');
                return 0;
              }else{
                var response = this.responseText;
                var myArray = response.split("-");
                var newre = myArray[0].split("/");
                opendalert('Success',newre[0],newre[1],myArray[1]);
                
              }				
            }
          xhttp1.open("GET", "uploaddata.php?upload");
          xhttp1.send();
				}	
      }
      function opendalert(title,details,d1,d2){
      var modal = document.getElementById('myalert');
      document.getElementById('title1').innerHTML = title
      document.getElementById('details').innerHTML = details;
      document.getElementById('details1').innerHTML = d1;
      document.getElementById('details3').innerHTML = d2;
        modal.style.display = "block";
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