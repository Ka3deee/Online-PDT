<?php
session_start();
date_default_timezone_set('Asia/Manila');
include("connect_mms.php");
include("connect.php");
ini_set('max_execution_time', 0); // to get unlimited php script execution time
$percent = '';
$mrndcode = date("Ymd").'_6DB0MDM35 '.(time() - strtotime("today"));



if(isset($_REQUEST["xstrings"])){

	echo 'Downloading Po <br>';
	$user_id = $_SESSION['user_id'];
	function addspace($noSpace){	
		$str = "";
		for($i = 0;$i<$noSpace;$i++){
			$str = $str."-";
		}
		return $str;
	}

	$polist = $_REQUEST["xstrings"];
	$po_array = explode(",",$polist);
	$spaceno = 0;
	$po_count = 0;
	$poliststr = "";
	//Count PO 
	echo "Total no. of PO: ".(count($po_array)-1). "<br>";
	for($i = 0; $i<count($po_array)-1;$i++){		
		$spaceno = 10 - (strlen(trim($po_array[$i])));
		$poliststr = $poliststr.str_replace('-', '0', addspace($spaceno).trim($po_array[$i]));
		$po_count++;
		if($po_count == 20){
			Try{
				$a = $poliststr;
				$b = $mrndcode;
				$stmt    = odbc_prepare($conn_m, 'CALL '.$db_name.'.getpx4a(?,?)');
				$success = odbc_execute($stmt, array($a, $b));
				//echo "First Procedure Done! <br>";			
				//replace counters to 0;
				$po_count = 0;
                $poliststr = "";

			}catch(Exception $exception){			
				echo $exception->getMessage();
			}
		 }		
	}
	//if less than 20 run this statement
	if($po_count <> 0){
		$loopx  = (20 - $po_count);
		for($i = 1;$i<=$loopx;$i++){
			$poliststr = $poliststr."0000000000";
		}
		Try{
				//execute mms command here 
				$a = $poliststr;
				$b = $mrndcode;
				$stmt    = odbc_prepare($conn_m, 'CALL '.$db_name.'.getpx4a(?,?)');
				$success = odbc_execute($stmt, array($a, $b));
				echo "First Procedure Done! <br>";			
				//replace counters to 0;
				$po_count = 0;
                $poliststr = "";
				
		}catch(Exception $exception){			
				echo $exception->getMessage();
		}
	}
	
	//GET PO DATA USING THE RNDCODE
	$porslt_cur = array();
	echo 'Query: CALL '.$db_name.'.getpx2("'.$mrndcode.'") <br>';
	echo "Result: <br>";
	$odbc_statement = "CALL ".$db_name.".getpx2('".$mrndcode."')";
	$result = odbc_exec($conn_m,$odbc_statement);
	while(odbc_fetch_row($result)){
		echo odbc_result($result, "xresult")."<br>";
		array_push( $porslt_cur, odbc_result($result, "xresult") );
	}
	//count all extracted data from download PO
	$sum = 0;
	for($i = 0;$i<count($porslt_cur);$i++){
		$sum = $sum + (int)(substr($porslt_cur[$i],13,7));
		
		//echo $porslt_cur[$i]."<br>";
	}
	echo "Sum: ".$sum."<br>";
	
	if ($sum > 0){
		//generate refno
		$Get_query1 = "call getmaxref";
		$Ref_result = $conn->query($Get_query1);
		$ref_row1 = $Ref_result->fetch(PDO::FETCH_ASSOC);
		$refno = ((int)$ref_row1["topref"]+1);
		$Ref_result->closeCursor();
		echo 'Your Ref No. IS: '.$refno.' <br>';
		//set refNo to session
		$_SESSION['refno'] = $refno;
		//get store code
		$store_code= $_SESSION['Storecode'];
		//after getting ref no increment the rows
		$date_now = date("Y-m-d");
		//$insert_to_po_ref = "INSERT INTO `tblpo_ref`(`receiveRef`, `DateDL`, `SetUp`) VALUES ('$refno','$date_now','none')";
		$insert_to_po_ref = "CALL add_ref('$refno','$date_now')";		
		$conn->exec($insert_to_po_ref);		
		
		//GET PO DATA USING THE RNDCODE
		
		echo 'Query: CALL '.$db_name.'.getpx("'.$mrndcode.'") <br>';
		$odbc_potransfers = "CALL ".$db_name.".getpx('".$mrndcode."')";
		$result1 = odbc_exec($conn_m,$odbc_potransfers);
	
		//Store fetch data into array
		$ptd = array();
		$ptd_ct = 0;
		while(odbc_fetch_row($result1)){
			
			$ptd[$ptd_ct][0] = odbc_result($result1, "ponumb");
			$ptd[$ptd_ct][1] = odbc_result($result1, "inumbr");
			$ptd[$ptd_ct][2] = odbc_result($result1, "pomum");
			$ptd[$ptd_ct][3] = odbc_result($result1, "pompk");
			$ptd[$ptd_ct][4] = odbc_result($result1, "pomqty");
			$ptd[$ptd_ct][5] = odbc_result($result1, "idescr");
			$ptd[$ptd_ct][6] = odbc_result($result1, "postor");
			$ptd[$ptd_ct][7] = odbc_result($result1, "povnum");
			$ptd[$ptd_ct][8] = odbc_result($result1, "istdpk");
			$ptd[$ptd_ct][9] = odbc_result($result1, "ivndpn");
			$ptd[$ptd_ct][10] = odbc_result($result1, "expqty");
			$ptd[$ptd_ct][11] = odbc_result($result1, "expday");
			$ptd[$ptd_ct][12] = odbc_result($result1, "iupc");
			
			$ptd_ct++;
		}
		echo 'Total no. of Transfers Downloaded'.$ptd_ct.' <br>';

		//insert to local server
		for($i = 0; $i<count($ptd); $i++){
			if (count($ptd) > 1) {
				$percent = intval($i/(count($ptd)- 1) * 100)."%"; 
			} else {
				$percent = intval(100);
			} 
			Try{
		  //$insert_to_transfers = "INSERT INTO `po_transfers`(`ref_rcv`, `Po`, `Ponumb`, `Inumber`, `Pomum`, `pompk`, `Pomqty`, `idescr`, `Postor`, `Povnum`, `istpdk`, `ivndpn`, `Expqty`, `Expday`, `iupc`, `rcvqty`, `rcvqty_var`, `expiredate`) VALUES ('".$refno."','PO','".$ptd[$i][0]."','".$ptd[$i][1]."','".$ptd[$i][2]."','".$ptd[$i][3]."','".$ptd[$i][4]."','".$ptd[$i][5]."','".$ptd[$i][6]."','".$ptd[$i][7]."','".$ptd[$i][8]."','".$ptd[$i][9]."','".number_format($ptd[$i][10],2)."','".$ptd[$i][11]."','".$ptd[$i][12]."',0,0,0)";			 
				$conn->beginTransaction();			
				$insert_to_transfers = "CALL addtoPoTransfer('".$refno."','PO','".$ptd[$i][0]."','".$ptd[$i][1]."','".$ptd[$i][2]."','".$ptd[$i][3]."','".$ptd[$i][4]."','".str_replace("'", '/',$ptd[$i][5])."','".$ptd[$i][6]."','".$ptd[$i][7]."','".$ptd[$i][8]."','".Trim($ptd[$i][9])."','".number_format($ptd[$i][10],2)."','".$ptd[$i][11]."','".$ptd[$i][12]."')";			 
					
				$conn->exec($insert_to_transfers);
				$conn->commit();
				//update progress bar
				echo '<script>
				parent.document.getElementById("progressbar").innerHTML="<div style=\"width:'.$percent.';background:linear-gradient(to bottom, #0f9cfa 0%,#0f9cfa 100%);height:35px;\">&nbsp;</div>";
				parent.document.getElementById("information").innerHTML="<div style=\"text-align:center; font-weight:bold\">Uploading Downloaded MMS data to Server.</div>";</script>';
			}catch(PDOException $exception){
				$conn->rollBack();            
				echo $exception->getMessage();
			}
			ob_flush(); 
  			  flush(); 
		}
		///------------Add to Polist--------------------
		echo 'Query: insert into PO list';
		Try{
			$newpo_list = array();
			//get all processed PO
			$Get_po = "call getProcessedpo(".$refno.")";
			$po_result = $conn->query($Get_po);
			while($po_row1 = $po_result->fetch(PDO::FETCH_ASSOC)){
				array_push($newpo_list,$po_row1['Ponumb']);
			}
			$po_result->closeCursor();
			
			echo '<script>
					parent.document.getElementById("progressbar").innerHTML="<div style=\"width:0%;background:linear-gradient(to bottom, #0f9cfa 0%,#0f9cfa 100%); ;height:35px;\">&nbsp;</div>";
					parent.document.getElementById("information").innerHTML="<div style=\"text-align:center; font-weight:bold\">Uploading PO list  data to Server.</div>";</script>';
			$conn->beginTransaction();
			//insert all Po to po list
			for($i = 0;$i<count($newpo_list);$i++){
				$Ponumb = trim($newpo_list[$i]);
				$insert_to_polist = "CALL addtoPoList('$Ponumb','$refno','$user_id','$store_code')";
				$conn->exec($insert_to_polist);
			}
			
			$conn->commit();
		}catch(PDOException $exception){
			$conn->rollBack();            
		echo $exception->getMessage();
		}	

		// insert mms data to local server iupc
		echo 'Query: CALL '.$db_name.'.getpx("'.$mrndcode.'") <br>';
		$odbc_poiupc = "CALL ".$db_name.".getpx1('".$mrndcode."')";
		$result = odbc_exec($conn_m,$odbc_poiupc);
		//get total no of  PO transfers
		$totalrows_upc = odbc_num_rows($result);
		echo 'Total no. of UPC Downloaded'.$totalrows_upc.' <br>';
		while(odbc_fetch_row($result)){			
			Try{					
				$insert_to_iupc = "INSERT INTO `tblupc`(`refno`, `inumbr`, `iupc`) VALUES ('".$refno."','".odbc_result($result, "inumbr")."','".odbc_result($result, "iupc")."')";
				$conn->beginTransaction();					
				$conn->exec($insert_to_iupc);
				$conn->commit();
			}catch(PDOException $exception){
				$conn->rollBack();            
			echo $exception->getMessage();
			}
			
		}
	}
	//show result to user
	$show_result = "";
	for($i = 0;$i<count($porslt_cur);$i++){
		$show_result = $show_result.$porslt_cur[$i]."<br>"; 
	}
	echo '<script> parent.show_result("'.$show_result.'");</script>';
	///get result
	echo 'Query: CALL '.$db_name.'.getpx3("'.$mrndcode.'") <br> DOne!';
	$odbc_poiupc = "CALL ".$db_name.".getpx3('".$mrndcode."')";
	$result = odbc_exec($conn_m,$odbc_poiupc);
	
	//show download is done
	echo '<script>
	parent.document.getElementById("progressbar").innerHTML="<div style=\"width:100%;background:linear-gradient(to bottom, #0f9cfa 0%,#0f9cfa 100%); ;height:35px;\">&nbsp;</div>";
	parent.document.getElementById("information").innerHTML="<div style=\"text-align:center; font-weight:bold\">Download Complete!.</div>";</script>';	

}

