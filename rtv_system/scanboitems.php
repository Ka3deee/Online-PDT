
<!DOCTYPE html>
<?php
session_start();
?>
<html lang="en" oncontextmenu="return false" onselectstart="return false" ondragstart="return false" >
<head>
  <title>PDT Application : RTV Releasing</title>
  <meta charset="UTF-8">
    <meta name="description" content="">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
	<link rel="shortcut icon" href="../images/favicon.ico"/>
    <link rel="bookmark" href="../images/favicon.ico"/>
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <link rel="stylesheet" href="../bootstrap-3.4.1-dist/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
   <script src="../js/onscan.js"></script>
  <script src="../js/onscan.min.js"></script>
  <!---   Content Styles -->
  <link href="../mycss.css" rel="stylesheet">
  <link href="../css/modify.css" rel="stylesheet">
  <link href="../css/addedcss.css" rel="stylesheet">
</head> 
<body   id = "scanbody" onload = "document.getElementById('textInput').focus()">
<div  class="container">  
	<div class="container-fluid text-center">
		<img src="../resources/lcc.jpg" style="width: 90px; height: 70px;">
		<h4>RTV Releasing : Scan</h4>
		<h5 class="semi-visible">v2.0.0</h5>
		<br><br>
		<div class="row">
		  <div class="col-xs-8">
			<label for="ex1">Barcode / UPC</label>
			<input maxlength="18"  onkeypress="if ( isNaN(this.value + String.fromCharCode(event.keyCode) )) return false;"  class="form-control" id="textInput" type="text" autofocus >		
		  </div>	 
		  <div class="col-xs-4">
			<label for="enter-btn" style="opacity: 0;">Enter</label>
			<button style = "width:100%;font-size:1em;" type="button" class="btn btn-primary" onclick = "getbarcode(document.getElementById('textInput').value)" id="enter-btn">Enter</button>
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
				<div class="col-xs-12">
					<label for="ex3">Item Description</label>
					<input class="form-control" id="desc" type="hidden"  readonly >
					<textarea  disabled class="form-control" rows="2" id="desc_text"></textarea>
				</div>
				<div class="col-xs-12">
					<div class="form-group">
					<label for="sel1"> Vendor Code</label>
					<input class="form-control" id="txtvendor" type="text"  readonly >		
					</div>
				</div>
				<div class="col-xs-6">
					<label for="sel1" >Class Code</label>
					<select class="form-control" id="class_code">
					<?php
                        include("connect.php");
                        $Get_classcode = "SELECT * FROM `tbl_class_code`";						
                        $classresult = $conn->query($Get_classcode);
                        if($classresult->rowCount() > 0){
							$ct = 0;
                            while($classrow1 = $classresult->fetch(PDO::FETCH_ASSOC)){
                                ?><option value = "<?php echo $ct.'_'.$classrow1["code"].'_'.$classrow1["codedes"];?>"><?php echo $classrow1["code"].'_'.$classrow1["codedes"];?></option><?php
                            $ct++;
                            }
                        }
                        ?> 
					</select>
				</div>
				<div class="col-xs-6">
					<div class="form-group">
					<label for="sel1">Quantity</label>
					<input maxlength="18"  onkeypress="if ( isNaN(this.value + String.fromCharCode(event.keyCode) )) return false;"  class="form-control" id="txtqty" type="text"  >
					<input  class="form-control" id="txtid" type="hidden" >
					<input  class="form-control" id="txtaction" type="hidden" value = "add" >
					</div>
				</div>
				<div class="col-xs-12" style = "font-size:9pt;">
						<?php
						//count item
						$pdtuser = $_SESSION['eenum'];
						$batchno = $_SESSION['batchno'];
                        $getline_count = "SELECT count(*) as cnt, sum(qty) as qty1 from tblScanned where isuploaded = 0 and pdtuser = '$pdtuser' and batchno =$batchno";						
                        $line_result = $conn->query($getline_count);
                        if($line_result->rowCount() > 0){
                            while($line_row = $line_result->fetch(PDO::FETCH_ASSOC)){
								?>
								<label for="ex2">Line Count: <?php echo $line_row["cnt"]; ?></label><br>
								<label for="ex2">Total Qty: <?php  if($line_row["qty1"] == null){echo 0;}else{ echo $line_row["qty1"];} ; ?></label>
								<?php
                            }
                        }else{
							?>
							<label for="ex2">Line Count: 0</label><br>
							<label for="ex2">Total Qty: 0</label>
							<?php
						}
                        ?>
				</div>
				<div class="col-xs-12">
				<button style = "width:100%;font-size:12pt;font-weight:bold;" type="button" class="btn btn-primary btn-lg" id = "btnaccept" disabled onclick = "alert('Please Scan item')" >Accept B.O</button>
				</div>
			</div>
		  </div>
		<hr>
		<div>
			<button type="button" class="btn btn-primary btn-block" onclick = "window.location.href='rtvmenu.php'"><span class="glyphicon glyphicon-log-out"></span> Back</button>			
		</div>
	</div>
