<?
	if(session_id()==''){session_start(); ob_start();}
	include("../dbconnect/db_connect.php");
	//var_dump($_REQUEST); exit;
	//$_REQUEST['id'] = '100';
	$id = 100;
	$data = array();
	$res = $dba->query("SELECT * FROM rego_users WHERE user_id != '5a6effb9c34ab' ORDER by user_id DESC"); 
	if($row = $res->fetch_assoc()){
		if(!empty($row['user_id'])){$id = $row['user_id'] +1;}
	}
	//var_dump($id); exit;
	ob_clean();
	echo $id;
	exit;
?>


















