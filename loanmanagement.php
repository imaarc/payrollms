<?php include('db_connect.php') ?>
<?php $type = $_SESSION['login_type']; ?>
		<div class="container-fluid " >
			<div class="col-lg-12">
				
				<br />
				<br />
				<div class="card">
					<div class="card-header">
						<span><b>Loan Management - Pending and Approved</b></span>
						<button class="btn btn-primary btn-sm btn-block col-md-3 float-right" data-toggle="modal" data-target="#addLoanButton"><span class="fa fa-plus"></span>Add Loan</button>
					</div>
					<div class="card-body">
						<table id="table" class="table table-bordered table-striped">
							
							<thead>
								<tr>
									<th>Employee No</th>
									<th>Name</th>
									<th>Loan Amount</th>
									<th>Loan Type</th>
									<th>Interest</th>
									<th>Loan Term</th>
									<th>Deductions</th>
									<th>Remaining terms</th>
									<th>Status</th>
									<th>Reason</th>
									<th>Payment Type</th>
									<th>Start Date</th>
									<th>End Date</th>
									<?php if ($type == 1) { ?>
										<th>Actions</th>
									<?php } ?>
									
								</tr>
							</thead>
							<tbody>
								

									<?php
									$sql = "select * from loanmanagement lm join employee em on lm.employeeId = em.id left join loanTypes lt on lm.loanTypeId = lt.loanTypeId WHERE status != 'Rejected'";
									$query = mysqli_query($conn, $sql);

									while($row = $query->fetch_assoc()){
										$loanId 
									?>
								<tr>
									<td><?=$row['employee_no']?></td>
									<td><?=$row['firstname']?> <?=$row['lastname']?></td>
									<td><?=$row['loan_amount']?></td>
									<td><?=$row['name']?></td>
									<td><?=$row['interest']?>%</td>
									<td><?=$row['loan_term']?> months</td>
									<td><?=$row['monthly_deduction']?></td>


									<?php if ($row['status'] == "Approved") { 
									$start = $row['start_date']; // Example: '12-19-2025'
									$end = $row['end_date'];     // Example: '10-19-2025'

									// Convert strings to DateTime objects
									$startDate = DateTime::createFromFormat('Y-m-d', $start);
									$endDate = DateTime::createFromFormat('Y-m-d', $end);

										if ($startDate && $endDate) {
										    // Get the difference in months
										    $interval = $startDate->diff($endDate);

										    // Calculate total months (difference in years and months)
										    $totalMonths = ($interval->y * 12) + $interval->m;

										    // Calculate the current month position
										    $currentMonthPosition = ($interval->invert === 1) ? 0 : 1;

										    // Display as "currentMonth/totalMonths"
										    $remainingMonths = $currentMonthPosition . '/' . $totalMonths;

										    echo "<td>{$remainingMonths}</td>";
										} else {
										    echo "<td>Invalid date format</td>";
										}
									}else{
										echo "<td>N/A</td>";
									}
									
									?>


									<td>

										<?php if ($row['status'] == "Approved") { ?>
											<div class="badge badge-primary text-wrap" style="width: 6rem;">
											  <?=$row['status']?> 
											</div>
									<?php } elseif ($row['status'] == "Rejected") { ?>
											<div class="badge badge-danger text-wrap" style="width: 6rem;">
											  <?=$row['status']?> 
											</div>
									<?php } elseif ($row['status'] == "Completed") { ?>
											<div class="badge badge-success text-wrap" style="width: 6rem;">
											  <?=$row['status']?> 
											</div>
									<?php	}else{
										echo $row['status'];
									} ?>

									<?php if ($type == 1) { ?>

										<?php if ($row['status'] == "Pending") { ?>
										<a href="php/update_loan_status.php?id=<?=$row['loanId']?>&&value=Approved&&type=<?=$row['payment_type']?>&&monthly_payment=<?=$row['monthly_deduction']?>&&start_date=<?=$row['start_date']?>&&payment_type=<?=$row['payment_type']?>&&employee_id=<?=$row['employeeId']?>&&name=<?=$row['lastname']?>&&email=<?=$row['email']?>" class="btn btn-sm btn-primary">Approve</a>

										<!-- <a href="php/update_loan_status.php?id=<?=$row['loanId']?>&&value=Rejected&&type=<?=$row['payment_type']?>&&monthly_payment=<?=$row['monthly_deduction']?>&&start_date=<?=$row['start_date']?>&&payment_type=<?=$row['payment_type']?>&&employee_id=<?=$row['employeeId']?>&&name=<?=$row['lastname']?>&&email=<?=$row['email']?>" class="btn btn-sm btn-danger">Reject</a> -->

										<a href="#" 
										   class="btn btn-sm btn-danger reject-loan-btn" 
										   data-loan-id="<?= $row['loanId'] ?>"
										   data-value="Rejected"
										   data-type="<?= $row['payment_type'] ?>"
										   data-monthly-payment="<?= $row['monthly_deduction'] ?>"
										   data-start-date="<?= $row['start_date'] ?>"
										   data-payment-type="<?= $row['payment_type'] ?>"
										   data-employee-id="<?= $row['employeeId'] ?>"
										   data-name="<?= $row['lastname'] ?>"
										   data-email="<?= $row['email'] ?>"
										   data-toggle="modal" 
										   data-target="#rejectLoanModal">
										   Reject
										</a>

									<?php }?>
										
										
									<?php } ?>
										
									
									</td>
									<td>
										<?php if ($row['status'] == "Rejected") 
										{ echo $row['reason']; }
										else if($row['status'] == "Pending"){ echo "Pending";}
										else { echo "Success"; }
										?>
									 	
									 </td>
									<?php
									if($row['payment_type'] == 1){ ?>
										<td>Monthly</td>
									<?php } else{?>
										<td>Semi-Monthly</td>
									<?php }
									?>
									<td><?=$row['start_date']?></td>
									<td><?=$row['end_date']?></td>
									<?php if ($type == 1) { ?>
										<td><a href="php/delete_loan.php?id=<?=$row['loanId']?>" class="btn btn-danger"><i class="fa fa-trash"></i></a></td>
									<?php } ?>
									

								</tr>
									<?php } ?>
									
								
							</tbody>
						</table>
					</div>
				</div>

				<div class="card mt-5">
					<div class="card-header">
						<span><b>Loan Management - Rejected</b></span>
					</div>
					<div class="card-body">
						<table id="table" class="table table-bordered table-striped">
							
							<thead>
								<tr>
									<th>Employee No</th>
									<th>Name</th>
									<th>Loan Amount</th>
									<th>Loan Type</th>
									<th>Interest</th>
									<th>Loan Term</th>
									<th>Deductions</th>
									<th>Status</th>
									<th>Reason</th>
									<th>Payment Type</th>
									<th>Start Date</th>
									<th>End Date</th>
									
									<?php if ($type == 1) { ?>
										<th>Actions</th>
									<?php } ?>
								</tr>
							</thead>
							<tbody>
								

									<?php
									$sql = "select * from loanmanagement lm join employee em on lm.employeeId = em.id left join loanTypes lt on lm.loanTypeId = lt.loanTypeId WHERE status = 'Rejected' ";
									$query = mysqli_query($conn, $sql);

									while($row = $query->fetch_assoc()){
										$loanId 
									?>
								<tr>
									<td><?=$row['employee_no']?></td>
									<td><?=$row['firstname']?> <?=$row['lastname']?></td>
									<td><?=$row['loan_amount']?></td>
									<td><?=$row['name']?></td>
									<td><?=$row['interest']?>%</td>
									<td><?=$row['loan_term']?> months</td>
									<td><?=$row['monthly_deduction']?></td>

									<td>

										<?php if ($row['status'] == "Approved") { ?>
											<div class="badge badge-primary text-wrap" style="width: 6rem;">
											  <?=$row['status']?> 
											</div>
									<?php } elseif ($row['status'] == "Rejected") { ?>
											<div class="badge badge-danger text-wrap" style="width: 6rem;">
											  <?=$row['status']?> 
											</div>
									<?php } elseif ($row['status'] == "Completed") { ?>
											<div class="badge badge-success text-wrap" style="width: 6rem;">
											  <?=$row['status']?> 
											</div>
									<?php	}else{
										echo $row['status'];
									} ?>

										
									<?php if ($row['status'] == "Pending") { ?>
										<a href="php/update_loan_status.php?id=<?=$row['loanId']?>&&value=Approved&&type=<?=$row['payment_type']?>&&monthly_payment=<?=$row['monthly_deduction']?>&&start_date=<?=$row['start_date']?>&&payment_type=<?=$row['payment_type']?>&&employee_id=<?=$row['employeeId']?>&&name=<?=$row['lastname']?>&&email=<?=$row['email']?>" class="btn btn-sm btn-primary">Approve</a>

										<!-- <a href="php/update_loan_status.php?id=<?=$row['loanId']?>&&value=Rejected&&type=<?=$row['payment_type']?>&&monthly_payment=<?=$row['monthly_deduction']?>&&start_date=<?=$row['start_date']?>&&payment_type=<?=$row['payment_type']?>&&employee_id=<?=$row['employeeId']?>&&name=<?=$row['lastname']?>&&email=<?=$row['email']?>" class="btn btn-sm btn-danger">Reject</a> -->

										<a href="#" 
										   class="btn btn-sm btn-danger reject-loan-btn" 
										   data-loan-id="<?= $row['loanId'] ?>"
										   data-value="Rejected"
										   data-type="<?= $row['payment_type'] ?>"
										   data-monthly-payment="<?= $row['monthly_deduction'] ?>"
										   data-start-date="<?= $row['start_date'] ?>"
										   data-payment-type="<?= $row['payment_type'] ?>"
										   data-employee-id="<?= $row['employeeId'] ?>"
										   data-name="<?= $row['lastname'] ?>"
										   data-email="<?= $row['email'] ?>"
										   data-toggle="modal" 
										   data-target="#rejectLoanModal">
										   Reject
										</a>

									<?php }?>
										
									</td>
									<td>
										<?php if ($row['status'] == "Rejected") 
										{ echo $row['reason']; }
										else if($row['status'] == "Pending"){ echo "Pending";}
										else { echo "Success"; }
										?>
									 	
									 </td>
									<?php
									if($row['payment_type'] == 1){ ?>
										<td>Monthly</td>
									<?php } else{?>
										<td>Semi-Monthly</td>
									<?php }
									?>
									<td><?=$row['start_date']?></td>
									<td><?=$row['end_date']?></td>
									<?php if ($type == 1) { ?>
										<td><a href="php/delete_loan.php?id=<?=$row['loanId']?>" class="btn btn-danger"><i class="fa fa-trash"></i></a></td>
									<?php } ?>
									

								</tr>
									<?php } ?>
									
								
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>


		<!-- modal -->

		<div class="modal fade" id="addLoanButton" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
		  <div class="modal-dialog">
		    <div class="modal-content">
		      <div class="modal-header">
		        <h5 class="modal-title" id="exampleModalLabel">Add a loan</h5>
		        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
		          <span aria-hidden="true">&times;</span>
		        </button>
		      </div>
		      <div class="modal-body">

		      	<form action="php/insert_loan.php" method="POST">
		            <div class="form-group">
		                <label for="" class="control-label">Employee</label>
						<select name="employee_id" class="borwser-default select2">
							<option value=""></option>
							<?php 
							$employee = $conn->query("SELECT *,concat(lastname,', ',firstname,' ',middlename) as ename FROM employee order by concat(lastname,', ',firstname,' ',middlename) asc");
							while($row = $employee->fetch_assoc()):
							?>
								<option value="<?php echo $row['id'] ?>"><?php echo $row['ename'] . ' | '. $row['employee_no'] ?></option>
							<?php endwhile; ?>
						</select>
		            </div>

		            <div class="form-group">
		                <label for="" class="control-label">Loan Type</label>
						<select name="loan_amount" class="borwser-default select2">
							<option value=""></option>
							<?php 
							$employee1 = $conn->query("SELECT * FROM loanTypes ");
							while($row = $employee1->fetch_assoc()):
							?>
								<option value="<?php echo $row['loanTypeId'] ."|".$row['amount'] ?>"><?php echo $row['name'] ?> - <?=$row['amount']?></option>
							<?php endwhile; ?>
						</select>
		            </div>
					<div class="form-group">
		                <label for="" class="control-label">Loan Term</label>
		            <select name="loan_term" class="borwser-default select2" id="loan_term">
						<option value="12">12 months</option>
						<option value="24">24 months</option>
						<option value="36">36 months</option>
					</select>
				</div>

					<div class="form-group">
				        <label for="interest_rate">Interest Rate (%)</label>
				        <input type="number" step="0.01" class="form-control" id="interest_rate" name="interest_rate" placeholder="Enter Interest Rate" readonly>
				    </div>

		             <!-- <div class="form-group">
		                <label for="loan_term">Loan Term (Months)</label>
		                <input type="number" class="form-control" name="loan_term" placeholder="Enter Loan Term" required>
		            </div> -->

		            
		           
		            <div class="form-group">
		                <label for="start_date">Start Date</label>
		                <input type="date" class="form-control" name="start_date" required>
		            </div>
		            <div class="form-group">
						<label for="" class="control-label">Payroll Type :</label>
						<select name="type" class="custom-select browser-default" id="">
							<option value="1">Monthly</option>
							<option value="2">Semi-Monthly</option>
						</select>
					</div>

		            <button type="submit"  class="btn btn-primary w-100">Submit</button>
		        </form>
		        
		      </div>
		    </div>
		  </div>
		</div>

		<div class="modal fade" id="rejectLoanModal" tabindex="-1" aria-labelledby="rejectLoanModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="rejectLoanModalLabel">Reject Loan</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="rejectLoanForm" action="php/upload_loan_status_v2.php" method="POST">
                    <!-- Hidden Inputs for Passing Data -->
                    <input type="hidden" name="id" id="loanId">
                    <input type="hidden" name="value" id="value" value="Rejected">
                    <input type="hidden" name="payment_type" id="payment_type">
                    <input type="hidden" name="monthly_payment" id="monthly_payment">
                    <input type="hidden" name="start_date" id="start_date">
                    <input type="hidden" name="employee_id" id="employee_id">
                    <input type="hidden" name="name" id="name">
                    <input type="hidden" name="email" id="email">

                    <!-- Loan Rejection Dropdown -->
                    <label for="rejection_reason">Reason for Rejection:</label>
                    <select id="rejection_reason" name="rejection_reason" class="form-control" required>
                        <option value="" disabled selected>-- Select a Reason --</option>
                        <option value="Low Credit Score">Low Credit Score</option>
                        <option value="High Debt-to-Income Ratio">High Debt-to-Income Ratio</option>
                        <option value="Insufficient Income">Insufficient Income</option>
                        <option value="Employment Instability">Employment Instability</option>
                        <option value="Loan Default History">Loan Default History</option>
                        <option value="Missing or Incomplete Documents">Missing or Incomplete Documents</option>
                        <option value="Invalid Loan Purpose">Invalid Loan Purpose</option>
                        <option value="Too Many Existing Loans">Too Many Existing Loans</option>
                    </select>

                    <!-- Submit Button -->
                    <button type="submit" class="btn btn-primary w-100 mt-3">Submit</button>
                </form>
            </div>
        </div>
    </div>
