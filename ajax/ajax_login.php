<?php

	if(session_id()==''){session_start();}
	ob_start();
	include('../dbconnect/db_connect.php');
	include(DIR.'files/functions.php');
	//var_dump($_REQUEST); exit;
	//unset($_SESSION['rego']);
	// echo '<pre>';
	// print_r($_REQUEST['remember']);
	// echo '</pre>';
	// die();


	
	if(empty($_REQUEST['username']) || empty($_REQUEST['password'])){
		ob_clean(); 
		echo 'empty'; 
		exit;
	}

	$username = strtolower(preg_replace('/\s+/', '', $_REQUEST['username']));
	$password = hash('sha256', preg_replace('/\s+/', '', $_REQUEST['password'])); 

	$sql = "SELECT * FROM rego_all_users WHERE LOWER(username) = '".$username."'";
	if($res = $dbx->query($sql)){
		if($all_users = $res->fetch_assoc()){
			if($all_users['password'] != $password){
				ob_clean(); 
				echo('password');
				exit;
			}
			if($all_users['type'] == 'emp'){
				if($all_users['emp_status'] == 0){
					ob_clean(); 
					echo('status');
					exit;
				}
				$_SESSION['rego']['timestamp'] = time();
				$_SESSION['rego']['cid'] = $all_users['emp_access'];
				$_SESSION['rego']['type'] = $all_users['type'];
				$_SESSION['rego']['emp_id'] = $all_users['emp_id'];
				$_SESSION['rego']['fname'] = $all_users['firstname'];
				$_SESSION['rego']['name'] = $all_users['firstname'].' '.$all_users['lastname'];
				$_SESSION['rego']['username'] = $all_users['username'];
				$_SESSION['rego']['img'] = $all_users['img'].'?'.time();
	
				$_SESSION['userLogincheck'] = 'check';
				$_SESSION['rego']['showConsentPage'] = '1';
				





				//var_dump($_SESSION['rego']); exit;
				ob_clean(); 
				echo $all_users['type'];
				exit;
			}
			
			// IF NOT EMPLOYEE USER ////////////////////////////////////////////////////
			if($all_users['sys_status'] != 1){
				ob_clean(); 
				echo('status');
				exit;
			}
			$last = $all_users['last'];
			if(empty($last)){
				$tmp = explode(',', $all_users['access']);
				$last = $tmp[0];
			}
			if(empty($last)){
				ob_clean(); 
				echo('nocomp');
				exit;
			}
			//var_dump($all_users); //exit;
			
			$sql = "SELECT * FROM rego_customers WHERE clientID = '".$last."'";
			if($res = $dbx->query($sql)){
				if($row = $res->fetch_assoc()){
					if($row['status'] == 0){ 
						ob_clean(); 
						echo 'status'; 
						exit;
					}
				}else{
					ob_clean(); 
					echo 'wrong'; 
					exit;
				}
			}
			
			
			$my_dbcname = $prefix.$last;
			//var_dump($my_dbcname);
			
			$dbc = new mysqli($my_database,$my_username,$my_password,$my_dbcname);
			mysqli_set_charset($dbc,"utf8");

			$sql = "SELECT * FROM ".$last."_users WHERE ref = '".$all_users['id']."'";
			if($res = $dbc->query($sql)){
				if($res->num_rows > 0){
					$com_users = $res->fetch_assoc();
				}else{
					ob_clean();
					echo('wrong');
					exit;
				}
			}else{
				ob_clean();
				echo('wrong');
				exit;
			}
			//var_dump($com_users); exit;
			
			if($com_users['status'] != 1){
				ob_clean(); 
				echo('suspended');
				exit;
			}else{

				/*if(empty($com_users['entities']) || empty($com_users['permissions'])){
					ob_clean(); 
					echo('access');
					exit;
				}*/
		

				if($all_users['lang'] == '1'){

					setcookie("username", encrypt_decrypt($username, 'encrypt'), time() + (86400 * 30) , '/', '.xray.co.th' );
				}

				if($all_users['rego_lang'] == '1'){
					setcookie("password", encrypt_decrypt($_REQUEST["password"], 'encrypt'), time() + (86400 * 30) , '/', '.xray.co.th' );
				}



				$array['timestamp'] = time();
				$array['id'] = $com_users['id'];
				$array['ref'] = $com_users['ref'];
				$array['cid'] = $last;
				$array['showConsentPage'] = '1';
				$array['customers'] = $all_users['access'];
				$array['type'] = $com_users['type'];
				$array['emp_id'] = $com_users['emp_id'];
				$array['fname'] = $com_users['firstname'];
				$array['name'] = $com_users['name'];
				//$array['phone'] = $com_users['phone'];
				$array['username'] = $com_users['username'];
				$array['img'] = $com_users['img'].'?'.time();
				
				$array['mn_entities'] = $com_users['entities'];
				$array['mn_branches'] = $com_users['branches'];
				$array['mn_divisions'] = $com_users['divisions'];
				$array['mn_departments'] = $com_users['departments'];
				$array['mn_teams'] = $com_users['teams'];
				
				$array['sel_entities'] = $com_users['entities'];
				$array['sel_branches'] = $com_users['branches'];
				$array['sel_divisions'] = $com_users['divisions'];
				$array['sel_departments'] = $com_users['departments'];
				$array['sel_teams'] = $com_users['teams'];
				
				$array['access_group'] = $com_users['emp_group'];
				if($com_users['emp_group'] == 'all'){
					$array['emp_group'] = 's';
				}else{
					$array['emp_group'] = $com_users['emp_group'];
				}
				$tmp = unserialize($com_users['permissions']);
				if(!$tmp){$tmp = array();}

				$_SESSION['rego'] = array_merge($array, $tmp);
		// 
				$_SESSION['userLogincheck'] = 'check';

				//writeToLogfile('log', 'Log-in');
				
				//var_dump($tmp); exit;
				//var_dump($_SESSION['rego']); exit;


				ob_clean(); 
				if($_SESSION['adminLogincheck'] == 'check' || $_SESSION['mobLogincheck'] == 'check')
				{
					echo 'session';
				}
				else
				{
					echo $com_users['type'];
				}



				exit;
			}
		}else{
			ob_clean(); echo('exist'); exit;
		}
	}else{
		//echo mysqli_error($dbc);
		ob_clean(); echo('error'); exit;
	}
	
	
	
	
	
	
