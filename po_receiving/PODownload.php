
<!DOCTYPE html>
<?php
session_start();
?>
<html lang="en">
<head>
  <title>PDT Application : Po Receiving</title>
  <meta charset="UTF-8">
    <meta name="description" content="">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<link rel="shortcut icon" href="../images/favicon.ico"/>
    <link rel="bookmark" href="../images/favicon.ico"/>
  <link rel="stylesheet" href="../bootstrap-3.4.1-dist/css/bootstrap.min.css">
  <!---   Content Styles -->
  <link href="../mycss.css" rel="stylesheet">
  <link href="../css/addedcss.css" rel="stylesheet">
  <link href="../css/modify.css" rel="stylesheet">
  <style>
    .download-animation-wrapper {
      position: absolute;
      height: 100vh;
      width: 100%;
    }
    .hidden {
      display: none;
    }
    .page {
      position: absolute;
      bottom: 0;
      padding: 20px 2%;
      @include display-flex;
      @include align-items(center);
      -moz-box-align: center;
      -webkit-box-pack: center;
      -webkit-justify-content: center;
      justify-content: center;
      -moz-box-pack: center;
      -ms-flex-pack: center;
    }

    .folder {
      background-color: #337ab7;
      position: relative;
      width: 92px;
      height: 64px;
      display: block;
      border-top-right-radius: 8px;
      border-bottom-right-radius: 8px;
      border-bottom-left-radius: 8px;
    }
      .folder-tab {
        position: absolute;
        height: 10px;
        left: 0;
        bottom: 100%;
        display: block;
        width: 40%;
        border-top-left-radius: 8px;
        background-color: inherit;
        &:after {
          content: '';
          position: absolute;
          display: block;
          top: 0;
          left: calc(100% - 10px);
          border-bottom: 10px solid #337ab7;
          border-left: 10px solid transparent;
          border-right: 10px solid transparent;
        }
      }
      .folder-icn {
        padding-top: 12px;
        width: 100%;
        height: 100%;
        display: block;
      }
      .downloading {
        width: 30px;
        height: 32px;
        margin: 0 auto;
        position: relative;
        overflow: hidden;
      }
        .custom-arrow {
          width: 14px;
          height: 14px;
          position: absolute;
          top: 0;
          left: 50%;
          margin-left: -7px;
          background-color: #fff;
          
          -webkit-animation-name: downloading;
          -webkit-animation-duration: 1.5s;
          -webkit-animation-iteration-count: infinite;
          animation-name: downloading;
          animation-duration: 1.5s;
          animation-iteration-count: infinite;
          
          &:after {
            content: ''; position: absolute; display: block;
            top: 100%;
            left: -9px;
            border-top: 15px solid #fff;
            border-left: 16px solid transparent;
            border-right: 16px solid transparent;
          }
        }
      .bar {
        width: 30px;
        height: 4px;
        background-color: #fff;
        margin: 0 auto;
      }

    @-webkit-keyframes downloading {
      0% {
        top: 0;
        opacity: 1;
      }
      50% {
        top: 110%;
        opacity: 0;
      }
      52% {
        top: -110%;
        opacity: 0;
      } 
      100% {
        top: 0;
        opacity: 1;
      }
    }
    @keyframes downloading {
      0% {
        top: 0;
        opacity: 1;
      }
      50% {
        top: 110%;
        opacity: 0;
      }
      52% {
        top: -110%;
        opacity: 0;
      } 
      100% {
        top: 0;
        opacity: 1;
      }
    }
  </style>
</head>  
<body >
<div id="download-animation-wrapper" class="download-animation-wrapper hidden">
  <div class="page">
    <div class="folder">
      <span class="folder-tab"></span>
      <div class="folder-icn">
        <div class="downloading">
          <span class="custom-arrow"></span>
        </div>
        <div class="bar"></div>
      </div>
    </div>
  </div>
