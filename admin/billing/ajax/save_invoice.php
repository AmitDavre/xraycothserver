<?

	if(session_id()==''){session_start();}
	ob_start();
	include("../../dbconnect/db_connect.php");
	//var_dump($_REQUEST['body']); exit;
	if($_REQUEST['total'] == 0){echo 'zero'; exit;}
	$_REQUEST['body'] = serialize($_REQUEST['body']);
	//var_dump($_REQUEST); exit;
	
	$sql = "INSERT IGNORE INTO rego_invoices (";
		foreach($_REQUEST as $k=>$v){
			$sql .= $k.",";
		}
	$sql = substr($sql,0,-1).") VALUES (";
		foreach($_REQUEST as $v){
			$sql .= "'".$dba->real_escape_string($v)."',";
		}
	$sql = substr($sql,0,-1).") ON DUPLICATE KEY UPDATE "; 
		foreach($_REQUEST as $k=>$v){
			$sql .= $k."=VALUES(".$k."),";
		}
	$sql = substr($sql,0,-1); 
	
	//var_dump($sql); //exit;
	//ob_clean();
	if($dba->query($sql)){
		echo 'success';
	}else{
		echo mysqli_error($dba);
	}
		foreach($_REQUEST as $k=>$v){
			$sql .= "'".$k."',";
		}
