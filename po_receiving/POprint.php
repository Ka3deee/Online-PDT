
<!DOCTYPE html>
<?php
session_start();
?>
<html lang="en">
<head>
<title>PDT Application : PO Receiving</title>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<link rel="shortcut icon" href="../images/favicon.ico"/>
    <link rel="bookmark" href="../images/favicon.ico"/>
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<link rel="stylesheet" href="../bootstrap-3.4.1-dist/css/bootstrap.min.css">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
	<link href="../mycss.css" rel="stylesheet">
  <link href="../css/modify.css" rel="stylesheet">
</head>

<body onload="retrievedata()">
	<div class="container text-center">
		
		<img src="../resources/lcc.jpg" style="width: 90px; height: 70px;">
		<h4>PO Receiving : Print PO</h4>
		<h5 class="semi-visible">v2.0.0</h5>
		<br>
	</div>
	<div class="container">
		<div style="margin-bottom: 2rem;">
			<label for="ex3">Po List</label>
			<textarea disabled class="form-control"  id="polist" rows="7"></textarea>
		</div>

		<div>
			<label for="ex1" id="information"></label>
			<div class="progress" id="progressbar"></div>
		</div>

		<div class="text-center">
			<iframe id="loadarea" style="display:none;width:100%;height:100px;"></iframe>
			<div class="row">
				<div class="col-md-6">
					<button type="button" class="btn btn-primary btn-block"
						<?php echo isset($_SESSION['user_id']) ? 'onclick="printPO(true)"' : 'onclick="alert(\'Please Set User ID First, Thank you\')"'; ?>
					><span class="glyphicon glyphicon-download-alt"></span> Download PO</button>
				</div>
				<div class="col-md-6">
					<button type="button" class="btn btn-primary btn-block"
						<?php echo isset($_SESSION['user_id']) ? 'onclick="printPO()"' : 'onclick="alert(\'Please Set User ID First, Thank you\')"'; ?>
					><span class="glyphicon glyphicon-print"></span> Print</button>
				</div>
			</div>
			<div style="margin-top: 5px; margin-bottom: 20px;">
				<button type="button" class="btn btn-primary btn-block" data-toggle="modal" data-target="#addIp" > Set Printer IP</button>
				<button type="button" class="btn btn-primary btn-block" onclick="window.location.href='PORrcv.php'"><span class="glyphicon glyphicon-log-out"></span> Back</button>
			</div>
			
		</div>
	</div>

	<div id="preloader">
		<div class="caviar-load"></div>
	</div>
	<!-- Modal -->
	<div class="modal fade" id="addIp" role="dialog">
		<div class="modal-dialog modal-sm">
		<div class="modal-content">
			<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal">&times;</button>
			<h4 class="modal-title">Printer IP Address</h4>
			</div>
			<div class="modal-body">
			<label for="ex1">Set Printer Ip Address</label>

			<input  maxlength="20" size="20"  value = "<?php if(isset($_SESSION['printer_ip']) ){ echo $_SESSION['printer_ip']; } ?>"style = "text-align:center !important;"  class="form-control input-lg" name="txtipadd"  id="txtipadd" type="text" autofocus >			
			</div>
			<div class="modal-footer">
			<button type="button" class="btn btn-primary " onclick = "setprinter(document.getElementById('txtipadd').value)" id = "btnsetstore" name = "btnsetstore">Set IP</button>
			<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
			</div>
		</div>
		</div>
	</div>
</body>

<script>
	function retrievedata(){
		document.getElementById('information').innerHTML = "Retrieving Data. Please Wait..."
		document.getElementById('loadarea').src = 'progressbar.php?get_to_print'
	}
	
	function show_result(data){
		var lines = data.split('<br>');

		for(var i=0; i < lines.length - 1; i++){				
			document.getElementById('polist').innerHTML = document.getElementById('polist').innerHTML + lines[i] + '\n';
		}
	}
	function setprinter(ip){
		const xhttp = new XMLHttpRequest();
		xhttp.onload = function() {
			if(this.responseText == "inserted"){
				alert("Saved!");
				location.reload();				
			}
		}
		xhttp.open("GET", "saveprinterIP.php?ip="+ip);
		xhttp.send();
	}
	function printPO(download = false){
		if (!confirm("Are you sure you want to "+ (download ? "download" : "print") +" all PO?")) return
		document.getElementById("information").innerHTML = "Processing. Please Wait..."
		document.getElementById("loadarea").src = "rhemTests.php?printReceiver&download=" + download
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
