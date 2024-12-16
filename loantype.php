<?php include('db_connect.php');?>

<div class="container-fluid">
	
	<div class="col-lg-12">
		<div class="row">
			<!-- FORM Panel -->
			<div class="col-md-4">
				<div class="card">
					<div class="card-header">
						  Loan Types
				  	</div>
					<div class="card-body">
						<form action="php/addLoanType.php" method="GET">
							<div class="form-group">
								<label class="control-label">Loan Name</label>
								<textarea name="loanName" cols="30" rows="2" class="form-control" required></textarea>
							</div>
							<div class="form-group">
								<label class="control-label">Amount</label>
								<input type="number" class="form-control" name="amount">
							</div>
							<button type="submit" class="btn btn-primary w-100">Save</button>
						</form>
							
					</div>
				</div>
			</div>
			<!-- FORM Panel -->

			<!-- Table Panel -->
			<div class="col-md-8">
				<div class="card">
					<div class="card-body">
						<table class="table table-bordered table-hover">
							<thead>
								<tr>
									<th class="text-center">Name</th>
									<th class="text-center">Amount</th>
								</tr>
							</thead>
							<tbody>
								<?php 
								$allowances = $conn->query("SELECT * FROM loanTypes");
								while($row=$allowances->fetch_assoc()):
								?>
								<tr>
									
									<td class="">
										 <p><?php echo $row['name'] ?></p>
										 
									</td>
									<td>
										<p> <?php echo $row['amount'] ?></p>
									</td>
								</tr>
								<?php endwhile; ?>
							</tbody>
						</table>
					</div>
				</div>
			</div>
			<!-- Table Panel -->
		</div>
	</div>	

</div>
<style>
	
	td{
		vertical-align: middle !important;
	}
	td p{
		margin: unset
	}
	img{
		max-width:100px;
		max-height: :150px;
	}
</style>
