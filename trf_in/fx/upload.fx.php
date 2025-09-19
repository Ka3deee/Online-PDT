<?php
date_default_timezone_set('Asia/Manila');
set_time_limit(0);

session_start();

echo '<style>
.message {
    color:red;
    font-weight:bold;
    font-size:12px;
    font-family : sans-serif;
}

</style>';

echo '<script>parent.document.getElementById("spantext").innerHTML = "Uploading data from the MMS Server...";</script>';

require('../Classes/DatabaseClass.php');
require('../Classes/DatabaseClass2.php');
require('../Classes/MMSDatabaseClass.php');
require('readtextfile.php');
date_default_timezone_set('Asia/Manila');

//$ip = $_SERVER['REMOTE_ADDR'];
$ip = readtxtfile();
$str = $_SESSION['strcode'];
$currentDate = date('ymd');

/*
* MMS CONNECTION SETTINGS
*/
//FOR MMS
$mms_host_ip      = "192.168.0.40";

// Development env
//$mms_dbase        = "mmsmtsml";

// Prod env
$mms_dbase        = "mmlciobj";

$mms_uname        = "studentwhs";
$mms_pass         = "studentwhs";

$tbl = "LCC1718PF";

// Development 
//$mmslib = "mmsmtsml";

// Prod
$mmslib = "mmsmrlib";

//MMS CONNECTION STRING
$mmsconn = odbc_connect(
    "DRIVER=Client Access ODBC Driver (32-bit);
    System=".$mms_host_ip.";
    DBQ=".$mms_dbase, $mms_uname, $mms_pass
);

// START :: CREATING TABLES
$obj = new DatabaseClass();
$obj2 = new DatabaseClass2();


$mtrarray = $obj->getMTRs($str);

$cntr = 1;
$arrycnt = count($mtrarray);

for($i = 0; $i < $arrycnt; $i++){
    $progressbar = $cntr / $arrycnt * 100;
    $progressbar = number_format($progressbar,2);

    $mtr = $mtrarray[$i]['mtr_no'];
    $trfbcharray = $obj->getTransferAll($str, $mtr);


    for($e = 0; $e < count($trfbcharray); $e++)
    {
        //individual transfer
        $lctrfbch = $trfbcharray[$e]['lctrfbch'];
        $lcqty = $trfbcharray[$e]['lcqty'];
        $lcsku = $trfbcharray[$e]['lcsku'];
        
        // insert to mms
        $qry = "INSERT INTO ".$mmslib.".".$tbl."(LCMTRNO,LCTRFBCH,LCSTRNUM,LCSKU,LCQTY,LCDAT)VALUES('".$mtr."','".$lctrfbch."',".$str.",".$lcsku.",".$lcqty.",".$currentDate.")";
        //$qry = "UPDATE ".$mmslib.".".$tbl." SET LCQTY = ".$lcqty." WHERE LCMTRNO='".$lcmtrno."'";
        //echo $qry;
        
        $result = odbc_exec($mmsconn,$qry);
        
        if ($result){
            // update tblmtr_dtl in the mtr_central
            $obj2->updateBatch($lctrfbch);
        }else {
            echo "Query failed " .odbc_error();
        }   
        
    }

    $obj2->updateMTRCentralHeaderCIP($mtr,3);
    $cntr += 1;

    echo '<script>parent.uptprogressbar('.$progressbar.');</script>';

    ob_flush(); 
    flush();

}


echo '<script>parent.document.getElementById("spantext").innerHTML = "Upload done.";</script>';
echo '<script>parent.enableBTN();</script>';
