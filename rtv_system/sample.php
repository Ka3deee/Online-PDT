<?php
 include("connect_mms.php");
//to get get upc data
$upc = '2500030026080';
$odbc_statement = "Select A.INUMBR, A.IDESCR, C.ASNUM, C.ASNAME, C.ASRTCD, D.IBHAND FROM INVMST A LEFT JOIN INVUPC B On A.INUMBR = B.INUMBR LEFT JOIN APSUPP C On A.ASNUM = C.ASNUM LEFT JOIN INVBAL D ON A.INUMBR = D.INUMBR where B.IUPC = '$upc' AND D.ISTORE = 347";
$result = odbc_exec($conn_m, $odbc_statement);
while (odbc_fetch_row($result)) {
    $hassku = true;
    $sku=  odbc_result($result, "INUMBR");
    $iupc=  odbc_result($result, "IDESCR");
    echo $sku;
}
//try to fetch data,
?>