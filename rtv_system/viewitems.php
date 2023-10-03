
<!DOCTYPE html>
<?php session_start(); 
   include 'connect.php';?>
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
<div  class="container">  
	<div class="container-fluid text-center">
		<img src="../resources/lcc.jpg" style="width: 90px; height: 70px;">
		<h4>RTV Releasing : Scan</h4>
		<h5 class="semi-visible">v2.0.0</h5>
		<br><br>
    <div class="panel panel-primary">
        <div class="panel-heading" style = "overflow:auto;">
        <?php
						//count item
						$pdtuser = $_SESSION['eenum'];
						$batchno = $_SESSION['batchno'];
            $getline_count = "SELECT count(*) as cnt, sum(qty) as qty1 from tblScanned where isuploaded = 0 and pdtuser = '$pdtuser' and batchno ='$batchno'";						
            $line_result = $conn->query($getline_count);
            if($line_result->rowCount() > 0){
                while($line_row = $line_result->fetch(PDO::FETCH_ASSOC)){
                  ?>
                   <b class="pull-left">Item Count : <?php echo $line_row["cnt"]; ?></b>
                    <b class="pull-right">Total Quantity: <?php  if($line_row["qty1"] == null){echo 0;}else{ echo $line_row["qty1"];} ; ?> </b>
                  <?php
                  }
            }else{
                ?>
                <b class="pull-left">Item Count : 0</b>
                <b class="pull-right">Total Quantity: 0 </b>
                <?php
						}
        ?> 
        </div>
        <div class="panel-body text-left">
        <h4>Summary list</h4>
        <div class="table-responsive">
         <table class="table table-bordered" style = "font-size:9pt;" >
                  <thead>
                  <tr>
                      <th>Vendor Code</th>
                      <th>Sku Count</th>
                      <th>Total Quantity</th>           
                  </tr>
                  </thead>
                  <tbody >
                  <?php
                    //get summary list
                 
                    $pdtuser = $_SESSION['eenum'];
                    $batchno = $_SESSION['batchno'];                   
                    $counter = 0;
                    $Get_query = "select asnum,count(inumber) as x1, sum(qty) as x2 from tblscanned where isuploaded = 0 and pdtuser = '$pdtuser' and batchno = '$batchno' group by asnum";						
                    $result = $conn->query($Get_query);
                    if($result->rowCount() > 0){
                          while($row = $result->fetch(PDO::FETCH_ASSOC)){
                              ?>
                              <tr>
                                <td><?php echo $row["asnum"];?></td>
                                <td><?php echo $row["x1"];?></td>
                                <td><?php echo $row["x2"];?></td>          
                              </tr>
                              <?php
                          }
                      }else{
                        ?>
                        <tr>
                          <td colspan = "6"> No Item Found</td>          
                        </tr>
                        <?php
                      }
                    
                    ?>
                </tbody>
        </table>
        </div>
        <h4>Detailed list</h4>
        <div class="table-responsive">
         <table class="table table-bordered" style = "font-size:8pt;" >
                  <thead>
                  <tr>
                      <th>SKU</th>
                      <th>Item Description</th>
                      <th>UPC</th>
                      <th>Qty</th>
                      <th>classcode</th>
                      <th>Vendor Code</th>
                  </tr>
                  </thead>
                  <tbody>
                  <?php
                    //get summary list
                    $pdtuser = $_SESSION['eenum'];
                    $batchno = $_SESSION['batchno'];                   
                    $counter = 0;
                    $Get_query1 = "SELECT inumber, idescr, iupc,qty, ccode,asnum from tblScanned  where isuploaded = 0 and pdtuser = '$pdtuser' and batchno = '$batchno'";						
                    $result1 = $conn->query($Get_query1);
                    if($result1->rowCount() > 0){
                          while($row1 = $result1->fetch(PDO::FETCH_ASSOC)){
                              ?>
                              <tr>
                                <td><?php echo $row1["inumber"];?></td>
                                <td><?php echo $row1["idescr"];?></td>
                                <td><?php echo $row1["iupc"];?></td>
                                <td><?php echo $row1["qty"];?></td> 
                                <td><?php echo $row1["ccode"];?></td> 
                                <td><?php echo $row1["asnum"];?></td>           
                              </tr>
                              <?php
                          }
                      }else{
                        ?>
                        <tr>
                          <td colspan = "6"> No Item Found</td>          
                        </tr>
                        <?php
                      }
                    
                    ?>
                </tbody>
        </table>
        </div>

        </div>
        
  </div>
  <hr>
  <button type="button" class="btn btn-primary btn-block" onclick = "window.location.href='rtvmenu.php'"><span class="glyphicon glyphicon-log-out"></span> Back to Menu</button>

</div>
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