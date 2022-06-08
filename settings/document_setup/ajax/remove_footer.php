<?
	if(session_id()==''){session_start();}
	ob_start();
	include("../../../dbconnect/db_connect.php");

	$sql = "DELETE FROM ".$cid."_footer_templates WHERE id='".$_REQUEST['id']."'";
	ob_clean();
	if($dbc->query($sql)){
		//ob_clean();
		echo 'success';
	}else{
		//ob_clean();
		echo mysqli_error($dbc);
	}
?>