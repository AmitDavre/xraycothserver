<?
	if(session_id()==''){session_start(); ob_start();}
	include('../../dbconnect/db_connect.php');
	//$cid = $_SESSION['xhr']['cid'];
	//$_REQUEST['code'] = 'Day Team';
	//var_dump($_REQUEST); exit;
	$data = array();
	
	$res = $dbc->query("SELECT * FROM ".$cid."_shiftplans_".$cur_year." WHERE id = '".$_REQUEST['id']."'");
	if(!mysqli_error($dbc)){
		$data = $res->fetch_assoc();
	}else{
		echo mysqli_error($dbc);
	}
	$data['plan'] = unserialize($data['plan']);
	unset($data['dates']);
	//var_dump($data); exit;

	ob_clean();
 	echo json_encode($data);
	exit;