<?php 
    session_start();
    unset($_SESSION['message']);
    unset($_SESSION['type']);
    include('../path.php');
    include(ROOT_PATH . "/databases/connect_mysql.php");
    include(ROOT_PATH . '/databases/functions.php');

    $sql_batchno = "SELECT MAX(batch_no) as max_batch FROM audit_log";
    $result = $conn->query($sql_batchno);
    $row = $result->fetch_assoc();
    if ($row['max_batch'] == null) {
        $batch_no = 1;
    } else {
        $batch_no = $row['max_batch'] + 1;
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
            <h4 class="tc font mb">TRF Releasing : Transfer Out Data Download</h4>
            <h5 class="tc semi-visible">v2.0.0</h5>
            <br>
        </header>
        <main class="display-center">
            <div class="semi-visible mb">Batch No. <?php echo $batch_no; ?></div>
            <div id="loader-wrapper" class="mb w-md">
                <div class="loader"></div><strong>Checking store code... Please wait...</strong>
            </div>
            <div id="loader-download" class="mb w-md">
                <div class="loader"></div><strong>Downloading Data... Please wait...</strong>
            </div>
            <div class="mb w-md"><?php include(ROOT_PATH . '/controllers/helpers/store.php'); ?></div>
            <div class="mb w-md grid-2">
                <input id="store-num" type="text" class="textarea" placeholder="Store Code" onkeydown="CheckStore(event)" autofocus>
                <input id="doc-num" type="text" class="textarea" placeholder="Document No." onkeypress="docNo(event)">
            </div>
            <div class="w-md grid-auto">
                <textarea class="textarea" id="trf-out-list" name="trf-out-list" rows="7" cols="50" disabled></textarea>
                <button type="button" class="btn btn-vt delete" onclick="Clear()"><ion-icon name="trash-outline"></ion-icon></button>
            </div>
            <div id="progress-container">
                <div id="progress-bar" class="w-md">&nbsp;&nbsp;<span id="percent-value">0%</span></div>
                <br>
                <div id="information" ></div>
            </div>
            <button class="btn primary p-hover btn-md flex a-center j-center mb" onclick="Download(document.getElementById('trf-out-list').value)"><ion-icon name="cloud-download"></ion-icon>&nbsp;&nbsp; Download Data</button>
            <button class="btn primary p-hover btn-md flex a-center j-center mb" onclick="saveTextFile()"><ion-icon name="reader"></ion-icon>&nbsp;&nbsp;Export Data</button>
            <button class="btn primary p-hover btn-md flex a-center j-center mb" onclick="window.location.href='../index.php'"><ion-icon name="arrow-undo"></ion-icon>&nbsp;&nbsp;Back</button>
            
            <div id="response"></div>
        </main>

        <div id="preloader">
            <div class="caviar-load"></div>
        </div>
        <iframe id="loadarea" style="display:none;"></iframe>
    </div>
    <br>
    <br>
</body>

<script src="../assets/js/validate.js"></script>
<script src="../assets/js/animate.js"></script>
<script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
<script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
</html>