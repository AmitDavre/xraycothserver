<?

	if(session_id()==''){session_start();}
	ob_start();
	include("../../../dbconnect/db_connect.php");
	//var_dump($_REQUEST); exit;
	
	$sql = "INSERT INTO ".$cid."_branches (id, code, th, en, entity) VALUES ";
	foreach($_REQUEST['branches'] as $k=>$v){
		if(!empty($v['code'])){
			$sql .= "('".$k."',";
			$sql .= "'".$dbc->real_escape_string($v['code'])."',";
			$sql .= "'".$dbc->real_escape_string($v['th'])."',";
			$sql .= "'".$dbc->real_escape_string($v['en'])."',";
			$sql .= "'".$dbc->real_escape_string($v['entity'])."'),";
		}
	}
	$sql = substr($sql,0,-1);
	$sql .= " ON DUPLICATE KEY UPDATE th=VALUES(th), en=VALUES(en), entity=VALUES(entity)";
	//echo($sql); exit;
	
	if($dbc->query($sql)){

		foreach($_REQUEST['branches'] as $k=>$v){
			if(!empty($v['code'])){

				$checkb = "SELECT * FROM `".$cid."_branches_data` WHERE `ref`= '".$k."' ";
				$resultb = $dbc->query($checkb);

				$num_rows = $resultb->num_rows;
				if($num_rows > 0){
					$sqlbdu = "UPDATE `".$cid."_branches_data` SET `bra_name_th`='".$v['th']."', `bra_name_en`='".$v['en']."' WHERE `ref`='".$k."'";
					$resultub = $dbc->query($sqlbdu);
					//print_r($resultub);

				}else{
					$sqlbdi = "INSERT INTO `".$cid."_branches_data`(`ref`, `bra_name_th`, `bra_name_en`) VALUES ('".$k."', '".$v['th']."','".$v['en']."')";
					$resultub = $dbc->query($sqlbdi);
				}
			}
		}

		//exit;

		ob_clean();
		echo 'success';
	}else{
		ob_clean();
		echo mysqli_error($dbc);
	}



	
	

