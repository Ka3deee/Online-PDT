<?php if (isset($_SESSION['store-code'])) { ?>
    <div class="msg success flex a-center j-center"><span id="store-code"><?php echo $_SESSION['store-code'];?></span>&nbsp;<?php echo "- " . $_SESSION['store-loc']; ?><span></span></div>
<?php } else { ?>
    <div class="msg warning flex a-center j-center"><span id="store-code"></span><ion-icon name="alert-circle"></ion-icon>&nbsp;&nbsp;Please set store code</div>
<?php } ?>