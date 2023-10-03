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
    
</head>
<body>
    <div class="container text-center"> 
		<img src="../resources/lcc.jpg" style="width: 90px; height: 70px;">
        <br>
        <h4 class="font-title">TRF Receiving : View Transfer</h4>
		<h5 style="font-size: 10pt;" class="semi-visible">v2.0.0</h5>
        <br>
    </div>
    <div class="container">
        <div id="Header" class="text-center" style="font-size:18px;">
            Transfer List
        </div>
        <a href="#" id="checkall">Check all</a>
        <a href="#" id="uncheckall">Uncheck all</a>        
        <div id="list" class="mb-1" style="border : 1px solid #eee;padding:5px 5px 5px">
        </div>
    
        <div id="hideme">
            <span>Note : Printing support only for Network Printers.</span>
            <div class="input-group" style="margin-bottom:5px;">
                <input type="text" id="printer_ip" class="form-control nput" placeholder="Printer's IP" autofocus>
                <div class="input-group-append">
                <button id="print" class="btn btn-sm mb-1 trf-btn">Print</button>
                </div>
            </div>        
            <div class="row mb-1">
            <div class="col"><a href="#" id="cleardownload" class="btn btn-sm trf-btn">Clear</a></div>
                <div class="col"><a href="index.php" class="btn btn-sm trf-btn">Back</a></div>
                
            </div>
        </div>
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



let cleardownloadsbtn = document.getElementById('cleardownload');

cleardownloadsbtn.addEventListener("click",function(event){
    //alert("This action is under development.");
    var xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            var jsonData = JSON.parse(this.responseText);
            if(jsonData.success === 1){
                alert("Data successfully cleared. System now ready to download new TRF.");
                window.location.reload(true);
            }else{
                alert("failed to clear data.")
            }
        }
    };
    xmlhttp.open("GET","fx/cleardata.fx.php",false);
    xmlhttp.send();    
});

document.addEventListener("DOMContentLoaded", function(event) {
    getTrfs();
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

</body>
</html>