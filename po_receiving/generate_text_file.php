<?php
/* 
o-- Start --o
    Author: Rainier C. Barbacena
    Date: June 13, 2023
    Description: Sends AJAX request to the PHP script that generates and returns the text file content.
*/
ob_start();
session_start();
require_once("connect.php");

// Function to fetch data from the database table
function fetchTableData($query, $conn) {
    $result = $conn->query($query);
    return $result;
}

function generateTextFileRef() {

}

// Function to generate the text file content
function generateTextFilePOTransfers($query, $conn) {
    $tableData = fetchTableData($query, $conn);
    $content = '';
    
    while ($row = $tableData->fetch(PDO::FETCH_ASSOC)) {
        $content .= $row['refno'] . ',' . $row['Po'] . ',' . $row['Ponumb'] . ',' . $row['Inumber'] . ',' . $row['Pomum'] . ',' . $row['pompk'] . ',' . $row['Pomqty'] . ',' . $row['idescr'] . ',' . $row['Postor'] . ',' . $row['Povnum'] . ',' . $row['istdpk'] . ',' . $row['ivndpn'] . ',' . $row['Expqty'] . ',' . $row['Expday'] . ',' . $row['iupc'] . ',' . $row['rcvqty'] . ',' . $row['rcvqty_var'] . ',' . $row['expiredate'] . "\n";
    }
    
    return $content;
}

function generateTextFileTblUpc($query, $conn) {
    $tableData = fetchTableData($query, $conn);
    $content = '';
    
    // Adjust the formatting and data fields as needed
    while ($row = $tableData->fetch(PDO::FETCH_ASSOC)) {
        $content .= $row['refno'] . ',' . $row['inumbr'] . ',' . $row['iupc'] . "\n";
    }
    
    return $content;
}

function generateTextFileTblUser($query, $conn) {
    $tableData = fetchTableData($query, $conn);
    $content = '';
    
    // Adjust the formatting and data fields as needed
    while ($row = $tableData->fetch(PDO::FETCH_ASSOC)) {
        $content .= $row['user_EEno'] . ',' . $row['user_pass'] . ',' . $row['user_fname'] . ',' . $row['user_mname'] . ',' . $row['user_lname'] . ',' . $row['store_code'] . "\n";
    }
    
    return $content;
}

// Set the Store Code, UserID, and Max_ref values from your C# code
$StoreCode = $_SESSION['Storecode'];
$UserID = $_SESSION['user_id'];
$Max_ref = $_SESSION['refno'];

// Generate the text file content for the PO transfers table
$poTransfersQuery = "SELECT * FROM `po_transfers` WHERE refno = '" . $Max_ref . "'";
$poTransfersContent = generateTextFilePOTransfers($poTransfersQuery, $conn);

// Generate the text file content for the tblupc table
$tblUpcQuery = "SELECT * FROM `tblupc` WHERE refno = " . $Max_ref;
$tblUpcContent = generateTextFileTblUpc($tblUpcQuery, $conn);

// Generate the text file content for the user_tbl table
$tblUserQuery = "SELECT * FROM `user_tbl` WHERE Active = 1";
$tblUserContent = generateTextFileTblUser($tblUserQuery, $conn);

// Concatenate the content of both tables
$fileContent = "PO Transfers \n" . $poTransfersContent . "\nUPC \n" . $tblUpcContent . "\nUsers \n" . $tblUserContent;

// Set the appropriate headers to indicate that the response is a downloadable file
header('Content-Type: text/plain');
header('Content-Disposition: attachment; filename="PoReceivingMasterData.txt"');

// Output the file content
echo $fileContent;
/* 
    Author: Rainier C. Barbacena
    Date: June 13, 2023
    Description: Sends AJAX request to the PHP script that generates and returns the text file content.
o-- End --o
*/
?>
