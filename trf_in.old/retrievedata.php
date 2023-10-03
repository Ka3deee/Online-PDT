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
<style>
#preloader {
  background : #eee url('loading.gif') no-repeat center;
  background-size: 20%;
  height : 100vh;
  width : 100%;
  position : fixed;
  z-index : 100;
}    
</style>
<body>

<div id="preloader"></div>

    <div class="container">

    <h4>Server IP:</h4>
    <span style="font-style: italic;color:red;">Note: Please make sure that the server already finished downloading of TRF's.</span>
        <input id="ipaddress" name="ipaddress" type="text" class="form-control text-center" placeholder="e.g. 192.168.1.1" style="font-size: 25px;display:none;"><br>
        <button type="submit" name="submit" id="submit" class="btn btn-primary btn-sm" style="width:100%;background-color:#034f84;">RETRIEVE</button><br>
        <a href="index.php" class="btn btn-primary btn-sm" style="width:100%;background-color:#034f84;margin-top:5px;">HOME</a>
    </div>
    
    <h5 id="notif" class="text-center"></h5>


    <script src="js/jquery/jquery-3.6.0.js"></script>
    <script src="js/bootstrap/bootstrap.min.js"></script>
    <script>
        //PROGRESS BAR
        function uptprogressbar(percent){
            let progressbar = document.getElementById("progressbar");
            progressbar.style["width"] = percent+'%';
            progressbar.innerHTML = percent+'%';
        }
        

        document.addEventListener("DOMContentLoaded", function(event) { 
            //hideProgressBar();
            //hideAlert();
            //disableBTN();
            
        });        

    </script>

<script>

let submitbtn = document.getElementById("submit");
let loader = document.getElementById("preloader");
let notif = document.getElementById("notif");

loader.style.display = "none";

submitbtn.addEventListener("click",(event)=>{
    loader.style.display = "block";

    /*let ip = document.getElementById("ipaddress").value;

    let ipformat = /^(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)$/;
  
    if(ip == ""){
        loader.style.display = "none";
        notif.style.display = "block";
        notif.innerHTML = "Please enter IP address.";
        return;
    }
    

    if(!ip.match(ipformat))
    {
        loader.style.display = "none";
        notif.style.display = "block";
        notif.innerHTML = "IP address not valid.";
        return;
    }*/

    var http = new XMLHttpRequest();
    var url = "fx/retrievedata.fx.php";
    var params = 'action=1';



    http.open('POST', url, true);
    //Send the proper header information along with the request
    http.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
    http.onreadystatechange = function() {//Call a function when the state changes.
        if(http.readyState == 4 && http.status == 200) {
            var resp = JSON.parse(this.responseText);
            loader.style.display = "none";
            notif.style.display = "block";
            if(resp.success === 1){
                notif.innerHTML = "Retrieve done.";
            }else if(resp.success === 2) {
                notif.innerHTML = "Store not yet downloaded by the server.";
            }else{
                notif.innerHTML = "Failed to retrieve.";
            }
        }
    }
    http.send(params);
});

function ValidateIPaddress(inputText)
{
    var ipformat = /^(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)$/;
    if(inputText.value.match(ipformat))
    {
        return true;
    }
    else
    {
        return false;
    }
} 
</script>
</body>
</html>