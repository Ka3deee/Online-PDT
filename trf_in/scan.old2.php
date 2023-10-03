<?php
session_start();
include('fx/getOStype.php');
if(!isset($_SESSION['strcode'])){
    header("Location:index.php?notif=nostrcode");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/mycss.css">
    <style>
        td {
            font-size:12px;
        }
    </style>
    <title></title>
</head>
<body>
    <div class="container">
        <!--<p id="rcv"></p>-->
        <div class="input-group mb-1 mt-4">
            <input type="text" id="barcode" class="form-control nput" placeholder="Barcode">
            <div class="input-group-append">
                <a href="#" class="btn btn-primary btn-sm" id="go" style="width:100%;background-color:#034f84;">Search</a>
            </div>
        </div>
        <div class="sr-only" id="trfbch_id"></div>
        <div class="sr-only" id="oldqty"></div>
        <table id="table" class="table table-bordered">
            <tr>
                <td>SKU:<div id="sku" style="font-weight:bold;"></div></td>
                <td>TRF#:<div id="trf" style="font-weight:bold;"></div></td>
            </tr>
            <tr>
                <td colspan = "2">DESC:<div id="desc" style="font-weight:bold;"></div></td>
            </tr>
            <tr>
                <td>RCVQTY:<input class="sr-only" id="expqty" type="text" value="0.00" style="width:100%" readonly/></td>
                <input class="sr-only" id="expqty" type="text" value="0.00" style="width:100%" readonly/>
                <td style="padding:0;"><input id="rcvqty" type="number" value="" min=0 style="width:100%;font-size:25px;text-align:center;"/></td>
            </tr>
        </table>
        <p class="text-center"><button id="save" class="btn btn-sm mb-1" style="width:100%;background-color:#034f84;color:white;">SAVE</button>
        <a href="javascript:prevPage()" id="btn_prev" class="btn btn-sm" style="background-color:#034f84;color:white;">Prev</a>
        <a href="javascript:nextPage()" id="btn_next" class="btn btn-sm" style="background-color:#034f84;color:white;">Next</a>
        page: <span id="page"></span></p>
        <div class="row mb-1">
            <div class="col"><a href="index.php" class="btn btn-sm"  style="width:100%;background-color:#034f84;color:white">HOME</a></div>
            <div class="col"><a href="scan.php" class="btn btn-sm"  style="width:100%;background-color:#034f84;color:white">Clear Search</a></div>
        </div>
    </div>

<script src="js/onscan.js"></script>
<script src="js/onscan.min.js"></script>

<?php if($type != 'Android'){ ?>
    <script>
    // Initialize with options
    onScan.attachTo(document, {
        suffixKeyCodes: [13], // enter-key expected at the end of a scan
        reactToPaste: false, // Compatibility to built-in scanners in paste-mode (as opposed to keyboard-mode)
        onScan: function() { // Alternative to document.addEventListener('scan')
            var barcode = document.getElementById("barcode").value;
            var xmlhttp = new XMLHttpRequest();
            xmlhttp.onreadystatechange = function() {
                if (this.readyState == 4 && this.status == 200) {
                    objJson = JSON.parse(this.responseText);
                    changePage(1);
                };
            }
            xmlhttp.open("GET","fx/fetch.fx.php?iupc="+barcode,true);
            xmlhttp.send();
        },
        onKeyDetect: function(iKeyCode){ // output all potentially relevant key events - great for debugging!
            console.log('Pressed: ' + iKeyCode);
        }
    });
    </script>	
<?php }else{ ?>
<script>
    document.addEventListener('keyup',function(){
        var barcode = document.getElementById("barcode").value;
        var xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                objJson = JSON.parse(this.responseText);
                changePage(1);
                document.getElementById("rcvqty").focus();
            };
        }
        xmlhttp.open("GET","fx/fetch.fx.php?iupc="+barcode,true);
        xmlhttp.send();
    });
</script>
<?php } ?>



<script>

var objJson = [];





var current_page = 1;
var records_per_page = 1;

function prevPage()
{
    if (current_page > 1) {
        current_page--;
        changePage(current_page);
    }
}

function nextPage()
{
    if (current_page < numPages()) {
        current_page++;
        changePage(current_page);
    }
}

function changePage(page)
{
    var btn_next = document.getElementById("btn_next");
    var btn_prev = document.getElementById("btn_prev");
    var page_span = document.getElementById("page");

    // Validate page
    if (page < 1) page = 1;
    if (page > numPages()) page = numPages();


    for (var i = (page-1) * records_per_page; i < (page * records_per_page); i++) {
        //listing_table.innerHTML += objJson[i].adName + "<br>";
        document.getElementById("trfbch_id").innerHTML = objJson[i].id;
        document.getElementById("sku").innerHTML = objJson[i].inumbr;
        document.getElementById("trf").innerHTML = objJson[i].trfbch;
        document.getElementById("desc").innerHTML = objJson[i].idescr;
        document.getElementById("oldqty").innerHTML = objJson[i].rcvqty;
        //document.getElementById("rcv").innerHTML = objJson[i].rcvqty;
        document.getElementById("expqty").value = objJson[i].expqty;
        //document.getElementById("rcvqty").value = "0.00";
        
    }
    page_span.innerHTML = page;

    if (page == 1) {
        btn_prev.style.visibility = "hidden";
    } else {
        btn_prev.style.visibility = "visible";
    }

    if (page == numPages()) {
        btn_next.style.visibility = "hidden";
    } else {
        btn_next.style.visibility = "visible";
    }
}

function numPages()
{
    return Math.ceil(objJson.length / records_per_page);
}

/*window.onload = function() {
    changePage(1);
};*/
</script>


<script>

document.getElementById("go").addEventListener('click', function() {
    var barcode = document.getElementById("barcode").value;
    var xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            objJson = JSON.parse(this.responseText);
            changePage(1);
            //changePage(1,objJson);
            document.getElementById("rcvqty").focus();
        };
    }
    xmlhttp.open("GET","fx/fetch.fx.php?iupc="+barcode,true);
    xmlhttp.send();
    });
</script>


<script>

document.getElementById("save").addEventListener('click', function() {
        var trfbch_id = document.getElementById("trfbch_id").innerHTML;
        var oldqty = document.getElementById("oldqty").innerHTML;
        var expqty = document.getElementById("expqty").value;
        var rcvqty = document.getElementById("rcvqty").value;
        var trfnum = document.getElementById("trf").innerHTML;

        //var newqty = parseInt(oldqty + rcvqty);
        if(1 == 2){
            alert("RCVQTY111 exceeded.");
        }else{
            var xmlhttp = new XMLHttpRequest();
            xmlhttp.onreadystatechange = function() {
                if (this.readyState == 4 && this.status == 200) {
                    var resp = JSON.parse(this.responseText);
                    if(resp.success === 1){
                        //window.location.reload('scan.php');
                        //alert("Saved");
                        window.location.reload(true);
                    }else{
                        alert("Failed : RCVQTY exceeded.");
                    }
                };
            }
            xmlhttp.open("GET","fx/savescanned.fx.php?trfbchid="+trfbch_id+"&&rcvqty="+rcvqty+"&&oldqty="+oldqty+"&&expqty="+expqty+"&&trfnum="+trfnum,true);
            xmlhttp.send();
        }
    });

    document.getElementById("barcode").focus();

</script>
</body>
</html>