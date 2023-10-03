<?php

$local_servername = "192.168.0.27";
$local_username = "mmsdump";
$local_password = "mmsdumpadmin";
$local_dbname = "duimptbl";

try {
  $conn_dump = new PDO("mysql:host=$local_servername;dbname=$local_dbname", $local_username, $local_password);
  // set the PDO error mode to exception
  $conn_dump->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  //echo "Connected!";
  
} catch(PDOException $e) {
  echo "Connection failed: " . $e->getMessage();
}

?>