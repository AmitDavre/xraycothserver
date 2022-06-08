<?
	if(session_id()==''){session_start();}
	ob_start();
	include("../../dbconnect/db_connect.php");
	include(DIR.'files/functions.php');
	//var_dump($_REQUEST); exit;

	if(isset($_REQUEST['img_data']) && strlen($_REQUEST['img_data']) < 20){unset($_REQUEST['img_data']);}
	if(isset($_REQUEST['img_data'])){
		$uploadmap = '../../'.$cid.'/';
		if (!file_exists($uploadmap)) {
			mkdir($uploadmap, 0777, true);
		}
		$filename = $uploadmap.'user_'.str_replace(' ', '', $_REQUEST['user_id']).'.jpg';
		$db_filename = $cid.'/user_'.str_replace(' ', '', $_REQUEST['user_id']).'.jpg';
		$img_data = utf8_decode($_REQUEST['img_data']);
		$base64img = str_replace('data:image/png;base64,', '', $img_data);
		$data = base64_decode($base64img);
		$source = imagecreatefromstring($data);
		$imageSave = imagejpeg($source,$filename,80);
		imagedestroy($source);
		if(!$imageSave){
			$err_msg .= '<p>Error</p>';
			//var_dump($err_msg);
		}
		unset($_REQUEST['img_data']);
	}
	//var_dump($_REQUEST); exit;
	//var_dump($_REQUEST['img']);
	
	$sql = "UPDATE rego_company_users SET img = '".$db_filename."' WHERE username = '".$_REQUEST['username']."'";

	//echo $sql;
	//exit;
		
	if($dbx->query($sql)){
		//$err_msg = '<div class="msg_success">'.$lng['updateemployeesuccess'].'</div>';
		$err_msg = 'ok';
		//writeToLogfile($_SESSION['xhr']['cid'], 'action', 'Update / Add '.$types[$_REQUEST['user_type']].' user : '.$_REQUEST['name'].' ('.$_REQUEST['user_id'].')');
		// notify XRAY from changes ---------------------------------------------------------------------------------------------------------
		/*require '../PHPMailer/PHPMailerAutoload.php';	
		
		$mail_subject = 'Employee register from  '.strtoupper($cid).' - '.$compinfo['th_compname'].' has been updated.';
		$from = $info_email;
		$from_name = $compname;
		$body = '<html>
				<head>
				<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
				</head>
				<body>Employee '.$_REQUEST['emp_id'].' : '.$_REQUEST['firstname'].' '.$_REQUEST['lastname'].' ('.$_REQUEST['eng_name'].') has been updated on '.date('d-m-Y @ H:i').'</body>
			</html>';
		$mailto = $info_email;
		$nameto = "X-RAY HR";
		
		$mail = new PHPMailer;
		$mail->CharSet = 'UTF-8';
		$mail->From = $from;
		$mail->FromName = $compname;
		$mail->addAddress($mailto, $nameto); 
		//$mail->addReplyTo($_SESSION['xray']['email'], $_SESSION['xray']['name']);
		$mail->isHTML(true);                                  
		$mail->Subject = $mail_subject;
		$mail->Body = $body;
		$mail->WordWrap = 100;*/
		//echo $body;
		/*if(!$mail->send()) {
			//echo $mail->ErrorInfo;
		}*/
	}else{
		//$err_msg = '<div class="msg_error">'.$lng['Error'].': '.mysqli_error($dbc).'</div>';
		$err_msg = mysqli_error($dbx);
	}
	echo $err_msg;
	exit;
	
?>














