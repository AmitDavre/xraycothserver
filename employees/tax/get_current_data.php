<?php

	if(session_id()==''){session_start();}
	ob_start();
	include('../../dbconnect/db_connect.php');
	//var_dump($_REQUEST); exit;
	
	$sql = "SELECT * FROM ".$cid."_employees WHERE emp_id = '".$_REQUEST['emp_id']."'";
	if($res = $dbc->query($sql)){
		//$data = $res->fetch_assoc();
		$row = $res->fetch_assoc();
		$data['basic_salary'] = $row['base_salary'];
		$fix = 0;
		for($i=1;$i<=10;$i++){$fix += $row['fix_allow_'.$i];}
		$data['fix_allow'] = $fix;
		$data['year_bonus'] = 0;//$row['yearbonus'];
		$sso = $row['base_salary'] * 0.05;
		$sso = ($sso > 750 ? 750 : $sso);
		$pvf = $row['base_salary'] * ($row['pvf_rate_emp']/100);		
		$data['sso'] = $sso;
		$data['pvf'] = $pvf;
		$data['min_deductions'] = $row['total_tax_deductions'] + $row['tax_standard_deduction'];
		$data['tax_deductions'] = $row['total_tax_deductions'] + $row['tax_standard_deduction'] + $row['tax_allow_pvf'] + $row['tax_allow_sso'];

		$data['pvf_rate_emp'] = $row['pvf_rate_emp'];
		$data['pvf_rate_com'] = $row['pvf_rate_com'];
		$data['calc_sso'] = $row['calc_sso'];
		$data['calc_pvf'] = $row['calc_pvf'];
		$data['calc_tax'] = $row['calc_tax'];
		$data['calc_method'] = $row['calc_method'];
		$data['modify_tax'] = $row['modify_tax'];
	}
	
	$prol = array();
	$var_allow = 0;
	$ot = 0;
	$rows = 0;
	if($res = $dbc->query("SELECT month, total_var_allow, total_otb FROM ".$_SESSION['rego']['payroll_dbase']." WHERE emp_id = '".$_REQUEST['emp_id']."' ORDER by month ASC")){
		while($row = $res->fetch_assoc()){
			//$prol[$row['month']]['var_allow'] = $row['total_var_allow'];
			//$prol[$row['month']]['ot'] = $row['total_otb'];
			$var_allow += $row['total_var_allow'];
			$ot += $row['total_otb'];
			$rows = $row['month'];
		}
	}
	$data['avg_var_allow'] = 0;
	$data['avg_overtime'] = 0;
	if($rows > 0){
		$data['avg_var_allow'] = $var_allow/$rows;
		$data['avg_overtime'] = $ot/$rows;
	}
	

	//var_dump($var_allow); 
	//var_dump($ot); 
	//var_dump($rows); 
	
	//exit;
	//var_dump($data); exit;
	ob_clean();
	echo json_encode($data);
	exit;
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
