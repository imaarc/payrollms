<?php 
include('../db_connect.php');

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../phpmailer/src/Exception.php';
require '../phpmailer/src/PHPMailer.php';
require '../phpmailer/src/SMTP.php';

// Create a new PHPMailer instance
$mail = new PHPMailer(true);

$name = $_GET['name'];
$senderName = "admin";
$emailAddressSender = "admin@gmail.com";
//$emailAddressReceipient = $_GET['email'];
$emailAddressReceipient = $_GET['email'];


$id = $_GET['id'];
$value = $_GET['value'];
$monthly_payment = $_GET['monthly_payment'];
$start_date = $_GET['start_date'];
$payment_type = $_GET['payment_type'];
$employee_id = $_GET['employee_id'];
$subject = "Loan Application";
$body = "Hello Mr./Mrs. ".$name." your loan application has been ".$value.". Please see admin for more details." ;

try {
    // Server settings
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPAuth = true;
    $mail->Username = 'montanezrechell@gmail.com'; //to be changed
    $mail->Password = 'xwit cikq fjtt icso';
    $mail->SMTPSecure = 'ssl';
    $mail->Port = 465;

    // Set sender email dynamically based on the user
    $userEmail = $emailAddressSender; // Replace this with the user's email address
    $mail->setFrom($userEmail, $senderName);

    // Recipient
    $mail->addAddress($emailAddressReceipient, $name);

    // Content
    $mail->isHTML(true);
    $mail->Subject = $subject;
    $mail->Body = nl2br($body);

    // Send the email
    $mail->send();

    $sql = "UPDATE loanmanagement SET status = '$value' WHERE loanId = '$id'";
    $query = mysqli_query($conn, $sql);

    if ($query) {
        if ($value == "Approved") {
            // Insert into employee_deductions
            $sql1 = "INSERT INTO employee_deductions (employee_id, deduction_id, type, amount, effective_date, date_created) 
                     VALUES ('$employee_id', 4, '$payment_type', '$monthly_payment', '$start_date', NOW())";
            $query1 = mysqli_query($conn, $sql1);
            
            if ($query1) {
                $emp_ded_id = mysqli_insert_id($conn);
                
                $update = "UPDATE loanmanagement SET employee_deduction_id = '$emp_ded_id' WHERE loanId = '$id'";
                mysqli_query($conn, $update);
            } else {
                echo "Error inserting into employee_deductions: " . mysqli_error($conn);
            }
        }
        header("Location: ../index.php?page=loanmanagement");
    } else {
        echo "Error updating loanmanagement: " . mysqli_error($conn);
    }


} catch (Exception $e) {
    echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
}




// Update the loan status

mysqli_close($conn);
?>
