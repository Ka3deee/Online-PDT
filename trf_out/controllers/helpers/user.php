<?php if (isset($_SESSION['employee_no'])) { ?>
    <div class="msg success"><?php echo "Employee No : " . $_SESSION['employee_no']; ?></div>
<?php } else { ?>
    <div class="msg warning">Please set a user</div>
<?php } ?>