<?php 
    session_start();
    include('path.php');
    include(ROOT_PATH . '/controllers/validate.php');
    include(ROOT_PATH . '/databases/connect_mms.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>PDT Application : Transfer Releasing</title>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="assets/images/favicon.ico"/>
    <link rel="stylesheet" href="assets/css/main.css">
    <link rel="stylesheet" href="assets/css/animate.css">
</head>

<body>
    <?php include('controllers/helpers/env_mms.php'); ?>
    <div class="display-center">
        <div class="display-center"> 
            <div class="display-center mb">
                <img src="assets/images/lcc.jpg" alt="LCC Logo">
            </div>
            <h4 class="tc font">TRF Releasing</h4>
            <h5 class="tc semi-visible">v1.0.0</h5>
            <br>
            <br>
        </div>
        <div class="display-center">
            <div class="mb w">
                <?php include(ROOT_PATH . '/controllers/helpers/user.php'); ?>
            </div>
            <div class="mb w">
                <!-- <button onclick="window.location.href='pages/set_store.php'" class="btn primary p-hover btn-md flex a-center j-center"><ion-icon name="storefront"></ion-icon>&nbsp;&nbsp; Set Store</button> -->
                <button onclick="window.location.href='pages/set_user.php'" class="btn primary p-hover btn-md flex a-center j-center"><ion-icon name="person"></ion-icon>&nbsp;&nbsp; Set User</button>
            </div>
            <div class="mb w">
                <button onclick="openDownload()" class="btn primary p-hover btn-md flex a-center j-center"><ion-icon name="cloud-download"></ion-icon>&nbsp;&nbsp; TRF Out Data Download</button>
            </div>
            <div class="mb w">
                <button onclick="alert('Coming soon...')" class="btn primary p-hover btn-md flex a-center j-center"><ion-icon name="scan"></ion-icon>&nbsp;&nbsp; Scan Items</button>
            </div>
            <div class="mb w">
                <button onclick="alert('Coming soon...')" class="btn primary p-hover btn-md flex a-center j-center"><ion-icon name="print"></ion-icon>&nbsp;&nbsp; Export PDF</button>
            </div>
            <div class="mb w">
                <button onclick="confirmExit()" class="btn primary p-hover btn-md flex a-center j-center"><ion-icon name="arrow-back-circle"></ion-icon>&nbsp;&nbsp; Exit</button>
            </div>
            <br>
            <div class="tc semi-visible">
                <h5>Date updated: 2023 October</h5>
            </div>
        </div>

        <div id="preloader">
            <div class="caviar-load"></div>
        </div>
    </div>
    <br>
    <br>
</body>

<script src="assets/js/animate.js"></script>
<script src="assets/js/validate.js"></script>
<script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
<script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
</html>