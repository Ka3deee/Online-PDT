
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
<div class="container" style = "padding-top:10px;text-align:center;">
	<img src="../resources/lcc.jpg" style="width: 120px; height: 100%;">
		<div class="row">
		  <div class="col-xs-12">
			  <label for="ex1">Please Set Store Code</label>
      
        <div class="form-group">
          <input  maxlength="5" size="5" onkeypress="if ( isNaN(this.value + String.fromCharCode(event.keyCode) )) return false;" value = "<?php if(isset($_SESSION['price_storecode']) ){ echo $_SESSION['price_storecode']; } ?>"style = "text-align:center !important;"  class="form-control input-lg" name="txtstorecode"  id="txtstorecode" type="text" autofocus >			
          <?php
            if(isset($_SESSION['price_storecode']) ){
              ?><div id = "promptalert" style = "font-size:1em;" class="alert alert-success alert-dismissible fade in">
              <strong></strong><b ><?php echo $_SESSION['price_storeloc'];?></b>
            </div><?php
          }
          
          ?>
            <div id = "loadingalert" style = "display:none;font-size:1.5em;"  class="alert alert-info">
              <div class="loader"></div> <strong style = "margin-left:10px;">Please Wait.. Checking Store Code..</strong> 
            </div>
        </div>
			 </div>
		  
		</div>
	  <br>
	<div class="container text-center">
		<button type="button" class="btn btn-primary btn-lg btn-block" onclick = "Checkstore()" name = "btnsetstore" id = "btnsetstore" >Set Store Code</button>
		<button type="button" class="btn btn-primary btn-lg btn-block" onclick = "window.location.href='priceverifier.php'"><span class="glyphicon glyphicon-log-out"></span> Back to Menu</button>
	</div>
</div>
<div id="preloader">
        <div class="caviar-load">
		</div>
		
</div>
</body>
    <script>
      function Checkstore(){
        var store = document.getElementById('txtstorecode').value;
        if(store == ""){
          alert("Please Enter Store code");
          return 0;
        }
        var response;
        const xhttp = new XMLHttpRequest();
        xhttp.onload = function() {
          response =  this.responseText;			
          document.getElementById('loadingalert').style = "display:none";
          if(response == "no result"){	
            alert("Invalid store code. Please Try Again");
            location.reload();			
          }else{
           var  storedetails = response.split("-");
            document.getElementById('btnsetstore').disabled = false;
            location.reload();
          }			
        }
        document.getElementById('btnsetstore').disabled = true;
        document.getElementById('loadingalert').style = "display:block";
        xhttp.open("GET", "getstore.php?check_store="+store);
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