<?

	if(session_id()==''){session_start();}
	ob_start();
	include("../../dbconnect/db_connect.php");
	//var_dump($_REQUEST); exit;
	if(!isset($_REQUEST['access'])){$_REQUEST['access'] = '';}
	if(!empty($_REQUEST['access']) && $_REQUEST['field'] != 'emp_group'){
		$_REQUEST['access'] = implode(',',$_REQUEST['access']);
	}
	//var_dump($_REQUEST); exit;
	
	$sql = "UPDATE ".$cid."_system_users SET ".$_REQUEST['field']." = '".$_REQUEST['access']."' WHERE id = '".$_REQUEST['id']."'"; 
	//echo $sql;
	//ob_clean();	
	if(!$dbc->query($sql)){
		echo mysqli_error($dbc);
	}
	








