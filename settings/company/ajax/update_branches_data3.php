<?

	if(session_id()==''){session_start();}
	ob_start();
	include("../../../dbconnect/db_connect.php");

	// var_dump($_REQUEST); //exit;


	// echo '<pre>';
	// print_r($_REQUEST);
	// echo '</pre>';

	// die();
	
	$loc3 = serialize($_REQUEST);
	
	$_REQUEST['loc3'] = $loc3;

	$sql = "INSERT INTO ".$cid."_branches_data (";
	foreach($_REQUEST as $k=>$v){
		$sql .= $k.', ';
	}
	$sql = substr($sql,0,-2).") VALUES (";
	foreach($_REQUEST as $k=>$v){
		$sql .= "'".$dbc->real_escape_string($v)."', ";
	}
	$sql = substr($sql,0,-2).") ON DUPLICATE KEY UPDATE ";
	foreach($_REQUEST as $k=>$v){
		$sql .= $k."=VALUES(".$k."), ";
	}
	$sql = substr($sql,0,-2);
	//echo($sql); exit;

	ob_clean();	
	if($dbc->query($sql)){
		echo 'success';
	}else{
		echo mysqli_error($dbc);
	}
	














