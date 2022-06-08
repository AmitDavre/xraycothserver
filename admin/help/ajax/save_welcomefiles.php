<?
	if(session_id()==''){session_start();}
	ob_start();
	include("../../dbconnect/db_connect.php");
	//var_dump($_REQUEST); //exit;
	
	$sql = "INSERT INTO rego_welcomefiles (";
		foreach($_REQUEST as $k=>$v){
			$sql .= $k.",";
		}
		$sql = substr($sql,0,-1);	
	$sql .= ") VALUES (";
		foreach($_REQUEST as $k=>$v){
			$sql .= "'".$dba->real_escape_string($v)."',";
		}
		$sql = substr($sql,0,-1);	
	$sql .= ") ON DUPLICATE KEY UPDATE "; 
		foreach($_REQUEST as $k=>$v){
			$sql .= $k."=VALUES(".$k."),";
		}
		$sql = substr($sql,0,-1);	
	
	//var_dump($sql); exit;
	
	ob_clean();
	if($dba->query($sql)){
		echo 'success';
	}else{
		echo mysqli_error($dba);
	}
