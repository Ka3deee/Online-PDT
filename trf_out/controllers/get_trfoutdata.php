<?php
session_start();
include('../path.php');
include(ROOT_PATH . "/databases/connect_mms.php");
include(ROOT_PATH . "/databases/connect_mysql.php");
$user_no = $_SESSION['employee_no'];        

if (isset($_POST['download'])) {
    unset($_POST['download']);

    $trfout_data = $_POST['trfout_data'];
    $lines = explode("\n", $trfout_data);
    $store_num;
    $doc_num;

    $sp_pick_clear = "TRUNCATE TABLE sp_pick_temp";
    $conn->query($sp_pick_clear);

    $sp_pick_clear1 = "TRUNCATE TABLE sp_pick1_temp";
    $conn->query($sp_pick_clear1);

    for ($i = 0; $i < count($lines); $i++) {
            
        $line = $lines[$i]; // Get the current line

        if (!empty($line) || $line != " " || $line != "") {
            
            $numbers = explode(",", $line);
            $store_num = $numbers[0];
            $doc_num = $numbers[1];
            
            try {

                $sp_pick = "call ".$lib_name.".sp_pick($doc_num, $store_num)";
                $result = odbc_exec($conn_m, $sp_pick);
                
                $sp_pick1 = "call ".$lib_name.".sp_pick1($doc_num, $store_num)";
                $result1 = odbc_exec($conn_m, $sp_pick1);
            
                $rcvqty = 000000000.00;
                $rexpday;

                if ($result) {
                    
                    $has_whmove = odbc_result($result, 'WHMOVE');
                    $has_inumbr = odbc_result($result, 'INUMBR');
                    $has_strnum = odbc_result($result, 'STRNUM');

                    if ($has_whmove != null && $has_inumbr != null && $has_strnum != null) {
                        try {

                            //* sp_pick_temp table
                            $whmove = odbc_result($result, 'WHMOVE'); 
                            $inumbr = odbc_result($result, 'INUMBR');
                            $whmumr = odbc_result($result, 'WHMUMR');
                            $whmvqt = odbc_result($result, 'WHMVQT');
                            $whmvqr = odbc_result($result, 'WHMVQR');
                            $iupc = odbc_result($result, 'IUPC');
                            $idescr = odbc_result($result, 'IDESCR');
                            $strnum = odbc_result($result, 'STRNUM');
                            $istdpk = odbc_result($result, 'ISTDPK');
                            $ivndpn = odbc_result($result, 'IVNDPN');
                            $whmvsq = odbc_result($result, 'WHMVSQ');
                            $whmfsl = odbc_result($result, 'WHMFSL');

                            //* sp_pick1_temp table
                            $whmove1 = odbc_result($result1, 'WHMOVE'); 
                            $inumbr1 = odbc_result($result1, 'INUMBR');
                            $whmumr1 = odbc_result($result1, 'WHMUMR');
                            $whmvqt1 = odbc_result($result1, 'WHMVQT');
                            $whmvqr1 = odbc_result($result1, 'WHMVQR');
                            $iupc1 = odbc_result($result1, 'IUPC');
                            $idescr1 = odbc_result($result1, 'IDESCR');
                            $strnum1 = odbc_result($result1, 'STRNUM');
                            $istdpk1 = odbc_result($result1, 'ISTDPK');
                            $ivndpn1 = odbc_result($result1, 'IVNDPN');
                            $whmvsq1 = odbc_result($result1, 'WHMVSQ');
                            $whmfsl1 = odbc_result($result1, 'WHMFSL');

                            $get_expday = "call ". $lib_name .".sp_lccexp1($inumbr)";
                            $expday_res = odbc_exec($conn_m, $get_expday);
                            if ($expday_res !== false && odbc_num_rows($expday_res) > 0) {
                                $rexpday = odbc_result($expday_res, 'LCEXPR');
                            } else {
                                $rexpday = '0';
                            }  

                            $conn->begin_transaction();
                        
                            $sql_sp_pick = "INSERT INTO sp_pick_temp (whmove, inumbr, whmumr, whmvqt, whmvqr, iupc, idescr, strnum, istdpk, ivndpn, whmvsq, whmfsl) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
                            $stmt = $conn->prepare($sql_sp_pick);
                            $stmt->bind_param("ssssssssssss", $whmove, $inumbr, $whmumr, $whmvqt, $whmvqr, $iupc, $idescr, $strnum, $istdpk, $ivndpn, $whmvsq, $whmfsl);
                            
                            $sql_sp_pick1 = "INSERT INTO sp_pick1_temp (whmove, inumbr, whmumr, whmvqt, whmvqr, iupc, idescr, strnum, istdpk, ivndpn, whmvsq, whmfsl) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
                            $stmt1 = $conn->prepare($sql_sp_pick1);
                            $stmt1->bind_param("ssssssssssss", $whmove1, $inumbr1, $whmumr1, $whmvqt1, $whmvqr1, $iupc1, $idescr1, $strnum1, $istdpk1, $ivndpn1, $whmvsq1, $whmfsl1);
                        
                            $expqty = round($whmvqr/$istdpk,2);
                            $sql_mms_pick = "INSERT INTO mms_pick (whmove, inumbr, whmumr, whmvqt, whmvqr, iupc, idescr, strnum, istdpk, ivndpn, whmvsq, whmfsl, rcvqty, expqty, rexpday) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
                            $stmt2 = $conn->prepare($sql_mms_pick);
                            $stmt2->bind_param("sssssssssssssss", $whmove, $inumbr, $whmumr, $whmvqt, $whmvqr, $iupc, $idescr, $strnum, $istdpk, $ivndpn, $whmvsq, $whmfsl, $rcvqty, $expqty, $rexpday);
                            $stmt2->execute();

                            /*
                            $sql_check_duplicate = "SELECT whmove FROM mms_pick WHERE whmove = ? AND strnum = ?";
                            $stmt_check = $conn->prepare($sql_check_duplicate);
                            $stmt_check->bind_param("s", $whmove, $strnum);
                            $stmt_check->execute();
                            $stmt_check->store_result();

                            if ($stmt_check->num_rows > 0) {
                                // Duplicates found
                                $stmt_check->bind_result($duplicate_whmove);
                                echo "Duplicates found for whmove: " . $whmove . "<br>";
                                while ($stmt_check->fetch()) {
                                    echo "Duplicate whmove: " . $duplicate_whmove . "<br>";
                                }
                                // Handle duplicates as needed
                            } else {
                                // No duplicates found, proceed with the INSERT operation
                                $stmt_check->close();

                                // Your INSERT code here
                                $sql_mms_pick = "INSERT INTO mms_pick (whmove, inumbr, whmumr, whmvqt, whmvqr, iupc, idescr, strnum, istdpk, ivndpn, whmvsq, whmfsl, rcvqty, expqty, rexpday) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
                                $stmt2 = $conn->prepare($sql_mms_pick);
                                $stmt2->bind_param("sssssssssssssss", $whmove, $inumbr, $whmumr, $whmvqt, $whmvqr, $iupc, $idescr, $strnum, $istdpk, $ivndpn, $whmvsq, $whmfsl, $rcvqty, $expqty, $rexpday);
                                $stmt2->execute();
                                // Handle the INSERT operation result
                            }
*/
                            $sql_pickupc = "INSERT INTO pickupc (inumbr, iupc) VALUES (?, ?) ON DUPLICATE KEY UPDATE inumbr=$inumbr";
                            $stmt3 = $conn->prepare($sql_pickupc);
                            $stmt3->bind_param("ss", $inumbr, $iupc);
                            $stmt3->execute();

                            //* Get primary UPC 
                            $get_prim_upc = "call " . $lib_name . ".getprimupc($inumbr)";
                            $result2 = odbc_exec($conn_m, $get_prim_upc);
                            
                            if (odbc_num_rows($result2) > 0) {
                                $m_xctr = true;
                            
                                while ($row = odbc_fetch_array($result2)) {
                                    $iupc2 = $row['IUPC'];
                            
                                    $sql_fortest = "SELECT COUNT(iupc) FROM pickupc WHERE inumbr = ? AND iupc = ?";
                                    $stmt5 = $conn->prepare($sql_fortest);
                                    $stmt5->bind_param("ss", $inumbr, $iupc2);
                                    $stmt5->execute();
                                    $stmt5->bind_result($count1);
                                    $stmt5->fetch();
                            
                                    if ($count1 == 0) {
                                        $insert_prim_upc = "INSERT INTO pickupc (inumbr, iupc) VALUES (?, ?)";
                                        $stmt6 = $conn->prepare($insert_prim_upc);
                                        $stmt6->bind_param("ss", $inumbr, $iupc2);
                                        $stmt6->execute();
                                    }
                            
                                    if ($m_xctr) {
                                        $update_prim_upc = "UPDATE mmspick SET iupc = ? WHERE inumbr = ?";
                                        $stmt7 = $conn->prepare($update_prim_upc);
                                        $stmt7->bind_param("", $iupc2, $inumbr);
                                        $stmt7->execute();
                            
                                        $m_xctr = false;
                                    }
                                }
                            }

                            //* Get parent sku UPC
                            $get_parent_upc = "call ". $lib_name .".getparentupc($inumbr)";
                            $result3 = odbc_exec($conn_m, $get_parent_upc);

                            if (odbc_num_rows($result3) > 0) {                    
                                while ($row = odbc_fetch_array($result3)) {
                                    $iupc3 = $row['IUPC'];
                            
                                    $sql_fortest1 = "SELECT COUNT(iupc) FROM pickupc WHERE inumbr = ? AND iupc = ?";
                                    $stmt8 = $conn->prepare($fortestQuery);
                                    $stmt8->bind_param("ss", $inumbr, $iupc3);
                                    $stmt8->execute();
                                    $stmt8->bind_result($count2);
                                    $stmt8->fetch();
                            
                                    if ($count2 == 0) {
                                        $insert_parent_upc = "INSERT INTO pickupc (inumbr, iupc) VALUES (?, ?)";
                                        $stmt9 = $conn->prepare($insert_parent_upc);
                                        $stmt9->bind_param("ss", $inumbr, $iupc3);
                                        $stmt9->execute();
                                    }
                                }
                            } 

                            if ($stmt->execute() && $stmt1->execute()) {
                                $get_mms_pick_id = "SELECT MAX(id) FROM mms_pick";
                                $result2 = $conn->query($get_mms_pick_id);
                                $row = $result2->fetch_assoc();
                                $mm_pick_id = $row['MAX(id)'];
                                $initial = 0;
                                
                                $audit_log = "INSERT INTO audit_log (mms_pick_id, is_scanned, is_gen_report, is_mms_uploaded, user_ee_no) VALUES (?, ?, ?, ?, ?)";
                                $stmt10 = $conn->prepare($audit_log);
                                $stmt10->bind_param("sssss", $mm_pick_id, $initial, $initial, $initial, $user_no);
                                $stmt10->execute();
                                $conn->commit();
                                echo "Success";
                            }

                        } catch (Exception $e) {
                            $conn->rollback();
                            echo "Error: " . $e->getMessage();
                        }

                    } else {
                        echo "No data found on Store No : " . $store_num . " Document No : " . $doc_num;
                    }
                    
                    echo "<br />";
                    odbc_free_result($result);
                    odbc_free_result($result1);
                }
                
            } catch (Exception $e) {
                echo 'Caught exception: ',  $e->getMessage(), "\n";
            }
        }
    }
}

?>