<?
	include(DIR.'files/arrays_'.$_SESSION['rego']['lang'].'.php');
	if(!isset($_GET['m'])){$_GET['m'] = $_SESSION['rego']['cur_month'];}
	
	$emps = getEmployees($cid,0);
	$emp_id = key($emps);

	$emp_array = '[';
	foreach($emps as $k=>$v){
		$emp_array .= "{data:'".$k."',value:'".$k.' - '.$v['en_name']."'},";
	}
	$emp_array = substr($emp_array,0,-1);
	$emp_array .= ']';
	
	//var_dump($emps);
	//var_dump($_SESSION['rego']['report_id']);
	
	$employee = '';
	$service_years = '';
	if(isset($_SESSION['rego']['report_id'])){
		$employee = $_SESSION['rego']['report_id'].' - '.$emps[$_SESSION['rego']['report_id']][$lang.'_name'];
	}

	$data = array();
	$id = false;
	if(isset($_SESSION['rego']['report_id'])){
		$id = true;
		$sql = "SELECT * FROM ".$cid."_employees WHERE emp_id = '".$_SESSION['rego']['report_id']."'";
		if($res = $dbc->query($sql)){
			if($row = $res->fetch_assoc()){
				$data = $row;
				$service_years = getAge($data['joining_date']);
			}
		}
	}
	
	//var_dump($service_years);
	//var_dump($emp_id);
	
	$fix = getFixAllowNames();
	$var = getVarAllowNames();
	
	$dfix = getFixDeductNames();
	$dvar = getVarDeductNames();
	
	$overtime = 0;
	$tot_salary = 0;
	$allowances = 0;
	$tot_deduct = 0;
	$net = 0;

	$pay = array();
	$pay['fix'] = array();
	$pay['var'] = array();
	$pay['dfix'] = array();
	$pay['dvar'] = array();
	$calculation = array();
	$taxes = array();
	$other_income = 0;
	$end_contract = 0;
	
	if(isset($_SESSION['rego']['report_id'])){
		$sql = "SELECT * FROM ".$_SESSION['rego']['payroll_dbase']." WHERE emp_id = '".$_SESSION['rego']['report_id']."' AND month = '".$_GET['m']."'";
		if($res = $dbc->query($sql)){
			if($row = $res->fetch_assoc()){
				//$pay = $row;
				if($row['salary'] > 0){$pay['salary'] = number_format($row['salary'],2);}else{$pay['salary'] = '-';}
				$tot_salary = $row['salary'];
				if($row['ot1h'] > 0){$pay['ot1h'] = number_format($row['ot1h']);}else{$pay['ot1h'] = '-';}
				if($row['ot1b'] > 0){$pay['ot1b'] = number_format($row['ot1b'],2); $overtime += $row['ot1b'];}else{$pay['ot1b'] = '-';}
				if($row['ot15h'] > 0){$pay['ot15h'] = number_format($row['ot15h']);}else{$pay['ot15h'] = '-';}
				if($row['ot15b'] > 0){$pay['ot15b'] = number_format($row['ot15b'],2); $overtime += $row['ot15b'];}else{$pay['ot15b'] = '-';}
				if($row['ot2h'] > 0){$pay['ot2h'] = number_format($row['ot2h']);}else{$pay['ot2h'] = '-';}
				if($row['ot2b'] > 0){$pay['ot2b'] = number_format($row['ot2b'],2); $overtime += $row['ot2b'];}else{$pay['ot2b'] = '-';}
				if($row['ot3h'] > 0){$pay['ot3h'] = number_format($row['ot3h']);}else{$pay['ot3h'] = '-';}
				if($row['ot3b'] > 0){$pay['ot3b'] = number_format($row['ot3b'],2); $overtime += $row['ot3b'];}else{$pay['ot3b'] = '-';}
				if($row['ootb'] > 0){$pay['ootb'] = number_format($row['ootb'],2);}else{$pay['ootb'] = '-';}
				$tot_salary += $overtime;
				
				for($i=1;$i<=10;$i++){
					if($row['fix_allow_'.$i] > 0){$pay['fix'][$i] = number_format($row['fix_allow_'.$i],2); $allowances += $row['fix_allow_'.$i];}
				}
				for($i=1;$i<=10;$i++){
					if($row['var_allow_'.$i] > 0){$pay['var'][$i] = number_format($row['var_allow_'.$i],2); $allowances += $row['var_allow_'.$i];}
				}
				
				for($i=1;$i<=5;$i++){
					if($row['fix_deduct_'.$i] > 0){$pay['dfix'][$i] = number_format($row['fix_deduct_'.$i],2); $allowances += $row['fix_deduct_'.$i];}
				}
				for($i=1;$i<=5;$i++){
					if($row['var_deduct_'.$i] > 0){$pay['dvar'][$i] = number_format($row['var_deduct_'.$i],2); $allowances += $row['var_deduct_'.$i];}
				}
				
				$other_income = $row['paid_leave'] + $row['other_income'] + $row['remaining_salary'];
				$end_contract = $row['notice_payment'] + $row['severance'];

				if($row['absence'] > 0){$pay['absence'] = number_format($row['absence']);}else{$pay['absence'] = '-';}
				if($row['absence_b'] > 0){$pay['absence_b'] = number_format($row['absence_b'],2); $tot_deduct += $row['absence_b'];}else{$pay['absence_b'] = '-';}
				if($row['late_early'] > 0){$pay['late_early'] = number_format($row['late_early']);}else{$pay['late_early'] = '-';}
				if($row['late_early_b'] > 0){$pay['late_early_b'] = number_format($row['late_early_b'],2); $tot_deduct += $row['late_early_b'];}else{$pay['late_early_b'] = '-';}
				if($row['leave_wop'] > 0){$pay['leave_wop'] = number_format($row['leave_wop']);}else{$pay['leave_wop'] = '-';}
				if($row['leave_wop_b'] > 0){$pay['leave_wop_b'] = number_format($row['leave_wop_b'],2); $tot_deduct += $row['leave_wop_b'];}else{$pay['leave_wop_b'] = '-';}
				if($row['tot_deduct_before'] > 0){$pay['tot_deduct_before'] = number_format($row['tot_deduct_before'],2); $tot_deduct += $row['tot_deduct_before'];}else{$pay['tot_deduct_before'] = '-';}
				if($row['tot_deduct_after'] > 0){$pay['tot_deduct_after'] = number_format($row['tot_deduct_after'],2); $tot_deduct += $row['tot_deduct_after'];}else{$pay['tot_deduct_after'] = '-';}
				if($row['pvf_employee'] > 0){$pay['pvf_employee'] = number_format($row['pvf_employee'],2); $tot_deduct += $row['pvf_employee'];}else{$pay['pvf_employee'] = '-';}
				if($row['social'] > 0){$pay['social'] = number_format($row['social'],2); $tot_deduct += $row['social'];}else{$pay['social'] = '-';}
				if($row['tax'] > 0){$pay['tax'] = number_format($row['tax'],2); $tot_deduct += $row['tax'];}else{$pay['tax'] = '-';}
				if($row['net_income'] > 0){$pay['net_income'] = number_format($row['net_income'],2); $tot_deduct += $row['net_income'];}else{$pay['net_income'] = '-';}
				if($row['advance'] > 0){$pay['advance'] = number_format($row['advance'],2); $tot_deduct += $row['advance'];}else{$pay['advance'] = '-';}
				if($row['legal_deductions'] > 0){$pay['legal_deductions'] = number_format($row['legal_deductions'],2); $tot_deduct += $row['legal_deductions'];}else{$pay['legal_deductions'] = '-';}
				$other_deduct = $row['tot_deduct_after'];// + $row['deduct_3'];
				if($other_deduct > 0){$pay['other_deductions'] = number_format($other_deduct,2); $tot_deduct += $other_deduct;}else{$pay['other_deductions'] = '-';}
				$net = $row['net_income'] - $row['advance'];
				$calculation = unserialize($row['tax_calculation']);
	
				if($calculation['standard_deduction'] > 0){$taxes[$lng['Standard deduction']] = number_format($calculation['standard_deduction'],2);}
				if($calculation['personal_allowance'] > 0){$taxes[$lng['Personal allowance']] = number_format($calculation['personal_allowance'],2);}
				if($calculation['spouse_allowance'] > 0){$taxes[$lng['Spouse care']] = number_format($calculation['spouse_allowance'],2);}
				if($calculation['parents_allowance'] > 0){$taxes[$lng['Parents care']] = number_format($calculation['parents_allowance'],2);}
				if($calculation['parents_inlaw_allowance'] > 0){$taxes[$lng['Parents in law care']] = number_format($calculation['parents_inlaw_allowance'],2);}
				if($calculation['disabled_allowance'] > 0){$taxes[$lng['Care disabled person']] = number_format($calculation['disabled_allowance'],2);}
				if($calculation['child_allowance'] > 0){$taxes[$lng['Child care - biological']] = number_format($calculation['child_allowance'],2);}
				if($calculation['child_birth_bonus'] > 0){$taxes[$lng['Child birth (Baby bonus)']] = number_format($calculation['child_birth_bonus'],2);}
				if($calculation['own_health_insurance'] > 0){$taxes[$lng['Own health insurance']] = number_format($calculation['own_health_insurance'],2);}
				if($calculation['own_life_insurance'] > 0){$taxes[$lng['Own life insurance']] = number_format($calculation['own_life_insurance'],2);}
				if($calculation['health_insurance_parent'] > 0){$taxes[$lng['Health insurance parents']] = number_format($calculation['health_insurance_parent'],2);}
				if($calculation['life_insurance_spouse'] > 0){$taxes[$lng['Life insurance spouse']] = number_format($calculation['life_insurance_spouse'],2);}
				if($calculation['pension_fund_allowance'] > 0){$taxes[$lng['Pension fund']] = number_format($calculation['pension_fund_allowance'],2);}
				if($calculation['pvf_year'] > 0){$taxes[$lng['Provident fund']] = number_format($calculation['pvf_year'],2);}
				if($calculation['nsf_allowance'] > 0){$taxes[$lng['NSF']] = number_format($calculation['nsf_allowance'],2);}
				if($calculation['rmf_allowance'] > 0){$taxes[$lng['RMF']] = number_format($calculation['rmf_allowance'],2);}
				if($calculation['sso_year'] > 0){$taxes[$lng['Social Security Fund']] = number_format($calculation['sso_year'],2);}
				if($calculation['ltf_deduction'] > 0){$taxes[$lng['LTF']] = number_format($calculation['ltf_deduction'],2);}
				if($calculation['home_loan_interest'] > 0){$taxes[$lng['Home loan interest']] = number_format($calculation['home_loan_interest'],2);}
				if($calculation['donation_charity'] > 0){$taxes[$lng['Donation charity']] = number_format($calculation['donation_charity'],2);}
				if($calculation['donation_flood'] > 0){$taxes[$lng['Donation flooding']] = number_format($calculation['donation_flood'],2);}
				if($calculation['donation_education'] > 0){$taxes[$lng['Donation education']] = number_format($calculation['donation_education'],2);}
				if($calculation['exemp_disabled_under'] > 0){$taxes[$lng['Exemption disabled person <65 yrs']] = number_format($calculation['exemp_disabled_under'],2);}
				if($calculation['exemp_payer_older'] > 0){$taxes[$lng['Exemption tax payer => 65yrs']] = number_format($calculation['exemp_payer_older'],2);}
				if($calculation['first_home_allowance'] > 0){$taxes[$lng['First home buyer']] = number_format($calculation['first_home_allowance'],2);}
				if($calculation['year_end_shop_allowance'] > 0){$taxes[$lng['Year-end shopping']] = number_format($calculation['year_end_shop_allowance'],2);}
				if($calculation['domestic_tour_allowance'] > 0){$taxes[$lng['Domestic tour']] = number_format($calculation['domestic_tour_allowance'],2);}
				if($calculation['other_allowance'] > 0){$taxes[$lng['Other allowance']] = number_format($calculation['other_allowance'],2);}
			}
		}
	}
	
	if(!isset($pay['salary'])){$pay['salary'] = '-';}
	
	if(!isset($pay['ot1h'])){$pay['ot1h'] = '-';}
	if(!isset($pay['ot1b'])){$pay['ot1b'] = '-';}
	if(!isset($pay['ot15h'])){$pay['ot15h'] = '-';}
	if(!isset($pay['ot15b'])){$pay['ot15b'] = '-';}
	if(!isset($pay['ot2h'])){$pay['ot2h'] = '-';}
	if(!isset($pay['ot2b'])){$pay['ot2b'] = '-';}
	if(!isset($pay['ot3h'])){$pay['ot3h'] = '-';}
	if(!isset($pay['ot3b'])){$pay['ot3b'] = '-';}
	if(!isset($pay['ootb'])){$pay['ootb'] = '-';}
	
	if(!isset($pay['absence'])){$pay['absence'] = '-';}
	if(!isset($pay['absence_b'])){$pay['absence_b'] = '-';}
	if(!isset($pay['late_early'])){$pay['late_early'] = '-';}
	if(!isset($pay['late_early_b'])){$pay['late_early_b'] = '-';}
	if(!isset($pay['leave_wop'])){$pay['leave_wop'] = '-';}
	if(!isset($pay['leave_wop_b'])){$pay['leave_wop_b'] = '-';}
	if(!isset($pay['tot_deduct_before'])){$pay['tot_deduct_before'] = '-';}
	if(!isset($pay['tot_deduct_after'])){$pay['tot_deduct_after'] = '-';}
	
	if(!isset($pay['pvf_employee'])){$pay['pvf_employee'] = '-';}
	if(!isset($pay['social'])){$pay['social'] = '-';}
	if(!isset($pay['tax'])){$pay['tax'] = '-';}
	if(!isset($pay['net_income'])){$pay['net_income'] = '-';}
	if(!isset($pay['advance'])){$pay['advance'] = '-';}
	if(!isset($pay['legal_deductions'])){$pay['legal_deductions'] = '-';}
	if(!isset($pay['other_deductions'])){$pay['other_deductions'] = '-';}

	if(!isset($calculation['gross_year'])){$gross_year = '-';}else{$gross_year = number_format($calculation['gross_year'],2);}
	if(!isset($calculation['tax_deductions'])){$tax_deductions = '-';}else{$tax_deductions = number_format($calculation['tax_deductions'],2);}
	if(!isset($calculation['taxable_year'])){$taxable_year = '-';}else{$taxable_year = number_format($calculation['taxable_year'],2);}
	if(!isset($calculation['tax_year'])){$tax_year = '-';}else{$tax_year = number_format($calculation['tax_year'],2);}
	if(!isset($calculation['tax_month'])){$tax_month = '-';}else{$tax_month = number_format($calculation['tax_month'],2);}
	if(!isset($calculation['tax_modify'])){$tax_modify = '-';}else{$tax_modify = number_format($calculation['tax_modify'],2);}
	if(!isset($calculation['tax_this_month'])){$tax_this_month = '-';}else{$tax_this_month = number_format($calculation['tax_this_month'],2);}
	
	if($tot_salary > 0){$tot_salary = number_format($tot_salary,2);}else{$tot_salary = '-';}
	if($overtime > 0){$overtime = number_format($overtime,2);}else{$overtime = '-';}
	if($allowances > 0){$allowances = number_format($allowances,2);}else{$allowances = '-';}
	if($other_income > 0){$other_income = number_format($other_income,2);}else{$other_income = '-';}
	if($end_contract > 0){$end_contract = number_format($end_contract,2);}else{$end_contract = '-';}
	if($tot_deduct > 0){$tot_deduct = number_format($tot_deduct,2);}else{$tot_deduct = '-';}
	if($net > 0){$net = number_format($net,2);}else{$net = '-';}
	//var_dump($calculation);
	
