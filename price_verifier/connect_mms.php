<?php

$host = "192.168.0.40";
$db_name = "mmsmrlib";
if($_SESSION['Price_sbu'] == "SMR"){
	$host = "192.168.0.40";
	$db_name = "mmsmrlib";
}else{
	$host = "192.168.0.43";	
	$db_name = "mmdsplib";
}

$username = "studentwhs";
$password = "studentwhs";

$conn_m = null;

try {
	//$MMS_Config = "DRIVER={iSeries Access ODBC Driver};System=".$host.";DBQ=".$db_name, $username, $password";
  //$conn_m = odbc_connect("DRIVER=Client Access ODBC Driver (32-bit);System=".$host.";DBQ=".$db_name, $username, $password);
  $conn_m = odbc_connect("DRIVER=iSeries Access ODBC Driver;System=".$host.";DBQ=".$db_name, $username, $password);
  
	if($conn_m == null){
		die('Could not connect: ' . odbc_errormsg());
		exit;
	}

} catch(Exception $e) {
  echo "Connection failed: " . $e->getMessage();
}

//try to fetch data,
?>