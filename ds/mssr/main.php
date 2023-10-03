<?php
    if (str_contains($_SERVER['REQUEST_URI'], '.php')) header("Location: ./");
?>

<table class="table table-bordered table-sm" style="margin-bottom: 0;">
    <tbody>
        <tr>
            <td style="padding-top: 15px;">MSSR Ref</td>
            <td colspan="3">
                <input type="text" class="form-control" name="mssr_ref" placeholder="MSSR Ref" required
                    value="<?php echo !isset($_GET['mssr_ref']) ? '' : ''.$_GET['mssr_ref'];?>"
                    <?php echo isset($_GET['mssr_ref']) && !empty($line_item) ? '' : 'autofocus';?>
                    onfocus="this.select()"
                    onkeypress="javascript: if(event.keyCode == 13 && this.value.trim() != '') window.location.href='?mssr_ref='+this.value.trim()"
                >
            </td>
        </tr>
        <tr>
            <td style="padding-top: 15px;">LINE ITEM</td>
            <td><input type="text" class="form-control" placeholder="000/000" disabled value="<?php echo $line_item; ?>"></td>
            <td style="padding-top: 15px;">TOT QTY</td>
            <td><input type="text" class="form-control" placeholder="000/000" disabled value="<?php echo $tot_qty; ?>"></td>
        </tr>
        <tr>
            <td style="padding-top: 15px;">SRC</td>
            <td colspan="3"><input type="text" class="form-control" placeholder="XXXX" disabled value="<?php echo $src; ?>"></td>
        </tr>
        <tr>
            <td style="padding-top: 15px;">DEST</td>
            <td colspan="3"><input type="text" class="form-control" placeholder="XXXX" disabled value="<?php echo $dest; ?>"></td>
        </tr>
        <tr>
            <td colspan="4" class="text-center">
                <label for="Scan_Item_Barcode">SCAN ITEM BARCODE</label>
                <input type="text" class="form-control" name="ar" placeholder="Scan Item Barcode" required
                    <?php echo !isset($_GET['mssr_ref']) && empty($line_item) ? '' : 'autofocus';?>
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
                <button type="button" class="btn btn-primary btn-block btn-sm" <?php echo isset($_GET['mssr_ref']) ? '' : 'disabled';?>
                    onclick="window.location.href='?details<?php echo !isset($_GET['mssr_ref']) ? '' : '&mssr_ref='.$_GET['mssr_ref']; ?>'">
                    Check Items
                </button>
            </td>
            <td width="50%" style="padding-right: 0;">
                <button type="button" class="btn btn-primary btn-block btn-sm" onclick="confirmMssr()" <?php echo isset($_GET['mssr_ref']) ? '' : 'disabled';?>>Confirm</button>
            </td>
        </tr>
        <tr>
            <td colspan="2" style="padding: 8px 0 8px 0;">
                <button type="button" class="btn btn-primary btn-block btn-sm"
                    onclick="window.location.href='?download<?php echo !isset($_GET['mssr_ref']) ? '' : '&mssr_ref='.$_GET['mssr_ref']; ?>'">
                    Download MSSR
                </button>
            </td>
        </tr>
    </tbody>
</table>