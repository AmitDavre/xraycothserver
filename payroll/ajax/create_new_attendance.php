<?php
	
	if(session_id()==''){session_start();}
	ob_start();
	include('../../dbconnect/db_connect.php');
	include(DIR.'files/payroll_functions.php');
	//include(DIR.'files/functions.php');
	//include(DIR.'files/arrays_'.$_SESSION['rego']['lang'].'.php');
	
	$d = $_SESSION['rego']['cur_year'].'-'.$_SESSION['rego']['curr_month'].'-01';
	$som = date('Y-m-d', strtotime($d));
	$eom = date('Y-m-t', strtotime($d));
	//$eom = date($_SESSION['rego']['cur_year'].'-'.$_SESSION['rego']['curr_month'].'-t');
	//$eom = '2021-01-31';//date($_SESSION['rego']['cur_year'].'-'.$_SESSION['rego']['curr_month'].'-t');
	$data = array();
	
	// echo '<pre>';
	//var_dump($d);
	//var_dump($_SESSION['rego']['cur_year']);
	//var_dump($_SESSION['rego']['curr_month']);
	//var_dump($som);
	//var_dump($eom); //exit;

	$sessionpayrollDbase = $_SESSION['rego']['cid'].'_payroll_'.$_SESSION['rego']['cur_year'];
	
	$sql = "SELECT * FROM ".$cid."_employees WHERE base_salary > 0 AND 
		joining_date <= '".$eom."' 
		AND (resign_date >= '".$som."' AND resign_date <= '".$eom."' OR emp_status = '1') 
		ORDER by resign_date DESC, emp_id ASC ";
	//$sql = "SELECT * FROM ".$cid."_employees WHERE base_salary > 0 AND joining_date <= '".$eom."' AND emp_status = 1 ORDER by emp_id ASC ";
	
	
	if($res = $dbc->query($sql)){
		while($row = $res->fetch_assoc()){
			$data[] = $row;
		}
	}else{
		echo mysqli_error($dbc);
		exit;
	}
	//echo $sql;
	//var_dump($data); exit;
	
	if($data){
		
		$sql = "INSERT INTO ".$sessionpayrollDbase." (id, emp_id, month, entity, branch, division, department, team, emp_group, position, emp_name_th, emp_name_en, basic_salary, paid_days, ";
		for($i=1;$i<=10;$i++){
			$sql .= "fix_allow_".$i.",";
		}
		for($i=1;$i<=5;$i++){
			$sql .= "fix_deduct_".$i.",";
		}
		$sql .= "remaining_salary, notice_payment, paid_leave, severance, legal_deductions, other_income, calc_tax, calc_sso, calc_pvf, calc_method, sso_rate_emp, sso_rate_com, pvf_rate_emp, pvf_rate_com, psf_rate_emp, psf_rate_com, contract_type, calc_base, base_ot_rate, ot_rate, sso_by, paid) VALUES ";
		
		$counter = 1; // Counter for max employees subscription
		foreach($data as $k=>$v){
			
			$workdays = ($rego_settings['days_month'] == 0 ? 30 : $rego_settings['days_month']);
			$tmp = getEmployeeWorkedDays($v['joining_date'], $v['resign_date'], $workdays);
			if(!$tmp['started'] && !$tmp['resigned']){
				$calendar_days = $tmp['calendar_days'];
			}else{
				$calendar_days = $tmp['worked_days'];
			}

			if($v['calc_tax'] == 2){
				$calc_tax = 1;
			}else{
				$calc_tax = $v['calc_tax'];
			}
			
			var_dump($workdays);
			var_dump($calendar_days);
			
			//var_dump($v['emp_id'].' - '.$v['joining_date']); //exit;
			$legal_deductions = $v['gov_house_banking'] + $v['savings'] + $v['legal_execution'] + $v['kor_yor_sor'];
			$sql .= "(
			'".$dbc->real_escape_string($v['emp_id'].$_SESSION['rego']['cur_month'])."',
			'".$dbc->real_escape_string($v['emp_id'])."',
			'".$dbc->real_escape_string($_SESSION['rego']['cur_month'])."',
			'".$dbc->real_escape_string($v['entity'])."',
			'".$dbc->real_escape_string($v['branch'])."',
			'".$dbc->real_escape_string($v['division'])."',
			'".$dbc->real_escape_string($v['department'])."',
			'".$dbc->real_escape_string($v['team'])."',
			'".$dbc->real_escape_string($v['emp_group'])."',
			'".$dbc->real_escape_string($v['position'])."',
			'".$dbc->real_escape_string($v['firstname'].' '.$v['lastname'])."',
			'".$dbc->real_escape_string($v['en_name'])."',
			'".$dbc->real_escape_string($v['base_salary'])."',
			'".$dbc->real_escape_string($calendar_days)."',
			'".$dbc->real_escape_string($v['fix_allow_1'])."',
			'".$dbc->real_escape_string($v['fix_allow_2'])."',
			'".$dbc->real_escape_string($v['fix_allow_3'])."',
			'".$dbc->real_escape_string($v['fix_allow_4'])."',
			'".$dbc->real_escape_string($v['fix_allow_5'])."',
			'".$dbc->real_escape_string($v['fix_allow_6'])."',
			'".$dbc->real_escape_string($v['fix_allow_7'])."',
			'".$dbc->real_escape_string($v['fix_allow_8'])."',
			'".$dbc->real_escape_string($v['fix_allow_9'])."',
			'".$dbc->real_escape_string($v['fix_allow_10'])."',
			'".$dbc->real_escape_string($v['fix_deduct_1'])."',
			'".$dbc->real_escape_string($v['fix_deduct_2'])."',
			'".$dbc->real_escape_string($v['fix_deduct_3'])."',
			'".$dbc->real_escape_string($v['fix_deduct_4'])."',
			'".$dbc->real_escape_string($v['fix_deduct_5'])."',
			'".$dbc->real_escape_string($v['remaining_salary'])."',
			'".$dbc->real_escape_string($v['notice_payment'])."',
			'".$dbc->real_escape_string($v['paid_leave'])."',
			'".$dbc->real_escape_string($v['severance'])."',
			'".$dbc->real_escape_string($legal_deductions)."',
			'".$dbc->real_escape_string($v['other_income'])."',
			'".$dbc->real_escape_string($calc_tax)."',
			'".$dbc->real_escape_string($v['calc_sso'])."',
			'".$dbc->real_escape_string($v['calc_pvf'])."',
			'".$dbc->real_escape_string($v['calc_method'])."',
			'".$dbc->real_escape_string($pr_settings['sso_rate_emp'])."',
			'".$dbc->real_escape_string($pr_settings['sso_rate_com'])."',
			'".$dbc->real_escape_string($v['pvf_rate_emp'])."',
			'".$dbc->real_escape_string($v['pvf_rate_com'])."',
			'".$dbc->real_escape_string($v['psf_rate_emp'])."',
			'".$dbc->real_escape_string($v['psf_rate_com'])."',
			'".$dbc->real_escape_string($v['contract_type'])."',
			'".$dbc->real_escape_string($v['calc_base'])."',
			'".$dbc->real_escape_string($v['base_ot_rate'])."',
			'".$dbc->real_escape_string($v['ot_rate'])."',
			'".$dbc->real_escape_string($v['sso_by'])."',
			'".$dbc->real_escape_string('C')."'),";
			if($v['resign_date'] < $som){
				$counter ++;
			}
			if($counter > $_SESSION['rego']['max']){break;}
			
		}
		$sql = substr($sql,0,-1);
		//echo $sql;
		//exit;
		
		ob_clean();
		if($dbc->query($sql)){
			echo 'success';
		}else{
			echo mysqli_error($dbc);
		}
	}else{
		echo 'data';
	}
?>