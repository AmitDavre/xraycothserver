<?
	if(session_id()==''){session_start();}
	ob_start();
	include("../../dbconnect/db_connect.php");
	//var_dump($_REQUEST); exit;

	$sql = "UPDATE ".$cid."_leave_time_settings SET comments = '".$dbc->real_escape_string(serialize($_REQUEST['comments']))."'";
	ob_clean();	
	if($dbc->query($sql)){
		echo 'success';
		//writeToLogfile($_SESSION['xhr']['cid'], 'action', 'Update time settings');
	}else{
		echo mysqli_error($dbc);
	}
	
?>