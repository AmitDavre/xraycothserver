<?

	if(session_id()==''){session_start();}
	ob_start();
	include("../../dbconnect/db_connect.php");
	include(DIR."files/functions.php");
	//var_dump($_REQUEST); exit;
	
	$dbc->query("UPDATE ".$cid."_employees SET allow_login = '".$dbc->real_escape_string($_REQUEST['val'])."' WHERE emp_id = '".$_REQUEST['id']."'");
	
	$data = array();
	if($res = $dbc->query("SELECT * FROM ".$cid."_employees WHERE emp_id = '".$_REQUEST['id']."'")){
		if($row = $res->fetch_assoc()){
			$data['emp_id'] = $row['emp_id'];
			$data['username'] = $row['personal_email'];
			$data['cid'] = $cid;
			$data['name'] = $row[$_SESSION['rego']['lang'].'_name'];
			$data['firstname'] = $row['firstname'];
			$data['img'] = $row['image'];
			if(empty($data['img'])){
				$data['img'] = 'images/profile_image.jpg';
			}
		}
	}
	
	//var_dump($data); //exit;
	
	$exist = '';
	if($res = $dbx->query("SELECT access FROM rego_company_users WHERE LOWER(username) = '".strtolower($data['username'])."'")){
		$exist = $res->fetch_assoc();
	}
	//var_dump($exist['access']);
	//var_dump($data['cid']);
	//var_dump(strstr('ego0101', 'rego0101'));
	
	//$protocol = 'http://';
	//if(isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on') { $protocol = 'https://';}

	if($exist){
		if(strstr($exist['access'], $data['cid']) != false) {
			//$access = $exist['access'] .= ','.$data['cid'];
			//var_dump('add emp_id');
			ob_clean();
			if($res = $dbx->query("UPDATE rego_company_users SET 
				emp_id = '".$data['emp_id']."', 
				cid = '".$data['cid']."', 
				img = '".$data['img']."' 
				WHERE LOWER(username) = '".strtolower($data['username'])."'")){
				/*$template = getEmailTemplate('EXISTING_USER');
				$txt = $template['body'];
				$text = str_replace('{RECIPIENT}', $data['firstname'], $txt);
				$text = str_replace('{COMPANY}', $compinfo[$lang.'_compname'], $text);
				$text = str_replace('{CLICK_HERE_LINK}', $protocol.$_SERVER['SERVER_NAME'].'/mob', $text);
				$text = str_replace('{SIGNATURE}', $_SESSION['rego']['name'], $text);*/
				//var_dump($template); exit;
				echo 'success';
			}else{
				echo mysqli_error($dbx); exit;
			}
		}
	}else{
		$sql = "INSERT INTO rego_company_users (username, password, access, type, name, emp_id, cid, img, status, last) VALUES ("; 
			$sql .= "'".$dbx->real_escape_string(strtolower($data['username']))."',";
			$sql .= "'".$dbx->real_escape_string(hash('sha256', $data['username']))."',";
			$sql .= "'".$dbx->real_escape_string($data['cid'])."',";
			$sql .= "'".$dbx->real_escape_string('emp')."',";
			$sql .= "'".$dbx->real_escape_string($data['name'])."',";
			$sql .= "'".$dbx->real_escape_string($data['emp_id'])."',";
			$sql .= "'".$dbx->real_escape_string($data['cid'])."',";
			$sql .= "'".$dbx->real_escape_string($data['img'])."',";
			$sql .= "'".$dbx->real_escape_string(1)."',";
			$sql .= "'".$dbx->real_escape_string($data['cid'])."')";
		
		ob_clean();	
		if($dbx->query($sql)){
			/*$template = getEmailTemplate('NEW_COMPANY');
			$txt = $template['body'];
			$text = str_replace('{RECIPIENT}', $data['firstname'], $txt);
			$text = str_replace('{COMPANY}', $compinfo[$lang.'_compname'], $text);
			$text = str_replace('{USERNAME}', '<a href="#" style="text-decoration:none; color:#000; cursor:text">'.$data['username'].'</a>', $text);
			$text = str_replace('{PASSWORD}', '<a href="#" style="text-decoration:none; color:#000; cursor:text">'.$data['username'].'</a>', $text);
			$text = str_replace('{CLICK_HERE_LINK}', $protocol.$_SERVER['SERVER_NAME'].'/mob', $text);
			$text = str_replace('{SIGNATURE}', $_SESSION['rego']['name'], $text);*/
			//var_dump($template); exit;
			echo 'success';
		}else{
			echo mysqli_error($dbx);
		}
	}
	
	/*require DIR.'PHPMailer/PHPMailerAutoload.php';	
	$mail_subject = 'New user Login for '.$data['name'];
	$body = '<html>
					<head>
					<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
					</head>
					<body style="font-size:16px">'.nl2br($text).'</body>
				</html>';
	
	$mail = new PHPMailer;
	$mail->CharSet = 'UTF-8';
	$mail->From = 'admin@regohr.com';
	$mail->FromName = 'REGO HR Admin';
	$mail->addAddress(strtolower($data['username']), $data['name']); 
	$mail->isHTML(true);                                  
	$mail->Subject = $mail_subject;
	$mail->Body = $body;
	$mail->WordWrap = 100;
	ob_clean();
	if(!$mail->send()) {
		echo $mail->ErrorInfo;
	}else{
		echo 'success';
	}*/
	
	
	
	exit;
	
	
