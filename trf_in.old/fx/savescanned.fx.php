<?php
session_start();
require('../Classes/DatabaseClass.php');
require('getOStype.php');
require('readtextfile.php');

if(isset($_GET['trfbchid'])){
    $trfbchid = $_GET['trfbchid'];
    $rcvqty = $_GET['rcvqty'];
    $oldqty = floatval($_GET['oldqty']);
    $expqty = $_GET['expqty'];
    $trfnum = $_GET['trfnum'];

    $newqty = $rcvqty + $oldqty;
    if($newqty > $expqty){
        echo json_encode(array('success' => 0));
        exit();
    }else{

        $ip = "";
        if($type == 'Android'){
            $ip = $_SERVER['REMOTE_ADDR'];
        }else{
            $ip = readtxtfile();
        }

        $str = $_SESSION['strcode'];
    
        $obj = new DatabaseClass();
        $res = $obj->mysql_update_trf($ip,$str,$trfbchid,$newqty,$trfnum);
    
        if($res == true){
            echo json_encode(array('success' => 1));
        }else{
            echo json_encode(array('success' => 0));
        }
    }


}