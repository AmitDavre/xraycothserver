<?php
	if(session_id()==''){session_start(); ob_start();}
	include('../../dbconnect/db_connect.php');
	include(DIR.'files/functions.php');
	include(DIR.'files/payroll_functions.php');
	//include('../inc/tax_modulle.php');
	
	//$pr_settings = getPayrollSettings();
	//$pslp = unserialize($pr_settings['payslip']);
	$field = unserialize($sys_settings['payslip_field']);
	//if($pslp['lang']=='app'){$lang = $_SESSION['rego']['lang'];}else{$lang = $pslp['lang'];}
	//var_dump($field); 
	//$lang = 'th';
	include(DIR.'files/arrays_'.$_SESSION['rego']['lang'].'.php');
	if(!isset($_GET['m'])){$month = $_SESSION['rego']['cur_month'];}else{$month = $_GET['m'];}
	//if(isset($_GET['p'])){$pslp['template'] = $_GET['p'];}
	
	if(isset($_GET['id'])){
		$sql = "SELECT emp_id, month FROM ".$_SESSION['rego']['payroll_dbase']." WHERE emp_id = '".$_GET['id']."' AND month = '".$month."' AND entity = '".$_SESSION['rego']['gov_entity']."' AND emp_group = '".$_SESSION['rego']['emp_group']."'";
	}else{
		$sql = "SELECT emp_id, month FROM ".$_SESSION['rego']['payroll_dbase']." WHERE month = '".$month."' AND entity = '".$_SESSION['rego']['gov_entity']."' AND emp_group = '".$_SESSION['rego']['emp_group']."' ORDER by LENGTH(emp_id) ASC, emp_id ASC";
	}
	//var_dump($sql); //exit;
	//var_dump($sys_settings['payslip_template']); //exit;
	//var_dump($sql); //exit;
	
	$res = $dbc->query($sql);
	$rows = $res->num_rows;
	$nr = 0;
	switch($sys_settings['payslip_template']){
		case 'tmp': include('print_payslips_matrix_layout.php'); break; // matrix preprinted
		case 'tme': include('print_payslips_matrix_blank.php'); break; // matrix blank
		case 'la4': include('print_payslips_laser_A4_window.php'); break; // laser A4 + window
		case 'la5': include('print_payslips_laser_A5.php'); break; // laser A4 + window
		default: include('print_payslips_laser_A4_window.php'); break; // matrix preprinted
	}
	
?>





















