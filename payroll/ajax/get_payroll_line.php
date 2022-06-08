<?php
	if(session_id()==''){session_start(); ob_start();}
	include("../../dbconnect/db_connect.php");
	
	$sql = "SELECT * FROM `".$_SESSION['rego']['payroll_dbase']."` WHERE id = '".$_REQUEST['id']."'";
	$res = $dbc->query($sql);
	$row = $res->fetch_assoc();
	
	echo json_encode((float)$row['basic_salary']);
	
	//var_dump($row); 
	
	exit;
	
	
	//echo $sql; exit;
	if($dbc->query($sql)){
		echo 'ok';
	}else{
		echo mysqli_error($dbc);
	}
?>
