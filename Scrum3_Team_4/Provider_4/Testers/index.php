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
    
    <h2>Test API Functions</h2>
    <form method="post" action="../Provider_4/Testers/API_4.php">
        <h3>Add Record</h3>
        <input type="hidden" name="action" value="add">
        <input type="hidden" name="tableName" value="users">
        <label for="add-record">Record Data:</label>
        <input type="text" id="add-record" name="record">
        <button type="submit">Add Record</button>
    </form>
    
    <form method="post" action="../Provider_4/Testers/API_4.php">
        <h3>Update Record</h3>
        <input type="hidden" name="action" value="update">
        <input type="hidden" name="tableName" value="users">
        <label for="update-id">Record ID:</label>
        <input type="text" id="update-id" name="id">
        <label for="update-record">New Record Data:</label>
        <input type="text" id="update-record" name="record">
        <button type="submit">Update Record</button>
    </form>
    
    <form method="post" action="../Provider_4/Testers/API_4.php">
        <h3>Delete Record</h3>
        <input type="hidden" name="action" value="delete">
        <input type="hidden" name="tableName" value="users">
        <label for="delete-id">Record ID:</label>
        <input type="text" id="delete-id" name="id">
        <button type="submit">Delete Record</button>
    </form>

   
</body>
</html>