</div>
<div class="container text-center">
	
	<img src="../resources/lcc.jpg" style="width: 90px; height: 70px;">
	<h4>PO Receiving : Download / Retrieve PO Data</h4>
	<h5 class="semi-visible">v2.0.0</h5>
	<br>
</div>
<div class="container">
	<div class="row">
		<div class="col-xs-12">
			<div class="row">
				<div class="col-xs-8">
					<label for="ponum">Purchase Order No.</label>
					<input class="form-control" type="text" id = 'ponum' autofocus onchange = "addtolist(this.value)" >
				</div>
				<div class="col-xs-4">
					<label for="ex2">&nbsp;</label>
					<button type="button" style = "width:100%;" onclick = "clearfields()" class="btn btn-primary" >
					<span class="glyphicon glyphicon-trash"></span> Clear
					</button>
				</div>
			</div>
		</div>

		<div class="col-xs-12" style="margin-top: 20px;">
			<label for="ex3">Po List</label>
			<textarea  disabled class="form-control" rows="7" id="polist"></textarea>
		</div>
		  
		<div class="col-xs-12" >
			<label for="ex1" id = "information" ></label>
			<div class="progress" id="progressbar">			
			</div>
		</div>

		<div class="col-xs-12" style = "margin-top:-20px;">
			<div class="row">
				<div class="col-xs-6">
					<label for="ex2"></label>
					<button style = "width:100%;" type="button" onclick = "retrievedata()" id="btn_prev" class="btn btn-primary">
					<span class="glyphicon glyphicon-repeat"></span> Retrieve Prev Data
					</button>
				</div>
				<div class="col-xs-6">
					<label for="ex2"></label>
					<?php
						if(isset($_SESSION['user_id']) ){
					?>
					<button style="width: 100%;" type="button" id="btn_download" onclick="downloadPO(document.getElementById('polist').innerHTML)" class="btn btn-primary" name="btn_download">
						<span class="glyphicon glyphicon-download-alt"></span> Download Data
					</button>

					<?php
						} else {
					?>

					<button style="width: 100%;" type="button" onclick="alert('Please Set User ID First, Thank you')" class="btn btn-primary">
						<span class="glyphicon glyphicon-download-alt"></span> Download Data
					</button>

					<?php
						}
					?>

				</div>
			</div>
		</div>

		<!-- 
		o-- Start --o
			Author: Rainier C. Barbacena
			Date: June 13, 2023
			Description: This button creates a text file for the master data to be uploaded on the local DB of PDT.
		-->
		<div class="col-xs-12">
			<label for="ex2"></label>
			<button style="width: 100%;" type="button" id="btn_save" onclick="saveTextFile()" class="btn btn-primary">
				<span class="glyphicon glyphicon-save"></span> Download Data as TXT
			</button>
		</div>
		<!-- 
			Author: Rainier C. Barbacena
			Date: June 13, 2023
			Description: This button creates a text file for the master data to be uploaded on the local DB of PDT.
		o-- End --o
		-->
		
		<div class="col-xs-12">
			<div class="row">
				<div class="container text-center">
					<iframe id="loadarea" style="display:none;width:100%;height:100px;"></iframe><br />
					<button type="button" id="btn_exit" class="btn btn-primary btn-block" onclick = "window.location.href='PORrcv.php'"><span class="glyphicon glyphicon-log-out"></span> Back</button>
				</div>
			</div>
		</div>
	</div>
</div>
<div id="preloader">
        <div class="caviar-load"></div>
