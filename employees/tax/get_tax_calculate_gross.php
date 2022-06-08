<?php

	if(session_id()==''){session_start();}
	ob_start();

	include('../../dbconnect/db_connect.php');
	//include(DIR.'files/functions.php');
	include(DIR.'files/payroll_functions.php');
	include('calculate_year_tax2.php');
	//$_REQUEST['emp_id'] = 'ST200-001';

	$row = array();
	$sql = "SELECT gross_income_year, pers_tax_deduct_gross, taxable_gross FROM ".$cid."_tax_simulation WHERE emp_id = '".$_REQUEST['emp_id']."'";
	if($res = $dbc->query($sql)){
		$row = $res->fetch_assoc();
	}
	//var_dump($row); //exit;
	$data = taxFomGross($row['gross_income_year'], $row['pers_tax_deduct_gross']);
	
	//$dbc->query("UPDATE ".$cid."_tax_simulation SET calculate_gross = '".serialize($data)."' WHERE emp_id = '".$_REQUEST['emp_id']."'");
	
	//var_dump($data); exit;
	//ob_clean();
	//echo json_encode($data); exit;

	
	// Calculate Gross Income
	$income = $row['gross_income_year'] - $row['pers_tax_deduct_gross'];
	//var_dump($income); //exit;
	//$income = $_REQUEST['gross'] - $_REQUEST['deduct'];
	$data['gross'] = calculateYearTax($income);
	
	
	
	//var_dump(calculateYearTax($income)); exit;
	if($row['gross_income_year'] > 0){
		$data['gpercent'] = ($data['gross']['year'] / $row['gross_income_year'])*100;
	}else{
		$data['gpercent'] = 0;
		$data['gross'][5][1] = 0;
		$data['gross'][0][1] = 0;
	}
	$data['gincome'] = $income;
	$data['gtax_tot'] = $data['gross']['year'];
	$data['gnet'] = $row['gross_income_year'] - $data['gross']['year'];
	$data['gincome_m'] = $row['gross_income_year'] / 12;
	$data['gtax_m'] = $data['gross']['year'] / 12;
	$data['gnet_m'] = $data['gincome_m'] - $data['gtax_m'];
	$data['gtaxable'] = $row['taxable_gross'];
	
	$dbc->query("UPDATE ".$cid."_tax_simulation SET calculate_gross = '".serialize($data)."' WHERE emp_id = '".$_REQUEST['emp_id']."'");
	
	//var_dump($data); exit;
	ob_clean();
	echo json_encode($data);
	exit;
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
