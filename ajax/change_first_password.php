<?php
	if(session_id()==''){session_start();}
	ob_start();
	include('../dbconnect/db_connect.php');
	//var_dump($_REQUEST); exit;
	
	if(empty($_REQUEST['npassword']) || empty($_REQUEST['rpassword'])){
		ob_clean(); 
		echo 'empty'; 
		exit;
	}
	if($_REQUEST['npassword'] != $_REQUEST['rpassword']){
		ob_clean(); 
		echo 'same'; 
		exit;
	}
	if(strlen($_REQUEST['npassword']) < 8){
		ob_clean(); 
		echo 'short'; 
		exit;
	}

	$username = strtolower($_REQUEST['username']);
	$password = hash('sha256', $_REQUEST['npassword']); 

	if($dbx->query("UPDATE rego_all_users SET 
		password = '".$password."',  
		visit = '1' 
		WHERE LOWER(username) = '".$username."'")){
			ob_clean(); 
			echo 'success'; 
			exit;
		}else{
			ob_clean(); 
			echo 'Error'; 
			exit;
		};
	
	
	

