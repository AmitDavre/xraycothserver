<?

	if(session_id()==''){session_start();}
	ob_start();
	include("../../dbconnect/db_connect.php");
	//var_dump($_REQUEST); exit;
	
	$res = $dbc->query("SELECT * FROM ".$cid."_users WHERE type = 'sys'");
	if($res->num_rows < 2){
		ob_clean();
		echo 'last'; 
		exit;
	}
	//exit;
	if($res = $dbc->query("DELETE FROM ".$cid."_users WHERE id = '".$_REQUEST['id']."'")){
		var_dump('DELETE FROM $cid_users'); //exit;
	}else{
		var_dump(mysqli_error($dbc)); //exit;
	}
	if(!empty($_REQUEST['emp'])){
		if($dbc->query("UPDATE ".$cid."_employees SET allow_login = '0' WHERE emp_id = '".$_REQUEST['emp']."'")){
			var_dump('UPDATE $cid_employees SET allow_login = 0');
		}else{
			var_dump(mysqli_error($dbc)); //exit;
		}
	}

	$a_exist = false;
	if($res = $dbx->query("SELECT * FROM rego_all_users WHERE id = '".$_REQUEST['ref']."'")){
		$a_exist = $res->fetch_assoc();
	}else{
		var_dump(mysqli_error($dbx)); //exit;
	}

	$tmpcomp = explode(',', $a_exist['access']);
	if(count($tmpcomp) == 1){
		
		$dbx->query("DELETE FROM rego_consent_log WHERE user_name = '".$a_exist['username']."' " );
		
	}


	//var_dump($a_exist); //exit;
	
	// DELETE ONLY FROM rego_all_users IF MORE THAN ONE COMPANY !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
	if($a_exist){
		$access = explode(',', $a_exist['access']);
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
			if($res = $dbx->query("UPDATE rego_all_users SET access = '$access', last = '$last' WHERE id = '".$a_exist['id']."'")){
				var_dump('UPDATE rego_all_users SET access, last'); //exit;
			}else{
				var_dump(mysqli_error($dbx)); //exit;
			}
		}else{
			if($res = $dbx->query("DELETE FROM rego_all_users WHERE id = '".$a_exist['id']."'")){
				var_dump('DELETE FROM rego_all_users'); //exit;
			}else{
				var_dump(mysqli_error($dbx)); //exit;
			}
		}
	}
	
	
	
	
	
	
