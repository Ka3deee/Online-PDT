<?php
    include 'fn.php';
    if (!isset($_SESSION['dss_ip']) || !isset($_SESSION['dss_user']) || !isset($_SESSION['dss_pass']) || !isset($_SESSION['dsw_user']) || !isset($_SESSION['dsw_pass'])) {
        header("Location: server_login.php");
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>NEW PDT APPLICATIONS</title>
    <meta charset="UTF-8">
    <meta name="description" content="">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="shortcut icon" href="../images/favicon.ico"/>
    <link rel="bookmark" href="../images/favicon.ico"/>
    <link rel="stylesheet" href="../bootstrap-3.4.1-dist/css/bootstrap.min.css">
    <!---   Content Styles -->
    <link href="../mycss.css" rel="stylesheet">
</head>  
<body >

<div class="container text-center">
    <img src="../resources/lcc.png">
    <h4>LCC Data Terminal Applications</h4>
    <h5><strong>Welcome <?php echo $_SESSION['wms_status_user'] ?></strong></h5>

    <div style="margin-bottom: 13px;">
        <button type="button" class="btn btn-primary btn-md btn-block" onclick="window.location.href='./mssr'">MSSR Receiving</button>
        <button type="button" class="btn btn-primary btn-md btn-block" onclick="window.location.href='./direct_receiving'">Direct Receiving</button>
        <button type="button" class="btn btn-primary btn-md btn-block" onclick="window.location.href='./trf'">TRF Line Inspection</button>
        <button type="button" class="btn btn-primary btn-md btn-block" onclick="window.location.href='./direct_line_inspection'">Direct Line Inspection</button>
        <button type="button" class="btn btn-primary btn-md btn-block" onclick="window.location.href='./report_exception'">Report Exception</button>
    </div>

    <div>
        <button type="button" class="btn btn-primary btn-md btn-block" onclick="window.location.href='?change_server'">
            <span class="glyphicon glyphicon-home"></span> Change Server
        </button>
        <button type="button" class="btn btn-primary btn-md btn-block" onclick="window.location.href='?change_user'">
            <span class="glyphicon glyphicon-user"></span> Change User
        </button>
        <button type="button" class="btn btn-primary btn-md btn-block" onclick="window.location.href='../'">
            <span class="glyphicon glyphicon-log-out"></span> Back to Menu
        </button>
    </div>
</div>

<div id="preloader">
    <div class="caviar-load"></div>
</div>

 
</body>
    <!-- Jquery-2.2.4 js -->
    <script src="../js/jquery/jquery-2.2.4.min.js"></script>
    <!-- Popper js -->
    <script src="../js/bootstrap/popper.min.js"></script>
    <!-- Bootstrap-4 js -->
    <script src="../js/bootstrap/bootstrap.min.js"></script>
    <!-- All Plugins js -->
    <script src="../js/others/plugins.js"></script>
    <!-- Active JS -->
    <script src="../js/active.js"></script>

</html>