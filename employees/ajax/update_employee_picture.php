<?php

	if(session_id()==''){session_start();}
	ob_start();
	include("../../dbconnect/db_connect.php");
	
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
	}
	//var_dump($_REQUEST); exit;

	$sql = "UPDATE ".$cid."_employees SET image = '".mysqli_real_escape_string($dbc, $db_filename)."' WHERE emp_id = '".$_REQUEST['emp_id']."'";
	//exit;
	
	ob_clean();
	if($dbc->query($sql)){
		echo 'success';
	}else{
		echo mysqli_error($dbc);
	}
	
	
