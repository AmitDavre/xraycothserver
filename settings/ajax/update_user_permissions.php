<?

	if(session_id()==''){session_start();}
	ob_start();
	include("../../dbconnect/db_connect.php");
	var_dump($_REQUEST); exit;
	
	
	if(!isset($_REQUEST['access'])){$_REQUEST['access'] = '';}
	if($_REQUEST['field'] == 'teams'){
		if(!empty($_REQUEST['access'])){
			$_REQUEST['access'] = implode(',',$_REQUEST['access']);
		}
	}
	//var_dump($_REQUEST); exit;
	
	$pid = $cid.'_'.$_REQUEST['username'];
	$sql = "INSERT INTO ".$cid."_user_permissions (id, cid, ".$_REQUEST['field'].") VALUES ("; 
		$sql .= "'".$dbc->real_escape_string($pid)."',";
		$sql .= "'".$dbc->real_escape_string($cid)."',";
		$sql .= "'".$dbc->real_escape_string($_REQUEST['access'])."')
		ON DUPLICATE KEY UPDATE 
		".$_REQUEST['field']." = VALUES(".$_REQUEST['field'].")";
	//echo $sql;
	//ob_clean();	
	if(!$dbc->query($sql)){
		echo mysqli_error($dbc);
	}
	








