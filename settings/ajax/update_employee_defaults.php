<?

	if(session_id()==''){session_start();}
	ob_start();
	include("../../dbconnect/db_connect.php");
	
	$_REQUEST['id_prefix'] = preg_replace("/[^a-zA-Z0-9,]+/", "", $_REQUEST['id_prefix']);
	$tmp = explode(',', $_REQUEST['id_prefix']);
	foreach($tmp as $k=>$v){
		if(empty($v)){unset($tmp[$k]);}
		if(strlen($v) > 3){$tmp[$k] = substr($v,0,3);}
	}
	$_REQUEST['id_prefix'] = implode(',', $tmp);
	
	//var_dump($tmp); //exit;
	//var_dump($_REQUEST); exit;
	
	$sql = "UPDATE ".$cid."_sys_settings SET ";
	foreach($_REQUEST as $k=>$v){
		$sql .= $k." = '".$dbc->real_escape_string($v)."',";
	}
	$sql = substr($sql,0,-1);
	//echo($sql); //exit;
	
	if($dbc->query($sql)){
		ob_clean();
		echo 'success';
		//writeToLogfile($_SESSION['rego']['cid'], 'action', 'Update company settings');
	}else{
		ob_clean();
		echo mysqli_error($dbc);
	}
	












