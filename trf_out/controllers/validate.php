<?php

if (isset($_SESSION['store-code'])) {
    echo "<script>var isStoreSet = true;</script>";
} else {
    echo "<script>var isStoreSet = false;</script>";
}

if (isset($_SESSION['employee_no'])) {
    echo "<script>var isUserSet = true;</script>";
} else {
    echo "<script>var isUserSet = false;</script>";
}

?>