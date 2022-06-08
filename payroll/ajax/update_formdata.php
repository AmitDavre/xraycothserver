<?php
	if(session_id()==''){session_start(); ob_start();}
	include('../../dbconnect/db_connect.php');
	
	//var_dump($_REQUEST); exit;
	
	$month = $_SESSION['rego']['cur_year'].'_'.$_SESSION['rego']['cur_month'];
	$sql = "INSERT INTO ".$cid."_payroll_months (month, form_name, form_position) 
		VALUES (
		'".$month."', 
		'".$_REQUEST['form_name']."', 
		'".$_REQUEST['form_position']."') 
		ON DUPLICATE KEY UPDATE
		form_name = VALUES(form_name), 
		form_position = VALUES(form_position)";
	//var_dump($sql);
	if(!$dbc->query($sql)){
		echo mysqli_error($dbc);
	}
	/*$sql = "UPDATE ".$_SESSION['rego']['cid']."_payroll_months SET 
		form_name = '".$_REQUEST['form_name']."',
		form_position = '".$_REQUEST['form_position']."',
		form_userid = '".$_SESSION['rego']['id']."' 
		WHERE month = '".$_SESSION['rego']['cur_month'].$_SESSION['rego']['cur_year']."'";
	$dbc->query($sql);*/
	
	echo 'ok';
	//echo '<br>xxx'.mysqli_error($dbc);
	//echo json_encode($dat);
	
	
	
?>