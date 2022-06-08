<?
	if(session_id()==''){session_start(); ob_start();}
	include('../../dbconnect/db_connect.php');
	//$cid = $_SESSION['xhr']['cid'];
	//$_REQUEST['code'] = 'Team A';
	
	$data = array();
	
	$res = $dba->query("SELECT * FROM rego_default_shiftplans WHERE id = '".$_REQUEST['id']."'");
	if(!mysqli_error($dba)){
		$data = $res->fetch_assoc();
	}else{
		echo mysqli_error($dba);
	}
	$data['plan'] = unserialize($data['plan']);
	unset($data['dates']);
	//var_dump($data); exit;

	ob_clean();
 	echo json_encode($data);
	exit;