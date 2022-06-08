<?php

	if(session_id()==''){session_start();}
	ob_start();

	include("../../dbconnect/db_connect.php");
	include(DIR.'files/functions.php');
	
	//var_dump($_FILES); //exit;
	//var_dump($_REQUEST); exit;
	
	if(empty($_REQUEST['emp_id'])){echo 'empty'; exit;}
	if($_REQUEST['update'] == 0){
		$sql = "SELECT emp_id FROM ".$cid."_employees WHERE LOWER(emp_id) = '".strtolower($_REQUEST['emp_id'])."'";
		if($res = $dbc->query($sql)){
			if($res->num_rows > 0){
				ob_clean();
				echo 'exist';
				exit;
			}
		}
	}
	unset($_REQUEST['update']);

	$_REQUEST['emp_id'] = str_replace(' ', '', $_REQUEST['emp_id']);
	$_REQUEST['personal_email'] = strtolower($_REQUEST['personal_email']);
	$_REQUEST['th_name'] = $_REQUEST['firstname'].' '.$_REQUEST['lastname'];
	
	if(isset($_REQUEST['idcard_nr'])){$_REQUEST['idcard_nr'] = str_replace('-','',$_REQUEST['idcard_nr']);}
	if(isset($_REQUEST['joining_date'])){$_REQUEST['joining_date'] = date('Y-m-d', strtotime($_REQUEST['joining_date']));}
	if(!empty($_REQUEST['resign_date'])){
		$_REQUEST['resign_date'] = date('Y-m-d', strtotime($_REQUEST['resign_date']));
	}else{
		$_REQUEST['resign_date'] = '';
	}
	
	if(isset($_REQUEST['emergency_contacts'])){
		foreach($_REQUEST['emergency_contacts'] as $k=>$v){
			if(empty(trim($v['name']))){unset($_REQUEST['emergency_contacts'][$k]);}
		}
		$_REQUEST['emergency_contacts'] = serialize($_REQUEST['emergency_contacts']);
	}
	
	//var_dump($_REQUEST); exit;
	
	$sql = "INSERT INTO ".$cid."_employees (";
	foreach($_REQUEST as $k=>$v){
		$sql .= $k.', ';
	}
	$sql = substr($sql,0,-2);
	$sql .= ") VALUES ("; 
	foreach($_REQUEST as $k=>$v){
		$sql .= "'".mysqli_real_escape_string($dbc,$v)."', ";
	}
	$sql = substr($sql,0,-2).')';
	$sql .= " ON DUPLICATE KEY UPDATE ";
	foreach($_REQUEST as $k=>$v){
		$sql .= $k." = VALUES(".$k."), ";
	}
	$sql = substr($sql,0,-2);
	//echo $sql;
	
	//exit;
	
	ob_clean();
	if($dbc->query($sql)){
		updateEmployeesForPayroll($cid);
		echo 'success';
	}else{
		echo mysqli_error($dbc);
	}
	
	
