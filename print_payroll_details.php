<?php include 'db_connect.php' ?>

<?php
	$payroll=$conn->query("SELECT p.*,concat(e.lastname,', ',e.firstname,' ',e.middlename) as ename,e.employee_no FROM payroll_items p inner join employee e on e.id = p.employee_id  where p.id=".$_GET['id']);
	foreach ($payroll->fetch_array() as $key => $value) {
		$$key = $value;
	}
	$pay = $conn->query("SELECT * FROM payroll where id = ".$payroll_id)->fetch_array();
	$pt = array(1=>"Monthly",2=>"Semi-Monthly");
?>

<div>
<h5>Employee ID :<?php echo $employee_no ?></h5>
<h4>Name: <?php echo ucwords($ename) ?></h4>
<hr>
</div>

<div>
				
				<p>Payroll ID : <?=$payroll_id?></p>
				<p>Payroll Ref : <?php echo $pay['ref_no'] ?></p>
				<p>Payroll Range : <?php echo date("M d, Y",strtotime($pay['date_from'])). " - ".date("M d, Y",strtotime($pay['date_to'])) ?></p>
				<p>Payroll type : <?php echo $pt[$pay['type']] ?></p>
			</div>
			<div class="col-md-6">
				<p>Days of Absent : <?php echo $absent ?></p>
				<p>Tardy/Undertime (mins) : <?php echo $late ?></p>
				<p>Total Allowance Amount : <?php echo number_format($allowance_amount,2) ?></p>
				<p>Total Deduction Amount : <?php echo number_format($deduction_amount,2) ?></p>
				<p>Net Pay : <?php echo number_format($net,2) ?></p>
			</div>

<div >
	<span><b>Allowances</b></span>				
</div>

<div >
	<ul >
	<?php
	$all_qry = $conn->query("SELECT * from allowances ");
	$t_arr = array(1=>"Monthly",2=>"Semi-Monthly",3=>"Once");
	while($row=$all_qry->fetch_assoc()):
	$all_arr[$row['id']] = $row['allowance'];
	endwhile; 
	foreach(json_decode($allowances) as $k => $val):

	?>
		<li >
		<?php echo $all_arr[$val->aid] ?> Allowance
		<?php echo number_format($val->amount,2) ?>
		</li>
	<?php endforeach; ?>
	</ul>
</div>

<div >
<span><b>Deductions</b></span>
</div>
<div >
	<ul >
	<?php
	$all_qry = $conn->query("SELECT * from deductions ");
	$t_arr = array(1=>"Monthly",2=>"Semi-Monthly",3=>"Once");
	while($row=$all_qry->fetch_assoc()):
	$ded_arr[$row['id']] = $row['deduction'];
	endwhile; 
	foreach(json_decode($deductions) as $k => $val):
	?>
		<li >
		<?php echo $ded_arr[$val->did] ?>
		<?php echo number_format($val->amount,2) ?>
		</li>
	<?php endforeach; ?>
	</ul>
</div>

<script>
        // Execute the print function once the page is fully loaded
        window.onload = function () {
            window.print();
        };
    </script>