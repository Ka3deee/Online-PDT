<!DOCTYPE html>
<html lang="en">
<head>
    <title>PDT Application : Transfer Releasing</title>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="../images/favicon.ico"/>
    <link rel="stylesheet" href="assets/css/main.css">
    <link rel="stylesheet" href="assets/css/animate.css">
</head>

<body>
    <div class="display-center" style="width: ">
        <div class="display-center"> 
            <div class="display-center">
                <img src="../resources/lcc.jpg" alt="LCC Logo">
            </div>
            <h4 class="tc font mb">TRF Releasing</h4>
            <h5 class="tc semi-visible">v1.0.0</h5>
            <br>
            <br>
        </div>
        <div class="display-center">
            <div class="mb w">
                <div class="msg">Please set store first!</div>
            </div>  
            <div class="mb w">
                <label for="store-code"></label>
                <input maxlength="5" size="5" onkeypress="if ( isNaN(this.value + String.fromCharCode(event.keyCode) )) return false;" class="btn-lg" id="store-code" type="text">
            </div>
            <div class="mb w">
                <button class="btn btn-lg">Save</button>
            </div>
            </div>
            <div class="mb w">
                <button onclick="window.location.href='../trf_out/index.php'" class="btn btn-lg">Back</button>
            </div>
        </div>

        <div id="preloader">
            <div class="caviar-load"></div>
        </div>
    </div>
</body>
<script>
    function Checkstore(){
    var store = document.getElementById('store-code').value;
    if(store == ""){
        alert("Please Enter Store code");
        return 0;
    }
    var response;
    const xhttp = new XMLHttpRequest();
    xhttp.onload = function() {
        response =  this.responseText;	
        // document.getElementById('loadingalert').style = "display:none";
        if(response == "no result"){	
        alert("Invalid store code. Please Try Again");
        location.reload();			
        }else{
        var  storedetails = response.split("-");
        // document.getElementById('btnsetstore').disabled = false;
        location.reload();
        }			
    }
    // document.getElementById('btnsetstore').disabled = true;
    // document.getElementById('loadingalert').style = "display:block";
    xhttp.open("GET", "controllers/get_store.php?check_store="+store);
    xhttp.send();
    }
</script>
<script src="assets/js/animate.js"></script>
</html>