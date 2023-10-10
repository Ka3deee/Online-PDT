<?php

$host = "192.168.0.40";
$db_name = "mmsmtsml"; 
$username = "studentwhs";
$password = "studentwhs";   
$conn_m = null;

try {
  $conn_m = odbc_connect("DRIVER=Client Access ODBC Driver (32-bit);System=".$host.";DBQ=".$db_name, $username, $password);
	if($conn_m == null){
		die('Could not connect: ' . odbc_errormsg());
		exit;
	}

	$qry = "call ".$db_name.".sp_pick('81559','110')";
    $result = odbc_exec($conn_m,$qry);
    while ($res = odbc_fetch_array($result)) {
        if (!empty($res)) {
            $trfbch = $res['WHMOVE'];
            $inumbr = $res['INUMBR'];
            $whmvqt = $res['WHMVQT'];
            $whmvqr = $res['WHMVQR'];
            
            echo $trfbch;
			echo "|||||";
            echo $inumbr;
			echo "|||||";
            echo $whmvqt;
			echo "|||||";
            echo $whmvqr;
        }
    }


} catch(Exception $e) {
  echo "Connection failed: " . $e->getMessage();
}

?>