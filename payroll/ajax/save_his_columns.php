<?php
	if(session_id()==''){session_start();}
	ob_start();
	include('../../dbconnect/db_connect.php');
	
	$his_cols = array();
	$cols = array();
	if(isset($_REQUEST['cols'])){
		foreach($_REQUEST['cols'] as $k=>$v){
			$his_cols[$v['db']] = $v['name'];
			$cols[] = (int)$v['id'];
		}
	}
	$dbc->query("UPDATE ".$_SESSION['rego']['cid']."_sys_settings SET 
		his_cols = '".$dbc->real_escape_string(serialize($his_cols))."', 
		his_showhide_cols = '".$dbc->real_escape_string(serialize($cols))."'");
	//var_dump($his_cols);
	//var_dump($cols);
	echo count($his_cols);
?>