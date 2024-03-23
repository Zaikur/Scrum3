<?php
/***********************************************************
 * Ethan Niehus
 * 3/23/2024
 * This file will return values from tables in HTML elements
 ***********************************************************/

require_once '../class_lib/DBClass.php'; 


$dbAccess = new DBClass_Access();


$tables = $dbAccess->showTables();

// HTML code for displaying records
echo "<!DOCTYPE html>";
echo "<html lang='en'>";
echo "<head>";
echo "<meta charset='UTF-8'>";
echo "<meta name='viewport' content='width=device-width, initial-scale=1.0'>";
echo "<title>All Records</title>";
echo "</head>";
echo "<body>";
echo "<h1>All Records</h1>";


foreach ($tables as $table) {
    $tableName = $table['Tables_in_scrum3_db'];

 
    echo "<h2>Table: $tableName</h2>";

    
    $records = $dbAccess->displayRecords($tableName);

    
    if ($records) {

        
        echo "<table border='1'>";
        echo "<tr>";
        foreach ($records[0] as $key => $value) {
            echo "<th>$key</th>";
        }
        echo "</tr>";

        
        foreach ($records as $record) {
            echo "<tr>";
            foreach ($record as $value) {
                echo "<td>$value</td>";
            }
            echo "</tr>";
        }
        echo "</table>";
    } else {
        echo "<p>No records found in table: $tableName</p>";
    }
}

echo "</body>";
echo "</html>";