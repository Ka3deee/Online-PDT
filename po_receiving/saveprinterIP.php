<?php
//set Printer IP

if(isset($_REQUEST['ip'])){
    session_start();
    $_SESSION['printer_ip'] = $_REQUEST['ip'];
    echo "inserted";
}
?>