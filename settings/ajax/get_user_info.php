<?
	if(session_id()==''){session_start();}
	ob_start();
	include("../../dbconnect/db_connect.php");
	//var_dump($_REQUEST); exit;
	//$_REQUEST['id'] = '10';

	$res = $dbc->query("SELECT * FROM ".$cid."_users WHERE id = '".$_REQUEST['id']."'"); 
	if($row = $res->fetch_assoc()){
		$data['id'] = $row['id'];
		$data['ref'] = $row['ref'];
		$data['username'] = $row['username'];
		$data['name'] = $row['name'];
		$data['phone'] = $row['phone'];
		$data['entities'] = $row['entities'];
		$data['branches'] = $row['branches'];
		$data['groups'] = $row['groups'];
		$data['departments'] = $row['departments'];
		$data['emp_group'] = $row['emp_group'];
		$data['teams'] = $row['teams'];
		$data['type'] = $row['type'];
		//$data['access'] = $row['access'];
		$data['img'] = $row['img'];
		$data['status'] = $row['status'];
	}else{
		$data = array();
		echo 'Error';
	}
	//var_dump($data); exit;
	ob_clean();
	echo json_encode($data);
	exit;
?>


















