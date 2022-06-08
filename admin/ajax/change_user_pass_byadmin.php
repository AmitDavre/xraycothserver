<?php
	if(session_id()==''){session_start();}
	ob_start();
	include("../dbconnect/db_connect.php");
	//var_dump($_REQUEST); //exit;
	
	if(empty($_REQUEST['npass'])){
		ob_clean();	echo 'empty'; exit;
	}
	if(strlen($_REQUEST['npass'])<8){
		ob_clean();	echo 'short'; exit;
	}
	/*if($_REQUEST['npass'] !== $_REQUEST['rpass']){
		ob_clean();	echo 'same'; exit;
	}*/

	//$pass1 = hash('sha256', $_REQUEST['opass']); 
	$pass3 = hash('sha256', $_REQUEST['npass']);
	//$pass5 = hash('sha256', 'www');

	//echo "SELECT id FROM rego_all_users WHERE LOWER(username) = '".strtolower($_REQUEST['uname'])."'";
	$res = $dba->query("SELECT id FROM rego_all_users WHERE LOWER(username) = '".strtolower($_REQUEST['uname'])."'");
	if($res->num_rows > 0){
		$sql = "UPDATE rego_all_users SET password = '".$pass3."' WHERE LOWER(username) = '".strtolower($_REQUEST['uname'])."'";
		if($dba->query($sql)){
			ob_clean();	
			echo 'success';
		}else{
			ob_clean();	
			echo mysqli_error($dba);
		}
	}else{
		ob_clean();	
		echo 'old';
	}
?>