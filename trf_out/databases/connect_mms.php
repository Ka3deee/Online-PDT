<?php

$host = "192.168.0.40";
$lib_name = "mmsmrlib"; 
$username = "studentwhs";
$password = "studentwhs";   
$conn_m = null;

try {
  $conn_m = odbc_connect("DRIVER=Client Access ODBC Driver (32-bit);System=".$host.";DBQ=".$lib_name, $username, $password);
	if($conn_m == null){
		die('Could not connect: ' . odbc_errormsg());
		exit;
	}
} catch(Exception $e) {
  echo "Connection failed: " . $e->getMessage();
}

?>