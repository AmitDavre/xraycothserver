<?
	if(session_id()==''){session_start();}
	ob_start();
	include("../../dbconnect/db_connect.php");
	//var_dump($_REQUEST); exit;
//$_REQUEST['id'] = 1;
	$res = $dbc->query("SELECT * FROM ".$cid."_users WHERE id = '".$_REQUEST['id']."'"); 
	if($row = $res->fetch_assoc()){
		$data['id'] = $row['id'];
		$data['name'] = $row['name'];
		$data['img'] = $row['img'].'?'.time();
		$data['permissions'] = unserialize($row['permissions']);
		$data['entities'] = $row['entities'];
		$data['access'] = $row['access'];
		$data['branches'] = $row['branches'];
		$data['divisions'] = $row['divisions'];
		$data['departments'] = $row['departments'];
		$data['teams'] = $row['teams'];
		$data['access_selection'] = $row['access_selection'];
		$data['emp_group'] = $row['emp_group'];
	}else{
		$data = array();
		echo 'Error';
	}
	//var_dump($data); exit;
	ob_clean();
	echo json_encode($data);
	exit;
?>


















