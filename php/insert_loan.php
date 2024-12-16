<?php 
include('../db_connect.php');

$id = $_POST['employee_id'];
$amount = $_POST['loan_amount'];
$interest_rate = $_POST['interest_rate'];
$loan_term = $_POST['loan_term'];
$start_date = $_POST['start_date'];
$type = $_POST['type'];

$exp = explode('|', $amount);
$loanTypeId = $exp[0];
$loan_amount= $exp[1];

function calculateMonthlyPayment($loanAmount, $monthlyInterestRate, $loanTermMonths) {
    $monthlyInterestRateDecimal = $monthlyInterestRate / 100;
    $monthlyPayment = ($loanAmount * $monthlyInterestRateDecimal * pow(1 + $monthlyInterestRateDecimal, $loanTermMonths)) / 
                      (pow(1 + $monthlyInterestRateDecimal, $loanTermMonths) - 1);
    return $monthlyPayment;
}

if ($type == 1) {
    $monthly_payment = calculateMonthlyPayment($loan_amount, $interest_rate, $loan_term);
} else {
    $monthly_payment = calculateMonthlyPayment($loan_amount, $interest_rate, $loan_term) / 2;
}

// Calculate the end date
$startDateObj = new DateTime($start_date);
$startDateObj->modify("+$loan_term months");
$end_date = $startDateObj->format('Y-m-d');

$sql = "INSERT INTO loanmanagement (employeeId, loan_amount, interest, loan_term, start_date, end_date, status, monthly_deduction, payment_type, loanTypeId) 
        VALUES ('$id', '$loan_amount', '$interest_rate', '$loan_term', '$start_date', '$end_date', 'Pending', '$monthly_payment', '$type','$loanTypeId')";

$query = mysqli_query($conn, $sql);

if ($query) {
    header("Location: ../index.php?page=loanmanagement");
} else {
    echo "Error: " . mysqli_error($conn);
}

mysqli_close($conn);
?>
