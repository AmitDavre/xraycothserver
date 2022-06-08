<?
	if(session_id()==''){session_start();} 
	ob_start();
	include('../../dbconnect/db_connect.php');
	
	//var_dump($_REQUEST); exit;
	$_REQUEST['settings'] = serialize($_REQUEST['settings']);
	$sql = "INSERT INTO `erp_document_setup` (";
	foreach($_REQUEST as $k=>$v){
		$sql .= $k.',';
	}
	$sql = substr($sql,0,-1);
	$sql .= ") VALUES ("; 
	foreach($_REQUEST as $k=>$v){
		$sql .= "'".$dbc->real_escape_string($v)."',";
	}
	$sql = substr($sql,0,-1);
	$sql .= ") ON DUPLICATE KEY UPDATE ";
	foreach($_REQUEST as $k=>$v){
		$sql .= $k.' = VALUES('.$k.'),';
	}
	$sql = substr($sql,0,-1);
	
	//echo $sql; exit;
	ob_clean();
	if($dbc->query($sql)){
		echo 'success';
	}else{
		echo mysqli_error($dbc);
	}