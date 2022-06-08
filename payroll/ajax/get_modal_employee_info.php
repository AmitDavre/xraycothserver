<?php
	if(session_id()==''){session_start(); ob_start();}
	include('../../dbconnect/db_connect.php');
	include(DIR.'files/functions.php');
	include(DIR.'files/arrays_'.$_SESSION['rego']['lang'].'.php');
	include(DIR.'files/payroll_functions.php');
	//$allow_names = getAllownames($pr_settings, $_SESSION['rego']['lang']);
	
	$fix_allow = getUsedFixAllow($_SESSION['rego']['lang']);
	//$var_allow = getUsedVarAllow($_SESSION['rego']['lang']);
	//var_dump($fix_allow);
	
//$yesno = array('N'=>'No','Y'=>'Yes');
	$sql = "SELECT * FROM ".$_SESSION['rego']['emp_dbase']." WHERE emp_id = '".$_REQUEST['id']."'";
	$res = $dbc->query($sql);
	$row = $res->fetch_assoc();
	//$tax = unserialize($row['tax_deductions']);
	//var_dump($tax);
	
	$table = '
		<table class="modalTable">
			<thead>
			<tr>
				<th>'.$lng['Description'].'</th>
				<th>'.$lng['Value'].'</th>
			</tr>
			</thead>
			<tbody>
			<tr><td>'.$lng['Joining date'].'</td><td>'.date('d-m-Y', strtotime($row['joining_date'])).'</td></tr>
			<tr><td>'.$lng['PVF rate employee'].'</td><td>'.number_format($row['pvf_rate_emp'],2).' %</td></tr>
			<tr><td>'.$lng['PVF rate employer'].'</td><td>'.number_format($row['pvf_rate_com'],2).' %</td></tr>
			<tr><td>'.$lng['Basic salary'].'</td><td class="tar">'.number_format($row['base_salary'],2).'</td></tr>';
			for($i=1;$i<=10;$i++){
				if($row['fix_allow_'.$i]>0){$table .= '<tr><td>'.$fix_allow[$i].'</td><td class="tar">'.number_format($row['fix_allow_'.$i],2).'</td></tr>';}
			}
	if($row['tax_allow_spouse']>0){$table .= '<tr><td>'.$lng['Spouse care allowance'].'</td><td class="tar">'.number_format($row['tax_allow_spouse']).'</td></tr>';}
	if($row['tax_allow_parents']>0){$table .= '<tr><td>'.$lng['Parents care allowance'].'</td><td class="tar">'.number_format($row['tax_allow_parents']).'</td></tr>';}
	if($row['tax_allow_parents_inlaw']>0){$table .= '<tr><td>'.$lng['Parents in law care allowance'].'</td><td class="tar">'.number_format($row['tax_allow_parents_inlaw']).'</td></tr>';}
	if($row['tax_allow_disabled_person']>0){$table .= '<tr><td>'.$lng['Care allowance disabled person'].'</td><td class="tar">'.number_format($row['tax_allow_disabled_person']).'</td></tr>';}
	if($row['tax_allow_child_bio']>0){$table .= '<tr><td>'.$lng['Child care allowance'].'</td><td class="tar">'.number_format($row['tax_allow_child_bio']).'</td></tr>';}
	if($row['tax_allow_child_bio_2018']>0){$table .= '<tr><td>'.$lng['Child care allowance'].'</td><td class="tar">'.number_format($row['tax_allow_child_bio_2018']).'</td></tr>';}
	if($row['tax_allow_child_adopted']>0){$table .= '<tr><td>'.$lng['Child care allowance'].'</td><td class="tar">'.number_format($row['tax_allow_child_adopted']).'</td></tr>';}
	if($row['tax_allow_child_birth']>0){$table .= '<tr><td>'.$lng['Child birth (baby bonus) allowance'].'</td><td class="tar">'.number_format($row['tax_allow_child_birth']).'</td></tr>';}
	if($row['tax_allow_own_health']>0){$table .= '<tr><td>'.$lng['Own health insurance premium allowance'].'</td><td class="tar">'.number_format($row['tax_allow_own_health']).'</td></tr>';}
	if($row['tax_allow_own_life_insurance']>0){$table .= '<tr><td>'.$lng['Own life insurance allowance'].'</td><td class="tar">'.number_format($row['tax_allow_own_life_insurance']).'</td></tr>';}
	if($row['tax_allow_health_parents']>0){$table .= '<tr><td>'.$lng['Health insurance premium allowance for parents & parents in law'].'</td><td class="tar">'.number_format($row['tax_allow_health_parents']).'</td></tr>';}
	if($row['tax_allow_life_insurance_spouse']>0){$table .= '<tr><td>'.$lng['Life insurance allowance spouse'].'</td><td class="tar">'.number_format($row['tax_allow_life_insurance_spouse']).'</td></tr>';}
	if($row['tax_allow_pension_fund']>0){$table .= '<tr><td>'.$lng['Pension fund allowance'].'</td><td class="tar">'.number_format($row['tax_allow_pension_fund']).'</td></tr>';}
	if($row['tax_allow_nsf']>0){$table .= '<tr><td>'.$lng['NSF allowance'].'</td><td class="tar">'.number_format($row['tax_allow_nsf']).'</td></tr>';}
	if($row['tax_allow_rmf']>0){$table .= '<tr><td>'.$lng['RMF allowance'].'</td><td class="tar">'.number_format($row['tax_allow_rmf']).'</td></tr>';}
	if($row['tax_allow_ltf']>0){$table .= '<tr><td>'.$lng['LTF amount'].'</td><td class="tar">'.number_format($row['tax_allow_ltf']).'</td></tr>';}
	if($row['tax_allow_home_loan_interest']>0){$table .= '<tr><td>'.$lng['Home loan interest allowance'].'</td><td class="tar">'.number_format($row['tax_allow_home_loan_interest']).'</td></tr>';}
	if($row['tax_allow_donation_charity']>0){$table .= '<tr><td>'.$lng['Donation charity'].'</td><td class="tar">'.number_format($row['tax_allow_donation_charity']).'</td></tr>';}
	if($row['tax_allow_donation_flood']>0){$table .= '<tr><td>'.$lng['Donation flooding'].'</td><td class="tar">'.number_format($row['tax_allow_donation_flood']).'</td></tr>';}
	if($row['tax_allow_donation_education']>0){$table .= '<tr><td>'.$lng['Donation education'].'</td><td class="tar">'.number_format($row['tax_allow_donation_education']).'</td></tr>';}
	if($row['tax_allow_exemp_disabled_under']>0){$table .= '<tr><td>'.$lng['Exemption disabled person under 65 years'].'</td><td class="tar">'.number_format($row['tax_allow_exemp_disabled_under']).'</td></tr>';}
	if($row['tax_allow_exemp_payer_older']>0){$table .= '<tr><td>'.$lng['Exemption for a tax payer of 65 years or more'].'</td><td class="tar">'.number_format($row['tax_allow_exemp_payer_older']).'</td></tr>';}
	if($row['tax_allow_first_home']>0){$table .= '<tr><td>'.$lng['First home buyers allowance'].'</td><td class="tar">'.number_format($row['tax_allow_first_home']).'</td></tr>';}
	if($row['tax_allow_year_end_shopping']>0){$table .= '<tr><td>'.$lng['Year-end shopping allowance'].'</td><td class="tar">'.number_format($row['tax_allow_year_end_shopping']).'</td></tr>';}
	if($row['tax_allow_domestic_tour']>0){$table .= '<tr><td>'.$lng['Domestic tour allowance'].'</td><td class="tar">'.number_format($row['tax_allow_domestic_tour']).'</td></tr>';}
	if($row['tax_allow_other']>0){$table .= '<tr><td>'.$lng['Other allowance'].'</td><td class="tar">'.number_format($row['tax_allow_other']).'</td></tr>';}
	if($row['tax_allow_sso']>0){$table .= '<tr><td>'.$lng['Social Security Fund'].'</td><td class="tar">'.number_format($row['tax_allow_sso']).'</td></tr>';}
	if($row['tax_allow_pvf']>0){$table .= '<tr><td>'.$lng['Provident fund allowance'].'</td><td class="tar">'.number_format($row['tax_allow_pvf']).'</td></tr>';}
			
	$table .= '</tbody></table>';
	
	echo $table; exit;
?>