</div>
<div id="preloader">
        <div class="caviar-load"></div>
</div>
<!-- The Modal -->
<div id="myModal" class="modal" style = "text-align:center;padding:30px;">
  <!-- Modal content -->
  <div class="modal-content">
    <div class="modal-header">
      <span class="close" onclick = "window.location.href='scanboitems.php'">&times;</span>
      <h4>-Modify Count-</h4>
    </div>
    <div class="modal-body">
	<input type ='hidden' id = "tol_dupli">
	<input type ='hidden' id = "dup_id">
	<input type ='hidden' id = "myupc">
	<input type ='hidden' id = "myqty">
	<input type ='hidden' id = "myccode">
	<input type ='hidden' id = "myccodei">
	<button type="button" class="btn btn-primary btn-block" onclick = "additem()"><span class="glyphicon glyphicon-plus-sign"></span> Add</button>
	<button type="button" class="btn btn-primary btn-block" onclick = "updateitem(document.getElementById('tol_dupli').value,document.getElementById('dup_id').value,document.getElementById('myupc').value,document.getElementById('myqty').value,document.getElementById('myccode').value,document.getElementById('myccodei').value)"><span class="glyphicon glyphicon-edit"></span> Update</button>
	<button type="button" class="btn btn-primary btn-block" onclick = "deleteitem(document.getElementById('tol_dupli').value,document.getElementById('dup_id').value,document.getElementById('myupc').value)"><span class="glyphicon glyphicon-minus-sign"></span> Delete</button>
    </div>
    <div class="modal-footer">
      <h5>Duplicate Found</h5>
    </div>
  </div>

</div>
<!-- The Modal -->
<div id="mylist" class="modal" style = "text-align:center;padding:30px;">

  <!-- Modal content -->
  <div class="modal-content">
    <div class="modal-header">
      <span class="close" onclick = "window.location.href='scanboitems.php'">&times;</span>
      <h4>-Item list with Duplicates-</h4>
    </div>
    <div class="modal-body">
	<div class="table-responsive">
	<table class="table table-bordered" style = "font-size:8pt;" >
            <thead>
            <tr>
                <th>SKU</th>
                <th>Item Description</th>
                <th>UPC</th>
                <th>Qty</th>
				<th>classcode</th>
				<th>Action</th>
            </tr>
            </thead>
            <tbody id = "duplicatelist" style ="">
           </tbody>
	</table>
	</div>		
    </div>
    <div class="modal-footer">
      <h5>Duplicate Found</h5>
    </div>
  	</div>
</div>
<!-- The Modal alert -->
<div class="modal" id="exampleModal" style = "text-align:center;padding:30px;">
	<div class="modal-content">
		<div class="modal-body" id = "message">
			You have inputted a large quantity!.<br> Press Ok to accept!
		</div>
		<div class="modal-footer">
			<button type="button" 
				id="closeid"
				class="btn btn-secondary" 
				data-dismiss="modal">
				Cancel
			</button>
			<button type="button" 
				id="saveid" 
				class="btn btn-primary">
				Ok
			</button>
		</div>
	</div>
