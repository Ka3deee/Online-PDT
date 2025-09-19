<?php session_start();
include('fx/getOStype.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>PDT Application : Transfer Receiving</title>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/mycss.css">
    <link href="../css/addedcss.css" rel="stylesheet">
    --<link href="../css/modify.css" rel="stylesheet">
</head>
<body>
    <div class="container text-center"> 
		<img src="../resources/lcc.jpg" style="width: 90px; height: 70px;">
        <br>
        <h4 class="font-title">TRF Receiving</h4>
		<h5 style="font-size: 10pt;" class="semi-visible">v2.0.0</h5>
        <br>

        <?php
          if ($_SERVER["REQUEST_METHOD"] === "POST") {
            // Check if a file was uploaded successfully
            if (isset($_FILES["file"]) && $_FILES["file"]["error"] == UPLOAD_ERR_OK) {
                $file = $_FILES["file"]["tmp_name"];

                // Read the uploaded file
                $lines = file($file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
                $str_code = $_SESSION['strcode'];
                // Establish a connection to the MySQL database
                $servername = "localhost";
                $username = "root";
                $password = "";
                $database = "trfin_db";

                $conn = new mysqli($servername, $username, $password, $database);

                // Check the database connection
                if ($conn->connect_error) {
                    die("Connection failed: " . $conn->connect_error);
                }
                // Process and insert the data into the database
                foreach ($lines as $line) {
                  $data = explode(",", $line);
                  $id = $data[0];
                  $trfbch = $data[1];
                  $inumbr = $data[2];
                  $rcvqty = $data[6];
                  $sql = "UPDATE " . $str_code . "_received_batch_trf_tbl SET `rcvqty`='$rcvqty' WHERE `trfbch`='$trfbch' and `inumbr`='$inumbr' and id ='$id'";
                  $result = $conn->query($sql);
                  if ($result !== TRUE) {
                      echo "Error updating record: " . $conn->error;
                  }
                }
              echo '<div class="col-xs-12">
                <div class="msg fade-out success notif" id="msg">
                  <span> Transfer Data uploaded successfully !</span>
                </div>
              </div>';

            } else {
              echo '<div class="col-xs-12">
                <div class="msg fade-out error notif" id="msg">
                  <span> Error uploading Transfer Data </span>
                </div>
              </div>';
            }
          }
          ?> 
        <?php if(!isset($_SESSION['strcode'])){?>
            <div class="input-group" style="margin-bottom:5px;">
                <input type="number" min=0 id="inpstr" class="form-control nput" placeholder="Store Code" autofocus>
                <div class="input-group-append">
                    <button class="btn btn-sm trf-btn" id="setstore" type="button"><span class="glyphicon glyphicon-home"></span> Set Store</button>
                </div>
            </div>
        <?php }
        if(isset($_SESSION['strcode'])){?>
            <div class="input-group input-group-sm mb-1">
            <div class="input-group-prepend">
                <span class="input-group-text" id="inputGroup-sizing-sm">Store Code : </span>
            </div>
            <input type="text" class="form-control" aria-label="Small" value="<?php echo $_SESSION['strcode']; ?> - <?php echo $_SESSION['strdesc'];?>" aria-describedby="inputGroup-sizing-sm">
        </div>
        <?php } ?>
        
        <div class="row" style="margin-bottom:5px">
            <div class="col"><button id="scan" class="btn btn-sm trf-btn"><?php echo ($type == 'Android') ? 'Scan Items':'Manual Recording';?></button></div>
        </div>

        <?php if($type == 'Android'){?>   
        <div class="row" style="margin-bottom:5px;">
            <div class="col"><a href="downloadtrfbch.php" class="btn btn-sm trf-btn">Download Transfer Data</a></div>
        </div>         
        <div class="row" style="margin-bottom:5px">
            <div class="col"><a href="retrievedata.php" class="btn btn-sm trf-btn">Retrieve Data</a></div>
        </div>
        <div class="row mb-1">
            <div class="col"><a href="#" class="btn btn-sm viewtrf trf-btn">View Transfer</a></div>
        </div>       
        <div class="row mb-1">
            <div class="col"><button type="button" id="upload-po-data" class="btn btn-sm trf-btn">Upload Transfer Data</button></div>
        </div>
        <?php }else{ ?>
        <div class="row" style="margin-bottom:5px">
            <div class="col"><a href="downloadtrfbch.php" class="btn btn-sm trf-btn">Download Transfer Data</a></div>
        </div>
        <div class="row mb-1">
            <div class="col"><a href="#" class="btn btn-sm viewtrf trf-btn">View Transfer</a></div>
        </div>            
        <div class="row mb-1">
            <div class="col"><a href="#" class="btn btn-sm viewpdf trf-btn">Export PDF</a></div>
        </div>      
        <div class="row" style="margin-bottom:5px">
            <div class="col"><a href="upload.php" class="btn btn-sm trf-btn">Upload to MMS</a></div>
        </div>
        <div class="row mb-1">
            <div class="col"><button type="button" id="upload-po-data" class="btn btn-sm trf-btn">Upload Transfer Data</button></div>
        </div>
        <?php } ?>
        <div class="row" style="margin-bottom:5px">
            <div class="col"><a target="_blank" href="faq.php" class="btn btn-sm trf-btn">FAQ</a></div>
            <div class="col"><a href="#" id="exit" class="btn btn-sm trf-btn">EXIT</a></div>
        </div>

        <hr>
        <div class="text-muted" style="font-size: 12px">Date updated : 2023 June</div>
        <?php if($_SERVER['REMOTE_ADDR'] != "::1") {?>
            <div class="text-muted text-center" style="font-size: 12px">Device IP : <?php echo $_SERVER['REMOTE_ADDR'];?></div>
        <?php }else{ ?>
            <div class="text-muted text-center" style="font-size: 12px">Server</div>
        <?php } ?>
    </div>

    <div class="upload-po-wrapper" style="display: none">
    <form action="index.php" method="post" class="form-container" id="submit-file" enctype="multipart/form-data">
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
        <input type="submit" class="upload-button" value="Upload">
      </div>
    </form>
  </div>

<script>
    // hide the notification message after 3 seconds
    setTimeout(function(){
        document.getElementById("msg").style.display = "none";
    }, 5000);
</script>
<script>
    
    document.addEventListener("DOMContentLoaded", function(event) {
        <?php if(!isset($_SESSION['strcode'])):?>
        document.getElementById("setstore").addEventListener('click', function() {
            
            var inpstr = document.getElementById("inpstr").value;
            var xmlhttp = new XMLHttpRequest();
            xmlhttp.onreadystatechange = function() {
                if (this.readyState == 4 && this.status == 200) {
                    var jsonData = JSON.parse(this.responseText);
                    if(jsonData.success === 0){
                        alert("Store not found.");
                    }else{
                        location.reload();
                    }
                }
            };
            xmlhttp.open("GET","fx/setstore.fx.php?str="+inpstr,true);
            xmlhttp.send();
        });
        <?php endif; ?>
    });

    document.addEventListener("DOMContentLoaded", function(event) { 
        document.getElementById("exit").addEventListener('click', function() {
            
            var xmlhttp = new XMLHttpRequest();
            xmlhttp.onreadystatechange = function() {
                if (this.readyState == 4 && this.status == 200) {
                    var jsonData = JSON.parse(this.responseText);
                    if(jsonData.success === 0){
                        location.replace('../smr.php');
                    }
                }
            };            
            xmlhttp.open("GET","fx/exit.fx.php?exit=ok",true);
            xmlhttp.send();
        });
    });


    let viewtrfbtn = document.querySelector(".viewtrf");
    let viewpdf = document.querySelector(".viewpdf");

    document.getElementById("scan").addEventListener('click', function() {
        location.replace('scan.php');
    });

    viewtrfbtn.addEventListener('click', function() {
        location.replace('viewtransfer.php');
    });
    viewpdf.addEventListener('click', function() {
        location.replace('downloadpdf.php');
    });
</script>

<?php
function getIpAddress()
{
    $ipAddress = '';
    if (! empty($_SERVER['HTTP_CLIENT_IP'])) {
        // to get shared ISP IP address
        $ipAddress = $_SERVER['HTTP_CLIENT_IP'];
    } else if (! empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        // check for IPs passing through proxy servers
        // check if multiple IP addresses are set and take the first one
        $ipAddressList = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
        foreach ($ipAddressList as $ip) {
            if (! empty($ip)) {
                // if you prefer, you can check for valid IP address here
                $ipAddress = $ip;
                break;
            }
        }
    } else if (! empty($_SERVER['HTTP_X_FORWARDED'])) {
        $ipAddress = $_SERVER['HTTP_X_FORWARDED'];
    } else if (! empty($_SERVER['HTTP_X_CLUSTER_CLIENT_IP'])) {
        $ipAddress = $_SERVER['HTTP_X_CLUSTER_CLIENT_IP'];
    } else if (! empty($_SERVER['HTTP_FORWARDED_FOR'])) {
        $ipAddress = $_SERVER['HTTP_FORWARDED_FOR'];
    } else if (! empty($_SERVER['HTTP_FORWARDED'])) {
        $ipAddress = $_SERVER['HTTP_FORWARDED'];
    } else if (! empty($_SERVER['REMOTE_ADDR'])) {
        $ipAddress = $_SERVER['REMOTE_ADDR'];
    }
    return $ipAddress;
}

?>
    <!-- Animate -->
    <script src="../js/animate.js"></script>
</body>
</html>