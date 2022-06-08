<?
	if(session_id()==''){session_start();}
	ob_start();
	include('../../dbconnect/db_connect.php');
	
	//var_dump($_REQUEST); exit;

	$sql = "DELETE FROM ".$cid."_leaves WHERE id = '".$_REQUEST['id']."'";
	
	if($dbc->query($sql)){
		ob_clean(); echo 'success';
		$dbc->query("DELETE FROM ".$cid."_leaves_data WHERE leave_id = '".$_REQUEST['id']."'");
	}else{
		echo mysqli_error($dbc);
	}
	














