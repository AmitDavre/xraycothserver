<?
	if(session_id()==''){session_start();}
	ob_start();
	include('../../dbconnect/db_connect.php');
	//include(DIR.'files/functions.php');
	//include(DIR.'files/payroll_functions.php');
	//var_dump($_REQUEST); exit;
	
	$data = array();
	$sql = "SELECT  
		SUM(salary) as salary, 
		SUM(total_otb) as ot, 
		SUM(total_fix_allow) as tot_fix_allow, 
		SUM(total_var_allow) as tot_var_allow, 
		(SUM(pvf_employee)+SUM(psf_employee)) as pvf_emp, 
		(SUM(pvf_employer)+SUM(psf_employer)) as pvf_com, 
		SUM(social) as social_emp, 
		SUM(social_com) as social_com, 
		SUM(tax_month) as tax, 
		SUM(tax_by_company) as tax_com, 
		SUM(sso_by_company) as sso_com, 
		(SUM(severance)+SUM(notice_payment)+SUM(remaining_salary)+SUM(paid_leave)) as contract, 
		SUM(other_income) as other_income, 
		SUM(fix_deduct_before) as fix_before, 
		SUM(var_deduct_before) as var_before, 
		SUM(fix_deduct_after) as fix_after, 
		SUM(var_deduct_after) as var_after, 
		
		SUM(advance) as advance, 
		SUM(legal_deductions) as legal 
		FROM ".$_SESSION['rego']['payroll_dbase']." WHERE month = '".$_SESSION['rego']['cur_month']."' AND emp_group = '".$_SESSION['rego']['emp_group']."'";
	if($res = $dbc->query($sql)){
		$data = $res->fetch_assoc();
	}else{
		$data['salary'] = 0;
		$data['tot_fix_allow'] = 0;
		$data['tot_var_allow'] = 0;
		$data['fix_deduct'] = 0;
		$data['var_deduct'] = 0;
		$data['ot'] = 0;
		$data['pvf_emp'] = 0; 
		$data['pvf_com'] = 0; 
		$data['social_emp'] = 0;
		$data['social_com'] = 0;
		$data['tax'] = 0;
		$data['advance'] = 0;
		$data['legal'] = 0;
		$data['contract'] = 0;
		$data['other_income'] = 0;
	}
	$other_deduct = $data['fix_after'] + $data['var_after'];
	$tot_fix = $data['salary'] + $data['tot_fix_allow'] - $data['fix_before'];
	$tot_var = $data['tot_var_allow'] + $data['ot'] + $data['other_income'] + $data['contract'] + $data['tax_com'] + $data['sso_com'] - $data['var_before'];
	$tot_gross = $tot_fix + $tot_var;
	$tot_net = $tot_gross - $data['pvf_emp'] - $data['social_emp'] - $data['tax'] - $other_deduct;
	$tot_salary = $tot_net - $data['advance'] - $data['legal'];
	$tot_cost = $tot_gross + $data['pvf_com'] + $data['social_com'];
	
	//var_dump($data); exit;
	//var_dump($row); //exit;
	
	$mytable = '
		<table class="summaryTable">
			<thead>
				<tr>
					<th colspan="2">'.$lng['Fixed income'].'</th>
				</tr>
			</thead>
			<tbody>
				<tr>
					<td class="pt3">'.$lng['Salary'].'</td>
					<td class="tar pt3">'.number_format($data['salary'],2).'</td>
				</tr>
				<tr>
					<td>'.$lng['Fixed allowances'].'</td>
					<td class="tar">'.number_format($data['tot_fix_allow'],2).'</td>
				</tr>
				<tr>
					<td>'.$lng['Fixed deductions'].'</td>
					<td class="tar">-'.number_format($data['fix_before'],2).'</td>
				</tr>
				<tr>
					<th class="pb5">'.$lng['Total fixed income'].'</th>
					<th class="tar pb5">'.number_format($tot_fix,2).'</th>
				</tr>
			</tbody>
			<thead>
				<tr>
					<th colspan="2">'.$lng['Variable income'].'</th>
				</tr>
			</thead>
			<tbody>
				<tr>
					<td class="pt3">'.$lng['Overtime'].'</td>
					<td class="tar pt3">'.number_format($data['ot'],2).'</td>
				</tr>
				<tr>
					<td>'.$lng['Variable allowances'].'</td>
					<td class="tar">'.number_format($data['tot_var_allow'],2).'</td>
				</tr>
				<tr>
					<td>'.$lng['Variable deductions'].'</td>
					<td class="tar">-'.number_format($data['var_before'],2).'</td>
				</tr>
				<tr>
					<td>'.$lng['Other income'].'</td>
					<td class="tar">'.number_format($data['other_income'],2).'</td>
				</tr>
				<tr>
					<td>'.$lng['Tax by company'].'</td>
					<td class="tar">'.number_format($data['tax_com'],2).'</td>
				</tr>
				<tr>
					<td>'.$lng['SSO by company'].'</td>
					<td class="tar">'.number_format($data['sso_com'],2).'</td>
				</tr>
				<tr>
					<td>'.$lng['End contract'].'</td>
					<td class="tar">'.number_format($data['contract'],2).'</td>
				</tr>
				<tr>
					<th class="pb5">'.$lng['Total variable income'].'</th>
					<th class="tar pb5">'.number_format($tot_var,2).'</th>
				</tr>
			</tbody>
			<thead>
				<tr>
					<th>'.$lng['Gross income this month'].'</th>
					<th class="tar">'.number_format($tot_gross,2).'</th>
				</tr>
			</thead>
			<tbody>
				<tr>
					<td class="pt3">'.$lng['PVF'].' / '.$lng['PSF'].'</td>
					<td class="tar pt3">'.number_format($data['pvf_emp'],2).'</td>
				</tr>
				<tr>
					<td>'.$lng['SSO'].'</td>
					<td class="tar">'.number_format($data['social_emp'],2).'</td>
				</tr>
				<tr>
					<td>'.$lng['TAX'].'</td>
					<td class="tar">'.number_format($data['tax'],2).'</td>
				</tr>
				<tr>
					<td class="pb5">'.$lng['Other deductions'].'</td>
					<td class="tar pb5">'.number_format($other_deduct,2).'</td>
				</tr>
			</tbody>
			<thead>
				<tr>
					<th>'.$lng['Net income this month'].'</th>
					<th class="tar">'.number_format($tot_net,2).'</th>
				</tr>
			</thead>
			<tbody>
				<tr>
					<td class="pt3">'.$lng['Advance payment'].'</td>
					<td class="tar pt3">'.number_format($data['advance'],2).'</td>
				</tr>
				<tr>
					<td class="pb5">'.$lng['Legal deductions'].'</td>
					<td class="tar pb5">'.number_format($data['legal'],2).'</td>
				</tr>
			</tbody>
			<thead>
				<tr>
					<th>'.$lng['Net paid salary this month'].'</th>
					<th class="tar">'.number_format($tot_salary,2).'</th>
				</tr>
			</thead>
			<tbody>
				<tr>
					<td class="pt3">'.$lng['PVF'].' / '.$lng['PSF'].' '.$lng['Company'].'</td>
					<td class="tar pt3">'.number_format($data['pvf_com'],2).'</td>
				</tr>
				<tr>
					<td class="pb5">'.$lng['SSO'].' '.$lng['Company'].'</td>
					<td class="tar pb5">'.number_format($data['social_com'],2).'</td>
				</tr>
			</tbody>
			<thead>
				<tr>
					<th>'.$lng['Total cost this month'].'</th>
					<th class="tar">'.number_format($tot_cost,2).'</th>
				</tr>
			</thead>
		</table>';
	
			
	echo $mytable;


?>





