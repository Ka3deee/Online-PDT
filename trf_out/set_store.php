<?php session_start(); ?>
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
            <h4 class="tc font mb">TRF Releasing : Set Store</h4>
            <h5 class="tc semi-visible">v1.0.0</h5>
            <br>
            <br>
        </div>
        <div class="display-center">
            <div id="loader-wrapper" class="mb w">
                <div id="loader"></div><strong>Checking store code... Please wait...</strong>
            </div>
            <div class="mb w">
                <?php if (isset($_SESSION['store-code'])) { ?>
                    <div class="msg success"><?php echo $_SESSION['store-code'] . " - " . $_SESSION['store-loc']; ?></div>
                <?php } else { ?>
                    <div class="msg warning">Please set store code</div>
                <?php } ?>
            </div>  
            <div class="mb w">
                <label for="store-code"></label>
                <input maxlength="5" size="5" onkeypress="if ( isNaN(this.value + String.fromCharCode(event.keyCode) )) return false;" class="btn-lg" id="store-code" type="text">
            </div>
            <div class="mb w">
                <button class="btn btn-lg" onclick="CheckStore()" id="save-btn">Save</button>
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
    function CheckStore(){
        var store = document.getElementById('store-code').value;
        document.getElementById('loader-wrapper').style = 'display:flex';
        if(store == ""){
            alert("Please enter store code");
            return 0;
        }
        var response;
        const xhttp = new XMLHttpRequest();
        xhttp.onload = function() {
            response =  this.responseText;
            if (response == "no result") {	
                alert("Invalid store code. Please Try Again");
                location.reload();			
            } else {
                var storedetails = response.split("-");
                document.getElementById('save-btn').disabled = false;
                location.reload();
            }			
        }
        document.getElementById('save-btn').disabled = true;
        xhttp.open("GET", "controllers/get_store.php?check_store=" + store);
        xhttp.send();
    }
</script>
<script src="assets/js/animate.js"></script>
</html>