<?php
date_default_timezone_set('Asia/Manila');
require('../Classes/MMSDatabaseClass.php');

class PDF extends FPDF
{

    function downloadPDF($file_names = array(), $user_id, $store_code) {
        ob_end_clean(); 
        $path = '../PDF/';
    
        if (sizeof($file_names) == 0) return;
    
        else if (sizeof($file_names) == 1){
            $file = $path . $file_names[0];
            $content = file_get_contents($file);
            header('Content-Type: application/pdf');
            header('Content-disposition: attachment; filename='. $file_names[0]);
            header('Content-Length: '. strlen($content));
            echo $content;
            return;
        }
    
        /*
        |--------------------------------------------------------------------------
        | IF HAS MULTIPLE PDF, ARCHIVE FIRST THEN DOWNLOAD
        | REFERENCE: https://stackoverflow.com/a/64479856/18159572
        |--------------------------------------------------------------------------
        */
    
        $zip = new ZipArchive();
        $zip_name = $user_id .'_'. $store_code .'.zip';
        $zip_file = $path . $zip_name;
    
        // OPEN ZIP
        if (file_exists($zip_file)) $zip->open($zip_file, ZipArchive::OVERWRITE);
        else $zip->open($zip_file, ZipArchive::CREATE);
    
        // ADD FILES TO ZIP
        foreach ($file_names as $value) {
            $zip->addFile($path . $value, $value);
        }
    
        // CLOSE ZIP
        $zip->close(); 
        
        // DOWNLOAD ZIP
        header('Content-Type: application/zip');
        header('Content-disposition: attachment; filename='. $zip_name);
        header("Content-length: " . filesize($zip_file));
        readfile($zip_file);
    }


    function Open($file)
    {
        if(FPDF_VERSION<'1.8')
            $this->Error('Version 1.8 or above is required by this extension');
        $this->f=fopen($file,'wb');
        if(!$this->f)
            $this->Error('Unable to create output file: '.$file);
        $this->_putheader();
    }




    //Cell with horizontal scaling if text is too wide
    function CellFit($w, $h=0, $txt='', $border=0, $ln=0, $align='', $fill=false, $link='', $scale=false, $force=true)
    {
        //Get string width
        $str_width=$this->GetStringWidth($txt);

        //Calculate ratio to fit cell
        if($w==0)
            $w = $this->w-$this->rMargin-$this->x;
        $ratio = ($w-$this->cMargin*2)/$str_width;

        $fit = ($ratio < 1 || ($ratio > 1 && $force));
        if ($fit)
        {
            if ($scale)
            {
                //Calculate horizontal scaling
                $horiz_scale=$ratio*100.0;
                //Set horizontal scaling
                $this->_out(sprintf('BT %.2F Tz ET',$horiz_scale));
            }
            else
            {
                //Calculate character spacing in points
                $char_space=($w-$this->cMargin*2-$str_width)/max(strlen($txt)-1,1)*$this->k;
                //Set character spacing
                $this->_out(sprintf('BT %.2F Tc ET',$char_space));
            }
            //Override user alignment (since text will fill up cell)
            $align='';
        }

        //Pass on to Cell method
        $this->Cell($w,$h,$txt,$border,$ln,$align,$fill,$link);

        //Reset character spacing/horizontal scaling
        if ($fit)
            $this->_out('BT '.($scale ? '100 Tz' : '0 Tc').' ET');
    }

    //Cell with horizontal scaling only if necessary
    function CellFitScale($w, $h=0, $txt='', $border=0, $ln=0, $align='', $fill=false, $link='')
    {
        $this->CellFit($w,$h,$txt,$border,$ln,$align,$fill,$link,true,false);
    }

    //Cell with horizontal scaling always
    function CellFitScaleForce($w, $h=0, $txt='', $border=0, $ln=0, $align='', $fill=false, $link='')
    {
        $this->CellFit($w,$h,$txt,$border,$ln,$align,$fill,$link,true,true);
    }

