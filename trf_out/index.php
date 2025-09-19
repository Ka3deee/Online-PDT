<?php 
    session_start();
    include('path.php');
    include(ROOT_PATH . '/controllers/validate.php');
    include(ROOT_PATH . '/databases/connect_mms.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>PDT Application : Transfer Releasing</title>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="assets/images/favicon.ico"/>
    <link rel="stylesheet" href="assets/css/main.css">
    <link rel="stylesheet" href="assets/css/animate.css">
</head>

<body>
    <?php include('controllers/helpers/env_mms.php'); ?>
    <div class="display-center">
        <header class="display-center"> 
            <div class="display-center">
                <img src="assets/images/lcc.jpg" alt="LCC Logo">
            </div>
            <h4 class="tc font mb">TRF Releasing (0ut)</h4>
            <h5 class="tc semi-visible">v2.0.0</h5>
            <br>
            <br>
        </header>
        <main class="display-center">
            <div class="mb w">
                <?php include(ROOT_PATH . '/controllers/helpers/upload_msg.php'); ?>
            </div>
            <div class="mb w">
                <?php include(ROOT_PATH . '/controllers/helpers/user.php'); ?>
            </div>
            <button onclick="window.location.href='pages/set_user.php'" class="btn primary p-hover btn-md flex a-center j-center mb"><ion-icon name="person"></ion-icon>&nbsp;&nbsp; Set User</button>
            <button onclick="openDownload()" class="btn primary p-hover btn-md flex a-center j-center mb"><ion-icon name="cloud-download"></ion-icon>&nbsp;&nbsp; Download TRF Out</button>
            <button onclick="openScan()" class="btn primary p-hover btn-md flex a-center j-center mb"><ion-icon name="scan"></ion-icon>&nbsp;&nbsp; Scan Items</button>
            <button onclick="openUpload()" class="btn primary p-hover btn-md flex a-center j-center mb"><ion-icon name="cloud-upload"></ion-icon>&nbsp;&nbsp; Upload TRF Out</button>
            <button onclick="openPrint()" class="btn primary p-hover btn-md flex a-center j-center mb"><ion-icon name="documents"></ion-icon>&nbsp;&nbsp; Reports</button>
            <button onclick="confirmExit()" class="btn primary p-hover btn-md flex a-center j-center mb"><ion-icon name="arrow-back-circle"></ion-icon>&nbsp;&nbsp; Exit</button>
            <br>
            <div class="tc semi-visible">
                <h5>Version: 2023 December</h5>
                <h6>Version Update: 2025 September</h6>
            </div>
        </main>
        <div id="preloader">
            <div class="caviar-load"></div>
        </div>
    </div>
    
    <div class="upload-wrapper" style="display:none;">
        <form class="form-container" id="submit-file" enctype="multipart/form-data">
            <div onclick="closeUpload()" class="nav-window"></div>
            <div class="upload-files-container">
                <div id="warning-msg" class="msg warning flex a-center j-center mb" style="display:none;">
                    <ion-icon name="alert-outline"></ion-icon><strong>Please select a file.</strong>
                </div>
                <div id="loader-upload">
                    <div class="loader"></div><strong>Processing... Please wait...</strong>
                </div>
                <input onchange="importFile(event)" type="file" name="file" class="file-import" id="file-import" accept=".txt, text/plain">      
                <div class="file-import-wrapper">
                    <label for="file-import" class="for-file-input">Select File</label>
                    <span class="span-text" id="file-name"></span>
                </div>
                <button onclick="uploadTextFile(event)" type="button" class="btn primary p-hover btn-md flex a-center j-center"><ion-icon name="cloud-upload"></ion-icon>&nbsp;&nbsp;Upload</button>
            </div>
        </form>
    </div>
    <br>
    <br>
</body>

<script src="assets/js/animate.js"></script>
<script src="assets/js/validate.js"></script>
<script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
<script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
</html>