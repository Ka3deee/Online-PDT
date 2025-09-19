<?php 
    session_start();
    include('../path.php');
    include(ROOT_PATH . "/databases/connect_mysql.php");
    include(ROOT_PATH . '/databases/functions.php');

    $batch = selectAll('audit_log');
    $batch_no = array();
    foreach ($batch as $no) {
        $batch_no[] = $no['batch_no'];
    }
    $batch_option = array_unique($batch_no);
    if (!empty($batch_option)) {
        rsort($batch_option);
        $max_batch = max($batch_option);
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>PDT Application : Transfer Releasing</title>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="../assets/images/favicon.ico"/>
    <link rel="stylesheet" href="../assets/css/main.css">
    <link rel="stylesheet" href="../assets/css/animate.css">
</head>

<body>
    <div id="download-animation-wrapper" class="download-animation-wrapper hidden">
        <div class="page">
            <div class="folder">
            <span class="folder-tab"></span>
            <div class="folder-icn">
                <div class="downloading">
                <span class="custom-arrow"></span>
                </div>
                    <div class="bar"></div>
                </div>
            </div>
        </div>
    </div>
    <div class="display-center">
        <header class="display-center"> 
            <div class="display-center">
                <img src="../assets/images/lcc.jpg" alt="LCC Logo">
            </div>
            <h4 class="tc font mb">TRF Releasing : Transfer Out Print Report</h4>
            <h5 class="tc semi-visible">v2.0.0</h5>
            <br>
            <br>
        </header>
        <main class="display-center">
            <div class="mb w-md grid-2">
                <h4 class="semi-visible flex a-end">List of Transfer</h4>
                <div class="flex a-end j-end">
                    <label for="select-batch" class="semi-visible">Batch No.&nbsp;&nbsp;&nbsp;</label>
                    <select onchange="selectBatch(document.getElementById('select-batch').value)" name="select-batch" id="select-batch" class="btn-md primary p-hover">
                        <?php foreach ($batch_option as $row): ?>
                            <option value="<?= $row; ?>"><?= $row; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            <textarea class="textarea w-md mb" id="trf-out-list" name="trf-out-list" rows="7" cols="50" disabled></textarea>
            <button class="btn primary p-hover btn-md flex a-center j-center mb w-md" onclick="genPDF(document.getElementById('select-batch').value);"><ion-icon name="print"></ion-icon>&nbsp;&nbsp;Print PDF</button>
            <button class="btn btn-md primary mb w-md" onclick="window.location.href='../index.php'"><ion-icon name="arrow-undo"></ion-icon>&nbsp;&nbsp;Back</button>
            
            <div id="response"></div>
        </main>

        <div id="preloader">
            <div class="caviar-load"></div>
        </div>
    </div>
    <br>
    <br>
</body>

<script>
    window.onload = function() {
        var selectedValue = document.getElementById('select-batch').value;
        selectBatch(selectedValue);
    };
</script>
<script src="../assets/js/validate.js"></script>
<script src="../assets/js/animate.js"></script>
<script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
<script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
</html>