<?
	if(session_id()==''){session_start();}
	ob_start();
	include("../../dbconnect/db_connect.php");
	//var_dump($_REQUEST); exit;
	
	$sql = "DELETE FROM ".$cid."_holidays WHERE id = '".$_REQUEST['id']."'";
	//echo $sql;
	//exit;
	ob_clean();	
	if($dbc->query($sql)){
		echo 'ok';
	}else{
		echo mysqli_error($dbc);
	}
	exit;
?>