<?php
session_start();
date_default_timezone_set('Asia/Manila');
include('connect_mms.php');
include('connect.php');
require_once('../fpdf184/createPDF.php');

// echo 56 % 20;
// exit;
// $order_date = '220629';
// $date = date('Y-m-d');
// $days = 4;
// echo $date . '<br>';
// echo date('m/d/Y', strtotime($date. ' + 2 days')) . '<br>';
// echo date('m/d/Y', strtotime(date('Y-m-d'). ' + '.$days.' days')) . '<br>';
// echo date_format(date_create($order_date), 'm/d/Y') . '<br>';
// echo date(strtotime('m/d/Y', $order_date)) . '<br>';
// exit;

// $Ponumb = 500008682;
// $user_id    = 2023;
// $store_code = 140;
// $refno  = 4;

// $query_odbc  = 'CALL '.$db_name.'.po_data(\''.$Ponumb.'\')';
// $odbc_result = odbc_exec($conn_m, $query_odbc);
// $data['strnam'] = odbc_result($odbc_result, 'strnam');
// echo $data['strnam'] . '<br>';

// exit;

if(isset($_REQUEST['printReceiver'])){
    $user_id        = $_SESSION['user_id'];
    $store_code     = $_SESSION['Storecode'];
    $download       = $_REQUEST['download'] == 'true';
    $printer_server = $download ? '' : $_SESSION['printer_ip'];
    //$fullname = $_SESSION['full_name'];
	$fullname = $_SESSION['full_name'];
    // $printer_server = '10.1.1.2';

    // CHECK IF PRINTER IP IS EMPTY
    if (!$download && empty($printer_server)) {
        echo '<script>alert("Please set Printer IP Address");</script>';
        echo '<script>parent.document.getElementById("information").innerHTML="No Printer IP Address found"</script>';
        exit;
    }

    //GET LIST
    $query_list = "SELECT P.* FROM polist AS P INNER JOIN (SELECT MAX(CAST(refno AS int)) AS maxref FROM polist WHERE user_id = '$user_id' AND store_code = '$store_code') AS P2 ON P.refno = P2.maxref";
	
    $list = $conn->query($query_list);

    if($list->rowCount() == 0){
        echo '<script>alert("Nothing to print");</script>';
        echo '<script>parent.document.getElementById("information").innerHTML="No data found"</script>';
        exit;
    }

    $pl_ct = 1;
    $show_result = $download ? '<br>Downloaded:<br>' : '<br>Printed:<br>';
    $file_names = array();

    while($row = $list->fetch(PDO::FETCH_ASSOC)){
        $Ponumb = $row['Ponumb'];
        $refno  = $row['refno'];
        $data   = array();


        // GET DATA FROM MySQL
        $query_data = 'SELECT Inumber, iupc, idescr, Pomqty, istdpk, Expqty, rcvqty, postor, povnum, Expday, expiredate
                         FROM polist AS p
                         LEFT JOIN po_transfers t
                           ON t.Ponumb = p.Ponumb
                          AND t.refno = p.refno
                        WHERE 1 = 1
                          AND p.Ponumb = '.$Ponumb.'
                          AND p.user_id = '.$user_id.'
                          AND p.store_code = '.$store_code.'
                          AND p.refno = '.$refno.'
                        GROUP BY Inumber, iupc
                        ORDER BY Inumber, iupc
        ';

        $stmt = $conn->prepare($query_data);
        $stmt->execute();
        $mysql_result = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        $data['details'] = $mysql_result;

        // CHECK IF NO DATA FOUND
        if ($stmt->rowCount() == 0) continue;
        
        // GET DATA FROM MMS
        $query_odbc  = 'CALL '.$db_name.'.po_data(\''.$Ponumb.'\')';
        $odbc_result = odbc_exec($conn_m, $query_odbc);
        $data['strnam'] = odbc_result($odbc_result, 'strnam');
        $data['Ponumb'] = $Ponumb;
        $data['pomrcv'] = odbc_result($odbc_result, 'pomrcv');
        $data['asname'] = odbc_result($odbc_result, 'asname');
        $data['pobuyr'] = odbc_result($odbc_result, 'pobuyr');
        $data['byrnam'] = odbc_result($odbc_result, 'byrnam');
        $data['poedat'] = odbc_result($odbc_result, 'poedat');
        $data['strhdo'] = odbc_result($odbc_result, 'strhdo');
        $data['postor'] = $mysql_result[0]['postor'];
        $data['povnum'] = $mysql_result[0]['povnum'];

        $file_name  = 'r_' . $Ponumb .'_'. $refno.'.pdf';
        $file = '../PDF/' . $file_name;

        $str_upload = array();
        $str = '';
        
        // CONSTRUCT TO STRING TO UPLOAD TO MMS
        foreach ($data['details'] as $key => $value) {
            if ($value['rcvqty'] != 0) {
                $rcvqty = $data['strhdo'] == 'W' ? (double) $value['rcvqty'] : (double) $value['rcvqty'] * (double) $value['istdpk'];
    
                // $str = Ponumb (10) + rcr no (10) + sku (9) + rcvqty (0) + expiredate (8)
                $str .= ''
                    . str_pad(preg_replace('/[^0-9]/' ,'' ,$Ponumb)                     ,10 ,'0' ,STR_PAD_LEFT)
                    . str_pad(preg_replace('/[^0-9]/' ,'' ,$data['pomrcv'])             ,10 ,'0' ,STR_PAD_LEFT)
                    . str_pad(preg_replace('/[^0-9]/' ,'' ,$value['Inumber'])           ,9  ,'0' ,STR_PAD_LEFT)
                    . str_pad(preg_replace('/[^0-9]/' ,'' ,number_format($rcvqty, 2))   ,9  ,'0' ,STR_PAD_LEFT)
                    . str_pad(preg_replace('/[^0-9]/' ,'' ,$value['expiredate'])        ,8  ,'0' ,STR_PAD_LEFT)
                ;
            }

            if (strlen($str) == 920) {
                array_push($str_upload, $str);
                $str = '';
            }
            if ($key == sizeof($data['details']) - 1) array_push($str_upload, str_pad($str, 920, '0', STR_PAD_RIGHT));
            $str2 = $key;
        }
        
        // UPLOAD TO MMS
        foreach ($str_upload as $value) {
            $query_odbc_up  = 'CALL '.$db_name.'.pdtposp3(\''.$value.'\')';
            $odbc_up_result = odbc_exec($conn_m, $query_odbc_up);
        }

        // GENERATE PDF
       $generate = generate_receiver($data, $file,$fullname);

        // PRINT / DOWNLOAD PDF
        if ($download) array_push($file_names, $file_name);
        else printPDF($file, $printer_server);
        
        
        // SHOW PROGRESS BAR
        $show_result .= $Ponumb.'<br>';
        // $percent = intval($pl_ct / ($list->rowCount()) * 100)."%";
        $percent = number_format((float)(($pl_ct * 100) / $list->rowCount()), 2, '.', '').'%';
        $pl_ct++;
        echo '<script>parent.document.getElementById("progressbar").innerHTML="<div style=\"width:'.$percent.';background:linear-gradient(to bottom, rgba(125,126,125,1) 0%,rgba(14,14,14,1) 100%); ;height:35px;\">&nbsp;</div>";</script>';
        ob_flush(); 
        flush();
    }

    // DOWNLOAD FILE
    $createdFile = "";
    if ($download) $createdFile = mergePDF($file_names, $user_id, $store_code);
    $message = $download ? 'Download' : 'Printing';

    //echo '<script>parent.show_result("'.$show_result.'");</script>';
    echo '<script>parent.document.getElementById("information").innerHTML="<div style=\"text-align:center; font-weight:bold\">'. $message .' Complete!</div>";
    </script>';
    if (!empty($createdFile)) echo "<script type='text/javascript'>window.open('../fpdf184/downloadFile.php?file=". $createdFile ."', '_self')</script>";
}
?>