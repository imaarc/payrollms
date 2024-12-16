<?php

include ('../db_connect.php');

$name = $_GET['loanName'];
$amount = $_GET['amount'];

$getEmpId = "INSERT INTO loantypes (name, amount) VALUES('$name','$amount')";
$result = mysqli_query($conn, $getEmpId);


if ($result) {
    header("Location: ../index.php?page=loanType");
} else {
    echo "Error: " . mysqli_error($conn);
}

mysqli_close($conn);
?>
