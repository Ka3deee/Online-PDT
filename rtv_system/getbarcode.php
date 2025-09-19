<?php
//getting sku
if (isset($_REQUEST['barcode'])) {
    session_start();
    date_default_timezone_set('Asia/Manila');
    include("connect_datadump.php");
    $rtv_store = $_SESSION['rtv_storecode'];
    $barcode = $_REQUEST['barcode'];
    $sku = '';
    $upc = '';
    $desc = '';
    $vendor = '';
    $hasdata = false;
    //Find SKU,UPC to INVUPC using UPC
    $counter = 0;
    $Get_query = "SELECT inumbr,iupc from invupc where iupc = '$barcode'";						
	$result = $conn_dump->query($Get_query);
	if($result->rowCount() > 0){
        while($row = $result->fetch(PDO::FETCH_ASSOC)){
            $sku = $row["inumbr"];
            $upc = $row["iupc"];
            $counter++;
        }
        $hasdata = true;
    }else{
        $hasdata = false;
        
    }
    $showdata = false;

    if ($hasdata) {
        //Continue to extract details in INVMST
        $get_vend = "SELECT idescr, asnum from invmst where inumbr = '$sku'";						
        $vendresult = $conn_dump->query($get_vend);
        if($vendresult->rowCount() > 0){
            while($vend_row = $vendresult->fetch(PDO::FETCH_ASSOC)){
                $vendor = $vend_row["asnum"];
                $desc = $vend_row["idescr"];
            }
            $showdata  = true;
        }
    } else {
        include("connect_mms.php");
        //to get get upc data
        $odbc_statement = "SELECT A.INUMBR, A.IDESCR, C.ASNUM, C.ASNAME, C.ASRTCD, D.IBHAND 
                            FROM INVMST A 
                            LEFT JOIN INVUPC B On A.INUMBR = B.INUMBR 
                            LEFT JOIN APSUPP C On A.ASNUM = C.ASNUM 
                            LEFT JOIN INVBAL D ON A.INUMBR = D.INUMBR 
                            WHERE B.IUPC = '$barcode' AND D.ISTORE = $rtv_store";
        $result = odbc_exec($conn_m, $odbc_statement);
        $counter = 0;
        while (odbc_fetch_row($result)) {
            $hassku = true;
            $sku=  odbc_result($result, "INUMBR");
            $iupc=  $barcode;
            $vendor = odbc_result($result, "ASNUM");
            $desc = odbc_result($result, "IDESCR");
            $counter++;
        }
        if( $counter > 0){
            $showdata  = true;
        }else{
            $showdata  = false;
        }
    }
    if($showdata){
            ?>
            <div class="col-xs-12">
					<label for="ex3">Item Description</label>
                    <input class="form-control" id="desc" type="hidden" value = "<?php echo $desc; ?>" readonly >
					<textarea  disabled class="form-control" rows="2" id="desc_text"><?php echo $desc; ?></textarea>
				</div>
				<div class="col-xs-12">
					<div class="form-group">
					<label for="sel1"> Vendor Code</label>
					<input class="form-control" id="txtvendor" value = "<?php echo $vendor; ?>" type="text"  readonly  >
					
					</div>
				</div>
				<div class="col-xs-6">
					<label for="sel1" >Class Code</label>
					<select class="form-control" id="class_code">
                    <?php
                        include("connect.php");
                        $Get_classcode = "SELECT * FROM `tbl_class_code`";						
                        $classresult = $conn->query($Get_classcode);
                        if($classresult->rowCount() > 0){
                            $ct = 0;
                            while($classrow1 = $classresult->fetch(PDO::FETCH_ASSOC)){
                                ?><option value = "<?php echo $ct.'_'.$classrow1["code"].'_'.$classrow1["codedes"];?>"><?php echo $classrow1["code"].'_'.$classrow1["codedes"];?></option><?php
                            $ct++;
                            }
                        }
                        ?> 

					</select>
				</div>
				<div class="col-xs-6">
					<div class="form-group">
					<label for="sel1">Quantity</label>
					<input maxlength="18"  onkeypress="if ( isNaN(this.value + String.fromCharCode(event.keyCode) )) return false;"  class="form-control" id="txtqty" type="text" autofocus >
                    <input  class="form-control" id="txtid" type="hidden" >
                    <input  class="form-control" id="txtaction" type="hidden" value = "add" >
					</div>
				</div>
				<div class="col-xs-12" style = "font-size:9pt;">
                <?php
						//count item
                        $pdtuser = $_SESSION['eenum'];
						$batchno = $_SESSION['batchno'];
                        $getline_count = "SELECT count(*) as cnt, sum(qty) as qty1 from tblScanned where isuploaded = 0 and pdtuser = '$pdtuser' and batchno =$batchno";						
                        $line_result = $conn->query($getline_count);
                        if($line_result->rowCount() > 0){
                            while($line_row = $line_result->fetch(PDO::FETCH_ASSOC)){
								?>
								<label for="ex2">Line Count: <?php echo $line_row["cnt"]; ?></label><br>
								<label for="ex2">Total Qty: <?php  if($line_row["qty1"] == null){echo 0;}else{ echo $line_row["qty1"];} ; ?></label>
								<?php
                            }
                        }else{
							?>
							<label for="ex2">Line Count: 0</label><br>
							<label for="ex2">Total Qty: 0</label>
							<?php
						}
                        ?>
				</div>
				<div class="col-xs-12">              
                <button style = "width:100%;font-size:12pt;font-weight:bold;" type="button" class=" btn btn-primary btn-lg" id = "btnsave" onclick = "openmdalert('exampleModal',document.getElementById('txtqty').value,document.getElementById('class_code').value,document.getElementById('txtvendor').value,'<?php echo $desc; ?>','<?php echo $upc; ?>','<?php echo $sku; ?>',document.getElementById('txtid').value,document.getElementById('txtaction').value)" >Accept B.O</button>
				</div>
            
            <?php
            //qty,classcode,vendor,desc,upc,sku
        

    }else{
        echo "not found";
    }
    
}
?>