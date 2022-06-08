<?php
	
	if(session_id()==''){session_start();}
	ob_start();

	include('../../dbconnect/db_connect.php');
	var_dump($_REQUEST); exit;
	
	$sql = "UPDATE ".$_SESSION['rego']['payroll_dbase']." SET 
		modify_tax = '".$_REQUEST['modify_tax']."', 
		calc_method = '".$_REQUEST['calc_method']."', 
		calc_sso = '".$_REQUEST['calc_sso']."', 
		calc_pvf = '".$_REQUEST['calc_pvf']."', 
		calc_tax = '".$_REQUEST['calc_tax']."' 
		WHERE id = '".$_REQUEST['id']."'";
	if($dbc->query($sql)){
		ob_clean(); echo 'success';
	}else{
		ob_clean(); echo mysqli_error($dbc);
	}
	
