<?php
if (isset($_REQUEST['ip'])) {
    session_start();
    date_default_timezone_set('Asia/Manila');
    $ip = $_REQUEST['ip'];
    $local_servername = $ip;
    $local_username = "rtv";
    $local_password = "rtvadmin";

    try {
    $conn = new PDO("mysql:host=$local_servername", $local_username, $local_password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        echo "Connected";
        $_SESSION['rtv_server_ip'] = $ip;
    } catch(PDOException $e) {
        echo "noconnection";
        unset($_SESSION['rtv_server_ip']);
    }
}

?>