</div>


		<style>
			td p{
				margin: unset;
			}
			.rem_att{
				cursor: pointer;
			}
		</style>

	<script>
    $(document).ready(function () {
        // Mapping of loan terms to interest rates
        const interestRates = {
            "12": 3,
            "24": 6,
            "36": 9
        };

        // Function to update the interest rate input
        function updateInterestRate() {
            const selectedTerm = $('#loan_term').val(); // Get selected loan term
            if (interestRates[selectedTerm] !== undefined) {
                $('#interest_rate').val(interestRates[selectedTerm]); // Set the value
            } else {
                $('#interest_rate').val(''); // Clear the value if no match
            }
        }

        // Add an event listener to update the interest rate when selection changes
        $('#loan_term').change(updateInterestRate);

        // Automatically set the value on page load
        updateInterestRate();
    });
</script>
			
		
		
	<script type="text/javascript">
		$(document).ready(function(){
			$('#table').DataTable();

		});
	</script>

	<script>
    document.addEventListener("DOMContentLoaded", function () {
        // Add event listener to all reject-loan buttons
        document.querySelectorAll('.reject-loan-btn').forEach(button => {
            button.addEventListener('click', function () {
                // Get modal inputs
                const loanIdInput = document.getElementById('loanId');
                const paymentTypeInput = document.getElementById('payment_type');
                const monthlyPaymentInput = document.getElementById('monthly_payment');
                const startDateInput = document.getElementById('start_date');
                const employeeIdInput = document.getElementById('employee_id');
                const nameInput = document.getElementById('name');
                const emailInput = document.getElementById('email');

                // Set values from button data attributes
                loanIdInput.value = this.dataset.loanId;
                paymentTypeInput.value = this.dataset.paymentType;
                monthlyPaymentInput.value = this.dataset.monthlyPayment;
                startDateInput.value = this.dataset.startDate;
                employeeIdInput.value = this.dataset.employeeId;
                nameInput.value = this.dataset.name;
                emailInput.value = this.dataset.email;
            });
        });
    });


    </script>
</script>


