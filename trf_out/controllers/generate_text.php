<?php
ob_start();
session_start();
include('../path.php');
include(ROOT_PATH . "/databases/connect_mysql.php");
$user_no = $_SESSION['employee_no'];        
$currentDate = date("Ymd");

function fetchTableData($query, $conn) {
    $result = $conn->query($query);
    return $result;
}

function generateTextTrfOutIupc($query, $conn) {
    $tableData = fetchTableData($query, $conn);
    $content = '';
    
    while ($row = $tableData->fetch_assoc()) {
        $content .= $row['inumbr'] . ',' . $row['iupc'] . "\n";
    }
    
    return $content;
}

function generateTextTrfOutPickUpc($query, $conn) {
    $tableData = fetchTableData($query, $conn);
    $content = '';
    
    while ($row = $tableData->fetch_assoc()) {
        $content .= $row['whmove'] . ',' . $row['inumbr'] . ',' . $row['whmumr'] . ',' . $row['whmvqt'] . ',' . $row['whmvqr'] . ',' . $row['iupc'] . ',' . $row['idescr'] . ',' . $row['strnum'] . ',' . $row['expqty'] . ',' . $row['rcvqty'] . ',' . $row['rexpday'] . "\n";
    }
    
    return $content;
}

function generateTextTrfOutUsers($query, $conn) {
    $tableData = fetchTableData($query, $conn);
    $content = '';
    
    while ($row = $tableData->fetch_assoc()) {
        $content .= $row['employee_no'] . ',' . $row['firstname'] . ',' . $row['lastname'] . ',' . $row['password'] . "\n";
    }
    
    return $content;
}

$pickUPCQuery = "SELECT p.* FROM pickupc AS p 
                JOIN mms_pick AS m ON p.inumbr = m.inumbr 
                JOIN audit_log AS a ON m.id = a.mms_pick_id 
                WHERE a.user_ee_no = $user_no AND a.is_scanned = 0;";
$pickUPCContent = generateTextTrfOutIupc($pickUPCQuery, $conn);

$mmsPickQuery = "SELECT m.* FROM mms_pick AS m
                JOIN audit_log AS a ON m.id = a.mms_pick_id
                WHERE a.user_ee_no = $user_no AND a.is_scanned = 0";
$pickMMSPickContent = generateTextTrfOutPickUpc($mmsPickQuery, $conn);

$usersQuery = "SELECT * FROM users WHERE employee_no = $user_no AND active = 1";
$usersContent = generateTextTrfOutUsers($usersQuery, $conn);

$fileContent = "Pick UPC \n" . $pickUPCContent . "\n\nMMS Pick \n" . $pickMMSPickContent . "\n\nUsers \n" . $usersContent;

// Set the appropriate headers to indicate that the response is a downloadable file
header('Content-Type: text/plain');
header("Content-Disposition: attachment; filename=TRFOUTDBMaster_{$currentDate}.txt");

echo $fileContent;
?>
