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
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/mycss.css">
    <title></title>
</head>
<body>

    <div class="container">
    <!--<div id="alert" class="alert alert-info" role="alert">
    </div>-->
    
    <?php if($type == 'Android'){ ?>

        Transfer No.
        <div class="input-group mb-1">
            <input type="number" id="trfbch" min=0 class="form-control nput" placeholder="TRF#" autofocus>
            <div class="input-group-append">
                <button class="btn btn-primary btn-sm" id="add2list" type="button" style="width:100%;background-color:#034f84;">Add</button>
            </div>
        </div>
        <textarea name="trfbchlist" id="trfbchlist" cols="30" class="form-control txtarea mb-1" readonly></textarea>
        
        
        <button id="download" class="btn btn-sm mb-1"  style="width:100%;background-color:#034f84;color:white">DOWNLOAD</button>
        <div class="row mb-1">
            <div class="col"><button id="clearlist" class="btn btn-sm"  style="width:100%;background-color:#034f84;color:white">CLEAR</button></div>
            <div class="col"><a id="home" href="index.php" class="btn btn-sm"  style="width:100%;background-color:#034f84;color:white">HOME</a></div>
        </div>
        
        <textarea style="color:red;" name="response" id="response" cols="30" rows="3" class="form-control txtarea" readonly></textarea>    
    <?php }else{?>

        <h5 class="text-center">Download Transfer</h5>
        <div class="input-group mb-1" style="display:none;">
            <input type="number" id="trfbch" min=0 class="form-control nput" placeholder="TRF#" autofocus>
            <div class="input-group-append">
                <button class="btn btn-primary btn-sm" id="add2list" type="button" style="width:100%;background-color:#034f84;">Add</button>
            </div>
        </div>
        <textarea name="trfbchlist" id="trfbchlist" cols="30" rows="10" class="form-control txtarea mb-1"></textarea>
        <span id="spantext" class="text-center" style="color:blue;font-size:12px;font-weight:bold;"></span>
        <div id="progress" class="progress">
            <div id="progressbar" class="progress-bar" style="width:0%">0%</div>
        </div>
        <span style="font-style: italic;color:red;font-weight:bold;font-size:12px;">Note: Please don't leave a blank or space at the last line of the textbox.</span>
        
        
        <button id="download" class="btn btn-sm mb-1"  style="width:100%;background-color:#034f84;color:white">DOWNLOAD</button>
        <div class="row mb-1">
            <div class="col"><button id="clearlist" class="btn btn-sm"  style="width:100%;background-color:#034f84;color:white">CLEAR</button></div>
            <div class="col"><a id="home" href="index.php" class="btn btn-sm"  style="width:100%;background-color:#034f84;color:white">HOME</a></div>
        </div>
        
        <iframe id="myiframe" width="100%" height="300" style="border:none;">

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
                var trfbch = "";
                for(var i = 0; i < arrayTRF.length; i++){
                    trfbch += arrayTRF[i] + ",";
                }

                if(trfbch != ","){     
                    document.getElementById("myiframe").src = 'fx/downloadtrfbch.fx.php?q='+ String(trfbch);
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

/*
        function hideAlert()
        {
            var pb = document.getElementById("alert");
            pb.style.display = "none";
            //var res = document.getElementById("response");
            //res.style.display = "none";
        }
        function showAlert()
        {
            var pb = document.getElementById("alert");
            pb.style.display = "block";
            //var res = document.getElementById("response");
            //res.style.display = "block";
        }*/

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
        }

        function enableBTN()
        {
            document.getElementById('download').classList.remove("disabled");
            document.getElementById('add2list').classList.remove("disabled");
            document.getElementById('home').classList.remove("disabled");
            document.getElementById('clearlist').classList.remove("disabled");
            document.getElementById("trfbchlist").classList.remove("disabled");
        }


        
    </script>

</body>
</html>