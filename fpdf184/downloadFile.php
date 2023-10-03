<?php
    ob_end_clean();

    $path = '../PDF/';
    $file_name = $_GET['file'];
    $file = $path . $file_name;

    if (substr(strtolower($file_name), -3) == 'pdf') header('Content-Type: application/zip');
    else if (substr(strtolower($file_name), -3) == 'zip') header('Content-Type: application/pdf');
    header('Content-disposition: attachment; filename='. $file_name);
    header("Content-length: " . filesize($file));
    readfile($file);