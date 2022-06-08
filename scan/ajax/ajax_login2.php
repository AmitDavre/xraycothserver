<?php

	if(session_id()==''){session_start();}
	ob_start();
	include('../db_connect.php');
	
	if(!empty($_REQUEST['clientID'])){
		$cid = strtolower($_REQUEST['clientID']);
		$my_dbcname = $prefix.$cid;
		$dbc = new mysqli($my_database,$my_username,$my_password,$my_dbcname);
		if($dbc->connect_error) {
			ob_clean(); 
			echo 'connect'; 
			exit;
		}else{
			mysqli_set_charset($dbc,"utf8");
		}
	}
	
	$sql = "SELECT id FROM ".$cid."_leave_time_settings WHERE scan_password = '".$_REQUEST['password']."'";
	if($res = $dbc->query($sql)){
		if($res->num_rows > 0){
			$_SESSION['scan']['cid'] = $cid;
			$cookie = array('cid'=>$cid, 'pass'=>$_REQUEST['password']);
			setcookie('scan', serialize($cookie), time()+31556926 ,'/');
			ob_clean();
			echo 'success'; exit;
		}else{
			ob_clean();
			echo('wrong');
		}
	}else{
		ob_clean();
		echo('wrong');
	}






