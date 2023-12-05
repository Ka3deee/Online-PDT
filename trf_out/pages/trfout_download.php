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
    <div id="download-animation-wrapper" class="download-animation-wrapper hidden">
    <div class="page">
        <div class="folder">
        <span class="folder-tab"></span>
        <div class="folder-icn">
            <div class="downloading">
            <span class="custom-arrow"></span>
            </div>
            <div class="bar"></div>
        </div>
        </div>
    </div>
    </div>
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
            
            <div id="loader-wrapper" class="mb w-md">
                <div class="loader"></div><strong>Checking store code... Please wait...</strong>
            </div>
            <div id="loader-download" class="mb w-md">
                <div class="loader"></div><strong>Downloading Data... Please wait...</strong>
            </div>
            <div class="mb w-md"><?php include(ROOT_PATH . '/controllers/helpers/store.php'); ?></div>
            <div class="mb w-md grid-2">
                <input id="store-num" type="text" class="textarea" placeholder="Store Code" onkeydown="CheckStore(event)">
                <input id="doc-num" type="text" class="textarea" placeholder="Document No." onkeypress="docNo(event)">
                <!-- <button onclick="window.location.href='set_store.php'" class="btn primary p-hover btn-md flex a-center j-center"><ion-icon name="storefront"></ion-icon>&nbsp;&nbsp; Set Store</button> -->
            </div>
            <div class="mb w-md grid-auto">
                <textarea class="textarea" id="trf-out-list" name="trf-out-list" rows="7" cols="50"></textarea>
                <button type="button" class="btn btn-vt delete" onclick="Clear()"><ion-icon name="trash-outline"></ion-icon></button>
            </div>
            <button class="btn btn-md primary mb w-md" onclick="Download()">Download Data from MMS</button>
            <button class="btn btn-md primary mb w-md" onclick="saveTextFile()">Download Data as TxT</button>
            <button class="btn btn-md primary mb w-md" onclick="window.location.href='../index.php'">Back</button>
            
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