<?php

$local_servername = "localhost";
$local_username = "smrapp123";
$local_password = "123";
$local_dbname = "porcv_db";

try {
  $conn = new PDO("mysql:host=$local_servername;dbname=$local_dbname", $local_username, $local_password);
  // set the PDO error mode to exception
  $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  //echo "Connected!";
  
} catch(PDOException $e) {
  echo "Connection failed: " . $e->getMessage();
}

?>