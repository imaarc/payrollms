<?php
include("db_connect.php");

$date = date("Y-m-d");

$select = "SELECT * FROM loanmanagement";
$queryselect  = mysqli_query($conn, $select);

if ($queryselect) {
    while ($querys = mysqli_fetch_assoc($queryselect)) {
        if ($querys['end_date'] == $date) {
            $loanId = $querys['loanId'];
            $sql = "UPDATE loanmanagement SET status = 'Completed' WHERE loanId = '$loanId'";
            $updateQuery = mysqli_query($conn, $sql);

            if ($updateQuery) {
                // Delete the corresponding employee deduction record
                $emp_ded_id = $querys['employee_deduction_id'];
                if ($emp_ded_id) {
                    $del = "DELETE FROM employee_deductions WHERE id = '$emp_ded_id'";
                    $deleteQuery = mysqli_query($conn, $del);
                    if (!$deleteQuery) {
                        echo "Error deleting employee deduction: " . mysqli_error($conn);
                    }
                }
            } else {
                echo "Error updating loan status: " . mysqli_error($conn);
            }
        }
    }
} else {
    echo "Error fetching loan records: " . mysqli_error($conn);
}

mysqli_close($conn);
?>
