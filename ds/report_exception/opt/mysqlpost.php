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
	$sr_name = $_SESSION['srname'];
	
	include("../../db_connect.php");
	///$db1 = new DatabaseClass();
	//echo $db1->getstor();
	function getDeviceHostName(){
        $user_agent = $_SERVER['HTTP_USER_AGENT'];
        $str = preg_split('/\(/', $user_agent)[1];
        $str = preg_split('/\)/', $str)[0];
        $str = preg_split('/;/', $str)[2];
        return $str;
    }
	if(isset($_REQUEST['update_exception'])){
		$po_ref = $_REQUEST['uploadposum'];
		$db = new DatabaseClass($dss_ip);
		$sql = "update po_sum_tbl set po_stat=4,po_date_rec_bwh=now() where po_num='".$po_ref."'";
		if($db->_update_data($sql) == 0){
			echo "0";	
		}else{
			echo "1";
		}	
	}
	if(isset($_REQUEST['upload_exception'])){
		$xc_source = $_REQUEST['xc_source'];
		$txt_docref = $_REQUEST['txt_docref'];
		$exc_type = $_REQUEST['exc_type'];
		$txt_desc = $_REQUEST['txt_desc'];
		$db = new DatabaseClass($dss_ip);
		$sql = "insert into ex_sum_tbl (exr_source,exr_date,exr_docref,exr_type,exr_detail,exr_user,exr_store) values ('".$xc_source."',now(),".$txt_docref.",'".$exc_type ."','".$txt_desc."','".$wms_status_user."','".$sr_name."')";
		if($db->_update_data($sql) == 0){
			echo "0";	
		}else{
			echo "1";
		}	
	}
	
	if(isset($_REQUEST['getexc_details'])){
		$db = new DatabaseClass($dss_ip);
		$result = $db->get_query("SELECT exr_ref,IFNULL(exr_type,0) as 'e_type',exr_docref,IFNULL(exr_detail,0) as 'ex_det'  from ex_sum_tbl where month(exr_date)=month(now()) and day(exr_date)=day(now()) and year(exr_date)=year(now())");
		if($result->rowCount() > 0){
            while($row = $result->fetch(PDO::FETCH_ASSOC)){
				?>
				<tr style = "padding:0px;cursor:pointer;" ondblclick="getdetails('<?php echo $row['exr_docref']; ?>','<?php echo $row['ex_det']; ?>')">
			
                    <td><?php echo $row['exr_ref']; ?></td>
                    <td><?php echo $row['e_type']; ?></td>  
					<td><?php echo $row['exr_docref']; ?></td>
					<td><?php echo $row['ex_det']; ?></td>       
                </tr>
				<?php
			} 
        }else{
			echo "No-Data";
		} 
	}	
	//SUNREADABLE BARCODE
?>
