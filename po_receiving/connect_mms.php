<?php

$host = "192.168.0.40";
$db_name = "mmlciobj"; // live
//$db_name = "mmsmtsml";  - test
$db_name1 = "mmsmrlib";
$username = "studentwhs";
$password = "studentwhs";   
$conn_m = null;
try {
  $conn_m = odbc_connect("DRIVER=Client Access ODBC Driver (32-bit);System=".$host.";DBQ=".$db_name1, $username, $password);
	if($conn_m == null){
		die('Could not connect: ' . odbc_errormsg());
		exit;
	}else{
		//echo 'MMS Connection Status :Connected <br>';
	}

} catch(Exception $e) {
  echo "Connection failed: " . $e->getMessage();
}

?>