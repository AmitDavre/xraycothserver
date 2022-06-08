<?
	if(session_id()==''){session_start();}
	ob_start();
	include("../../dbconnect/db_connect.php");

	if(isset($_REQUEST['otsa'])){$_REQUEST['otsa'] = serialize($_REQUEST['otsa']);}
	if(isset($_REQUEST['otsu'])){$_REQUEST['otsu'] = serialize($_REQUEST['otsu']);}
	if(isset($_REQUEST['othd'])){$_REQUEST['othd'] = serialize($_REQUEST['othd']);}
	
	//var_dump($_REQUEST); exit;
	
	$sql = "UPDATE ".$cid."_leave_time_settings SET ";
		foreach($_REQUEST as $k=>$v){
			$sql .= $k." = '".$dbc->real_escape_string($v)."', ";
		}
		$sql = substr($sql, 0, -2);
		//echo $sql; exit;
	
	ob_clean();	
	if($dbc->query($sql)){
		echo 'success';
		//writeToLogfile($_SESSION['xhr']['cid'], 'action', 'Update time settings');
	}else{
		echo mysqli_error($dbc);
	}
	
?>