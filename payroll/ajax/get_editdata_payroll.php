<?
	if(session_id()==''){session_start(); ob_start();}
	include('../../dbconnect/db_connect.php');
	include(DIR.'files/functions.php');
	//include(DIR.'files/payroll_functions.php');
	//var_dump($_REQUEST); exit;
	
	$fix = getFixAllowNames();
	$var = getVarAllowNames();
	
	$dfix = getFixDeductNames();
	$dvar = getVarDeductNames();
	
	$taxes = array();
	$allow['fix'] = array();
	$allow['var'] = array();
	$deduct['fix'] = array();
	$deduct['var'] = array();

	$data = array();
	$sql = "SELECT *, 
		(emp_name_".$lang.") as emp_name, 
		(pvf_employee + psf_employee) as pvf_emp, 
		(pvf_employer + psf_employer) as pvf_com, 
		(severance + notice_payment + remaining_salary + paid_leave) as contract 

		FROM ".$_SESSION['rego']['payroll_dbase']." WHERE id = '".$_REQUEST["id"]."' AND month = '".$_SESSION['rego']['cur_month']."'";
	if($res = $dbc->query($sql)){
		$data = $res->fetch_assoc();
		
		for($i=1;$i<=10;$i++){
			if($data['fix_allow_'.$i] > 0){$allow['fix'][$i] = round($data['fix_allow_'.$i],2);}
		}
		for($i=1;$i<=10;$i++){
			if($data['var_allow_'.$i] > 0){$allow['var'][$i] = round($data['var_allow_'.$i],2);}
		}
		
		for($i=1;$i<=5;$i++){
			if($data['fix_deduct_'.$i] > 0){$deduct['fix'][$i] = round($data['fix_deduct_'.$i],2);}
		}
		for($i=1;$i<=5;$i++){
			if($data['var_deduct_'.$i] > 0){$deduct['var'][$i] = round($data['var_deduct_'.$i],2);}
		}
		
		$data['tot_fix'] = $data['salary'] + $data['total_fix_allow'] - $data['fix_deduct_before'];
		$data['tot_var'] = $data['total_var_allow'] + $data['total_otb'] + $data['other_income'] + $data['tax_by_company'] + $data['sso_by_company'] + $data['contract'] - $data['var_deduct_before'];
		$data['other_deduct'] = $data['fix_deduct_after'] + $data['var_deduct_after'];
		
		$data['gross'] = $data['tot_fix'] + $data['tot_var'];
		//$data['net'] = $data['gross'] - $data['pvf_emp'] - $data['social'] - $data['tax_month'] - $data['other_deduct'];
		$data['net'] = $data['net_income'] + $data['advance'];
		//$data['net_paid'] = $data['net'] - $data['advance'] - $data['legal_deductions'];
		$data['net_paid'] = $data['net_income'];
		$data['tot_cost'] = $data['gross'] + $data['pvf_com'] + $data['social_com'];
	
		$calculation = unserialize($data['tax_calculation']);
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
	
		if(!isset($calculation['tax_deductions'])){$data['tax_deductions'] = '-';}else{$data['tax_deductions'] = number_format($calculation['tax_deductions'],2);}
		if(!isset($calculation['gross_year'])){$data['gross_year'] = '-';}else{$data['gross_year'] = number_format($calculation['gross_year'],2);}
		if(!isset($calculation['taxable_year'])){$data['taxable_year'] = '-';}else{$data['taxable_year'] = number_format($calculation['taxable_year'],2);}
		if(!isset($calculation['tax_year'])){$data['tax_year'] = '-';}else{$data['tax_year'] = number_format($calculation['tax_year'],2);}
		//if(!isset($calculation['tax_month'])){$data['tax_month'] = '-';}else{$data['tax_month'] = number_format($calculation['tax_month'],2);}
		if(!isset($calculation['tax_modify'])){$data['tax_modify'] = '-';}else{$data['tax_modify'] = number_format($calculation['tax_modify'],2);}
		if(!isset($calculation['tax_this_month'])){$data['tax_this_month'] = '-';}else{$data['tax_this_month'] = number_format($calculation['tax_this_month'],2);}
		
		
	}
	
	$fixallowTable = '';
	if($allow['fix']){ foreach($allow['fix'] as $k=>$v){
		$fixallowTable .= '<tr>';
		$fixallowTable .= '<th>'.$fix[$k].'</th>';
		$fixallowTable .= '<td class="nopad"><input name="fix_allow_'.$k.'" class="sel float72 tar" type="text" value="'.$v.'"></td>';
		$fixallowTable .= '</tr>';
	}}else{
		$fixallowTable .= '<tr><td colspan="2">'.$lng['No data available'].'</td></tr>';
	}
	$data['fixallowTable'] = $fixallowTable;
	
	$varallowTable = '';
	if($allow['var']){ foreach($allow['var'] as $k=>$v){
		$varallowTable .= '<tr>';
		$varallowTable .= '<th>'.$var[$k].'</th>';
		$varallowTable .= '<td class="nopad"><input name="var_allow_'.$k.'" class="sel float72 tar" type="text" value="'.$v.'"></td>';
		$varallowTable .= '</tr>';
	}}else{
		$varallowTable .= '<tr><td colspan="2">'.$lng['No data available'].'</td></tr>';
	}
	$data['varallowTable'] = $varallowTable;
	
	$fixdeductTable = '';
	if($deduct['fix']){ foreach($deduct['fix'] as $k=>$v){
		$fixdeductTable .= '<tr>';
		$fixdeductTable .= '<th>'.$dfix[$k].'</th>';
		$fixdeductTable .= '<td class="nopad"><input name="fix_deduct_'.$k.'" class="sel float72 tar" type="text" value="'.$v.'"></td>';
		$fixdeductTable .= '</tr>';
	}}else{
		$fixdeductTable .= '<tr><td colspan="2">'.$lng['No data available'].'</td></tr>';
	}
	$data['fixdeductTable'] = $fixdeductTable;
	
	$vardeductTable = '';
	if($deduct['var']){ foreach($deduct['var'] as $k=>$v){
		$vardeductTable .= '<tr>';
		$vardeductTable .= '<th>'.$dvar[$k].'</th>';
		$vardeductTable .= '<td class="nopad"><input name="var_deduct_'.$k.'" class="sel float72 tar" type="text" value="'.$v.'"></td>';
		$vardeductTable .= '</tr>';
	}}else{
		$vardeductTable .= '<tr><td colspan="2">'.$lng['No data available'].'</td></tr>';
	}
	$data['vardeductTable'] = $vardeductTable;
	
	
	$taxTable = '';
	if($taxes){ foreach($taxes as $k=>$v){
		$taxTable .= '<tr>';
		$taxTable .= '<th>'.$k.'</th>';
		$taxTable .= '<td class="tar">'.$v.'</td>';
		$taxTable .= '</tr>';
	}}else{
		$taxTable .= '<tr><td colspan="2">'.$lng['No data available'].'</td></tr>';
	}
	if($taxes){
		$taxTable .= '<tr>';
		$taxTable .= '<th>'.$lng['Total tax deductions'].'</th>';
		$taxTable .= '<th class="tar">'.$data['tax_deductions'].'</th>';
		$taxTable .= '</tr>';
	}
	$data['taxTable'] = $taxTable;
	
	//var_dump($data); exit;
	echo json_encode($data); exit;
	





