<?

	if(session_id()==''){session_start();}
	ob_start();
	include("../../dbconnect/db_connect.php");
	//var_dump($_REQUEST); //exit;
	
	// DELETE ONLY FROM ACCESS IF MORE THAN ONE COMPANY !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
	$res = $dbx->query("SELECT id, access, type FROM rego_all_users WHERE LOWER(username) = '".strtolower($_REQUEST['username'])."'");
	$row = $res->fetch_assoc();
	$access = explode(',', $row['access']);
	//var_dump($row); exit;
	//var_dump($row); //exit;
	
	if(count($access > 1)){
		//var_dump($access); //exit;
		if(($key = array_search($cid, $access)) !== false) {
			 unset($access[$key]);
		}
		//var_dump($access); exit;
		if($access){
			$last = $row['last'];
			if($last == $cid){
				$last = $access[0];
			}
			$access = implode(',', $access);
			$res = $dbx->query("UPDATE rego_all_users SET 
				access = '$access',  
				last = '$last' 
				WHERE id = '".$row['id']."'");
		}else{
			if($row['type'] == 'emp'){
				$dbx->query("DELETE FROM rego_all_users WHERE id = '".$row['id']."'");
			}
		}
		//var_dump($access); exit;
		//$res = $dbc->query("DELETE FROM ".$cid."_user_permissions WHERE id = '".$cid.'_'.$_REQUEST['usename']."'");
	}else{
		if($row['type'] == 'emp'){
			$dbx->query("DELETE FROM rego_all_users WHERE id = '".$row['id']."'");
		}
	}
	
	$dbc->query("DELETE FROM ".$cid."_users WHERE username = '".$_REQUEST['username']."'");
	
	$dbc->query("UPDATE ".$cid."_employees SET allow_login = '".$_REQUEST['val']."' WHERE emp_id = '".$_REQUEST['id']."'");
	
	ob_clean();
	echo 'success';
	












