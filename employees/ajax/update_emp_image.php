<?php

	if(session_id()==''){session_start();}
	ob_start();
	include("../../dbconnect/db_connect.php");
	//include(DIR.'files/functions.php');
	//var_dump($_REQUEST); //exit;
	
	if(empty($_REQUEST['emp_id'])){echo 'empty'; exit;}
	
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
		
		$sql = "UPDATE ".$cid."_employees 
			SET image = '".mysqli_real_escape_string($dbc,$db_filename)."' 
			WHERE emp_id = '".$_REQUEST['emp_id']."'";
		//var_dump($sql);
		
		if($dbc->query($sql)){
			ob_clean();
			echo 'success';
		}else{
			ob_clean();
			echo mysqli_error($dbc);
		}
	
	}
	exit;
