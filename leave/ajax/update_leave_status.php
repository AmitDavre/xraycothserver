<?
	if(session_id()==''){session_start();}
	ob_start();
	include('../../dbconnect/db_connect.php');
	
	//var_dump($_REQUEST); exit;

	$sql = "UPDATE ".$cid."_leaves SET 
		status = '".$_REQUEST['status']."' WHERE id = '".$_REQUEST['id']."'";
	
	if($dbc->query($sql)){
		ob_clean(); echo 'success';
		$dbc->query("UPDATE ".$cid."_leaves_data SET status = '".$_REQUEST['status']."' WHERE leave_id = '".$_REQUEST['id']."'");
	}else{
		echo mysqli_error($dbc);
	}
	














