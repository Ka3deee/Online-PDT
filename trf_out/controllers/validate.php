<?php

if (isset($_SESSION['store-code'])) {
    echo "<script>var isStoreSet = true;</script>";
} else {
    echo "<script>var isStoreSet = false;</script>";
}

?>