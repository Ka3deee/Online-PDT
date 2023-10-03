<?php
//getting sku
session_start();
if(isset($_REQUEST['barcode'])){
	include 'connect.php';
	$barcode = $_REQUEST['barcode'];
    $refno = $_SESSION['refno'];
 
    $Get_query = "select inumbr from tblupc  where iupc = '$barcode' and refno = '$refno'";						
	$result = $conn->query($Get_query);
	if($result->rowCount() > 0){
        $row1 = $result->fetch(PDO::FETCH_ASSOC);
        echo $row1['inumbr'];  
	}
	else{
		echo 'not found';
	}
}
if(isset($_REQUEST['sku'])){
	include 'connect.php';
	$sku = $_REQUEST['sku'];
    $refno = $_SESSION['refno'];
    $Get_query = "select Ponumb from po_transfers  where Inumber = '$sku' and refno = '$refno'";
    $hasduplicate = false;						
	$result = $conn->query($Get_query);
	if($result->rowCount() > 1){
        $hasduplicate = true;               
	}
	else{
        $row1 = $result->fetch(PDO::FETCH_ASSOC);
        $Ponumb = $row1['Ponumb'];
		$hasduplicate = false; 
	}

    if($hasduplicate){
        //$Getdupli_query = "select Ponumb,Inumber,idescr,Expqty,istdpk from po_transfers  where 	Inumber = '$sku' and refno = '$refno'";					
        $Getdupli_query = "select Ponumb,Inumber,idescr,Expqty,istdpk,rcvqty from po_transfers where Inumber = '$sku' and refno = '$refno' and (CAST(rcvqty AS int) != CAST(REPLACE(Expqty, ',', '') AS int));";					
        
		$result1 = $conn->query($Getdupli_query);
		
		// remove finish Scanned PO from the list
        if($result1->rowCount() > 0){
            ?><table class="table table-bordered" style = "font-size:8pt;">
            <thead>
            <tr>
                <th>Reference</th>
                <th>SKU</th>
                <th>Item Description</th>
                <th>Action</th>
            </tr>
            </thead>
            <tbody><?php
           while($rowd = $result1->fetch(PDO::FETCH_ASSOC)){
                ?><tr>
					<td><?php echo $rowd["Ponumb"]; ?></td>
					<td><?php echo $rowd["Inumber"]; ?></td>
					<td><?php echo $rowd["idescr"]; ?></td>
                    <td> <button style = "width:100%;" type="button" class="btn btn-sm btn-primary" onclick = "getSkudata('<?php echo $rowd['Ponumb']; ?>','<?php echo $rowd['Inumber']; ?>')" >Select</button></td>
				</tr><?php
           }          
           ?>
           </tbody>
			</table>
           <?php
        }

    }else{
        echo "no duplicate";
    }
    
    
}
if(isset($_REQUEST['skunoduplicate']) and isset($_REQUEST['ponumber']) ){
    include 'connect.php';
    $sku = $_REQUEST['skunoduplicate'];
    $ponumber = $_REQUEST['ponumber'];
    $refno = $_SESSION['refno'];
    $limit = 1;
    if($ponumber == "none"){
        $Get_query = "select Ponumb,Inumber,idescr,Expqty,Expday,expiredate,istdpk from po_transfers  where Inumber = '$sku'  and refno = '$refno'";	
    }else{
        $Get_query = "select Ponumb,Inumber,idescr,Expqty,Expday,expiredate,istdpk from po_transfers  where Inumber = '$sku'  and refno = '$refno' and Ponumb = '$ponumber'";	
    }
    				
    $result2 = $conn->query($Get_query);
    if($result2->rowCount() > 0){
       while($row2 = $result2->fetch(PDO::FETCH_ASSOC)){
            //get data
            $Ponumb = $row2["Ponumb"];
            $Inumber = $row2["Inumber"];
            $idescr = $row2["idescr"];
            $Expqty = $row2["Expqty"];
            $istpdk = $row2["istdpk"];
            $Expday = $row2["Expday"];
            $expiredate = $row2["expiredate"];
            //Check if has expiration date
			$Expqty = str_replace(",","",$Expqty);
            $itemdate = "";
            $withExpiry = false;
            $expiry = "no";
            if((int)$Expday > 0){
                $withExpiry = true;
                $expiry = "yes";
                if( $expiredate == "0"){
                    $Date = date('Y-m-d');
                    $itemdate =  date('Y-m-d', strtotime($Date. ' + '.(int)$Expday.' days'));
                }else{
                    $itemdate =   $expiredate;
                }
               
            }
            //check if exceeds txtlimit
            $exceed_limit = false;
            if((int)$Expqty >  (int)$limit){
                $exceed_limit = true;
            }
               

            //check if already equals to expected quantity
            $exceeds = false;
            $check_query = "select rcvqty,Expqty from po_transfers  where Inumber = '$Inumber'  and Ponumb = '$Ponumb' and refno='$refno' ";	
            $check_result = $conn->query($check_query);
            if($check_result->rowCount() > 0){
                while($row3 = $check_result->fetch(PDO::FETCH_ASSOC)){
                    $crcvqty = $row3["rcvqty"];
                    $Expqty = $row3["Expqty"];
					$Expqty = str_replace(",","",$Expqty);
                    if ((int)$crcvqty == (int)$Expqty){
                        $exceeds = true;
                        echo "exceeds";
                    }else{
                        $exceeds = false;
                    }
                }
            }
        }          
       if(!$exceeds){
        ?>
         <input type = "hidden" class="form-control" value = "<?php echo $crcvqty ; ?>" id="crcvqty"  type="text">
         <input type = "hidden" class="form-control" value = "<?php echo $Expqty ; ?>" id="Expqty"  type="text">
         <input type = "hidden" class="form-control" value = "<?php echo $expiry ; ?>" id="expiry"  type="text">
        <div class="col-xs-6">
             <label for="ex1">SKU Number</label>
             <input class="form-control" style = "color:black;"  value = "<?php echo $Inumber ; ?>" id="sku" disabled type="text">
         </div>
         <div class="col-xs-6">
             <label for="ex2">Ref Number</label>
             <input class="form-control" style = "color:black;"  id="ref" value = "<?php echo $Ponumb ; ?>"  disabled type="text">
         </div>
         <div class="col-xs-12">
             <label for="ex3">Item Description</label>
             <textarea  disabled class="form-control" style = "color:black;"  rows="2" id="desc"><?php echo $idescr ; ?></textarea>
         </div>
         
        <?php
            if($withExpiry){
               ?>

               <div class="col-xs-12">
                    <label for="ex2">Expiration (<b style = "color:red;" >Please Enter Expiration Date </b>)</label>
                    <input autofocus value = "<?php echo $itemdate ; ?>" class="form-control" id="expiry1"   type="hidden">
                    <input autofocus value = "<?php echo $itemdate ; ?>" onchange = "checktolerable(this.value,document.getElementById('expiry1').value)" class="form-control" id="expiry2"   type="date">
                </div>
               <?php 
            }
        ?>
        
         <div class="col-xs-6">
             <label for="ex1">STD Pack</label>
             <input  disabled class="form-control" id="std" value = "<?php echo $istpdk ; ?>"  type="text">
         </div>
         <?php
            if($exceed_limit){
               ?>
                <div class="col-xs-6">
                    <label for="ex2">Quantity</label>
                    <input value = ""   class="form-control" id="qty" type="number">
                </div>
               <?php 
            }else{
                ?>
                 <div class="col-xs-6">
                    <label for="ex2">Quantity</label>
                    <input disabled value = "1"   class="form-control" id="qty" type="number">
                </div>
                <?php 
            }
       }
       
    }
}

if(isset($_REQUEST['checktrans'])){
	include 'connect.php';
    $user_id = $_SESSION['user_id'];
	$Storecode = $_SESSION['Storecode'];
	$refno = "";
	//get max ref
	$Get_query1 = "SELECT IFNULL (max(P.refno),'') as 'refno' FROM polist as P INNER JOIN (SELECT MAX(CAST(refno AS int)) as maxref FROM polist where user_id = '$user_id' and store_code = '$Storecode') as P2 ON P.refno = P2.maxref";					
	$result1 = $conn->query($Get_query1);
	if($result1->rowCount() > 0){
        $row3 = $result1->fetch(PDO::FETCH_ASSOC);  
		$refno = $row3['refno'];
	}
	if($refno != ''){
		$Get_query = "select sum(Expqty) as 'totalexpqty',sum(rcvqty) as 'totalrcvqty' from po_transfers  where refno = '$refno'";					
		$result = $conn->query($Get_query);
		if($result->rowCount() > 0){
			 $Mysql_result =  $result->fetchAll(\PDO::FETCH_ASSOC);
		    echo json_encode($Mysql_result);              
		}
		
	}else{
		echo 'no-data';
	}
	
	
    

   
    
}
?>