<?php
session_start(); 
include_once('../path.php');
include(ROOT_PATH . '/databases/functions.php');

$mms_pick = 'mms_pick';
$pick_upc = 'pickupc';
$audit_log = 'audit_log';

// Handle barcode lookup
if (isset($_REQUEST['barcode'])) {
    $barcode = ['iupc' => $_GET['barcode']];
    $result = selectOne($mms_pick, $barcode);
    if ($result) {
        echo $result['inumbr'];
    } else {
        echo "not found";
    }
}

// Handle SKU duplicate check
if (isset($_REQUEST['sku'])) {
    $conditions = [
        'inumbr' => $_REQUEST['sku'],
        '(CAST(rcvqty AS int) != CAST(REPLACE(expqty, ",", "") AS int))',
    ];
    $hasduplicate = false;
    $sku = selectAll($mms_pick, $conditions);
    if (!empty($sku)) {
        if (count($sku) > 1) {
            $hasduplicate = true;
        } else {
            $whmove = $sku[0]['whmove'];
        }
    }

    if ($hasduplicate) {
        if (count($sku) > 0) {
            ?>
            <table class="w-md" style="font-size:9pt;">
                <thead>
                    <tr>
                        <th class="tc bold">Reference</th>
                        <th class="tc bold">SKU</th>
                        <th class="tc bold">Item Description</th>
                        <th class="tc bold">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($sku as $rowd) { ?>
                        <tr>
                            <td class="tc"><?php echo $rowd["whmove"]; ?></td>
                            <td class="tc"><?php echo $rowd["inumbr"]; ?></td>
                            <td class="tc"><?php echo $rowd["idescr"]; ?></td>
                            <td>
                                <button type="button" class="btn primary btn-md flex a-center j-center" onclick="getSkudata('<?php echo $rowd['inumbr']; ?>','<?php echo $rowd['whmove']; ?>')"><ion-icon name="checkmark-circle-outline"></ion-icon></button>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
            <?php
        }
    } else {
        echo "no duplicate";
    }
}

// Handle SKU with no duplicate (load details)
if (isset($_REQUEST['skunoduplicate']) && isset($_REQUEST['whmove'])) {
    $sku = $_REQUEST['skunoduplicate'];
    $whmove = $_REQUEST['whmove'];
    $limit = 1;

    if ($whmove == "none") {
        $conditions = ['inumbr' => $sku];
    } else {        
        $conditions = ['inumbr' => $sku, 'whmove' => $whmove];
    }

    $result = selectAll($mms_pick, $conditions);	
    if (!empty($result)) {
        foreach ($result as $row) {
            // Get data
            $currentWhmove = $row["whmove"];
            $inumbr = $row["inumbr"];
            $idescr = $row["idescr"];
            $expqty = $row["expqty"];
            $rcvqty = $row["rcvqty"];
            $rexpday = $row["rexpday"];

            if ($rexpday != "0") {
                $time = new DateTime('today');
                $time->add(new DateInterval('P' . $rexpday . 'D'));
                $rexpday = $time->format('m/d/Y');
            } else {
                $rexpday = "";
            }

            // Check if exceeds txtlimit
            $exceed_limit = false;
            if ((int)$expqty >  (int)$limit) {
                $exceed_limit = true;
            }

            // Check if already equals to expected quantity
            $exceeds = false;
            $barcode = ['whmove' => $currentWhmove, 'inumbr' => $inumbr];
            $check_query = selectOne($mms_pick, $barcode);
            	
            if (!empty($result)) {
                foreach ($result as $row2) {
                    $crcvqty = $row2["rcvqty"];
                    $cexpqty = str_replace(",", "", $row2["expqty"]);
                    if ((int)$crcvqty == (int)$cexpqty) {
                        $exceeds = true;
                        echo "exceeds";
                    } else {
                        $exceeds = false;
                    }
                }
            }
        }          
       
        if (!$exceeds) {
        ?>
            <input type="hidden" id="crcvqty" type="text" value="<?php echo $crcvqty ; ?>">
            <input type="hidden" id="expqty" type="text" value="<?php echo $cexpqty ; ?>">
            <div class="mb w-md grid-2">
                <div>
                    <label for="sku">SKU Number</label>
                    <input id="sku" class="font-xs" readonly type="text" value="<?php echo $inumbr ; ?>">
                </div>
                <div>
                    <label for="whmove">Reference</label>
                    <input id="whmove" class="font-xs" readonly type="text" value="<?php echo $currentWhmove ; ?>">
                </div>
            </div>
            <div class="flex flex-col">
                <label>Item Description</label>
                <div id="desc" class="textarea text-div mb w-md font-xs"><?php echo $idescr ; ?></div>
            </div>
            <div class="mb w-md grid-2">
                <div>
                    <label for="expiry">Expiration</label>
                    <input class="font-xs" id="expiry" type="text" disabled value="<?php echo $rexpday; ?>">
                </div>
                <?php if($exceed_limit) { ?>
                    <div>
                        <label for="qty">Quantity</label>
                        <input class="textarea font-xs" onkeypress="if (isNaN(this.value + String.fromCharCode(event.keyCode))) return false;" id="qty" type="number" value="">
                    </div>
                <?php } else { ?>
                    <div>
                        <label for="qty">Quantity</label>
                        <input class="textarea font-xs" onkeypress="if (isNaN(this.value + String.fromCharCode(event.keyCode))) return false;" id="qty" type="number" value="1">
                    </div>
                <?php } ?>
            </div>
        <?php
        }
    }
}

// Handle quantity update
if (isset($_REQUEST['rcvqty'])) {
    $whmove = $_REQUEST['whmove'];
    $sku = $_REQUEST['inumbr'];
    $conditions = ['whmove' => $whmove, 'inumbr' => $sku];
    $result = selectOne($mms_pick, $conditions);
    if ($result) {
        $id = $result["id"];
        $update = update($mms_pick, $id, $_REQUEST);
        if ($update) {
            $audit = selectOne($audit_log, ['mms_pick_id' => $id]);
            if ($audit) {
                update($audit_log, $audit['id'], ['is_scanned' => '1']);
            }
            echo "inserted";
        }
    }
}

// Handle file upload (strictly .txt only)
if (isset($_REQUEST['upload'])) {
    if (isset($_FILES["file"]) && $_FILES["file"]["error"] === UPLOAD_ERR_OK) {
        $filename = $_FILES["file"]["name"];
        $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));

        // Check extension must be .txt
        if ($ext !== "txt") {
            echo "invalid file type, only .txt files are allowed";
            exit;
        }

        // Check MIME type with finfo
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mime = finfo_file($finfo, $_FILES["file"]["tmp_name"]);
        finfo_close($finfo);

        if ($mime !== "text/plain") {
            echo "invalid file content, only plain text files are allowed";
            exit;
        }

        // Read the uploaded file
        $file = $_FILES["file"]["tmp_name"];
        $lines = file($file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

        // Process and insert the data into the database
        foreach ($lines as $line) {
            $data = explode(",", $line);
            if (count($data) < 11) {
                continue; // skip malformed lines
            }

            $whmove = $data[0];
            $inumbr = $data[1];
            $iupc = $data[5];
            $strnum = $data[7];
            $rcvqty = $data[9];
            $is_scanned = $data[10];
        
            $conditions = [
                'whmove' => $whmove,
                'inumbr' => $inumbr,
                'iupc' => $iupc,
                'strnum' => $strnum,
            ];
            $select = selectOne($mms_pick, $conditions);
            if ($select) {
                update($mms_pick, $select['id'], ['rcvqty' => $rcvqty]);
                $audit = selectOne($audit_log, ['mms_pick_id' => $select['id']]);
                if ($audit) {
                    update($audit_log, $audit['id'], ['is_scanned' => $is_scanned]);
                }
            }
        }
        echo "upload success";
    } else {
        echo "upload error";
    }
}
?>