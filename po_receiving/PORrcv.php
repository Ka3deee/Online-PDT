
<!DOCTYPE html>
<!--
  Enhance By : Rainier Barbacena
  Date : June 19, 2023
!-->
<?php
session_start();
?>
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

  <script src="../js/jquery/jquery-2.2.4.min.js"></script>
    <script src="../js/bootstrap/bootstrap.min.js"></script>
  <!---   Content Styles -->
  <link href="../css/modify.css" rel="stylesheet">
  <link href="../css/addedcss.css" rel="stylesheet">
  <link href="../mycss.css" rel="stylesheet">
</head>  
<body >
	<div class="container-fluid text-center">
		<img src="../resources/lcc.jpg" style="width: 90px; height: 70px;">
		<h4>PO Receiving</h4>
		<h5 class="semi-visible">v2.0.0</h5>
    <br><br>
        <div class="row">
        <?php
          if ($_SERVER["REQUEST_METHOD"] === "POST") {
            // Check if a file was uploaded successfully
            if (isset($_FILES["file"]) && $_FILES["file"]["error"] == UPLOAD_ERR_OK) {
                $file = $_FILES["file"]["tmp_name"];

                // Read the uploaded file
                $lines = file($file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

                // Establish a connection to the MySQL database
                $servername = "localhost";
                $username = "root";
                $password = "";
                $database = "porcv_db";

                $conn = new mysqli($servername, $username, $password, $database);

                // Check the database connection
                if ($conn->connect_error) {
                    die("Connection failed: " . $conn->connect_error);
                }
                // Process and insert the data into the database
                foreach ($lines as $line) {
                  $data = explode(",", $line);
                  $ref = $data[0];
                  $rcvqty = $data[15];
                  $Ponumb = $data[2];
                  $Inumber = $data[3];
                  $expiredate = $data[17];

                  $sql = "UPDATE `po_transfers` SET `rcvqty` = '$rcvqty', `expiredate` = '$expiredate' WHERE `refno` = '$ref' AND `Ponumb` = '$Ponumb' AND `Inumber` = '$Inumber'";
                  $result = $conn->query($sql);
                  if ($result !== TRUE) {
                      echo "Error updating record: " . $conn->error;
                  }
                }
              echo '<div class="col-xs-12">
                <div class="msg fade-out success notif" id="msg">
                  <span> PO Data uploaded successfully !</span>
                </div>
              </div>';

            } else {
              echo '<div class="col-xs-12">
                <div class="msg fade-out error notif" id="msg">
                  <span> Error uploading PO Data </span>
                </div>
              </div>';
            }
          }
          ?>  
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
		 <button type="button" class="btn btn-primary  btn-block" onclick = "window.location.href='userID.php'" ><span class="glyphicon glyphicon-user"></span> Set User ID </button>
		 </div>	
		 <div class="col-xs-6" style = "padding-bottom:5px;">
		 <button type="button" class="btn btn-primary  btn-block" onclick = "window.location.href='setstore.php'" ><span class="glyphicon glyphicon-home"></span> Set Store  </button>
		</div>
		<?php 
      //store Code
        if(isset($_SESSION['Storecode']) ){
            ?><div class="col-xs-12" style = "padding-bottom:5px;">
    <button type="button" class="btn btn-primary  btn-block" 
    onclick = "checkadmin()"
    ><span class="glyphicon glyphicon-user"></span> User Maintenance </button>
  </div><?php
        }else{
          ?><div class="col-xs-12" style = "padding-bottom:5px;">
    <button type="button" class="btn btn-primary btn-block" 
    onclick = "alert('Please Set Store Code')"
    ><span class="glyphicon glyphicon-user"></span> User Maintenance </button>
  </div><?php
        }
      ?>
				
	</div>	
      <?php 
    //store Code
       if(isset($_SESSION['Storecode']) and isset($_SESSION['user_id'])  ){
          ?><button type="button" class="btn btn-primary btn-block" onclick = "window.location.href='PODownload.php'" >
          <span class="glyphicon glyphicon-download-alt"></span> Download / Retrieve PO Data</button>
		  <?php
		   if(isset($_SESSION['refno'])){
			   ?><button type="button" class="btn btn-primary  btn-block" onclick = "window.location.href='POScan.php'" ><span class="glyphicon glyphicon-barcode"></span> Scan Items</button><?php
		   }else{
			    ?><button type="button" class="btn btn-primary  btn-block" onclick = "alert('Please Download or Retrieve Data Before Scanning, Thank you');" ><span class="glyphicon glyphicon-barcode"></span> Scan Items</button><?php
		   }
			?>                    
          <?php if(isset($_SESSION['Storecode']) and isset($_SESSION['user_id'])  ) { ?>
            <button type="button" class="btn btn-primary btn-block" id="upload-po-data">
              <span class="glyphicon glyphicon-upload"></span> Upload PO Data
            </button>	
          <?php } ?>  
          <button type="button"  onclick = "window.location.href='POprint.php'" class="btn btn-primary btn-block"><span class="glyphicon glyphicon-print"></span> Print</button>		
      <?php } else {
        ?><button type="button" class="btn btn-primary  btn-block" onclick = "alert('Please Set Store or User ID First, Thank you');" ><span class="glyphicon glyphicon-download-alt"></span> Download / Retrieve PO Data</button>
        <button type="button" class="btn btn-primary  btn-block" onclick = "alert('Please Set Store or User ID First, Thank you');" ><span class="glyphicon glyphicon-barcode"></span> Scan Items</button>
        <button type="button" class="btn btn-primary btn-block" onclick = "alert('Please Set Store or User ID First, Thank you');"><span class="glyphicon glyphicon-upload"></span> Upload PO Data</button>	
        <button type="button"  onclick = "alert('Please Set Store or User ID First, Thank you');" class="btn btn-primary btn-block"><span class="glyphicon glyphicon-print"></span> Print PO</button>		
        <?php
       }
    ?>
	
		<button type="button" class="btn btn-primary btn-block" onclick="window.location.href='../smr.php'"><span class="glyphicon glyphicon-log-out"></span> Exit</button>
    </div>
    <br>
    <div class="text-muted" style="font-size: 12px; text-align: center;">Date updated : 2023 June</div>
    <div id="preloader">
            <div class="caviar-load"></div>
    </div>

  <div class="upload-po-wrapper" style="display: none">
    <form action="PORrcv.php" method="post" class="form-container" id="submit-file" enctype="multipart/form-data">
      <div class="nav-window"></div>
      <div class="upload-files-container">
        <div style="padding:5px; display: none;" class="alert alert-success import-msg">
          <strong>Please select file. </strong>
        </div>
        <input type="file" name="file" class="file-import" id="file-import" accept=".txt">      
        <div class="file-import-wrapper">
          <label for="file-import" class="for-file-input">Select File</label>
          <span class="span-text" id="file-name"></span>
        </div>
        <input type="submit" class="upload-button" value="Upload">
      </div>
    </form>
  </div>

</body>

<script>
// hide the notification message after 3 seconds
setTimeout(function(){
  document.getElementById("msg").style.display = "none";
}, 5000);

</script>
 <!-- Jquery-2.2.4 js -->
	<script>
	function checkadmin(){
		let code = prompt("Enter Administrator Passcode");
		if (code != null) {
			if(code  == '13791379'){
				window.location.href="usermaintenance.php";
			}else{
				alert("Invalid Passcode, Please try again");
			}
    }
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
    <!-- Animate -->
    <script src="../js/animate.js"></script>
</html>