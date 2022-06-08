<?php
	if(session_id()==''){session_start(); ob_start();}
	include("../../dbconnect/db_connect.php");
	$sql = "UPDATE `".$_SESSION['rego']['payroll_dbase']."` SET `paid` = 'Y' WHERE month = '".$_REQUEST['id']."'";
	if($_REQUEST['id']<12){$_REQUEST['id']++;}
	$_SESSION['rego']['cur_month'] = sprintf('%02d', $_REQUEST['id']);
	//echo $sql; exit;
	if($dbc->query($sql)){
		echo 'ok';
	}else{
		echo mysqli_error($dbc);
	}
?>