<?php
    session_start();
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
    <div class="pos-rel">
        <header class="display-center"> 
            <div class="display-center">
                <img src="../assets/images/lcc.jpg" alt="LCC Logo">
            </div>
            <h4 class="tc font mb">TRF Releasing : Scan Items</h4>
            <h5 class="tc semi-visible">v2.0.0</h5>
            <br>
        </header>
        <main class="display-center">
            <div class="grid-auto-2 mb w-md">
                <div>
                    <label for="barcode">Barcode</label>
                    <input id="barcode" class="textarea font-xs ls" maxlength="18"  onkeypress="if ( isNaN(this.value + String.fromCharCode(event.keyCode) )) return false;" type="text" autofocus >
                </div>
                <div>
                    <label style="opacity: 0;" for="enter">Enter</label>
                    <button type="button" id="enter" class="btn primary p-hover btn-md flex a-center j-center" onclick="getBarcode(document.getElementById('barcode').value)">
                        <ion-icon name="scan"></ion-icon><span class="text-none">&nbsp;&nbsp;Scan</span>
                    </button>
                </div>
            </div>
            <div id="loader-download" class="mb error btn msg-scan">
                <div class="loader" style="height:20px; width:20px;"></div> <strong style="margin-left:10px; font-size:9pt;">Please Wait.. Retrieving Data..</strong> 
            </div>
            <div id="promptalert" style="display:none;font-size:9pt;" class="mb error btn msg-scan">
                <strong>Sorry! </strong><b id="prompt_title"></b>
            </div>
            <div id="duplicateSKU"></div>
            <br>
            <div id="displayitemskuref" class="display-center">
                <input type = "hidden" id="crcvqty" type="text">
                <input type = "hidden" id="expqty" type="text">
                <div class="mb w-md grid-2">
                    <div>
                        <label for="sku">SKU Number</label>
                        <input id="sku" class="font-xs" readonly type="text">
                    </div>
                    <div>
                        <label for="whmove">Reference</label>
                        <input id="whmove" class="font-xs" readonly type="text">
                    </div>
                </div>
                <div class="flex flex-col">
                    <h4>Item Description</h4>
                    <div id="desc" class="textarea text-div mb w-md font-xs"></div>
                </div>
                <div class="mb w-md grid-2">
                    <div>
                        <label for="expiry">Expiration</label>
                        <input class="font-xs" id="expiry" type="text" disabled>
                    </div>
                    <div>
                        <label for="qty">Quantity</label>
                        <input class="textarea font-xs" onkeypress="if (isNaN(this.value + String.fromCharCode(event.keyCode))) return false;" id="qty" type="number">
                    </div>
                </div>
            </div>
            <br>
            <button type="button" class="btn primary p-hover btn-md flex a-center j-center mb" id="accept-btn" onclick="acceptQty(document.getElementById('qty').value,document.getElementById('crcvqty').value,document.getElementById('expqty').value,document.getElementById('whmove').value,document.getElementById('sku').value)" ><ion-icon name="checkmark"></ion-icon>&nbsp; Accept</button>
            <button type="button" class="btn primary p-hover btn-md flex a-center j-center mb" onclick = "window.location.href='../index.php'"><ion-icon name="arrow-undo"></ion-icon>&nbsp;&nbsp;Back</button>
            <br>
            <br>
        </main>
    </div>
</body>

<script src="../assets/js/validate.js"></script>
<script src="../assets/js/animate.js"></script>
<script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
<script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
</html>