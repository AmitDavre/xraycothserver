<?
	
	if(session_id()==''){session_start();}
	ob_start();
	include("../../dbconnect/db_connect.php");
	//var_dump($_REQUEST); exit;
	//$_REQUEST['page'] = 1;
	$data['page'] = '';
	$data['en_title'] = '';
	$data['th_title'] = '';
	$data['en_content'] = '';
	$data['th_content'] = '';

	$res = $dba->query("SELECT * FROM rego_welcomefiles WHERE page = '".$_REQUEST['page']."'");
	if($row = $res->fetch_assoc()){
		$data['page'] = $row['page'];
		$data['en_title'] = $row['en_title'];
		$data['th_title'] = $row['th_title'];
		$data['en_content'] = $row['en_content'];
		$data['th_content'] = $row['th_content'];
	}
	//var_dump($data); exit;	
	echo json_encode($data);

	exit;