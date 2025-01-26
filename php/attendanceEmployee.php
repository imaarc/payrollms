<?php

include('../db_connect.php');
date_default_timezone_set('Asia/Manila');

// Get the form values
$empCode = $_POST['empCode'];
$type = $_POST['btnValue'];

// Determine logType based on the button value
if ($type === "Time-in AM") {
    $logType = 1;
} else if ($type === "Timeout AM") {
    $logType = 2;
} else if ($type === "Time-in PM") {
    $logType = 3;
} else if ($type === "Timeout PM") {
    $logType = 4;
}

// Fetch the employee ID using the employee code
$sel = "SELECT * FROM employee WHERE employee_no = '$empCode'";
$selQuery = mysqli_query($conn, $sel);

// Check if the query returns a result
if ($selQuery && mysqli_num_rows($selQuery) > 0) {
    $selQueryResult = $selQuery->fetch_assoc();
    $empId = $selQueryResult['id'];
    $currentDate = date('Y-m-d'); // Format: YYYY-MM-DD
    $currentDateTime = date('Y-m-d H:i:s'); // Format: YYYY-MM-DD HH:MM:SS

    // Check if the employee already logged this log type today
    $checkLogQuery = "
        SELECT * FROM attendance 
        WHERE employee_id = '$empId' 
        AND log_type = '$logType' 
        AND DATE(datetime_log) = '$currentDate'
    ";
    $checkLogResult = mysqli_query($conn, $checkLogQuery);

    if ($checkLogResult && mysqli_num_rows($checkLogResult) > 0) {
        // Employee already logged this action today
        header("Location:../employeeindex.php?msg=3");
        exit;
    } else {
        // Insert the new attendance record
        $sql = "INSERT INTO attendance (employee_id, log_type, datetime_log, date_updated) 
                VALUES ('$empId', '$logType', '$currentDateTime', '$currentDateTime')";
        $insertQuery = mysqli_query($conn, $sql);

        if ($insertQuery) {
            header("Location:../employeeindex.php?msg=1&&empId=$empId");
        } else {
            echo "Error: " . mysqli_error($conn);
        }
    }
} else {
    // Employee not found
    header("Location:../employeeindex.php?msg=2");
}

?>
