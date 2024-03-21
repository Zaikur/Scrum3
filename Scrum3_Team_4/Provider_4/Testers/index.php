<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tester</title>
   
</head>
<body>
    <h1>Tester</h1>
    
    <h2>All Records</h2>
    <?php
    // Include the DBClass file and instantiate the class
    require_once '../class_lib/DBClass.php';
    $db = new DBClass_Access();
    
    // Display all records for each table in the database
    $tables = ['users', 'posts', 'comments']; // Update with your table names
    foreach ($tables as $table) {
        $records = $db->displayRecords($table);
        echo "<h3>$table</h3>";
        echo "<ul>";
        foreach ($records as $record) {
            echo "<li>" . json_encode($record) . "</li>";
        }
        echo "</ul>";
    }
    ?>   
</body>
</html>
