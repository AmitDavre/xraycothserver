<?

	if(session_id()==''){session_start();}
	ob_start();
	include("../../dbconnect/db_connect.php");
	//var_dump($_REQUEST); exit;
	
	if($dbc->query("UPDATE ".$cid."_users SET status = '".$_REQUEST['value']."' WHERE id = '".$_REQUEST['id']."'")){
		var_dump('UPDATE $cid_users SET status');
	}else{
		var_dump(mysqli_error($dbc));
	}
	
	if($dbx->query("UPDATE rego_all_users SET sys_status = '".$_REQUEST['value']."' WHERE id = '".$_REQUEST['ref']."'")){
		var_dump('UPDATE rego_all_users SET sys_status');
	}else{
		var_dump(mysqli_error($dbx));
	}
	
	ob_clean();	
	echo 'success';
	








