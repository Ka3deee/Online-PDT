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
                <?php include('controllers/helpers/store.php'); ?>
            </div>  
            <div class="mb w tc">
                <label for="store-code">Store Code</label>
                <input maxlength="5" size="5" onkeypress="if ( isNaN(this.value + String.fromCharCode(event.keyCode) )) return false;" class="btn-lg" id="store-code" type="text">
            </div>
            <div class="mb w">
                <button class="btn btn-lg primary" onclick="CheckStore()" id="save-btn">Save</button>
            </div>
            </div>
            <div class="mb w">
                <button onclick="window.location.href='../trf_out/index.php'" class="btn btn-lg primary">Back</button>
            </div>
        </div>

        <div id="preloader">
            <div class="caviar-load"></div>
        </div>
    </div>
    <br>
    <br>
</body>
<script src="assets/js/validate.js"></script>
<script src="assets/js/animate.js"></script>
</html>