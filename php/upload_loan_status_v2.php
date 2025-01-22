<?php
include('../db_connect.php');

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../phpmailer/src/Exception.php';
require '../phpmailer/src/PHPMailer.php';
require '../phpmailer/src/SMTP.php';

function sendEmail($toEmail, $toName, $subject, $body, $fromEmail, $fromName) {
    $mail = new PHPMailer(true);
    try {
        // Server settings
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'montanezrechell@gmail.com'; //to be changed
        $mail->Password = 'oyfa rric aaue yvir';
        $mail->SMTPSecure = 'ssl';
        $mail->Port = 465;

        // Set sender and recipient
        $mail->setFrom($fromEmail, $fromName);
        $mail->addAddress($toEmail, $toName);

        // Email content
        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body = nl2br($body); 

        // Send email
        $mail->send();
        return true;
    } catch (Exception $e) {
        error_log("Mailer Error: {$mail->ErrorInfo}");
        return false;
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = mysqli_real_escape_string($conn, $_POST['id']);
    $value = mysqli_real_escape_string($conn, $_POST['value']);
    $monthly_payment = mysqli_real_escape_string($conn, $_POST['monthly_payment']);
    $start_date = mysqli_real_escape_string($conn, $_POST['start_date']);
    $payment_type = mysqli_real_escape_string($conn, $_POST['payment_type']);
    $employee_id = mysqli_real_escape_string($conn, $_POST['employee_id']);
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $emailAddressReceipient = mysqli_real_escape_string($conn, $_POST['email']);
    $rejection_reason =  mysqli_real_escape_string($conn, $_POST['rejection_reason']);

    $subject = "Loan Application";
    $body = "Hello Mr./Mrs. {$name}, your loan application has been {$value}.Reason: {$rejection_reason}. Please see admin for more details.";

    // Update loan status in the database
    $sql = "UPDATE loanmanagement SET status = ?, reason = ? WHERE loanId = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('ssi', $value,$rejection_reason, $id);

    if ($stmt->execute()) {
        if ($value === "Approved") {
            // Insert into employee_deductions
            $sql1 = "INSERT INTO employee_deductions (employee_id, deduction_id, type, amount, effective_date, date_created) 
                     VALUES (?, 4, ?, ?, ?, NOW())";
            $stmt1 = $conn->prepare($sql1);
            $stmt1->bind_param('isds', $employee_id, $payment_type, $monthly_payment, $start_date);

            if ($stmt1->execute()) {
                $emp_ded_id = $conn->insert_id;

                $update = "UPDATE loanmanagement SET employee_deduction_id = ? WHERE loanId = ?";
                $stmt2 = $conn->prepare($update);
                $stmt2->bind_param('ii', $emp_ded_id, $id);
                $stmt2->execute();
            } else {
                error_log("Error inserting into employee_deductions: " . $conn->error);
            }
        }

        // Send email notification
        if (!sendEmail($emailAddressReceipient, $name, $subject, $body, 'admin@gmail.com', 'Admin')) {
            error_log("Error sending email to {$emailAddressReceipient}");
        }

        header("Location: ../index.php?page=loanmanagement");
        exit;
    } else {
        error_log("Error updating loanmanagement: " . $conn->error);
        echo "Error updating loanmanagement.";
    }

    $stmt->close();
}

mysqli_close($conn);
?>
