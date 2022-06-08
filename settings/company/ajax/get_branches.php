<?
	if(session_id()==''){session_start();}
	ob_start();
	include("../../../dbconnect/db_connect.php");
	//var_dump($_REQUEST); exit;
	//$_REQUEST['id'] = '10';
	
	$data = array();
	$res = $dbc->query("SELECT id, ".$lang." FROM ".$cid."_branches WHERE entity = '".$_REQUEST['entity']."'"); 
	while($row = $res->fetch_assoc()){
		$data[$row['id']] = $row[$lang];
	}
	//var_dump($data); exit;
	ob_clean();
	echo json_encode($data);
	exit;
?>


















