<?php
session_start();
require('../fpdf184/fpdf.php');
require('../Classes/PDFClass.php');
require('../Classes/DatabaseClass.php');
date_default_timezone_set('Asia/Manila');



set_time_limit(0);


echo '<style>
.message {
    color:red;
    font-weight:bold;
    font-size:12px;
    font-family : sans-serif;
}

</style>';

echo '<script>parent.document.getElementById("spantext").innerHTML = "Making PDF...Started...";</script>';


$str = $_SESSION['strcode'];
$path = '../TransferIN_PDF/'.$str;
$trfbchs = $_REQUEST['q'];

$trfbcharray = explode(",",$trfbchs);

$cntr = 1;
$arrycnt = count($trfbcharray) - 1;

for($x=0;$x < $arrycnt; $x++){

    //TRANSFER NUMBER
    $trfid = $trfbcharray[$x];

    $obj2 = new DatabaseClass();
    $transferbatch = $trfid;
    $pdf->trfnum = $transferbatch;

    $progressbar = $cntr / $arrycnt * 100;
    $progressbar = number_format($progressbar,2);


    /*
    =========================================
                START FOR CREATING PDF
    =========================================
    */
    $mysql_obj = new DatabaseClass(); //instanciate mysql class
    $mms1_obj = new MMSDatabaseClass(); //instanciate mms class
    

    $tblnam = $str.'_received_batch_trf_tbl';
    $sql = "select 
    a.inumbr,
    a.idescr,
    a.trfshp as trfshp,
    a.istdpk as istdpk,
    a.rcvqty as rcvqty,
    a.expqty as expqty,
    a.trfbch
    from `".$tblnam."` as a
    where a.trfbch = '".$trfid."' group by a.inumbr, a.idescr, a.trfshp, a.istdpk, a.rcvqty,a.expqty, a.trfbch"; 
    
    $result = $mysql_obj->mysql_exec_query($sql);

    $trfs = array(); // set array for listing trfs row

    while($row = mysqli_fetch_assoc($result)){
        $inumbr = $row['inumbr'];
        $idescr = $row['idescr'];
        $trfshp = $row['trfshp'];
        $istdpk = $row['istdpk'];
        $expqty = $row['expqty'];
        $rcvqty = $row['rcvqty'];
        $short  = number_format($expqty - $rcvqty,2);

        //to get primary iupc
        $res = $mms1_obj->mms_get_prim_upc($inumbr);
        $iupc   = $res[0]['IUPC'];
        //$iupc   = '1212312312313';

        $array = array(''.$inumbr.'',''.$iupc.'',''.$idescr.'',''.$trfshp.'',''.$istdpk.'',''.$expqty.'',''.$rcvqty.'',''.$short.'');
    
        $trfs[]=$array;

    }

    $header = array('SKU','UPC', 'Description', 'Ship Qty','BUM','Exp.Qty','Recv.Qty','Short Qty');
    $pdf->headers_array = $header;

    $pdf->SetFont('Arial','',10);
    $pdf->AddPage();
    $pdf->ImprovedTable($header,$trfs);

    echo '<script>parent.document.getElementById("spantext").innerHTML = "Adding '.$trfid.' to PDF...";</script>';
    echo '<script>parent.uptprogressbar('.$progressbar.');</script>';

    $cntr += 1;

    ob_flush(); 
    flush();

    
}

$date = date_create();
$date = date_timestamp_get($date);
//$fileName = 'TRF'.$transferbatch.'STR'.$str.'GEN'.date('Ymd_Hs');
$fileName = 'TRANSFER_REPORT_FOR_STORE_'.$str.'_'.$date;
$extension = "pdf";
$fileNameWithExtension = implode(".", [$fileName, $extension]);

$file = $path.$fileNameWithExtension;
$pdf->Output('F',$file);

echo '<script>parent.document.getElementById("spantext").innerHTML = "Done making PDF file.";</script>';
echo '<script>parent.enableBTN();</script>';