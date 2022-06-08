<?

	if(session_id()==''){session_start();}
	ob_start();
	include("../../dbconnect/db_connect.php");
	//var_dump($_REQUEST); exit;
	
	if($dbc->query("UPDATE ".$cid."_users SET type = '".$_REQUEST['value']."' WHERE id = '".$_REQUEST['id']."'")){
		var_dump('UPDATE $cid_users SET type');
	}else{
		var_dump(mysqli_error($dbc));
	}
	
	if($dbx->query("UPDATE rego_all_users SET type = '".$_REQUEST['value']."' WHERE id = '".$_REQUEST['ref']."'")){
		var_dump('UPDATE rego_all_users SET type');
	}else{
		var_dump(mysqli_error($dbx));
	}
	
	ob_clean();	
	echo 'success';
	








