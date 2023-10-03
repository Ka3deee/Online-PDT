
<!DOCTYPE html>
<?php
session_start();
?>
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

  <script src="../js/jquery/jquery-2.2.4.min.js"></script>
    <script src="../js/bootstrap/bootstrap.min.js"></script>
  <!---   Content Styles -->
  <link href="../mycss.css" rel="stylesheet">
  <style>
  .fontTitle{
	  font-size:10pt;
	  font-weight:bold;
  }
  </style>
</head>  
<body >
	<div class="container-fluid text-center">
		<img src="../resources/lcc.jpg" style="width: 90px; height: 70px;">
		<h4 class = "fontTitle">PO Receiving Menu</h4>
		<h5>V1.091622</h5>
        <div class="row">
            <div class="col-xs-6">
                <?php 
                //User ID      
                  if(isset($_SESSION['user_id']) ){
                      ?><div style = "padding:5px;"class="alert alert-success">
                      <strong>User ID: <?php echo $_SESSION['user_id'];?></strong>
                    </div><?php
                  }else{
                    ?><div style = "padding:5px;" class="alert alert-warning">
                      <strong>Set User ID!</strong>
                    </div><?php
                  }
                ?>
            </div>
            <div class="col-xs-6">
              <?php 
              //store Code
                if(isset($_SESSION['refno']) ){
                    ?><div style = "padding:5px;"class="alert alert-success">
                    <strong>Ref #: <?php echo $_SESSION['refno'];?></strong>
                  </div><?php
                }else{
                  ?><div style = "padding:5px;" class="alert alert-warning">
                    <strong>Ref #: N/A</strong>
                  </div><?php
                }
              ?>
            </div>
            <div class="col-xs-12" style = "margin-top:-10px;">
              <?php 
              //store Code
                if(isset($_SESSION['Storecode']) ){
                    ?><div style = "padding:5px;"class="alert alert-success">
                    <strong>Store: <?php echo $_SESSION['Storecode']; ?> - <?php echo $_SESSION['Storecode_loc']; ?> </strong>
                  </div><?php
                }else{
                  ?><div style = "padding:5px;" class="alert alert-warning">
                    <strong>Please Set Store Code!</strong>
                  </div><?php
                }
              ?>
            </div>	
        </div>
	<div class="row" style = "padding-bottom:5px;" >
		 <div class="col-xs-6">
		 <button type="button" class="btn btn-primary  btn-block" onclick = "window.location.href='userID.php'" ><span class="glyphicon glyphicon-user"></span> Set UserID </button>
		 </div>	
		 <div class="col-xs-6" style = "padding-bottom:5px;">
		 <button type="button" class="btn btn-primary  btn-block" onclick = "window.location.href='setstore.php'" ><span class="glyphicon glyphicon-home"></span> Set Store  </button>
		</div>	
		
	</div>	
	 <?php 
    //store Code
       if(isset($_SESSION['Storecode']) and isset($_SESSION['user_id'])  ){
          ?><button type="button" class="btn btn-primary  btn-block" onclick = "window.location.href='PODownload.php'" >Download/Retrieve PO Data</button>
		  <?php
		   if(isset($_SESSION['refno'])){
			   ?><button type="button" class="btn btn-primary  btn-block" onclick = "window.location.href='POScan.php'" >Scan Items</button><?php
		   }else{
			    ?><button type="button" class="btn btn-primary  btn-block" onclick = "alert('Please Download or Retrieve Data Before Scanning, Thank you');" >Scan Items</button><?php
		   }
			?>
          
          <button type="button"  onclick = "window.location.href='POprint.php'" class="btn btn-primary btn-block"><span class="glyphicon glyphicon-print"></span> Print</button>		
          <?php
       }else{
        ?><button type="button" class="btn btn-primary  btn-block" onclick = "alert('Please Set Store or User ID First, Thank you');" >Download//Retrieve PO Data</button>
        <button type="button" class="btn btn-primary  btn-block" onclick = "alert('Please Set Store or User ID First, Thank you');" >Scan Items</button>
        <button type="button"  onclick = "alert('Please Set Store or User ID First, Thank you');" class="btn btn-primary btn-block"><span class="glyphicon glyphicon-print"></span> Print</button>		
        <?php
       }
    ?>
	  
		
		<button type="button" class="btn btn-primary  btn-block" onclick = "window.location.href='../smr.php'"><span class="glyphicon glyphicon-log-out"></span> Back to Menu</button>
    </div>
<div id="preloader">
        <div class="caviar-load"></div>
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