<?
	if(session_id()==''){session_start();}
	ob_start();
	include("../dbconnect/db_connect.php");
	//var_dump($_REQUEST); exit;
	//$_REQUEST['id'] = '10';
	
	$data = array();
	$res = $dba->query("SELECT * FROM rego_users WHERE user_id = '".$_REQUEST['id']."'"); 
	if($row = $res->fetch_assoc()){
		$data['user_id'] = $row['user_id'];
		$data['name'] = $row['name'];
		$data['img'] = $row['img'];
		$data['access'] = unserialize($row['access']);
		$data['as_customers'] = unserialize($row['as_customers']);
	}else{
		echo 'Error';
	}
	//var_dump($data); exit;
	ob_clean();
	echo json_encode($data);
	exit;
?>


















