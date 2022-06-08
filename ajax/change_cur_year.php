<?php
	if(session_id()==''){session_start();}
	ob_start();
	include('../dbconnect/db_connect.php');
	
	$_SESSION['rego']['cur_year'] = $_REQUEST['year'];
	$_SESSION['rego']['year_en'] = $_REQUEST['year'];
	$_SESSION['rego']['year_th'] = (int)$_REQUEST['year'] + 543;

	$_SESSION['rego']['payroll_dbase'] =  $_SESSION['rego']['cid'].'_payroll_'.$_SESSION['rego']['cur_year'] ;



	$dbc->query("UPDATE ".$cid."_sys_settings SET cur_year = '".$_REQUEST['year']."'");
	if((int)$_SESSION['rego']['cur_year'] < (int)date('Y')){
		$_SESSION['rego']['cur_month'] = 12;
		$dbc->query("UPDATE ".$cid."_system_settings SET cur_month = 12");
	}
	echo $_REQUEST['year'];
?>