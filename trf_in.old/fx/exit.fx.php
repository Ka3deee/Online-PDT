<?php

if(isset($_GET['exit'])){
    session_start();
    session_unset();
    session_destroy();

    echo json_encode(array('success' => 0));
}