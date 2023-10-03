<?php
	//defince Sessions//
	session_start();

	$wms_user_type = $_SESSION['wms_user_type'];
	$wms_status_user = $_SESSION['wms_status_user'];
	$wms_status_pickid = $_SESSION['wms_status_pickid'];
	$wms_user_code = $_SESSION['wms_user_code'];
	$myArNew = $_SESSION['myArNew'];
	$lastname = $_SESSION['lastname'];
	$device_id =  $_SESSION['device_id'];
	$dss_ip =  $_SESSION['dss_ip'];
	
	include("../../db_connect.php");

	function getDeviceHostName(){
        $user_agent = $_SERVER['HTTP_USER_AGENT'];
        $str = preg_split('/\(/', $user_agent)[1];
        $str = preg_split('/\)/', $str)[0];
        $str = preg_split('/;/', $str)[2];
        return $str;
    }

	if(isset($_REQUEST['po'])){
		$po = $_REQUEST['po'];
		$db = new DatabaseClass($dss_ip);
		$sql = "select b.RA_vendor_name,a.po_rcr_ref,a.po_stat,a.PO_AR_Ref from po_sum_tbl a left join init_ra_tbl b on a.PO_num=b.RA_po_ref where a.po_num='$po'";
		if($db->getdata($sql) == 'No-Data'){
			echo "No-Data";
		}else{
			echo json_encode($db->getdata($sql));
		}
	}
	if(isset($_REQUEST['order_po'])){
		$po = $_REQUEST['order_po'];
		$db = new DatabaseClass($dss_ip);
		$sql = "select count(item_sku) as 'itemcount',IFNULL (sum(item_req_qty),0) as 'itemsum' from po_det_tbl where po_num='$po'";
		echo json_encode($db->getdata($sql));
	}
	if(isset($_REQUEST['rec_qty'])){
		$po = $_REQUEST['rec_qty'];
		$db = new DatabaseClass($dss_ip);
		$sql = "select count(item_rec_qty) as 'item_rec_qty',IFNULL(sum(item_rec_qty),0) as 'item_rec_sum' from po_det_tbl where item_rec_qty > 0 and po_num='$po'";
		echo json_encode($db->getdata($sql));
	}
	//upc fetch
	if(isset($_REQUEST['s_upc'])){
		$upc = $_REQUEST['s_upc'];
		$po_ref = $_REQUEST['ponum_ref'];
		$db = new DatabaseClass($dss_ip);
		$sql = "SELECT b.item_sku,b.item_desc,b.item_req_qty,b.item_rec_qty,b.item_sku_ret,b.item_check_tally,b.item_remarks,b.item_exp_ref,a.iupc,c.storeExp as exp FROM invupc a left join po_det_tbl b on a.inumbr=b.item_sku LEFT JOIN lccexp c on b.item_sku=c.inumbr WHERE  b.po_num='".$po_ref."' AND (b.item_sku='".$upc."' OR a.iupc='".$upc."')";
		if($db->getdata($sql) == 'No-Data'){
			echo "No-Data";	
		}else{
			echo json_encode($db->getdata($sql));
		}	
	}

	if(isset($_REQUEST['s_upc1'])){
		$upc = $_REQUEST['s_upc1'];
		$po_ref = $_REQUEST['ponum_ref1'];
		$db = new DatabaseClass($dss_ip);
		$sql = " SELECT b.item_sku,b.item_desc,b.item_req_qty,b.item_rec_qty, b.item_sku_ret,b.item_check_tally,b.item_remarks,b.item_exp_ref,a.upc,c.storeExp as exp,a.sku_parent,a.sku_child  FROM   sku_rel_tbl a left join po_det_tbl b on a.sku_child=b.item_sku or a.sku_parent=b.item_sku  LEFT JOIN lccexp c on a.sku_parent=c.inumbr or a.sku_child=c.inumbr WHERE  b.po_num='".$po_ref."' AND  (a.sku_parent='".$upc."' OR a.sku_child='".$upc."' OR  a.upc='".$upc."')";
		if($db->getdata($sql) == 'No-Data'){
			echo "No-Data";
		}else{
			echo json_encode($db->getdata($sql));
		}	
	}
	if(isset($_REQUEST['lot_needed'])){
		$sku = $_REQUEST['lot_needed'];
		$db = new DatabaseClass($dss_ip);
		$result = "No-Data";
		$sql = "select sku_code from sku_lot_tbl where sku_code='".$sku."'";
		if($db->getdata($sql) != 'No-Data'){
			$result=  "hasresult";
		}

		$sql = "select inumbr,storeExp from lccexp where inumbr='".$sku."'";
		if($db->getdata($sql) != 'No-Data'){
			$result =  "hasresult";
		}	
		echo $result;
	}
	if(isset($_REQUEST['checkexpire'])){
		$date = $_REQUEST['checkexpire'];
		$db = new DatabaseClass($dss_ip);
		$result = "exceeds";
		$date1=date_create($date);
		$date2=date_create(date("Y-m-d"));
		$diff =  date_diff($date1, $date2);
		echo (int)($diff->format("%a"));
		if((int)($diff->format("%a")) < 90){
			return "pass";
		}
	}

	if(isset($_REQUEST['uploadposum'])){
		$po_ref = $_REQUEST['uploadposum'];
		$db = new DatabaseClass($dss_ip);
		$sql = "update po_sum_tbl set po_stat=4,po_date_rec_bwh=now() where po_num='".$po_ref."'";
		if($db->_update_data($sql) == 0){
			echo "0";	
		}else{
			echo "1";
		}	
	}
	if(isset($_REQUEST['uploadar_sum'])){
		$usku_cnt = $_REQUEST['usku_cnt'];
		$utot_qty = $_REQUEST['utot_qty'];
		$upo_cnt = $_REQUEST['upo_cnt'];
		$uc_ar_ref = $_REQUEST['uc_ar_ref'];
		$db = new DatabaseClass($dss_ip);
		$sql = "update ar_sum_tbl set AR_DATE_END=now(),ar_tsku='".$usku_cnt."',ar_tqty='".$utot_qty ."',ar_tpo='".$upo_cnt."' where ar_ref='".$uc_ar_ref."'";
		if($db->_update_data($sql) == 0){
			echo "0";	
		}else{
			echo "1";
		}	
	}
	if(isset($_REQUEST['c_ar_ref'])){
		$c_ar_ref = $_REQUEST['c_ar_ref'];
		$db = new DatabaseClass($dss_ip);
		$sql = "SELECT COUNT( item_sku ) as 'citemsku',SUM( item_rec_qty ) as 'crec_qty' FROM po_sum_tbl a LEFT JOIN po_det_tbl b ON a.po_num = b.po_num WHERE po_ar_ref ='".$c_ar_ref."'";
		if($db->getdata($sql) == 'No-Data'){
			echo "No-Data";	
		}else{
			echo json_encode($db->getdata($sql));
		}	
	}
	if(isset($_REQUEST['count_ar_ref'])){
		$c_ar_ref = $_REQUEST['count_ar_ref'];
		$db = new DatabaseClass($dss_ip);
		$sql = "SELECT COUNT(po_num) as 'pocount' FROM po_sum_tbl WHERE po_ar_ref ='".$c_ar_ref."'";
		if($db->getdata($sql) == 'No-Data'){
			echo "No-Data";	
		}else{
			echo json_encode($db->getdata($sql));
		}	
	}

	if(isset($_REQUEST['getdetails'])){
		$po_ref = $_REQUEST['getdetails'];
		
		$db = new DatabaseClass($dss_ip);
		$result = $db->get_query("SELECT item_sku,IFNULL (item_rec_qty,0) as 'i_recqty',item_req_qty,item_desc from po_det_tbl where po_num ='$po_ref'");
		if($result->rowCount() > 0){
            while($row = $result->fetch(PDO::FETCH_ASSOC)){
				?>
				<tr style = "padding:0px;" ondblclick="Get_sku('<?php echo $row['item_sku']; ?>','<?php echo $po_ref; ?>')" <?php 
					if((int)$row['i_recqty'] == 0){
						echo "style = 'background:gray'";
					}
				?>>
                    <td><?php echo $row['item_sku']; ?></td>
                    <td><?php echo $row['i_recqty']; ?></td>  
					<td><?php echo $row['item_req_qty']; ?></td>
					<td><?php echo $row['item_desc']; ?></td>       
                </tr>
				<?php
			} 
        }else{
			echo "No-Data";
		} 
	}
	
	if(isset($_REQUEST['getdesc'])){
		$sku = $_REQUEST['getdesc'];
		$desc_po = $_REQUEST['desc_po'];
		$db = new DatabaseClass($dss_ip);
		$result = $db->get_query("SELECT item_sku,item_desc,item_req_qty,item_rec_qty from po_det_tbl where item_sku=".$sku." and po_num =".$desc_po);
		if($result->rowCount() > 0){
            while($row = $result->fetch(PDO::FETCH_ASSOC)){
				echo "Item: ".$row['item_sku']." Desc: ".$row['item_desc']." Qty Req: ".$row['item_req_qty']." Qty Received: ".$row['item_req_qty'];
			} 
        }else{
				echo "No-Data";
		} 
	}
	
	if(isset($_REQUEST['slot_req'])){
		$slot_req = $_REQUEST['slot_req'];
		$srelFlag = $_REQUEST['srelFlag'];
		$mylot = $_REQUEST['mylot'];
		$Qty_tally = $_REQUEST['Qty_tally'];
		$Sku_qty = $_REQUEST['Sku_qty'];
		$poref = $_REQUEST['poref'];
		$sku_parent = $_REQUEST['sku_parent'];
		$sku_child = $_REQUEST['sku_child'];
		$sku = $_REQUEST['sku'];
		$db = new DatabaseClass($dss_ip);
		
		if ($srelFlag == "1"){
			$sql = "update po_det_tbl set item_check_tally='".$Qty_tally."',item_rec_qty=".$Sku_qty." where po_num=".$poref." and (item_sku=".$sku_parent." OR item_sku=".$sku_child.")";		
		}else{
			$sql = "update po_det_tbl set item_check_tally='".$Qty_tally."',item_rec_qty=".$Sku_qty." where po_num=".$poref." and item_sku=".$sku;
		
		}
		if($db->_update_data($sql) == 0){
			echo $sql;	
		}else{
			echo "1";
		}
	}

	 
	
?>
