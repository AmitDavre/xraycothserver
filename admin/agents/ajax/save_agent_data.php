<?
	
	if(session_id()==''){session_start(); ob_start();}
	include("../../dbconnect/db_connect.php");
	include(DIR.'admin/files/admin_functions.php');
	//var_dump($_FILES); //exit;
	//var_dump($_REQUEST); exit;
	
	//$password = $_REQUEST['password'];
	
	if(isset($_REQUEST['password'])){$_REQUEST['password'] = hash('sha256', $_REQUEST['password']);}
	if(isset($_REQUEST['img_data']) && strlen($_REQUEST['img_data']) < 20){unset($_REQUEST['img_data']);}
	
	if(isset($_REQUEST['img_data'])){
		$uploadmap = DIR.'admin/uploads/';
		$filename = $uploadmap.'agent_'.str_replace(' ', '', $_REQUEST['agent_id']).'.jpg';
		$db_filename = 'uploads/agent_'.str_replace(' ', '', $_REQUEST['agent_id']).'.jpg';
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
	
	$agent_path = DIR.'admin/uploads/agents/';
	if (!file_exists($agent_path)) {
		mkdir($agent_path, 0755, true);
	}
	if(!empty($_FILES['certificate_attach']['tmp_name'])){
		$extension = pathinfo($_FILES['certificate_attach']['name'], PATHINFO_EXTENSION);		
		$filename = $_FILES['certificate_attach']['name'];
		$file = $agent_path. $_FILES['certificate_attach']['name'];
		$baseName = pathinfo($_FILES['certificate_attach']['name'], PATHINFO_FILENAME );
		$counter = 1;				
		while(file_exists($file)) {
			 $filename = $baseName.'('.$counter.').'.$extension;
			 $file = $agent_path.$baseName.'('.$counter.').'.$extension;
			 $counter++;
		};
		if(move_uploaded_file($_FILES['certificate_attach']['tmp_name'], $file)){
			$_REQUEST['certificate'] = $filename;
		}
	}
	if(!empty($_FILES['agreement_attach']['tmp_name'])){
		$extension = pathinfo($_FILES['agreement_attach']['name'], PATHINFO_EXTENSION);		
		$filename = $_FILES['agreement_attach']['name'];
		$file = $agent_path. $_FILES['agreement_attach']['name'];
		$baseName = pathinfo($_FILES['agreement_attach']['name'], PATHINFO_FILENAME );
		$counter = 1;				
		while(file_exists($file)) {
			 $filename = $baseName.'('.$counter.').'.$extension;
			 $file = $agent_path.$baseName.'('.$counter.').'.$extension;
			 $counter++;
		};
		if(move_uploaded_file($_FILES['agreement_attach']['tmp_name'], $file)){
			$_REQUEST['agreement'] = $filename;
		}
	}
	if(!empty($_FILES['idcard_attach']['tmp_name'])){
		$extension = pathinfo($_FILES['idcard_attach']['name'], PATHINFO_EXTENSION);		
		$filename = $_FILES['idcard_attach']['name'];
		$file = $agent_path. $_FILES['idcard_attach']['name'];
		$baseName = pathinfo($_FILES['idcard_attach']['name'], PATHINFO_FILENAME );
		$counter = 1;				
		while(file_exists($file)) {
			 $filename = $baseName.'('.$counter.').'.$extension;
			 $file = $agent_path.$baseName.'('.$counter.').'.$extension;
			 $counter++;
		};
		if(move_uploaded_file($_FILES['idcard_attach']['tmp_name'], $file)){
			$_REQUEST['idcard'] = $filename;
		}
	}
	//var_dump($_REQUEST); exit;
	
	foreach($_REQUEST as $k=>$v){
		$fields[] = $k;
	}
	$sql = "INSERT INTO rego_agents (";
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
		echo 'success';
		//writeToLogfile($_SESSION['xhr']['cid'], 'action', 'Update / Add '.$types[$_REQUEST['user_type']].' user : '.$_REQUEST['name'].' ('.$_REQUEST['user_id'].')');
		
		if(isset($_REQUEST['xxxpassword'])){
			// notify new user ---------------------------------------------------------------------------------
			require DIR.'PHPMailer/PHPMailerAutoload.php';	
			
			$template = getEmailTemplate('NEW_USER');
			$txt = $template['body'];
			
			$link = '<a href="'.AROOT.'index.php">'.AROOT.'index.php</a>';
			$text = str_replace('{RECIPIENT}', $_REQUEST['name'], $txt);
			$text = str_replace('{USERNAME}', $_REQUEST['email'], $text);
			$text = str_replace('{PASSWORD}', $password, $text);
			$text = str_replace('{CLICK_HERE_LINK}', $link, $text);
			$text = str_replace('{SIGNATURE}', $_SESSION['RGadmin']['name'], $text);
		
			$body = '<html>
							<head>
							<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
							</head>
							<body>'.nl2br($text).'</body>
						</html>';
			
			$mail = new PHPMailer;
			$mail->CharSet = 'UTF-8';
			$mail->From = $_SESSION['RGadmin']['email'];
			$mail->FromName = $_SESSION['RGadmin']['name'];
			$mail->addAddress($_REQUEST['email'], $_REQUEST['name']); 
			$mail->isHTML(true);                                  
			$mail->Subject = 'New system user';
			$mail->Body = $body;
			$mail->WordWrap = 100;
			if(!$mail->send()) {
				echo $mail->ErrorInfo;
			}
		}
		
	}else{
		echo mysqli_error($dba);
	}














