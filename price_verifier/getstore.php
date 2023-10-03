<?php
if (isset($_REQUEST['check_store'])) {
    session_start();
    date_default_timezone_set('Asia/Manila');
    $store = $_REQUEST['check_store'];
    include("connect_mms.php");
    $odbc_statement = "SELEcT strnum,strnam FROM tblstr where strnum='$store'";
    $result = odbc_exec($conn_m, $odbc_statement);
    $ifhasrow = false;
    $fetched_strcode;
    $fetched_strloc;
    while (odbc_fetch_row($result)) {
        $ifhasrow = true;
        $fetched_strcode = odbc_result($result, "strnum");
        $fetched_strloc= odbc_result($result, "strnam");
       
    }
    if(!$ifhasrow){
        echo "no result";
        unset($_SESSION["price_storecode"]);
        unset($_SESSION["price_storeloc"]);
    }else{
        echo $fetched_strcode."-".$fetched_strloc;
        $_SESSION['price_storecode'] =  $fetched_strcode ;
        $_SESSION['price_storeloc'] = $fetched_strloc;
    }
}

?>