?>	
<style>
.A4form {
	width:100%;
	xmargin:10px 10px 10px 15px;
	background:#fff; 
	padding:20px 30px 20px 30px; 
	box-shadow:0 0 10px rgba(0,0,0,0.4); 
	position:relative;
	min-height:500px;
}
table.reportTable {
	width:100%;
	border-collapse:collapse;
	font-size:13px;
	margin-bottom:5px;
}
table.reportTable b {
	font-weight:600;
	color:#039;
}
table.reportTable thead th {
	padding:4px 8px;
	font-weight:600;
	text-align:center;
	font-size:13px;
}
table.reportTable thead th.head {
	padding:4px 8px;
	font-weight:600;
	text-align:center;
	font-size:16px;
}
table.reportTable tbody th {
	padding:2px 8px;
	white-space:nowrap;
	font-weight:600;
	border:1px solid #eee;
	border-left:0;
}
table.reportTable tbody td {
	padding:2px 8px;
	border:1px solid #eee;
	white-space:nowrap;
}
table.reportTable tbody td.bold {
	font-weight:600;
}
table.reportTable tbody td:first-child {
	border-left:0;
}
table.reportTable tbody td:last-child {
	border-right:0;
}
table.reportTable tbody th.H1 {
	background:#eee;
	color:#900;
	padding:2px 8px;
	font-weight:600;
	border:1px solid #fff;
	border-bottom:1px solid #bbb;
}
table.reportTable tbody th.H1:last-child {
	xborder-right:0;
}


