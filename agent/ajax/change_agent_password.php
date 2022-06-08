<?php
	
	if(session_id()==''){session_start();}
	ob_start();
	//var_dump($_REQUEST); exit;
	
	include("../db_connect.php");

	if(empty($_REQUEST['opass']) || empty($_REQUEST['npass']) || empty($_REQUEST['rpass'])){
		ob_clean(); echo 'empty'; exit;
	}
	if(strlen($_REQUEST['npass']) < 8){
		ob_clean(); echo 'short'; exit;
	}
	if($_REQUEST['npass'] !== $_REQUEST['rpass']){
		ob_clean(); echo 'same'; exit;
	}
	$pass1 = hash('sha256', $_REQUEST['opass']); 
	$pass3 = hash('sha256', $_REQUEST['rpass']);
	//var_dump($pass1); exit;
	
	$sql = "SELECT email FROM rego_agents WHERE password = '".$pass1."' AND LOWER(email) = '".strtolower($_SESSION['agent']['email'])."'";
	if($res = $dbx->query($sql)){
		if($res->num_rows > 0){
			$sql = "UPDATE rego_agents SET password = '".$pass3."' WHERE LOWER(email) = '".strtolower($_SESSION['agent']['email'])."'";
			if($dbx->query($sql)){
				ob_clean(); echo 'success'; exit;
			}else{
				ob_clean();	echo 'Error : '.mysqli_error($dbx); exit;
			}
		}else{
			ob_clean(); echo 'old'; exit;
		}
	}
	
?>