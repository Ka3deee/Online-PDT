<?php 
    session_start(); 
    include('controllers/validate.php');
    include('database/connect_mms.php');
?>
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
    <?php include('controllers/helpers/env_mms.php'); ?>
    <div class="display-center">
        <div class="display-center"> 
            <div class="display-center mb">
                <img src="../resources/lcc.jpg" alt="LCC Logo">
            </div>
            <h4 class="tc font">TRF Releasing</h4>
            <h5 class="tc semi-visible">v1.0.0</h5>
            <br>
            <br>
        </div>
        <div class="display-center">            
            <div class="mb w">
                <?php include('controllers/helpers/store.php'); ?>
            </div>         
            <div class="mb w">
                <?php include('controllers/helpers/user.php'); ?>
            </div>
            <div class="mb w grid-2">
                <button onclick="window.location.href='set_store.php'" class="btn primary p-hover btn-md">Set Store</button>
                <button onclick="window.location.href='set_user.php'" class="btn primary p-hover btn-md">Set User</button>
            </div>
            <div class="mb w">
                <button onclick="StoreIsSet()" class="btn primary p-hover btn-md">Download Transfer Out Data</button>
            </div>
            <div class="mb w">
                <button onclick="alert('Coming soon...')" class="btn primary p-hover btn-md">Manual Recording</button>
            </div>
            <div class="mb w">
                <button onclick="alert('Coming soon...')" class="btn primary p-hover btn-md">Export PDF</button>
            </div>
            <div class="mb w">
                <button onclick="window.location.href='../smr.php'" class="btn primary p-hover btn-md">Exit</button>
            </div>
            <br>
            <hr>
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
</html>