
<!DOCTYPE html>
<?php
session_start();

?>
<html lang="en">
<head>
  <title>PDT Application : RTV Releasing</title>
  <meta charset="UTF-8">
  <meta name="description" content="">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <link rel="shortcut icon" href="../images/favicon.ico"/>
  <link rel="bookmark" href="../images/favicon.ico"/>
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <link rel="stylesheet" href="../bootstrap-3.4.1-dist/css/bootstrap.min.css">

  <!---   Content Styles -->
  <link href="../mycss.css" rel="stylesheet">
  <link href="../css/modify.css" rel="stylesheet">
  <link href="../css/addedcss.css" rel="stylesheet">
  <style>
    .download-animation-wrapper {
      position: absolute;
      height: 100vh;
      width: 100%;
    }
    .hidden {
      display: none;
    }
    .page {
      position: absolute;
      bottom: 0;
      padding: 20px 2%;
      @include display-flex;
      @include align-items(center);
      -moz-box-align: center;
      -webkit-box-pack: center;
      -webkit-justify-content: center;
      justify-content: center;
      -moz-box-pack: center;
      -ms-flex-pack: center;
    }

    .folder {
      background-color: #337ab7;
      position: relative;
      width: 92px;
      height: 64px;
      display: block;
      border-top-right-radius: 8px;
      border-bottom-right-radius: 8px;
      border-bottom-left-radius: 8px;
    }
      .folder-tab {
        position: absolute;
        height: 10px;
        left: 0;
        bottom: 100%;
        display: block;
        width: 40%;
        border-top-left-radius: 8px;
        background-color: inherit;
        &:after {
          content: '';
          position: absolute;
          display: block;
          top: 0;
          left: calc(100% - 10px);
          border-bottom: 10px solid #337ab7;
          border-left: 10px solid transparent;
          border-right: 10px solid transparent;
        }
      }

      .folder-icn {
        padding-top: 12px;
        width: 100%;
        height: 100%;
        display: block;
      }
      .downloading {
        width: 30px;
        height: 32px;
        margin: 0 auto;
        position: relative;
        overflow: hidden;
      }
        .custom-arrow {
          width: 14px;
          height: 14px;
          position: absolute;
          top: 0;
          left: 50%;
          margin-left: -7px;
          background-color: #fff;
          
          -webkit-animation-name: downloading;
          -webkit-animation-duration: 1.5s;
          -webkit-animation-iteration-count: infinite;
          animation-name: downloading;
          animation-duration: 1.5s;
          animation-iteration-count: infinite;
          
          &:after {
            content: ''; position: absolute; display: block;
            top: 100%;
            left: -9px;
            border-top: 15px solid #fff;
            border-left: 16px solid transparent;
            border-right: 16px solid transparent;
          }
        }
      .bar {
        width: 30px;
        height: 4px;
        background-color: #fff;
        margin: 0 auto;
      }

    @-webkit-keyframes downloading {
      0% {
        top: 0;
        opacity: 1;
      }
      50% {
        top: 110%;
        opacity: 0;
      }
      52% {
        top: -110%;
        opacity: 0;
      } 
      100% {
        top: 0;
        opacity: 1;
      }
    }
    @keyframes downloading {
      0% {
        top: 0;
        opacity: 1;
      }
      50% {
        top: 110%;
        opacity: 0;
      }
      52% {
        top: -110%;
        opacity: 0;
      } 
      100% {
        top: 0;
        opacity: 1;
      }
    }
  </style>
</head>  
<body >
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
<div class="container">
  <div class="container-fluid text-center">
		<img src="../resources/lcc.jpg" style="width: 90px; height: 70px;">
		<h4>RTV Releasing</h4>
		<h5 class="semi-visible">v2.0.0</h5>
    <br><br>
    <div class="row" style = "margin-top:-20px;">
