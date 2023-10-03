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

    
        <div id="Header" class="text-center" style="font-size:20px;">
            Downloaded TRF#
        </div>
        <?php if($type != 'Android'): ?>
        <p id="backp1" class="text-center"><a href="#" id="showall" class="btn btn-danger btn-sm">Download PDF</a></p>
        <?php endif; ?>
        <p id="backp" class="text-center"><a href="#" id="back" class="btn btn-success btn-sm">< Back</a></p>
        <div id="list" class="mb-1" style="border : 1px solid #eee;padding:5px 5px 5px">
        </div>

        <div id="alllist" class="mb-1" style="border : 1px solid #eee;padding:5px 5px 5px;">
        </div>
        <div id="hideme">
            <span>Note : Printing support only for Network Printers.</span>
            <div class="input-group" style="margin-bottom:5px;">
                <input type="text" id="printer_ip" class="form-control nput" placeholder="Printer's IP" autofocus>
                <div class="input-group-append">
                <button id="print" class="btn btn-sm mb-1"  style="width:100%;background-color:#034f84;color:white">PRINT</button>
                </div>
            </div>        
            <div class="row mb-1">
                <div class="col"><a href="index.php" class="btn btn-sm"  style="width:100%;background-color:#034f84;color:white">HOME</a></div>
            </div>
        </div>
        <button id="dl" class="btn btn-sm"  style="width:100%;background-color:#034f84;color:white">DOWNLOAD</button>
    </div>



<script>
document.addEventListener("DOMContentLoaded", function(event) {
    getTrfs();
    document.getElementById('dl').disabled = true;
 });

 function getTrfs(){
    var xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            var jsonData = JSON.parse(this.responseText);
            for(var i=0; i < jsonData.length; i++){

                var id = jsonData[i].id;
                var trfbch = jsonData[i].trfbch;
                var newElement = '<div id="del'+id+'" class="form-check"><label class="form-check-label"><input type="checkbox" name="trfs" class="form-check-input trfs" value="'+id+'">'+trfbch+'</label></div>';
                document.getElementById('list').innerHTML += newElement;
            }
        }
    };
    xmlhttp.open("GET","fx/trflist.php?view=byip",true);
    xmlhttp.send();
};
</script>

<script>
var btn = document.querySelector('#showall');
btn.addEventListener('click', (event) => {
    document.getElementById("alllist").style.display ="block";
    document.getElementById("dl").style.display ="block";
    document.getElementById("hideme").style.display ="none";
    document.getElementById('alllist').innerHTML = "";
    document.getElementById('dl').disabled = false;
    document.getElementById("backp").style.display ="block";
    document.getElementById("backp1").style.display ="none";
    document.getElementById("Header").innerHTML = "All downloaded TRF#";
    var xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            var jsonData = JSON.parse(this.responseText);
            for(var i=0; i < jsonData.length; i++){

                var id = jsonData[i].trfbch;
                var trfbch = jsonData[i].trfbch;
                var newElement = '<div class="form-check"><label class="form-check-label"><input type="checkbox" name="trfs" class="form-check-input trfs" value="'+id+'">'+trfbch+'</label></div>';
                document.getElementById('list').style.display = 'none';
                document.getElementById('print').disabled = true;
                document.getElementById('alllist').innerHTML += newElement;
            }
        }
    };
    xmlhttp.open("GET","fx/trflist.php?view=all",true);
    xmlhttp.send();    
});
</script>

<script>
var btn = document.querySelector('#print');
var printerip = document.getElementById('printer_ip');
btn.addEventListener('click', (event) => {
    var checkboxes = document.querySelectorAll('input[name="trfs"]:checked');
    var values = [];
    checkboxes.forEach((checkbox) => {
        values.push(checkbox.value);
    });
    
    for(var i = 0; i < values.length; i++){
        var http = new XMLHttpRequest();
        var url = 'fx/print.fx.php';
        var params = 'trf='+values[i]+'&prip='+printerip.value;
        http.open('POST', url, true);

        //Send the proper header information along with the request
        http.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');

        http.onreadystatechange = function() {//Call a function when the state changes.
            if(http.readyState == 4 && http.status == 200) {
                var res = JSON.parse(this.responseText);
                if(res.success === 1){
                    console.log('sucess_'+res.trf);
                }else{
                    console.log('failed_'+res.trf);
                }
            }
        }
        http.send(params);
        
        if(i === values.length - 1){
            alert("Printed");
        }
    }
});    
</script>

<script>

var btn = document.querySelector('#dl');
var myurls = [];
btn.addEventListener('click', (event) => {
    document.getElementById('dl').disabled = true;
    var checkboxes = document.querySelectorAll('input[name="trfs"]:checked');
    var values = [];
    checkboxes.forEach((checkbox) => {
        values.push(checkbox.value);
    });

    var trf = "";
    var host = "";
    var thelink = "";
    
    for(var i = 0; i < values.length; i++){

            var x = new XMLHttpRequest();
            var y = 'fx/redirect.php';
            var z = 'trf=' + values[i];

            sendData(x,y,z,addtoarray);

            //function to send data
            function sendData(http, url, params,myCallback){
                http.open('POST', url, true);
                //Send the proper header information along with the request
                http.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');

                http.onreadystatechange = function() {//Call a function when the state changes.
                    if(http.readyState == 4 && http.status == 200) {
                        var res = JSON.parse(this.responseText);
                        if(res.success === 1){
                            trf = res.trf;
                            host = res.host;

                            thelink = host + 'trf_in/fx/download.fx.php?trf=' + trf;
                            myCallback(thelink);
                            
                        }else{
                            console.log('failed_' + res.trf);
                        }
                    };
                };
                http.send(params);                
            };
    }

});

function addtoarray(t){
    myurls.push(t);
    window.open(t, '_blank');
    document.getElementById('dl').disabled = false;
};

let btnbck = document.getElementById("back");
btnbck.addEventListener('click',(event) => {
    window.location.reload();
});


document.getElementById("alllist").style.display ="none";
document.getElementById("dl").style.display ="none";
document.getElementById("backp").style.display ="none";

  
</script>
</body>
</html>