<?php

include ('../db_connect.php');

$id = $_GET['id'];

// Execute the SELECT query to get the employee deduction ID
$getEmpId = "SELECT * FROM loanmanagement WHERE loanId = '$id'";
$result = mysqli_query($conn, $getEmpId);

if ($result) {
    $getId = mysqli_fetch_assoc($result);
    $emp_ded_id = $getId['employee_deduction_id'];

    // Delete the loan record
    $delLoan = "DELETE FROM loanmanagement WHERE loanId = '$id'";
    $quLoan = mysqli_query($conn, $delLoan);

    if ($quLoan) {
        // If the loan deletion is successful and there's a valid employee deduction ID
        if ($emp_ded_id != 0) {
            $delDeduction = "DELETE FROM employee_deductions WHERE id = '$emp_ded_id'";
            $quDeduction = mysqli_query($conn, $delDeduction);
            
            if (!$quDeduction) {
                echo "Error deleting employee deduction: " . mysqli_error($conn);
            }
        }
        // Redirect to loan management page
        header("Location: ../index.php?page=loanmanagement");
    } else {
        echo "Error deleting loan record: " . mysqli_error($conn);
    }
} else {
    echo "Error fetching loan record: " . mysqli_error($conn);
}

mysqli_close($conn);
?>
