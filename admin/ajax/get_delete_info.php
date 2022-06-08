<?

	if(session_id()==''){session_start();}
	ob_start();
	include("../dbconnect/db_connect.php");
	if(empty($_REQUEST) || $_REQUEST['id'] == '0'){
		 $result['string'] = '<i class="fa fa-exclamation-triangle"></i>&nbsp; '.$lng['No parameters send'];
		 echo json_encode($result);
		 exit;
	}
	//var_dump($_REQUEST); //exit;
	$result['error'] = 1;
	$result['string'] = '<p style="font-size:13px; line-height:150%">';
	
	$id = '';
	$clientID = '';
	$email ='';
	$res = $dba->query("SELECT * FROM rego_customers WHERE id = '".$_REQUEST['id']."'");
	if($res->num_rows > 0){
		if($row = $res->fetch_assoc()){
			$clientID = $row['clientID'];
			$email = $row['email'];
			$result['string'] .= '<i class="fa fa-check-square-o"></i>&nbsp; '.$lng['This will remove Customer'].' <b>'.strtoupper($clientID).'</b> '.$lng['from server'].'<br>';
		}
	}else{
		 $result['string'] = '<i class="fa fa-exclamation-triangle"></i>&nbsp; '.$lng['Customer not exist in database'];
		 echo json_encode($result);
		 exit;
	}
	
	$dbc = @new mysqli($my_database, $my_username, $my_password);
	$dbname = $prefix.$clientID;
	if(empty(mysqli_fetch_array(mysqli_query($dbc,"SHOW DATABASES LIKE '$dbname'")))){
		 $result['string'] = '<i class="fa fa-exclamation-triangle"></i>&nbsp; '.$lng['Database'].' <b>'.$dbname.'</b> '.$lng['not exist'].'';
		 echo json_encode($result);
		 exit;
	}else{
		$result['string'] .= '<i class="fa fa-check-square-o"></i>&nbsp; '.$lng['This will delete database'].' <b>'.$clientID.'</b> '.$lng['from Customer'].'<br>';
	}	
	
	$result['string'] .= '<i class="fa fa-check-square-o"></i>&nbsp; '.$lng['This will delete all folders and files from Customer'].'<br>';

	$data = array();
	$res = $dba->query("SELECT id, username, access, last FROM rego_all_users WHERE access LIKE'%".$clientID."%'");
	while($row = $res->fetch_assoc()){
		$data[$row['id']]['access'] = $row['access'];
		$data[$row['id']]['last'] = $row['last'];
		$data[$row['id']]['username'] = $row['username'];
	}
	if($data){
		foreach($data as $k=>$v){
			$tmp = explode(',', $v['access']);
			if(count($tmp) == 1){
				$result['string'] .= '<i class="fa fa-check-square-o"></i>&nbsp; '.$lng['User'].' <b>'.$v['name'].'</b> '.$lng['will be deleted from database'].'<br>';
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
				$result['string'] .= '<i class="fa fa-check-square-o"></i>&nbsp; '.$lng['Access will be removed for user'].' <b>'.$v['username'].'</b><br>';
			}
		}
	}else{
		$result['string'] .= '<i class="fa fa-exclamation-triangle"></i>&nbsp; '.$lng['No users found for this Company'].'<br>';	
	}
	
	$result['error'] = 0;
	$result['string'] .= '<i class="fa fa-exclamation-triangle"></i>&nbsp; <b>'.$lng['THIS CAN NOT BE UNDONE'].' !!!</b></p>';

	echo json_encode($result);




