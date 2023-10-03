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

// Function to generate the text file content
function generateTextFileRtvInvmst($query, $conn) {
    $tableData = fetchTableData($query, $conn);
    $content = '';
    
    while ($row = $tableData->fetch(PDO::FETCH_ASSOC)) {
        $content .= $row['inumbr'] . ',' . $row['idescr'] . ',' . $row['asnum'] . "\n";
    }
    
    return $content;
}

function generateTextFileRtvInvupc($query, $conn) {
    $tableData = fetchTableData($query, $conn);
    $content = '';
    
    // Adjust the formatting and data fields as needed
    while ($row = $tableData->fetch(PDO::FETCH_ASSOC)) {
        $content .= $row['iupc'] . ',' . $row['inumbr'] . "\n";
    }
    
    return $content;
}

function generateTextFileRtvUserAccess($query, $conn) {
    $tableData = fetchTableData($query, $conn);
    $content = '';
    
    // Adjust the formatting and data fields as needed
    while ($row = $tableData->fetch(PDO::FETCH_ASSOC)) {
        $content .= $row['id'] . ',' . $row['eenumb'] . ',' . $row['username'] . ',' . $row['password'] . ',' . $row['isallowed'] . "\n";
    }
    
    return $content;
}

function generateTextFileRtvClassCode($query, $conn) {
    $tableData = fetchTableData($query, $conn);
    $content = '';
    
    // Adjust the formatting and data fields as needed
    while ($row = $tableData->fetch(PDO::FETCH_ASSOC)) {
        $content .= $row['id'] . ',' . $row['code'] . ',' . $row['codedes'] . "\n";
    }
    
    return $content;
}


// Generate the text file content for the invmst table
$rtvInvmstQuery = "SELECT * FROM invmst";
$rtvInvmstContent = generateTextFileRtvInvmst($rtvInvmstQuery, $conn);

// Generate the text file content for the tblupc table
$rtvUpcQuery = "SELECT * FROM invupc";
$rtvUpcContent = generateTextFileRtvInvupc($rtvUpcQuery, $conn);

$rtvClassCodeQuery = "SELECT * FROM tbl_class_code";
$rtvClassCodeContent = generateTextFileRtvClassCode($rtvClassCodeQuery, $conn);

$rtvUserAccessQuery = "SELECT * FROM tbl_user_access";
$rtvUserAccessContent = generateTextFileRtvUserAccess($rtvUserAccessQuery, $conn);

// Concatenate the content of both tables
$fileContent = "Invmst \n" . $rtvInvmstContent . "\nInvupc \n" . $rtvUpcContent . "\nClass Code \n" . $rtvClassCodeContent . "\nUser Access \n" . $rtvUserAccessContent;

// Set the appropriate headers to indicate that the response is a downloadable file
header('Content-Type: text/plain');
header('Content-Disposition: attachment; filename="RtvSystemMasterData.txt"');

// Output the file content
echo $fileContent;
/* 
    Author: Rainier C. Barbacena
    Date: June 13, 2023
    Description: Sends AJAX request to the PHP script that generates and returns the text file content.
o-- End --o
*/
?>
