<?
	if(session_id()==''){session_start();}
	ob_start();
	include('../../dbconnect/db_connect.php');


	if($_REQUEST['date'] !=''){
		$_REQUEST['date'] = date('Y-m-d', strtotime($_REQUEST['date']));
	}

	if($_REQUEST['publish_on'] != 'Not published'){
		$_REQUEST['publish_on'] = date('Y-m-d H:i', strtotime($_REQUEST['publish_on']));
	}else{
		$_REQUEST['publish_on'] = 'Not published';
	}

	$insertdata = '';
	if($_REQUEST['id'] > 0){

		$olddata = array();
		$sql1w = "SELECT * FROM ".$cid."_comm_centers WHERE id = '".$_REQUEST['id']."'";
		if($res1w = $dbc->query($sql1w)){
			if($row1w = $res1w->fetch_assoc()){
				$olddata = $row1w;
			}
		}

		include('db_array.php');
		foreach($_REQUEST as $k=>$v){
			if($v != $olddata[$k] && isset($cc_array[$k])){
				$history[] = array('field'=>$cc_array[$k], 'prev'=>$olddata[$k], 'new'=>$v, 'user'=>$_SESSION['rego']['name']);
			}
		}

		//update to comm center log...
		if(!empty($history)){
			foreach($history as $k=>$v){
				$dbc->query("INSERT INTO ".$cid."_commCenters_logs (cc_id, field, prev, new, user) VALUES ('".$_REQUEST['id']."','".$v['field']."','".$v['prev']."','".$v['new']."','".$v['user']."' ) ");
			}
		}

		$insertID = $_REQUEST['id'];
		$sql = "UPDATE ".$cid."_comm_centers SET `username`='".$_REQUEST['username']."', `publish_on`='".$_REQUEST['publish_on']."', `date`='".$_REQUEST['date']."', `anno_type`='".$_REQUEST['anno_type']."', `appr_required`='".$_REQUEST['appr_required']."', `fromss`='".$_REQUEST['fromss']."', `tooss`='".$_REQUEST['tooss']."', `anno_mode`='".$_REQUEST['anno_mode']."', `anno_category`='".$_REQUEST['anno_category']."', `description`='".$_REQUEST['description']."' WHERE id='".$_REQUEST['id']."'";
	}else{

		$insertdata = 1;
		if(isset($_SESSION['rego']['comm_center']['need_approver'])){
			$need_approval = $_SESSION['rego']['comm_center']['need_approver'];
		}else{
			$need_approval = 1;
		}

		if($need_approval == 1){ $submit_status = 2;}else{ $submit_status = 1;}

		$sql = "INSERT INTO ".$cid."_comm_centers (`anno_id`, `username`, `approval`, `submit_status`, `publish_on`, `month`, `date`, `anno_type`, `appr_required`, `fromss`, `tooss`, `anno_mode`, `anno_category`, `description`, `status`) VALUES ('".$_REQUEST['anno_id']."', '".$_REQUEST['username']."', '".$need_approval."', '".$submit_status."', '".$_REQUEST['publish_on']."', '".date('m')."', '".$_REQUEST['date']."', '".$_REQUEST['anno_type']."', '".$_REQUEST['appr_required']."', '".$_REQUEST['fromss']."', '".$_REQUEST['tooss']."', '".$_REQUEST['anno_mode']."', '".$_REQUEST['anno_category']."', '".$_REQUEST['description']."', '1')";
	}

	ob_clean();
	if($dbc->query($sql)){

		if($insertdata == 1){ //only when insert data...

			$insertID = $dbc->insert_id;
			include('db_array.php');
			foreach($_REQUEST as $k=>$v){
				$history[] = array('field'=>$cc_array[$k], 'new'=>$v, 'user'=>$_SESSION['rego']['name']);
			}

			//insert to comm center log...
			if(!empty($history)){
				foreach($history as $k=>$v){
					$dbc->query("INSERT INTO ".$cid."_commCenters_logs (cc_id, field, prev, new, user) VALUES ('".$insertID."','".$v['field']."','','".$v['new']."','".$v['user']."' ) ");
				}
			}
		}
		
		$data['lastid'] = $insertID;
		$data['msg'] = 'success';
		echo json_encode($data);
	}else{
		echo mysqli_error($dbc);
	}

?>