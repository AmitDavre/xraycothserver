<?php

	if(session_id()==''){session_start();}
	ob_start();
	include('../dbconnect/db_connect.php');
	include(DIR.'files/functions.php');
	//var_dump($_REQUEST); exit;
	
	if(empty($_REQUEST['username']) || empty($_REQUEST['password'])){
		ob_clean(); 
		echo 'empty'; 
		exit;
	}
	
	$username = strtolower(preg_replace('/\s+/', '', $_REQUEST['username']));
	$password = hash('sha256', preg_replace('/\s+/', '', $_REQUEST['password'])); 

	$sql = "SELECT * FROM rego_users WHERE LOWER(username) = '".$username."' AND password = '".$password."'";
	if($res = $dba->query($sql)){
		if($res->num_rows > 0){
			$row = $res->fetch_assoc();
			if($row['status'] != 1){
				ob_clean(); 
				echo('suspended');
				exit;
			}else{
				if($row['user_id'] < 102){
					include('../files/sc_on_permissions.php');
					$_SESSION['RGadmin']['img'] = 'images/xrayadmin.jpg?'.time();
				}else{	
					include('../files/sc_off_permissions.php');
					$_SESSION['RGadmin']['img'] = $row['img'];
					$_SESSION['RGadmin']['access'] = unserialize($row['access']);
					$_SESSION['RGadmin']['as_customers'] = unserialize($row['as_customers']);
				}						
				$_SESSION['RGadmin']['id'] = $row['user_id'];
				$_SESSION['RGadmin']['username'] = $row['username'];
				$_SESSION['RGadmin']['name'] = $row['name'];
				$_SESSION['RGadmin']['email'] = $row['email'];
				$_SESSION['RGadmin']['phone'] = $row['phone'];
				$_SESSION['RGadmin']['showConsentPage'] = '1';
				//$_SESSION['RGadmin']['platform'] = 'SC';
				
				//$_SESSION['RGadmin']['clients'] = 'all';//$row['clients'];
				$_SESSION['RGadmin']['timestamp'] = time();
				//setcookie('aCID', $_POST['clientID'], time()+31556926 ,'/');
				//header('location: ../index.php?mn=2');
				$base = basename($_SERVER["REQUEST_URI"]);
				if (strpos($base, 'index.php') !== false) {
					//header('location: ../'.$base);
				}else{
					//header('location: ../index.php?mn=2');
				}



				// $hourTime = time() + 3600 * 24 * 30;

				// if($row['lang'] == '1'){
				// 	setcookie('username', $username, $hourTime);
				// }

				// if($row['rego_lang'] == '1'){
				// 	setcookie('password', $password, $hourTime);
				// }



				if($row['lang'] == '1'){

					setcookie("username", encrypt_decrypt($username, 'encrypt'), time() + (86400 * 30) , '/', '.xray.co.th' );
				}

				if($row['rego_lang'] == '1'){
					setcookie("password", encrypt_decrypt($_REQUEST["password"], 'encrypt'), time() + (86400 * 30) , '/', '.xray.co.th' );
				}




				ob_clean();

				// unset($_COOKIE['admin']); 
				// setcookie("admincheck", "login", time() + (86400 * 30) , '/', '.pkfpeople.com' );

				$_SESSION['adminLogincheck'] = 'check';
		

				if($_SESSION['userLogincheck'] == 'check' || $_SESSION['mobLogincheck'] == 'check')
				{
					echo 'session';
				}
				else
				{
					echo 'index.php?mn=2';

				}
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
	
	
	
	
	
	
