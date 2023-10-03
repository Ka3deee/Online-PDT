<?php
// require('fpdf.php');
require('cellfit.php');
require('fpdf_merge.php');
require('concatenate-fake.php');
// require_once('autoprint.php');
date_default_timezone_set('Asia/Manila');

// REFERENCES:
// https://www.geeksforgeeks.org/how-to-generate-pdf-file-using-php/
// http://www.fpdf.org/en/script/script62.php
  
class PDF extends FPDF_CellFit {
  
    function Header() {
        // Add logo to page
        $this->Image('../images/lcc.png', 22, 5, 25);

        $this->Cell(85);
        $this->SetFont('Arial', 'B', 14);
        $this->Cell(0, 0, 'LCC');
        $this->Ln(4);
       
        $this->SetFont('Arial','', 10);
        $this->Cell(160);
        $this->Cell(0, 0, 'Page ' . $this->PageNo() . ' of {nb}');
        $this->Ln(3);
        
        $this->SetFont('Arial','B', 10);
        $this->Cell(65);
        $this->Cell(0, 0, 'Receiver Authorization by SKU');
        $this->Ln(6);
        
        $this->Cell(70);
        $this->Cell(0, 0, date("m/d/Y h:i:s A"));
        $this->Ln(7);
    }
  
    function forSignature($name) {
        $this->SetFont('Arial','B', 10);
        $this->SetX($this->lMargin);
        $this->Cell(5);
        $this->Cell(0, 0, 'Received By:');

        $this->SetX($this->lMargin);
        $this->Cell(53);
        $this->Cell(0, 0, 'Date Received:');

        $this->SetX($this->lMargin);
        $this->Cell(104);
        $this->Cell(0, 0, 'Confirmed By:');

        $this->SetX($this->lMargin);
        $this->Cell(156);
        $this->Cell(0, 0, 'Tallied By:');
        $this->Ln(10);

        $this->SetFont('Arial','U', 10);
        $this->Cell(0, 0, $name);
        $this->SetX($this->lMargin);
        $this->Cell(50);
        $this->Cell(0, 0, '________________');
        $this->SetX($this->lMargin);
        $this->Cell(100);
        $this->Cell(0, 0, '________________');
        $this->SetX($this->lMargin);
        $this->Cell(150);
        $this->Cell(0, 0, '________________');
    }

    function receiver($data,$name){
        $this->AliasNbPages();
        $this->AddPage();
        $this->SetFont('Arial','', 10);
        
        /*
        |--------------------------------------------------------------------------
        | CONSTRUCT DETAILS BEFORE TABLE
        |--------------------------------------------------------------------------
        */
        $this->Cell(0, 0, 'Location:');
        $this->SetX($this->lMargin);
        $this->Cell(15);
        $this->CellFitScale(85, 0, $data['postor'] .' - '. $data['strnam']);
        $this->SetX($this->lMargin);
        $this->Cell(100);
        $this->Cell(0, 0, 'Vendor:');
        $this->SetX($this->lMargin);
        $this->Cell(114);
        $this->CellFitScale(76, 0, $data['povnum'] .' - '. $data['asname']);
        $this->Ln(5);

        $this->Cell(0, 0, 'PO Number: ' . $data['Ponumb']);
        $this->SetX($this->lMargin);
        $this->Cell(100);
        $this->Cell(0, 0, 'Buyer:' );
        $this->SetX($this->lMargin);
        $this->Cell(112);
        $this->CellFitScale(77, 0, $data['pobuyr'] .' - '. $data['byrnam']);
        $this->Ln(5);
        
        $this->Cell(0, 0, 'RCR Number: ' . $data['pomrcv']);
        $this->SetX($this->lMargin);
        $this->Cell(100);
        $this->Cell(0, 0, 'Order Date: ' . date('m/d/Y', strtotime(implode('-', str_split($data['poedat'], 2)))));
        $this->Ln(5);
        
        /*
        |--------------------------------------------------------------------------
        | CONSTRUCT TABLE HEADER
        |--------------------------------------------------------------------------
        */
        $cell_height = 7;
        $this->SetFont('Arial','B', 9);
        $this->Cell(16, $cell_height, 'SKU', 1, 0, 'C');
        $this->Cell(26, $cell_height, 'UPC', 1, 0, 'C');
        $this->Cell(57, $cell_height, 'Description', 1, 0, 'C');
        $this->Cell(20, $cell_height, 'Po Qty', 1, 0, 'C');
        $this->Cell(11, $cell_height, 'BUM', 1, 0, 'C');
        $this->Cell(20, $cell_height, 'Exp qty', 1, 0, 'C');
        $this->Cell(20, $cell_height, 'Recv. Qty', 1, 0, 'C');
        $this->Cell(20, $cell_height, 'Short. Qty', 1, 0, 'C');
        $this->Ln($cell_height);
        
        $page_total  = array(
            'Expqty'    => 0,
            'rcvqty'    => 0,
            'shortqty'  => 0,
        );
        $grand_total  = array(
            'Expqty'    => 0,
            'rcvqty'    => 0,
            'shortqty'  => 0,
        );

        foreach ($data['details'] as $value) {
            $this->SetFont('Arial','', 9);
            $Expqty   = (double) $value['Pomqty'];
            $rcvqty   = (double) $value['rcvqty'] * (double) $value['istdpk'];
            $shortqty = (double) $Expqty - (double) $rcvqty ;

            if ($data['strhdo'] == 'W') { // strhdo == strtype
                $Expqty   = (double) $value['Pomqty'] / (double) $value['istdpk'];
                $rcvqty   = (double) $value['rcvqty'];
                $shortqty = (double) $Expqty - (double) $rcvqty;
            }
			
            /*
            |--------------------------------------------------------------------------
            | CALCULATE PAGE TOTAL
            |--------------------------------------------------------------------------
            */
            $page_total['Expqty']   += $Expqty;
            $page_total['rcvqty']   += $rcvqty;
            $page_total['shortqty'] += $shortqty;
        
            /*
            |--------------------------------------------------------------------------
            | CALCULATE GRAND TOTAL
            |--------------------------------------------------------------------------
            */
            $grand_total['Expqty']   += $Expqty;
            $grand_total['rcvqty']   += $rcvqty;
            $grand_total['shortqty'] += $shortqty;
        
            /*
            |--------------------------------------------------------------------------
            | CONSTRUCT TABLE BODY
            |--------------------------------------------------------------------------
            */
            $border = $value['Expday'] == '0' ? 1 : 'R,L,T';
            $this->CellFitScale(16, $cell_height, $value['Inumber'], $border);
            $this->CellFitScale(26, $cell_height, $value['iupc'], $border);
            $this->CellFitScale(57, $cell_height, $value['idescr'], $border);
            $this->CellFitScale(20, $cell_height, number_format($value['Pomqty'], 2), $border, 0, 'R');
            $this->CellFitScale(11, $cell_height, $value['istdpk'], $border, 0, 'C');
            $this->CellFitScale(20, $cell_height, number_format($Expqty, 2), $border, 0, 'R');
            $this->CellFitScale(20, $cell_height, number_format($rcvqty, 2), $border, 0, 'R');
            $this->CellFitScale(20, $cell_height, number_format($shortqty, 2), $border, 0, 'R');
            
            /*************** IF HAS EXPIRY ***************/
            if ($value['Expday'] != '0') {
                $this->SetFont('Arial','', 8);
                if ($value['expiredate'] == 0) {
                    $expiry_date = 'Invalid Expiry Date';
                    $tol_expiry_date = 'Invalid Expiry Date';
                    $earlier = false;
                }
                else {
                    $expiry_date     = date_format(date_create($value['expiredate']), 'm/d/Y');
                    $tol_expiry_date = date('m/d/Y', strtotime(date('Y-m-d') . ' + '.$value['Expday'].' days'));
                    $earlier         = $expiry_date < $tol_expiry_date;
                }

                $expiry_date_str     = '      Expiry Date      : ' . $expiry_date;
                $expiry_date_str    .= $earlier ? ' * Expiry Date Earlier Than Tolerable' : '';
                $tol_expiry_date_str = '      Tol. Exp. Date  : ' . $tol_expiry_date;

                $this->Ln($cell_height);
                $this->CellFitScale(16, 3.5, '', 'R,L');
                $this->CellFitScale(26, 3.5, '', 'R,L');
                $this->CellFitScale($earlier ? 88 : 57, 3.5, $expiry_date_str, 'R, L');
                if (!$earlier) {
                    $this->CellFitScale(20, 3.5, '', 'R,L');
                    $this->CellFitScale(11, 3.5, '', 'R,L');
                }
                $this->CellFitScale(20, 3.5, '', 'R,L');
                $this->CellFitScale(20, 3.5, '', 'R,L');
                $this->CellFitScale(20, 3.5, '', 'R,L');
                
                $this->Ln(3.5);
                $this->CellFitScale(16, 3.5, '', 'R,L');
                $this->CellFitScale(26, 3.5, '', 'R,L');
                $this->CellFitScale($earlier ? 88 : 57, 3.5, $tol_expiry_date_str, 'R');
                if (!$earlier) {
                    $this->CellFitScale(20, 3.5, '', 'R,L');
                    $this->CellFitScale(11, 3.5, '', 'R,L');
                }
                $this->CellFitScale(20, 3.5, '', 'R,L');
                $this->CellFitScale(20, 3.5, '', 'R,L');
                $this->CellFitScale(20, 3.5, '', 'R,L');
            }

            $this->Ln($value['Expday'] == '0' ? $cell_height : 3.5);
            
            /*
            |--------------------------------------------------------------------------
            | DISPLAY PAGE TOTAL
            |--------------------------------------------------------------------------
            */
            /*************** CALCULATE DISTANCE BETWEEN LAST CELL AND BOTTOM MARGIN ***************/
            $distance =( $this->GetPageHeight() - $this->GetY()) - $this->bMargin; // REFERENCE: https://stackoverflow.com/a/2694671/18159572
    
            // if ($distance < ($this->PageNo() == 1 ? 8 : 9)) {
            if ($distance < 15) {
                $this->SetFont('Arial','B', 10);
                $this->Cell(130, $cell_height, 'Page Total', 1, 0, 'R');
                $this->Cell(20, $cell_height, number_format($page_total['Expqty'], 2), 1, 0, 'R');
                $this->Cell(20, $cell_height, number_format($page_total['rcvqty'], 2), 1, 0, 'R');
                $this->Cell(20, $cell_height, number_format($page_total['shortqty'], 2), 1, 0, 'R');
                $this->Ln($cell_height);
    
                // RESET TOTAL PER PAGE
                /*************** RESET TOTAL PER PAGE ***************/
                $page_total  = array(
                    'Expqty'   => 0,
                    'rcvqty'   => 0,
                    'shortqty' => 0,
                );
                $this->AddPage();
            }
        }
        
        /*
        |--------------------------------------------------------------------------
        | DISPLAY PAGE TOTAL
        |--------------------------------------------------------------------------
        */
        $this->SetFont('Arial','B', 10);
        $this->Cell(130, $cell_height, 'Page Total', 1, 0, 'R');
        $this->Cell(20, $cell_height, number_format($page_total['Expqty'], 2), 1, 0, 'R');
        $this->Cell(20, $cell_height, number_format($page_total['rcvqty'], 2), 1, 0, 'R');
        $this->Cell(20, $cell_height, number_format($page_total['shortqty'], 2), 1, 0, 'R');
        $this->Ln($cell_height);
        
        /*
        |--------------------------------------------------------------------------
        | DISPLAY GRAND TOTAL
        |--------------------------------------------------------------------------
        */
        $this->SetFont('Arial','B', 10);
        $this->Cell(130, $cell_height, 'Grand Total', 1, 0, 'R');
        $this->CellFitScale(20, $cell_height, number_format($grand_total['Expqty'], 2), 1, 0, 'R');
        $this->CellFitScale(20, $cell_height, number_format($grand_total['rcvqty'], 2), 1, 0, 'R');
        $this->CellFitScale(20, $cell_height, number_format($grand_total['shortqty'], 2), 1, 0, 'R');
        $this->Ln(20);
        
        /*
        |--------------------------------------------------------------------------
        | DISPLAY FOR SIGNATURE
        |--------------------------------------------------------------------------
        */
        $this->forSignature($name);
    }
}

