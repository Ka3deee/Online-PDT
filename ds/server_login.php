<?php
    include 'fn.php';
    if (isset($_SESSION['dss_ip']) && isset($_SESSION['dss_user']) && isset($_SESSION['dss_pass']) && (!isset($_SESSION['dsw_user']) || !isset($_SESSION['dsw_pass']))) {
        header("Location: wms_login.php");
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
</head>  
<body >

<div class="container text-center">
    <img src="../resources/lcc.png">
    <h4>DS Applications</h4>
    <br>
    <h4>Connect to Server</h4>
    <h6 style="color: red" id="show_errmsg"></h6>
    
    <form method="POST" style="margin-bottom: 10px;">
        <table class="table table-bordered table-sm">
            <tbody>
                <tr>
                    <td class="text-right" style="padding-top: 15px;">IP Address</td>
                    <td><input type="text" class="form-control" name="dss_ip" placeholder="IP Address" autofocus></td>
                </tr>
                <tr>
                    <td class="text-right" style="padding-top: 15px;">Store Name</td>
                    <td><input type="text" class="form-control" name="srname" placeholder="Store Name" autofocus></td>
                </tr>
                <tr>
                    <td class="text-right" style="padding-top: 15px;">Username</td>
                    <td><input type="text" class="form-control" name="dss_user" placeholder="Username" autocomplete="off"></td>
                </tr>
                <tr>
                    <td class="text-right" style="padding-top: 15px;">Password</td>
                    <td><input type="password" class="form-control" name="dss_pass" placeholder="Password" autocomplete="off"></td>
                </tr>
            </tbody>
        </table>
        
        <button type="submit" class="btn btn-md btn-primary btn-block" name="connect">Connect</button>
    </form>

    <button type="button" class="btn btn-primary btn-md btn-block" onclick="window.location.href='../'">
        <span class="glyphicon glyphicon-log-out"></span> Back to Menu
    </button>
</div>

<div id="preloader">
    <div class="caviar-load"></div>
</div>

<?php
    if (isset($_POST['connect'])) {
        $dss_ip   = trim($_POST['dss_ip']);
        $srname   = trim($_POST['srname']);
        $dss_user = trim($_POST['dss_user']);
        $dss_pass = trim($_POST['dss_pass']);

        // VALIDATE FIELDS
        $errmsg = '';
        if (empty($dss_ip)) $errmsg = 'Invalid IP Address';
        if (empty($srname)) $errmsg = 'Invalid Store Name';
        else if (empty($dss_user)) $errmsg = 'Invalid Username';
        // else if (empty($dss_pass)) $errmsg = 'Invalid Password';
        
        if (!empty($errmsg)) {
            echo '<script>document.getElementById("show_errmsg").innerHTML="'.$errmsg.'";</script>';
        }
        else {
            echo '<script>document.getElementById("show_errmsg").innerHTML="";</script>';

            // SET SESSION
            $_SESSION['dss_ip']   = $dss_ip;
            $_SESSION['srname']   = $srname;
            $_SESSION['dss_user'] = $dss_user;
            $_SESSION['dss_pass'] = $dss_pass;

            // CONNECT TO DATABASE
            include 'server_connect.php';
            
            // REDIRECT IF CONNECTION SUCCESSFUL
            echo '<script>if (document.getElementById("show_errmsg").innerHTML == "") window.location.href = "wms_login.php";</script>';
        }
        
        echo '<script>document.getElementsByName("dss_ip")[0].value="'.$dss_ip.'";</script>';
        echo '<script>document.getElementsByName("srname")[0].value="'.$srname.'";</script>';
        echo '<script>document.getElementsByName("dss_user")[0].value="'.$dss_user.'";</script>';
        echo '<script>document.getElementsByName("dss_pass")[0].value="'.$dss_pass.'";</script>';
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