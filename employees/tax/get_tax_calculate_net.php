<?php

	if(session_id()==''){session_start();}
	ob_start();

	include('../../dbconnect/db_connect.php');
	include(DIR.'files/functions.php');
	include(DIR.'files/payroll_functions.php');
	include('calculate_year_tax2.php');
	
	$row = array();
	$sql = "SELECT net_income_year, pers_tax_deduct_net, taxable_net FROM ".$cid."_tax_simulation WHERE emp_id = '".$_REQUEST['emp_id']."'";
	if($res = $dbc->query($sql)){
		$row = $res->fetch_assoc();
	}
	//var_dump($row); //exit;
	//$data = calculateYearTaxNet($row['net_income_year'], $row['pers_tax_deduct_net']);

	//$dbc->query("UPDATE ".$cid."_tax_simulation SET calculate_net = '".serialize($data)."' WHERE emp_id = '".$_REQUEST['emp_id']."'");
	
	//var_dump($xdata); exit;
	//ob_clean();
	//echo json_encode($data); exit;

	
	$row = array();
	$sql = "SELECT * FROM ".$cid."_tax_simulation WHERE emp_id = '".$_REQUEST['emp_id']."'";
	if($res = $dbc->query($sql)){
		$row = $res->fetch_assoc();
	}
	
	// Calculate Net Income
	
	$data['net'] = calculateYearTaxNet($row['net_income_year'], $row['pers_tax_deduct_net']);
	
	$income = $row['net_income_year'] - $row['pers_tax_deduct_net'];
	
	$data += taxFomNet($income,0);
	
	$dbc->query("UPDATE ".$cid."_tax_simulation SET calculate_net = '".serialize($data)."' WHERE emp_id = '".$_REQUEST['emp_id']."'");

	//var_dump($data); exit;
	ob_clean();
	echo json_encode($data);
	exit;
	
	$data['npercent'] = ($xdata['net']['year'] / ($xdata['net']['gross'] + $row['pers_tax_deduct_net']))*100;
	$data['ntaxable'] = $xdata['net']['gross'];
	//$data['nnet'] = $row['net_income_year'] - $data['year'];
	$data['nincome_gross'] = $xdata['net']['gross'] + $row['pers_tax_deduct_net'];
	$data['ntax_tot'] = $xdata['net']['year'];
	$data['nincome_m'] = ($xdata['net']['gross'] + $row['pers_tax_deduct_net']) / 12;
	$data['ntax_m'] = $xdata['net']['year'] / 12;
	$data['nnet_m'] = $xdata['nincome_m'] - $xdata['ntax_m'];
	
	$dbc->query("UPDATE ".$cid."_tax_simulation SET calculate_net = '".serialize($data)."' WHERE emp_id = '".$_REQUEST['emp_id']."'");
	
	//var_dump($data); exit;
	ob_clean();
	echo json_encode($data);
	exit;
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
