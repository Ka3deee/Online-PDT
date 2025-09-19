<?php
session_start();
include('fx/getOStype.php');
if(!isset($_SESSION['strcode'])){
    header("Location:index.php?notif=nostrcode");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>PDT Application : Transfer Receiving</title>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/mycss.css">
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
<body>
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
        <br>
        <h4 class="font-title">TRF Receiving : Download Transfer Data</h4>
		<h5 style="font-size: 10pt;" class="semi-visible">v2.0.0</h5>
        <br>
    </div>
    <div class="container">
    <!--<div id="alert" class="alert alert-info" role="alert">
    </div>-->
  

    <?php if($type == 'Android'){ ?>

        <div class="input-group mb-1" style="display:none">
            <input type="text" id="trfbch" class="form-control nput" placeholder="TRF#" autofocus>
            <div class="input-group-append">
                <button class="btn btn-primary btn-sm trf-btn" id="add2list" type="button">Add</button>
            </div>
        </div>
		<!--<input type="text" id="input">
		<input type="text" id="inputw">-->
        <textarea name="trfbchlist" id="trfbchlist" cols="30" class="form-control txtarea mb-1" placeholder="Scan MTR barcode" autofocus></textarea>
        <span id="spantext" class="text-center" style="color:blue;font-size:12px;font-weight:bold;"></span>
        <div id="progress" class="progress">
            <div id="progressbar" class="progress-bar" style="width:0%">0%</div>
        </div>
        
        
        <button id="download" class="btn btn-sm mb-1 trf-btn">Download</button>
        
		<!-- 
		o-- Start --o
			Author: Rainier C. Barbacena
			Date: June 13, 2023
			Description: This button creates a text file for the master data to be uploaded on the local DB of PDT.
		-->
        <button style="display:none" onclick="saveTextFile()" id="save-text-file" class="btn btn-sm mb-1 trf-btn">Download as txt</button>
        <!-- 
			Author: Rainier C. Barbacena
			Date: June 13, 2023
			Description: This button creates a text file for the master data to be uploaded on the local DB of PDT.
		o-- End --o
		-->

        <div class="row mb-1">
            <div class="col"><button id="clearlist" class="btn btn-sm trf-btn">Clear</button></div>
            <div class="col"><a id="home" href="index.php" class="btn btn-sm trf-btn">Back</a></div>
        </div>
        
        <!-- <iframe id="myiframe" width="100%" height="300" style="border:none;"> -->
        <iframe id="myiframe" width="100%" height="50" style="border:none;">

        </iframe>
    <?php }else{?>

        <h6 class="text-center">Transfer No.</h6>
        <div class="input-group mb-1" style="display:none;">
            <input id="trfbch" class="form-control nput" placeholder="TRF#" autofocus>
            <div class="input-group-append">
                <button class="btn btn-primary btn-sm trf-btn" id="add2list" type="button">Add</button>
            </div>
        </div>
        <textarea name="trfbchlist" id="trfbchlist" cols="30" rows="7" class="form-control txtarea mb-1"></textarea>
        <span id="spantext" class="text-center" style="color:blue;font-size:12px;font-weight:bold;"></span>
        <div id="progress" class="progress">
            <div id="progressbar" class="progress-bar" style="width:0%">0%</div>
        </div>
        <span style="font-style: italic;color:red;font-weight:bold;font-size:12px;">Note: Please don't leave a blank or space at the last line of the textbox.</span>
        
        
        <button id="download" class="btn btn-sm mb-1 trf-btn">Download</button>
        
		<!-- 
		o-- Start --o
			Author: Rainier C. Barbacena
			Date: June 13, 2023
			Description: This button creates a text file for the master data to be uploaded on the local DB of PDT.
		-->
        <button onclick="saveTextFile()" id="save-text-file" class="btn btn-sm mb-1 trf-btn">Download as txt</button>
        <!-- 
			Author: Rainier C. Barbacena
			Date: June 13, 2023
			Description: This button creates a text file for the master data to be uploaded on the local DB of PDT.
		o-- End --o
		-->

        <div class="row mb-1">
            <div class="col"><button id="clearlist" class="btn btn-sm trf-btn">Clear</button></div>
            <div class="col"><a id="home" href="index.php" class="btn btn-sm trf-btn">Back</a></div>
        </div>
        
        <!-- <iframe id="myiframe" width="100%" height="300" style="border:none;"> -->
        <iframe id="myiframe" width="100%" height="50" style="border:none;">

        </iframe>
        <!--<textarea style="color:red;" name="response" id="response" cols="30" rows="3" class="form-control txtarea" readonly></textarea>-->
    <?php } ?>

    </div>
    <script src="js/jquery/jquery-3.6.0.js"></script>
    <script src="js/bootstrap/bootstrap.min.js"></script>





    <script>
        //PROGRESS BAR
        function uptprogressbar(percent){
            let progressbar = document.getElementById("progressbar");
            progressbar.style["width"] = percent+'%';
            progressbar.innerHTML = percent.toFixed(2)+'%';
        }
        

        document.addEventListener("DOMContentLoaded", function(event) { 
            //hideProgressBar();
            //hideAlert();
            //disableBTN();
            document.getElementById("spantext").style.display = "none";
            document.getElementById("progress").style.display = "none";
            
        });        

        // FOR ADD TRF TO LIST
        document.getElementById("add2list").addEventListener('click', function() {
            

                var txtrow = document.getElementById("trfbchlist").getAttribute("rows");
                var trfbch = document.getElementById('trfbch').value;
                var newrowcnt = parseFloat(txtrow) + 1;

                if(isEmpty(trfbch)){
                    if (checkInp(trfbch)){ // CHECK IF INPUT IS A NUMBER
                    document.getElementById("trfbchlist").innerHTML += trfbch + '\n';
                    document.getElementById("trfbchlist").setAttribute("rows", ""+ newrowcnt +""); 
                    document.getElementById('trfbch').value = "";
                    document.getElementById("trfbch").focus();
                    }else{
                        alert("Please input only number.");
                        document.getElementById('trfbch').value = "";
                        document.getElementById("trfbch").focus();
                    }
                }else{
                    alert("Please input trf.");
                }            
            });


        // FOR CLEARING LIST
        document.getElementById("clearlist").addEventListener('click', function() {
                window.location.reload(true);
            });

        // FOR DOWNLOAD
        document.getElementById("download").addEventListener('click', function() {
            
            disableBTN();

                // GET VALUES FROM TEXTAREA -> TO ARRAY
                var list = document.getElementById("trfbchlist").value;             
                var arrayTRF = list.split("\n");
                
                //all transfer :: STRING TYPE
                var mtrno = "";
                for(var i = 0; i < arrayTRF.length; i++){
                    mtrno += arrayTRF[i] + ",";
                }

                if(mtrno != ","){     
                    var test = document.getElementById("myiframe").src = 'fx/downloadtrfbch.fx.php?q='+ String(mtrno);
                }else{
                    alert("Empty list.");
                    enableBTN();
                }
                
            });

        // FUNCTION FOR CHECKING INPUT
        function checkInp(x)
        {
            if (isNaN(x)) 
            {
                return false;
            }
            else
            {
                return true;
            }
        }

        // FUNCTION FOR CHECKING INPUT IF EMPTY
        function isEmpty(x)
        {
            if (x == "") 
            {
                return false;
            }
            else
            {
                return true;
            }
        }

            //
        function isArraynotEmp(x)
        {
            if (x == 0) 
            {
                return false;
            }
            else
            {
                return true;
            }
        }


        function disableBTN()
        {
            document.getElementById("spantext").style.display = "block";
            document.getElementById("progress").style.display = "block";
            document.getElementById('spantext').innerHTML = "Requesting data from the MMS Server.";
            document.getElementById('download').classList.add("disabled");
            document.getElementById('add2list').classList.add("disabled");
            document.getElementById('home').classList.add("disabled");
            document.getElementById('clearlist').classList.add("disabled");
            document.getElementById("trfbchlist").classList.add("disabled");
            document.getElementById("save-text-file").classList.add("disabled");
        }

        function enableBTN()
        {
            document.getElementById('download').classList.remove("disabled");
            document.getElementById('add2list').classList.remove("disabled");
            document.getElementById('home').classList.remove("disabled");
            document.getElementById('clearlist').classList.remove("disabled");
            document.getElementById("trfbchlist").classList.remove("disabled");
            document.getElementById("save-text-file").classList.remove("disabled");
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
                const currentDate = getCurrentDate();
                document.getElementById("download-animation-wrapper").classList.remove("hidden");
                document.getElementById("download").disabled = true;
                document.getElementById("save-text-file").disabled = true;
                document.getElementById("clearlist").disabled = true;
                document.getElementById("home").disabled = true;
                var textareaValue = document.getElementById("trfbchlist").value;
                var xhr = new XMLHttpRequest();
                xhr.open('GET', 'generate_text_file.php?trfbchlist=' + encodeURIComponent(textareaValue), true);
                xhr.responseType = 'blob'; // Set the response type to 'blob' to handle binary data
                xhr.onload = function (e) {
                    if (this.status === 200) {
                        // Create a temporary anchor element to facilitate the file download
                        var blob = new Blob([this.response], { type: 'text/plain' });
                        var downloadLink = document.createElement('a');
                        downloadLink.href = window.URL.createObjectURL(blob);
                        downloadLink.download = 'TRFINDBMaster_' + currentDate + '.txt';

                        // Programmatically trigger the click event on the download link
                        document.body.appendChild(downloadLink);
                        downloadLink.click();
                        document.body.removeChild(downloadLink);
                        document.getElementById("download").disabled = false;
                        document.getElementById("save-text-file").disabled = false;
                        document.getElementById("clearlist").disabled = false;
                        document.getElementById("home").disabled = false;
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



</body>
</html>
