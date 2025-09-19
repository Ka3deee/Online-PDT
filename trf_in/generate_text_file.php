<?php
/* 
o-- Start --o
    Author: Rainier C. Barbacena
    Date: June 19, 2023
    Description: Sends AJAX request to the PHP script that generates and returns the text file content.
*/
ob_start();
session_start();

$local_servername = "localhost";
$local_username = "root";
$local_password = "";
$local_dbname = "trfin_db";
$currentDate = date("Ymd");

try {
  $conn = new PDO("mysql:host=$local_servername;dbname=$local_dbname", $local_username, $local_password);
  $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
  echo "Connection failed: " . $e->getMessage();
}
$textareaValue = $_GET['trfbchlist'];
$valuesArray = explode("\n", $textareaValue);
$valuesArray = array_map('trim', $valuesArray);
$valuesArray = array_map(function($value) {
    return "'$value'";
}, $valuesArray);
$commaSeparatedString = implode(',', $valuesArray);
// Function to fetch data from the database table
function fetchTableData($query, $conn) {
    $result = $conn->query($query);
    return $result;
}

// Function to generate the text file content
function generateTextFileTransfers($query, $conn) {
    $tableData = fetchTableData($query, $conn);
    $content = '';
    
    while ($row = $tableData->fetch(PDO::FETCH_ASSOC)) {
        $content .= $row['id'] . ',' . $row['trfbch'] . ',' . $row['inumbr'] . ',' . $row['idescr'] . ',' . $row['trfshp'] . ',' . $row['istdpk'] . ',' . $row['rcvqty'] . ',' . $row['expqty'] . "\n";
    }
    
    return $content;
}

function generateTextFileTblUpc($query, $conn) {
    $tableData = fetchTableData($query, $conn);
    $content = '';
    
    // Adjust the formatting and data fields as needed
    while ($row = $tableData->fetch(PDO::FETCH_ASSOC)) {
        $content .= $row['id'] . ',' . $row['inumbr'] . ',' . $row['iupc'] . "\n";
    }
    
    return $content;
}

// Set the Store Code, UserID, and Max_ref values from your C# code
$StoreCode = $_SESSION['strcode'];

// Generate the text file content for the PO transfers table
$transfersQuery = "SELECT * FROM " . $StoreCode . "_received_batch_trf_tbl WHERE `trfbch` IN ($commaSeparatedString)";
$transfersContent = generateTextFileTransfers($transfersQuery, $conn);

// Generate the text file content for the tblupc table
$tblUpcQuery = "SELECT * FROM " . $StoreCode . "_iupc_tbl WHERE id IN (SELECT MAX(id) FROM " . $StoreCode . "_iupc_tbl WHERE inumbr IN (SELECT inumbr FROM " . $StoreCode . "_received_batch_trf_tbl WHERE `trfbch` IN ($commaSeparatedString)) GROUP BY inumbr)";

$tblUpcContent = generateTextFileTblUpc($tblUpcQuery, $conn);

// Concatenate the content of both tables
$fileContent = "Batch Transfer \n" . $transfersContent . "\nIUPC \n" . $tblUpcContent;

// Set the appropriate headers to indicate that the response is a downloadable file
header('Content-Type: text/plain');
header("Content-Disposition: attachment; filename=TRFINDBMaster_{$currentDate}.txt");

// Output the file content
echo $fileContent;

/* 
    Author: Rainier C. Barbacena
    Date: June 19, 2023
    Description: Sends AJAX request to the PHP script that generates and returns the text file content.
o-- End --o
*/
?>
