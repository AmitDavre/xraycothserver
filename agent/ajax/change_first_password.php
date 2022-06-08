<?php
	
	if(session_id()==''){session_start();}
	ob_start();
	//var_dump($_REQUEST); exit;
	
	include("../db_connect.php");

	if(empty($_REQUEST['agent_id']) || empty($_REQUEST['npassword']) || empty($_REQUEST['rpassword'])){
		ob_clean(); echo 'empty'; exit;
	}
	if(strlen($_REQUEST['npassword']) < 8){
		ob_clean(); echo 'short'; exit;
	}
	if($_REQUEST['npassword'] !== $_REQUEST['rpassword']){
		ob_clean(); echo 'same'; exit;
	}
	$password = hash('sha256', $_REQUEST['npassword']); 
	
	$sql = "UPDATE rego_agents SET 
		password = '".$password."', 
		visit = 1 
		WHERE LOWER(agent_id) = '".strtolower($_REQUEST['agent_id'])."'";
	if($dbx->query($sql)){
		ob_clean(); echo 'success';
	}else{
		ob_clean();	echo 'Error : '.mysqli_error($dbx);
	}
	
?>