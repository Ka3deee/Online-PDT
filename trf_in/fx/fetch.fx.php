<?php
session_start();

require('../Classes/DatabaseClass.php');
require('../Classes/MMSDatabaseClass.php');
require('getOStype.php');
require('readtextfile.php');

if(isset($_GET['iupc'])){
    $barcode = $_GET['iupc'];
    $str = $_SESSION['strcode'];

    $ip = "";
    if($type == 'Android'){
        $ip = $_SERVER['REMOTE_ADDR'];
    }else{
        $ip = readtxtfile();
    }


    $obj = new DatabaseClass();
    $result = $obj->selectitrfs($barcode,$str,$ip);

    echo json_encode($result);

}