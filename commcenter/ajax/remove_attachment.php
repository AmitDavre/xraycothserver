<?
	if(session_id()==''){session_start();}
	ob_start();
	include("../../dbconnect/db_connect.php");

	$sql = "SELECT * FROM ".$cid."_comm_centers WHERE id='".$_REQUEST['id']."'";
	if($res = $dbc->query($sql)){
		if($row = $res->fetch_assoc()){
			$attach = $row;

			$attached = $attach['attachments'];

			$replaseStr = str_replace($_REQUEST['attch'].',', '', $attached);

			$dbc->query("UPDATE ".$cid."_comm_centers SET attachments='".$replaseStr."' WHERE id='".$_REQUEST['id']."'");
			unlink($_REQUEST['attch']);

			ob_clean();
			echo 'success';
		}
	}
?>