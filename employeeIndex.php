<?php include 'db_connect.php'; ?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Employee Attendance - PUMV</title>
	<link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
	<script>
		// Function to update the button text based on the current time
		function updateButtonText() {
			const now = new Date();
			const hours = now.getHours();
			const minutes = now.getMinutes();
			const submitButton = document.getElementById("submitButton");

			// Determine the button text based on the time range
			  if (hours < 10) {
        submitButton.textContent = "Time-in AM";
        submitButton.value = "Time-in AM";
		    } else if (hours === 10 || (hours === 11 || (hours === 12 && minutes <= 30))) {
		        submitButton.textContent = "Timeout AM";
		        submitButton.value = "Timeout AM";
		    } else if (hours === 12 && minutes >= 31 || (hours === 13 && minutes <= 30)) {
		        submitButton.textContent = "Time-in PM";
		        submitButton.value = "Time-in PM";
		    } else if ((hours >= 13 && minutes >= 31) || (hours < 17 || (hours === 17 && minutes <= 30))) {
		        submitButton.textContent = "Timeout PM";
		        submitButton.value = "Timeout PM";
		    } else {
		        submitButton.textContent = "Submit";
		        submitButton.value = "Submit";
		    }
		}

		// Function to display the current time
		function displayTime() {
			const now = new Date();
			const timeDisplay = document.getElementById("timeDisplay");
			timeDisplay.textContent = now.toLocaleTimeString();
		}

		// Set intervals to update the button text and current time display every second
		setInterval(() => {
			updateButtonText();
			displayTime();
		}, 1000);

		// Run initially when the page loads
		window.onload = () => {
			updateButtonText();
			displayTime();
		};
	</script>
</head>
<body style="background: #01889F;">

<div class="card align w-50 mx-auto" style="margin-top: 150px;">
  <div class="card-body">

  	<h4 class="text-center">PUMV Cooperative Management System</h4>
  	<p class="text-center">Employee Attendance</p>
  	<p class="text-center" id="timeDisplay"></p> <!-- Display current time -->
    <form method="POST" action="php/attendanceEmployee.php">
	  <div class="mb-3">
	    <label for="exampleInputEmail1" class="form-label">Employee Code</label>
	    <input type="text" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" name="empCode">
	  </div>
	  <div class="w-50 mx-auto">
	  	<button type="submit" id="submitButton" class="btn btn-primary w-100" name="btnValue">Submit</button>
	  </div>
	</form>
  </div>
</div>

<?php
if (isset($_GET['msg'])) {

    $msg = (int)$_GET['msg']; // Convert the value to an integer

    if ($msg === 1) {
    	if (isset($_GET['empId'])) {
    		$empId = $_GET['empId'];

    		$sel = "SELECT * FROM employee WHERE id = '$empId'";
				$selQuery = mysqli_query($conn, $sel)->fetch_assoc();

				$fullName = $selQuery['firstname']." ".$selQuery['lastname'];

				 echo "<script>alert('Success logged Mr./Ms. ".$fullName." ');</script>";
    	}
       
    } else if ($msg === 2) {
        echo "<script>alert('Employee not found!');</script>";
    } else if ($msg === 3) {
        echo "<script>alert('You\'ve already logged this time');</script>";
    }
}


?>


</body>
</html>
