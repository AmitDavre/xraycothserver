<?php
	if(session_id()==''){session_start();}
	ob_start();
	include('../../dbconnect/db_connect.php');
	//var_dump($_REQUEST['field']); exit;
	
	$fields = serialize($_REQUEST['field']);
	$res = $dbc->query("UPDATE ".$cid."_sys_settings SET emp_export_fields = '".$dbc->real_escape_string($fields)."'");
	
	ob_end_clean();
	echo 'success';
	exit;
?>