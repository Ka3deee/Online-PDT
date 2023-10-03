<?php
if (isset($_REQUEST['upload'])) {
    session_start();
    include('connect.php');
    
    $pdtuser = $_SESSION['eenum'];
    $batchno = $_SESSION['batchno'];
    $uploaddone = false;
    // get item count
    $getline_count = "SELECT count(*) as cnt, sum(qty) as qty1 from tblScanned where isuploaded = 0 and pdtuser = '$pdtuser' and batchno ='$batchno'";						
    $line_result = $conn->query($getline_count);
    $rows = $line_result->fetch(PDO::FETCH_ASSOC);
    $itemcount = $rows['cnt'];
    //get scanned datas   
    $Get_query = "SELECT id, inumber, qty, ccode from tblScanned  where isuploaded = 0 and pdtuser = '$pdtuser' and batchno = '$batchno'";					
	$result1 = $conn->query($Get_query);
    
    if ($result1->rowCount() > 0) {
        $ctr = 0;
        while ($row = $result1->fetch(PDO::FETCH_ASSOC)) {
            $id = $row['id'];
            $sku = $row['inumber'];
            $qty = $row['qty'];
            $ccode = $row['ccode'];

            //upload data
            $myquery = "INSERT INTO `tbl_scanned`(`store`, `inumbr`, `qty`, `ccode`, `pdtuser`, `batchno`) VALUES (1,'$sku','$qty','$ccode','$pdtuser','$batchno')";
            if($conn->exec($myquery)){
                $ctr++;
                //update flag
                $updatequery = "UPDATE tblScanned set isuploaded = 1 where id = ' $id ' and batchno = '$batchno'";
                $conn->exec($updatequery);
            }
        }

        $x_c = $ctr;
        $x_i = $itemcount;
        $x_t = $itemcount - $x_c; //Compare total items with uploaded items

        if((int)$ctr == (int)$itemcount){
            //Change status once done
            $update_query = "INSERT INTO tbl_donescan(store, batchno, pdtuser) VALUES(1, '$batchno', '$pdtuser')";
            if($conn->exec($update_query)){
                $uploaddone = true;
                echo 'Uploaded : '.$x_c.' / Not Uploaded : '.$x_t.'-';
            }

        }else{
            echo 'Uploaded : '.$x_c.' / Not Uploaded : '.$x_t.'-';           
        }

        if((int)$ctr == (int)$itemcount && $uploaddone){
            //delete uploaded data
            $deletedata_query = "DELETE from tblScanned where isuploaded = 1 and batchno = '$batchno' and pdtuser = '$pdtuser' ";
            if($conn->exec($deletedata_query)){
                echo 'Scanned data was cleared';
            }
           
            $deletebatch_query = " DELETE From tblBatch where pdtuser = '$pdtuser' and batchno = '$batchno'";
            if($conn->exec($deletebatch_query)){
                unset($_SESSION['eenum']);
                unset($_SESSION['batchno']);
            }

        }
    }else{
        echo 'failed';
    }
  
}  
?>