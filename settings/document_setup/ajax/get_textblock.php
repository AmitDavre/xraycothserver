<?
	if(session_id()==''){session_start();}
	ob_start();
	include('../../../dbconnect/db_connect.php');
	
	//$_REQUEST['id'] = 2;
	$data = array();
	$sql = "SELECT * FROM ".$cid."_document_textblocks WHERE id = '".$_REQUEST['id']."'";
	if($res = $dbc->query($sql)){
		if($row = $res->fetch_assoc()){
			$data = $row;
		}
	}
	ob_clean();
	echo json_encode($data);
?>