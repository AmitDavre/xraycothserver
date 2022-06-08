<?php

	if(session_id()==''){session_start();}
	ob_start();
	include('../../dbconnect/db_connect.php');
	include(DIR.'files/functions.php');

	$sql = "SELECT * FROM ".$cid."_historic_data ORDER BY month, emp_id ASC";
	if($res = $dbc->query($sql)){
		while($row = $res->fetch_assoc()){
			$data[$row['id']] = $row;
			$data[$row['id']]['tax_month'] = $row['tax'];
			$data[$row['id']]['calc_tax'] = 1;
			$data[$row['id']]['ytd_income'] = $row['gross_income'];
			//$data[$row['id']]['tot_deduct_before'] = $row['fix_deduct_before'] + $row['var_deduct_before'];
		}
	}
	//var_dump($data); exit;
	
	$field = key($data);
	$sql = "INSERT INTO ".$_SESSION['rego']['payroll_dbase']." (";
	
	foreach($data[$field] as $k=>$v){
		$sql .= $k.',';
	}
	$sql = substr($sql,0,-1);
	$sql .= ") VALUES ("; 
	foreach($data as $val){
		foreach($val as $k=>$v){
			$sql .= "'".$dbc->real_escape_string($v)."',";
		}
		$sql = substr($sql,0,-1);
		$sql .= '),(';
	}
	$sql = substr($sql,0,-2);
	$sql .= " ON DUPLICATE KEY UPDATE ";
	foreach($data[$field] as $k=>$v){
		$sql .= $k.' = VALUES('.$k.'),';
	}
	$sql = substr($sql,0,-1);
	//echo $sql; exit;
	//var_dump($sql); exit;
	ob_clean();
	if($dbc->query($sql)){
		echo 'success';
		$dbc->query("UPDATE ".$cid."_sys_settings SET history = 1");
		$dbc->query("TRUNCATE TABLE ".$cid."_historic_data");
	
	}else{
		echo mysqli_error($dbc);
	}
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	