if(isset($_REQUEST["retrivedata"])){
	$recref;
	$user_id = $_SESSION['user_id'];
	$store_code = $_SESSION['Storecode'];
	$query_list = "SELECT P.* FROM polist as P INNER JOIN (SELECT MAX(CAST(refno AS int)) as maxref FROM polist where user_id = '$user_id' and store_code = '$store_code') as P2 ON P.refno = P2.maxref";
	
	$result1 = $conn->query($query_list);
	if($result1->rowCount() > 0){
		$rows12 = $result1->rowCount();
		$pl_ct = 1;
		$show_result = "";
		while($rows1 = $result1->fetch(PDO::FETCH_ASSOC)){
			$recref = $rows1["refno"];
			$percent = intval($pl_ct/($result1->rowCount()) * 100)."%";  
			$show_result = $show_result.$rows1["Ponumb"]."<br>";
			echo '<script>
						parent.document.getElementById("progressbar").innerHTML="<div  style=\"width:'.$percent.';background:linear-gradient(to bottom, #0f9cfa 0%,#4993c4 100%); ;height:35px;\">&nbsp;</div>";</script>';
			ob_flush(); 
			flush();
			$pl_ct ++;		
		}
		$_SESSION['refno']  = $recref;
		echo '<script> parent.show_result("'.$show_result.'");
				parent.document.getElementById("information").innerHTML="<div style=\"text-align:center; font-weight:bold\">Retrieve Complete!.</div>";
				</script>';
		
	}else{
		echo '<script>
				parent.document.getElementById("information").innerHTML="<div style=\"text-align:center; font-weight:bold\">Retrieve Complete!.</div>";
				parent.document.getElementById("progressbar").innerHTML="<div style=\"width:100%;background:linear-gradient(to bottom, #0f9cfa 0%,#4993c4 100%); ;height:35px;\">&nbsp;</div>";
				</script>';
		echo '<script> alert("Unable to retrieve Data");</script>';
	}	
}

