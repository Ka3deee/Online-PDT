<?php
    include '../fn.php';
    include '../server_connect.php';
    isLoggedin();

    $tot_qty_trf    = '';
    $tot_qty_rec    = '';
    $tot_qty_var    = '';
    $line_item_trf  = '';
    $line_item_rec  = '';
    $line_item_var  = '';
    $src            = '';
    $dest           = '';
    $trf_ref        = '';

    if (isset($_GET['trf_ref'])) {
        $trf_ref = $_GET['trf_ref'];
        
        // CHECK IF TRF REF IS VALID
        $query = 'SELECT trf_ref,msr,shp_date,src_loc,dest_loc,trf_stat FROM trf_sum_tbl WHERE trf_ref='. $trf_ref;
        $stmt  = $conn->prepare($query);
        $stmt->execute();
        $data1 = $stmt->fetch(PDO::FETCH_NUM);

        if (!$data1) {
            echo '<script>alert("TRF data not found. Download the TRF");</script>';
            echo '<script>window.location.href="./";</script>';
        }
        else {
            $src  = trim($data1[3]);
            $dest = trim($data1[4]);
            
            $query = 'SELECT COUNT(sku) AS cnt_sku, (SELECT COUNT(rcv_qty) FROM trf_det_tbl WHERE trf_ref='. $trf_ref .' AND rcv_qty > 0) AS cnt_rcv_qty, SUM(qty) AS sum_qty, SUM(rcv_qty) AS sum_rcv_qty FROM trf_det_tbl WHERE trf_ref='. $trf_ref;
            $stmt = $conn->prepare($query);
            $stmt->execute();
            $data = $stmt->fetchAll(\PDO::FETCH_NUM)[0];
            
            $tot_qty_trf   = $data[0];
            $tot_qty_rec   = $data[1];
            $tot_qty_var   = $tot_qty_trf - $tot_qty_rec;
            
            $line_item_trf = $data[2];
            $line_item_rec = $data[3];
            $line_item_var = $line_item_trf - $line_item_rec;

            if ($data1[3] > 1 && $tot_qty_var == 0 && $line_item_var == 0 && !isset($_GET['scan']) && !isset($_GET['details']) && !isset($_GET['download'])) {
                echo '<script>alert("TRF already completed");</script>';
            }
        }
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>NEW PDT APPLICATIONS</title>
    <meta charset="UTF-8">
    <meta name="description" content="">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="shortcut icon" href="../../images/favicon.ico"/>
    <link rel="bookmark" href="../../images/favicon.ico"/>
    <link rel="stylesheet" href="../../bootstrap-3.4.1-dist/css/bootstrap.min.css">
    <!---   Content Styles -->
    <link href="../../mycss.css" rel="stylesheet">
</head>  
<body >

<div class="container text-center" style="margin-bottom: 20px;">
    <img src="../../resources/lcc.png">
    <h5><strong>Welcome <?php echo $_SESSION['wms_status_user'] ?></strong></h5>
    <h4>TRF Line Inspection</h4>
    <h6 style="color: red" id="show_errmsg"></h6>

    <?php
        if (isset($_GET['scan'])) {
            include 'scan.php';
        }
        else if (isset($_GET['details'])) {
            include 'details.php';
        }
        else if (isset($_GET['download'])) {
            include 'download.php';
        }
        else include 'main.php';
    ?>

    <button type="button" class="btn btn-primary btn-sm btn-block"
        onclick="window.location.href='<?php echo !isset($_GET['scan']) && !isset($_GET['details']) && !isset($_GET['download']) ? '../' : './'. (empty($trf_ref) ? '' : '?trf_ref='. $trf_ref) ; ?>'">
        <span class="glyphicon glyphicon-log-out"></span> Back
    </button>

</div>

<div id="preloader">
    <div class="caviar-load"></div>
</div>
 
</body>
 <!-- Jquery-2.2.4 js -->
    <script src="../../js/jquery/jquery-2.2.4.min.js"></script>
    <!-- Popper js -->
    <script src="../../js/bootstrap/popper.min.js"></script>
    <!-- Bootstrap-4 js -->
    <script src="../../js/bootstrap/bootstrap.min.js"></script>
    <!-- All Plugins js -->
    <script src="../../js/others/plugins.js"></script>
    <!-- Active JS -->
    <script src="../../js/active.js"></script>
    <!-- TRF RECEIVING -->
    <script src="./trf.js"></script>

</html>