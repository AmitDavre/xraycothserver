<?php
	if(session_id()==''){session_start();}
	ob_start();
	include('../../dbconnect/db_connect.php');
	include(DIR.'files/functions.php');
	include(DIR.'files/payroll_functions.php');
	include(DIR.'files/arrays_'.$_SESSION['rego']['lang'].'.php');
	if(!isset($_GET['m'])){$month = $_SESSION['rego']['cur_month'];}else{$month = $_GET['m'];}
	
	//$_GET['id'] = 'DEMO-0011';
	//$sys_settings['payslip_template'] = 'la5';
	$field = unserialize($sys_settings['payslip_field']);
	if(isset($_GET['id'])){
		$sql = "SELECT * FROM ".$_SESSION['rego']['payroll_dbase']." WHERE id = '".$_GET['id']."'";
	}
	
	$res = $dbc->query($sql);
	$rows = $res->num_rows;
	$nr = 0;
	switch($sys_settings['payslip_template']){
		case 'tmp': include('print_payslips_matrix_layout.php'); break; // matrix preprinted
		case 'tme': include('print_payslips_matrix_blank.php'); break; // matrix blank
		case 'la4': include('print_payslips_laser_A4_window.php'); break; // laser A4 + window
		case 'la5': include('print_payslips_laser_A5.php'); break; // laser A4 + window
		default: include('print_payslips_laser_A4_window.php'); break; // laser A4 + window
	}
	
?>





