</div>
</body>
	<script>
		document.onkeydown = function(e) {
		if(event.keyCode == 123) {
		return false;
		}
		if(e.ctrlKey && e.shiftKey && e.keyCode == 'I'.charCodeAt(0)){
		return false;
		}
		if(e.ctrlKey && e.shiftKey && e.keyCode == 'J'.charCodeAt(0)){
		return false;
		}
		if(e.ctrlKey && e.keyCode == 'U'.charCodeAt(0)){
		return false;
		}
		}
	var windowWidth = window.screen.width;
	var isscanning = false;
	function additem(){
		if (confirm('YOU ARE ADDING NEW ITEM.\n Press Ok to accept!')){
			document.getElementById('txtqty').value = '1';
			close_modal('myModal');
			document.getElementById('txtqty').focus();
			return 0;
		}	
	}
	function setvalues(id,myupc,myqty,myccode,myccodei){
		document.getElementById('txtqty').focus();
		document.getElementById('textInput').value = myupc;
			document.getElementById('txtqty').value = myqty;
			document.getElementById('txtid').value = id;
			$('#class_code option[value="'+ myccodei+'_'+myccode +'"]').prop('selected', true);	
			close_modal('mylist');
			
	}
	function deleteitem(totaldupli,id,myupc){
		if(totaldupli > 1){
			var xhttp1 = new XMLHttpRequest();
				xhttp1.onload = function() {
					if(this.responseText != "notfound"){
						document.getElementById('duplicatelist').innerHTML = this.responseText;
						openduplicates('mylist');
					}				
				}
			xhttp1.open("GET", "getduplicates.php?duplicate="+myupc +"&action=delete");
			xhttp1.send();
		}else{
				document.getElementById('message').innerHTML = "Are you sure to delete Item? <br> Press Ok to continue!";
				var modal = document.getElementById('exampleModal');
				modal.style.display = "block";
				// custome Alerts
				var modalConfirm = function(callback) {
				$("#saveid").on("click", function() {
					callback(true);
					modal.style.display = "none";
				});
				$("#closeid").on("click", function() {
					callback(false);
					modal.style.display = "none";
				});
				};
				modalConfirm(function(confirm) {
				if (confirm) {
					var xhttp1 = new XMLHttpRequest();
						xhttp1.onload = function() {
							if(this.responseText == "deleted"){
								clear_fields();
								document.getElementById('textInput').focus();
								document.getElementById('btnsave').disabled = false;
								document.getElementById('mylist').style.display = "none";
								document.getElementById('myModal').style.display = "none";
								modal.style.display = "none";
							}				
						}
					xhttp1.open("GET", "getduplicates.php?delete="+id);
					xhttp1.send();
				}else{
					clear_fields();
					document.getElementById('textInput').focus();
					document.getElementById('btnsave').disabled = false;
					document.getElementById('mylist').style.display = "none";
					document.getElementById('myModal').style.display = "none";
					return 0;
				}
				});				
		}
	}
	function updateitem(totaldupli,id,myupc,myqty,myccode,myccodei){
		document.getElementById('txtaction').value = 'update';
		if(totaldupli > 1){
			close_modal('myModal');
			document.getElementById('textInput').value = myupc;
			var xhttp1 = new XMLHttpRequest();
				xhttp1.onload = function() {
					if(this.responseText != "notfound"){
						document.getElementById('duplicatelist').innerHTML = this.responseText;
						openduplicates('mylist');
					}				
				}
			xhttp1.open("GET", "getduplicates.php?duplicate="+myupc+"&action=update");
			xhttp1.send();
		}else{
			document.getElementById('textInput').value = myupc;
			document.getElementById('txtqty').value = myqty;
			document.getElementById('txtid').value = id;
			$('#class_code option[value="'+ myccodei+'_'+myccode +'"]').prop('selected', true);	
			close_modal('myModal');
		}
	}
	function clear_fields(){
			document.getElementById('textInput').value = "";
			document.getElementById('desc').value = "";
			document.getElementById('txtqty').value = "";
			document.getElementById('txtvendor').value = "";
			document.getElementById('desc_text').value = ""; 
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
	function openmdalert(id,qty,classcode,vendor,desc,upc,sku,txtid,txtaction){	
		if(document.getElementById('textInput').value == ""){
				prompt_alert('Please Scan item Thank you!');
				document.getElementById('btnsave').disabled = false;				
				return 0;
		}
		if(parseInt(qty) > 5){
				document.getElementById('message').innerHTML = "You have inputted a large quantity!.<br> Press Ok to accept!";
				var modal = document.getElementById(id);
				modal.style.display = "block";
				// custome Alerts
				var modalConfirm = function(callback) {
				$("#saveid").on("click", function() {
					callback(true);
					modal.style.display = "none";
				});
				$("#closeid").on("click", function() {
					callback(false);
					modal.style.display = "none";
				});
				};
				modalConfirm(function(confirm) {
				if (confirm) {
					saveScanned(qty,classcode,vendor,desc,upc,sku,txtid,txtaction);
				}else{
					clear_fields();
					document.getElementById('textInput').focus();
					document.getElementById('btnsave').disabled = false;
					return 0;
				}
				});				
		}else{
			// save item
			saveScanned(qty,classcode,vendor,desc,upc,sku,txtid,txtaction);
		}		
	}
	function saveScanned(qty,classcode,vendor,desc,upc,sku,txtid,txtaction){
			newStr = desc.replace('#', '');
			newStr = desc.replace("'", '');
			newStr = desc.replace("&", '-');
			//alert(newStr);
			document.getElementById('btnsave').disabled = true;				
			var xhttp = new XMLHttpRequest();
			xhttp.onload = function() {
					document.getElementById("btnsave").disabled = true;
				if(this.responseText == "inserted"){
					location.reload();
					clear_fields();
					document.getElementById('textInput').focus();
					document.getElementById('btnsave').disabled = false;
				}else{
					prompt_alert('Error while inserting Quantity.');	
					clear_fields();
					document.getElementById('btnsave').disabled = false;
					document.getElementById('textInput').focus();
				}				
			}
			xhttp.open("GET", "savescanned.php?qty="+qty+"&classcode="+classcode+"&vendor="+vendor+"&desc1="+newStr+"&upc1="+upc+"&sku1="+sku+"&txtid="+txtid+"&txtaction="+txtaction);
			xhttp.send();			
		}
	function getbarcode(code){		
		isscanning = true;

		document.getElementById('textInput').disabled = true;
		//check if a valid barcode
		code = code.trim();
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
		//'Find SKU,UPC to INVUPC using UPC
		var sku_response;
		var xhttp = new XMLHttpRequest();
		xhttp.onload = function() {
			sku_response =  this.responseText;			
			document.getElementById('loadingalert').style.display = "none";
			if(sku_response == "not found"){
				clear_fields();
				document.getElementById('textInput').disabled = false;
				document.getElementById('textInput').focus();				
				prompt_alert('No Item Found');
				isscanning = false;
				return 0;
			}else{				
				document.getElementById('textInput').value = code;
				document.getElementById('displaypanel').innerHTML = sku_response;
				document.getElementById('textInput').disabled = false;
				document.getElementById('txtqty').focus();
				document.getElementById("btnsave").disabled = false;
				isscanning = false;	
				//check for duplicates
				var xhttp1 = new XMLHttpRequest();
				xhttp1.onload = function() {
					if(this.responseText != "notfound"){
						openoptions(this.responseText,'myModal');	
					}				
				}
				xhttp1.open("GET", "getduplicates.php?barcode="+code.trim());
				xhttp1.send();
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
			reactToPaste: false, // Compatibility to built-in scanners in paste-mode (as opposed to keyboard-mode)
			onScan: function(sCode, iQty) { // Alternative to document.addEventListener('scan')
				//console.log('Scanned: ' + iQty + 'x ' + sCode); 			
				getbarcode(sCode);				
			},
			onKeyDetect: function(iKeyCode){ // output all potentially relevant key events - great for debugging!
				console.log('Pressed: ' + iKeyCode);
			}
		});
	</script>
	<script>
	// When the user clicks the button, open the modal 
	function openoptions(params,id){
		var myArray = params.split("-");
		var modal = document.getElementById(id);
		document.getElementById('dup_id').value = myArray[0];
		document.getElementById('tol_dupli').value = myArray[1];
		document.getElementById('myupc').value = myArray[2];
		document.getElementById('myqty').value = myArray[3];
		document.getElementById('myccode').value = myArray[4];
		document.getElementById('myccodei').value = myArray[5];
		modal.style.display = "block";
	}
	function openduplicates(id){
		var modal = document.getElementById(id);
		modal.style.display = "block";
	}
	function opendelete(id){
		var modal = document.getElementById(id);
		modal.style.display = "block";
	}
	function opendalert(title,details,d1){
		var modal = document.getElementById('myalert');
		document.getElementById('title1').innerHTML = title
		document.getElementById('details').innerHTML = details;
		document.getElementById('details1').innerHTML = d1;
		modal.style.display = "block";
	}
	// When the user clicks on <span> (x), close the modal
	function close_modal(id) {
		// Get the modal
		var modal = document.getElementById(id);
		modal.style.display = "none";
	}	
	/* When the user clicks anywhere outside of the modal, close it
	window.onclick = function(event) {
	if (event.target == modal) {
	// modal.style.display = "none";
	}
	}*/
	</script>
 <!-- Jquery-2.2.4 js -->
    <script src="../js/jquery/jquery-2.2.4.min.js"></script>
    <!-- All Plugins js -->
    <script src="../js/others/plugins.js"></script>
    <!-- Active JS -->
    <script src="../js/active.js"></script>
</html>