<?php
//getting sku
if (isset($_REQUEST['barcode'])) {
    session_start();
    date_default_timezone_set('Asia/Manila');
    include("connect.php");

    $barcode = $_REQUEST['barcode'];
    $pdtuser = $_SESSION['eenum'];
    $batchno = $_SESSION['batchno'];
    //Find SKU,UPC to INVUPC using UPC
    $counter = 0;
    $Get_query = "SELECT iupc,qty,ccode, ccodei, id from tblScanned where iupc = '$barcode' and isuploaded = 0 and pdtuser = '$pdtuser' and batchno = '$batchno'";						
	$result = $conn->query($Get_query);
	if($result->rowCount() > 0){
        while($row = $result->fetch(PDO::FETCH_ASSOC)){
            $myupc = $row["iupc"];
            $myqty = $row["qty"];
            $myccode = $row["ccode"];
            $myccodei = $row["ccodei"];
            $myid = $row["id"];
            $counter++;
        }
        echo $myid.'-'.$counter;
    }else{
        echo 'notfound';
    }
    
    
}
?>