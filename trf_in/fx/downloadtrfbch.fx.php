<?php

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

echo '<script>parent.document.getElementById("spantext").innerHTML = "Downloading data from the MMS Server...";</script>';

require('../Classes/DatabaseClass.php');
require('../Classes/MMSDatabaseClass.php');
require('readtextfile.php');
date_default_timezone_set('Asia/Manila');

//$ip = $_SERVER['REMOTE_ADDR'];
$ip = readtxtfile();
$str = $_SESSION['strcode'];

/*
* MMS CONNECTION SETTINGS
*/
//FOR MMS
$mms_host_ip      = "192.168.0.40";
$mms_dbase        = "mmsmtsml";
$mms_uname        = "studentwhs";
$mms_pass         = "studentwhs";

//MMS CONNECTION STRING
$mmsconn = odbc_connect(
    "DRIVER=Client Access ODBC Driver (32-bit);
    System=".$mms_host_ip.";
    DBQ=".$mms_dbase, $mms_uname, $mms_pass
);

// START :: CREATING TABLES
$obj = new DatabaseClass();
$obj->createTempTable($ip, $str);
$mms_obj = new MMSDatabaseClass();
$trfbchs = $_REQUEST['q'];

$trfbcharray = explode(",",$trfbchs);

$cntr = 1;
$arrycnt = count($trfbcharray) - 1;

for($e = 0; $e < count($trfbcharray) - 1; $e++)
{
    $progressbar = $cntr / $arrycnt * 100;
    $progressbar = number_format($progressbar,2);
    //individual transfer
    $trfbchx = $trfbcharray[$e];
    if($obj->checknoduplicate($ip,$trfbchx,$str)==false){
        echo '<div class="message">Transfer "'.$trfbchx.'" Already downloaded.</div>';
        $cntr += 1;
        continue;
    }

    $qry = "call ".$mms_dbase.".spgettrf(".$trfbchx.")";
    $result = odbc_exec($mmsconn,$qry);

    $insert_trfip = "INSERT INTO `".$ip."_batchtransfer_tbl`(`trfbch`, `inumbr`, `idescr`, `trfshp`, `istdpk`, `rcvqty`, `expqty`, `strcode`) VALUES ";
    $insert_values_trfip = "";
    $insert_trf= "INSERT INTO `".$str."_received_batch_trf_tbl`(`trfbch`, `inumbr`, `idescr`, `trfshp`, `istdpk`, `rcvqty`, `expqty`) VALUES ";
    $insert_values_trf = "";

    while($res = odbc_fetch_array($result))
    {
        if(!empty($res)){

            $trfbch = $res['TRFBCH'];
            $inumbr = $res['INUMBR'];
            $idescr = $res['IDESCR'];
            $idescr = str_replace(',','',$idescr);
            $idescr = str_replace('&','',$idescr);
            $idescr = str_replace("'",'',$idescr);
            $trfshp = $res['TRFSHP'];
            $istdpk = $res['ISTDPK'];

            $insert_values_trfip .= "('".$trfbch."','".$inumbr."','".$idescr."','".$trfshp."','".$istdpk."','0.00','".$trfshp."','".$str."'),";
            $insert_values_trf .= "('".$trfbch."','".$inumbr."','".$idescr."','".$trfshp."','".$istdpk."','0.00','".$trfshp."'),";
        }
    }
    
    if($insert_values_trf != ""){
        $sql_query = $insert_trf.mb_substr($insert_values_trf, 0, -1);
        $obj->mysql_exec_query($sql_query);
        $sql_queryIP = $insert_trfip.mb_substr($insert_values_trfip, 0, -1);
        $obj->mysql_exec_query($sql_queryIP);

        $date = date("Y-m-d H:i:sa");

        $sql_query2 = "INSERT INTO `".$ip."_batchtransfer_download_logs_tbl`(`trfbch`, `strcode`, `downloaded_date`) VALUES ('$trfbchx','$str','$date')";
        $obj->mysql_exec_query($sql_query2);
        $sql_query3 = "INSERT INTO `".$str."_batchtransfer_status_tbl`(`trfbch`) VALUES ('$trfbch')";
        $obj->mysql_exec_query($sql_query3);

    }else{
        echo '<div class="message">Transfer "'.$trfbchx.'" not found in the MMS.</div>';
    }

    $result = null;


    $qry2 = "call ".$mms_dbase.".spgettrf1(".$trfbchx.")";
    $result2 = odbc_exec($mmsconn,$qry2);

    $insert_upcIP = "INSERT INTO `".$ip."_iupc_tbl`(`iupc`, `inumbr`) VALUES ";
    $insert_values_upcIP = "";
    $insert_upc= "INSERT INTO `".$str."_iupc_tbl`(`iupc`, `inumbr`) VALUES ";
    $insert_values_upc = "";

    while($res2 = odbc_fetch_array($result2))
    {
        if(!empty($res2)){
            $iupc   = $res2['IUPC'];
            $icmpno = $res2['ICMPNO'];

            $insert_values_upcIP .= "('$iupc','$icmpno'),";
            $insert_values_upc .= "('$iupc','$icmpno'),";
            
        }        
    }

    if($insert_values_upc != ""){
        $sql_query3 = $insert_upcIP.mb_substr($insert_values_upcIP, 0, -1);
        $obj->mysql_exec_query($sql_query3);
        $sql_query4 = $insert_upc.mb_substr($insert_values_upc, 0, -1);
        $obj->mysql_exec_query($sql_query4);     
    }else{
        echo '<div class="message">UPC of Transfer "'.$trfbchx.'" not found in the MMS.</div>';
    }

    $result2 = null;
    
    $cntr += 1;

    echo '<script>parent.uptprogressbar('.$progressbar.');</script>';

    ob_flush(); 
    flush();
}
echo '<script>parent.document.getElementById("spantext").innerHTML = "Download finished, press F5 to refresh the page.";</script>';
echo '<script>parent.enableBTN();</script>';
