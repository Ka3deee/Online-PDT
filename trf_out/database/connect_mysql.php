<?php

$db_host = 'localhost';
$db_name = 'trfout_db';
$db_user = 'root';
$db_pass = '';

$conn = new MySQLi($db_host, $db_user, $db_pass, $db_name);

if ($conn->connect_error) {
    die('Database connection error: ' . $conn->connect_error);
} 