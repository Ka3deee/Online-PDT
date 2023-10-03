<?php session_start();
include('fx/getOStype.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/mycss.css">
    <title></title>
</head>
<body>
    <div class="container text-center">
        <h4>Transfer Receiving Menu</h4>
        <?php if(!isset($_SESSION['strcode'])){?>
            <div class="input-group" style="margin-bottom:5px;">
                <input type="number" min=0 id="inpstr" class="form-control nput" placeholder="Store Code" autofocus>
                <div class="input-group-append">
                    <button class="btn btn-primary btn-sm" id="setstore" type="button" style="background-color:#034f84">SET STORE</button>
                </div>
            </div>
        <?php }
        if(isset($_SESSION['strcode'])){?>
            <div class="input-group input-group-sm mb-1">
            <div class="input-group-prepend">
                <span class="input-group-text" id="inputGroup-sizing-sm">STORE CODE : </span>
            </div>
            <input type="text" class="form-control" aria-label="Small" value="<?php echo $_SESSION['strcode']; ?> - <?php echo $_SESSION['strdesc'];?>" aria-describedby="inputGroup-sizing-sm">
        </div>
        <?php } ?>


        
        <div class="row" style="margin-bottom:5px">
            <div class="col"><button id="scan" class="btn btn-primary btn-sm" style="width:100%;background-color:#034f84;"><?php echo ($type == 'Android') ? 'SCAN ITEMS':'MANUAL RECORDING';?></button></div>
        </div>
        <?php if($type == 'Android'){?>   
        <div class="row" style="margin-bottom:5px;display:none;">
            <div class="col"><a href="downloadtrfbch.php" class="btn btn-primary btn-sm" style="width:100%;background-color:#034f84;">DOWNLOAD TRANSFER</a></div>
        </div>         
        <div class="row" style="margin-bottom:5px">
            <div class="col"><a href="retrievedata.php" class="btn btn-primary btn-sm" style="width:100%;background-color:#034f84;">RETRIEVE DATA</a></div>
        </div>
        <div class="row mb-1">
            <div class="col"><a href="#" class="btn btn-primary btn-sm viewtrf" style="width:100%;background-color:#034f84;">VIEW TRANSFER</a></div>
        </div>
        <?php }else{ ?>
        <div class="row" style="margin-bottom:5px">
            <div class="col"><a href="downloadtrfbch.php" class="btn btn-primary btn-sm" style="width:100%;background-color:#034f84;">DOWNLOAD TRANSFER</a></div>
        </div>
        <div class="row mb-1">
            <div class="col"><a href="#" class="btn btn-primary btn-sm viewtrf" style="width:100%;background-color:#034f84;">VIEW TRANSFER</a></div>
        </div>            
        <div class="row mb-1">
            <div class="col"><a href="#" class="btn btn-primary btn-sm viewpdf" style="width:100%;background-color:#034f84;">DOWNLOAD PDF</a></div>
        </div>
        <?php } ?>
        <div class="row" style="margin-bottom:5px">
            <div class="col"><a target="_blank" href="faq.php" class="btn btn-primary btn-sm" style="width:100%;background-color:#034f84;">FAQ</a></div>
            <div class="col"><a href="#" id="exit" class="btn btn-primary btn-sm" style="width:100%;background-color:#034f84;">EXIT</a></div>
        </div>

        
        <hr>
        <?php if($_SERVER['REMOTE_ADDR'] != "::1") {?>
            <div class="text-muted text-center" style="font-weight: bold">Device IP : <?php echo $_SERVER['REMOTE_ADDR'];?></div>
        <?php }else{ ?>
            <div class="text-muted text-center" style="font-weight: bold">SERVER</div>
        <?php } ?>
        <div class="text-muted">Version 1.2</div>
        <div class="text-muted">Date updated:100622</div>
    </div>

    



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
                        location.replace('../index.php');
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
    
</body>
</html>