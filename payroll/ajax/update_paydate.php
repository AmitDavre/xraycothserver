<?php
	
	if(session_id()==''){session_start();}
	include('../../dbconnect/db_connect.php');
	$_SESSION['rego']['paydate'] = $_REQUEST['date'];
	$date = date('d-m-', strtotime($_REQUEST['date'])).$_SESSION['rego']['cur_year'];
	//var_dump($date); exit;
	//echo $_REQUEST['date'];
	
	$month = $_SESSION['rego']['cur_year'].'_'.$_SESSION['rego']['cur_month'];
	$sql = "INSERT INTO ".$cid."_payroll_months (month, paydate) 
		VALUES ('".$month."', '".$date."') 
		ON DUPLICATE KEY UPDATE
		paydate = VALUES(paydate)";
	if(!$dbc->query($sql)){
		//echo mysqli_error($dbc);
	}
	
