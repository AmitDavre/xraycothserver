<?php

	if(session_id()==''){session_start();}
	ob_start();
	include('../db_connect.php');
	include(DIR.'files/functions.php');
	//var_dump($_REQUEST); exit;

	function array_flatten($array) { 
	  if (!is_array($array)) { 
	    return false; 
	  } 
	  $result = array(); 
	  
	  foreach ($array as $key => $value) { 

	    if (is_array($value)) { 
	      $result = array_merge($result, array_flatten($value)); 
	    } else { 
	      $result = array_merge($result, array($key => $value));
	    } 
	  } 

	  $resultsss = array_combine(range(1, count($result)), array_values($result));
	  return $resultsss; 
	}
	
	if(empty($_REQUEST['username']) || empty($_REQUEST['password'])){
		ob_clean(); 
		echo json_encode('empty'); 
		exit;
	}

	$username = strtolower(preg_replace('/\s+/', '', $_REQUEST['username']));
	$password = hash('sha256', preg_replace('/\s+/', '', $_REQUEST['password'])); 

	$sql = "SELECT * FROM rego_all_users WHERE LOWER(username) = '".$username."' AND password = '".$password."'";
	if($res = $dbx->query($sql)){
		if($res->num_rows > 0){
			$row = $res->fetch_assoc();

			if($row['sys_status'] != 1){
				ob_clean(); 
				echo json_encode('suspended');
				exit;
			}else if($row['type'] != 'sys'){
				ob_clean(); 
				echo json_encode('type');
				exit;
			}



			$access = $row['access'];

			$str_arr = explode (",", $access); 

			

			ob_clean();
			echo json_encode($str_arr); exit;

			
		}
		else
		{
			echo json_encode('wrong');
		}
	}
	else
	{
		echo json_encode('wrong');
	}








