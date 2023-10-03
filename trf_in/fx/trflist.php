<?php
session_start();
require('../Classes/DatabaseClass.php');
require('getOStype.php');
require('readtextfile.php');

if(isset($_GET['view'])){
    if($_GET['view'] == 'all'){
        $str = $_SESSION['strcode'];

        $ip = "";
        if($type == 'Android'){
            $ip = $_SERVER['REMOTE_ADDR'];
        }else{
            $ip = readtxtfile();
        }
 
        $obj = new DatabaseClass();
        $array = $obj->mysql_get_trfs_all($str);

        echo json_encode($array);
    }

    if($_GET['view'] == 'byip'){
        $str = $_SESSION['strcode'];
        $ip = "";
        if($type == 'Android'){
            $ip = $_SERVER['REMOTE_ADDR'];
        }else{
            $ip = readtxtfile();
        }
        $obj = new DatabaseClass();
        $array = $obj->mysql_get_trfs($ip,$str);

        echo json_encode($array);
    }
}