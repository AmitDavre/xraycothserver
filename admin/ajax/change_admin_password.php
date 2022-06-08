<?php
	if(session_id()==''){session_start();}
	ob_start();
	include("../dbconnect/db_connect.php");
	//var_dump($_REQUEST);
	//var_dump($_SESSION['RGadmin']['id']);
	//exit;

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
	
	$res = $dba->query("SELECT user_id FROM rego_users WHERE password = '".$pass1."' AND user_id = '".$_SESSION['RGadmin']['id']."'");
	//echo mysqli_error($dbc);
	
	if($res->num_rows > 0){
		$sql = "UPDATE rego_users SET password = '".$pass3."' WHERE user_id = '".$_SESSION['RGadmin']['id']."'";
		if($dba->query($sql)){
			ob_clean();	echo 'success'; exit;
		}else{
			ob_clean();	echo 'Error : '.mysqli_error($dba);
		}
	}else{
		ob_clean();	echo 'old';
	}
	
	
?>