<?php

	if(session_id()==''){session_start();}
	ob_start();
	include("../../dbconnect/db_connect.php");
	//include(DIR.'files/functions.php');

	//var_dump($_REQUEST); exit;
	
	$sql = "UPDATE ".$cid."_sys_settings SET account_allocations = '".$dbc->real_escape_string(serialize($_REQUEST))."'";

	ob_clean();	
	if($dbc->query($sql)){
		echo 'success';
		//writeToLogfile($_SESSION['rego']['cid'], 'action', 'Update company settings');
	}else{
		echo mysqli_error($dbc);
	}
	
	exit;