</div>

 
</body>
	<script>
		function clearfields(){
			document.getElementById("progressbar").style="width:0%";
			document.getElementById('polist').innerHTML = '';
			document.getElementById('information').innerHTML = '';
		}
		function show_result(param1){
			document.getElementById('polist').innerHTML = "";
			var lines = param1.split('<br>');
			for(var i=0;i<lines.length-1;i++){				
				document.getElementById('polist').innerHTML = document.getElementById('polist').innerHTML + lines[i] + '\n';
			}
		}
		function retrievedata(){
			document.getElementById('information').innerHTML = "Retrieving Data. Please Wait...";
			document.getElementById('loadarea').src = 'progressbar.php?retrivedata=yes';
		}
		function downloadPO(polist){
			var newpolist = "";
			var lines = polist.split(/\n/); 

			for(var i=0;i<lines.length-1;i++){				
				newpolist = newpolist + lines[i] + ",";
			}
			if((lines.length-1) > 0){
				var confirm_result = "";
				if(GetPrev_percentage() != 'no-data'){
					
					confirm_result = " Are you sure you want to Download NEW PO Batch?\n Latest Transaction Percentage :"+ GetPrev_percentage().toFixed(2) +"%\n This Action Cannot be Undone. ";
				}else{
					confirm_result = "Are you sure you want to Download PO? \n This Action Cannot be Undone. ";
				}
				
				if (!confirm(confirm_result)) return
				document.getElementById('information').innerHTML = "Processing...."
				document.getElementById('loadarea').src = 'progressbar.php?xstrings='+ String(newpolist);
			}else{
				alert('Po list cannot be Empty.');
			}						
		}	
		function addtolist(value1){
			document.getElementById('ponum').value = '';
			document.getElementById('polist').innerHTML = document.getElementById('polist').innerHTML + value1 + '\n';
		}
		function GetPrev_percentage(){
			
			var result = 0;
			var resultjson = [];
			var xhttp = new XMLHttpRequest();
			  xhttp.onload = function() { 
				//alert(this.responseText);
				if(this.responseText == "no-data"){
				  result = "no-data";
				}else{           
				  resultjson = JSON.parse(this.responseText);
				  result =  (parseFloat(resultjson[0].totalrcvqty) / parseFloat(resultjson[0].totalexpqty)) * 100; 
				  
				}			
			  }
			  xhttp.open("GET", "getsku.php?checktrans",false);
			  xhttp.send();
			return result;
		}

		/* 
		o-- Start --o
			Author: Rainier C. Barbacena
			Date: June 13, 2023
			Description: Sends AJAX request to the PHP script that generates and returns the text file content.
		*/
		function saveTextFile() {

			var confirmation = confirm("Are you sure to download data as text file?");
			if (confirmation) {
				document.getElementById("download-animation-wrapper").classList.remove("hidden");
				document.getElementById("btn_prev").disabled = true;
				document.getElementById("btn_download").disabled = true;
				document.getElementById("btn_save").disabled = true;
				document.getElementById("btn_exit").disabled = true;
				var xhr = new XMLHttpRequest();
				xhr.open('GET', 'generate_text_file.php', true);
				xhr.responseType = 'blob'; // Set the response type to 'blob' to handle binary data
				xhr.onload = function (e) {
					if (this.status === 200) {
						// Create a temporary anchor element to facilitate the file download
						var blob = new Blob([this.response], { type: 'text/plain' });
						var downloadLink = document.createElement('a');
						downloadLink.href = window.URL.createObjectURL(blob);
						downloadLink.download = 'PoReceivingMasterData.txt';

						// Programmatically trigger the click event on the download link
						document.body.appendChild(downloadLink);
						downloadLink.click();
						document.body.removeChild(downloadLink);
						document.getElementById("btn_prev").disabled = false;
						document.getElementById("btn_download").disabled = false;
						document.getElementById("btn_save").disabled = false;
						document.getElementById("btn_exit").disabled = false;
						document.getElementById("download-animation-wrapper").classList.add("hidden");
					}
				};
				xhr.send();
			}
		}
		/* 
			Author: Rainier C. Barbacena
			Date: June 13, 2023
			Description: Sends AJAX request to the PHP script that generates and returns the text file content.
		o-- End --o
		*/
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