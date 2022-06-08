<?php

	if(session_id()==''){session_start();}
	ob_start();
	include('../../dbconnect/db_connect.php');
	include(DIR.'files/functions.php');
	//var_dump($_REQUEST); //exit;

	// echo '<pre>';
	// print_r($_SESSION);
	// echo '</pre>';

	// die();



	
	if(empty($_REQUEST['username']) || empty($_REQUEST['password'])){
		ob_clean(); 
		echo 'empty'; 
		exit;
	}

	$username = strtolower(preg_replace('/\s+/', '', $_REQUEST['username']));
	$password = hash('sha256', preg_replace('/\s+/', '', $_REQUEST['password'])); 

	//$sql = "SELECT * FROM rego_all_users WHERE LOWER(username) = '".$username."' AND password = '".$password."' AND type = 'emp'";
	$sql = "SELECT * FROM rego_all_users WHERE LOWER(username) = '".$username."' AND password = '".$password."'";
	if($res = $dbx->query($sql)){
		if($row = $res->fetch_assoc()){
			if($row['emp_status'] != 1){
				ob_clean(); 
				echo('suspended');
				exit;
			}
			if($row['emp_id'] == ''){
				ob_clean(); 
				echo('emp');
				exit;
			}
			$_SESSION['rego']['cid'] = $row['emp_access'];
			//$_SESSION['rego']['access'] = $row['access'];
			$_SESSION['rego']['type'] = $row['type'];
			//$_SESSION['rego']['approve'] = $row['approve'];
			//$_SESSION['rego']['module'] = $row['module'];
			$_SESSION['rego']['emp_id'] = $row['emp_id'];
			$_SESSION['rego']['name'] = $row['firstname'].' '.$row['lastname'];
			$_SESSION['rego']['phone'] = $com_users['phone'];
			$_SESSION['rego']['username'] = $row['username'];
			$_SESSION['rego']['img'] = $row['img'].'?'.time();
			$_SESSION['rego']['timestamp'] = time();
			$_SESSION['rego']['login_link'] = 'mob';
			$_SESSION['rego']['showConsentPage'] = '1';

			//writeToLogfile('log', 'Log-in');
			$cookie = array('user'=>$_REQUEST['username'], 'pass'=>$_REQUEST['password'], 'remember'=>$_REQUEST['remember'], 'lang'=>$lang);
			// setcookie('log', serialize($cookie), time()+31556926 ,'/');


			if($row['lang'] == '1'){

				setcookie("username", encrypt_decrypt($username, 'encrypt'), time() + (86400 * 30) , '/', '.xray.co.th' );
			}

			if($row['rego_lang'] == '1'){
				setcookie("password", encrypt_decrypt($_REQUEST["password"], 'encrypt'), time() + (86400 * 30) , '/', '.xray.co.th' );
			}


				
				

			ob_clean(); 
			$_SESSION['mobLogincheck'] = 'check';
			

			// if($_SESSION['adminLogincheck2'] = 'check' || $_SESSION['userLogincheck'] = 'true')
			// {
			// 	echo 'session';
			// }
			// else
			// {
				echo 'success';
			// }
			//echo 'emp';
			exit;
		}else{
			echo('wrong');
		}
	}else{
		echo('wrong');
	}








