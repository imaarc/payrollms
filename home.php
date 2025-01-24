<?php include 'db_connect.php' ?>
<style>
   
</style>

<?php
    // Fetching data from the database
    $sql = "SELECT DATE_FORMAT(date_created, '%Y-%m') as month, SUM(salary - net) as total_salary 
            FROM payroll_items 
            GROUP BY YEAR(date_created), MONTH(date_created)";
    
    $query = mysqli_query($conn, $sql);

    $months = [];
    $totals = [];

    if ($query) {
        while ($row = mysqli_fetch_assoc($query)) {
            $months[] = $row['month'];           // Store the month (YYYY-MM)
            $totals[] = $row['total_salary'];    // Store the total salary difference for that month
        }
    } else {
        echo "Error: " . mysqli_error($conn);
    }

    // Convert PHP arrays to JavaScript arrays
    $months_json = json_encode($months);
    $totals_json = json_encode($totals);
?>

<?php
    // Fetching data from the database for monthly deductions
    $sql1 = "SELECT DATE_FORMAT(start_date, '%Y-%m') as month, SUM(monthly_deduction) as total_deduction 
            FROM loanmanagement 
            WHERE status = 'Approved' 
            GROUP BY YEAR(start_date), MONTH(start_date)";
    
    $query1 = mysqli_query($conn, $sql1);

    $deduction_months = [];
    $monthly_deductions = [];

    if ($query1) {
        while ($row = mysqli_fetch_assoc($query1)) {
            $deduction_months[] = $row['month'];           // Store the month (YYYY-MM)
            $monthly_deductions[] = $row['total_deduction'];    // Store the total monthly deduction for that month
        }
    } else {
        echo "Error: " . mysqli_error($conn);
    }

    // Convert PHP arrays to JavaScript arrays
    $deduction_months_json = json_encode($deduction_months);
    $monthly_deductions_json = json_encode($monthly_deductions);
?>



<div class="containe-fluid">

	<div class="row">
		<div class="col-lg-12">
			
		</div>
	</div>

	<div class="row mt-3 ml-3 mr-3">
			<div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                    <?php echo "Welcome back ". $_SESSION['login_name']."!"  ?>
                                        
                    </div>
                    
                </div>
            </div>

            <div class="row mt-3 w-100">

            	<div class="col-lg-4">
            		<div class="card" style="height: 135px;">
					  <div class="card-body">
					    Total Employee
					    	
					    	<h1 class="fs-1">
						    <?php
							    $sql = "SELECT COUNT(*) as count FROM employee";
							    $query = mysqli_query($conn, $sql);

							    if ($query) {
							        $row = mysqli_fetch_assoc($query);
							        echo $row['count'];
							    } else {
							        echo "Error: " . mysqli_error($conn);
							    }
							?>
					    </h1>
					  </div>
					</div>
            	</div>

            	<div class="col-lg-4">
            		<div class="card" style="height: 135px;">
					  <div class="card-body">
					    Total Loan Payment (Month)
					    <h1>
					    	<?php
						    $sql = "SELECT SUM(monthly_deduction) as total_deduction FROM loanmanagement WHERE status ='Approved'";
						    $query = mysqli_query($conn, $sql);

						    if ($query) {
						        $row = mysqli_fetch_assoc($query);
						        echo $row['total_deduction'];
						    } else {
						        echo "Error: " . mysqli_error($conn);
						    }
						?>
					    </h1>
					    
					  </div>
					</div>
            	</div>

            	<div class="col-lg-4">
            		<div class="card " style="height: 135px;">
					  <div class="card-body">
					    Total Salary (Month)

					    
					    	<?php
						    $sql = "SELECT  DATE_FORMAT(date_created, '%Y-%m') as month, SUM(salary - net) as total_salary 
						            FROM payroll_items 
						            GROUP BY YEAR(date_created), MONTH(date_created)  ORDER BY date_created DESC LIMIT 1";
						    
						    $query = mysqli_query($conn, $sql)->fetch_assoc();

						    echo  "<h1>". $query['total_salary'] ."</h1>" . " <span>(". $query['month'].")</span>";
						   
						?>
					    

					  </div>
					</div>
            	</div>
            	
            </div>

            
	</div>

			<div class="row  mt-3">
            	<div class="container mt-5 col-lg-5 ms-3">
				    <h2 class="text-center">Monthly Payroll Summary</h2>
				    <canvas id="payrollChart"></canvas> <!-- Canvas for the chart -->
				</div>

				<div class="container col-lg-5 mt-5">
				    <h2 class="text-center">Monthly Deductions Summary</h2>
				    <canvas id="deductionChart"></canvas> <!-- Canvas for the chart -->
				</div>
            	
            </div>

</div>
<script>
    // Get the data from PHP
    var months = <?php echo $months_json; ?>;
    var totals = <?php echo $totals_json; ?>;

    // Create the chart
    var ctx = document.getElementById('payrollChart').getContext('2d');
    var payrollChart = new Chart(ctx, {
        type: 'line', // You can also use 'bar', 'pie', 'doughnut', etc.
        data: {
            labels: months, // X-axis labels (Months)
            datasets: [{
                label: 'Total Salary Difference (salary - net)',
                data: totals, // Y-axis data (Total Salary for each month)
                backgroundColor: 'rgba(75, 192, 192, 0.2)', // Area under the curve color
                borderColor: 'rgba(75, 192, 192, 1)', // Line color
                borderWidth: 1,
                fill: true // Fill the area under the curve
            }]
        },
        options: {
            responsive: true,
            scales: {
                x: {
                    title: {
                        display: true,
                        text: 'Month'
                    }
                },
                y: {
                    title: {
                        display: true,
                        text: 'Total Salary Difference'
                    },
                    beginAtZero: true
                }
            }
        }
    });


</script>

<script>
    // Get the data from PHP with distinct variable names
    var deductionMonths = <?php echo $deduction_months_json; ?>;
    var monthlyDeductions = <?php echo $monthly_deductions_json; ?>;

    // Create the chart with distinct names
    var ctxDeduction = document.getElementById('deductionChart').getContext('2d');
    var deductionChart = new Chart(ctxDeduction, {
        type: 'bar', // You can change 'bar' to 'line', 'pie', etc.
        data: {
            labels: deductionMonths, // X-axis labels (Months)
            datasets: [{
                label: 'Total Monthly Deductions',
                data: monthlyDeductions, // Y-axis data (Total Deductions for each month)
                backgroundColor: 'rgba(153, 102, 255, 0.2)', // Bar fill color
                borderColor: 'rgba(153, 102, 255, 1)', // Bar border color
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            scales: {
                x: {
                    title: {
                        display: true,
                        text: 'Month'
                    }
                },
                y: {
                    title: {
                        display: true,
                        text: 'Total Deductions'
                    },
                    beginAtZero: true
                }
            }
        }
    });
</script>