<?php
if (isset($_POST['upload-scanned'])) {
  if (isset($_FILES["file"]) && $_FILES["file"]["error"] == UPLOAD_ERR_OK) {

    // Code here 
    // Read the uploaded file
    $file = $_FILES["file"]["tmp_name"];
    $lines = file($file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    include("connect.php");
    $pdtuser = $_SESSION['eenum'];
    // $batchno = $_SESSION['batchno'];

    $userqry = "INSERT INTO tbl_items_batch (pdtuser, isgenerated, status) VALUES(:pdtuser, 0, 1)";
    $stmt = $conn->prepare($userqry);
    $stmt->bindParam(':pdtuser', $pdtuser);
    $userqryresult = $stmt->execute();
    
    if ($userqryresult) {
        $lastInsertedId = $conn->lastInsertId();
        $batchnoqry = "INSERT INTO tblBatch(batchno,pdtuser, isuploaded) VALUES('" . $lastInsertedId . "','" . $pdtuser . "',0)";
        $batchnoqryresult = $conn->query($batchnoqry);
    } else {
        echo "Error: " . $stmt->errorInfo()[2];
    }
    $batchno = $lastInsertedId;
    $isuploaded = 0;

    // Process and insert the data into the database
    foreach ($lines as $line) {
      $data = explode(",", $line);
      $inumber = $data[0];
      $iupc = $data[1];
      $idescr = $data[2];
      $asnum = $data[3];
      $qty = $data[4];
      $ccodei = $data[5];
      $ccode = $data[6];
      $pdtuser = $data[7];

      $sql = "INSERT INTO tblscanned(`inumber`,`iupc`, `idescr`, `asnum`, `qty`, `ccodei`, `ccode`, `batchno`, `pdtuser`, `isuploaded`) VALUES ('" . $inumber . "','" . $iupc . "','" . $idescr . "','" . $asnum . "','" . $qty . "','" . $ccodei . "','" . $ccode . "','" . $batchno . "','" . $pdtuser . "',0)";
      $result = $conn->query($sql);
    }

    $uploaddone = false;
    // get item count
    $getline_count = "SELECT count(*) as cnt, sum(qty) as qty1 from tblScanned where isuploaded = 0 and pdtuser = '$pdtuser' and batchno ='$batchno'";						
    $line_result = $conn->query($getline_count);
    $rows = $line_result->fetch(PDO::FETCH_ASSOC);
    $itemcount = $rows['cnt'];
    //get scanned datas   
    $Get_query = "SELECT id, inumber, qty, ccode from tblScanned  where isuploaded = 0 and pdtuser = '$pdtuser' and batchno = '$batchno'";					
    $result1 = $conn->query($Get_query);
    
    if ($result1->rowCount() > 0) {
        $ctr = 0;
        while ($row = $result1->fetch(PDO::FETCH_ASSOC)) {
            $id = $row['id'];
            $sku = $row['inumber'];
            $qty = $row['qty'];
            $ccode = $row['ccode'];

            //upload data
            $myquery = "INSERT INTO `tbl_scanned`(`store`, `inumbr`, `qty`, `ccode`, `pdtuser`, `batchno`) VALUES (1,'$sku','$qty','$ccode','$pdtuser','$batchno')";
            if($conn->exec($myquery)){
                $ctr++;
                //update flag
                $updatequery = "UPDATE tblScanned set isuploaded = 1 where id = ' $id ' and batchno = '$batchno'";
                $conn->exec($updatequery);
            }
        }

        $x_c = $ctr;
        $x_i = $itemcount;
        $x_t = $itemcount - $x_c; //Compare total items with uploaded items

        if((int)$ctr == (int)$itemcount){
            //Change status once done
            $update_query = "INSERT INTO tbl_donescan(store, batchno, pdtuser) VALUES(1, '$batchno', '$pdtuser')";
            if($conn->exec($update_query)){
                $uploaddone = true;
                // echo 'Uploaded : '.$x_c.' / Not Uploaded : '.$x_t.'-';
            }

        }else{
            // echo 'Uploaded : '.$x_c.' / Not Uploaded : '.$x_t.'-';           
        }

        if((int)$ctr == (int)$itemcount && $uploaddone){
          //delete uploaded data
          $deletedata_query = "DELETE from tblScanned where isuploaded = 1 and batchno = '$batchno' and pdtuser = '$pdtuser' ";
          if($conn->exec($deletedata_query)){
              // echo 'Scanned data was cleared';
          }
          
          $deletebatch_query = " DELETE From tblBatch where pdtuser = '$pdtuser' and batchno = '$batchno'";
          // if($conn->exec($deletebatch_query)){
          //     unset($_SESSION['eenum']);
          //     unset($_SESSION['batchno']);
          // }
        }
    }else{
        echo 'failed';
    }
    echo '<div class="col-xs-12">
      <div class="msg fade-out success notif" id="msg">
        <span> PO Data uploaded successfully !</span>
      </div>
    </div>';

  } else {
    echo '<div class="col-xs-12">
      <div class="msg fade-out error notif" id="msg">
        <span> Error uploading PO Data </span>
      </div>
    </div>';
  }
}
?>
    </div>
    <?php 
    //ip Code
       if(isset($_SESSION['rtv_server_ip']) ){
          ?><div style = "padding:5px;margin-top:-10px;"class="alert alert-success">
          <strong>Server IP : <?php echo $_SESSION['rtv_server_ip'];?> </strong>
          
        </div><?php
       }else{
        ?><div style = "padding:5px;margin-top:-10px;" class="alert alert-warning">
          <strong>Please Set IP Address!</strong>
        </div><?php
       }
       //Store Code
       if(isset($_SESSION['rtv_storecode']) ){
        ?><div style = "padding:5px;margin-top:-10px;"class="alert alert-success">
        <strong>Store Code: <?php echo $_SESSION['rtv_storecode'];?> </strong>
        
      </div><?php
     }else{
      ?><div style = "padding:5px;margin-top:-10px;" class="alert alert-warning">
        <strong>Please Set Store Code!</strong>
      </div><?php
     }
      //User ID
      if(isset($_SESSION['eenum']) ){
        ?><div style = "padding:5px;margin-top:-10px;"class="alert alert-success">
        <strong>User ID: <?php echo $_SESSION['eenum'];?> </strong>
        
      </div><?php
     }else{
      ?><div style = "padding:5px;margin-top:-10px;" class="alert alert-warning">
        <strong>Please Set User ID!</strong>
      </div><?php
     }
    ?>
    <button type="button" class="btn btn-primary  btn-block" onclick = "window.location.href='set_server_ip.php'" >Set Server IP  </button>
    <button type="button" class="btn btn-primary  btn-block" onclick = "window.location.href='setstore.php'" >Set Store  </button>
    <?php
     if(isset($_SESSION['rtv_server_ip']) ){
      ?><button type="button" class="btn btn-primary  btn-block" onclick = "window.location.href='userID.php'" >Set User ID  </button><?php
     }else{
      ?><button type="button" class="btn btn-primary  btn-block" onclick = "alert('Please Set Server IP')" >Set User ID  </button><?php
     }
    ?>
    <?php 
    //store Code
       if(isset($_SESSION['eenum']) and isset($_SESSION['rtv_server_ip']) ){
          ?>
          <button type="button" id="download-rtv" class="btn btn-primary btn-block" onclick="saveTextFile()"><span class="glyphicon glyphicon-save"></span> Download RTV Data as TXT</button>
          <button type="button" id="scan-items" class="btn btn-primary btn-block" onclick = "window.location.href='rtvmenu.php'" > Scan Items</button>
          <button type="button" id="upload-po-data" class="btn btn-primary btn-block"><span></span> Upload Scanned</button>
          <?php
       }else{
        ?>
        <button type="button" class="btn btn-primary btn-block" onclick = "alert('Please Set Server IP And User ID, Thank you');"><span class="glyphicon glyphicon-save"></span> Download RTV Data as TXT</button>
        <button type="button" class="btn btn-primary  btn-block" onclick = "alert('Please Set Server IP And User ID, Thank you');" > Scan Items</button>
          <button type="button" class="btn btn-primary btn-block" onclick = "alert('Please Set Server IP And User ID, Thank you');"><span></span> Upload Scanned</button>
        <?php
       }
    ?>
    
		<button type="button" id="exit-btn" class="btn btn-primary btn-block" onclick = "window.location.href='../smr.php'"><span class="glyphicon glyphicon-log-out"></span> Exit</button>
      
    <br>
    <div class="text-muted" style="font-size: 12px; text-align: center;">Date updated : 2023 June</div>
	</div>
  
</div>

<div id="preloader">
        <div class="caviar-load"></div>
</div> 

<div class="upload-po-wrapper" style="display: none">
  <form action="server_settings.php" method="post" class="form-container" id="submit-file" enctype="multipart/form-data">
    <div class="nav-window"></div>
    <div class="upload-files-container">
      <div style="padding:5px; display: none;" class="alert alert-success import-msg">
        <strong>Please select file. </strong>
      </div>
      <input type="file" name="file" class="file-import" id="file-import" accept=".txt">      
      <div class="file-import-wrapper">
        <label for="file-import" class="for-file-input">Select File</label>
        <span class="span-text" id="file-name"></span>
      </div>
      <input type="submit" class="upload-button" name="upload-scanned" value="Upload" onclick="showLoader()">
    </div>
  </form>
</div>

</body>
<script>
// hide the notification message after 3 seconds
setTimeout(function(){
  document.getElementById("msg").style.display = "none";
}, 5000);

</script>
<script>
    /* 
		o-- Start --o
			Author: Rainier C. Barbacena
			Date: June 22, 2023
			Description: Sends AJAX request to the PHP script that generates and returns the text file content.
		*/
		function saveTextFile() {
      var confirmation = confirm("Are you sure you want to download data as text file?");
      if (confirmation) {
        document.getElementById("download-rtv").disabled = true;
        document.getElementById("scan-items").disabled = true;
        document.getElementById("upload-po-data").disabled = true;
        document.getElementById("exit-btn").disabled = true;
        document.getElementById("download-animation-wrapper").classList.remove("hidden");
        var xhr = new XMLHttpRequest();
        xhr.open('GET', 'generate_text_file.php', true);
        xhr.responseType = 'blob'; // Set the response type to 'blob' to handle binary data
        xhr.onload = function (e) {
          if (this.status === 200) {
            // Create a temporary anchor element to facilitate the file download
            var blob = new Blob([this.response], { type: 'text/plain' });
            var downloadLink = document.createElement('a');
            downloadLink.href = window.URL.createObjectURL(blob);
            downloadLink.download = 'RtvSystemMasterData.txt';

            // Programmatically trigger the click event on the download link
            document.body.appendChild(downloadLink);
            downloadLink.click();
            document.body.removeChild(downloadLink);
            document.getElementById("download-animation-wrapper").classList.add("hidden");
            document.getElementById("download-rtv").disabled = false;
            document.getElementById("scan-items").disabled = false;
            document.getElementById("upload-po-data").disabled = false;
            document.getElementById("exit-btn").disabled = false;
          }
        };
        xhr.send();
      }
		}
		/* 
			Author: Rainier C. Barbacena
			Date: June 22, 2023
			Description: Sends AJAX request to the PHP script that generates and returns the text file content.
		o-- End --o
		*/
    </script>
 <!-- Jquery-2.2.4 js -->
    <script src="../js/jquery/jquery-2.2.4.min.js"></script>
    <!-- Popper js -->
    <script src="../js/bootstrap/popper.min.js"></script>
    <!-- Bootstrap-4 js -->
    <script src="../js/bootstrap/bootstrap.min.js"></script>
    <!-- All Plugins js -->
    <script src="../js/others/plugins.js"></script>
    <!-- Active JS -->
    <script src="../js/active.js"></script>
    <!-- Animate -->
    <script src="../js/animate.js"></script>
</html>