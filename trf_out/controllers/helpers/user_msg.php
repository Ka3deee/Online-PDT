<?php if (isset($_SESSION['type'])) { if ($_SESSION['type'] == 'create') { ?>
    <div class="msg success mb flex a-center j-center"><ion-icon name="checkmark-circle"></ion-icon>&nbsp;<?php echo $_SESSION['message']; ?></div>
<?php } else if ($_SESSION['type'] == 'delete') { ?>
    <div class="msg success mb flex a-center j-center"><ion-icon name="checkmark-circle"></ion-icon>&nbsp;<?php echo $_SESSION['message']; ?></div>
<?php }} ?>