<?php
	if(session_id()==''){session_start();}
	ob_start();
	
	include('../../dbconnect/db_connect.php');
	//var_dump($_REQUEST); exit;
	
	$sql = "INSERT INTO ".$cid."_tax_simulation (emp_id, basic_salary, fix_allow, year_bonus, avg_var_allow, avg_overtime, tax_deductions, modify_tax, calc_method,calc_sso, calc_pvf, calc_tax, pvf_rate_emp, pvf_rate_com, gross_income_year, pers_tax_deduct_gross, taxable_gross) 
		VALUES (
		'".$_REQUEST['emp_id']."', 
		'".$_REQUEST['basic_salary']."', 
		'".$_REQUEST['fix_allow']."', 
		'".$_REQUEST['year_bonus']."', 
		'".$_REQUEST['avg_var_allow']."', 
		'".$_REQUEST['avg_overtime']."', 
		'".$_REQUEST['tax_deductions']."', 
		'".$_REQUEST['modify_tax']."', 
		'".$_REQUEST['calc_method']."', 
		'".$_REQUEST['calc_sso']."', 
		'".$_REQUEST['calc_pvf']."', 
		'".$_REQUEST['calc_tax']."', 
		'".$_REQUEST['pvf_rate_emp']."', 
		'".$_REQUEST['pvf_rate_com']."', 
		'".$_REQUEST['gross_income_year']."', 
		'".$_REQUEST['pers_tax_deduct_gross']."', 
		'".$_REQUEST['taxable_gross']."')
		
		ON DUPLICATE KEY UPDATE 
		basic_salary = VALUES(basic_salary), 
		fix_allow = VALUES(fix_allow), 
		year_bonus = VALUES(year_bonus), 
		avg_var_allow = VALUES(avg_var_allow), 
		avg_overtime = VALUES(avg_overtime), 
		tax_deductions = VALUES(tax_deductions), 
		modify_tax = VALUES(modify_tax), 
		calc_method = VALUES(calc_method), 
		calc_sso = VALUES(calc_sso), 
		calc_pvf = VALUES(calc_pvf), 
		calc_tax = VALUES(calc_tax), 
		pvf_rate_emp = VALUES(pvf_rate_emp), 
		pvf_rate_com = VALUES(pvf_rate_com), 
		gross_income_year = VALUES(gross_income_year), 
		pers_tax_deduct_gross = VALUES(pers_tax_deduct_gross), 
		taxable_gross = VALUES(taxable_gross)";
	
	if($dbc->query($sql)){
		$err_msg = 'success';
	}else{
		$err_msg = mysqli_error($dbc);
		
	}
	echo $err_msg;
	exit;
	
?>