<?php
$local_servername = $_SESSION['rtv_server_ip'];
$local_username = "rtv";
$local_password = "rtvadmin";
$local_dbname = "rtv";

try {
  $conn = new PDO("mysql:host=$local_servername;dbname=$local_dbname", $local_username, $local_password);
  // set the PDO error mode to exception
  $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
 // echo "Connected!";
  
} catch(PDOException $e) {
  echo "Connection failed: " . $e->getMessage();
}
?>