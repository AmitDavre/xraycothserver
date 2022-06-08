<?

	if(session_id()==''){session_start();}
	ob_start();
	include("../../dbconnect/db_connect.php");
	//var_dump($_REQUEST); exit;
	
	$sql = "UPDATE ".$cid."_users SET ".$_REQUEST['field']." = '".$_REQUEST['value']."' WHERE id = '".$_REQUEST['id']."'"; 
	//echo $sql;
	ob_clean();	
	if(!$dbc->query($sql)){
		echo mysqli_error($dbc);
	}
	








