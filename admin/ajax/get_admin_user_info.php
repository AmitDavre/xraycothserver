<?
	if(session_id()==''){session_start(); ob_start();}
	include("../dbconnect/db_connect.php");
	//var_dump($_REQUEST); exit;
	//$_REQUEST['id'] = '100';
	
	$data = array();
	$res = $dba->query("SELECT * FROM rego_users WHERE user_id = '".$_REQUEST['id']."'"); 
	if($row = $res->fetch_assoc()){
		$data['user_id'] = $row['user_id'];
		$data['username'] = $row['username'];
		$data['name'] = $row['name'];
		$data['position'] = $row['position'];
		$data['phone'] = $row['phone'];
		$data['email'] = $row['email'];
		$data['img'] = $row['img'];
		$data['status'] = $row['status'];
		$data['remarks'] = $row['remarks'];
		if($row['clients']){
			$data['clients'] = unserialize($row['clients']);
		}else{
			$data['clients'] = array();
		}
	}else{
		echo 'Error';
	}
	//var_dump($data); exit;
	ob_clean();
	echo json_encode($data);
	exit;
?>


















