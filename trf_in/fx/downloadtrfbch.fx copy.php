<?php

session_start();

require('../Classes/DatabaseClass.php');
require('../Classes/MMSDatabaseClass.php');
date_default_timezone_set('Asia/Manila');

$trfbch = $_REQUEST['q'];
$ip = $_SERVER['REMOTE_ADDR'];
$str = $_SESSION['strcode'];

// START :: CREATING TABLES
$obj = new DatabaseClass();
$obj->createTempTable($ip, $str);


$obj5 = new DatabaseClass();
$obj2 = new MMSDatabaseClass();

$obj3 = new MMSDatabaseClass();
$obj4 = new MMSDatabaseClass();

if($obj->checknoduplicate($ip,$trfbch,$str)){
    // IF NOT YET DOWNLOADED
    $tranferdetail = $obj2->mms_get_rows("spgettrf",$trfbch);
    if(empty($tranferdetail)){
        // IF NO RECORD FOUND
        echo json_encode(array('success' => 0, 'trfbch' => ''.$trfbch.''));
        exit();
    }


    for($i = 0; $i < count($tranferdetail); $i++){

        //print_r($tranferdetail);
            
        //START : DATA TO SAVE TO TRANFILES TABLE===============
        $tblcol = array("trfbch","inumbr","idescr","trfshp","istdpk","rcvqty","expqty","strcode");
        $table  = $ip."_batchtransfer_tbl";

        //=================================
        $trfbch = $tranferdetail[$i]['TRFBCH'];
        $inumbr = $tranferdetail[$i]['INUMBR'];
        $idescr = $tranferdetail[$i]['IDESCR'];
        $trfshp = $tranferdetail[$i]['TRFSHP'];
        $istdpk = $tranferdetail[$i]['ISTDPK'];

        $data = array("'".$trfbch."'","'".$inumbr."'","'".$idescr."'","'".$trfshp."'","'".$istdpk."'","0.00","'".$trfshp."'","'".$str."'");
        //END =============================================

        //SAVING UNIQUE SKU

        $rex =$obj4->mms_get_prim_upc($inumbr);
        $iupc   = $rex[0]['IUPC'];

    //START : DATA TO SAVE IN UPC=================================
        $tblcol2 = array("iupc","inumbr");
        $table2 = $ip."_iupc_tbl";
        $data2 = array("'".$iupc."'","'".$inumbr."'");

        $verif = $obj5->checkbarcode($ip,$inumbr,$iupc);

        //echo $verif;
    

        if($verif == 1){
            $obj->mysql_add_row($tblcol2,$data2,$table2);
            //echo "IUPC : ".$iupc." == INUMBR: ".$inumbr."<br>";
        }else{
            //echo "wtih duplicate<br>";
        }
        //END ===========================================================
        $obj->mysql_add_row($tblcol,$data,$table);
        
    }

    //SAVE DOWNLOADED TRF
    $tblcol3 = array("trfbch","strcode","downloaded_date");
    $table3 = $ip."_batchtransfer_download_logs_tbl";
    $date3 = date("Y-m-d H:i:s");
    $data3 = array("'".$trfbch."'","'".$str."'","'".$date3."'");
    $obj->mysql_add_row($tblcol3,$data3,$table3);
    //=============================
                        
    echo json_encode(array('success' => 1,'trfbch' => ''.$trfbch.'')); 
}else{
    echo json_encode(array('success' => 0,'trfbch' => ''.$trfbch.''));
}

