<?php
	if(session_id()==''){session_start(); ob_start();}
	include("../../dbconnect/db_connect.php");
	
	$sql = "DELETE FROM `".$_SESSION['rego']['payroll_dbase']."` WHERE emp_id = '".$_REQUEST['id']."' AND month = '".$_SESSION['rego']['cur_month']."'";
	//echo $sql; exit;
	if($dbc->query($sql)){
		echo 'ok';
	}else{
		echo mysqli_error($dbc);
	}
?>
