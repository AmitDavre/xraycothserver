<?
	if(session_id()==''){session_start();}
	ob_start();
	include("../../dbconnect/db_connect.php");

	$sql = "DELETE FROM ".$cid."_comm_centers WHERE id='".$_REQUEST['id']."'";
	ob_clean();
	if($dbc->query($sql)){
		//ob_clean();

		$dbc->query("DELETE FROM ".$cid."_commCenters_logs WHERE cc_id='".$_REQUEST['id']."'");
		echo 'success';
	}else{
		//ob_clean();
		echo mysqli_error($dbc);
	}
?>