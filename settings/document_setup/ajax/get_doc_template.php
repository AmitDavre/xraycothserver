<?
	if(session_id()==''){session_start();}
	ob_start();
	include('../../dbconnect/db_connect.php');
	
	//$_REQUEST['id'] = 2;
	$data = array();
	$sql = "SELECT * FROM erp_document_setup WHERE id = '".$_REQUEST['id']."'";
	if($res = $dbc->query($sql)){
		if($row = $res->fetch_assoc()){
			$data['id'] = $row['id'];
			$data['name'] = $row['name'];
			$data['footer'] = $row['footer'];
			$data['settings'] = unserialize($row['settings']);
		}
	}
	ob_clean();
	echo json_encode($data);
?>