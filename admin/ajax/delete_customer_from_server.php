<?

	if(session_id()==''){session_start();}
	ob_start();
	include("../dbconnect/db_connect.php");
	if(empty($_REQUEST) || $_REQUEST['id'] == '0'){
		ob_clean(); echo 'No parameters send'; exit;
	}
	//var_dump($_REQUEST); exit;
	
	$id = '';
	$clientID = '';
	$email ='';
	$res = $dba->query("SELECT * FROM rego_customers WHERE id = '".$_REQUEST['id']."'");
	if($res->num_rows > 0){
		if($row = $res->fetch_assoc()){
			$id = $row['id'];
			$clientID = $row['clientID'];
			$email = $row['email'];
		}
	}else{
		ob_clean(); echo 'Customer not exist in database'; exit;
	}
	if(empty($id) || empty($clientID) || empty($email)){
		ob_clean(); echo 'Customer not exist in database'; exit;
	}
	//exit;
	
	$dbname = $prefix.$clientID;
	$dbc = new mysqli($my_database, $my_username, $my_password, $dbname);
	$dbc->query('SET foreign_key_checks = 0');
	if($result = $dbc->query("SHOW TABLES")){
		while($row = $result->fetch_array(MYSQLI_NUM)){
			$dbc->query('DROP TABLE IF EXISTS '.$row[0]);
		 }
	}
	$dbc->query('SET foreign_key_checks = 1');
	$dbc->close();	
	
	// DELETE FOLDERS //////////////////////////
	function removeDir($dir){
		if(is_dir($dir)){
			$objects = scandir($dir);
			foreach($objects as $object){
				if($object != "." && $object != ".."){
					if(filetype($dir."/".$object) == "dir"){
						removeDir($dir."/".$object); 
					}else{ 
						unlink($dir."/".$object);
					}
				}
			}
			reset($objects);
			@rmdir($dir);
		}
	}
	if(strlen($clientID) == 9){
		removeDir(DIR.$clientID);
	}
	//var_dump(DIR.$clientID);
	//exit;
	
	$dba->query("DELETE FROM rego_customers WHERE id = '".$id."'");
	
	//var_dump($email);
	$data = array();
	$res = $dba->query("SELECT username,id, access, last FROM rego_all_users WHERE access LIKE'%".$clientID."%'");
	while($row = $res->fetch_assoc()){
		$data[$row['id']]['access'] = $row['access'];
		$data[$row['id']]['last'] = $row['last'];
		$data[$row['id']]['username'] = $row['username'];
	}
	if($data){
		foreach($data as $k=>$v){
			$tmp = explode(',', $v['access']);
			if(count($tmp) == 1){
				$dba->query("DELETE FROM rego_all_users WHERE id = '".$k."'");
				$dba->query("DELETE FROM rego_consent_log WHERE user_name = '".$v['username']."' AND cid = '".$v['access']."'" );
				
			}else{
				if (($key = array_search($clientID, $tmp)) !== false) {
					 unset($tmp[$key]);
				}
				reset($tmp);	
				$last = $v['last'];
				if ($last == $clientID){
					 $last = $tmp[key($tmp)];
				}
				$access = implode(',', $tmp);
				$dba->query("UPDATE rego_all_users SET 
					access = '".$access."', 
					last = '".$last."' 
					WHERE id = '".$k."'");
			}
		}
	}
	ob_clean(); 
	echo 'success';


