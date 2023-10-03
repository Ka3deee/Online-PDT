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
        echo $myid.'-'.$counter.'-'.$myupc.'-'.$myqty.'-'.$myccode.'-'.$myccodei;
    }else{
        echo 'notfound';
    }
    
    
}
if (isset($_REQUEST['duplicate'])) {
    session_start();
    date_default_timezone_set('Asia/Manila');
    include("connect.php");

    $barcode = $_REQUEST['duplicate'];
    $action = $_REQUEST['action'];
    $pdtuser = $_SESSION['eenum'];
    $batchno = $_SESSION['batchno'];
    //Find SKU,UPC to INVUPC using UPC
    $counter = 0;
    $Get_query = "SELECT id,inumber, idescr, iupc, qty, ccode, ccodei from tblScanned where iupc = '$barcode' and pdtuser = '$pdtuser' and batchno = '$batchno' and isuploaded = 0";						
	$result = $conn->query($Get_query);
	if($result->rowCount() > 0){
        while($row = $result->fetch(PDO::FETCH_ASSOC)){
            ?>
            <tr>
					<td><?php echo $row["inumber"];?></td>
					<td><?php echo $row["idescr"];?></td>
					<td><?php echo $row["iupc"];?></td>
                    <td><?php echo $row["qty"];?></td>
                    <td><?php echo $row["ccode"];?></td>
                    <?php
                    if ($action == 'delete'){
                        ?>
                        <td> <button style = "width:100%;" type="button" class="btn btn-sm btn-warning" onclick = "deleteitem(1,'<?php echo $row['id'];?>','<?php echo $row['iupc'];?>')"  >Delete</button></td>
                        <?php  
                    }else{
                        ?>
                        <td> <button style = "width:100%;" type="button" class="btn btn-sm btn-primary" onclick = "setvalues('<?php echo $row['id'];?>','<?php echo $row['iupc'];?>','<?php echo $row['qty'];?>','<?php echo $row['ccode'];?>','<?php echo $row['ccodei'];?>')"  >Select</button></td>
                        <?php
                    }
                    ?>
                    
				</tr>
            <?php
        }
    }else{
        echo 'notfound';
    }
    
    
}
if (isset($_REQUEST['delete'])) {
    session_start();
    date_default_timezone_set('Asia/Manila');
    include("connect.php");

    $id = $_REQUEST['delete'];

    Try{	
        $conn->beginTransaction();	
        $myquery = " Delete from tblScanned where id = '$id'";
        
        $conn->exec($myquery);
        echo 'deleted';
        $conn->commit();
          
    }catch(Exception $exception){
        $conn->rollBack(); 
        echo 'Failed';     
    }
    
    
}
?>