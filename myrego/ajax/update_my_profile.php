<?php

	if(session_id()==''){session_start();}
	ob_start();
	include("../../dbconnect/db_connect.php");
	//var_dump($_REQUEST); exit;
	
	if(strtolower($_REQUEST['username']) != strtolower($_REQUEST['prev_username'])){
		// CHECK IF USERNAME EXIST
		$sql = "SELECT username FROM rego_company_users WHERE LOWER(username) = '".strtolower($_REQUEST['username'])."'";
		if($res = $dbx->query($sql)){
			if($res->num_rows > 0){
				ob_clean();
				echo 'exist';
				exit;
			}
		}
	}
	
	$sql = "UPDATE rego_company_users SET 
		firstname = '".$dbx->real_escape_string($_REQUEST['firstname'])."',
		lastname = '".$dbx->real_escape_string($_REQUEST['lastname'])."',
		name = '".$dbx->real_escape_string($_REQUEST['firstname'].' '.$_REQUEST['lastname'])."',
		position = '".$dbx->real_escape_string($_REQUEST['position'])."',
		phone = '".$dbx->real_escape_string($_REQUEST['phone'])."',
		line_id = '".$dbx->real_escape_string($_REQUEST['line_id'])."',
		username = '".$dbx->real_escape_string(strtolower($_REQUEST['username']))."' 
		WHERE id = '".$_REQUEST['id']."'";
	//var_dump($sql);
	//echo($sql);
	//exit;
	ob_clean();	
	if($dbx->query($sql)){
		$_SESSION['rego']['username'] = strtolower($_REQUEST['username']);
		$_SESSION['rego']['name'] = $_REQUEST['firstname'].' '.$_REQUEST['lastname'];
		$_SESSION['rego']['phone'] = $_REQUEST['phone'];
		echo 'success';
	}else{
		echo mysqli_error($dbx);
	}
