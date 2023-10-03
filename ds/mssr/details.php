<?php
    if (str_contains($_SERVER['REQUEST_URI'], '.php')) header("Location: ./?details");
    if (!isset($_GET['mssr_ref'])) header("Location: ./");

    $query = 'SELECT ar_ref,rec_qty,qty,ar_det FROM msr_det_tbl WHERE msr_ts ='. $mssr_ref;
    $stmt = $conn->prepare($query);
    $stmt->execute();
    $data = $stmt->fetchAll(PDO::FETCH_NUM);

    // if (!$data) echo json_encode(array('status' => 'error', 'message' => 'No items found'));
?>
<div class="text-left">
    <div style="margin-bottom: 13px;">
        <label for="mssr_ref">MSSR Ref</label>
        <input type="text" class="form-control" placeholder="MSSR Ref" value="<?php echo $mssr_ref; ?>" disabled>
    </div>

    <div>
        <em style="display: none;" id="checkDtlsTotal"></em>
        
        <div style="margin-bottom: 0;">
            <table class="table table-bordered table-sm" style="margin-bottom: 0;">
                <thead>
                    <tr>
                        <th width="25%">AR</th>
                        <th width="10%">REC</th>
                        <th width="10%">TRF</th>
                        <th width="55%">DESC</th>
                    </tr>
                </thead>
            </table>
        </div>
        <div style="max-height: 200px; overflow: auto;">
            <table class="table table-bordered table-sm">
                <tbody id="checkDtls">
                    <?php
                        if (!$data) echo '<tr><td colspan="4"></td></tr>';
                        foreach ($data as $value) {
                            echo '
                                <tr onclick="selectCheckDtl('.$value[0].',\''.$value[3].'\')" style="cursor: pointer;" title="Check Details of '.$value[0].'">
                                    <td width="25%">'. $value[0] .'</td>
                                    <td width="10%">'. $value[1] .'</td>
                                    <td width="10%">'. $value[2] .'</td>
                                    <td width="55%">'. $value[3] .'</td>
                                </tr>
                            ';
                        }
                    ?>
                </tbody>
            </table>
        </div>
    </div>

    <hr>
    <div style="margin-bottom: 10px;">
        <label for="description">DESCRIPTION</label>
        <div>
            <textarea id="checkDtlDesc" style="min-width: 100%; max-width: 100%; min-height: 65px; max-height: 150px; padding: 2px 12px 2px 12px;" disabled></textarea>
        </div>
    </div>
</div>