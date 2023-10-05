<?php 
    session_start(); 
    if (isset($_SESSION['store-code'])) {
        echo "<script>var isStoreSet = true;</script>";
    } else {
        echo "<script>var isStoreSet = false;</script>";
    }
    // include('database/connect_mms.php');
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
    <?php if ($conn_m != null && $db_name == 'mmsmrlib' || $db_name == 'mmlciobj') { ?>
        <div class="success mb tc">Connection Successful !</div>
    <?php } else if ($conn_m != null && $db_name == 'mmsmtsml') { ?>
        <div class="success mb tc">Connected to MMS test environment</div>
    <?php } else { ?>
        <div class="error mb tc">Check connection settings !</div>
    <?php } ?>

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
                <?php if (isset($_SESSION['store-code'])) { ?>
                    <div class="msg success"><?php echo $_SESSION['store-code'] . " - " . $_SESSION['store-loc']; ?></div>
                <?php } else { ?>
                    <div class="msg warning">Please set store code</div>
                <?php } ?>
            </div>         
            <div class="mb w">
                <div class="msg warning">Please set a user</div>
            </div>
            <div class="mb w grid-2">
                <button onclick="window.location.href='set_store.php'" class="btn btn-md">Set Store</button>
                <button onclick="window.location.href='set_user.php'" class="btn btn-md">Set User</button>
            </div>
            <div class="mb w">
                <button onclick="StoreIsSet()" class="btn btn-md">Download Transfer Out Data</button>
            </div>
            <div class="mb w">
                <button onclick="alert('Coming soon...')" class="btn btn-md">Manual Recording</button>
            </div>
            <div class="mb w">
                <button onclick="alert('Coming soon...')" class="btn btn-md">Export PDF</button>
            </div>
            <div class="mb w">
                <button onclick="window.location.href='../smr.php'" class="btn btn-md">Exit</button>
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
</body>
<script>
    function StoreIsSet() {
        if (isStoreSet == false) { 
            alert('Please set store first'); 
        } else { 
            window.location.href='download_trfout.php'; 
        }
    }
</script>
<script src="assets/js/animate.js"></script>
<script src="assets/js/validate.js"></script>
</html>