</style>

	<h2><i class="fa fa-file-pdf-o"></i>&nbsp; <?=$lng['Overview per employee per month']?></h2>		
	
	<div class="main" style="padding-top:15px; top:130px">
		<div style="padding:0 0 0 20px" id="dump"></div>

		<div class="searchFilter" style="width:300px">
			<input style="width:100%; font-size:13px; line-height:27px" class="sFilter" placeholder="<?=$lng['Employee']?> ... <?=$lng['Type for hints']?> ..." type="text" id="selectEmployee" value="<?=$employee?>" />
		</div>
		<select id="monthFilter" style="padding:4px 7px 3px; border:1px #ddd solid; margin:50; width:auto; float:left">
		<? foreach($months as $k=>$v){
				echo '<option ';
				if($k == $_GET['m']){echo 'selected ';}
				echo 'value="'.$k.'">'.$v.'</option>';
			} ?>
		</select>
					
		<button <? if(!$id){echo 'disabled';}?> id="exportExcel" type="button" class="btn btn-primary btn-fr"><i class="fa fa-upload"></i>&nbsp; <?=$lng['Export Excel']?></button>
		<button <? //if(!$id){echo 'disabled';}?> disabled type="button" class="btn btn-primary btn-fr"><i class="fa fa-file-pdf-o"></i>&nbsp; <?=$lng['Print PDF']?></button>
		<div style="clear:both; height:8px"></div>
		
		<div class="A4form">
			<div style="overflow-x:auto; width:100%">

			<table class="reportTable" border="0" style="margin:0">
				<thead>
					<tr>
						<th style="padding:0 0 5px 2px" class="head tal"><?=$lng['Employee']?> <? if($id){echo $data['emp_id'].' '.$title[$data['title']].' '.$data[$lang.'_name'];}?></th>
					</tr>
				</thead>
			</table>

			<table class="reportTable" border="0">
				<tbody>
					<tr>
						<th class="H1 tal" colspan="6"><?=$lng['Employee']?></th>
					</tr>
					<tr>
						<td><b><?=$lng['ID']?> :</b> <? if($id){echo $data['emp_id'];}?></td>
						<td><b><?=$lng['Name']?> :</b> <? if($id){echo $data[$lang.'_name'];}?></td>
						<td><b><?=$lng['Position']?> :</b> <? if($id){echo $positions[$data['position']][$lang];}?></td>
						<td><b><?=$lng['Joining date']?> :</b> <? if($id){echo date('d-m-Y', strtotime($data['joining_date']));}?></td>
						<td style="border-right:0"><b><?=$lng['Service years']?> :</b> <? if($id){echo $service_years;}?></td>
						<td style="width:40%; border-left:0"></td>
					</tr>
				</tbody>
			</table>

			<table border="0" style="table-layout:fixed; width:100%"><tr><td style="padding-right:7px; border-right:1px solid #ddd; vertical-align:top; width:40%">
				
				<table class="reportTable" border="0" style="table-layout:xfixed">
					<tbody>
						<tr>
							<th class="H1" style="width:1px"><?=$lng['Overtime']?></th>
							<th class="H1 tar" style="width:20%"><?=$lng['OT 1']?></th>
							<th class="H1 tar" style="width:20%"><?=$lng['OT 1.5']?></th>
							<th class="H1 tar" style="width:20%"><?=$lng['OT 2']?></th>
							<th class="H1 tar" style="width:20%"><?=$lng['OT 3']?></th>
							<th class="H1 tar" style="width:20%"><?=$lng['Other OT']?></th>
						</tr>
						<tr>
							<th><?=$lng['Hours']?></th>
							<td class="tar"><?=$pay['ot1h']?></td>
							<td class="tar"><?=$pay['ot15h']?></td>
							<td class="tar"><?=$pay['ot2h']?></td>
							<td class="tar"><?=$pay['ot3h']?></td>
							<td class="tar"></td>
						</tr>
						<tr>
							<th><?=$lng['Baht']?></th>
							<td class="tar"><?=$pay['ot1b']?></td>
							<td class="tar"><?=$pay['ot15b']?></td>
							<td class="tar"><?=$pay['ot2b']?></td>
							<td class="tar"><?=$pay['ot3b']?></td>
							<td class="tar"><?=$pay['ootb']?></td>
						</tr>
					</tbody>
				</table>
				
				<table border="0" style="width:100%">
					<tr>
						<td style="width:50%; padding-right:3px; vertical-align:top">
							<table class="reportTable" border="0" style="table-layout:fixed">
								<tbody>
									<tr>
										<th class="H1"><?=$lng['Fixed allowances']?></th>
										<th class="H1 tar"><?=$lng['Baht']?></th>
									</tr>
									<? if($pay['fix']){ foreach($pay['fix'] as $k=>$v){
											echo '<tr>';
											echo '<th>'.$fix[$k].'</th>';
											echo '<td class="tar">'.$v.'</td>';
											echo '</tr>';
									} } 
									if(!$pay['fix']){
											echo '<tr><td colspan="2">'.$lng['No data available'].'</td></tr>';
									} ?>
								</tbody>
							</table>
						</td>
						<td style="width:50%; padding-left:3px; vertical-align:top">
							<table class="reportTable" border="0" style="table-layout:fixed">
								<tbody>
									<tr>
										<th class="H1"><?=$lng['Variable allowances']?></th>
										<th class="H1 tar"><?=$lng['Baht']?></th>
									</tr>
									<? if($pay['var']){ foreach($pay['var'] as $k=>$v){
											echo '<tr>';
											echo '<th>'.$var[$k].'</th>';
											echo '<td class="tar">'.$v.'</td>';
											echo '</tr>';
									} } 
									if(!$pay['var']){
											echo '<tr><td colspan="2">'.$lng['No data available'].'</td></tr>';
									} ?>
								</tbody>
							</table>
						</td>
					</tr>
				</table>
				
				<table border="0" style="width:100%">
					<tr>
						<td style="width:50%; padding-right:3px; vertical-align:top">
							<table class="reportTable" border="0" style="table-layout:fixed">
								<tbody>
									<tr>
										<th class="H1"><?=$lng['Fixed deductions']?></th>
										<th class="H1 tar"><?=$lng['Baht']?></th>
									</tr>
									<? if($pay['dfix']){ foreach($pay['dfix'] as $k=>$v){
											echo '<tr>';
											echo '<th>'.$fix[$k].'</th>';
											echo '<td class="tar">'.$v.'</td>';
											echo '</tr>';
									} } 
									if(!$pay['dfix']){
											echo '<tr><td colspan="2">'.$lng['No data available'].'</td></tr>';
									} ?>
								</tbody>
							</table>
						</td>
						<td style="width:50%; padding-left:3px; vertical-align:top">
							<table class="reportTable" border="0" style="table-layout:fixed">
								<tbody>
									<tr>
										<th class="H1"><?=$lng['Variable deductions']?></th>
										<th class="H1 tar"><?=$lng['Baht']?></th>
									</tr>
									<? if($pay['dvar']){ foreach($pay['dvar'] as $k=>$v){
											echo '<tr>';
											echo '<th>'.$var[$k].'</th>';
											echo '<td class="tar">'.$v.'</td>';
											echo '</tr>';
									} } 
									if(!$pay['dvar']){
											echo '<tr><td colspan="2">'.$lng['No data available'].'</td></tr>';
									} ?>
								</tbody>
							</table>
						</td>
					</tr>
				</table>
				
				<table class="reportTable" border="0" style="table-layout:fixed">
					<tbody>
						<tr>
							<th class="H1" style="width:60%"><?=$lng['Deductions']?></th>
							<th class="H1 tar"><?=$lng['Hours']?></th>
							<th class="H1 tar"><?=$lng['Baht']?></th>
						</tr>
						<tr>
							<th><?=$lng['Absence']?></th>
							<td class="tar"><?=$pay['absence']?></td>
							<td class="tar"><?=$pay['absence_b']?></td>
						</tr>
						<tr>
							<th><?=$lng['Late Early']?></th>
							<td class="tar"><?=$pay['late_early']?></td>
							<td class="tar"><?=$pay['late_early_b']?></td>
						</tr>
						<tr>
							<th><?=$lng['Leave WOP']?></th>
							<td class="tar"><?=$pay['leave_wop']?></td>
							<td class="tar"><?=$pay['leave_wop_b']?></td>
						</tr>
						<tr>
							<th><?=$lng['Deduct before']?></th>
							<td class="tar"></td>
							<td class="tar"><?=$pay['tot_deduct_before']?></td>
						</tr>
						<tr>
							<th><?=$lng['Deduct after']?></th>
							<td class="tar"></td>
							<td class="tar"><?=$pay['tot_deduct_after']?></td>
						</tr>
					</tbody>
				</table>
				
			</td><td style="padding:0 7px; vertical-align:top; width:30%">
	
				<table class="reportTable" border="0" style="table-layout:fixed">
					<tbody>
						<tr>
							<th class="H1" style="width:75%"><?=$lng['Monthly salary information']?></th>
							<th class="H1 tar"><?=$lng['Baht']?></th>
						</tr>
						<tr>
							<th><?=$lng['Salary']?></th>
							<td class="tar"><?=$pay['salary']?></td>
						</tr>
						<tr>
							<th><?=$lng['Overtime']?></th>
							<td class="tar"><?=$overtime?></td>
						</tr>
						<tr>
							<th><?=$lng['Total salary']?></th>
							<td class="tar"><?=$tot_salary?></td>
						</tr>
						<tr>
							<th><?=$lng['Allowances']?></th>
							<td class="tar"><?=$allowances?></td>
						</tr>
						<tr>
							<th><?=$lng['Other income']?></th>
							<td class="tar"><?=$other_income?></td>
						</tr>
						<tr>
							<th><?=$lng['End contract']?></th>
							<td class="tar"><?=$end_contract?></td>
						</tr>
						<tr>
							<th class="H1"><?=$lng['Gross income']?></th>
							<th class="H1 tar"><?=$tot_deduct?></th>
						</tr>
						<tr>
							<th><?=$lng['PVF']?></th>
							<td class="tar"><?=$pay['pvf_employee']?></td>
						</tr>
						<tr>
							<th><?=$lng['SSO']?></th>
							<td class="tar"><?=$pay['social']?></td>
						</tr>
						<tr>
							<th><?=$lng['Tax']?></th>
							<td class="tar"><?=$pay['tax']?></td>
						</tr>
						<tr>
							<th><?=$lng['Other deductions']?></th>
							<td class="tar"><?=$pay['other_deductions']?></td>
						</tr>
						<tr>
							<th class="H1"><?=$lng['Net income']?></th>
							<th class="tar H1"><?=$pay['net_income']?></th>
						</tr>
						<tr>
							<th><?=$lng['Advance payment']?></th>
							<td class="tar"><?=$pay['advance']?></td>
						</tr>
						<tr>
							<th><?=$lng['Legal deductions']?></th>
							<td class="tar"><?=$pay['legal_deductions']?></td>
						</tr>
						<tr>
							<th class="H1"><?=$lng['NET PAID SALARY']?></th>
							<th class="tar H1"><?=$net?></th>
						</tr>
					</tbody>
				</table>
			
			</td><td style="padding-left:7px; border-left:1px solid #ddd; vertical-align:top; width:30%">
	
				<table class="reportTable" border="0" style="table-layout:fixed">
					<tbody>
						<tr>
							<th class="H1" style="width:75%"><?=$lng['Tax calculation']?></th>
							<th class="H1 tar"><?=$lng['Baht']?></th>
						</tr>
						<tr>
							<th><?=$lng['Gross year income']?></th>
							<td class="tar"><?=$gross_year?></td>
						</tr>
						<tr>
							<th><?=$lng['Total tax deductions']?></th>
							<td class="tar"><?=$tax_deductions?></td>
						</tr>
						<tr>
							<th><?=$lng['Taxable year income']?></th>
							<td class="tar"><?=$taxable_year?></td>
						</tr>
						<tr>
							<th><?=$lng['Tax whole year']?></th>
							<td class="tar"><?=$tax_year?></td>
						</tr>
						<!--<tr>
							<th><?=$lng['Tax month']?></th>
							<td class="tar"><?=$tax_month?></td>
						</tr>-->
						<tr>
							<th><?=$lng['Modified tax']?></th>
							<td class="tar"><?=$tax_modify?></td>
						</tr>
						<tr>
							<th><?=$lng['Tax this month']?></th>
							<td class="tar"><?=$tax_this_month?></td>
						</tr>
					</tbody>
				</table>
		
				<table class="reportTable" border="0" style="table-layout:fixed">
					<tbody>
						<tr>
							<th class="H1" style="width:75%"><?=$lng['Tax deductions']?></th>
							<th class="H1 tar"><?=$lng['Baht']?></th>
						</tr>
						<? if($taxes){ foreach($taxes as $k=>$v){
								echo '<tr>';
								echo '<th>'.$k.'</th>';
								echo '<td class="tar">'.$v.'</td>';
								echo '</tr>';
							}}else{
								echo '<tr><td colspan="2">'.$lng['No data available'].'</td></tr>';
							} if($taxes){ ?>
						<tr>
							<th class="H1"><?=$lng['Total tax deductions']?></th>
							<th class="tar H1"><?=$tax_deductions?></th>
						</tr>
						<? } ?>
					</tbody>
				</table>
			
			</td></tr></table>
			</div>
	
		</div>
		
	</div>
	
<script type="text/javascript">
	
	$(document).ready(function() {
		
		var employees = <?=$emp_array?>;
		//var emps = <?//=json_encode($emps)?>;
		$('#selectEmployee').devbridgeAutocomplete({
			 lookup: employees,
			 onSelect: function (suggestion) {
			 	//$("#emp_id").val(suggestion.data);
				//alert(suggestion.data);
				$.ajax({
					//url: ROOT+"reports/ajax/select_employee.php",
					url: "ajax/select_employee.php",
					data: {id: suggestion.data},
					success: function(response) {
						//$('#dump').html(response);
						location.reload();
					},
					error: function (xhr, ajaxOptions, thrownError) {
						alert(thrownError);
					}
				});
			 }
		});	

		$('#monthFilter').on('change', function(){
			window.location.href = "index.php?mn=451&m="+this.value;
		})

		$('#exportExcel').on('click', function(){
			//window.location.href = ROOT+'reports/excel/export_employee_month_excel.php?m=' + <?=$_GET['m']?>;
			window.location.href = 'excel/export_employee_month_excel.php?m=' + <?=$_GET['m']?>;
		})

	});
	
</script>

	
	
	
	
	
	
	
	
	
	
	
	
	
	
	

