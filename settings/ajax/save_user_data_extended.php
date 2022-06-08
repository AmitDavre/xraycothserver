<?

	if(session_id()==''){session_start();}
	ob_start();
	include("../../dbconnect/db_connect.php");
	include(DIR.'files/functions.php');
	//var_dump($_REQUEST); exit;

	$_REQUEST['email'] = preg_replace('/\s+/', '', strtolower($_REQUEST['email']));
	
	$res = $dbx->query("SELECT * FROM rego_company_users WHERE LOWER(username) = '".$_REQUEST['email']."'");
	$exist = $res->fetch_assoc();
	if($exist){
		if(strpos($exist['access'], $_REQUEST['cid']) === false) {
			$access = $exist['access'] .= ','.$_REQUEST['cid'];
			if($res = $dbx->query("UPDATE rego_company_users SET 
				access = '$access', 
				module = 'all',
				type = 'sys',
				cid = '".$cid."',
				last = '".$_REQUEST['cid']."' 
				WHERE LOWER(username) = '".strtolower($_REQUEST['email'])."'")){
					$template = getEmailTemplate('EXISTING_USER');
					$txt = $template['body'];
					$text = str_replace('{RECIPIENT}', $_REQUEST['firstname'], $txt);
					$text = str_replace('{COMPANY}', $_REQUEST[$lang.'_compname'], $text);
					$text = str_replace('{CLICK_HERE_LINK}', $protocol.$_SERVER['SERVER_NAME'], $text);
					$text = str_replace('{SIGNATURE}', $_SESSION['rego']['name'], $text);
					//var_dump($template); exit;
					//echo 'success';
					//exit;
			}else{
				echo mysqli_error($dbx);
			}
		}
	}else{
		//var_dump($_REQUEST);
		$sql = "INSERT INTO rego_company_users (username, password, access, module, type, cid, name, emp_id, img, status, visit, last) VALUES ("; 
			$sql .= "'".$dbx->real_escape_string($_REQUEST['email'])."',";
			$sql .= "'".$dbx->real_escape_string(hash('sha256', $_REQUEST['password']))."',";
			$sql .= "'".$dbx->real_escape_string($_REQUEST['cid'])."',";
			$sql .= "'".$dbx->real_escape_string('all')."',";
			$sql .= "'".$dbx->real_escape_string('sys')."',";
			$sql .= "'".$dbx->real_escape_string($_REQUEST['cid'])."',";
			$sql .= "'".$dbx->real_escape_string($_REQUEST['name'])."',";
			$sql .= "'".$dbx->real_escape_string($_REQUEST['emp_id'])."',";
			$sql .= "'".$dbx->real_escape_string($_REQUEST['img'])."',";
			$sql .= "'".$dbx->real_escape_string(1)."',";
			$sql .= "'".$dbx->real_escape_string(1)."',";
			$sql .= "'".$dbx->real_escape_string($_REQUEST['cid'])."')";
		
		//ob_clean();	
		if($dbx->query($sql)){
			
			$pid = $_REQUEST['cid'].'_'.$_REQUEST['email'];
			$sql = "INSERT INTO ".$cid."_users_permissions (id, type, modules, teams) VALUES ("; 
				$sql .= "'".$dbx->real_escape_string($pid)."',";
				$sql .= "'".$dbx->real_escape_string('sys')."',";
				$sql .= "'".$dbx->real_escape_string('all')."',";
				$sql .= "'".$dbx->real_escape_string('all')."')
				ON DUPLICATE KEY UPDATE 
				type = VALUES(type),
				modules = VALUES(modules),
				teams = VALUES(teams)";
			//echo $sql;
			//ob_clean();	
			if(!$dbx->query($sql)){
				//echo mysqli_error($dbx);
			}
			
			$template = getEmailTemplate('NEW_COMPANY');
			$txt = $template['body'];
			$text = str_replace('{RECIPIENT}', $_REQUEST['name'], $txt);
			$text = str_replace('{COMPANY}', $compinfo[$lang.'_compname'], $text);
			$text = str_replace('{USERNAME}', '<a href="#" style="text-decoration:none; color:#000; cursor:text">'.$_REQUEST['email'].'</a>', $text);
			$text = str_replace('{PASSWORD}', '<a href="#" style="text-decoration:none; color:#000; cursor:text">'.$_REQUEST['password'].'</a>', $text);
			$text = str_replace('{CLICK_HERE_LINK}', $protocol.$_SERVER['SERVER_NAME'], $text);
			$text = str_replace('{SIGNATURE}', $_SESSION['rego']['name'], $text);
			//var_dump($template); exit;
			//echo 'success';
		}else{
			echo mysqli_error($dbx);
		}
	}
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
	$mail->From = 'admin@regohr.com';
	$mail->FromName = 'REGO HR Admin';
	$mail->addAddress($_REQUEST['email'], $_REQUEST['name']); 
	$mail->isHTML(true);                                  
	$mail->Subject = $mail_subject;
	$mail->Body = $body;
	$mail->WordWrap = 100;
	if(!$mail->send()) {
		echo $mail->ErrorInfo;
	}else{
		echo 'success';
		//$err_msg .= '<i class="fa fa-check-square-o"></i>&nbsp;&nbsp;eMail send to <b>'.$_REQUEST['name'].'</b>.<br>';
	}
		
		//writeToLogfile($_SESSION['rego']['cid'], 'action', 'Update / Add '.$types[$_REQUEST['user_type']].' user : '.$_REQUEST['name'].' ('.$_REQUEST['user_id'].')');
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














