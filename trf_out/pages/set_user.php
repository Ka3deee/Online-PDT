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
            <h4 class="tc font mb">TRF Releasing : Set User</h4>
            <h5 class="tc semi-visible">v1.0.0</h5>
            <br>
            <br>
        </div>
        <div class="display-center">
            <form id="set-user">
                <div id="loader-wrapper" class="mb w">
                    <div class="loader"></div><strong>Checking user... Please wait...</strong>
                </div>
                <div class="mb w">
                    <?php include(ROOT_PATH . '/controllers/helpers/user.php'); ?>
                </div>  
                <div class="mb w tc">
                    <label for="employee_no">EE No.</label>
                    <input class="btn-lg" maxlength="5" size="5" onkeypress="if ( isNaN(this.value + String.fromCharCode(event.keyCode) )) return false;" name="employee_no" id="employee_no" type="text">
                </div>
                <div class="mb w tc">
                    <label for="password">Password</label>
                    <input class="btn-lg" name="password" id="password" type="password">
                </div>
                <div class="mb w">
                    <button onclick="SetUser()" type="submit" class="btn btn-lg primary flex a-center j-center"><ion-icon name="checkmark"></ion-icon>&nbsp; Set</button>
                </div>
                <div class="mb w">
                    <button onclick="CheckAdmin()" type="button" class="btn btn-lg primary flex a-center j-center" id="maintenance-btn"><ion-icon name="people"></ion-icon>&nbsp; User Maintenance</button>
                </div>
                </div>
                <div class="mb w">
                    <button onclick="window.location.href='../../trf_out/index.php'" type="button" class="btn btn-lg primary flex a-center j-center"><ion-icon name="arrow-undo"></ion-icon>&nbsp; Back</button>
                </div>
            </form>
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