
<!DOCTYPE html>
<?php
session_start();
date_default_timezone_set('Asia/Manila');
include ("opt/redirect.php");
?>
<html lang="en">
<head>
  <title>PDT Application DS : Drirect Receiving</title>
  <meta charset="UTF-8">
    <meta name="description" content="">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <link rel="shortcut icon" href="../../images/favicon.ico"/>
    <link rel="bookmark" href="../../images/favicon.ico"/>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
   <link rel="stylesheet" href="../../bootstrap-3.4.1-dist/css/bootstrap.min.css">
  <!---   Content Styles -->
  <link href="../../mycss.css" rel="stylesheet">
  <style>
  .fontTitle , label{
	  font-size:12pt;
	  font-weight:bold;
  }
  .btn {
    font-weight:bold;
  }
  table thead,table tbody {
    text-align:left;
    font-size:12pt;
	  font-weight:bold;
  }
  table tbody tr:hover {
    background:#2f8a9e;
    cursor:pointer;
    color:white;
  }
  </style>
</head>  
<body >
	<div class="container-fluid text-center">
		<img src="../../resources/lcc.jpg" style="width: 90px; height: 70px;">
    <h5 class = "fontTitle" style = "font-style:italic">Device Name: <?php echo $_SESSION['device_id'];?></h5>
		<h4 class = "fontTitle">Dash Board</h4>
        <hr>
        <div id='popanel' style = 'text-align:left;display:none'>
        <h5 class = "fontTitle" style = "background:#35a8de;padding:10px">Purchase Order No: </h5>
        <input maxlength="18"  onkeypress="if ( isNaN(this.value + String.fromCharCode(event.keyCode) )) return false;" class="form-control input-lg" id="textInput" type="text" autofocus >
        <button type="button" class="btn btn-primary  btn-block btn-lg" onclick = "window.location.href='drt_rcv_b.php'" >PREVIOUS LIST</button>
          <hr>
        </div>
        <div id='polist' style = 'display:block' >
          <label for="ex1"> ~ PO LIST (<label id = 'listcount' >0</label>) ~</label>
          <div class="table-responsive">
          <table class="table table-bordered" >
                    <thead style = "background:#35a8de;">
                    <tr>
                        <th>PO #</th>
                        <th></th>
                        <th></th>        
                    </tr>
                    </thead>
                    <tbody style = "max-height:150px;overflow:auto;" id = "polistdata">
                    <tr>
                        <td></td>
                        <td></td>
                        <td></td>         
                    </tr>
                    
                  </tbody>
          </table>
          </div>
        </div>
        <button type="button" class="btn btn-primary  btn-block btn-md" disabled>Clear List</button>
        <button type="button" class="btn btn-primary  btn-block btn-md" onclick = "viewfn('all')" >View All</button>
        <button type="button" class="btn btn-primary btn-block btn-md" onclick = "viewfn('current_date')">View Current Date</button>

 
		<button type="button" class="btn btn-primary  btn-block btn-md" onclick = "window.location.href='./'"><span class="glyphicon glyphicon-log-out"></span> Back</button>
    </div>
<div id="preloader">
        <div class="caviar-load"></div>
</div>

</body>
	<!-- Page Functions -->
    <script src="js/direct_rcv_dasboard_a.js"></script>
	<!-- Jquery-2.2.4 js -->
    <script src="../../js/jquery/jquery-2.2.4.min.js"></script>
    <!-- Popper js -->
    <script src="../../js/bootstrap/popper.min.js"></script>
    <!-- Bootstrap-4 js -->
    <script src="../../js/bootstrap/bootstrap.min.js"></script>
    <!-- All Plugins js -->
    <script src="../../js/others/plugins.js"></script>
    <!-- Active JS -->
    <script src="../../js/active.js"></script>
</html>