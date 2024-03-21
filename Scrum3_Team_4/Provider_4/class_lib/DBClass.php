<?php
/*
* Quinton Nelson
* 3/17/2024
* This class handles access to the Scrum3_db database
* Contains functions used to create, read, update, and delete (CRUD) records in the database as well as...
* - functions to display all the databases and tables in the database server
* - functions to display all the records in a table and a single record in a table
* - a function to indirectly expose the database name to the API
* - a function to connect to the database
*
***********************************************************************************************************************/

class DBClass_Access
{
    private static $conn;
    private static $hostName = "localhost";
    private static $databaseName = "scrum3_db";
    private static $userName = "root";
    private static $password = "";


    // This private function is used to connect to the database
    // Throws an exception if the connection to the database failed
    private function connectTo()
    {
        self::$conn = new mysqli(self::$hostName, self::$userName, self::$password, self::$databaseName);
        if (self::$conn->connect_error) {
            throw new Exception("Connection failed: " . self::$conn->connect_error);
        }
    }

    // This function is used to create a record in the database
    // Accepts the name of the table as a string
    // Accepts the record to insert as an associative array
    // Builds an SQL statement to insert the record into the table based on the column names and values (keys and values) in the record
    // Throws an exception if the connection to the database failed, and one if the query failed
    // Returns true if the record was inserted successfully
    public function insertRecord($tableName, $record) {
        self::connectTo();
        if (self::$conn->connect_error) {
            throw new Exception("Failed to connect to the database: " . self::$conn->connect_error);
        }
    
        // Prepare column names and values for the SQL statement
        $columns = array_keys($record);
        $values = array_values($record);
    
        // Escape all values to prevent SQL injection
        $escaped_values = array_map(function($value) {
            return self::$conn->real_escape_string($value);
        }, $values);
    
        // Build the SQL statement with column names and escaped values
        $query = sprintf(
            "INSERT INTO %s (%s) VALUES ('%s')",
            $tableName,
            implode(", ", $columns),
            implode("', '", $escaped_values)
        );
    
        if (!mysqli_query(self::$conn, $query)) {
            self::$conn->close();
            throw new Exception("Failed to insert record into $tableName: " . self::$conn->error);
        }
    
        self::$conn->close();
        return true; // Indicating success
    }

    // This function is used to update a record in the database
    // Accepts the name of the table to update the record in, and the record to update as an associative array (First key:value pair MUST be the primary key and value of the record)
    // Builds an SQL statement to update the record in the table based on the column names and values (keys and values) in the record
    // Throws an exception if the connection to the database failed or if the query failed
    // Returns true if the record was updated successfully
    public function updateRecord($tableName, $record) {
        self::connectTo();
        if (self::$conn->connect_error) {
            throw new Exception("Failed to connect to the database: " . self::$conn->connect_error);
        }
    
        // Extract and remove the primary key:value pair from the record
        $primaryKeyColumn = array_key_first($record);
        $primaryKeyValue = $record[$primaryKeyColumn];
        unset($record[$primaryKeyColumn]);

    
        $query = "UPDATE " . $tableName . " SET ";
        $updates = [];
        foreach ($record as $key => $value) {
            // Skip empty values
            if ($value !== '') {
                $value = self::$conn->real_escape_string($value);
                $updates[] = "$key = '$value'";
            }
        }
        $query .= implode(", ", $updates);
        $query .= " WHERE " . $primaryKeyColumn . " = '" . self::$conn->real_escape_string($primaryKeyValue) . "'";
    
        if (!mysqli_query(self::$conn, $query)) {
            self::$conn->close();
            throw new Exception("Failed to update record in $tableName: " . self::$conn->error);
        }
    
        self::$conn->close();
        return true;
    }    

    // This function is used to delete a record from the database
    // Accepts the name of the table to delete the record from and the ID of the record to delete as a string
    // Throws an exception if the connection to the database failed or if the query failed
    // Returns true if the record was deleted successfully
    public function deleteRecord($tableName, $primaryKeyColumn, $id) {
        self::connectTo();
        if (self::$conn->connect_error) {
            self::$conn->close();
            throw new Exception("Failed to connect to the database: " . self::$conn->connect_error);
        }
    
        // Initialize the DELETE query
        $query = "DELETE FROM " . $tableName;
    
        // Append the WHERE clause to target the record for deletion
        $query .= " WHERE " . $primaryKeyColumn . " = '" . self::$conn->real_escape_string($id) . "'";
    
        // Execute the DELETE query
        if (!mysqli_query(self::$conn, $query)) {
            self::$conn->close();
            throw new Exception("Failed to delete record from $tableName: " . self::$conn->error);
        }
    
        self::$conn->close();
        return true; // Indicating success
    }
    

