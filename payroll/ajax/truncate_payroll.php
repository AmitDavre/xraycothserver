<?php
	if(session_id()==''){session_start(); ob_start();}
	include("../../dbconnect/db_connect");
	$sql = "TRUNCATE TABLE `".$_SESSION['rego']['payroll_dbase']."`";
	//echo $sql; exit;
	if($dbc->query($sql)){
		echo 'ok';
	}else{
		echo mysqli_error($dbc);
	}
?>