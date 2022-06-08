<?
	if(session_id()==''){session_start();}
	ob_start();
	include('../../dbconnect/db_connect.php');
	
	$data = array();
	$sql = "SELECT * FROM ".$cid."_comm_centers WHERE id = '".$_REQUEST['id']."'";
	if($res = $dbc->query($sql)){
		if($row = $res->fetch_assoc()){
			$data = $row;
			$data['settings'] = unserialize($data['settings']);
			$data['areas'] = unserialize($data['areas']);
			$data['sectionVal'] = unserialize($data['sectionVal']);
		}else{
			$data['id'] = '0';
		}
	}

	// echo '<pre>';
	// print_r($data);
	// echo '</pre>';
	// exit;
	
	ob_clean();
	//var_dump($data);
	echo json_encode($data);
?>