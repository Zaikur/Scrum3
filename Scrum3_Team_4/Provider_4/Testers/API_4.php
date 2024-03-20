<?php
/*
* Ethan Niehus
* 3/20/2024
* This file is an API that provides JSON responses for client requests for all tables.
***********************************************************************************************************************/
require_once '../class_lib/DBClass.php';

// Instantiate your database class
$db = new DBClass_Access();

header('Content-Type: application/json');

try {
    $method = $_SERVER['REQUEST_METHOD'];

    if ($method === 'GET') {
        $tableName = $_GET['tableName'] ?? '';
        if (!$tableName) {
            throw new Exception("Table name is required.");
        }

        $rows = $db->displayRecords($tableName);
        echo json_encode(['success' => true, 'data' => $rows]);
    }
    else if ($method === 'POST') {
        $action = $_POST['action'] ?? '';
        $tableName = $_POST['tableName'] ?? '';

        if (!$tableName) {
            throw new Exception("Table name is required.");
        }

        if ($action === 'add') {
            $record = $_POST['record'] ?? [];
            if (!$record) {
                throw new Exception("Record data is required.");
            }
            $db->insertRecord($tableName, $record);
            echo json_encode(['success' => true, 'message' => 'Record added successfully.']);
        
        } elseif ($action === 'update') {
            $record = $_POST['record'] ?? [];
            if (!$record) {
                throw new Exception("Record data and ID are required for update.");
            }
            $db->updateRecord($tableName, $record);
            echo json_encode(['success' => true, 'message' => 'Record updated successfully.']);
        
        } elseif ($action === 'delete') {
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
?>;
