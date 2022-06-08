<?
	
	if(session_id()==''){session_start();}
	ob_start();
	include("../../../dbconnect/db_connect.php");
	//var_dump($_REQUEST); //exit;
	
	$sql = "INSERT INTO ".$cid."_branches (id, code, th, en, entity) VALUES ";
	foreach($_REQUEST['branches'] as $k=>$v){
		if(!empty($v['code'])){
			$sql .= "('".$k."',";
			$sql .= "'".$dbc->real_escape_string($k])."',";
			$sql .= "'".$dbc->real_escape_string($v['th'])."',";
			$sql .= "'".$dbc->real_escape_string($v['en'])."',";
			$sql .= "'".$dbc->real_escape_string($v['entity'])."'),";
		}
	}
	$sql = substr($sql,0,-1);
	$sql .= " ON DUPLICATE KEY UPDATE th=VALUES(th), en=VALUES(en), entity=VALUES(entity)";
	//echo($sql); exit;
	
	if($dbc->query($sql)){
		ob_clean();
		echo 'success';
	}else{
		ob_clean();
		echo mysqli_error($dbc);
	}
	

