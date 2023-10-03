<?php
//getting sku

if(isset($_REQUEST['qty'])){
    session_start();
	include 'connect.php';
	$qty = $_REQUEST['qty'];
    $classcode = $_REQUEST['classcode'];
    $vendor = $_REQUEST['vendor'];
    $desc = $_REQUEST['desc1'];
    $upc = $_REQUEST['upc1'];
    $sku = $_REQUEST['sku1'];
    $txtid = $_REQUEST['txtid'];
    $txtaction = $_REQUEST['txtaction'];
    $pdtuser = $_SESSION['eenum'];
    $batchno = $_SESSION['batchno'];
    $code = explode("_",$classcode);
    $codei = $code[1].'_'.$code[2];
    Try{	
        $conn->beginTransaction();	        
        if($txtaction =='add'){
            $myquery= "INSERT INTO `tblscanned`(`inumber`,`iupc`, `idescr`, `asnum`, `qty`, `ccodei`, `ccode`, `batchno`, `pdtuser`, `isuploaded`) VALUES ('$sku','$upc','$desc','$vendor','$qty','$code[0]','$codei','$batchno','$pdtuser',0)"; 
        }else{
            $myquery = "  UPDATE `tblscanned` SET `inumber`=$sku,`qty`=$qty,`ccodei`='$code[0]',`ccode`='$codei' WHERE `iupc` = '$upc' and id = '$txtid'";
        }              
        $conn->exec($myquery);
        echo 'inserted';
        $conn->commit();
          
    }catch(Exception $exception){
        $conn->rollBack(); 
        echo 'Failed';     
       // echo $exception->getMessage();
    }
	
}
?>