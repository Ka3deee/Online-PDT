
<?php
    if (str_contains($_SERVER['REQUEST_URI'], '.php') || !isset($_GET['scan']) || !isset($_GET['trf_ref']) || !isset($_GET['sku']))
        header("Location: ./");
    
    $trf_ref = $_GET['trf_ref'];
    $sku     = $_GET['sku'];

    $query = 'SELECT b.sku,b.sdesc,b.qty,b.rcv_qty,b.ret_val,b.tally,b.rcv_remarks,b.smodel FROM invupc a LEFT JOIN trf_det_tbl b ON a.inumbr=b.sku  where b.trf_ref="'. $trf_ref .'" AND (a.iupc="'. $sku .'" OR b.sku="'. $sku .'")';
    
    $stmt = $conn->prepare($query);
    $stmt->execute();
    $data = $stmt->fetchAll(\PDO::FETCH_ASSOC);
    
    // IF NO DATA FOUND, REDIRECT TO MAIN
    if (sizeof($data) == 0) {
        echo '<script>alert("Item not included in the list");</script>';
        echo '<script>window.location.href="./?trf_ref='. $trf_ref .'";</script>';
    }

    $sku_ref     = $data[0]['sku'];
    $sdesc       = $data[0]['sdesc'];
    $qty         = $data[0]['qty'];
    $rcv_qty     = $data[0]['rcv_qty'];
    $ret_val     = $data[0]['ret_val'];
    $tally       = $data[0]['tally'];
    $rcv_remarks = $data[0]['rcv_remarks'];
    $smodel      = $data[0]['smodel'];
?>
<div class="text-left">
    <div style="margin-bottom: 20px;">
        <label for="trf_ref">SKU / DESCRIPTION</label>
        <input type="text" class="form-control" disabled style="margin-bottom: 8px;" value="<?php echo $sku_ref; ?>">
        <textarea style="min-width: 100%; max-width: 100%; max-height: 150px; padding: 2px 12px 0 12px;" disabled><?php echo $sdesc; ?></textarea>
    </div>

    <div style="margin-bottom: 10px;">
        <table class="table table-bordered table-sm">
            <thead class="text-center">
                <tr>
                    <th width="50%">UPC / MODEL</th>
                    <th width="50%">RETAIL</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td><?php echo $sku; ?></td>
                    <td rowspan="2"><?php echo $ret_val; ?></td>
                </tr>
                <tr>
                    <td><?php echo $smodel; ?></td>
                </tr>
            </tbody>
        </table>
    </div>

    <div style="margin-bottom: 10px;">
        <table class="table table-bordered table-sm">
            <thead class="text-center">
                <tr>
                    <th>QTY RCVD/SHP</th>
                    <th>SCAN QTY</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>
                        <input type="text" class="form-control" placeholder="000/000" disabled
                            value="<?php echo (empty($rcv_qty) ? '0' : $rcv_qty) .'/'. $qty; ?>"
                        >
                    </td>
                    <td>
                        <form method="POST">
                            <input type="text" class="form-control" name="scan_qty" value="1" autofocus onfocus="this.select()">
                            <button type="submit" name="confirmScan" style="display: none;"></button>
                        </form>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>

    <div style="margin-bottom: 10px;">
        <label for="description">TALLY</label>
        <div>
            <textarea style="min-width: 100%; max-width: 100%; max-height: 150px; padding: 2px 12px 0 12px;" disabled><?php echo $tally; ?></textarea>
        </div>
    </div>
</div>


<?php
    if (isset($_POST['confirmScan'])) {
        $scan_qty = trim($_POST['scan_qty']);

        // VALIDATE FIELDS
        if (empty($scan_qty) || $scan_qty == 0) {
            echo '<script>alert("Invalid Scan Qty");</script>';
        }
        else if ((int) $scan_qty + (int) $rcv_qty > $qty) {
            echo '<script>alert("Quantity over than required");</script>';
        }
        else {
            $temp_tally = empty($tally) ? $scan_qty : $tally .','. $scan_qty;
            $query = 'UPDATE trf_det_tbl SET rcv_qty="'. ((int) $scan_qty + (int) $rcv_qty) .'", tally="'.$temp_tally.'" WHERE trf_ref="'. $trf_ref .'" AND sku="'. $sku .'"';
            $stmt = $conn->prepare($query);
            $stmt->execute();
            
            // REDIRECT TO MAIN
            echo '<script>window.location.href="./?trf_ref='. $trf_ref .'";</script>';
        }

    }
?>