<?php
//DATABASE CLASS FOR DIRECT RECEIVING, DIRECT LINE INSPECTION AND REPORT Exception

//AUTHOR: Ely Blanquera III
//Version: 2.0.0

$host = "192.168.0.40";
$db_name = "mmsmtsml";
$db_name1 = "mmsmrlib";
$username = "studentwhs";
$password = "studentwhs";   
$conn_m = null;
try {
  //$conn_m = odbc_connect("DRIVER=={iSeries Access ODBC Driver};System=".$host.";DBQ=".$db_name1, $username, $password);
  $conn_m = odbc_connect("DRIVER=iSeries Access ODBC Driver;System=".$host.";DBQ=".$db_name1, $username, $password);
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