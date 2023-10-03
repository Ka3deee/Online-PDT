<?php
if (isset($_REQUEST['eenum']) and isset($_REQUEST['pass'])) {
    session_start();
    $eenum = $_REQUEST['eenum'];
    $pass = $_REQUEST['pass'];
    $batchno = 0;
    //checkuser
    include('connect.php');
    $Get_query = "SELECT * FROM tbl_user_access where `eenumb` = '$eenum' and `password` = '$pass' and isallowed = 1";						
	$result1 = $conn->query($Get_query);
	if($result1->rowCount() > 0){
        //check batch
        $check_batch = "SELECT batchno from tblBatch where isuploaded = 0 and pdtuser = '$eenum'";						
        $check_result = $conn->query($check_batch);
        if($check_result->rowCount() > 0){
            $batchrow = $check_result->fetch(PDO::FETCH_ASSOC);
            $batchno = $batchrow['batchno'];
            $_SESSION['batchno'] = $batchno;
            $_SESSION['eenum'] = $eenum;
            echo $batchno;
        }else{
            $update_query = "INSERT INTO tbl_items_batch(pdtuser, isgenerated, status) VALUES('$eenum', 0, 1)";       
            $conn->exec($update_query);
            $stmt = $conn->query("SELECT LAST_INSERT_ID()");
            $lastId = $stmt->fetchColumn();

            //insert batch
            $update_query = "INSERT INTO tblBatch(batchno,pdtuser, isuploaded) VALUES($lastId, $eenum,0)";       
            $conn->exec($update_query);
            $_SESSION['batchno'] = $lastId;
            echo $lastId;
            $_SESSION['eenum'] = $eenum;
        }
	}
	else{
		echo 'notfound';
	}


}

?>