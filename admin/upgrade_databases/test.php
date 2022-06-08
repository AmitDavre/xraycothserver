<?

	if(session_id()==''){session_start();}
	ob_start();
	include("../dbconnect/db_connect.php");

	$dbc = new mysqli($my_database, $my_username, $my_password);
	
	$customers = array();
	$res = $dba->query("SELECT clientID FROM rego_customers ORDER BY clientID ASC");
	while($row = $res->fetch_assoc()){
		$customers[] = $row['clientID'];
	}
	//unset($customers[0]); 
	var_dump($customers);
	//exit;
	
	foreach($customers as $v){
		$dbname = $prefix.$v.'.'.$v.'_employees';
		$sql = "ALTER TABLE $dbname ADD fix_deduct_1 DOUBLE NOT NULL AFTER fix_allow_10, ADD fix_deduct_2 DOUBLE NOT NULL AFTER fix_deduct_1, ADD fix_deduct_3 DOUBLE NOT NULL AFTER fix_deduct_2, ADD fix_deduct_4 DOUBLE NOT NULL AFTER fix_deduct_3, ADD fix_deduct_5 DOUBLE NOT NULL AFTER fix_deduct_4";
		if($res = $dbc->query($sql)){
			echo 'Ok<br>';
		}else{
			echo mysqli_error($dbc).'<br>';
		}
		//var_dump($sql);
	}
	exit;
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
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
	$res = $dba->query("SELECT id, name, access, last FROM rego_company_users WHERE access LIKE'%".$clientID."%'");
	while($row = $res->fetch_assoc()){
		$data[$row['id']]['access'] = $row['access'];
		$data[$row['id']]['last'] = $row['last'];
	}
	if($data){
		foreach($data as $k=>$v){
			$tmp = explode(',', $v['access']);
			if(count($tmp) == 1){
				$dba->query("DELETE FROM rego_company_users WHERE id = '".$k."'");
				
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
				$dba->query("UPDATE rego_company_users SET 
					access = '".$access."', 
					last = '".$last."' 
					WHERE id = '".$k."'");
			}
		}
	}
	ob_clean(); 
	echo 'success';


