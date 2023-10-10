<?php 
    session_start();
    unset($_SESSION['message']);
    unset($_SESSION['type']);
    include('../path.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>PDT Application : Transfer Releasing</title>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="../assets/images/favicon.ico"/>
    <link rel="stylesheet" href="../assets/css/main.css">
    <link rel="stylesheet" href="../assets/css/animate.css">
</head>

<body>
    <div class="display-center">
        <div class="display-center"> 
            <div class="display-center">
                <img src="../assets/images/lcc.jpg" alt="LCC Logo">
            </div>
            <h4 class="tc font mb">TRF Releasing : Transfer Out Data Download</h4>
            <h5 class="tc semi-visible">v1.0.0</h5>
            <br>
            <br>
        </div>
        <div class="display-center">
            <label for="myTextarea" class="mb tc">Transfer No :</label>
            <div>
                <textarea class="w-md textarea mb" id="myTextarea" name="myTextarea" rows="8" cols="50" onkeypress="transfersOnly(event)"></textarea>
            </div>
            <button class="btn btn-md primary mb">Download Data from MMS</button>
            <button class="btn btn-md primary mb">Download Data as TxT</button>
            <button class="btn btn-md primary mb">Back</button>
            
            <div id="response"></div>
        </div>

        <div id="preloader">
            <div class="caviar-load"></div>
        </div>
    </div>
    <br>
    <br>
</body>
<script src="../assets/js/validate.js"></script>
<script src="../assets/js/animate.js"></script>
<script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
<script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
</html>