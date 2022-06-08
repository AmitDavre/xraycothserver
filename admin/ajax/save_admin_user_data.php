<?
	if(session_id()==''){session_start(); ob_start();}
	include("../dbconnect/db_connect.php");
	include(DIR.'admin/files/admin_functions.php');
	//var_dump($_REQUEST); exit;
	
	$password = $_REQUEST['password'];
	
	if(isset($_REQUEST['password'])){$_REQUEST['password'] = hash('sha256', $_REQUEST['password']);}
	if(isset($_REQUEST['img_data']) && strlen($_REQUEST['img_data']) < 20){unset($_REQUEST['img_data']);}
	if(isset($_REQUEST['img_data'])){
		$uploadmap = DIR.'admin/uploads/';
		$filename = $uploadmap.'admin_'.str_replace(' ', '', $_REQUEST['user_id']).'.jpg';
		$db_filename = AROOT.'uploads/admin_'.str_replace(' ', '', $_REQUEST['user_id']).'.jpg';
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
		$_REQUEST['img'] = $db_filename;
	}
	
	foreach($_REQUEST as $k=>$v){
		$fields[] = $k;
	}
	$sql = "INSERT INTO rego_users (";
	foreach($fields as $v){
		$sql .= $v.',';
	}
	$sql = substr($sql,0,-1);
	$sql .= ") VALUES ("; 
	foreach($_REQUEST as $k=>$v){
		$sql .= "'".$dba->real_escape_string($v)."',";
	}
	$sql = substr($sql,0,-1);
	$sql .= ") ON DUPLICATE KEY UPDATE ";
	foreach($fields as $v){
		$sql .= $v.' = VALUES('.$v.'),';
	}
	$sql = substr($sql,0,-1);
	//echo $sql;
	//exit;
		
	if($dba->query($sql)){

		$err_msg = 'ok';
		//writeToLogfile($_SESSION['xhr']['cid'], 'action', 'Update / Add '.$types[$_REQUEST['user_type']].' user : '.$_REQUEST['name'].' ('.$_REQUEST['user_id'].')');
		
		if(isset($_REQUEST['password'])){
			// notify new user ---------------------------------------------------------------------------------
			require DIR.'PHPMailer/PHPMailerAutoload.php';	
			
			$template = getEmailTemplate('NEW_USER');
			$txt = $template['body'];
			
			$link = '<a href="'.AROOT.'index.php">'.AROOT.'index.php</a>';
			$text = str_replace('{RECIPIENT}', $_REQUEST['name'], $txt);
			$text = str_replace('{USERNAME}', $_REQUEST['username'], $text);
			$text = str_replace('{PASSWORD}', $password, $text);
			$text = str_replace('{CLICK_HERE_LINK}', $link, $text);
		
			$body = '<html>
							<head>
							<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
							</head>
							<body>'.nl2br($text).'</body>
						</html>';
			
			$mail = new PHPMailer;
			$mail->CharSet = 'UTF-8';
			$mail->From = $from_email;
			$mail->FromName = strtoupper($client_prefix).' ผู้ดูแลระบบ (Admin)';
			$mail->addAddress($_REQUEST['email'], $_REQUEST['name']); 
			$mail->isHTML(true);                                  
			$mail->Subject = $template['subject'];
			$mail->Body = $body;
			$mail->WordWrap = 100;
			if(!$mail->send()) {
				echo $mail->ErrorInfo;
			}
		}
		
	}else{
		$err_msg = '<br><br>error'.mysqli_error($dba);
	}
	echo $err_msg;
	exit;
	
?>














