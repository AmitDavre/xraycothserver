<?php

	if(session_id()==''){session_start();}
	ob_start();
	include("../../dbconnect/db_connect.php");
	//var_dump($_FILES); //exit;
	//var_dump($_REQUEST); //exit;
	
	if(empty($_REQUEST['emp_id'])){exit;}
	$id = $_REQUEST['emp_id'];
	unset($_REQUEST['emp_id']);

	$dir = DIR.$cid.'/medical/';
	$attachment = '';
	if(!empty($_FILES['attachment']['tmp_name'][0])){
		$sql = "SELECT med_attachments FROM ".$cid."_employees WHERE emp_id = '".$id."'";
		var_dump($sql); //exit;
		if($res = $dbc->query($sql)){
			$row = $res->fetch_assoc();
			if(!empty($row['med_attachments'])){
				$_REQUEST['med_attachments'] = unserialize($row['med_attachments']);
			}
		}
		foreach($_FILES['attachment']['tmp_name'] as $k=>$v){
			$filename = $_FILES['attachment']['name'][$k];	
			$baseName = pathinfo($filename, PATHINFO_FILENAME );
			$extension = pathinfo($filename, PATHINFO_EXTENSION );
			$counter = 1;				
			while(file_exists($dir.$filename)) {
				 $filename = $baseName.'('.$counter.').'.$extension;
				 $counter++;
			};
			if(move_uploaded_file($v,$dir.$filename)){
				//$_REQUEST['logo'] = substr($filename,3);
				$_REQUEST['med_attachments'][] = $filename;	
			}
		}
		$attachment = $_REQUEST['med_attachments'];
		$_REQUEST['med_attachments'] = serialize($_REQUEST['med_attachments']);
	}
	//var_dump($_REQUEST); exit;
	
	$sql = "UPDATE ".$cid."_employees SET ";
	foreach($_REQUEST as $k=>$v){
		$sql .= $k."='".mysqli_real_escape_string($dbc,$v)."', ";
	}
	$sql = substr($sql,0,-2);
	$sql .= " WHERE emp_id='".$id."'";
	//echo $sql; exit;	
	
	ob_clean();
	if($dbc->query($sql)){
		$data['result'] = 'success';
		$data['attachment'] = $attachment;
	}else{
		$data['result'] = mysqli_error($dbc);
	}
	echo json_encode($data);
