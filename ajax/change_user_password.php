<?php
	if(session_id()==''){session_start();}
	ob_start();
	include("../dbconnect/db_connect.php");
	//var_dump($_REQUEST); //exit;
	
	if(empty($_REQUEST['opass']) || empty($_REQUEST['npass']) || empty($_REQUEST['rpass'])){
		ob_clean();	echo 'empty'; exit;
	}
	if(strlen($_REQUEST['rpass'])<8){
		ob_clean();	echo 'short'; exit;
	}
	if($_REQUEST['npass'] !== $_REQUEST['rpass']){
		ob_clean();	echo 'same'; exit;
	}
	$pass1 = hash('sha256', $_REQUEST['opass']); 
	$pass3 = hash('sha256', $_REQUEST['rpass']);
	//$pass5 = hash('sha256', 'www');

	$res = $dbx->query("SELECT id FROM rego_all_users WHERE password = '".$pass1."' AND LOWER(username) = '".strtolower($_SESSION['rego']['username'])."'");
	if($res->num_rows > 0){
		$sql = "UPDATE rego_all_users SET password = '".$pass3."' WHERE LOWER(username) = '".strtolower($_SESSION['rego']['username'])."'";
		if($dbx->query($sql)){
			ob_clean();	
			echo 'success';
		}else{
			ob_clean();	
			echo mysqli_error($dbx);
		}
	}else{
		ob_clean();	
		echo 'old';
	}
?>