function generate_receiver($data, $file,$name){
    $pdf = new PDF();
    $pdf->receiver($data,$name);
    $result = $pdf->Output('F', $file);
    return $result;
}

function printPDF($file, $printer_server) {
    try {
        //$file = fopen($file,'r')
        $fp = pfsockopen($printer_server, 9100);
        //fputs($fp, $print_output);
        fputs($fp, file_get_contents($file));
        //fwrite($fp,'');
        fclose($fp);
        echo 'Successfully Printed';
        return true;
    }
    catch (Exception $e) {
        echo 'Caught exception: ',  $e->getMessage(), '\n';
        return false;
    }
}

function mergePDF($file_names = array(), $user_id, $store_code) {
    ob_end_clean();
    $path = '../PDF/';

    if (sizeof($file_names) == 0) return;
    else if (sizeof($file_names) == 1) return $file_names[0]; // RETURN FILE NAME FOR DOWNLOAD


    /*************************************** IF HAS MULTIPLE PDFs ***************************************/
    
    $dl_filename = $user_id .'_'. $store_code .'_'. str_replace('.pdf', '', explode('_', $file_names[0])[2]);

    /*
    |--------------------------------------------------------------------------
    | OPTION 1: ARCHIVE FIRST THEN DOWNLOAD
    | REFERENCE: https://stackoverflow.com/a/64479856/18159572
    |--------------------------------------------------------------------------
    */

    // $zip = new ZipArchive();
    // $dl_filename .= '.zip';
    // $zip_file = $path . $dl_filename;

    // // OPEN ZIP
    // if (file_exists($zip_file)) $zip->open($zip_file, ZipArchive::OVERWRITE);
    // else $zip->open($zip_file, ZipArchive::CREATE);

    // // ADD FILES TO ZIP
    // foreach ($file_names as $file_name) {
    //     $zip->addFile($path . $file_name, $file_name);
    // }

    // // CLOSE ZIP
    // $zip->close();

    /*
    |--------------------------------------------------------------------------
    | OPTION 2.1: MERGE ALL PDFs INTO ONE USING FPDF_MERGE
    | REFERENCE: http://www.fpdf.org/en/script/script94.php
    |--------------------------------------------------------------------------
    */

    $dl_filename .= '.pdf';
    $dl_file = $path . $dl_filename;

    // $merge = new FPDF_Merge();
    // foreach ($file_names as $file_name) {
    //     if (file_exists($temp_file)) $merge->add($path . $file_name);
    // }
    
    // $merge->output($dl_file);

    /*
    |--------------------------------------------------------------------------
    | OPTION 2.2: MERGE ALL PDFs INTO ONE USING FPDI
    | REFERENCE: https://www.setasign.com/products/fpdi/demos/concatenate-fake/
    |--------------------------------------------------------------------------
    */

    $merge = new ConcatPdf();
    $merge->setFiles($file_names);
    $merge->concat();
    
    $merge->Output('F', $dl_file);
    

    /***************************** RETURN THE NAME OF THE FILE TO BE DOWNLOADED *****************************/
    return $dl_filename;
}


