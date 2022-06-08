<?php

	if(session_id()==''){session_start();}
	ob_start();
	include('../../dbconnect/db_connect.php');
	//var_dump($_REQUEST); exit;
	
	$data = array();
	$res = $dbc->query("SELECT * FROM ".$_SESSION['rego']['payroll_dbase']." WHERE emp_id = '".$_REQUEST['id']."' AND paid = 'Y' ORDER by month ASC");
	while($row = $res->fetch_assoc()){
		$data[$row['month']]['net_income'] = $row['net_income'] + $row['social'] + $row['pvf_employee'];
		//$data[$row['month']]['fix_allow'] = $row['total_fix_allow'];
		//$data[$row['month']]['var_income'] = $row['total_var_allow'] + $row['total_otb'] + $row['other_income'] + $row['severance'] + $row['bonus'] + $row['bonus_cash'];
		
	}
	//var_dump($proll);
	
	//var_dump($data); exit;
	//ob_clean();
	echo json_encode($data);
	exit;
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
