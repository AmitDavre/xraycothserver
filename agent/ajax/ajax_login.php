<?php

	if(session_id()==''){session_start();}
	ob_start();
	include('../../dbconnect/db_connect.php');
	include(DIR.'files/functions.php');
	//var_dump($_REQUEST); exit;
	
	if(empty($_REQUEST['username']) || empty($_REQUEST['password'])){
		ob_clean(); 
		echo 'empty'; 
		exit;
	}
	
	$username = strtolower(preg_replace('/\s+/', '', $_REQUEST['username']));
	$password = hash('sha256', preg_replace('/\s+/', '', $_REQUEST['password'])); 

	$sql = "SELECT * FROM rego_agents WHERE LOWER(agent_id) = '".$username."' AND password = '".$password."'";
	if($res = $dbx->query($sql)){
		if($res->num_rows > 0){
			$row = $res->fetch_assoc();
			if($row['status'] != 1){
				ob_clean(); 
				echo('suspended');
				exit;
			}else{
				if($row['visit'] == 0){
					ob_clean(); 
					echo('first');
					exit;
				}
				$_SESSION['agent']['name'] = $row[$lang.'_name'];
				$_SESSION['agent']['email'] = $row['email'];
				$_SESSION['agent']['phone'] = $row['phone'];
				$_SESSION['agent']['agent_id'] = $row['agent_id'];
				$_SESSION['agent']['img'] = $row['img'].'?'.time();
				$_SESSION['agent']['timestamp'] = time();

				$cookie = array('user'=>$_REQUEST['username'], 'pass'=>$_REQUEST['password'], 'remember'=>$_REQUEST['remember'], 'lang'=>$lang);
				setcookie('aglog', serialize($cookie), time()+31556926 ,'/');
				
				ob_clean(); 
				echo 'ok';
				//echo 'emp';
				exit;
			}
		}else{
			ob_clean(); 
			echo('wrong');
		}
	}else{
		ob_clean(); 
		echo('wrong');
	}








