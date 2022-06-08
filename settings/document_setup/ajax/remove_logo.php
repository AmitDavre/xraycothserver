<?
	if(session_id()==''){session_start();}
	ob_start();
	include("../../../dbconnect/db_connect.php");

	$sql = "UPDATE ".$cid."_document_templates SET `logo`= '' WHERE id='".$_REQUEST['id']."'";
	ob_clean();
	if($dbc->query($sql)){
		//ob_clean();
		echo 'success';
	}else{
		//ob_clean();
		echo mysqli_error($dbc);
	}
?>