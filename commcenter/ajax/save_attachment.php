<?
	if(session_id()==''){session_start();}
	ob_start();
	include('../../dbconnect/db_connect.php');
	
	// echo '<pre>';
	// print_r($_REQUEST);
	// print_r($_FILES);
	// echo '</pre>';
	// exit;

	$tot_record = 1;
	$sqltt = "SELECT COUNT(*) as tot_record FROM ".$cid."_comm_centers WHERE month = '".date('m')."'";
	if($restt = $dbc->query($sqltt)){
		if($row = $restt->fetch_assoc()){
			$tot_record = $row['tot_record'] + 1;
		}
	}

	$todayYM 	= date('Y').date('m');
	//$todayD 	= date('d');
	$todayD 	= $tot_record;
	$yearm 		= (int)filter_var($todayYM, FILTER_SANITIZE_NUMBER_INT);
	$AnnId 		= str_pad($todayD, 3, "0", STR_PAD_LEFT);
	$AnnIdVal 	= $yearm.'-'.$AnnId;

	$uploadmap = '../../'.$cid.'/commcenter/attach/';
	if (!file_exists($uploadmap)) {
		mkdir($uploadmap, 0755, true);
	}

	if($_REQUEST['iddds'] > 0){

		

		$target_file = $uploadmap . basename($_FILES["filedd"]["name"]);
		if(move_uploaded_file($_FILES["filedd"]["tmp_name"], $target_file)) {

			$attach = array();
			$getAttach = "SELECT * FROM ".$cid."_comm_centers WHERE id='".$_REQUEST['iddds']."'";
			if($res = $dbc->query($getAttach)){
				if($row = $res->fetch_assoc()){
					$attach[] = $row;
				}
			}

			$preattach = $attach[0]['attachments'];
			if($preattach !=''){
				$newval = $preattach.$target_file.',';
			}else{
				$newval = $target_file.',';
			}

			$dbc->query("UPDATE ".$cid."_comm_centers SET attachments='".$newval."' WHERE id='".$_REQUEST['iddds']."'");

			ob_clean();
			echo 'success';
			
		}else{
			ob_clean();
			echo 'error';
		}

	}else{

		$target_file = $uploadmap . basename($_FILES["filedd"]["name"]);
		if(move_uploaded_file($_FILES["filedd"]["tmp_name"], $target_file)) {

			$newval = $target_file.',';
			$dbc->query("INSERT INTO ".$cid."_comm_centers (anno_id, month, date, attachments) VALUES ('".$AnnIdVal."','".date('m')."','".date('Y-m-d')."','".$newval."') ");

			ob_clean();
			echo 'success';
			
		}else{
			ob_clean();
			echo 'error';
		}
	}


?>