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
            EXPORT to PDF
        </div>
        <a href="#" id="checkall">Check all</a>
        <a href="#" id="uncheckall">Uncheck all</a>
        <div id="alllist" class="mb-1" style="border : 1px solid #eee;padding:5px 5px 5px;">
        </div>
        <button id="dl" class="btn btn-sm"  style="width:100%;background-color:#034f84;color:white">DOWNLOAD</button>
        <a href="index.php" class="btn btn-sm"  style="width:100%;background-color:#034f84;color:white;margin-top:5px;">HOME</a>
    </div>



<script>
let checkallbtn = document.getElementById("checkall");
let trfs = document.getElementsByName("trfs");
let uncheckallbtn = document.getElementById("uncheckall");

uncheckallbtn.style.display = "none";

checkallbtn.addEventListener("click",(event)=>{
    checkallbtn.style.display = "none";
    for( var i=0; i< trfs.length; i++){
        trfs[i].checked = true;
    };
    uncheckallbtn.style.display = "block";
});

uncheckallbtn.addEventListener("click",(event)=>{
    uncheckallbtn.style.display = "none";
    for( var i=0; i< trfs.length; i++){
        trfs[i].checked = false;
    };
    checkallbtn.style.display = "block";
});

document.addEventListener("DOMContentLoaded", function(event) {
    //getTrfs();
    //document.getElementById('dl').disabled = true;
    viewall();
 });

 function viewall(){
    document.getElementById("alllist").style.display ="block";
    document.getElementById("dl").style.display ="block";
    document.getElementById('alllist').innerHTML = "";
    document.getElementById('dl').disabled = false;
    var xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            var jsonData = JSON.parse(this.responseText);
            for(var i=0; i < jsonData.length; i++){

                var id = jsonData[i].trfbch;
                var trfbch = jsonData[i].trfbch;
                var newElement = '<div class="form-check"><label class="form-check-label"><input type="checkbox" name="trfs" class="form-check-input trfs" value="'+id+'">'+trfbch+'</label></div>';
                document.getElementById('alllist').innerHTML += newElement;
            }
        }
    };
    xmlhttp.open("GET","fx/trflist.php?view=all",true);
    xmlhttp.send();   
 }



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
var printerip = document.getElementById('printer_ip');

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





  
</script>
</body>
</html>