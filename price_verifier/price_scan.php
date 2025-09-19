
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
   <script src="../js/onscan.js"></script>
  <script src="../js/onscan.min.js"></script>
  <!---   Content Styles -->

  <link href="../mycss.css" rel="stylesheet">
  <link href="../css/modify.css" rel="stylesheet">
  <link href="../css/addedcss.css" rel="stylesheet">
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
<body  id = "scanbody" onload = "document.getElementById('textInput').focus();">

<div class="container" style = "padding-top:10px;">
		<div class="row">
		  <div class="col-xs-8">
			<label for="ex1">Barcode</label>
			<input maxlength="18"  onkeypress="if ( isNaN(this.value + String.fromCharCode(event.keyCode) )) return false;"  class="form-control" id="textInput" type="text" autofocus >
			
		  </div>
			 
		  <div class="col-xs-4">
			<label for="ex1"></label>
			<button style = "width:100%;font-size:1em;" type="button" class="btn btn-primary" onclick = "getbarcode(document.getElementById('textInput').value)" >Enter</button>
		  </div>
		  <div class="col-xs-12">
		  <div id = "loadingalert" style = "display:none;font-size:9pt;"  class="alert alert-info">
		  <div class="loader"></div> <strong style = "margin-left:10px;">Please Wait.. Retrieving Data..</strong> 
			</div>
		  </div>
		  
		  <div class="col-xs-12">
		   <div id = "promptalert" style = "display:none;font-size:9pt;" class="alert alert-danger alert-dismissible fade in">
				
				<strong>Sorry! </strong><b id = "prompt_title"></b>
			</div>
		  </div>
		  <div id = "displaypanel">
				<div class="col-xs-5">
					<label for="ex1" style = "font-size:9pt;" >SKU Number</label>
					<input class="form-control" style = "font-size:10pt;color:black;" id="sku" readonly type="text">
				</div>
				<div class="col-xs-7">
					<label for="ex1"style = "font-size:9pt;" >UPC / Barcode</label>
					<input class="form-control" style = "font-size:10pt;color:black;" id="upc" readonly type="text">
				</div>
				<div class="col-xs-12">
					<label for="ex3">Price</label>
					<div class="well well-sm" style= "text-align:center;font-size:2em;color:red;"><b id = "price">Php 0.00</b></div>
				</div>
				<div class="col-xs-12">
					<label for="ex3">Item Description</label>
					<div class="well well-sm" style= "text-align:center;font-size:1em;color:darkblue;"><b id = "desc">None</b></div>
				</div>
			
			</div>
		  </div>
		</div>
	<div class="container text-center">
		<button type="button" class="btn btn-primary btn-block" onclick = "window.location.href='priceverifier.php'"><span class="glyphicon glyphicon-log-out"></span> Back to Menu</button>
	</div>
</div>

<div id="preloader">
  <div class="caviar-load"></div>
</div>
 
</body>

<script>
	var isscanning = false;
		function clear_fields(){
			document.getElementById('textInput').value = "";
			document.getElementById('sku').value = "";
			document.getElementById('upc').value = "";		
			document.getElementById('price').innerHTML = "Php 0.00";
			document.getElementById('desc').innerHTML = "None";
			
		}
	function prompt_alert(content){
		document.getElementById('prompt_title').innerHTML = content;
		document.getElementById('promptalert').style.display = "block";
		setTimeout(
		function(){
			document.getElementById('promptalert').style.display = "none";
		}
		,3000);
	}
	function getbarcode(code){
		isscanning = true;
		document.getElementById('textInput').disabled = true;
		//check if a valid barcode
		if(isNaN(code) || code == "" ){
			prompt_alert('Scanned barcode is invalid item barcode!');
			document.getElementById('loadingalert').style.display = "none";
			clear_fields();
			document.getElementById('textInput').disabled = false;
			document.getElementById('textInput').focus();
			isscanning = false;
			//location.reload();
			return 0;
		}
		//get Sku of barcode
		var sku_response;
		const xhttp = new XMLHttpRequest();
		xhttp.onload = function() {
			sku_response =  this.responseText;			
			document.getElementById('loadingalert').style.display = "none";
			if(sku_response == "price not found"){
				clear_fields();
				document.getElementById('textInput').disabled = false;
				document.getElementById('textInput').focus();				
				prompt_alert('Price Not Found');
				isscanning = false;
				return 0;
			}else{
				document.getElementById('textInput').value = "";
				document.getElementById('displaypanel').innerHTML = sku_response;
				document.getElementById('textInput').disabled = false;
				document.getElementById('textInput').focus();
				isscanning = false;
			}			
		}
		document.getElementById('loadingalert').style.display = "block";
		xhttp.open("GET", "getbarcode.php?barcode="+code);
		xhttp.send();			
	}		
		document.addEventListener("keyup", function onEvent(event) {
            if (event.key == 'Unidentified' || event.key == 'Enter') {
				if(!isscanning){
					getbarcode(document.getElementById('textInput').value);
				}
                
            }				
		});
		onScan.attachTo(document, {
			suffixKeyCodes: [13], // enter-key expected at the end of a scan
			reactToPaste: true, // Compatibility to built-in scanners in paste-mode (as opposed to keyboard-mode)
			onScan: function(sCode, iQty) { // Alternative to document.addEventListener('scan')
				//console.log('Scanned: ' + iQty + 'x ' + sCode); 
				document.getElementById('sku').focus();
				getbarcode(sCode);
				
			},
			onKeyDetect: function(iKeyCode){ // output all potentially relevant key events - great for debugging!
				console.log('Pressed: ' + iKeyCode);

			}
		});
	
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