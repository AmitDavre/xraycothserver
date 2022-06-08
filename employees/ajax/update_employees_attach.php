<?php

	if(session_id()==''){session_start();}
	ob_start();
	include("../../dbconnect/db_connect.php");
	
	//var_dump($_FILES); exit;
	//var_dump($_REQUEST); //exit;

	if(empty($_REQUEST['emp_id'])){echo 'empty'; exit;}

	$uploadmap = DIR.$cid.'/employees/';
	if (!file_exists($uploadmap)) {
		mkdir($uploadmap, 0755, true);
	}
	
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
	$sql .= " ON DUPLICATE KEY UPDATE ";
	foreach($_REQUEST as $k=>$v){
		$sql .= $k." = VALUES(".$k."), ";
	}
	$sql = substr($sql,0,-2);
	//echo $sql;
	
	ob_clean();
	if($dbc->query($sql)){
		echo 'success';
	}else{
		echo mysqli_error($dbc);
	}









