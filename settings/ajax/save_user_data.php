<?

	if(session_id()==''){session_start();}
	ob_start();
	include("../../dbconnect/db_connect.php");
	include(DIR.'files/functions.php');
	$_REQUEST['firstname'] = '';
	$_REQUEST['lastname'] = '';
	//var_dump($_REQUEST); exit;
	
	$_REQUEST['username'] = preg_replace('/\s+/', '', strtolower($_REQUEST['username']));
	
	$data = array();
	if($res = $dbc->query("SELECT emp_id, image, firstname, lastname, en_name, th_name FROM ".$cid."_employees WHERE LOWER(personal_email) = '".$_REQUEST['username']."'")){
		if($row = $res->fetch_assoc()){
			$img = $row['image'];
			if(empty($img)){
				$img = 'images/profile_image.jpg';
			}
			$_REQUEST['emp_id'] = $row['emp_id'];
			$_REQUEST['img'] = $img;
			$_REQUEST['firstname'] = $row['firstname'];
			$_REQUEST['lastname'] = $row['lastname'];
			$_REQUEST['name'] = $row[$lang.'_name'];
		}
	}
	//var_dump($_REQUEST); //exit;
	
	if(isset($_REQUEST['img_data']) && strlen($_REQUEST['img_data']) < 20){unset($_REQUEST['img_data']);}
	if(isset($_REQUEST['img_data'])){
		$uploadmap = '../../'.$cid.'/';
		if (!file_exists($uploadmap)) {
			mkdir($uploadmap, 0777, true);
		}
		if(!empty($_REQUEST['emp_id'])){
			$avatar = $_REQUEST['emp_id'];
		}else{
			$avatar = time();
		}
		$filename = $uploadmap.'user_'.str_replace(' ', '', $avatar).'.jpg';
		$db_filename = $cid.'/user_'.str_replace(' ', '', $avatar).'.jpg';
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
		$_REQUEST['img'] = $db_filename;
		unset($_REQUEST['img_data']);
	}

	$last_id = false;
	$text = '';
	
	$a_exist = false;
	if($res = $dbx->query("SELECT * FROM rego_all_users WHERE LOWER(username) = '".$_REQUEST['username']."'")){
		$a_exist = $res->fetch_assoc();
	}else{
		var_dump(mysqli_error($dbx)); //exit;
	}
	//var_dump($a_exist); //exit;
	
	$c_exist = false;
	if($res = $dbc->query("SELECT * FROM ".$cid."_users WHERE LOWER(username) = '".$_REQUEST['username']."'")){
		$c_exist = $res->fetch_assoc();
	}else{
		var_dump(mysqli_error($dbc)); //exit;
	}
	//var_dump($c_exist); //exit;
	
	if($a_exist){
		var_dump('Exist');
		$last_id = $a_exist['id'];
		$access = $a_exist['access'];
		if(!preg_match("/{$cid}/i", $a_exist['access'])) {
			$access .= ','.$cid;
		}	
		//var_dump($access); exit;
			
		// if($res = $dbx->query("UPDATE rego_all_users SET emp_id = '".$_REQUEST['emp_id']."', emp_access = '".$cid."', img = '".$_REQUEST['img']."', access = '".$access."', last = '".$cid."', type = 'sys', sys_status = '1' WHERE LOWER(username) = '".$_REQUEST['username']."'")){
		// 	var_dump('UPDATE rego_all_users SET emp_id, emp_access, img, access, last, type, sys_status');
		// }else{
		if($res = $dbx->query("UPDATE rego_all_users SET emp_id = '".$a_exist['emp_id']."', emp_access = '".$a_exist['emp_access']."', img = '".$_REQUEST['img']."', access = '".$access."', last = '".$cid."', type = 'sys', sys_status = '1' WHERE LOWER(username) = '".$_REQUEST['username']."'")){
			var_dump('UPDATE rego_all_users SET emp_id, emp_access, img, access, last, type, sys_status');
		}else{
			var_dump(mysqli_error($dbx)); //exit;
		}
		
		if($res = $dbc->query("UPDATE ".$cid."_users SET type = 'sys' WHERE ref = '".$a_exist['id']."'")){
			var_dump('UPDATE $cid_users SET type = sys');
		}else{
			var_dump(mysqli_error($dbc)); //exit;
		}
		
		/*$template = getEmailTemplate('EXISTING_USER');
		$txt = $template['body'];
		$text = str_replace('{RECIPIENT}', $_REQUEST['firstname'], $txt);
		$text = str_replace('{COMPANY}', $compinfo[$lang.'_compname'], $text);
		$text = str_replace('{CLICK_HERE_LINK}', $protocol.$_SERVER['SERVER_NAME'], $text);
		$text = str_replace('{SIGNATURE}', $_SESSION['rego']['name'], $text);*/
		//var_dump($text); exit;
	
	}else{
		var_dump('Not exist');
		$sql = "INSERT INTO rego_all_users (firstname, lastname, username, password, access, last, sys_status, emp_id, emp_access, img, type) VALUES ("; 
			$sql .= "'".$dbx->real_escape_string($_REQUEST['firstname'])."',";
			$sql .= "'".$dbx->real_escape_string($_REQUEST['lastname'])."',";
			$sql .= "'".$dbx->real_escape_string($_REQUEST['username'])."',";
			$sql .= "'".$dbx->real_escape_string(hash('sha256', $_REQUEST['password']))."',";
			$sql .= "'".$dbx->real_escape_string($cid)."',";
			$sql .= "'".$dbx->real_escape_string($cid)."',";
			$sql .= "'".$dbx->real_escape_string(1)."',";
			$sql .= "'".$dbx->real_escape_string($_REQUEST['emp_id'])."',";
			$sql .= "'".$dbx->real_escape_string($cid)."',";
			$sql .= "'".$dbx->real_escape_string($_REQUEST['img'])."',";
			$sql .= "'".$dbx->real_escape_string($_REQUEST['type'])."')";
		//echo $sql;
		if(!$dbx->query($sql)){
			echo mysqli_error($dbx);
		}else{
			var_dump('INSERT INTO rego_all_users (firstname, lastname, username, password, access, last, sys_status, emp_id, emp_access, img, type');
			$last_id = $dbx->insert_id;
			
			$template = getEmailTemplate('NEW_COMPANY');
			$txt = $template['body'];
			$text = str_replace('{RECIPIENT}', $_REQUEST['firstname'], $txt);
			$text = str_replace('{COMPANY}', $compinfo[$lang.'_compname'], $text);
			$text = str_replace('{USERNAME}', '<a href="#" style="text-decoration:none; color:#000; cursor:text">'.$_REQUEST['username'].'</a>', $text);
			$text = str_replace('{PASSWORD}', '<a href="#" style="text-decoration:none; color:#000; cursor:text">'.$_REQUEST['password'].'</a>', $text);
			$text = str_replace('{CLICK_HERE_LINK}', $protocol.$_SERVER['SERVER_NAME'].'/hr', $text);
			$text = str_replace('{SIGNATURE}', $_SESSION['rego']['name'], $text);
			//var_dump($template); //exit;
		}
	}
	
	//var_dump($last_id);
	
	
	if(!$c_exist && $last_id){
		$sql = "INSERT INTO ".$cid."_users (ref, firstname, name, username, emp_id, type, img, status) VALUES ("; 
			$sql .= "'".$dbx->real_escape_string($last_id)."',";
			$sql .= "'".$dbx->real_escape_string($_REQUEST['firstname'])."',";
			$sql .= "'".$dbx->real_escape_string($_REQUEST['name'])."',";
			$sql .= "'".$dbx->real_escape_string($_REQUEST['username'])."',";
			$sql .= "'".$dbx->real_escape_string($_REQUEST['emp_id'])."',";
			$sql .= "'".$dbx->real_escape_string($_REQUEST['type'])."',";
			$sql .= "'".$dbx->real_escape_string($_REQUEST['img'])."',";
			$sql .= "'".$dbx->real_escape_string(1)."')";
			//echo $sql;
		if($dbc->query($sql)){
			var_dump('INSERT INTO $cid_users (ref, firstname, name, username, emp_id, type, img, status');
		}else{
			var_dump(mysqli_error($dbc)); //exit;
		}
	}else{
	
	}
	
	if($dbc->query("UPDATE ".$cid."_employees SET allow_login = '1' WHERE emp_id = '".$_REQUEST['emp_id']."'")){
		var_dump('UPDATE $cid_employees SET allow_login = 1');
	}else{
		var_dump(mysqli_error($dbc)); //exit;
	}

	if(!empty($text)){
		require DIR.'PHPMailer/PHPMailerAutoload.php';	
		$mail_subject = 'New user Login for '.$_REQUEST['name'];
		$body = '<html>
						<head>
						<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
						</head>
						<body style="font-size:16px">'.nl2br($text).'</body>
					</html>';
		
		$mail = new PHPMailer;
		$mail->CharSet = 'UTF-8';
		$mail->From = 'noreply@regohr.com';
		$mail->FromName = 'REGO HR';
		$mail->addAddress($_REQUEST['username'], $_REQUEST['name']); 
		$mail->isHTML(true);                                  
		$mail->Subject = $mail_subject;
		$mail->Body = $body;
		$mail->WordWrap = 100;
		if(!$mail->send()) {
			//echo $mail->ErrorInfo;
		}else{
			//echo 'success';
			//$err_msg .= '<i class="fa fa-check-square-o"></i>&nbsp;&nbsp;eMail send to <b>'.$_REQUEST['name'].'</b>.<br>';
		}
	}
	
	ob_clean();
	echo 'success';


