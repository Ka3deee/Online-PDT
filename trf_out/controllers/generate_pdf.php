<?php
    session_start();
    date_default_timezone_set('Asia/Manila');
    require('fpdf/fpdf.php');
    include('../path.php');
    include(ROOT_PATH . "/databases/connect_mms.php");
    include(ROOT_PATH . "/databases/connect_mysql.php");
    include(ROOT_PATH . '/databases/functions.php');

    $user_no = $_SESSION['employee_no'];   
    $currentDate = date("Ymd");

    $mms_pick = 'mms_pick';     // ----------------------------------------- MySQL Tables
    $users = 'users';           
    $audit_log = 'audit_log';  

    $selectedBatch = isset($_GET['batch_no']) ? urldecode($_GET['batch_no']) : '';
    if ($selectedBatch != '') {
        $batch_no = $selectedBatch;

        $audit_data = selectAll($audit_log, ['batch_no' => $batch_no]);
        $pick_id = array();
        foreach ($audit_data as $id) {
            $pick_id[] = $id['mms_pick_id'];
            update($audit_log, $id['id'], ['is_gen_report' => 1]);
        }
        
        $mms_id = selectAllId($mms_pick, $pick_id);
        $grouped_whmove = array_count_values(array_map('strval', array_column($mms_id, 'whmove')));
        
        $pdf = new FPDF('P', 'mm', "Letter");
        $pdf->AliasNbPages();
        
        foreach ($grouped_whmove as $whmove_num => $count) {

            $pdf->AddPage();
            $mms_data = selectAll($mms_pick, ['whmove' => $whmove_num]); // ----------- Select all mms_pick row with whmove = $whmove

            $strnum = array();
            foreach($mms_data as $num) {
                $strnum = $num['strnum'];
            }
            $odbc_statement = "SELECT strnum,strnam FROM tblstr where strnum='$strnum'"; // ------------ Get Store from MMS
            $result = odbc_exec($conn_m, $odbc_statement);
            $strcode;
            while (odbc_fetch_row($result)) {
                $ifhasrow = true;
                $strcode = odbc_result($result, "strnum");
                $strloc = odbc_result($result, "strnam");
            }

            $exp_page_total = 0;
            $relsd_page_total = 0;
            $short_page_total = 0;

            $pdf->SetFont('Arial', '', 11); // --------------------------------------------------- PDF Header
            $pdf->Cell(138, 5, 'Location : '.$strnum.' - '.$strloc.'', 0,0);
            $pdf->Cell(0, 5, 'Document Number : '.$whmove_num.'', 0,0);
            $pdf->Ln(7);

            $cell_height = 7;   // --------------------------------------------------------------- PDF Table Header
            $pdf->SetFont('Arial','B', 9);
            $pdf->Cell(16, $cell_height, 'SKU', 1, 0, 'C');
            $pdf->Cell(26, $cell_height, 'UPC', 1, 0, 'C');
            $pdf->Cell(57, $cell_height, 'Description', 1, 0, 'C');
            $pdf->Cell(20, $cell_height, 'Rqst.Qty', 1, 0, 'C');
            $pdf->Cell(11, $cell_height, 'BUM', 1, 0, 'C');
            $pdf->Cell(20, $cell_height, 'Exp.Qty', 1, 0, 'C');
            $pdf->Cell(20, $cell_height, 'Relsd.Qty', 1, 0, 'C');
            $pdf->Cell(20, $cell_height, 'Short Qty', 1, 0, 'C');
            $pdf->Ln($cell_height);
        
            foreach ($mms_data as $data) {  // --------------------------------------------------- PDF Table Rows
                $pdf->SetFont('Arial','', 9);
                $pdf->Cell(16, $cell_height, $data['inumbr'], 1, 0, 'C');
                $pdf->Cell(26, $cell_height, $data['iupc'], 1, 0, 'C');
                $pdf->Cell(57, $cell_height, $data['idescr'], 1, 0, 'L');
                $pdf->Cell(20, $cell_height, $data['whmvqr'], 1, 0, 'R');
                $pdf->Cell(11, $cell_height, $data['whmvsq'], 1, 0, 'C');
                $pdf->Cell(20, $cell_height, $data['expqty'], 1, 0, 'R');
                $pdf->Cell(20, $cell_height, $data['rcvqty'], 1, 0, 'R');
                $pdf->Cell(20, $cell_height, number_format($data['expqty'] - $data['rcvqty'], 2), 1, 0, 'R');
                $pdf->Ln($cell_height);
        
                $exp_page_total += $data['expqty'];
                $relsd_page_total += $data['rcvqty'];
                $short_page_total += $data['expqty'];
            }

            $pdf->SetFont('Arial','B', 10); // --------------------------------------------------- PDF Table Total
            $pdf->Cell(130, $cell_height, 'Page Total', 1, 0, 'R');
            $pdf->Cell(20, $cell_height, number_format($exp_page_total, 2), 1, 0, 'R');
            $pdf->Cell(20, $cell_height, number_format($relsd_page_total, 2), 1, 0, 'R');
            $pdf->Cell(20, $cell_height, number_format($short_page_total, 2), 1, 0, 'R');
            $pdf->Ln($cell_height);
        
            $pdf->SetFont('Arial','B', 10);
            $pdf->Cell(130, $cell_height, 'Grand Total', 1, 0, 'R');
            $pdf->Cell(20, $cell_height, number_format($exp_page_total, 2), 1, 0, 'R');
            $pdf->Cell(20, $cell_height, number_format($relsd_page_total, 2), 1, 0, 'R');
            $pdf->Cell(20, $cell_height, number_format($short_page_total, 2), 1, 0, 'R');
            $pdf->Ln(20);
        
            $employee = selectOne($users, ['employee_no' => $user_no]); // ----------------------- PDF Signature
            $pdf->SetFont('Arial','B', 10);
            $pdf->Cell(50, $cell_height, 'Released by', 0, 0, 'L');
            $pdf->Cell(50, $cell_height, 'Date Released', 0, 0, 'L');
            $pdf->Cell(50, $cell_height, 'Confirmed by', 0, 0, 'L');
            $pdf->Cell(50, $cell_height, 'Tallied by', 0, 0, 'L');
            $pdf->Ln(10);
            
            $pdf->SetFont('Arial','', 10);
            $pdf->Cell(50, $cell_height, $employee['firstname'] . " " . (!empty($employee['middlename']) ? substr($employee['middlename'], 0, 1) . ". " : "") . $employee['lastname'], 0, 0, 'L');
            $pdf->Ln(1);
            $pdf->SetFont('Arial','B', 10);
            $pdf->Cell(50, $cell_height, '___________________', 0, 0, 'L');
            $pdf->Cell(50, $cell_height, '___________________', 0, 0, 'L');
            $pdf->Cell(50, $cell_height, '___________________', 0, 0, 'L');
            $pdf->Cell(50, $cell_height, '___________________', 0, 0, 'L');
            $pdf->Ln($cell_height);
        
        }
        $pdf->Output();
    }
?>