    //Cell with character spacing only if necessary
    function CellFitSpace($w, $h=0, $txt='', $border=0, $ln=0, $align='', $fill=false, $link='')
    {
        $this->CellFit($w,$h,$txt,$border,$ln,$align,$fill,$link,false,false);
    }

    //Cell with character spacing always
    function CellFitSpaceForce($w, $h=0, $txt='', $border=0, $ln=0, $align='', $fill=false, $link='')
    {
        //Same as calling CellFit directly
        $this->CellFit($w,$h,$txt,$border,$ln,$align,$fill,$link,false,true);
    }    




    //TABLLEEEESSSSSSS+===========================

// Load data
function LoadData($file)
{
    // Read file lines
    $lines = file($file);
    $data = array();
    foreach($lines as $line)
        $data[] = explode(';',trim($line));
    return $data;
}

// Simple table
function BasicTable($header, $data)
{
    // Header
    foreach($header as $col)
        $this->Cell(40,7,$col,1);
    $this->Ln();
    // Data
    foreach($data as $row)
    {
        foreach($row as $col)
            $this->Cell(40,6,$col,1);
        $this->Ln();
    }
}

function Header()
{
    $obj_mms = new MMSDatabaseClass();

    $mms_data = $obj_mms->mms_get_trf_data($this->trfnum);
    $trftlc         = $mms_data[0]['TRFTLC'];
    $tlcnam         = $mms_data[0]['TLCNAM'];
    $trfflc         = $mms_data[0]['TRFFLC'];
    $flcnam         = $mms_data[0]['FLCNAM'];
    $trftyp         = $mms_data[0]['TRFTYP'];
    $typdsc         = $mms_data[0]['TYPDSC'];
    $trfpty         = $mms_data[0]['TRFPTY'];
    $ptydsc         = $mms_data[0]['PTYDSC'];
    $this->Cell(195.9,5,'LCC',0,1,'C');
    $this->Cell(195.9,5,'Shipping Manifest by SKU',0,1,'C');
    $this->Cell(195.9,5,'Date : '.date('M d,Y'),0,1,'C');
    $this->Cell(97.95,5,'Time : '.date('H:s a'),0,0);
    $this->Cell(97.95,5,'Page :'.$this->PageNo().'/{nb}',0,1,'R');
    $this->Cell(97.95,5,'Ship to :'.$trftlc.' - '.$tlcnam,0,0);
    $this->Cell(97.95,5,'Origination :'.$trfflc.' - '.$flcnam,0,1);
    $this->Cell(97.95,5,'Transfer Number :'.$this->trfnum,0,1);
    $this->Cell(97.95,5,'Transfer Type :'.$trftyp.' - '.$typdsc,0,1);
    $this->Cell(97.95,5,'Transfer Priority :'.$trfpty.' - '.$ptydsc,0,1);
    // Column widths
    $w = array(20.4875, 28.4875, 64.4875, 16.4875, 16.4875, 16.4875, 16.4875, 16.4875);
    // Header
    for($i=0;$i<count($this->headers_array);$i++){
        $this->Cell($w[$i],7,$this->headers_array[$i],1,0,'C');
    }
    $this->Ln();
}

function Footer()
{
    //$w = array(20.4875, 28.4875, 64.4875, 16.4875, 16.4875, 16.4875, 16.4875, 16.4875)

    $this->Cell(113.4625,7,'Page Total',1,0,'C');
    $this->Cell(16.4875,7,number_format($this->grand_shpqty,2),1,0,'R');
    $this->Cell(16.4875,7,number_format($this->grand_bum,2),1,0,'R');
    $this->Cell(16.4875,7,number_format($this->grand_expqty,2),1,0,'R');
    $this->Cell(16.4875,7,number_format($this->grand_rcvqty,2),1,0,'R');
    $this->Cell(16.4875,7,number_format($this->grand_shtqty,2),1,1,'R');

    if($this->isrowend){
        $this->GrandTotal();

        $this->Cell(195.9,20,'',0,1);
        $this->Cell(48.975,7,'_____________________',0,0,'C');
        $this->Cell(48.975,7,'_____________________',0,0,'C');
        $this->Cell(48.975,7,'_____________________',0,0,'C');
        $this->Cell(48.975,7,'_____________________',0,1,'C');

        $this->Cell(48.975,7,'Received by',0,0,'C');
        $this->Cell(48.975,7,'Date Received',0,0,'C');
        $this->Cell(48.975,7,'Confirmed by',0,0,'C');
        $this->Cell(48.975,7,'Tallied by',0,1,'C');        
        
    }    


    // Go to 1.5 cm from bottom
    $this->SetY(-15);
    // Select Arial italic 8
    $this->SetFont('Arial','I',8);
    // Print centered page number
    $this->Cell(0,10,'Page '.$this->PageNo(),0,0,'C');
}

function GrandTotal()
{
    $this->Cell(113.4625,7,'Grand Total',1,0,'C');
    $this->Cell(16.4875,7,number_format($this->grand_shpqty,2),1,0,'R');
    $this->Cell(16.4875,7,number_format($this->grand_bum,2),1,0,'R');
    $this->Cell(16.4875,7,number_format($this->grand_expqty,2),1,0,'R');
    $this->Cell(16.4875,7,number_format($this->grand_rcvqty,2),1,0,'R');
    $this->Cell(16.4875,7,number_format($this->grand_shtqty,2),1,1,'R');
}


// Better table
function ImprovedTable($header, $data)
{
    // Column widths
    $w = array(20.4875, 28.4875, 64.4875, 16.4875, 16.4875, 16.4875, 16.4875, 16.4875);


    // Data
    foreach($data as $row)
    {
        $this->Cell($w[0],6,$row[0],0,0);
        $this->CellFitScale($w[1],6,$row[1],0,0);
        $this->CellFitScale($w[2],6,$row[2],0,0);
        $this->Cell($w[3],6,$row[3],0,0,'R');
        $this->Cell($w[4],6,$row[4],0,0,'R');
        $this->Cell($w[5],6,$row[5],0,0,'R');
        $this->Cell($w[6],6,$row[6],0,0,'R');
        $this->Cell($w[7],6,$row[7],0,1,'R');
        //$this->Ln();

    }
    $this->isrowend = true;
    // Closing line
    //$this->Cell(array_sum($w),1,'','T');

    $this->Ln(5);
}

// Colored table
function FancyTable($header, $data)
{
    // Colors, line width and bold font
    $this->SetFillColor(255,0,0);
    $this->SetTextColor(255);
    $this->SetDrawColor(128,0,0);
    $this->SetLineWidth(.3);
    $this->SetFont('','B');
    // Header
    $w = array(40, 35, 40, 45);
    for($i=0;$i<count($header);$i++)
        $this->Cell($w[$i],7,$header[$i],1,0,'C',true);
    $this->Ln();
    // Color and font restoration
    $this->SetFillColor(224,235,255);
    $this->SetTextColor(0);
    $this->SetFont('');
    // Data
    $fill = false;
    foreach($data as $row)
    {
        $this->Cell($w[0],6,$row[0],'LR',0,'L',$fill);
        $this->Cell($w[1],6,$row[1],'LR',0,'L',$fill);
        $this->Cell($w[2],6,$row[2],'LR',0,'R',$fill);
        $this->Cell($w[3],6,$row[3],'LR',0,'R',$fill);
        $this->Ln();
        $fill = !$fill;
    }
    // Closing line
    $this->Cell(array_sum($w),0,'','T');

}    


}


//instanciate
$pdf = new PDF('P','mm','Letter');
$pdf->AliasNbPages();