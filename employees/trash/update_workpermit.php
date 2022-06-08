<?php

	if(session_id()==''){session_start();}
	ob_start();

	include("../../dbconnect/db_connect.php");
	include(DIR.'files/functions.php');
	
	$_REQUEST['family'] = serialize($_REQUEST['family']);
	
	//var_dump($_REQUEST); exit;
	
	$sql = "INSERT INTO ".$cid."_workpermit (";
	foreach($_REQUEST as $k=>$v){
		$sql .= $k.', ';
	}
	$sql = substr($sql,0,-2);
	$sql .= ") VALUES ("; 
	foreach($_REQUEST as $k=>$v){
		$sql .= "'".mysqli_real_escape_string($dbc,$v)."', ";
	}
	$sql = substr($sql,0,-2).')';
	$sql .= " ON DUPLICATE KEY UPDATE ";
	foreach($_REQUEST as $k=>$v){
		$sql .= $k." = VALUES(".$k."), ";
	}
	$sql = substr($sql,0,-2);
	//echo $sql;

	//ob_clean();
	if($dbc->query($sql)){
		echo 'success';
	}else{
		echo mysqli_error($dbc);
	}








	exit;
	
	if(empty($_REQUEST['emp_id'])){echo 'empty'; exit;}
	
	$_REQUEST['emp_id'] = str_replace(' ', '', $_REQUEST['emp_id']);
	$_REQUEST['personal_email'] = strtolower($_REQUEST['personal_email']);
	if(isset($_REQUEST['firstname'])){$_REQUEST['th_name'] = $_REQUEST['firstname'].' '.$_REQUEST['lastname'];}
	if(isset($_REQUEST['idcard_nr'])){$_REQUEST['idcard_nr'] = str_replace('-','',$_REQUEST['idcard_nr']);}
	if(isset($_REQUEST['startdate'])){$_REQUEST['startdate'] = date('Y-m-d', strtotime($_REQUEST['startdate']));}
	if(!empty($_REQUEST['resign_date'])){
		$_REQUEST['resign_date'] = date('Y-m-d', strtotime($_REQUEST['resign_date']));
	}else{
		$_REQUEST['resign_date'] = '';
	}
	
	if(isset($_REQUEST['emergency_contacts'])){
		foreach($_REQUEST['emergency_contacts'] as $k=>$v){
			if(empty(trim($v['name']))){unset($_REQUEST['emergency_contacts'][$k]);}
		}
		$_REQUEST['emergency_contacts'] = serialize($_REQUEST['emergency_contacts']);
	}
	
	if(isset($_REQUEST['img_data']) && strlen($_REQUEST['img_data']) > 20){
		$uploadmap = '../../'.$cid.'/employees/img/';
		if (!file_exists($uploadmap)) {
			mkdir($uploadmap, 0755, true);
		}
		$filename = $uploadmap.str_replace(' ', '', $_REQUEST['emp_id']).'.jpg';
		$db_filename =  $cid.'/employees/img/'.str_replace(' ', '', $_REQUEST['emp_id']).'.jpg';
		$img_data = utf8_decode($_REQUEST['img_data']);
		$base64img = str_replace('data:image/png;base64,', '', $img_data);
		$data = base64_decode($base64img);
		$source = imagecreatefromstring($data);
		$imageSave = imagejpeg($source,$filename,80);
		imagedestroy($source);
		if(!$imageSave){
			$err_msg .= '<p>Error</p>';
		}
		unset($_REQUEST['img_data']);
		$_REQUEST['image'] = $db_filename;
	}
	
	$uploadmap = '../../'.$cid.'/employees/';
	if(!empty($_FILES['att_idcard']['tmp_name'])){
		$ext = pathinfo($_FILES['att_idcard']['name'], PATHINFO_EXTENSION);		
		$file = $_REQUEST['emp_id'].'_idcard.'.$ext;
		$filename = $uploadmap.$file;
		if(move_uploaded_file($_FILES['att_idcard']['tmp_name'],$filename)){
			$_REQUEST['att_idcard'] = $file;
		}
	}
	if(!empty($_FILES['att_housebook']['tmp_name'])){
		$ext = pathinfo($_FILES['att_housebook']['name'], PATHINFO_EXTENSION);		
		$file = $_REQUEST['emp_id'].'_housebook.'.$ext;
		$filename = $uploadmap.$file;
		if(move_uploaded_file($_FILES['att_housebook']['tmp_name'],$filename)){
			$_REQUEST['att_housebook'] = $file;
		}
	}
	if(!empty($_FILES['att_bankbook']['tmp_name'])){
		$ext = pathinfo($_FILES['att_bankbook']['name'], PATHINFO_EXTENSION);
		$file = $_REQUEST['emp_id'].'_bankbook.'.$ext;		
		$filename = $uploadmap.$file;
		if(move_uploaded_file($_FILES['att_bankbook']['tmp_name'],$filename)){
			$_REQUEST['att_bankbook'] = $file;
		}
	}
	if(!empty($_FILES['att_contract']['tmp_name'])){
		$ext = pathinfo($_FILES['att_contract']['name'], PATHINFO_EXTENSION);
		$file = $_REQUEST['emp_id'].'_contract.'.$ext;		
		$filename = $uploadmap.$file;
		if(move_uploaded_file($_FILES['att_contract']['tmp_name'],$filename)){
			$_REQUEST['att_contract'] = $file;
		}
	}
	if(!empty($_FILES['att_employment']['tmp_name'])){
		$ext = pathinfo($_FILES['att_employment']['name'], PATHINFO_EXTENSION);
		$file = $_REQUEST['emp_id'].'_employment_certificate.'.$ext;		
		$filename = $uploadmap.$file;
		if(move_uploaded_file($_FILES['att_employment']['tmp_name'],$filename)){
			$_REQUEST['att_employment'] = $file;
		}
	}
	if(!empty($_FILES['attach1']['tmp_name'])){
		$ext = pathinfo($_FILES['attach1']['name'], PATHINFO_EXTENSION);
		$file = $_REQUEST['emp_id'].'_end_contract.'.$ext;		
		$filename = $uploadmap.$file;
		if(move_uploaded_file($_FILES['attach1']['tmp_name'],$filename)){
			$_REQUEST['attach1'] = $file;
		}
	}
	if(!empty($_FILES['attach2']['tmp_name'])){
		$file = $_REQUEST['emp_id'].'_'.$_FILES['attach2']['name'];		
		$filename = $uploadmap.$file;
		if(move_uploaded_file($_FILES['attach2']['tmp_name'],$filename)){
			$_REQUEST['attach2'] = $file;
		}
	}

	$update = 1;
	if(isset($_REQUEST['update'])){
		$update = $_REQUEST['update'];
		unset($_REQUEST['update']);
	}
	
	if($update == 0){
		//$tax_settings = unserialize($pr_settings['tax_settings']);
		/*$_REQUEST['contribute'] = 'Y';
		$_REQUEST['modify_tax'] = 0;
		$_REQUEST['calc_method'] = $pr_settings['tax_calc_method'];//'def';
		$_REQUEST['startdate'] = date('D d-m-Y');
		$_REQUEST['emp_status'] = 1;
		$_REQUEST['emp_type'] = 1;
		$_REQUEST['bonus_payinmonth'] = 12;
		$_REQUEST['bonus_cash'] = 0;
		$_REQUEST['bonus_months'] = 0;
		$_REQUEST['tax_personal_allowance'] = $tax_settings['personal_allowance'];*/
		
		$sql = "INSERT INTO ".$cid."_employees (";
		foreach($_REQUEST as $k=>$v){
			$sql .= $k.', ';
		}
		$sql = substr($sql,0,-2);
		$sql .= ") VALUES ("; 
		foreach($_REQUEST as $k=>$v){
			$sql .= "'".mysqli_real_escape_string($dbc,$v)."', ";
		}
		$sql = substr($sql,0,-2).')';
	}else{
		$sql = "UPDATE ".$cid."_employees SET ";
		foreach($_REQUEST as $k=>$v){
			$sql .= $k."='".mysqli_real_escape_string($dbc,$v)."', ";
		}
		$sql = substr($sql,0,-2);
		$sql .= " WHERE emp_id='".$_REQUEST['emp_id']."'";
	}
	//var_dump($sql);
	//exit;
	
	ob_clean();
	if($dbc->query($sql)){
		updateEmployeesForPayroll($cid);
		echo 'success';
	}else{
		echo mysqli_error($dbc);
	}
	
	