if(isset($_REQUEST["get_to_print"])){
    $store_code = $_SESSION['Storecode'];
	$user_id = $_SESSION['user_id'];
    //GET LIST
    // $query_list = "SELECT * FROM polist WHERE store_code = '$store_code' AND isGen_ra = 1 AND is_printed = 0";
    //$query_list = "SELECT * FROM polist WHERE store_code = '$store_code'";
	//$query_list = "SELECT P.* FROM polist as P INNER JOIN (SELECT Ponumb, MAX(RefRef) AS ref_no FROM polist GROUP BY Ponumb) as P2 ON P.Ponumb = P2.Ponumb AND P.user_id = '$user_id' and store_code = '$store_code'";
    $query_list = "SELECT P.* FROM polist as P INNER JOIN (SELECT  MAX(CAST(refno AS int)) as maxref FROM polist where user_id = '$user_id' and store_code = '$store_code') as P2 ON P.refno = P2.maxref";	
	$list       = $conn->query($query_list);

    if($list->rowCount() == 0){
        echo '<script>alert("Unable to retrieve data");</script>';
        echo '<script>parent.document.getElementById("information").innerHTML="No data found"</script>';
        exit;
    }

    $pl_ct = 1;
    $show_result = '';
    while($row = $list->fetch(PDO::FETCH_ASSOC)){
        $percent = intval($pl_ct / ($list->rowCount()) * 100)."%";  
        $pl_ct ++;

        $show_result .= $row["Ponumb"]."<br>";

        echo '<script>parent.document.getElementById("progressbar").innerHTML="<div style=\"width:'.$percent.';background:linear-gradient(to bottom, #4993c4 0%,#4993c4 100%); ;height:35px;\">&nbsp;</div>";</script>';
        ob_flush(); 
        flush(); 
    }
    
    echo '<script>parent.show_result("'.$show_result.'")</script>';
    echo '<script>parent.document.getElementById("information").innerHTML="<div style=\"text-align:center; font-weight:bold\">Retrieve Complete!</div>";
    </script>';
}


//session_destroy(); 