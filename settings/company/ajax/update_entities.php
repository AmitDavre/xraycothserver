<?

	if(session_id()==''){session_start();}
	ob_start();
	include("../../../dbconnect/db_connect.php");
	//var_dump($_REQUEST); exit;
	
	$sql = "INSERT INTO ".$cid."_entities (id, code, th, en) VALUES ";
	foreach($_REQUEST['entities'] as $k=>$v){
		if(!empty($v['code'])){
			$sql .= "('".$k."',";
			$sql .= "'".$dbc->real_escape_string($v['code'])."',";
			$sql .= "'".$dbc->real_escape_string($v['th'])."',";
			$sql .= "'".$dbc->real_escape_string($v['en'])."'),";
		}
	}
	$sql = substr($sql,0,-1);
	$sql .= " ON DUPLICATE KEY UPDATE th=VALUES(th), en=VALUES(en)";
	//echo($sql); exit;
	
	if($dbc->query($sql)){

		foreach($_REQUEST['entities'] as $k=>$v){
			if(!empty($v['code'])){

				$checkb = "SELECT * FROM `".$cid."_entities_data` WHERE `ref`= '".$k."' ";
				$resultb = $dbc->query($checkb);

				$num_rows = $resultb->num_rows;
				if($num_rows > 0){
					$sqlbdu = "UPDATE `".$cid."_entities_data` SET `code`='".$v['code']."' WHERE `ref`='".$k."'";
					$resultub = $dbc->query($sqlbdu);
					//print_r($resultub);
				}else{
					$sqlbdi = "INSERT INTO `".$cid."_entities_data`(`ref`, `code`) VALUES ('".$k."', '".$v['code']."')";
					$resultub = $dbc->query($sqlbdi);
				}
			}
		}

		ob_clean();
		echo 'success';
	}else{
		ob_clean();
		echo mysqli_error($dbc);
	}
	

