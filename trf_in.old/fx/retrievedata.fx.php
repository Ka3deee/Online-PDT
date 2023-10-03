<?php
session_start();
require('../Classes/DatabaseClass.php');
require('getOStype.php');
require('readtextfile.php');

$obj = new DatabaseClass();

if($_POST['action'] == "1"){
    $serverip = readtxtfile();
    $str = $_SESSION['strcode'];
    $ip = $_SERVER['REMOTE_ADDR'];

    if($obj->checkStoreTable($str)){
        if($obj->checkServerTable($serverip)){
            /*
            Creating pdt table, if table exist it will be truncated
            */
            $obj->createTempTable2($ip);
    
            // Start :: Migrate data from server to pdt table
            $arraytables = ["_batchtransfer_download_logs_tbl","_batchtransfer_tbl","_iupc_tbl"];
            for($i=0;$i < count($arraytables);$i++){
                $servertbl = $serverip.''.$arraytables[$i];
                $pdttbl = $ip.''.$arraytables[$i];
                $obj->migrateData($servertbl, $pdttbl);
                //echo $tblname.'<br>';
            }
            // End :: Done migrating
            echo json_encode(array('success' => 1));
        }else{
            echo json_encode(array('success' => 0));
        }
    }else{
        echo json_encode(array('success' => 2));
    }

}
