<?php
include('connect_mms.php');

$sql_odbc = "SELECT cusnum, cusloc FROM ARZMST WHERE cusloc = '100'";
$result = odbc_exec($conn_m, $sql_odbc);
$count = 1;
while (odbc_fetch_row($result)) {
    
    $cus_num = odbc_result($result, "cusnum");
    $cus_loc = odbc_result($result, "cusloc");
    
    echo $count++ . "<pre>", print_r($cus_num, true), "</pre>";
    echo "<pre>", print_r($cus_loc, true), "</pre>";
}

?>
