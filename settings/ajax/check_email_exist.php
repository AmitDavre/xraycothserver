<?
	if(session_id()==''){session_start();}
	ob_start();
	include("../../dbconnect/db_connect.php");
	//var_dump($_REQUEST); exit;
	$_REQUEST['email'] = preg_replace('/\s+/', '', strtolower($_REQUEST['email']));
	
	$a_exist = false;
	if($res = $dbx->query("SELECT * FROM rego_all_users WHERE LOWER(username) = '".$_REQUEST['email']."'")){
		$a_exist = $res->fetch_assoc();
	}else{
		var_dump(mysqli_error($dbx)); //exit;
	}
	//var_dump($a_exist); exit;
	
	// $c_exist = false;
	// if($res = $dbc->query("SELECT * FROM ".$cid."_users WHERE LOWER(username) = '".$_REQUEST['email']."'")){
	// 	$c_exist = $res->fetch_assoc();
	// }else{
	// 	var_dump(mysqli_error($dbc)); //exit;
	// }
	//var_dump($c_exist); //exit;
	
	if($a_exist){
		ob_clean();
		echo 'userexist';
	}else{
		ob_clean();
		echo 'success';
	}
	
