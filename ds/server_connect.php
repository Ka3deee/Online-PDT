<?php

date_default_timezone_set('Asia/Manila');
$local_servername = $_SESSION['dss_ip'];
$local_srname     = $_SESSION['srname'];
$local_username   = $_SESSION['dss_user'];
$local_password   = $_SESSION['dss_pass'];
$local_dbname     = 'sr_db';

try {
    $conn = new PDO("mysql:host=$local_servername;dbname=$local_dbname", $local_username, $local_password);
    // set the PDO error mode to exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  
} catch(PDOException $e) {
    // echo "Connection failed: " . $e->getMessage();
    unset($_SESSION['dss_ip']);
    unset($_SESSION['srname']);
    unset($_SESSION['dss_user']);
    unset($_SESSION['dss_pass']);
    echo '<script>document.getElementById("show_errmsg").innerHTML="Connection failed: '.$e->getMessage().'";</script>';
}

?>