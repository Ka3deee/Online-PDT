<?php
session_start();
include('../path.php');
include(ROOT_PATH . "/databases/connect_mms.php");
include(ROOT_PATH . "/databases/connect_mysql.php");
include(ROOT_PATH . '/databases/functions.php');
ini_set('max_execution_time', 0);

$audit_log = "audit_log";
$mms_pick = "mms_pick";
$pickupc = "pickupc";
$sp_pick1_temp = "sp_pick1_temp";
$sp_pick_temp = "sp_pick_temp";
$user_no = $_SESSION['employee_no'];   
     
if (isset($_REQUEST['download'])) {
    unset($_REQUEST['download']);

    $trfout_data = $_REQUEST['trfout_data'];
    $lines = explode("\n", trim($trfout_data));
    $store_num;
    $doc_num;

    $sp_pick_clear = "TRUNCATE TABLE $sp_pick_temp";
    $conn->query($sp_pick_clear);

    $sp_pick_clear1 = "TRUNCATE TABLE $sp_pick1_temp";
    $conn->query($sp_pick_clear1);

    $sql_batchno = "SELECT MAX(batch_no) as max_batch FROM $audit_log";
    $result = $conn->query($sql_batchno);
    $row = $result->fetch_assoc();
    if ($row['max_batch'] == null) {
        $batch_no = 1;
    } else {
        $batch_no = $row['max_batch'] + 1;
    }

    $total = count($lines);
    for ($i = 0; $i < $total; $i++) {
            
        $line = $lines[$i]; // Get the current line

        if (!empty($line)) {
            
            $numbers = explode(",", $line);
            $store_num = $numbers[0];
            $doc_num = $numbers[1];
            
            try {
                $initial = 0;
                
                $pickQry = "call ".$lib_name.".sp_pick($doc_num, $store_num)";
                $result = odbc_exec($conn_m, $pickQry);

                $pickQry1 = "call ".$lib_name.".sp_pick1($doc_num, $store_num)";
                $result1 = odbc_exec($conn_m, $pickQry1);
                if ($result1) {
                    while ($row = odbc_fetch_array($result1)) {
                        create($sp_pick1_temp, $row); 
                    }
                }

                if ($result1) {
                    $sp_pick_rows = selectAllPick($sp_pick1_temp);
                    if ($sp_pick_rows) {
                        foreach ($sp_pick_rows as $row) {
                            $data = [
                                'whmove' => $row['whmove'],
                                'inumbr' => $row['inumbr'],
                                'whmumr' => $row['whmumr'],
                                'whmvqt' => $row['whmvqt'],
                                'whmvqr' => $row['whmvqr'],
                                'iupc' => $row['iupc'],
                                'idescr' => $row['idescr'],
                                'strnum' => $row['strnum'],
                                'istdpk' => $row['istdpk'],
                                'ivndpn' => $row['ivndpn'],
                                'whmvsq' => $row['whmvsq'],
                                'whmfsl' => $row['whmfsl'],
                                'expqty' => number_format(intval($row['whmvqr']) / intval($row['istdpk']), 2),
                                'rcvqty' => 000000000.00,
                                'rexpday' => 000,
                            ];
    
                            $data1 = [
                                'whmove' => $row['whmove'],
                                'inumbr' => $row['inumbr'],
                                'strnum' => $row['strnum'],
                            ];
                            
                            $mms_row = selectAll($mms_pick, $data1);
                            if (count($mms_row) == 0) {
                                create($mms_pick, $data); // ---------------------------------------------- insert all data to mms_pick table
                            }
                            $get_id = selectOne($mms_pick, array_merge($data1, ['iupc' => $row['iupc']]));
                            $mms_pick_id = $get_id['id'];
                            $data2 = [
                                'mms_pick_id' => $mms_pick_id,
                                'batch_no' => $batch_no,
                                'is_scanned' => $initial,
                                'is_gen_report' => $initial,
                                'is_mms_uploaded' => $initial,
                                'user_ee_no' => $user_no,
                            ];
                            create($audit_log, $data2); // -------------------------------------------- insert audit_log table
                        }
                    }
                }
                
                if ($result) {
                    while ($row = odbc_fetch_array($result)) {
                        $inumbr = $row['INUMBR'];
                        $iupc = $row['IUPC'];
                        $conditions = [
                            'inumbr' => $inumbr,
                            'iupc' => $iupc,
                        ];
                        create($sp_pick_temp, $row); // ------------------------------------------ insert all data to sp_pick_temp table
                        $prim_upc_res = selectAll($pickupc, $conditions);
                        if (count($prim_upc_res) == 0) {
                            create($pickupc, $conditions); // ------------------------------------ insert data to pickupc table
                        }

                        $getprimupc = "call " . $lib_name . ".getprimupc($inumbr)"; // ----------- get primary upc
                        $prim_upc_result = odbc_exec($conn_m, $getprimupc);
                        if ($prim_upc_result) {
                            $prim_upc = odbc_result($prim_upc_result, 'IUPC'); 
                            $conditions = [
                                'inumbr' => $inumbr,
                                'iupc' => $prim_upc,
                            ];
                            $prim_upc_res = selectAll($pickupc, $conditions);
                            if (count($prim_upc_res) == 0) {
                                create($pickupc, $conditions); // ------------------------------- insert data to pickupc table
                            }
                            
                            $get_mms_pick = selectAll($mms_pick, ['inumbr' => $inumbr]);
                            foreach ($get_mms_pick as $row) {
                                $id = $row['id'];
                                $data3 = ['iupc' => $prim_upc];
                                update($mms_pick, $id, $data3);

                                $mmd_pick_inumbr = $row['inumbr'];
                                $sp_lccexp1 = "call ". $lib_name . ".sp_lccexp1($mmd_pick_inumbr)"; // -------- get expday
                                $rexpday_res = odbc_exec($conn_m, $sp_lccexp1);
                                if ($rexpday_res) {
                                    $rexpday = odbc_result($rexpday_res, 'LCEXPS');
                                    if ($rexpday) {
                                        update($mms_pick, $id, ['rexpday' => $rexpday]);
                                    }
                                }
                            }
                        }
                        
                        $getparentupc = "call ". $lib_name .".getparentupc($inumbr)"; // -------- get parent sku upc
                        $parent_upc_result = odbc_exec($conn_m, $getparentupc);  
                        if ($parent_upc_result) {
                            $parent_upc = odbc_result($parent_upc_result, 'IUPC');
                            if ($parent_upc) {
                                $conditions = [
                                    'inumbr' => $inumbr,
                                    'iupc' => $parent_upc,
                                ];
                                $parent_upc_res = selectAll($pickupc, $conditions);
                                if (count($parent_upc_res) == 0) {
                                    create($pickupc, $conditions);
                                }
                            }
                        }
                    }
                }
                
            } catch (Exception $e) {
                echo 'Caught exception: ',  $e->getMessage(), "\n";
            }
            odbc_free_result($result);
            odbc_free_result($result1);
        }
        
        $trf = $i + 1;
        $percent = intval($trf/$total * 100)."%";
        echo '
            <script>
                parent.document.getElementById("progress-bar").style.width="'.$percent.'";
                setTimeout(function() {
                    parent.document.getElementById("percent-value").innerText="'.$percent.'";
                }, 1000);
            </script>';

        ob_flush(); 
        flush(); 
    }
    
    echo '
        <script>
            setTimeout(function() {
                parent.document.getElementById("loader-download").style.display="none";
                alert("Download Complete! Click \"OK\" to continue.");
            }, 1000);
        </script>';
}

if (isset($_REQUEST['batch'])) {
    $batch_no = $_REQUEST['batch'];

    $audit_data = selectAll($audit_log, ['batch_no' => $batch_no]);
    $pick_id = array();
    foreach ($audit_data as $id) {
        $pick_id[] = $id['mms_pick_id'];
    }
    
    $mms_id = selectAllId($mms_pick, $pick_id);

    $grouped_whmove = array_count_values(array_map(function ($item) { // Grouping by 'whmove' and 'strnum' and counting occurrences
        return $item['whmove'] . '-' . $item['strnum'];
    }, $mms_id));
    
    $batch_no = array();
    foreach ($grouped_whmove as $key => $count) {
        list($whmove, $strnum) = explode('-', $key);
        $batch_no[] = $strnum . "," . $whmove;
    }
    
    echo implode("\n", $batch_no);
} // --------------------- send output result to textarea as list

if (isset($_REQUEST['clear_store'])) {
    unset($_SESSION['store-code']);
}

?>
