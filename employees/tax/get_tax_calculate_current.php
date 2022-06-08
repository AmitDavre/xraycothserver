<?php

	if(session_id()==''){session_start();}
	ob_start();

	include('../../dbconnect/db_connect.php');
	//include(DIR.'files/functions.php');
	include(DIR.'files/payroll_functions.php');
	include('calculate_year_tax2.php');
	//$_REQUEST['emp_id'] = 'ST200-001';
	//var_dump($_REQUEST); //exit;
	
	//$_REQUEST['cgross'] = 900000;
	//$_REQUEST['cdeduct'] = 100000;
	
	//$data = taxFomGross($_REQUEST['cgross'], $_REQUEST['cdeduct']);
	$data = taxFomGross($_REQUEST['cgross'], $_REQUEST['cdeduct']);
	
	$dbc->query("UPDATE ".$cid."_tax_simulation SET calculate_current = '".serialize($data)."' WHERE emp_id = '".$_REQUEST['emp_id']."'");
	
	//var_dump($data); //exit;
	//ob_clean();
	//echo json_encode($data); exit;
	
	// Calculate Current Income
	$income = $_REQUEST['ctaxable'];
	$data['current'] = calculateYearTax($income);
	//var_dump($data); exit;
	
	
	//$data['cpercent'] = ($data['current']['year'] / $_REQUEST['cgross'])*100;
	//$data['ctot_tax'] = $data['current']['year'];
	$data['cgross'] = $_REQUEST['cgross'];
	$data['ctaxable'] = $_REQUEST['ctaxable'];
	$data['cdeduct'] = $_REQUEST['cdeduct'];
	
	$dbc->query("UPDATE ".$cid."_tax_simulation SET calculate_current = '".serialize($data)."' WHERE emp_id = '".$_REQUEST['emp_id']."'");

	//var_dump($data); exit;
	ob_clean();
	echo json_encode($data);
	exit;
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
