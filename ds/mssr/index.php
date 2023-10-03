<?php
    include '../fn.php';
    include '../server_connect.php';
    isLoggedin();

    $line_item   = '';
    $tot_qty     = '';
    $src         = '';
    $dest        = '';
    $mssr_ref    = '';

    if (isset($_GET['mssr_ref'])) {
        $mssr_ref = $_GET['mssr_ref'];
        
        // CHECK IF MSSR REF IS VALID
        $query = 'SELECT msr_ts,msr_source,msr_dest,msr_stat FROM msr_sum_tbl WHERE msr_ts='. $mssr_ref;
        $stmt  = $conn->prepare($query);
        $stmt->execute();
        $data1 = $stmt->fetch(PDO::FETCH_NUM);

        if (!$data1) {
            echo '<script>alert("MSR data not found. Download the MSR");</script>';
            echo '<script>window.location.href="./";</script>';
        }
        else {
            $src  = trim($data1[1]);
            $dest = trim($data1[2]);
            
            $query = 'SELECT COUNT(ar_ref) AS cnt_ar, (SELECT COUNT(rec_qty) FROM msr_det_tbl WHERE msr_ts='. $mssr_ref .' AND rec_qty > 0) AS cnt_rec_qty, SUM(qty) AS sum_qty, SUM(rec_qty) AS sum_rec_qty FROM msr_det_tbl WHERE msr_ts='. $mssr_ref;
            $stmt = $conn->prepare($query);
            $stmt->execute();
            $data = $stmt->fetchAll(\PDO::FETCH_NUM)[0];
    
            $line_item = $data[1] . '/' . $data[0];
            $tot_qty   = $data[3] . '/' . $data[2];

            if ($data1[3] > 1 && $data[1] == $data[0]  && $data[3] == $data[2] && !isset($_GET['scan']) && !isset($_GET['details']) && !isset($_GET['download'])) {
                echo '<script>alert("MSR already completed");</script>';
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
    <h4>MSSR Receiving</h4>
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
        onclick="window.location.href='<?php echo !isset($_GET['scan']) && !isset($_GET['details']) && !isset($_GET['download']) ? '../' : './'. (empty($mssr_ref) ? '' : '?mssr_ref='. $mssr_ref) ; ?>'">
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
    <!-- MSSR RECEIVING -->
    <script src="./mssr.js"></script>

</html>