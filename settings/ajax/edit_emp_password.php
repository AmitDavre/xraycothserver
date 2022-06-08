<?php
	if(session_id()==''){session_start();}
	ob_start();
	include("../../dbconnect/db_connect.php");
	include(DIR."files/functions.php");

	$username = preg_replace('/\s+/', '', strtolower($_REQUEST['uname']));
	$data = array();
	if($res = $dbc->query("SELECT * FROM ".$cid."_employees WHERE emp_id = '".$_REQUEST['EmpID']."'")){
		if($row = $res->fetch_assoc()){
			$data['emp_id'] = $row['emp_id'];
			$data['name'] = $row[$lang.'_name'];
			$data['firstname'] = $row['firstname'];
			$data['lastname'] = $row['lastname'];
			$data['img'] = $row['image'];
			if(empty($data['img'])){
				$data['img'] = 'images/profile_image.jpg';
			}
		}
	}

	// var_dump($data);
	//  exit;
	
	if(empty($_REQUEST['npass'])){
		ob_clean();	echo 'empty'; exit;
	}
	if(strlen($_REQUEST['npass'])<8){
		ob_clean();	echo 'short'; exit;
	}
	/*if($_REQUEST['npass'] !== $_REQUEST['rpass']){
		ob_clean();	echo 'same'; exit;
	}*/

	//$pass1 = hash('sha256', $_REQUEST['opass']); 
	$pass3 = hash('sha256', $_REQUEST['npass']);
	//$pass5 = hash('sha256', 'www');

	//echo "SELECT id FROM rego_all_users WHERE LOWER(username) = '".strtolower($_REQUEST['uname'])."'";
	$res = $dbx->query("SELECT id FROM rego_all_users WHERE LOWER(username) = '".strtolower($_REQUEST['uname'])."'");
	if($res->num_rows > 0){
		$sql = "UPDATE rego_all_users SET password = '".$pass3."' WHERE LOWER(username) = '".strtolower($_REQUEST['uname'])."'";
		if($dbx->query($sql)){

			//====== Start Send Email to Employee user =====
			$template = getEmailTemplate('NEW_COMPANY');
			$txt = $template['body'];
			$text = str_replace('{RECIPIENT}', 'Employee', $txt);
			$text = str_replace('{COMPANY}', $compinfo[$lang.'_compname'], $text);
			$text = str_replace('{USERNAME}', $_REQUEST['uname'], $text);
			$text = str_replace('{PASSWORD}', $_REQUEST['npass'], $text);
			$text = str_replace('{CLICK_HERE_LINK}', '<a href="'.$protocol.$_SERVER['SERVER_NAME'].'/hr/mob" style="text-decoration:underline">'.$protocol.$_SERVER['SERVER_NAME'].'/hr/mob</a>', $text);
			//$text = str_replace('{REPLY_EMAIL}', '<a href="mailto:'.$comp_settings['comp_email'].'" style="text-decoration:underline">'.$comp_settings['comp_email'].'</a>', $text);

			require DIR.'PHPMailer/PHPMailerAutoload.php';	
			$link = "<a href='".ROOT."hr/mob'>".ROOT."hr/mob</a>";
			$body = "<html>
					<head>
					<meta http-equiv='Content-Type' content='text/html; charset=utf-8' />
					</head>
					<body>".nl2br($text)."</body></html>";
			$mail = new PHPMailer;
			$mail->CharSet = 'UTF-8';
			$mail->From = $from_email;
			$mail->FromName = strtoupper($client_prefix).' ผู้ดูแลระบบ (Admin)';
			$mail->addAddress($_REQUEST['uname'], 'Emp');
			$mail->isHTML(true);                                  
			$mail->Subject = 'New Login Credential';
			$mail->Body = $body;
			$mail->WordWrap = 100;                                
			if(!$mail->send()) { }
			//====== End Send Email to Employee user =====


			ob_clean();	
			echo 'success';
		}else{
			ob_clean();	
			echo mysqli_error($dbx);
		}

	}else{ //when user enter 1st time...

		$sql = "INSERT INTO rego_all_users (firstname, lastname, username, password, access, last, type, emp_id, emp_access, emp_status, img) VALUES ("; 
			$sql .= "'".$dbx->real_escape_string($data['firstname'])."',";
			$sql .= "'".$dbx->real_escape_string($data['lastname'])."',";
			$sql .= "'".$dbx->real_escape_string($username)."',";
			$sql .= "'".$dbx->real_escape_string($pass3)."',";
			$sql .= "'".$dbx->real_escape_string($cid)."',";
			$sql .= "'".$dbx->real_escape_string($cid)."',";
			$sql .= "'".$dbx->real_escape_string('emp')."',";
			$sql .= "'".$dbx->real_escape_string($data['emp_id'])."',";
			$sql .= "'".$dbx->real_escape_string($cid)."',";
			$sql .= "'".$dbx->real_escape_string(1)."',";
			$sql .= "'".$dbx->real_escape_string($data['img'])."')";
		if($dbx->query($sql)){
			$last_id = $dbx->insert_id;
		
		}else{
			var_dump(mysqli_error($dbx)); //exit;
		}


		$c_exist = false;
		if($res = $dbc->query("SELECT * FROM ".$cid."_users WHERE LOWER(username) = '".$username."'")){
			$c_exist = $res->fetch_assoc();
		}else{
			var_dump(mysqli_error($dbc)); //exit;
		}
		//var_dump($c_exist); //exit;
		
		if(!$c_exist && $last_id){
			$sql = "INSERT INTO ".$cid."_users (ref, firstname, name, username, emp_id, type, img, status) VALUES ("; 
				$sql .= "'".$dbx->real_escape_string($last_id)."',";
				$sql .= "'".$dbx->real_escape_string($data['firstname'])."',";
				$sql .= "'".$dbx->real_escape_string($data['name'])."',";
				$sql .= "'".$dbx->real_escape_string($username)."',";
				$sql .= "'".$dbx->real_escape_string($data['emp_id'])."',";
				$sql .= "'".$dbx->real_escape_string('emp')."',";
				$sql .= "'".$dbx->real_escape_string($data['img'])."',";
				$sql .= "'".$dbx->real_escape_string(1)."')";
				//echo $sql;
			if($dbc->query($sql)){
				var_dump('INSERT INTO $cid_users (ref, firstname, name, username, emp_id, type, img, status');
			}else{
				var_dump(mysqli_error($dbc)); //exit;
			}
		}
		
		if($dbc->query("UPDATE ".$cid."_employees SET allow_login = '1' WHERE emp_id = '".$_REQUEST['EmpID']."'")){
			var_dump('UPDATE $cid_employees SET allow_login = 1');
		}else{
			var_dump(mysqli_error($dbc)); //exit;
		}


		//====== Start Send Email to Employee user =====
			$template = getEmailTemplate('NEW_COMPANY');
			$txt = $template['body'];
			$text = str_replace('{RECIPIENT}', $data['firstname'], $txt);
			$text = str_replace('{COMPANY}', $compinfo[$lang.'_compname'], $text);
			$text = str_replace('{USERNAME}', $_REQUEST['uname'], $text);
			$text = str_replace('{PASSWORD}', $_REQUEST['npass'], $text);
			$text = str_replace('{CLICK_HERE_LINK}', '<a href="'.$protocol.$_SERVER['SERVER_NAME'].'/hr/mob" style="text-decoration:underline">'.$protocol.$_SERVER['SERVER_NAME'].'/hr/mob</a>', $text);
			//$text = str_replace('{REPLY_EMAIL}', '<a href="mailto:'.$comp_settings['comp_email'].'" style="text-decoration:underline">'.$comp_settings['comp_email'].'</a>', $text);

			require DIR.'PHPMailer/PHPMailerAutoload.php';	
			$link = "<a href='".ROOT."hr/mob'>".ROOT."hr/mob</a>";
			$body = "<html>
					<head>
					<meta http-equiv='Content-Type' content='text/html; charset=utf-8' />
					</head>
					<body>".nl2br($text)."</body></html>";
			$mail = new PHPMailer;
			$mail->CharSet = 'UTF-8';
			$mail->From = $from_email;
			$mail->FromName = strtoupper($client_prefix).' ผู้ดูแลระบบ (Admin)';
			$mail->addAddress($_REQUEST['uname'], 'Emp');
			$mail->isHTML(true);                                  
			$mail->Subject = 'New Login Credential';
			$mail->Body = $body;
			$mail->WordWrap = 100;                                
			if(!$mail->send()) { }
		//====== End Send Email to Employee user =====

		ob_clean();	
		echo 'success';
	}
?>