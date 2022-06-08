<?php

	if(session_id()==''){session_start();}
	ob_start();
	include("../../dbconnect/db_connect.php");
	//var_dump($_FILES); //exit;
	//var_dump($_REQUEST); exit;

	$_REQUEST['personal_email'] = strtolower($_REQUEST['personal_email']);
	$_REQUEST['th_name'] = $_REQUEST['firstname'].' '.$_REQUEST['lastname'];
	$_REQUEST['idcard_nr'] = str_replace('-','',$_REQUEST['idcard_nr']);
	
	if(isset($_REQUEST['img_data']) && strlen($_REQUEST['img_data']) > 20){
		$uploadmap = '../../uploads/demo/';
		if (!file_exists($uploadmap)) {
			mkdir($uploadmap, 0755, true);
		}
		$filename = $uploadmap.str_replace(' ', '', $_REQUEST['emp_id']).'.jpg';
		$db_filename =  ROOT.'admin/uploads/demo/'.str_replace(' ', '', $_REQUEST['emp_id']).'.jpg';
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
	$id = $_REQUEST['emp_id'];
	unset($_REQUEST['emp_id']);
	
	$sql = "UPDATE demo_employees SET ";
	foreach($_REQUEST as $k=>$v){
		$sql .= $k."='".mysqli_real_escape_string($dba,$v)."', ";
	}
	$sql = substr($sql,0,-2);
	$sql .= " WHERE emp_id='".$id."'";
	//echo $sql; exit;
	
	ob_clean();
	if($dba->query($sql)){
		//updateEmployeesForPayroll($cid);
		echo 'success';
	}else{
		echo mysqli_error($dba);
	}
	
	
