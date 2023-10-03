
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
  <script src="../js/onscan.js"></script>
  <script src="../js/onscan.min.js"></script>
  <!---   Content Styles -->
  <link href="../mycss.css" rel="stylesheet">
  <link href="../css/modify.css" rel="stylesheet">
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
	.col{
		padding 0;
	}
  </style>
</head>  
<body onload = "document.getElementById('textInput').focus();">

<div class="container text-center">
	
	<img src="../resources/lcc.jpg" style="width: 90px; height: 70px;">
	<h4>PO Receiving : Scan Items</h4>
	<h5 class="semi-visible">v2.0.0</h5>
    <br>
		<div class="row">
		  <div class="col-xs-8">
			<label for="textInput">Barcode</label>
			<input maxlength="18"  onkeypress="if ( isNaN(this.value + String.fromCharCode(event.keyCode) )) return false;" class="form-control" id="textInput" type="text" autofocus >
			
		  </div>
		
		 
		  <div class="col-xs-4">
			<label style="opacity: 0;">Enter</label>
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
		  <div class="col-xs-12" id = "duplicateSKU">
			
		  </div>
		  <div id = "displayitemskuref">
		  <input type = "hidden" class="form-control" value = "" id="crcvqty"  type="text">
         <input type = "hidden" class="form-control" value = "" id="Expqty"  type="text">
         <input type = "hidden" class="form-control" value = "" id="expiry"  type="text">
			<div class="col-xs-6">
				<label for="ex1">SKU Number</label>
				<input class="form-control" style = "color:black;"  id="sku" readonly type="text">
			</div>
			<div class="col-xs-6">
				<label for="ex2">Ref Number</label>
				<input class="form-control" style = "color:black;"  id="ref"  readonly type="text">
			</div>
			<div class="col-xs-12">
				<label for="ex3">Item Description</label>
				<textarea  disabled class="form-control" style = "color:black;" rows="2" id="desc"></textarea>
			</div>
			<div class="col-xs-12">
			<label for="expiry2">Expiration Date</label>
				<input class="form-control" id="expiry2" type="date">
			</div>
			<div class="col-xs-6">
				<label for="ex1" style = "font-size:10pt;" >STD Pack</label>
				<input  disabled class="form-control" id="std" type="text">
			</div>
			<div class="col-xs-6">
			<label for="qty">Quantity</label>
			<input onkeypress="if (isNaN(this.value + String.fromCharCode(event.keyCode))) return false;"  class="form-control" id="qty" type="number">
		  </div>
		  </div>
		  
		  <div class="col-xs-12">
		  <label for="ex2"></label>
			 <button style = "width:100%;font-size:10pt;" type="button" class="btn btn-primary" id = "btnaccept" onclick = "addtoItem(document.getElementById('qty').value,document.getElementById('crcvqty').value,document.getElementById('Expqty').value,document.getElementById('expiry').value,document.getElementById('ref').value,document.getElementById('sku').value)" ><span class="glyphicon glyphicon-ok"></span> Accept</button>
		  </div>
		</div>
	  <br>
	<div>
		<button type="button" class="btn btn-primary btn-block" onclick = "window.location.href='PORrcv.php'"><span class="glyphicon glyphicon-log-out"></span>  Back to Menu</button>

	</div>
</div>

<div id="preloader">
        <div class="caviar-load"></div>
