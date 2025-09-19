<?php
//getting sku
if (isset($_REQUEST['barcode'])) {
    session_start();
    date_default_timezone_set('Asia/Manila');
    include("connect_mms.php");
    // $store = $_SESSION['price_storecode'];
    // $store = $_GET['store_code'];
    $store = 140;
    $barcode = $_REQUEST['barcode'];
    $sku;
    $iupc;
    $desc;
    $xstyle;
    $hassku = false;
    $odbc_statement = "SELECT inumbr,iupc, iupprm FROM invupc WHERE inumbr<>0 and iupc='".$barcode."' order by iupprm";
    $result = odbc_exec($conn_m, $odbc_statement);
    while (odbc_fetch_row($result)) {
        $hassku = true;
        $sku=  odbc_result($result, "inumbr");
        $iupc=  odbc_result($result, "iupc");
    }
    if(!$hassku){
        echo "price not found";
        return 0;
    }
    //get Description
    $odbc_statement = "SELECT inumbr,idescr,istyln FROM invmst WHERE inumbr='".$sku."'";
    $result = odbc_exec($conn_m, $odbc_statement);
    while (odbc_fetch_row($result)) {
        $desc =  odbc_result($result, "idescr");
        $xstyle =  odbc_result($result, "istyln");
    }

    //set date format (ymd) 220705
    $year = substr(date('Y'), -2);
    $month = date('m');
    $day = date('d');
    $date1 =  $year.$month.$day;


    $price;
    $hasprice = false;
    // $odbc_statement = "SELECT plnamt,plncdt FROM prcpln WHERE plnitm='".$sku."' and plncdt <='".$date1."' and plnadt >='".$date1."' and plnstr in (0,".$store.") order by plnlvl,plncdt,plnevt,plnflg desc,plntyp";
    
    $odbc_statement = "SELECT plnamt,plncdt FROM prcpln WHERE plnitm='".$sku."' and plncdt <='".$date1."' and plnadt >='".$date1."' and plnstr in (0,".$store.") order by plnlvl,plncdt,plnevt,plnflg desc,plntyp";

    $result = odbc_exec($conn_m, $odbc_statement);
    while (odbc_fetch_row($result)) {
        $hasprice = true;
        $price =  odbc_result($result, "plnamt");
    }

    if (!$hasprice) {
        $odbc_statement = "Select plnamt,plncdt from prcpln WHERE plnitm=0 and plnsty='".$xstyle."'  and plnadt>='".$date1."' and plnstr in (0,".$store.") order by plnlvl,plncdt,plnevt,plnflg desc,plntyp";
        $result = odbc_exec($conn_m, $odbc_statement);
        while (odbc_fetch_row($result)) {
            $price =  odbc_result($result, "plnamt");
        }
    }

    if ($hasprice) { ?>

        <!-- <div class="col-xs-5">
            <label for="ex1" style="font-size:9pt;">SKU Number</label>
            <input value="<?php echo $sku; ?>" style="font-size:10pt;color:black;" class="form-control" id="sku" disabled type="text">
        </div>
        <div class="col-xs-7">
            <label for="ex1" style="font-size:9pt;">UPC / Barcode</label>
            <input value="<?php echo $iupc; ?>" style="font-size:10pt;color:black;" class="form-control" id="upc" disabled type="text">
        </div>
        <div class="col-xs-12" style="margin-top: 10px;">
            <label for="ex3">Price</label>
            <div class="well well-sm" style="text-align:center;font-size:2em;color:red;"><b id="price">Php <?php echo number_format($price,2); ?></b></div>
        </div>
        <div class="col-xs-12">
            <label for="ex3">Item Description</label>
            <div class="well well-sm" style="text-align:center;font-size:1em;color:darkblue;"><b id="desc"><?php echo $desc; ?></b></div>
        </div> -->
        <?php
            $formattedPrice = number_format($price, 2);
            $parts = explode('.', $formattedPrice);

            $integerPart = $parts[0];
            $decimalPart = $parts[1];
        ?>
        <div id="price-tag-container">
            <div id="price-tag-wrapper">
                <div class="price-tag-divide">
                    <div class="left-div">
                        <h4 class="price-tag-desc"><?php echo $desc; ?></h4>
                        <div class="price-tag-number">
                            <h6><?php echo $sku; ?></h6>
                            <!-- <h6>2307</h6> -->
                        </div>
                    </div>
                    <div class="right-div">
                        <div class="price">
                            <h6 class="peso-sign">₱</h6>
                            <h6 class="peso-int">
                                <?php
                                    $formattedPrice = number_format($price, 2);
                                    $parts = explode('.', $formattedPrice);
                                    echo $integerPart = $parts[0] . '.';
                                ?>
                            </h6>
                            <h6 class="peso-tenths">
                                <?php
                                    $formattedPrice = number_format($price, 2);
                                    $parts = explode('.', $formattedPrice);
                                    echo $decimalPart = $parts[1];
                                ?>
                            </h6>
                        </div>
                        <svg class="barcode" data-upc="<?php echo $iupc; ?>"></svg>
                    </div>
                </div>
            </div>
        </div>
    <?php 
    } else {
        echo "price not found";
    }
}
?>