/*************************************** SAMPLE DATA TO CREATE PDF ***************************************/
// $data = array(
//     'postor'    => 100,
//     'strnam'    => 'Tabaco Department Store',
//     'Ponumb'    => 500004843,
//     'pomrcv'    => 500001132,
//     'povnum'    => 20178,
//     'asname'    => 'Asahi Electrical Mfg. Corp.',
//     'pobuyr'    => 'Ma. Aurora B. Bongga',
//     'byrnam'    => 'Ma. Aurora B. Bongga',
//     'poedat'    => '11/24/21',
//     'strhdo'    => 'W',
//     'details'   => array(
//         [
//             'Inumber'       => 4253718,
//             'iupc'          => 2800042537186,
//             'idescr'        => 'Asahi Stand Fan BB 6026, Asahi Stand Fan BB 6026',
//             'Pomqty'        => 100.00,
//             'istdpk'        => 1,
//             'Expqty'        => 200.00,
//             'rcvqty'        => 100,
//             'Expday'        => 30,
//             'expiredate'    => '2023-08-05',
//         ],
//         [
//             'Inumber'       => 4253718,
//             'iupc'          => 2800042537186,
//             'idescr'        => 'Asahi Stand Fan BB 6026',
//             'Pomqty'        => 100.00,
//             'istdpk'        => 1,
//             'Expqty'        => 100.00,
//             'rcvqty'        => 100,
//             'Expday'        => 50,
//             'expiredate'    => '2023-08-27',
//         ],
//         [
//             'Inumber'       => 4253718,
//             'iupc'          => 2800042537186,
//             'idescr'        => 'Asahi Stand Fan BB 6026',
//             'Pomqty'        => 100.00,
//             'istdpk'        => 1,
//             'Expqty'        => 100.00,
//             'rcvqty'        => 100,
//             'Expday'        => 0,
//             'expiredate'    => '',
//         ],
//         [
//             'Inumber'       => 4253718,
//             'iupc'          => 2800042537186,
//             'idescr'        => 'Asahi Stand Fan BB 6026',
//             'Pomqty'        => 100.00,
//             'istdpk'        => 1,
//             'Expqty'        => 100.00,
//             'rcvqty'        => 100,
//             'Expday'        => 0,
//             'expiredate'    => '',
//         ],
//         [
//             'Inumber'       => 4253718,
//             'iupc'          => 2800042537186,
//             'idescr'        => 'Asahi Stand Fan BB 6026',
//             'Pomqty'        => 100.00,
//             'istdpk'        => 1,
//             'Expqty'        => 100.00,
//             'rcvqty'        => 100,
//             'Expday'        => 0,
//             'expiredate'    => '',
//         ],
//         [
//             'Inumber'       => 4253718,
//             'iupc'          => 2800042537186,
//             'idescr'        => 'Asahi Stand Fan BB 6026',
//             'Pomqty'        => 100.00,
//             'istdpk'        => 1,
//             'Expqty'        => 100.00,
//             'rcvqty'        => 100,
//             'Expday'        => 0,
//             'expiredate'    => '',
//         ],
//         [
//             'Inumber'       => 4253718,
//             'iupc'          => 2800042537186,
//             'idescr'        => 'Asahi Stand Fan BB 6026',
//             'Pomqty'        => 100.00,
//             'istdpk'        => 1,
//             'Expqty'        => 100.00,
//             'rcvqty'        => 100,
//             'Expday'        => 0,
//             'expiredate'    => '',
//         ],
//         [
//             'Inumber'       => 4253718,
//             'iupc'          => 2800042537186,
//             'idescr'        => 'Asahi Stand Fan BB 6026',
//             'Pomqty'        => 100.00,
//             'istdpk'        => 1,
//             'Expqty'        => 100.00,
//             'rcvqty'        => 100,
//             'Expday'        => 0,
//             'expiredate'    => '',
//         ],
//         [
//             'Inumber'       => 4253718,
//             'iupc'          => 2800042537186,
//             'idescr'        => 'Asahi Stand Fan BB 6026',
//             'Pomqty'        => 100.00,
//             'istdpk'        => 1,
//             'Expqty'        => 100.00,
//             'rcvqty'        => 100,
//             'Expday'        => 0,
//             'expiredate'    => '',
//         ],
//         [
//             'Inumber'       => 4253718,
//             'iupc'          => 2800042537186,
//             'idescr'        => 'Asahi Stand Fan BB 6026',
//             'Pomqty'        => 100.00,
//             'istdpk'        => 1,
//             'Expqty'        => 100.00,
//             'rcvqty'        => 100,
//             'Expday'        => 0,
//             'expiredate'    => '',
//         ],
//         [
//             'Inumber'       => 4253718,
//             'iupc'          => 2800042537186,
//             'idescr'        => 'Asahi Stand Fan BB 6026',
//             'Pomqty'        => 100.00,
//             'istdpk'        => 1,
//             'Expqty'        => 100.00,
//             'rcvqty'        => 100,
//             'Expday'        => 0,
//             'expiredate'    => '',
//         ],
//         [
//             'Inumber'       => 4253718,
//             'iupc'          => 2800042537186,
//             'idescr'        => 'Asahi Stand Fan BB 6026',
//             'Pomqty'        => 100.00,
//             'istdpk'        => 1,
//             'Expqty'        => 100.00,
//             'rcvqty'        => 100,
//             'Expday'        => 0,
//             'expiredate'    => '',
//         ],
//         [
//             'Inumber'       => 4253718,
//             'iupc'          => 2800042537186,
//             'idescr'        => 'Asahi Stand Fan BB 6026',
//             'Pomqty'        => 100.00,
//             'istdpk'        => 1,
//             'Expqty'        => 100.00,
//             'rcvqty'        => 100,
//             'Expday'        => 0,
//             'expiredate'    => '',
//         ],
//         [
//             'Inumber'       => 4253718,
//             'iupc'          => 2800042537186,
//             'idescr'        => 'Asahi Stand Fan BB 6026',
//             'Pomqty'        => 100.00,
//             'istdpk'        => 1,
//             'Expqty'        => 100.00,
//             'rcvqty'        => 100,
//             'Expday'        => 0,
//             'expiredate'    => '',
//         ],
//         [
//             'Inumber'       => 4253718,
//             'iupc'          => 2800042537186,
//             'idescr'        => 'Asahi Stand Fan BB 6026',
//             'Pomqty'        => 100.00,
//             'istdpk'        => 1,
//             'Expqty'        => 100.00,
//             'rcvqty'        => 100,
//             'Expday'        => 0,
//             'expiredate'    => '',
//         ],
//         [
//             'Inumber'       => 4253718,
//             'iupc'          => 2800042537186,
//             'idescr'        => 'Asahi Stand Fan BB 6026',
//             'Pomqty'        => 100.00,
//             'istdpk'        => 1,
//             'Expqty'        => 100.00,
//             'rcvqty'        => 100,
//             'Expday'        => 0,
//             'expiredate'    => '',
//         ],
//         [
//             'Inumber'       => 4253718,
//             'iupc'          => 2800042537186,
//             'idescr'        => 'Asahi Stand Fan BB 6026',
//             'Pomqty'        => 100.00,
//             'istdpk'        => 1,
//             'Expqty'        => 100.00,
//             'rcvqty'        => 100,
//             'Expday'        => 0,
//             'expiredate'    => '',
//         ],
//         [
//             'Inumber'       => 4253718,
//             'iupc'          => 2800042537186,
//             'idescr'        => 'Asahi Stand Fan BB 6026',
//             'Pomqty'        => 100.00,
//             'istdpk'        => 1,
//             'Expqty'        => 100.00,
//             'rcvqty'        => 100,
//             'Expday'        => 0,
//             'expiredate'    => '',
//         ],
//         [
//             'Inumber'       => 4253718,
//             'iupc'          => 2800042537186,
//             'idescr'        => 'Asahi Stand Fan BB 6026',
//             'Pomqty'        => 100.00,
//             'istdpk'        => 1,
//             'Expqty'        => 100.00,
//             'rcvqty'        => 100,
//             'Expday'        => 0,
//             'expiredate'    => '',
//         ],
//         [
//             'Inumber'       => 4253718,
//             'iupc'          => 2800042537186,
//             'idescr'        => 'Asahi Stand Fan BB 6026',
//             'Pomqty'        => 100.00,
//             'istdpk'        => 1,
//             'Expqty'        => 100.00,
//             'rcvqty'        => 100,
//             'Expday'        => 0,
//             'expiredate'    => '',
//         ],
//         [
//             'Inumber'       => 4253718,
//             'iupc'          => 2800042537186,
//             'idescr'        => 'Asahi Stand Fan BB 6026',
//             'Pomqty'        => 100.00,
//             'istdpk'        => 1,
//             'Expqty'        => 100.00,
//             'rcvqty'        => 100,
//             'Expday'        => 0,
//             'expiredate'    => '',
//         ],
//         [
//             'Inumber'       => 4253718,
//             'iupc'          => 2800042537186,
//             'idescr'        => 'Asahi Stand Fan BB 6026',
//             'Pomqty'        => 100.00,
//             'istdpk'        => 1,
//             'Expqty'        => 100.00,
//             'rcvqty'        => 100,
//             'Expday'        => 0,
//             'expiredate'    => '',
//         ],
//         [
//             'Inumber'       => 4253718,
//             'iupc'          => 2800042537186,
//             'idescr'        => 'Asahi Stand Fan BB 6026',
//             'Pomqty'        => 100.00,
//             'istdpk'        => 1,
//             'Expqty'        => 100.00,
//             'rcvqty'        => 100,
//             'Expday'        => 0,
//             'expiredate'    => '',
//         ],
//         [
//             'Inumber'       => 4253718,
//             'iupc'          => 2800042537186,
//             'idescr'        => 'Asahi Stand Fan BB 6026',
//             'Pomqty'        => 100.00,
//             'istdpk'        => 1,
//             'Expqty'        => 100.00,
//             'rcvqty'        => 100,
//             'Expday'        => 0,
//             'expiredate'    => '',
//         ],
//         [
//             'Inumber'       => 4253718,
//             'iupc'          => 2800042537186,
//             'idescr'        => 'Asahi Stand Fan BB 6026',
//             'Pomqty'        => 100.00,
//             'istdpk'        => 1,
//             'Expqty'        => 100.00,
//             'rcvqty'        => 100,
//             'Expday'        => 0,
//             'expiredate'    => '',
//         ],
//         [
//             'Inumber'       => 4253718,
//             'iupc'          => 2800042537186,
//             'idescr'        => 'Asahi Stand Fan BB 6026',
//             'Pomqty'        => 100.00,
//             'istdpk'        => 1,
//             'Expqty'        => 100.00,
//             'rcvqty'        => 100,
//             'Expday'        => 0,
//             'expiredate'    => '',
//         ],
//         [
//             'Inumber'       => 4253718,
//             'iupc'          => 2800042537186,
//             'idescr'        => 'Asahi Stand Fan BB 6026',
//             'Pomqty'        => 100.00,
//             'istdpk'        => 1,
//             'Expqty'        => 100.00,
//             'rcvqty'        => 100,
//             'Expday'        => 0,
//             'expiredate'    => '',
//         ],
//         [
//             'Inumber'       => 4253718,
//             'iupc'          => 2800042537186,
//             'idescr'        => 'Asahi Stand Fan BB 6026',
//             'Pomqty'        => 100.00,
//             'istdpk'        => 1,
//             'Expqty'        => 100.00,
//             'rcvqty'        => 100,
//             'Expday'        => 0,
//             'expiredate'    => '',
//         ],
//         [
//             'Inumber'       => 4253718,
//             'iupc'          => 2800042537186,
//             'idescr'        => 'Asahi Stand Fan BB 6026',
//             'Pomqty'        => 100.00,
//             'istdpk'        => 1,
//             'Expqty'        => 100.00,
//             'rcvqty'        => 100,
//             'Expday'        => 0,
//             'expiredate'    => '',
//         ],
//         [
//             'Inumber'       => 4253718,
//             'iupc'          => 2800042537186,
//             'idescr'        => 'Asahi Stand Fan BB 6026',
//             'Pomqty'        => 100.00,
//             'istdpk'        => 1,
//             'Expqty'        => 100.00,
//             'rcvqty'        => 100,
//             'Expday'        => 0,
//             'expiredate'    => '',
//         ],
//         [
//             'Inumber'       => 4253718,
//             'iupc'          => 2800042537186,
//             'idescr'        => 'Asahi Stand Fan BB 6026',
//             'Pomqty'        => 100.00,
//             'istdpk'        => 1,
//             'Expqty'        => 100.00,
//             'rcvqty'        => 100,
//             'Expday'        => 0,
//             'expiredate'    => '',
//         ],
//         [
//             'Inumber'       => 4253718,
//             'iupc'          => 2800042537186,
//             'idescr'        => 'Asahi Stand Fan BB 6026',
//             'Pomqty'        => 100.00,
//             'istdpk'        => 1,
//             'Expqty'        => 100.00,
//             'rcvqty'        => 100,
//             'Expday'        => 0,
//             'expiredate'    => '',
//         ],
//         [
//             'Inumber'       => 4253718,
//             'iupc'          => 2800042537186,
//             'idescr'        => 'Asahi Stand Fan BB 6026',
//             'Pomqty'        => 100.00,
//             'istdpk'        => 1,
//             'Expqty'        => 100.00,
//             'rcvqty'        => 100,
//             'Expday'        => 0,
//             'expiredate'    => '',
//         ],
//         [
//             'Inumber'       => 4253718,
//             'iupc'          => 2800042537186,
//             'idescr'        => 'Asahi Stand Fan BB 6026',
//             'Pomqty'        => 100.00,
//             'istdpk'        => 1,
//             'Expqty'        => 100.00,
//             'rcvqty'        => 100,
//             'Expday'        => 0,
//             'expiredate'    => '',
//         ],
//         [
//             'Inumber'       => 4253718,
//             'iupc'          => 2800042537186,
//             'idescr'        => 'Asahi Stand Fan BB 6026',
//             'Pomqty'        => 100.00,
//             'istdpk'        => 1,
//             'Expqty'        => 100.00,
//             'rcvqty'        => 100,
//             'Expday'        => 0,
//             'expiredate'    => '',
//         ],
//         [
//             'Inumber'       => 4253718,
//             'iupc'          => 2800042537186,
//             'idescr'        => 'Asahi Stand Fan BB 6026',
//             'Pomqty'        => 100.00,
//             'istdpk'        => 1,
//             'Expqty'        => 100.00,
//             'rcvqty'        => 100,
//             'Expday'        => 0,
//             'expiredate'    => '',
//         ],
//         [
//             'Inumber'       => 4253718,
//             'iupc'          => 2800042537186,
//             'idescr'        => 'Asahi Stand Fan BB 6026',
//             'Pomqty'        => 100.00,
//             'istdpk'        => 1,
//             'Expqty'        => 100.00,
//             'rcvqty'        => 100,
//             'Expday'        => 0,
//             'expiredate'    => '',
//         ],
//         [
//             'Inumber'       => 4253718,
//             'iupc'          => 2800042537186,
//             'idescr'        => 'Asahi Stand Fan BB 6026',
//             'Pomqty'        => 100.00,
//             'istdpk'        => 1,
//             'Expqty'        => 100.00,
//             'rcvqty'        => 100,
//             'Expday'        => 0,
//             'expiredate'    => '',
//         ],
//         [
//             'Inumber'       => 4253718,
//             'iupc'          => 2800042537186,
//             'idescr'        => 'Asahi Stand Fan BB 6026',
//             'Pomqty'        => 100.00,
//             'istdpk'        => 1,
//             'Expqty'        => 100.00,
//             'rcvqty'        => 100,
//             'Expday'        => 0,
//             'expiredate'    => '',
//         ],
//         [
//             'Inumber'       => 4253718,
//             'iupc'          => 2800042537186,
//             'idescr'        => 'Asahi Stand Fan BB 6026',
//             'Pomqty'        => 100.00,
//             'istdpk'        => 1,
//             'Expqty'        => 100.00,
//             'rcvqty'        => 100,
//             'Expday'        => 0,
//             'expiredate'    => '',
//         ],
//         [
//             'Inumber'       => 4253718,
//             'iupc'          => 2800042537186,
//             'idescr'        => 'Asahi Stand Fan BB 6026',
//             'Pomqty'        => 100.00,
//             'istdpk'        => 1,
//             'Expqty'        => 100.00,
//             'rcvqty'        => 100,
//             'Expday'        => 0,
//             'expiredate'    => '',
//         ],
//         [
//             'Inumber'       => 4253718,
//             'iupc'          => 2800042537186,
//             'idescr'        => 'Asahi Stand Fan BB 6026',
//             'Pomqty'        => 100.00,
//             'istdpk'        => 1,
//             'Expqty'        => 100.00,
//             'rcvqty'        => 100,
//             'Expday'        => 0,
//             'expiredate'    => '',
//         ],
//         [
//             'Inumber'       => 4253718,
//             'iupc'          => 2800042537186,
//             'idescr'        => 'Asahi Stand Fan BB 6026',
//             'Pomqty'        => 100.00,
//             'istdpk'        => 1,
//             'Expqty'        => 100.00,
//             'rcvqty'        => 100,
//             'Expday'        => 0,
//             'expiredate'    => '',
//         ],
//         [
//             'Inumber'       => 4253718,
//             'iupc'          => 2800042537186,
//             'idescr'        => 'Asahi Stand Fan BB 6026',
//             'Pomqty'        => 100.00,
//             'istdpk'        => 1,
//             'Expqty'        => 100.00,
//             'rcvqty'        => 100,
//             'Expday'        => 0,
//             'expiredate'    => '',
//         ],
//         [
//             'Inumber'       => 4253718,
//             'iupc'          => 2800042537186,
//             'idescr'        => 'Asahi Stand Fan BB 6026',
//             'Pomqty'        => 100.00,
//             'istdpk'        => 1,
//             'Expqty'        => 100.00,
//             'rcvqty'        => 100,
//             'Expday'        => 0,
//             'expiredate'    => '',
//         ],
//         [
//             'Inumber'       => 4253718,
//             'iupc'          => 2800042537186,
//             'idescr'        => 'Asahi Stand Fan BB 6026',
//             'Pomqty'        => 100.00,
//             'istdpk'        => 1,
//             'Expqty'        => 100.00,
//             'rcvqty'        => 100,
//             'Expday'        => 0,
//             'expiredate'    => '',
//         ],
//         [
//             'Inumber'       => 4253718,
//             'iupc'          => 2800042537186,
//             'idescr'        => 'Asahi Stand Fan BB 6026',
//             'Pomqty'        => 100.00,
//             'istdpk'        => 1,
//             'Expqty'        => 100.00,
//             'rcvqty'        => 100,
//             'Expday'        => 0,
//             'expiredate'    => '',
//         ],
//         [
//             'Inumber'       => 4253718,
//             'iupc'          => 2800042537186,
//             'idescr'        => 'Asahi Stand Fan BB 6026',
//             'Pomqty'        => 100.00,
//             'istdpk'        => 1,
//             'Expqty'        => 100.00,
//             'rcvqty'        => 100,
//             'Expday'        => 0,
//             'expiredate'    => '',
//         ],
//         [
//             'Inumber'       => 4253718,
//             'iupc'          => 2800042537186,
//             'idescr'        => 'Asahi Stand Fan BB 6026',
//             'Pomqty'        => 100.00,
//             'istdpk'        => 1,
//             'Expqty'        => 100.00,
//             'rcvqty'        => 100,
//             'Expday'        => 0,
//             'expiredate'    => '',
//         ],
//         [
//             'Inumber'       => 4253718,
//             'iupc'          => 2800042537186,
//             'idescr'        => 'Asahi Stand Fan BB 6026',
//             'Pomqty'        => 100.00,
//             'istdpk'        => 1,
//             'Expqty'        => 100.00,
//             'rcvqty'        => 100,
//             'Expday'        => 0,
//             'expiredate'    => '',
//         ],
//         [
//             'Inumber'       => 4253718,
//             'iupc'          => 2800042537186,
//             'idescr'        => 'Asahi Stand Fan BB 6026',
//             'Pomqty'        => 100.00,
//             'istdpk'        => 1,
//             'Expqty'        => 100.00,
//             'rcvqty'        => 100,
//             'Expday'        => 0,
//             'expiredate'    => '',
//         ],
//         [
//             'Inumber'       => 4253718,
//             'iupc'          => 2800042537186,
//             'idescr'        => 'Asahi Stand Fan BB 6026',
//             'Pomqty'        => 100.00,
//             'istdpk'        => 1,
//             'Expqty'        => 100.00,
//             'rcvqty'        => 100,
//             'Expday'        => 0,
//             'expiredate'    => '',
//         ],
//         [
//             'Inumber'       => 4253718,
//             'iupc'          => 2800042537186,
//             'idescr'        => 'Asahi Stand Fan BB 6026',
//             'Pomqty'        => 100.00,
//             'istdpk'        => 1,
//             'Expqty'        => 100.00,
//             'rcvqty'        => 100,
//             'Expday'        => 0,
//             'expiredate'    => '',
//         ],
//         [
//             'Inumber'       => 4253718,
//             'iupc'          => 2800042537186,
//             'idescr'        => 'Asahi Stand Fan BB 6026',
//             'Pomqty'        => 100.00,
//             'istdpk'        => 1,
//             'Expqty'        => 100.00,
//             'rcvqty'        => 100,
//             'Expday'        => 0,
//             'expiredate'    => '',
//         ],
//         [
//             'Inumber'       => 4253718,
//             'iupc'          => 2800042537186,
//             'idescr'        => 'Asahi Stand Fan BB 6026',
//             'Pomqty'        => 100.00,
//             'istdpk'        => 1,
//             'Expqty'        => 100.00,
//             'rcvqty'        => 100,
//             'Expday'        => 0,
//             'expiredate'    => '',
//         ],
//         [
//             'Inumber'       => 4253718,
//             'iupc'          => 2800042537186,
//             'idescr'        => 'Asahi Stand Fan BB 6026',
//             'Pomqty'        => 100.00,
//             'istdpk'        => 1,
//             'Expqty'        => 100.00,
//             'rcvqty'        => 100,
//             'Expday'        => 0,
//             'expiredate'    => '',
//         ],
//         [
//             'Inumber'       => 4253718,
//             'iupc'          => 2800042537186,
//             'idescr'        => 'Asahi Stand Fan BB 6026',
//             'Pomqty'        => 100.00,
//             'istdpk'        => 1,
//             'Expqty'        => 100.00,
//             'rcvqty'        => 100,
//             'Expday'        => 0,
//             'expiredate'    => '',
//         ],
//         [
//             'Inumber'       => 4253718,
//             'iupc'          => 2800042537186,
//             'idescr'        => 'Asahi Stand Fan BB 6026',
//             'Pomqty'        => 100.00,
//             'istdpk'        => 1,
//             'Expqty'        => 100.00,
//             'rcvqty'        => 100,
//             'Expday'        => 0,
//             'expiredate'    => '',
//         ],
//         [
//             'Inumber'       => 4253718,
//             'iupc'          => 2800042537186,
//             'idescr'        => 'Asahi Stand Fan BB 6026',
//             'Pomqty'        => 100.00,
//             'istdpk'        => 1,
//             'Expqty'        => 100.00,
//             'rcvqty'        => 100,
//             'Expday'        => 0,
//             'expiredate'    => '',
//         ],
//         [
//             'Inumber'       => 4253718,
//             'iupc'          => 2800042537186,
//             'idescr'        => 'Asahi Stand Fan BB 6026',
//             'Pomqty'        => 100.00,
//             'istdpk'        => 1,
//             'Expqty'        => 100.00,
//             'rcvqty'        => 100,
//             'Expday'        => 0,
//             'expiredate'    => '',
//         ],
//         [
//             'Inumber'       => 4253718,
//             'iupc'          => 2800042537186,
//             'idescr'        => 'Asahi Stand Fan BB 6026',
//             'Pomqty'        => 100.00,
//             'istdpk'        => 1,
//             'Expqty'        => 100.00,
//             'rcvqty'        => 100,
//             'Expday'        => 0,
//             'expiredate'    => '',
//         ],
//         [
//             'Inumber'       => 4253718,
//             'iupc'          => 2800042537186,
//             'idescr'        => 'Asahi Stand Fan BB 6026',
//             'Pomqty'        => 100.00,
//             'istdpk'        => 1,
//             'Expqty'        => 100.00,
//             'rcvqty'        => 100,
//             'Expday'        => 0,
//             'expiredate'    => '',
//         ],
//         [
//             'Inumber'       => 4253718,
//             'iupc'          => 2800042537186,
//             'idescr'        => 'Asahi Stand Fan BB 6026',
//             'Pomqty'        => 100.00,
//             'istdpk'        => 1,
//             'Expqty'        => 100.00,
//             'rcvqty'        => 100,
//             'Expday'        => 0,
//             'expiredate'    => '',
//         ],
//         [
//             'Inumber'       => 4253718,
//             'iupc'          => 2800042537186,
//             'idescr'        => 'Asahi Stand Fan BB 6026',
//             'Pomqty'        => 100.00,
//             'istdpk'        => 1,
//             'Expqty'        => 100.00,
//             'rcvqty'        => 100,
//             'Expday'        => 0,
//             'expiredate'    => '',
//         ],
//         [
//             'Inumber'       => 4253718,
//             'iupc'          => 2800042537186,
//             'idescr'        => 'Asahi Stand Fan BB 6026',
//             'Pomqty'        => 100.00,
//             'istdpk'        => 1,
//             'Expqty'        => 100.00,
//             'rcvqty'        => 100,
//             'Expday'        => 0,
//             'expiredate'    => '',
//         ],
//         [
//             'Inumber'       => 4253718,
//             'iupc'          => 2800042537186,
//             'idescr'        => 'Asahi Stand Fan BB 6026',
//             'Pomqty'        => 100.00,
//             'istdpk'        => 1,
//             'Expqty'        => 100.00,
//             'rcvqty'        => 100,
//             'Expday'        => 0,
//             'expiredate'    => '',
//         ],
//         [
//             'Inumber'       => 4253718,
//             'iupc'          => 2800042537186,
//             'idescr'        => 'Asahi Stand Fan BB 6026',
//             'Pomqty'        => 100.00,
//             'istdpk'        => 1,
//             'Expqty'        => 100.00,
//             'rcvqty'        => 100,
//             'Expday'        => 0,
//             'expiredate'    => '',
//         ],
//         [
//             'Inumber'       => 4253718,
//             'iupc'          => 2800042537186,
//             'idescr'        => 'Asahi Stand Fan BB 6026',
//             'Pomqty'        => 100.00,
//             'istdpk'        => 1,
//             'Expqty'        => 100.00,
//             'rcvqty'        => 100,
//             'Expday'        => 0,
//             'expiredate'    => '',
//         ],
//         [
//             'Inumber'       => 4253718,
//             'iupc'          => 2800042537186,
//             'idescr'        => 'Asahi Stand Fan BB 6026',
//             'Pomqty'        => 100.00,
//             'istdpk'        => 1,
//             'Expqty'        => 100.00,
//             'rcvqty'        => 100,
//             'Expday'        => 0,
//             'expiredate'    => '',
//         ],
//         [
//             'Inumber'       => 4253718,
//             'iupc'          => 2800042537186,
//             'idescr'        => 'Asahi Stand Fan BB 6026',
//             'Pomqty'        => 100.00,
//             'istdpk'        => 1,
//             'Expqty'        => 100.00,
//             'rcvqty'        => 100,
//             'Expday'        => 0,
//             'expiredate'    => '',
//         ],
//         [
//             'Inumber'       => 4253718,
//             'iupc'          => 2800042537186,
//             'idescr'        => 'Asahi Stand Fan BB 6026',
//             'Pomqty'        => 100.00,
//             'istdpk'        => 1,
//             'Expqty'        => 100.00,
//             'rcvqty'        => 100,
//             'Expday'        => 0,
//             'expiredate'    => '',
//         ],
//         [
//             'Inumber'       => 4253718,
//             'iupc'          => 2800042537186,
//             'idescr'        => 'Asahi Stand Fan BB 6026',
//             'Pomqty'        => 100.00,
//             'istdpk'        => 1,
//             'Expqty'        => 100.00,
//             'rcvqty'        => 100,
//             'Expday'        => 0,
//             'expiredate'    => '',
//         ],
//         [
//             'Inumber'       => 4253718,
//             'iupc'          => 2800042537186,
//             'idescr'        => 'Asahi Stand Fan BB 6026',
//             'Pomqty'        => 100.00,
//             'istdpk'        => 1,
//             'Expqty'        => 100.00,
//             'rcvqty'        => 100,
//             'Expday'        => 0,
//             'expiredate'    => '',
//         ],
//         [
//             'Inumber'       => 4253718,
//             'iupc'          => 2800042537186,
//             'idescr'        => 'Asahi Stand Fan BB 6026',
//             'Pomqty'        => 100.00,
//             'istdpk'        => 1,
//             'Expqty'        => 100.00,
//             'rcvqty'        => 100,
//             'Expday'        => 0,
//             'expiredate'    => '',
//         ],
//         [
//             'Inumber'       => 4253718,
//             'iupc'          => 2800042537186,
//             'idescr'        => 'Asahi Stand Fan BB 6026',
//             'Pomqty'        => 100.00,
//             'istdpk'        => 1,
//             'Expqty'        => 100.00,
//             'rcvqty'        => 100,
//             'Expday'        => 0,
//             'expiredate'    => '',
//         ],
//         [
//             'Inumber'       => 4253718,
//             'iupc'          => 2800042537186,
//             'idescr'        => 'Asahi Stand Fan BB 6026',
//             'Pomqty'        => 100.00,
//             'istdpk'        => 1,
//             'Expqty'        => 100.00,
//             'rcvqty'        => 100,
//             'Expday'        => 0,
//             'expiredate'    => '',
//         ],
//         [
//             'Inumber'       => 4253718,
//             'iupc'          => 2800042537186,
//             'idescr'        => 'Asahi Stand Fan BB 6026',
//             'Pomqty'        => 100.00,
//             'istdpk'        => 1,
//             'Expqty'        => 100.00,
//             'rcvqty'        => 100,
//             'Expday'        => 0,
//             'expiredate'    => '',
//         ],
//         [
//             'Inumber'       => 4253718,
//             'iupc'          => 2800042537186,
//             'idescr'        => 'Asahi Stand Fan BB 6026',
//             'Pomqty'        => 100.00,
//             'istdpk'        => 1,
//             'Expqty'        => 100.00,
//             'rcvqty'        => 100,
//             'Expday'        => 0,
//             'expiredate'    => '',
//         ],
//         [
//             'Inumber'       => 4253718,
//             'iupc'          => 2800042537186,
//             'idescr'        => 'Asahi Stand Fan BB 6026',
//             'Pomqty'        => 100.00,
//             'istdpk'        => 1,
//             'Expqty'        => 100.00,
//             'rcvqty'        => 100,
//             'Expday'        => 0,
//             'expiredate'    => '',
//         ],
//         [
//             'Inumber'       => 4253718,
//             'iupc'          => 2800042537186,
//             'idescr'        => 'Asahi Stand Fan BB 6026',
//             'Pomqty'        => 100.00,
//             'istdpk'        => 1,
//             'Expqty'        => 100.00,
//             'rcvqty'        => 100,
//             'Expday'        => 0,
//             'expiredate'    => '',
//         ],
//         [
//             'Inumber'       => 4253718,
//             'iupc'          => 2800042537186,
//             'idescr'        => 'Asahi Stand Fan BB 6026',
//             'Pomqty'        => 100.00,
//             'istdpk'        => 1,
//             'Expqty'        => 100.00,
//             'rcvqty'        => 100,
//             'Expday'        => 0,
//             'expiredate'    => '',
//         ],
//         [
//             'Inumber'       => 4253718,
//             'iupc'          => 2800042537186,
//             'idescr'        => 'Asahi Stand Fan BB 6026',
//             'Pomqty'        => 100.00,
//             'istdpk'        => 1,
//             'Expqty'        => 100.00,
//             'rcvqty'        => 100,
//             'Expday'        => 0,
//             'expiredate'    => '',
//         ],
//         [
//             'Inumber'       => 4253718,
//             'iupc'          => 2800042537186,
//             'idescr'        => 'Asahi Stand Fan BB 6026',
//             'Pomqty'        => 100.00,
//             'istdpk'        => 1,
//             'Expqty'        => 100.00,
//             'rcvqty'        => 100,
//             'Expday'        => 0,
//             'expiredate'    => '',
//         ],
//         [
//             'Inumber'       => 4253718,
//             'iupc'          => 2800042537186,
//             'idescr'        => 'Asahi Stand Fan BB 6026',
//             'Pomqty'        => 100.00,
//             'istdpk'        => 1,
//             'Expqty'        => 100.00,
//             'rcvqty'        => 100,
//             'Expday'        => 0,
//             'expiredate'    => '',
//         ],
//         [
//             'Inumber'       => 4253718,
//             'iupc'          => 2800042537186,
//             'idescr'        => 'Asahi Stand Fan BB 6026',
//             'Pomqty'        => 100.00,
//             'istdpk'        => 1,
//             'Expqty'        => 100.00,
//             'rcvqty'        => 100,
//             'Expday'        => 0,
//             'expiredate'    => '',
//         ],
//         [
//             'Inumber'       => 4253718,
//             'iupc'          => 2800042537186,
//             'idescr'        => 'Asahi Stand Fan BB 6026',
//             'Pomqty'        => 100.00,
//             'istdpk'        => 1,
//             'Expqty'        => 100.00,
//             'rcvqty'        => 100,
//             'Expday'        => 0,
//             'expiredate'    => '',
//         ],
//         [
//             'Inumber'       => 4253718,
//             'iupc'          => 2800042537186,
//             'idescr'        => 'Asahi Stand Fan BB 6026',
//             'Pomqty'        => 100.00,
//             'istdpk'        => 1,
//             'Expqty'        => 100.00,
//             'rcvqty'        => 100,
//             'Expday'        => 0,
//             'expiredate'    => '',
//         ],
//         [
//             'Inumber'       => 4253718,
//             'iupc'          => 2800042537186,
//             'idescr'        => 'Asahi Stand Fan BB 6026',
//             'Pomqty'        => 100.00,
//             'istdpk'        => 1,
//             'Expqty'        => 100.00,
//             'rcvqty'        => 100,
//             'Expday'        => 0,
//             'expiredate'    => '',
//         ],
//         [
//             'Inumber'       => 4253718,
//             'iupc'          => 2800042537186,
//             'idescr'        => 'Asahi Stand Fan BB 6026',
//             'Pomqty'        => 100.00,
//             'istdpk'        => 1,
//             'Expqty'        => 100.00,
//             'rcvqty'        => 100,
//             'Expday'        => 0,
//             'expiredate'    => '',
//         ],
//         [
//             'Inumber'       => 4253718,
//             'iupc'          => 2800042537186,
//             'idescr'        => 'Asahi Stand Fan BB 6026',
//             'Pomqty'        => 100.00,
//             'istdpk'        => 1,
//             'Expqty'        => 100.00,
//             'rcvqty'        => 100,
//             'Expday'        => 0,
//             'expiredate'    => '',
//         ],
//         [
//             'Inumber'       => 4253718,
//             'iupc'          => 2800042537186,
//             'idescr'        => 'Asahi Stand Fan BB 6026',
//             'Pomqty'        => 100.00,
//             'istdpk'        => 1,
//             'Expqty'        => 100.00,
//             'rcvqty'        => 100,
//             'Expday'        => 0,
//             'expiredate'    => '',
//         ],
//         [
//             'Inumber'       => 4253718,
//             'iupc'          => 2800042537186,
//             'idescr'        => 'Asahi Stand Fan BB 6026',
//             'Pomqty'        => 100.00,
//             'istdpk'        => 1,
//             'Expqty'        => 100.00,
//             'rcvqty'        => 100,
//             'Expday'        => 0,
//             'expiredate'    => '',
//         ],
//         [
//             'Inumber'       => 4253718,
//             'iupc'          => 2800042537186,
//             'idescr'        => 'Asahi Stand Fan BB 6026',
//             'Pomqty'        => 100.00,
//             'istdpk'        => 1,
//             'Expqty'        => 100.00,
//             'rcvqty'        => 100,
//             'Expday'        => 0,
//             'expiredate'    => '',
//         ],
//         [
//             'Inumber'       => 4253718,
//             'iupc'          => 2800042537186,
//             'idescr'        => 'Asahi Stand Fan BB 6026',
//             'Pomqty'        => 100.00,
//             'istdpk'        => 1,
//             'Expqty'        => 100.00,
//             'rcvqty'        => 100,
//             'Expday'        => 0,
//             'expiredate'    => '',
//         ],
//         [
//             'Inumber'       => 4253718,
//             'iupc'          => 2800042537186,
//             'idescr'        => 'Asahi Stand Fan BB 6026',
//             'Pomqty'        => 100.00,
//             'istdpk'        => 1,
//             'Expqty'        => 100.00,
//             'rcvqty'        => 100,
//             'Expday'        => 0,
//             'expiredate'    => '',
//         ],
//         [
//             'Inumber'       => 4253718,
//             'iupc'          => 2800042537186,
//             'idescr'        => 'Asahi Stand Fan BB 6026',
//             'Pomqty'        => 100.00,
//             'istdpk'        => 1,
//             'Expqty'        => 100.00,
//             'rcvqty'        => 100,
//             'Expday'        => 0,
//             'expiredate'    => '',
//         ],
//         [
//             'Inumber'       => 4253718,
//             'iupc'          => 2800042537186,
//             'idescr'        => 'Asahi Stand Fan BB 6026',
//             'Pomqty'        => 100.00,
//             'istdpk'        => 1,
//             'Expqty'        => 100.00,
//             'rcvqty'        => 100,
//             'Expday'        => 0,
//             'expiredate'    => '',
//         ],
//         [
//             'Inumber'       => 4253718,
//             'iupc'          => 2800042537186,
//             'idescr'        => 'Asahi Stand Fan BB 6026',
//             'Pomqty'        => 100.00,
//             'istdpk'        => 1,
//             'Expqty'        => 100.00,
//             'rcvqty'        => 100,
//             'Expday'        => 0,
//             'expiredate'    => '',
//         ],
//         [
//             'Inumber'       => 4253718,
//             'iupc'          => 2800042537186,
//             'idescr'        => 'Asahi Stand Fan BB 6026',
//             'Pomqty'        => 100.00,
//             'istdpk'        => 1,
//             'Expqty'        => 100.00,
//             'rcvqty'        => 100,
//             'Expday'        => 0,
//             'expiredate'    => '',
//         ],
//         [
//             'Inumber'       => 4253718,
//             'iupc'          => 2800042537186,
//             'idescr'        => 'Asahi Stand Fan BB 6026',
//             'Pomqty'        => 100.00,
//             'istdpk'        => 1,
//             'Expqty'        => 100.00,
//             'rcvqty'        => 100,
//             'Expday'        => 0,
//             'expiredate'    => '',
//         ],
//         [
//             'Inumber'       => 4253718,
//             'iupc'          => 2800042537186,
//             'idescr'        => 'Asahi Stand Fan BB 6026',
//             'Pomqty'        => 100.00,
//             'istdpk'        => 1,
//             'Expqty'        => 100.00,
//             'rcvqty'        => 100,
//             'Expday'        => 0,
//             'expiredate'    => '',
//         ],
//         [
//             'Inumber'       => 4253718,
//             'iupc'          => 2800042537186,
//             'idescr'        => 'Asahi Stand Fan BB 6026',
//             'Pomqty'        => 100.00,
//             'istdpk'        => 1,
//             'Expqty'        => 100.00,
//             'rcvqty'        => 100,
//             'Expday'        => 0,
//             'expiredate'    => '',
//         ],
//         [
//             'Inumber'       => 4253718,
//             'iupc'          => 2800042537186,
//             'idescr'        => 'Asahi Stand Fan BB 6026',
//             'Pomqty'        => 100.00,
//             'istdpk'        => 1,
//             'Expqty'        => 100.00,
//             'rcvqty'        => 100,
//             'Expday'        => 0,
//             'expiredate'    => '',
//         ],
//         [
//             'Inumber'       => 4253718,
//             'iupc'          => 2800042537186,
//             'idescr'        => 'Asahi Stand Fan BB 6026',
//             'Pomqty'        => 100.00,
//             'istdpk'        => 1,
//             'Expqty'        => 100.00,
//             'rcvqty'        => 100,
//             'Expday'        => 0,
//             'expiredate'    => '',
//         ],
//         [
//             'Inumber'       => 4253718,
//             'iupc'          => 2800042537186,
//             'idescr'        => 'Asahi Stand Fan BB 6026',
//             'Pomqty'        => 100.00,
//             'istdpk'        => 1,
//             'Expqty'        => 100.00,
//             'rcvqty'        => 100,
//             'Expday'        => 0,
//             'expiredate'    => '',
//         ],
//         [
//             'Inumber'       => 4253718,
//             'iupc'          => 2800042537186,
//             'idescr'        => 'Asahi Stand Fan BB 6026',
//             'Pomqty'        => 100.00,
//             'istdpk'        => 1,
//             'Expqty'        => 100.00,
//             'rcvqty'        => 100,
//             'Expday'        => 0,
//             'expiredate'    => '',
//         ],
//         [
//             'Inumber'       => 4253718,
//             'iupc'          => 2800042537186,
//             'idescr'        => 'Asahi Stand Fan BB 6026',
//             'Pomqty'        => 100.00,
//             'istdpk'        => 1,
//             'Expqty'        => 100.00,
//             'rcvqty'        => 100,
//             'Expday'        => 0,
//             'expiredate'    => '',
//         ],
//         [
//             'Inumber'       => 4253718,
//             'iupc'          => 2800042537186,
//             'idescr'        => 'Asahi Stand Fan BB 6026',
//             'Pomqty'        => 100.00,
//             'istdpk'        => 1,
//             'Expqty'        => 100.00,
//             'rcvqty'        => 100,
//             'Expday'        => 0,
//             'expiredate'    => '',
//         ],
//         [
//             'Inumber'       => 4253718,
//             'iupc'          => 2800042537186,
//             'idescr'        => 'Asahi Stand Fan BB 6026',
//             'Pomqty'        => 100.00,
//             'istdpk'        => 1,
//             'Expqty'        => 100.00,
//             'rcvqty'        => 100,
//             'Expday'        => 0,
//             'expiredate'    => '',
//         ],
//         [
//             'Inumber'       => 4253718,
//             'iupc'          => 2800042537186,
//             'idescr'        => 'Asahi Stand Fan BB 6026',
//             'Pomqty'        => 100.00,
//             'istdpk'        => 1,
//             'Expqty'        => 100.00,
//             'rcvqty'        => 100,
//             'Expday'        => 0,
//             'expiredate'    => '',
//         ],
//         [
//             'Inumber'       => 4253718,
//             'iupc'          => 2800042537186,
//             'idescr'        => 'Asahi Stand Fan BB 6026',
//             'Pomqty'        => 100.00,
//             'istdpk'        => 1,
//             'Expqty'        => 100.00,
//             'rcvqty'        => 100,
//             'Expday'        => 0,
//             'expiredate'    => '',
//         ],
//         [
//             'Inumber'       => 4253718,
//             'iupc'          => 2800042537186,
//             'idescr'        => 'Asahi Stand Fan BB 6026',
//             'Pomqty'        => 100.00,
//             'istdpk'        => 1,
//             'Expqty'        => 100.00,
//             'rcvqty'        => 100,
//             'Expday'        => 0,
//             'expiredate'    => '',
//         ],
//         [
//             'Inumber'       => 4253718,
//             'iupc'          => 2800042537186,
//             'idescr'        => 'Asahi Stand Fan BB 6026',
//             'Pomqty'        => 100.00,
//             'istdpk'        => 1,
//             'Expqty'        => 100.00,
//             'rcvqty'        => 100,
//             'Expday'        => 0,
//             'expiredate'    => '',
//         ],
//         [
//             'Inumber'       => 4253718,
//             'iupc'          => 2800042537186,
//             'idescr'        => 'Asahi Stand Fan BB 6026',
//             'Pomqty'        => 100.00,
//             'istdpk'        => 1,
//             'Expqty'        => 100.00,
//             'rcvqty'        => 100,
//             'Expday'        => 0,
//             'expiredate'    => '',
//         ],
//         [
//             'Inumber'       => 4253718,
//             'iupc'          => 2800042537186,
//             'idescr'        => 'Asahi Stand Fan BB 6026',
//             'Pomqty'        => 100.00,
//             'istdpk'        => 1,
//             'Expqty'        => 100.00,
//             'rcvqty'        => 100,
//             'Expday'        => 0,
//             'expiredate'    => '',
//         ],
//         [
//             'Inumber'       => 4253718,
//             'iupc'          => 2800042537186,
//             'idescr'        => 'Asahi Stand Fan BB 6026',
//             'Pomqty'        => 100.00,
//             'istdpk'        => 1,
//             'Expqty'        => 100.00,
//             'rcvqty'        => 100,
//             'Expday'        => 0,
//             'expiredate'    => '',
//         ],
//         [
//             'Inumber'       => 4253718,
//             'iupc'          => 2800042537186,
//             'idescr'        => 'Asahi Stand Fan BB 6026',
//             'Pomqty'        => 100.00,
//             'istdpk'        => 1,
//             'Expqty'        => 100.00,
//             'rcvqty'        => 100,
//             'Expday'        => 0,
//             'expiredate'    => '',
//         ],
//         [
//             'Inumber'       => 4253718,
//             'iupc'          => 2800042537186,
//             'idescr'        => 'Asahi Stand Fan BB 6026',
//             'Pomqty'        => 100.00,
//             'istdpk'        => 1,
//             'Expqty'        => 100.00,
//             'rcvqty'        => 100,
//             'Expday'        => 0,
//             'expiredate'    => '',
//         ],
//         [
//             'Inumber'       => 4253718,
//             'iupc'          => 2800042537186,
//             'idescr'        => 'Asahi Stand Fan BB 6026',
//             'Pomqty'        => 100.00,
//             'istdpk'        => 1,
//             'Expqty'        => 100.00,
//             'rcvqty'        => 100,
//             'Expday'        => 0,
//             'expiredate'    => '',
//         ],
//         [
//             'Inumber'       => 4253718,
//             'iupc'          => 2800042537186,
//             'idescr'        => 'Asahi Stand Fan BB 6026',
//             'Pomqty'        => 100.00,
//             'istdpk'        => 1,
//             'Expqty'        => 100.00,
//             'rcvqty'        => 100,
//             'Expday'        => 0,
//             'expiredate'    => '',
//         ],
//         [
//             'Inumber'       => 4253718,
//             'iupc'          => 2800042537186,
//             'idescr'        => 'Asahi Stand Fan BB 6026',
//             'Pomqty'        => 100.00,
//             'istdpk'        => 1,
//             'Expqty'        => 100.00,
//             'rcvqty'        => 100,
//             'Expday'        => 0,
//             'expiredate'    => '',
//         ],
//         [
//             'Inumber'       => 4253718,
//             'iupc'          => 2800042537186,
//             'idescr'        => 'Asahi Stand Fan BB 6026',
//             'Pomqty'        => 100.00,
//             'istdpk'        => 1,
//             'Expqty'        => 100.00,
//             'rcvqty'        => 100,
//             'Expday'        => 0,
//             'expiredate'    => '',
//         ],
//         [
//             'Inumber'       => 4253718,
//             'iupc'          => 2800042537186,
//             'idescr'        => 'Asahi Stand Fan BB 6026',
//             'Pomqty'        => 100.00,
//             'istdpk'        => 1,
//             'Expqty'        => 100.00,
//             'rcvqty'        => 100,
//             'Expday'        => 0,
//             'expiredate'    => '',
//         ],
//         [
//             'Inumber'       => 4253718,
//             'iupc'          => 2800042537186,
//             'idescr'        => 'Asahi Stand Fan BB 6026',
//             'Pomqty'        => 100.00,
//             'istdpk'        => 1,
//             'Expqty'        => 100.00,
//             'rcvqty'        => 100,
//             'Expday'        => 0,
//             'expiredate'    => '',
//         ],
//         [
//             'Inumber'       => 4253718,
//             'iupc'          => 2800042537186,
//             'idescr'        => 'Asahi Stand Fan BB 6026',
//             'Pomqty'        => 100.00,
//             'istdpk'        => 1,
//             'Expqty'        => 100.00,
//             'rcvqty'        => 100,
//             'Expday'        => 0,
//             'expiredate'    => '',
//         ],
//         [
//             'Inumber'       => 4253718,
//             'iupc'          => 2800042537186,
//             'idescr'        => 'Asahi Stand Fan BB 6026',
//             'Pomqty'        => 100.00,
//             'istdpk'        => 1,
//             'Expqty'        => 100.00,
//             'rcvqty'        => 100,
//             'Expday'        => 0,
//             'expiredate'    => '',
//         ],
//         [
//             'Inumber'       => 4253718,
//             'iupc'          => 2800042537186,
//             'idescr'        => 'Asahi Stand Fan BB 6026',
//             'Pomqty'        => 100.00,
//             'istdpk'        => 1,
//             'Expqty'        => 100.00,
//             'rcvqty'        => 100,
//             'Expday'        => 0,
//             'expiredate'    => '',
//         ],
//         [
//             'Inumber'       => 4253718,
//             'iupc'          => 2800042537186,
//             'idescr'        => 'Asahi Stand Fan BB 6026',
//             'Pomqty'        => 100.00,
//             'istdpk'        => 1,
//             'Expqty'        => 100.00,
//             'rcvqty'        => 100,
//             'Expday'        => 0,
//             'expiredate'    => '',
//         ],
//         [
//             'Inumber'       => 4253718,
//             'iupc'          => 2800042537186,
//             'idescr'        => 'Asahi Stand Fan BB 6026',
//             'Pomqty'        => 100.00,
//             'istdpk'        => 1,
//             'Expqty'        => 100.00,
//             'rcvqty'        => 100,
//             'Expday'        => 0,
//             'expiredate'    => '',
//         ],
//         [
//             'Inumber'       => 4253718,
//             'iupc'          => 2800042537186,
//             'idescr'        => 'Asahi Stand Fan BB 6026',
//             'Pomqty'        => 100.00,
//             'istdpk'        => 1,
//             'Expqty'        => 100.00,
//             'rcvqty'        => 100,
//             'Expday'        => 0,
//             'expiredate'    => '',
//         ],
//         [
//             'Inumber'       => 4253718,
//             'iupc'          => 2800042537186,
//             'idescr'        => 'Asahi Stand Fan BB 6026',
//             'Pomqty'        => 100.00,
//             'istdpk'        => 1,
//             'Expqty'        => 100.00,
//             'rcvqty'        => 100,
//             'Expday'        => 0,
//             'expiredate'    => '',
//         ],
//         [
//             'Inumber'       => 4253718,
//             'iupc'          => 2800042537186,
//             'idescr'        => 'Asahi Stand Fan BB 6026',
//             'Pomqty'        => 100.00,
//             'istdpk'        => 1,
//             'Expqty'        => 100.00,
//             'rcvqty'        => 100,
//             'Expday'        => 0,
//             'expiredate'    => '',
//         ],
//         [
//             'Inumber'       => 4253718,
//             'iupc'          => 2800042537186,
//             'idescr'        => 'Asahi Stand Fan BB 6026',
//             'Pomqty'        => 100.00,
//             'istdpk'        => 1,
//             'Expqty'        => 100.00,
//             'rcvqty'        => 100,
//             'Expday'        => 0,
//             'expiredate'    => '',
//         ],
//         [
//             'Inumber'       => 4253718,
//             'iupc'          => 2800042537186,
//             'idescr'        => 'Asahi Stand Fan BB 6026',
//             'Pomqty'        => 100.00,
//             'istdpk'        => 1,
//             'Expqty'        => 100.00,
//             'rcvqty'        => 100,
//             'Expday'        => 0,
//             'expiredate'    => '',
//         ],
//         [
//             'Inumber'       => 4253718,
//             'iupc'          => 2800042537186,
//             'idescr'        => 'Asahi Stand Fan BB 6026',
//             'Pomqty'        => 100.00,
//             'istdpk'        => 1,
//             'Expqty'        => 100.00,
//             'rcvqty'        => 100,
//             'Expday'        => 0,
//             'expiredate'    => '',
//         ],
//         [
//             'Inumber'       => 4253718,
//             'iupc'          => 2800042537186,
//             'idescr'        => 'Asahi Stand Fan BB 6026',
//             'Pomqty'        => 100.00,
//             'istdpk'        => 1,
//             'Expqty'        => 100.00,
//             'rcvqty'        => 100,
//             'Expday'        => 0,
//             'expiredate'    => '',
//         ],
//         [
//             'Inumber'       => 4253718,
//             'iupc'          => 2800042537186,
//             'idescr'        => 'Asahi Stand Fan BB 6026',
//             'Pomqty'        => 100.00,
//             'istdpk'        => 1,
//             'Expqty'        => 100.00,
//             'rcvqty'        => 100,
//             'Expday'        => 0,
//             'expiredate'    => '',
//         ],
//         [
//             'Inumber'       => 4253718,
//             'iupc'          => 2800042537186,
//             'idescr'        => 'Asahi Stand Fan BB 6026',
//             'Pomqty'        => 100.00,
//             'istdpk'        => 1,
//             'Expqty'        => 100.00,
//             'rcvqty'        => 100,
//             'Expday'        => 0,
//             'expiredate'    => '',
//         ],
//         [
//             'Inumber'       => 4253718,
//             'iupc'          => 2800042537186,
//             'idescr'        => 'Asahi Stand Fan BB 6026',
//             'Pomqty'        => 100.00,
//             'istdpk'        => 1,
//             'Expqty'        => 100.00,
//             'rcvqty'        => 100,
//             'Expday'        => 0,
//             'expiredate'    => '',
//         ],
//         [
//             'Inumber'       => 4253718,
//             'iupc'          => 2800042537186,
//             'idescr'        => 'Asahi Stand Fan BB 6026',
//             'Pomqty'        => 100.00,
//             'istdpk'        => 1,
//             'Expqty'        => 100.00,
//             'rcvqty'        => 100,
//             'Expday'        => 0,
//             'expiredate'    => '',
//         ],
//         [
//             'Inumber'       => 4253718,
//             'iupc'          => 2800042537186,
//             'idescr'        => 'Asahi Stand Fan BB 6026',
//             'Pomqty'        => 100.00,
//             'istdpk'        => 1,
//             'Expqty'        => 100.00,
//             'rcvqty'        => 100,
//             'Expday'        => 0,
//             'expiredate'    => '',
//         ],
//         [
//             'Inumber'       => 4253718,
//             'iupc'          => 2800042537186,
//             'idescr'        => 'Asahi Stand Fan BB 6026',
//             'Pomqty'        => 100.00,
//             'istdpk'        => 1,
//             'Expqty'        => 100.00,
//             'rcvqty'        => 100,
//             'Expday'        => 0,
//             'expiredate'    => '',
//         ],
//         [
//             'Inumber'       => 4253718,
//             'iupc'          => 2800042537186,
//             'idescr'        => 'Asahi Stand Fan BB 6026',
//             'Pomqty'        => 100.00,
//             'istdpk'        => 1,
//             'Expqty'        => 100.00,
//             'rcvqty'        => 100,
//             'Expday'        => 0,
//             'expiredate'    => '',
//         ],
//         [
//             'Inumber'       => 4253718,
//             'iupc'          => 2800042537186,
//             'idescr'        => 'Asahi Stand Fan BB 6026',
//             'Pomqty'        => 100.00,
//             'istdpk'        => 1,
//             'Expqty'        => 100.00,
//             'rcvqty'        => 100,
//             'Expday'        => 0,
//             'expiredate'    => '',
//         ],
//     )
// );

// $pdf = new PDF();
// $pdf->receiver($data);
// $pdf->Output();
// $pdf->Output('D', $data['Ponumb'] . '.pdf');
  
?>