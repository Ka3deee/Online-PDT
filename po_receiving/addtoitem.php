<?php
//getting sku
session_start();
if(isset($_REQUEST['quantity'])){
	include 'connect.php';
	$quantity = $_REQUEST['quantity'];
    $ponumber = $_REQUEST['ponumber'];
    $sku = $_REQUEST['sku'];
    $refno = $_SESSION['refno'];
    $expiration = $_REQUEST['expiration'];
    $withexpiry = $_REQUEST['withexpiry'];
    
    Try{	
        $conn->beginTransaction();	
        
        $update_query = "UPDATE po_transfers SET rcvqty= CAST(rcvqty AS int) + CAST($quantity AS int)  WHERE Ponumb = '$ponumber' and Inumber = '$sku ' and refno = '$refno'"; 
        
        $conn->exec($update_query);

        //if has expiration date
        if ($withexpiry =="yes"){
            $update_expiration = "UPDATE po_transfers SET expiredate = '$expiration'  WHERE Ponumb = '$ponumber' and Inumber = '$sku ' and refno = '$refno'"; 
        
            $conn->exec($update_expiration);
        }
        echo 'inserted';
        $conn->commit();
          
    }catch(Exception $exception){
        $conn->rollBack();      
        echo $exception->getMessage();
    }
	
}
?>