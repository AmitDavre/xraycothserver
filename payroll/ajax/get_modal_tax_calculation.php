<?php
	if(session_id()==''){session_start(); ob_start();}
	include('../../dbconnect/db_connect.php');
	include(DIR.'files/functions.php');
	include(DIR.'files/payroll_functions.php');
	//$dlng = getTaxdeductionNames();
	//var_dump($dlng);
	
	$res = $dbc->query("SELECT tax_calculation FROM ".$_SESSION['rego']['payroll_dbase']." WHERE emp_id = '".$_REQUEST["id"]."' AND month = '".$_SESSION['rego']['cur_month']."'");
	$row = $res->fetch_assoc();
	$data = unserialize($row['tax_calculation']);
	//var_dump($data); exit;
	
	// TEMPORARY FIX Januari to June
	//if(isset($data['taxable_allowance'])){$data['tot_tax_allow'] = $data['taxable_allowance'];}
	//if(isset($data['non_taxable_allowance'])){$data['tot_non_allow'] = $data['non_taxable_allowance'];}
	//if(isset($data['gross_year'])){$data['year_income'] = $data['gross_year'];}
	//if(isset($data['tax_thismonth'])){$data['tax'] = $data['tax_thismonth'];}
	
	$table = '
			<table class="modalTable">
				<thead>
				<tr style="background:#eee">
					<th style="padding:4px 5px width:60%">'.$lng['Description'].' - '.strtoupper($data['calc_method']).'</th>
					<th class="tar">'.$lng['Amount'].'</th>
				</tr>
				</thead>
				<tbody>
				<tr><td>'.$lng['Basic salary'].'</td><td style="text-align:right">'.number_format($data['basic_salary'],2).'</td></tr>
				<tr><td>'.$lng['Salary this month'].'</td><td style="text-align:right">'.number_format($data['salary'],2).'</td></tr>
				<tr><td>'.$lng['Fixed income'].'</td><td style="text-align:right">'.number_format($data['fix_income'],2).'</td></tr>
				<tr><td>'.$lng['Variable income'].'</td><td style="text-align:right">'.number_format($data['var_income'],2).'</td></tr>
				<tr><td>'.$lng['Gross year income'].'</td><td style="text-align:right">'.number_format($data['gross_year'],2).'</td></tr>
				
				<tr><td>'.$lng['Standard deduction'].'</td><td style="text-align:right;color:#c00">'.number_format($data['standard_deduction'],2).'</td></tr>
				<tr><td>'.$lng['Personal allowance'].'</td><td style="text-align:right;color:#c00">'.number_format($data['personal_allowance'],2).'</td></tr>';
				if($data['spouse_allowance']>0){$table .= '<tr><td>'.$lng['Spouse care allowance'].'</td><td style="text-align:right;color:#c00">'.number_format($data['spouse_allowance'],2).'</td></tr>';}
				if($data['parents_allowance']>0){$table .= '<tr><td>'.$lng['Parents care allowance'].'</td><td style="text-align:right;color:#c00">'.number_format($data['parents_allowance'],2).'</td></tr>';}
				if($data['parents_inlaw_allowance']>0){$table .= '<tr><td>'.$lng['Parents in law care allowance'].'</td><td style="text-align:right;color:#c00">'.number_format($data['parents_inlaw_allowance'],2).'</td></tr>';}
				if($data['disabled_allowance']>0){$table .= '<tr><td>'.$lng['Care allowance disabled person'].'</td><td style="text-align:right;color:#c00">'.number_format($data['disabled_allowance'],2).'</td></tr>';}
				
				if($data['child_allowance']>0){$table .= '<tr><td>'.$lng['Child care - biological'].'</td><td style="text-align:right;color:#c00">'.number_format($data['child_allowance'],2).'</td></tr>';}
				
				if($data['child_allowance_2018']>0){$table .= '<tr><td>'.$lng['Child care - biological 2018/19/20'].'</td><td style="text-align:right;color:#c00">'.number_format($data['child_allowance_2018'],2).'</td></tr>';}
				
				if($data['child_allowance_adopted']>0){$table .= '<tr><td>'.$lng['Child care - adopted'].'</td><td style="text-align:right;color:#c00">'.number_format($data['child_allowance_adopted'],2).'</td></tr>';}
				
				if($data['child_birth_bonus']>0){$table .= '<tr><td>'.$lng['Child birth (Baby bonus)'].'</td><td style="text-align:right;color:#c00">'.number_format($data['child_birth_bonus'],2).'</td></tr>';}
				if($data['own_health_insurance']>0){$table .= '<tr><td>'.$lng['Own health insurance premium allowance'].'</td><td style="text-align:right;color:#c00">'.number_format($data['own_health_insurance'],2).'</td></tr>';}
				if($data['own_life_insurance']>0){$table .= '<tr><td>'.$lng['Own life insurance allowance'].'</td><td style="text-align:right;color:#c00">'.number_format($data['own_life_insurance'],2).'</td></tr>';}
				if($data['health_insurance_parent']>0){$table .= '<tr><td>'.$lng['Health insurance premium allowance for parents & parents in law'].'</td><td style="text-align:right;color:#c00">'.number_format($data['health_insurance_parent'],2).'</td></tr>';}
				if($data['life_insurance_spouse']>0){$table .= '<tr><td>'.$lng['Life insurance allowance spouse'].'</td><td style="text-align:right;color:#c00">'.number_format($data['life_insurance_spouse'],2).'</td></tr>';}
				if($data['pension_fund_allowance']>0){$table .= '<tr><td>'.$lng['Pension fund allowance'].'</td><td style="text-align:right;color:#c00">'.number_format($data['pension_fund_allowance'],2).'</td></tr>';}
				if($data['nsf_allowance']>0){$table .= '<tr><td>'.$lng['NSF allowance'].'</td><td style="text-align:right;color:#c00">'.number_format($data['nsf_allowance'],2).'</td></tr>';}
				if($data['rmf_allowance']>0){$table .= '<tr><td>'.$lng['RMF allowance'].'</td><td style="text-align:right;color:#c00">'.number_format($data['rmf_allowance'],2).'</td></tr>';}
				if($data['ltf_deduction']>0){$table .= '<tr><td>'.$lng['LTF amount'].'</td><td style="text-align:right;color:#c00">'.number_format($data['ltf_deduction'],2).'</td></tr>';}
				if($data['home_loan_interest']>0){$table .= '<tr><td>'.$lng['Home loan interest allowance'].'</td><td style="text-align:right;color:#c00">'.number_format($data['home_loan_interest'],2).'</td></tr>';}
				if($data['donation_charity']>0){$table .= '<tr><td>'.$lng['Donation charity'].'</td><td style="text-align:right;color:#c00">'.number_format($data['donation_charity'],2).'</td></tr>';}
				if($data['donation_flood']>0){$table .= '<tr><td>'.$lng['Donation flooding'].'</td><td style="text-align:right;color:#c00">'.number_format($data['donation_flood'],2).'</td></tr>';}
				if($data['donation_education']>0){$table .= '<tr><td>'.$lng['Donation education'].'</td><td style="text-align:right;color:#c00">'.number_format($data['donation_education'],2).'</td></tr>';}
				if($data['exemp_disabled_under']>0){$table .= '<tr><td>'.$lng['Exemption disabled person under 65 years'].'</td><td style="text-align:right;color:#c00">'.number_format($data['exemp_disabled_under'],2).'</td></tr>';}
				if($data['exemp_payer_older']>0){$table .= '<tr><td>'.$lng['Exemption for a tax payer of 65 years or more'].'</td><td style="text-align:right;color:#c00">'.number_format($data['exemp_payer_older'],2).'</td></tr>';}
				if($data['first_home_allowance']>0){$table .= '<tr><td>'.$lng['First home buyers allowance'].'</td><td style="text-align:right;color:#c00">'.number_format($data['first_home_allowance'],2).'</td></tr>';}
				if($data['year_end_shop_allowance']>0){$table .= '<tr><td>'.$lng['Year-end shopping allowance'].'</td><td style="text-align:right;color:#c00">'.number_format($data['year_end_shop_allowance'],2).'</td></tr>';}
				if($data['domestic_tour_allowance']>0){$table .= '<tr><td>'.$lng['Domestic tour allowance'].'</td><td style="text-align:right;color:#c00">'.number_format($data['domestic_tour_allowance'],2).'</td></tr>';}
				if($data['other_allowance']>0){$table .= '<tr><td>'.$lng['Other allowance'].'</td><td style="text-align:right;color:#c00">'.number_format($data['other_allowance'],2).'</td></tr>';}

	$table .= '			
				<tr><td>'.$lng['Social security fund'].'</td><td style="text-align:right;color:#c00">'.number_format($data['sso_year'],2).'</td></tr>
				<tr><td>'.$lng['Provident fund'].'</td><td style="text-align:right;color:#c00">'.number_format($data['pvf_year'],2).'</td></tr>
				<tr><td>'.$lng['Total tax deductions'].'</td><td style="text-align:right"><b style="color:#c00">'.number_format($data['tax_deductions'],2).'</b></td></tr>
				
				<tr><td>'.$lng['Taxable year income'].'</td><td style="text-align:right"><b>'.number_format($data['taxable_year'],2).'</b></td></tr>
				<tr><td>'.$lng['Tax whole year'].'</td><td style="text-align:right"><b>'.number_format($data['tax_year'],2).'</b></td></tr>';
				if(isset($data['tax_modify'])){ $mcolor = 'color:#000'; if($data['tax_modify'] < 0){$mcolor = 'color:#b00';}
		$table .= '	
				<tr><td>'.$lng['Tax month'].'</td><td style="text-align:right"><b>'.number_format($data['tax_this_month'],2).'</b></td></tr>
				<tr><td>'.$lng['Modify tax'].'</td><td style="text-align:right;'.$mcolor.'">'.number_format($data['tax_modify'],2).'</td></tr>';
				}
		$table .= '			
				<tr><td>'.$lng['Tax this month'].'</td><td style="text-align:right"><b>'.number_format($data['tax_month'],2).'</b></td></tr>
				<tr><td>'.$lng['Tax next month'].'</td><td style="text-align:right"><b>'.number_format($data['tax_next_month'],2).'</b></td></tr>
				</tbody>
			</table>';
	
	//var_dump($row);
	//var_dump(getAccumulated($_REQUEST["id"]));
	echo $table;
	//echo json_encode($data);
?>