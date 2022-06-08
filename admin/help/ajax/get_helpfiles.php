<?
	
	if(session_id()==''){session_start();}
	ob_start();
	include("../../dbconnect/db_connect.php");
	//var_dump($_REQUEST); exit;
	
	$data = array();
	$res = $dba->query("SELECT * FROM rego_helpfiles WHERE page = '".$_REQUEST['page']."'");
	if($row = $res->fetch_assoc()){
		$data['page'] = $row['page'];
		$data['en'] = $row['en_content'];
		$data['th'] = $row['th_content'];
	}	
	echo json_encode($data);

	exit;