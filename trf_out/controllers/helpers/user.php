<?php if (isset($_SESSION['employee_no'])) { ?>
    <div class="msg success flex a-center j-center"><?php echo "Employee No : " . $_SESSION['employee_no']; ?></div>
<?php } else { ?>
    <div class="msg warning flex a-center j-center"><ion-icon name="alert-circle"></ion-icon>&nbsp;&nbsp;Please set a user</div>
<?php } ?>