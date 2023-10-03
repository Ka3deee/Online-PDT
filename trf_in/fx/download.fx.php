<?php
session_start();
require('../fpdf184/fpdf.php');
require('../Classes/PDFClass.php');
require('../Classes/DatabaseClass.php');
date_default_timezone_set('Asia/Manila');


if(isset($_GET['trf'])){

    $str = $_SESSION['strcode'];
    $ip = $_SERVER['REMOTE_ADDR'];

    $path = '../../PDF/';


        $trfid = $_GET['trf'];


        $obj2 = new DatabaseClass();
        //$transferbatch = $obj2->get_trfbch_all($str,$trfid);
        $transferbatch = $trfid;
        $pdf->trfnum = $transferbatch;
    


        /*
        =========================================
                    START FOR CREATING PDF
        =========================================
        */
        $mysql_obj = new DatabaseClass(); //instanciate mysql class
        $mms1_obj = new MMSDatabaseClass(); //instanciate mms class
        
        $mysql_result = $mysql_obj->mysql_get_tranfiles_all($str,$transferbatch); // call function from DatabaseClass
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
            $res = $mms1_obj->mms_get_prim_upc($inumbr);
            $iupc   = $res[0]['IUPC'];
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
    
        
        $file = $path.$fileNameWithExtension;
        $pdf->Output('F',$file);
        //$pdf->Output();
            //fopen($filename,'wb');
            /*
            =========================================
                        END FOR CREATING PDF
            =========================================
            */

        
/*
        $fileName = 'TRF'.$trf.'REF'.$_SESSION['ref_id'].'GEN'.date('Ymd_Hs');
        $extension = "pdf";
        $fileNameWithExtension = implode(".", [$fileName, $extension]);

        $file = $path . $fileNameWithExtension;*/
        $content = file_get_contents($file);
        header('Content-Type: application/pdf');
        header('Content-disposition: attachment; filename="'. $fileNameWithExtension.'"');
        header('Content-Length: '. strlen($content));
        echo $content;


    // KILL THIS END


   // echo $_SESSION['ref_id'];

    //echo count($_GET['trfs']);
    //echo $_GET['trfs'][0];
    //$obj->downloadPDF($trfid);
}