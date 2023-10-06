<?php if (isset($_SESSION['store-code'])) { ?>
    <div class="msg success"><?php echo $_SESSION['store-code'] . " - " . $_SESSION['store-loc']; ?></div>
<?php } else { ?>
    <div class="msg warning">Please set store code</div>
<?php } ?>