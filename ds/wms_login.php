<?php
    include 'fn.php';
    if (!isset($_SESSION['dss_ip']) || !isset($_SESSION['dss_user']) || !isset($_SESSION['dss_pass'])) {
        header("Location: server_login.php");
    }
    else if (isset($_SESSION['dss_ip']) && isset($_SESSION['dss_user']) && isset($_SESSION['dss_pass']) && isset($_SESSION['dsw_user']) && isset($_SESSION['dsw_pass'])) {
        header("Location: ./");
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
    </style>
</head>  
<body >

<div class="container text-center">
    <img src="../resources/lcc.png">
    <h4>LCC Data Terminal Applications</h4>
    <h4><?php echo $_SESSION['srname']; ?></h4>
    <h4>WMS Log in</h4>
    <h6 style="color: red" id="show_errmsg"></h6>

    
    <form method="POST" style="margin-bottom: 10px;">
        <table class="table table-bordered table-sm">
            <tbody>
                <tr>
                    <td class="text-right" style="padding-top: 15px;">Username</td>
                    <td><input type="text" class="form-control" name="dsw_user" placeholder="Username" autofocus autocomplete="nope"></td>
                </tr>
                <tr>
                    <td class="text-right" style="padding-top: 15px;">Password</td>
                    <td><input type="password" class="form-control" name="dsw_pass" placeholder="Password" autocomplete="nope"></td>
                </tr>
            </tbody>
        </table>
        
        <button type="submit" class="btn btn-md btn-primary btn-block" name="login">Log in</button>
    </form>

    <button type="button" class="btn btn-primary btn-md btn-block" onclick="window.location.href='?change_server'">
        <span class="glyphicon glyphicon-home"></span> Change Server
    </button>
    <button type="button" class="btn btn-primary btn-md btn-block" onclick="window.location.href='../'">
        <span class="glyphicon glyphicon-log-out"></span> Back to Menu
    </button>
</div>

<div id="preloader">
    <div class="caviar-load"></div>
</div>

<?php
    if (isset($_POST['login'])) {
        $dsw_user = trim($_POST['dsw_user']);
        $dsw_pass = trim($_POST['dsw_pass']);

        // VALIDATE FIELDS
        $errmsg = '';
        if (empty($dsw_user)) $errmsg = 'Invalid Username';
        else if (empty($dsw_pass)) $errmsg = 'Invalid Password';
        
        if (!empty($errmsg)) {
            echo '<script>document.getElementById("show_errmsg").innerHTML="'.$errmsg.'";</script>';
        }
        else {
            echo '<script>document.getElementById("show_errmsg").innerHTML="";</script>';

            // SET SESSION
            $_SESSION['dsw_user'] = $dsw_user;
            $_SESSION['dsw_pass'] = $dsw_pass;

            // CHECK IF USER EXISTS
            include 'wms_connect.php';
            
            // REDIRECT IF LOGIN SUCCESSFUL
            echo '<script>if (document.getElementById("show_errmsg").innerHTML == "") window.location.href = "./";</script>';
        }

        echo '<script>document.getElementsByName("dsw_user")[0].value="'.$dsw_user.'";</script>';
        echo '<script>document.getElementsByName("dsw_pass")[0].value="'.$dsw_pass.'";</script>';
    }
?>

 
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