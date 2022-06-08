<?php
	if(session_id()==''){session_start(); ob_start();}
	include("../../dbconnect/db_connect.php");
	
	$sql = "DELETE FROM `".$_SESSION['rego']['payroll_dbase']."` WHERE paid = 'C'";
	unset($_SESSION['rego']['cur_month']);
	ob_clean();
	//echo $sql; exit;
	if($dbc->query($sql)){
		echo 'ok';
	}else{
		echo mysqli_error($dbc);
	}
?>