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
        <h4 class="font-title">TRF Receiving : Export PDF</h4>
		<h5 style="font-size: 10pt;" class="semi-visible">v2.0.0</h5>
        <br>
    </div>
    <div class="container">
        <div id="Header" class="text-center" style="font-size:15px;">
            Select Transfer No.
        </div>
        <a href="#" id="checkall">Check all</a>
        <a href="#" id="uncheckall">Uncheck all</a>
        <div id="alllist" class="mb-1" style="border : 1px solid #eee;padding:5px 5px 5px;height:250px;overflow:scroll;">
        </div>
        <span id="spantext" class="text-center" style="color:blue;font-size:12px;font-weight:bold;"></span>
        <div id="progress" class="progress" style="margin-bottom:5px;">
            <div id="progressbar" class="progress-bar" style="width:0%">0%</div>
        </div>
        <button id="dl" class="btn btn-sm trf-btn">Download</button>
        <a id="home" href="index.php" class="btn btn-sm trf-btn" style="margin-top:5px;">Back</a>

        <iframe id="myiframe" width="100%" height="100" style="border: none;"></iframe>
    </div>


    <script src="js/jquery/jquery-3.6.0.js"></script>
    <script src="js/bootstrap/bootstrap.min.js"></script>


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
    viewall();
    document.getElementById("spantext").style.display = "none";
    document.getElementById("progress").style.display = "none";
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
</script>

<script>
var printerip = document.getElementById('printer_ip');

</script>

<script>

//DOWNLOAD
var btn = document.querySelector('#dl');
var myurls = [];
btn.addEventListener('click', (event) => {

    disableBTN();

    var checkboxes = document.querySelectorAll('input[name="trfs"]:checked');
    var values = [];

    var trfbch = ""; //STRING TYPE

    checkboxes.forEach((checkbox) => {
        trfbch += checkbox.value + ',';
    });

    console.log(trfbch);

    if(trfbch != ""){     
        document.getElementById("myiframe").src = 'fx/downloadpdf2.fx.php?q='+ String(trfbch);
        
    }else{
        alert("Please Select TRF.");
        document.getElementById("spantext").style.display = "none";
        document.getElementById("progress").style.display = "none";
        enableBTN();
    }

});

function addtoarray(t){
    myurls.push(t);
    window.open(t, '_blank');
    document.getElementById('dl').disabled = false;
};



        //PROGRESS BAR
        function uptprogressbar(percent){
            let progressbar = document.getElementById("progressbar");
            progressbar.style["width"] = percent+'%';
            progressbar.innerHTML = percent.toFixed(2)+'%';
        }



        function disableBTN()
        {
            document.getElementById("spantext").style.display = "block";
            document.getElementById("progress").style.display = "block";
            document.getElementById('spantext').innerHTML = "Requesting data from the Server.";
            document.getElementById('dl').classList.add("disabled");
            document.getElementById('home').classList.add("disabled");
        }

        function enableBTN()
        {
            document.getElementById('dl').classList.remove("disabled");
            document.getElementById('home').classList.remove("disabled");
        }


  
</script>
</body>
</html>