
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
  <style>
	.loader {
	float:left;
	border: 5px solid #f3f3f3;
	border-radius: 50%;
	border-top: 5px solid #3498db;
	width: 30px;
	height: 30px;
	-webkit-animation: spin 2s linear infinite; /* Safari */
	animation: spin 2s linear infinite;
	
	}

	/* Safari */
	@-webkit-keyframes spin {
	0% { -webkit-transform: rotate(0deg); }
	100% { -webkit-transform: rotate(360deg); }
	}

	@keyframes spin {
	0% { transform: rotate(0deg); }
	100% { transform: rotate(360deg); }
	}
  </style>
</head> 
<body>
<div class="container">
  <div class="container-fluid text-center">
		<img src="../resources/lcc.jpg" style="width: 90px; height: 70px;">
		<h4>RTV Releasing : Set Server IP</h4>
		<h5 class="semi-visible">v2.0.0</h5>
    <br><br>
    <label for="txtserverip">IP Address</label>
    <div class="form-group">
      <input value = "<?php if(isset($_SESSION['rtv_server_ip']) ){ echo $_SESSION['rtv_server_ip']; } ?>"  maxlength="20" size="20" style = "text-align:center !important;"  class="form-control input-lg" name="txtserverip"  id="txtserverip" type="text" autofocus >			
        <div id = "loadingalert" style = "display:none;font-size:1.5em;" class="alert alert-info">
          <div class="loader"></div> <strong style = "margin-left:10px;">Please Wait.. Testing Connection To Server..</strong> 
        </div>
    </div>
    <br>
    <div>
      <button type="button" class="btn btn-primary btn-lg btn-block" onclick = "CheckIP()" name = "btnsetip" id = "btnsetip" ><span class="glyphicon glyphicon-ok"></span> Set</button>
      <button type="button" class="btn btn-primary btn-lg btn-block" onclick = "window.location.href='server_settings.php'"><span class="glyphicon glyphicon-log-out"></span> Back</button>
    </div>
  </div>
</div>

<div id="preloader">
    <div class="caviar-load">
</div>
	
</body>
    <script>
      function CheckIP(){
        var ip = document.getElementById('txtserverip').value;
        if(ip == ""){
          alert("Field Cannot Be Empty");
          return 0;
        }
        var response;
        const xhttp = new XMLHttpRequest();
        xhttp.onload = function() {
          response =  this.responseText;			
          //alert(response);
          document.getElementById('loadingalert').style = "display:none";
          if(response == "noconnection"){	
            alert("Connection Failed, Please input a Valid Server IP");
            location.reload();			
          }else{
            alert("Connection Successful");
            document.getElementById('btnsetip').disabled = false;
            location.reload();
          }			
        }
        document.getElementById('btnsetip').disabled = true;
        document.getElementById('loadingalert').style = "display:block";
        xhttp.open("GET", "checkip.php?ip="+ip);
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