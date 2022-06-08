<?php

	if(session_id()==''){session_start();}
	ob_start();
	include("../../dbconnect/db_connect.php");
	//var_dump($_FILES); //exit;
	//var_dump($_REQUEST); //exit;
	
	if(empty($_REQUEST['emp_id'])){exit;}

	$dir = DIR.$cid.'/medical/';
	$attachment = '';
	if(!empty($_FILES['attachment']['tmp_name'][0])){
		$sql = "SELECT attachments FROM ".$cid."_employee_medical WHERE id = '".$_REQUEST['id']."'";
		if($res = $dbc->query($sql)){
			$row = $res->fetch_assoc();
			if(!empty($row['attachments'])){
				$_REQUEST['attachments'] = unserialize($row['attachments']);
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
				$_REQUEST['attachments'][] = $filename;	
			}
		}
		$attachment = $_REQUEST['attachments'];
		$_REQUEST['attachments'] = serialize($_REQUEST['attachments']);
	}
	//var_dump($_REQUEST['attachments']); exit;
	
	$certificate = '';	
	if(!empty($_FILES['certificate']['tmp_name'])){
		$filename = $_FILES['certificate']['name'];	
		$baseName = pathinfo($filename, PATHINFO_FILENAME );
		$extension = pathinfo($filename, PATHINFO_EXTENSION );
		$counter = 1;				
		while(file_exists($dir.$filename)) {
			 $filename = $baseName.'('.$counter.').'.$extension;
			 $counter++;
		};
		if(move_uploaded_file($_FILES['certificate']['tmp_name'], $dir.$filename)){
			$_REQUEST['certificate'] = $filename;
			$certificate = $filename;
		}
	}
	//var_dump($_REQUEST); exit;
	
	$sql = "INSERT INTO ".$cid."_employee_medical (";
	foreach($_REQUEST as $k=>$v){
		$sql .= $k.',';
	}
	$sql = substr($sql,0,-1);
	$sql .= ") VALUES ("; 
	foreach($_REQUEST as $k=>$v){
		$sql .= "'".mysqli_real_escape_string($dbc,$v)."',";
	}
	$sql = substr($sql,0,-1);
	$sql .= ") ON DUPLICATE KEY UPDATE ";
	foreach($_REQUEST as $k=>$v){
		$sql .= $k.'=VALUES('.$k.'), ';
	}
	$sql = substr($sql,0,-2);
	echo $sql; //exit;

	ob_clean();
	if($dbc->query($sql)){
		$data['result'] = 'success';
		$data['attachment'] = $attachment;
		$data['certificate'] = $certificate;
	}else{
		$data['result'] = mysqli_error($dbc);
	}
	echo json_encode($data);
