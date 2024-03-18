/*
* Quinton Nelson
* 3/17/2024
* This file is used to manage the records of the database. It is used to add, update, and delete records from the database.
* - dynamically updates the form placeholders based on the column names
* - fetches and displays column names for a specified table.
* - refreshes and displays records dynamically based on column names.
* - adds a record to the database.
* - updates a record in the database.
* - deletes a record from the database.
*
* This file used for all three databases in this assignment. It gets a table name from the URL used to get to the recordManager.html page
* and uses that table name to get the data it needs from the database to populate the page via AJAX calls.
***********************************************************************************************************************/

$(document).ready(function() {
    const apiUrl = '../Provider_4/Testers/record_handler.php';
    const tableName = getTableNameFromUrl(); // Global variable to store table name
    let tableColumns = []; // Global variable to store column names

    function getTableNameFromUrl() {
        const queryString = window.location.search;
        const urlParams = new URLSearchParams(queryString);
        return urlParams.get('tableName'); // Returns the value of "tableName" parameter or null if not found
    }

    

    // Function to fetch and display column names for a specified table
    function fetchAndDisplayColumnNames(tableName) {
        $.ajax({
            url: apiUrl,
            type: 'GET',
            data: { action: 'showColumns', tableName: tableName },
            success: function(response) {
                if (response.success) {
                    tableColumns = response.data; // Store column names globally
                    updateFormPlaceholders(tableColumns); // Update form placeholders based on column names
                    refreshRecords(tableName); // Refresh records with updated columns
                } else {
                    alert('Error fetching column names: ' + response.error);
                }
            },
            error: function() {
                alert('Error fetching column names from the server.');
            }
        });
    }

    // Dynamically update form placeholders based on column names
    function updateFormPlaceholders(columns) {
        columns.forEach((column, index) => {
            $(`#add-col${index + 1}, #update-col${index + 1}`).attr('placeholder', column);
        });
    }

    // Refresh and display records dynamically based on column names
    function refreshRecords(tableName) {
        $.ajax({
            url: apiUrl,
            type: 'GET',
            data: { tableName: tableName },
            success: function(response) {
                if (response.success) {
                    const records = response.data;
                    let html = '<table><tr>';
                    tableColumns.forEach(column => {
                        html += `<th>${column}</th>`; // Use the global columns for headers
                    });
                    html += '</tr>';
                    records.forEach(record => {
                        html += '<tr>';
                        tableColumns.forEach(column => {
                            html += `<td>${record[column] || ''}</td>`;
                        });
                        html += '</tr>';
                    });
                    html += '</table>';
                    $('#records').html(html);
                } else {
                    alert('Error fetching records: ' + response.error);
                }

            },
            error: function() {
                alert('Error fetching records from the server.');
            }
        });
    }


    $('#add-form').on('submit', function(e) {
        e.preventDefault();
    
        // Dynamically build the record object based on the input fields in the form
        let record = {};
        tableColumns.forEach((column, index) => {
            // Skip auto-increment columns like 'UserID'
            if(index > 0) {
                let value = $(`#add-col${index + 1}`).val();
                record[column] = value;
            }
        });
    
        $.ajax({
            url: apiUrl,
            type: 'POST',
            data: { action: 'add', tableName: tableName, record: record },
            success: function(response) {
                if (response.success) {
                    refreshRecords(tableName);
                    $('#update-form').trigger('reset'); // Clear the form after successful update
                    alert('Record added successfully.');
                    $('#add-form')[0].reset(); // Clear the form after successful insert
                } else {
                    alert('Error adding record: ' + response.error);
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                alert('Error adding record on the server. ' + textStatus);
            }
        });
    });
    

    // Update Record
    $('#update-form').on('submit', function(e) {
        e.preventDefault();
    
        let record = {};
        tableColumns.forEach((column, index) => {
            let value = $(`#update-col${index + 1}`).val();
            record[column] = value;
        });
    
        $.ajax({
            url: apiUrl,
            type: 'POST',
            data: { action: 'update', tableName: tableName, record: record },
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    refreshRecords(tableName);
                    $('#update-form').trigger('reset'); // Clear the form after successful update
                    alert('Record updated successfully.');
                } else {
                    alert('Error updating record: ' + response.error);
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                alert('Error updating record on the server: ' + textStatus);
            }
        });
    });
    
    
    

    // Delete Record
    $('#delete-form').on('submit', function(e) {
        e.preventDefault();
        const id = $('#delete-id').val();
        const primaryKeyColumn = tableColumns[0]; // Use the first column as the primary key

        $.ajax({
            url: apiUrl,
            type: 'POST',
            data: { action: 'delete', tableName: tableName, primaryKeyColumn: primaryKeyColumn, id: id },
            success: function(response) {
                if (response.success) {
                    $('#delete-form').trigger('reset'); // Clear the form after successful delete
                    alert('Record deleted successfully.');
                } else {
                    alert('Error deleting record: ' + response.error);
                }
            },
            error: function() {
                alert('Error deleting record on the server.');
            }
        });
        refreshRecords(tableName);
    });

    if (tableName !== null) {
        fetchAndDisplayColumnNames(tableName);
    } else {
        alert('Table name not found in URL.');
    }
});
