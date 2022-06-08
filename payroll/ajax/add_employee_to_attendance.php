<?php
	if(session_id()==''){session_start();}
	ob_start();
	include('../../dbconnect/db_connect.php');
	include(DIR.'files/payroll_functions.php');
	//var_dump($_REQUEST); exit;
	
	$sql = "SELECT * FROM ".$_SESSION['rego']['emp_dbase']." WHERE emp_id = '".$_REQUEST['id']."'";
	$res = $dbc->query($sql);
	//var_dump(mysqli_error($dbc));//exit;
	//var_dump($row);exit;
	
	$sql = "INSERT INTO ".$_SESSION['rego']['payroll_dbase']." 
	(id, emp_id, month, emp_name_th, emp_name_en, entity, branch, division, department, team, emp_group, position, basic_salary, paid_days, ";
	for($i=1;$i<=10;$i++){
		$sql .= "fix_allow_".$i.",";
	}
	$sql .= "remaining_salary, notice_payment, paid_leave, severance, legal_deductions, other_income, calc_tax, calc_sso, calc_pvf, calc_method, sso_rate_emp, sso_rate_com, pvf_rate_emp, pvf_rate_com, psf_rate_emp, psf_rate_com, contract_type, calc_base, base_ot_rate, ot_rate, sso_by, paid) VALUES ";
	
	if($row = $res->fetch_assoc()){
		$tmp = getEmployeeWorkedDays($row['joining_date'], $row['resign_date'], 30);
		if(!$tmp['started'] && !$tmp['resigned']){
			$calendar_days = $tmp['calendar_days'];
		}else{
			$calendar_days = $tmp['worked_days'];
		}
		$legal_deductions = $row['gov_house_banking'] + $row['savings'] + $row['legal_execution'] + $row['kor_yor_sor'];

		if($row['calc_tax'] == 2){
			$calc_tax = 1;
		}else{
			$calc_tax = $row['calc_tax'];
		}

		$sql .= "(
		'".$dbc->real_escape_string($row['emp_id'].$_SESSION['rego']['cur_month'])."',
		'".$dbc->real_escape_string($row['emp_id'])."',
		'".$dbc->real_escape_string($_SESSION['rego']['cur_month'])."',
		'".$dbc->real_escape_string($row['firstname'].' '.$row['lastname'])."',
		'".$dbc->real_escape_string($row['en_name'])."',
		
		'".$dbc->real_escape_string($row['entity'])."',
		'".$dbc->real_escape_string($row['branch'])."',
		'".$dbc->real_escape_string($row['division'])."',
		'".$dbc->real_escape_string($row['department'])."',
		'".$dbc->real_escape_string($row['team'])."',
		'".$dbc->real_escape_string($row['emp_group'])."',
		'".$dbc->real_escape_string($row['position'])."',
		
		'".$dbc->real_escape_string($row['base_salary'])."',
		'".$dbc->real_escape_string($calendar_days)."',
		'".$dbc->real_escape_string($row['fix_allow_1'])."',
		'".$dbc->real_escape_string($row['fix_allow_2'])."',
		'".$dbc->real_escape_string($row['fix_allow_3'])."',
		'".$dbc->real_escape_string($row['fix_allow_4'])."',
		'".$dbc->real_escape_string($row['fix_allow_5'])."',
		'".$dbc->real_escape_string($row['fix_allow_6'])."',
		'".$dbc->real_escape_string($row['fix_allow_7'])."',
		'".$dbc->real_escape_string($row['fix_allow_8'])."',
		'".$dbc->real_escape_string($row['fix_allow_9'])."',
		'".$dbc->real_escape_string($row['fix_allow_10'])."',
			
		'".$dbc->real_escape_string($row['remaining_salary'])."',
		'".$dbc->real_escape_string($row['notice_payment'])."',
		'".$dbc->real_escape_string($row['paid_leave'])."',
		'".$dbc->real_escape_string($row['severance'])."',
		'".$dbc->real_escape_string($legal_deductions)."',
		'".$dbc->real_escape_string($row['other_income'])."',
		'".$dbc->real_escape_string($calc_tax)."',
		'".$dbc->real_escape_string($row['calc_sso'])."',
		'".$dbc->real_escape_string($row['calc_pvf'])."',
		'".$dbc->real_escape_string($row['calc_method'])."',
		'".$dbc->real_escape_string($pr_settings['sso_rate_emp'])."',
		'".$dbc->real_escape_string($pr_settings['sso_rate_com'])."',
		'".$dbc->real_escape_string($row['pvf_rate_emp'])."',
		'".$dbc->real_escape_string($row['pvf_rate_com'])."',
		'".$dbc->real_escape_string($row['psf_rate_emp'])."',
		'".$dbc->real_escape_string($row['psf_rate_com'])."',
		'".$dbc->real_escape_string($row['contract_type'])."',
		'".$dbc->real_escape_string($row['calc_base'])."',
		'".$dbc->real_escape_string($row['base_ot_rate'])."',
		'".$dbc->real_escape_string($row['ot_rate'])."',
		'".$dbc->real_escape_string($row['sso_by'])."',
		
		'".$dbc->real_escape_string('C')."'),";
	}
	$sql = substr($sql,0,-1);
	//echo $sql;
	//exit;
	if($dbc->query($sql)){
		$msg = 'success';
	}else{
		$msg = mysqli_error($dbc);
	}
	echo $msg;
?>