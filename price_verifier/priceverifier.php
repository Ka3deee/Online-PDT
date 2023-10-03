
<!DOCTYPE html>
<?php
session_start();
?>
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
	<div class="container text-center">
		<img src="../resources/lcc.jpg" style="width: 90px; height: 70px;">
		<h4 class = "fontTitle">Price Verifier Menu</h4>
    <br>
    <?php 
    //store Code
       if(isset($_SESSION['price_storecode']) ){
          ?><div style = "padding:5px;margin-top:-10px;"class="alert alert-success">
          <strong>Store : <?php echo $_SESSION['price_storecode'];?> - <?php echo $_SESSION['price_storeloc'];?></strong>
          
        </div><?php
       }else{
        ?><div style = "padding:5px;margin-top:-10px;" class="alert alert-warning">
          <strong>Please Set Store Code!</strong>
        </div><?php
       }
       //sbu

       if(isset($_SESSION['Price_sbu']) ){
        ?><div style = "padding:5px;margin-top:-10px;"class="alert alert-success">
        <strong>SBU : <?php echo $_SESSION['Price_sbu'];?></strong>
        
      </div><?php
     }else{
      ?><div style = "padding:5px;margin-top:-10px;" class="alert alert-warning">
        <strong>Please Set SBU!</strong>
      </div><?php
     }
    ?>
		
    <button type="button" class="btn btn-primary  btn-block" onclick = "window.location.href='setstore.php'" >Set Store  </button>
    <button type="button" class="btn btn-primary  btn-block" onclick = "window.location.href='setSBU.php'" >Set SBU  </button>
    <?php 
    //store Code
       if(isset($_SESSION['price_storecode']) and isset($_SESSION['Price_sbu']) ){
          ?><button type="button" class="btn btn-primary  btn-block" onclick = "window.location.href='price_scan.php'" >Scan Barcode</button><?php
       }else{
        ?><button type="button" class="btn btn-primary  btn-block" onclick = "alert('Please Set Store or Sbu First, Thank you');" >Scan Barcode</button><?php
       }
    ?>
		<button type="button" class="btn btn-primary  btn-block" onclick = "window.location.href='../smr.php'"><span class="glyphicon glyphicon-log-out"></span> Back to Menu</button>

	</div>
</div>

<div id="preloader">
        <div class="caviar-load"></div>
</div> 
</body>
    <script>
      function check_settings(){   
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