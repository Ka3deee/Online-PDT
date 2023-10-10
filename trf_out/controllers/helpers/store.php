<?php if (isset($_SESSION['store-code'])) { ?>
    <div class="msg success flex a-center j-center"><?php echo $_SESSION['store-code'] . " - " . $_SESSION['store-loc']; ?></div>
<?php } else { ?>
    <div class="msg warning flex a-center j-center"><ion-icon name="alert-circle"></ion-icon>&nbsp;&nbsp;Please set store code</div>
<?php } ?>