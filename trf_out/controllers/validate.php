<?php

if (isset($_SESSION['employee_no'])) {
    echo "<script>var isUserSet = true;</script>";
} else {
    echo "<script>var isUserSet = false;</script>";
}

?>