    // This function is used to show all the databases in the database server
    // Throws an exception if the connection to the database failed or if the query failed
    // Returns the result of the query as an associative array
    public function showDatabases() {
        self::connectTo();
        if (self::$conn->connect_error) {
            throw new Exception("Failed to connect to the database: " . self::$conn->connect_error);
        }
    
        $query = "SHOW DATABASES";
        $result = mysqli_query(self::$conn, $query);
        if (!$result) {
            self::$conn->close();
            throw new Exception("Failed to show databases: " . self::$conn->error);
        }
    
        $databases = mysqli_fetch_all($result, MYSQLI_ASSOC);
        self::$conn->close();
        return $databases;
    }
    

    // This function is used to show all the tables in the database
    // Throws an exception if the connection to the database failed or if the query failed
    // Returns the result of the query as an associative array
    public function showTables() {
        self::connectTo();
        if (self::$conn->connect_error) {
            throw new Exception("Failed to connect to the database: " . self::$conn->connect_error);
        }
    
        $query = "SHOW TABLES FROM " . self::$databaseName;
        $result = mysqli_query(self::$conn, $query);
        if (!$result) {
            self::$conn->close();
            throw new Exception("Failed to show tables from " . self::$databaseName . ": " . self::$conn->error);
        }
    
        $tables = mysqli_fetch_all($result, MYSQLI_ASSOC);
        self::$conn->close();
        return $tables;
    }

    // This function is used to show all the columns in a table
    // Accepts the name of the table to show the columns from as a string
    // Throws an exception if the connection to the database failed or if the query failed
    // Returns the result of the query as an associative array
    public function showColumns($tableName) {
        self::connectTo();
        if (self::$conn->connect_error) {
            throw new Exception("Failed to connect to the database: " . self::$conn->connect_error);
        }
    
        $query = "SHOW COLUMNS FROM " . $tableName;
        $result = mysqli_query(self::$conn, $query);
        if (!$result) {
            self::$conn->close();
            throw new Exception("Failed to show columns from " . $tableName . ": " . self::$conn->error);
        }
    
        $columns = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $columns[] = $row['Field'];
        }
        self::$conn->close();
        return $columns;
    }
    
    

    // This function is used to display all the records in a table
    // Accepts the name of the table to display the records from as a string
    // Throws an exception if the connection to the database failed or if the query failed
    // Returns the result of the query as an associative array
    public function displayRecords($tableName){
        self::connectTo();
        if (self::$conn->connect_error) {
            throw new Exception("Failed to connect to the database: " . self::$conn->connect_error);
        }
    
        $query = "SELECT * FROM " . $tableName;
        $result = mysqli_query(self::$conn, $query);
        if (!$result) {
            self::$conn->close();
            throw new Exception("Failed to display records from " . $tableName . ": " . self::$conn->error);
        }
    
        $records = mysqli_fetch_all($result, MYSQLI_ASSOC);
        self::$conn->close();
        return $records;
    }
    

    // This function is used to display a single record in a given table
    // Accepts the name of the table to display the record from and the ID of the record to display
    // Throws an exception if the connection to the database failed or if the query failed
    // Returns the result of the query as an associative array, or null if the record was not found
    public function displayRecord($tableName, $id){
        self::connectTo();
        if (self::$conn->connect_error) {
            throw new Exception("Failed to connect to the database: " . self::$conn->connect_error);
        }
    
        $query = "SELECT * FROM " . $tableName . " WHERE id = " . $id;
        $result = mysqli_query(self::$conn, $query);
        if (!$result) {
            self::$conn->close();
            throw new Exception("Failed to display the record from " . $tableName . " with ID " . $id . ": " . self::$conn->error);
        }
    
        $record = mysqli_fetch_assoc($result); // Fetch a single row as an associative array
        self::$conn->close();
        return $record ? $record : null; // Return the record or null if not found
    }
    
    
}