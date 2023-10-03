<?php

session_start();
date_default_timezone_set('Asia/Manila');
require('../Classes/DatabaseClass.php');
require('../fpdf184/fpdf.php');
require('../Classes/PDFClass.php');

if(isset($_POST['trf'])){
    $trfid = $_POST['trf'];
    $printer_ip = $_POST['prip'];
    $str = $_SESSION['strcode'];
    $ip = $_SERVER['REMOTE_ADDR'];


    $obj2 = new DatabaseClass();
    $transferbatch = $obj2->get_trfbch($ip,$str,$trfid);
    $pdf->trfnum = $transferbatch;


    /*
    =========================================
                START FOR CREATING PDF
    =========================================
    */
    $mysql_obj = new DatabaseClass(); //instanciate mysql class
    $mms1_obj = new MMSDatabaseClass(); //instanciate mms class

    $mysql_result = $mysql_obj->mysql_get_tranfiles($ip,$str,$transferbatch); // call function from DatabaseClass
    $trfs = array(); // set array for listing trfs row

    // Start looping
    for($i=0 ;$i < count($mysql_result);$i++)
    {
        $inumbr = $mysql_result[$i]['inumbr'];
        $idescr = $mysql_result[$i]['idescr'];
        $trfshp = $mysql_result[$i]['trfshp'];
        $istdpk = $mysql_result[$i]['istdpk'];
        $expqty = $mysql_result[$i]['expqty'];
        $rcvqty = $mysql_result[$i]['rcvqty'];
        $short  = number_format($expqty - $rcvqty,2);
            
    
        
        //to get primary iupc
        $res = $mysql_obj->getUPC($ip,$inumbr);
        $iupc   = $res['iupc'];
        //$iupc   = '1212312312313';
        
            
        
        $array = array(''.$inumbr.'',''.$iupc.'',''.$idescr.'',''.$trfshp.'',''.$istdpk.'',''.$expqty.'',''.$rcvqty.'',''.$short.'');
        
        $trfs[]=$array;
        $pdf->grand_shpqty += $trfshp;
        $pdf->grand_bum += $istdpk;
        $pdf->grand_expqty += $expqty;
        $pdf->grand_rcvqty += $rcvqty;
        $pdf->grand_shtqty += $short;
    }
    // End looping
    
    
    $header = array('SKU','UPC', 'Description', 'Ship Qty','BUM','Exp.Qty','Recv.Qty','Short Qty');
    $pdf->headers_array = $header;
    
    $pdf->SetFont('Arial','',10);
    //$pdf->AddPage();
    //$pdf->BasicTable($header,$trfs);
    $pdf->AddPage();
    $pdf->ImprovedTable($header,$trfs);
    //$pdf->AddPage();
    //$pdf->FancyTable($header,$data);
    //$pdf->Output();
    //fopen("../PDFReports/", "r");  
    //$filename="../PDFReports/trf".$trf."_ref".$refiD."_date".date('Ymd').".pdf";
    //$pdf->Open("../PDFReports/TRFPDF.pdf");
    //
    $fileName = 'TRF'.$transferbatch.'STR'.$str.'GEN'.date('Ymd_Hs');
    $extension = "pdf";
    $fileNameWithExtension = implode(".", [$fileName, $extension]);
    
    $filename = '../../PDF/'.$fileNameWithExtension;
    $pdf->Output('F',$filename);
    //fopen($filename,'wb');
    /*
    =========================================
             END FOR CREATING PDF
    =========================================
    */    

    /*
    =============================================
                START AUTO PRINT
    =============================================
    */
    
    
    try
    {
        //$file = fopen("trfprint60.pdf","r")
        $fp=pfsockopen($printer_ip, 9100);
        //fputs($fp, $print_output);

        fputs($fp, file_get_contents($filename));
        //fwrite($fp,"");
        fclose($fp);
    
        //echo 'Successfully Printed';
    }
    catch (Exception $e) 
    {
        echo json_encode(array('success' => 0,'trf'=>$trf));
        exit();
    }
        
    //Uploading to main table of store
    //$obj2->mysql_main_trfs($ip,$str,$transferbatch);

    if($obj2 == true){
        echo json_encode(array('success' => 1,'trf'=>$transferbatch));
    }else{
        echo json_encode(array('success' => 0,'trf'=>$transferbatch));
    }
    

}