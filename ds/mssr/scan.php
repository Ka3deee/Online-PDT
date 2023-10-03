
<?php
    if (str_contains($_SERVER['REQUEST_URI'], '.php') || !isset($_GET['scan']) || !isset($_GET['mssr_ref']) || !isset($_GET['ar']))
        header("Location: ./");
    
    $mssr_ref = $_GET['mssr_ref'];
    $ar  = $_GET['ar'];
    $query = 'SELECT ar_ref,ar_det,qty,rec_qty,tally FROM msr_det_tbl WHERE msr_ts="'. $mssr_ref .'" AND ar_ref="'. $ar .'"';
    
    $stmt = $conn->prepare($query);
    $stmt->execute();
    $data = $stmt->fetchAll(\PDO::FETCH_ASSOC);
    
    // IF NO DATA FOUND, REDIRECT TO MAIN
    if (sizeof($data) == 0) {
        echo '<script>alert("Item not included in the list");</script>';
        echo '<script>window.location.href="./?mssr_ref='. $mssr_ref .'";</script>';
    }

    $ar_ref  = $data[0]['ar_ref'];
    $ar_det  = $data[0]['ar_det'];
    $qty     = $data[0]['qty'];
    $rec_qty = $data[0]['rec_qty'];
    $tally   = $data[0]['tally'];
?>
<div class="text-left">
    <div style="margin-bottom: 20px;">
        <label for="mssr_ref">ITEM / DESCRIPTION</label>
        <input type="text" class="form-control" disabled style="margin-bottom: 8px;" value="<?php echo $ar_ref; ?>">
        <textarea style="min-width: 100%; max-width: 100%; max-height: 150px; padding: 2px 12px 0 12px;" disabled><?php echo $ar_det; ?></textarea>
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
                            value="<?php echo (empty($rec_qty) ? '0' : $rec_qty) .'/'. $qty; ?>"
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
        else if ((int) $scan_qty + (int) $rec_qty > $qty) {
            echo '<script>alert("Quantity over than required");</script>';
        }
        else {
            $temp_tally = empty($tally) ? $scan_qty : $tally .','. $scan_qty;
            $query = 'UPDATE msr_det_tbl SET rec_qty="'. ((int) $scan_qty + (int) $rec_qty) .'", tally="'.$temp_tally.'" WHERE msr_ts="'. $mssr_ref .'" AND ar_ref="'. $ar .'"';
            $stmt = $conn->prepare($query);
            $stmt->execute();

            // REDIRECT TO MAIN
            echo '<script>window.location.href="./?mssr_ref='. $mssr_ref .'";</script>';
        }
    }
?>