</div>

 
</body>
	<script>
		function checktolerable(dtp1,dtp2){
			if(dtp2 > dtp1){
				alert("Expiry date earlier than tolerable date.");
			}
		}
		function addtoItem(input_qty,current,exp_qty,withexpiry,po,sku){
			var expiration = "";
			
			document.getElementById('btnaccept').disabled = true;
			if(document.getElementById('sku').value == "" || document.getElementById('ref').value == "" ){
				prompt_alert('Please Scan item Thank you!');
				document.getElementById('btnaccept').disabled = false;				
				return 0;
			}
			//alert(exp_qty);
			if((parseInt(input_qty) + parseInt(current)) > exp_qty){
				prompt_alert('Receive quantity will exceed the expected quantity');				
				clear_fields();
				document.getElementById('btnaccept').disabled = false;
				return 0;
			}
			if(withexpiry == 'yes'){
				expiration = document.getElementById('expiry2').value;
				
			}else{
				expiration = "none";
			}
			const xhttp = new XMLHttpRequest();
			xhttp.onload = function() {
				if(this.responseText == "inserted"){
					clear_fields();
					document.getElementById('textInput').focus();
					document.getElementById('btnaccept').disabled = false;
				}else{
					prompt_alert('Error while inserting Quantity.');	
					clear_fields();
					document.getElementById('btnaccept').disabled = false;
					document.getElementById('textInput').focus();
				}
				
			}
			xhttp.open("GET", "addtoitem.php?quantity="+input_qty+"&ponumber="+po+"&sku="+sku+"&withexpiry="+withexpiry+"&expiration="+expiration);
			xhttp.send();
			
		}
		function clear_fields(){
			document.getElementById('textInput').value = "";
			document.getElementById('sku').value = "";
			document.getElementById('ref').value = "";
			document.getElementById('desc').innerHTML = "";
			document.getElementById('std').value = "";
			document.getElementById('qty').value = "";
			document.getElementById('duplicateSKU').innerHTML = "";
			
		}
	function getSkudata(po,sku){
		const xhttp = new XMLHttpRequest();
		xhttp.onload = function() {
			//alert(this.responseText);
			document.getElementById('loadingalert').style.display = "none";
			if(this.responseText == "exceeds"){
				prompt_alert('Receive quantity is already equal to the expected quantity');
				clear_fields();
				document.getElementById('textInput').focus();
				return 0;
			}else{
				document.getElementById("duplicateSKU").innerHTML = "";
				document.getElementById("displayitemskuref").innerHTML =this.responseText;
				
				document.getElementById('expiry2').focus();
			}
			
		}
		document.getElementById('loadingalert').style.display = "block";
		xhttp.open("GET", "getsku.php?skunoduplicate="+sku+"&ponumber="+po);
		xhttp.send();
	}
	function check_duplicate(sku){
		const xhttp = new XMLHttpRequest();
		xhttp.onload = function() {
			
			if(this.responseText == "no duplicate"){
				//alert("Response: "+this.responseText);
				getSkudata('none',sku);	
			}else{
				document.getElementById('loadingalert').style.display = "none";
				document.getElementById("duplicateSKU").innerHTML =this.responseText;
			}			
		}
		xhttp.open("GET", "getsku.php?sku="+sku);
		xhttp.send();
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
		//clear fields		
		//check if a valid barcode
		if(isNaN(code)){
			prompt_alert('Scanned barcode is invalid item barcode!');
			clear_fields();
			document.getElementById('textInput').focus();
			//location.reload();
			return 0;
		}
		//get Sku of barcode

		//
		if(code.charAt(0) === '0'){
		    code = code.substring(1);
		}

		var sku_response;
		const xhttp = new XMLHttpRequest();
		xhttp.onload = function() {
			sku_response =  this.responseText;
			if(sku_response == "not found"){
				document.getElementById('loadingalert').style.display = "none";
				clear_fields();
				document.getElementById('textInput').focus();				
				document.getElementById('duplicateSKU').innerHTML = "";
				prompt_alert('There is no item having the scanned barcode! Invalid UPC.');
				return 0;
			}else{
				document.getElementById('promptalert').style.display = "none";
				//check if duplicate
				check_duplicate(sku_response);	
			}			
		}
		document.getElementById('loadingalert').style.display = "block";
		xhttp.open("GET", "getsku.php?barcode="+code);
		xhttp.send();
		
			
		}	 			
		document.body.addEventListener("keyup", function onEvent(event) {
			
            if (event.key == 'Unidentified' || event.key == 'Enter') {
				
                getbarcode(document.getElementById('textInput').value);
            }				
		});
		
		onScan.attachTo(document, {
			suffixKeyCodes: [13], // enter-key expected at the end of a scan
			reactToPaste: false, // Compatibility to built-in scanners in paste-mode (as opposed to keyboard-mode)
			onScan: function(sCode, iQty) { // Alternative to document.addEventListener('scan')
				//console.log('Scanned: ' + iQty + 'x ' + sCode); 
				document.getElementById('sku').focus();
				getbarcode(document.getElementById('textInput').value);
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