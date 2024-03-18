<?php
/*
* Quinton Nelson
* 3/17/2024
* This file is used to handle requests to the database using a given table name
***********************************************************************************************************************/
require_once '../class_lib/DBClass.php';




header('Content-Type: application/json');

// Instantiate your database class
$db = new DBClass_Access();

try {
    $method = $_SERVER['REQUEST_METHOD'];

    if ($method === 'GET') {
        $action = $_GET['action'] ?? null;
        $tableName = $_GET['tableName'] ?? '';
        if (!$tableName) {
            throw new Exception("Table name is required.");
        }

        switch ($action) {
            case 'showColumns':
                $columns = $db->showColumns($tableName);
                echo json_encode(['success' => true, 'data' => $columns]);
                break;

            default:
                $rows = $db->displayRecords($tableName);
                echo json_encode(['success' => true, 'data' => $rows]);
                break;
        }
    }
    else if ($method === 'POST') {
        // For adding, updating, or deleting records
        $action = $_POST['action'] ?? '';
        $tableName = $_POST['tableName'] ?? '';

        if (!$tableName) {
            throw new Exception("Table name is required.");
        }

        if ($action === 'add') {
            // Add a new record
            $record = $_POST['record'] ?? [];
            if (!$record) {
                throw new Exception("Record data is required.");
            }
            $db->insertRecord($tableName, $record);
            echo json_encode(['success' => true, 'message' => 'Record added successfully.']);
        
        } elseif ($action === 'update') {
            // Update an existing record
            $record = $_POST['record'] ?? [];
            if (!$record) {
                throw new Exception("Record data and ID are required for update.");
            }
            $db->updateRecord($tableName, $record);
            echo json_encode(['success' => true, 'message' => 'Record updated successfully.']);
        
        } elseif ($action === 'delete') {
            // Delete an existing record
            $id = $_POST['id'] ?? null;
            $primaryKeyColumn = $_POST['primaryKeyColumn'] ?? null;
            if (!$id || !$primaryKeyColumn) {
                throw new Exception("ID is required for deletion.");
            }
            $db->deleteRecord($tableName, $primaryKeyColumn, $id);
            echo json_encode(['success' => true, 'message' => 'Record deleted successfully.']);
        
        } else {
            throw new Exception("Invalid action specified.");
        }
    }
    else {
        echo json_encode(['error' => "Method $method not supported"]);
    }


} catch (Exception $e) {
    // Catch any exceptions and return the error
    http_response_code(400);
    echo json_encode(['error' => $e->getMessage()]);
}

