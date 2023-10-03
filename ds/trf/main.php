<?php
    if (str_contains($_SERVER['REQUEST_URI'], '.php')) header("Location: ./");
?>

<table class="table table-bordered table-sm" style="margin-bottom: 0;">
    <tbody>
        <tr>
            <td style="padding-top: 15px;">TRF Ref</td>
            <td colspan="3">
                <input type="text" class="form-control" name="trf_ref" placeholder="TRF Ref" required
                    value="<?php echo !isset($_GET['trf_ref']) ? '' : ''.$_GET['trf_ref'];?>"
                    <?php echo isset($_GET['trf_ref']) && !empty($tot_qty_trf) ? '' : 'autofocus';?>
                    onfocus="this.select()"
                    onkeypress="javascript: if(event.keyCode == 13 && this.value.trim() != '') window.location.href='?trf_ref='+this.value.trim()"
                >
            </td>
        </tr>
        <tr>
            <td></td>
            <td>TRF</td>
            <td>REC</td>
            <td>VAR</td>
        </tr>
        <tr style="padding-top: 15px;">
            <td>TOT QTY</td>
            <td><input type="text" class="form-control" placeholder="000" disabled value="<?php echo $tot_qty_trf; ?>"></td>
            <td><input type="text" class="form-control" placeholder="000" disabled value="<?php echo $tot_qty_rec; ?>"></td>
            <td><input type="text" class="form-control" placeholder="000" disabled value="<?php echo $tot_qty_var; ?>"></td>
        </tr>
        <tr style="padding-top: 15px;">
            <td>LINE ITEM</td>
            <td><input type="text" class="form-control" placeholder="000" disabled value="<?php echo $line_item_trf; ?>"></td>
            <td><input type="text" class="form-control" placeholder="000" disabled value="<?php echo $line_item_rec; ?>"></td>
            <td><input type="text" class="form-control" placeholder="000" disabled value="<?php echo $line_item_var; ?>"></td>
        </tr>
        <tr>
            <td style="padding-top: 15px;">SRC</td>
            <td><input type="text" class="form-control" placeholder="XXXX" disabled value="<?php echo $src; ?>"></td>
            <td style="padding-top: 15px;">DEST</td>
            <td><input type="text" class="form-control" placeholder="XXXX" disabled value="<?php echo $dest; ?>"></td>
        </tr>
        <tr>
        </tr>
        <tr>
            <td colspan="4" class="text-center">
                <label for="Scan_Item_Barcode">SCAN ITEM BARCODE</label>
                <input type="text" class="form-control" name="sku" placeholder="Scan Item Barcode" required
                    <?php echo !isset($_GET['trf_ref']) && empty($tot_qty_trf) ? '' : 'autofocus';?>
                    onfocus="this.select()"
                    onkeypress="javascript: if(event.keyCode == 13 && this.value.trim() != '') scanItem(this.value)"
                >
            </td>
        </tr>
    </tbody>
</table>

<table class="table table-borderless" style="margin-bottom: 0;">
    <tbody>
        <tr>
            <td width="50%" style="padding-left: 0;">
                <button type="button" class="btn btn-primary btn-block btn-sm" <?php echo isset($_GET['trf_ref']) ? '' : 'disabled';?>
                    onclick="window.location.href='?details<?php echo !isset($_GET['trf_ref']) ? '' : '&trf_ref='.$_GET['trf_ref']; ?>'">
                    Check Items
                </button>
            </td>
            <td width="50%" style="padding-right: 0;">
                <button type="button" class="btn btn-primary btn-block btn-sm" onclick="confirmTrf()" <?php echo isset($_GET['trf_ref']) ? '' : 'disabled';?>>Confirm</button>
            </td>
        </tr>
        <tr>
            <td colspan="2" style="padding: 8px 0 8px 0;">
                <button type="button" class="btn btn-primary btn-block btn-sm"
                    onclick="window.location.href='?download<?php echo !isset($_GET['trf_ref']) ? '' : '&trf_ref='.$_GET['trf_ref']; ?>'">
                    Download TRF
                </button>
            </td>
        </tr>
    </tbody>
</table>