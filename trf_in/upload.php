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
        <h4 class="font-title">TRF Receiving : Upload to MMS</h4>
		<h5 style="font-size: 10pt;" class="semi-visible">v2.0.0</h5>
        <br>
    </div>
    <div class="container">
    <!--<div id="alert" class="alert alert-info" role="alert">
    </div>-->
  

        <div class="input-group mb-1" style="display:none;">
            <input id="trfbch" class="form-control nput" placeholder="TRF#" autofocus>
            <div class="input-group-append">
                <button class="btn btn-primary btn-sm trf-btn" id="add2list" type="button">Add</button>
            </div>
        </div>
        <textarea name="trfbchlist" id="trfbchlist" cols="30" rows="7" class="form-control txtarea mb-1" style="display:none"></textarea>
        <span id="spantext" class="text-center" style="color:blue;font-size:12px;font-weight:bold;"></span>
        <div id="progress" class="progress">
            <div id="progressbar" class="progress-bar" style="width:0%">0%</div>
        </div>
        
        
        <button id="upload" class="btn btn-sm mb-1 trf-btn">Upload</button>
        <a id="home" href="index.php" class="btn btn-sm mb-1 trf-btn">Back</a>
        

        <div class="row mb-1" style="display:none">
            <div class="col"><button id="clearlist" class="btn btn-sm trf-btn">Clear</button></div>
            <div class="col"><a id="home" href="index.php" class="btn btn-sm trf-btn">Back</a></div>
        </div>
        
        <!-- <iframe id="myiframe" width="100%" height="300" style="border:none;"> -->
        <iframe id="myiframe" width="100%" height="50" style="border:none; display:none;">

        </iframe>

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


        // FOR DOWNLOAD
        document.getElementById("upload").addEventListener('click', function() {
            
            disableBTN();

                var test = document.getElementById("myiframe").src = 'fx/upload.fx.php';
                
            });


        function disableBTN()
        {
            document.getElementById("spantext").style.display = "block";
            document.getElementById("progress").style.display = "block";
            document.getElementById('spantext').innerHTML = "Uploading data to the MMS Server.";
            document.getElementById('upload').classList.add("disabled");
            document.getElementById('home').classList.add("disabled");
        }

        function enableBTN()
        {
            document.getElementById('upload').classList.remove("disabled");
            document.getElementById('home').classList.remove("disabled");
        }

        </script>




</body>
</html>
