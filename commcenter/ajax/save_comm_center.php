<?
	if(session_id()==''){session_start();}
	ob_start();
	include('../../dbconnect/db_connect.php');

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


	$blocks = array();
	$sql = "SELECT * FROM ".$cid."_document_textblocks";
	if($res = $dbc->query($sql)){
		while($row = $res->fetch_assoc()){
			$blocks[$row['id']] = $row['name'];
			$blocksdd[$row['name']] = $row['id'];
		}
	}
	
	$sectionArr = $_REQUEST['sectionArr'];
	$newArry = array();
	foreach ($sectionArr as $value) {
		if(in_array($value, $blocks)){
			$newArry[$blocksdd[$value]] = $blocksdd[$value];
		}
	}

	$serSection = $newArry;


	// echo '<pre>';
	// print_r($_REQUEST);
	// print_r($blocks);
	// print_r($newArry);
	// echo '</pre>';

	// exit;
	

	$settings = $_REQUEST['settings'];

	if($settings['header'] == 1){
		$headerval = isset($_REQUEST['headerval']) ? $_REQUEST['headerval'] : ' ';
	}else{
		$headerval = '';
	}

	if($settings['section'] == 1){
		if(isset($_REQUEST['areas'])){
			$_REQUEST['areas'] = serialize(array_values($_REQUEST['areas']));
		}else{
			$_REQUEST['areas'] = '';
		}
	}else{
		$_REQUEST['areas'] = '';
	}

	if($settings['footer'] == 1){
		$footerval = isset($_REQUEST['footerval']) ? $_REQUEST['footerval'] : ' ';
	}else{
		$footerval = '';
	}


	if($_REQUEST['id'] > 0){

		$sql = "UPDATE ".$cid."_comm_centers SET `settings`='".serialize($settings)."', `headerval`='".$headerval."', `sectionVal`='".serialize($serSection)."', `areas`='".$_REQUEST['areas']."', `footerval`='".$footerval."' WHERE id='".$_REQUEST['id']."'";
	}else{
		$sql = "INSERT INTO ".$cid."_comm_centers (`anno_id`, `publish_on`, `month`, `date`, `status`, `settings`, `headerval`, `sectionVal`, `areas`, `footerval`) VALUES ('".$AnnIdVal."', 'Not published', '".date('m')."', '".date('Y-m-d')."', '1', '".serialize($settings)."', '".$headerval."', '".serialize($serSection)."', '".$_REQUEST['areas']."', '".$footerval."')";
	}

	ob_clean(); 
	if($dbc->query($sql)){
		echo 'success';
	}else{
		echo mysqli_error($dbc);
	}