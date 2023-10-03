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

	if(isset($_REQUEST['start_direct_rcv'])){
		
		//get Max ar_ref
		$db = new DatabaseClass($dss_ip);
		$new_ar = $db->get_ar_new() + $myArNew;

		//inser data to tbl ar_sum_tbl

		$sql = "insert into ar_sum_tbl (ar_ref,ar_m_ref,ar_d_stat,ar_m_stat,ar_ven,ar_date_start,ar_user) values (". $new_ar.",'DIRECT',0,3,'RESERVE',now(), '". $wms_status_user." ".$lastname."')";
		if($db->_insert_data($sql) == 0){
			echo 'Error: - Ar_Ref';
		}else{
			echo $new_ar;
			$_SESSION['new_ar'] = $new_ar;
		}		
	}
	if(isset($_REQUEST['insert_inv'])){	
		//get Max ar_ref
		$db = new DatabaseClass($dss_ip);
		$po_ref = $_REQUEST['po_ref'];
		$type = $_REQUEST['type'];
		$saleinv = $_REQUEST['saleinv'];
		$amount = $_REQUEST['amount'];
		//inser data to tbl ar_sum_tbl
		$sql = "insert into inv_sum_tbl (po_num,si_ref,si_amount,si_date,invtyp) values (".$po_ref.",'".$saleinv."','".$amount ."',now(),'".$type."')";
		if($db->_insert_data($sql) == 0){
			echo 'not inserted';
		}else{
			echo 'inserted';
		}		
	}
	if(isset($_REQUEST['insert_ar'])){		
		//get Max ar_ref
		$db = new DatabaseClass($dss_ip);
		$plate = $_REQUEST['plate'];
		$ar_ref = $_REQUEST['ar_ref'];
		//inser data to tbl ar_sum_tbl
		$sql = "update ar_sum_tbl set ar_m_stat=3,ar_d_stat=1,ar_qty=0,ar_unit='NA',ar_ven='NA',AR_PLATE='".$plate."',ar_dept='ESSENTIALS',ar_m_ref='DIRECT',AR_TRUCKING='DIRECT',ar_user='".$wms_status_user." ". $lastname."',ar_date_start=now() where ar_ref=".$ar_ref;
		if($db->_update_data($sql) == 0){
			echo 'Error: - Unable to Update Data. please Try again';
		}else{
			echo "AR reference: ".$ar_ref." updated. Please mark the receiving document for reference";
			$_SESSION['plate_num'] = $plate;
		}		
	}
	if(isset($_REQUEST['storetosession'])){
		//get Max ar_ref
		$po_ref = $_REQUEST['storetosession'];
		if(isset($_SESSION['po_ref_array'])){
			$_SESSION['po_ref_array'] = $po_ref.','.$_SESSION['po_ref_array'];
		}else{
			$_SESSION['po_ref_array'] = $po_ref.',';
		}
		$po_array = explode(",",substr($_SESSION['po_ref_array'], 0, -1) );
		$options = "";
		for ($i=0;$i<count($po_array);$i++) {
			$options = $options."<option>".$po_array[$i]."</option>";
		}
		echo $options;
	}
	if(isset($_REQUEST['inv_list_check'])){
		//get Max ar_ref
		$po_ref = $_REQUEST['po_ref'];
		
		$db = new DatabaseClass($dss_ip);
		$result = $db->get_query("SELECT Si_ref,si_amount,si_date FROM inv_sum_tbl where po_num=".$po_ref);
		if($result->rowCount() > 0){
			
            while($row = $result->fetch(PDO::FETCH_ASSOC)){
				?>
				<tr>
                    <td><?php echo $row['Si_ref']; ?></td>
                    <td><?php echo $row['si_amount']; ?></td>         
                </tr>
				<?php
			} 
        }else{
			echo "not found";
		}  
	}	
	if(isset($_REQUEST['getpodata'])){
		//get Max ar_ref
		$pdt_id  = $_SESSION['device_id'];	
		$db = new DatabaseClass($dss_ip);
		if(isset($_SESSION['viewdate'] )){
			$view = $_SESSION['viewdate'];
			if($view == "all"){
				$sql = "SELECT * FROM pdt_info a left join polist b on a.id=b.id WHERE pdt_id='".$pdt_id ."' AND b.ponumb is not null AND  a.entry_date > date(curdate()-2) ORDER BY a.entry_date desc";
			}else{
				$sql = "SELECT * FROM pdt_info a left join polist b on a.id=b.id WHERE pdt_id='".$pdt_id ."' AND b.ponumb is not null AND a.entry_date > date(curdate())  ORDER BY a.entry_date desc";		
			}
			
		}else{
			$sql = "SELECT * FROM pdt_info a left join polist b on a.id=b.id WHERE pdt_id='".$pdt_id ."' AND b.ponumb is not null AND  a.entry_date > date(curdate()) ORDER BY a.entry_date desc";
		}
		$result = $db->get_query($sql);
		if($result->rowCount() > 0){
			echo $result->rowCount();
        }else{
			echo "0";
		} 
	}
	if(isset($_REQUEST['getpdtinfo'])){
		function checkstatus($po,$status){
			if ($status == '5')  Return "RA Created";
			else if ($status == '-1') return "PO Not Found!";
			else return "Creating RA...";
		}
		//get Max ar_ref
		$pdt_id  = $_SESSION['device_id'];
		if(isset($_SESSION['viewdate'] )){
			$view = $_SESSION['viewdate'];
			if($view == "all"){
				$sql = "SELECT * FROM pdt_info a left join polist b on a.id=b.id WHERE pdt_id='".$pdt_id ."' AND b.ponumb is not null AND  a.entry_date > date(curdate()-2) ORDER BY a.entry_date desc";
			}else{
				$sql = "SELECT * FROM pdt_info a left join polist b on a.id=b.id WHERE pdt_id='".$pdt_id ."' AND b.ponumb is not null AND a.entry_date > date(curdate())  ORDER BY a.entry_date desc";		
			}
			
		}else{
			$sql = "SELECT * FROM pdt_info a left join polist b on a.id=b.id WHERE pdt_id='".$pdt_id ."' AND b.ponumb is not null AND  a.entry_date > date(curdate()) ORDER BY a.entry_date desc";
		}
		$db = new DatabaseClass($dss_ip);
		$result = $db->get_query($sql);
		if($result->rowCount() > 0){
			while($row = $result->fetch(PDO::FETCH_ASSOC)){
				?>
				<tr>
                    <td><?php echo trim($row['ponumb']); ?></td>
                    <td><?php echo trim(checkstatus($row['ponumb'],$row['stat'])); ?></td>
					<td><?php echo trim($row['stat']); ?></td>          
                </tr>
				<?php
			} 
        }else{
			?>
				<tr>
                    <td style = "text-align:center;"  colspan = "3"> No Data Found. </td>
                            
                </tr>
				<?php
		} 
	}
	if(isset($_REQUEST['sendtomsysql'])){
		$myval = 0;
		//get Max ar_ref
		$id = date('Ymdhis').rand(1,9);
		$wms_user_code = $_SESSION['wms_user_code'];
		//$pdt_id = getDeviceHostName();
		$pdt_id  = $_SESSION['device_id'];
		$po_ref = $_REQUEST['po_ref'];
		$ar_ref = $_REQUEST['ar_ref'];
		$db = new DatabaseClass($dss_ip);
		$result = $db->get_query("SELECT count(*) as cnt FROM polist WHERE ponumb=".$po_ref);
		if($result->rowCount() > 0){
			$row = $result->fetch(PDO::FETCH_ASSOC);
			$myval = $row['cnt'];
		}
		 if((int)$myval < 1){
			//insert to polist
			$sql = "INSERT INTO pdt_info(id,status,userid,pdt_id) VALUES('".$id."',0,".$wms_user_code.",'".$pdt_id."')";
			if($db->_insert_data($sql) == 0){
				echo 'pdt_info: not inserted';
			}else{
				echo 'pdt_info: inserted';
			}
			//insert to polist
			$sql = "INSERT INTO polist(id,ponumb) VALUES(".$id.",".$po_ref.")";
			if($db->_insert_data($sql) == 0){
				echo 'tbl_polist: not inserted';
			}else{
				echo 'tbl_polist: inserted';
			}
			//insert to po_sumtbl
			$sql = "INSERT INTO po_sum_tbl (po_num,po_ar_ref,po_pt_type) VALUES (".$po_ref.",".$ar_ref.",'PT')";
			if($db->_insert_data($sql) == 0){
				echo 'po_sumtbl: not inserted';
			}else{
				echo 'po_sumtbl: inserted';
			}
		 }
	}
	if(isset($_REQUEST['confirmfinish'])){
		$ar_ref = $_REQUEST['ar_ref'];
		$db = new DatabaseClass($dss_ip);
		$sql = "Update ar_sum_tbl set ar_d_stat=2,ar_date_end=now() where ar_ref=".$ar_ref;
			if($db->_update_data($sql) == 0){
				echo 'Error: not inserted';
			}else{
				echo 'po_sumtbl: inserted';
			}
	}
	if(isset($_REQUEST['clearsession'])){		
		//get Max ar_ref
		unset($_SESSION['po_ref_array']);
		unset($_SESSION['plate_num']);
		unset($_SESSION['new_ar']);
	}
	if(isset($_REQUEST['viewdate'])){		
		//get Max ar_ref
		$view = $_REQUEST['viewdate'];
		$_SESSION['viewdate'] = $view;
	}


?>
