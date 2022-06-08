<?

	if(session_id()==''){session_start();}
	ob_start();
	include("../../dbconnect/db_connect.php");
	include(DIR."files/functions.php");
	//var_dump($_REQUEST); //exit;
	
	$username = preg_replace('/\s+/', '', strtolower($_REQUEST['username']));
	$data = array();
	if($res = $dbc->query("SELECT * FROM ".$cid."_employees WHERE emp_id = '".$_REQUEST['id']."'")){
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
	var_dump($data); //exit;
	
	$password = generateStrongPassword(8, false);//randomPassword();
	$password = $username;
	
	$a_exist = false;
	if($res = $dbx->query("SELECT * FROM rego_all_users WHERE LOWER(username) = '".$username."'")){
		$a_exist = $res->fetch_assoc();
	}else{
		var_dump(mysqli_error($dbx)); //exit;
	}
	//var_dump($a_exist); //exit;
	
	if($_REQUEST['val'] == '1'){
		$send_mail = false;
		$last_id = false;
		if(!$a_exist){
			$sql = "INSERT INTO rego_all_users (firstname, lastname, username, password, access, last, type, emp_id, emp_access, emp_status, img) VALUES ("; 
				$sql .= "'".$dbx->real_escape_string($data['firstname'])."',";
				$sql .= "'".$dbx->real_escape_string($data['lastname'])."',";
				$sql .= "'".$dbx->real_escape_string($username)."',";
				$sql .= "'".$dbx->real_escape_string(hash('sha256', $password))."',";
				$sql .= "'".$dbx->real_escape_string($cid)."',";
				$sql .= "'".$dbx->real_escape_string($cid)."',";
				$sql .= "'".$dbx->real_escape_string('emp')."',";
				$sql .= "'".$dbx->real_escape_string($data['emp_id'])."',";
				$sql .= "'".$dbx->real_escape_string($cid)."',";
				$sql .= "'".$dbx->real_escape_string(1)."',";
				$sql .= "'".$dbx->real_escape_string($data['img'])."')";
			if($dbx->query($sql)){
				$send_mail = true;
				$last_id = $dbx->insert_id;
				var_dump('INSERT INTO rego_all_users (firstname, lastname, username, password, access, last, type, emp_id, emp_access, emp_status, img)');
			}else{
				var_dump(mysqli_error($dbx)); //exit;
			}

		}else{

			if($a_exist['sys_status'] == 1){
				$addcondition = '';
			}else{
				$passreset = hash('sha256', $username);
				$addcondition = "password = '".$passreset."',";
			}

			$access = $a_exist['access'];
			if(!preg_match("/{$cid}/i", $a_exist['access'])) {
				$access = $a_exist['access'] .= ','.$cid;
			}
			$last_id = $a_exist['id'];
			if($dbx->query("UPDATE rego_all_users SET ".$addcondition." access = '".$access."', emp_id = '".$data['emp_id']."', img = '".$data['img']."', emp_access = '".$cid."', emp_status = '1' WHERE id = '".$a_exist['id']."'")){
				var_dump('UPDATE rego_all_users SET access, emp_id, img, emp_access, emp_status');
			}else{
				var_dump(mysqli_error($dbx)); //exit;
			}

			//====== Start Send Email to Employee user =====
			$template = getEmailTemplate('EXISTING_EMPUSER');
			$txt = $template['body'];
			$text = str_replace('{RECIPIENT}', $data['name'], $txt);
			$text = str_replace('{COMPANY}', $compinfo[$lang.'_compname'], $text);
			$text = str_replace('{CLICK_HERE_LINK}', '<a href="'.$protocol.$_SERVER['SERVER_NAME'].'/hr/mob" style="text-decoration:underline">'.$protocol.$_SERVER['SERVER_NAME'].'/hr/mob</a>', $text);
			$text = str_replace('{REPLY_EMAIL}', '<a href="mailto:'.$comp_settings['comp_email'].'" style="text-decoration:underline">'.$comp_settings['comp_email'].'</a>', $text);

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
			$mail->addAddress($username, $data['firstname']);
			$mail->isHTML(true);                                  
			$mail->Subject = $template['sub'];
			$mail->Body = $body;
			$mail->WordWrap = 100;                                
			if(!$mail->send()) { }
			//====== End Send Email to Employee user =====

		} //end else part
		
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
		
		if($dbc->query("UPDATE ".$cid."_employees SET allow_login = '1' WHERE emp_id = '".$_REQUEST['id']."'")){
			var_dump('UPDATE $cid_employees SET allow_login = 1');
		}else{
			var_dump(mysqli_error($dbc)); //exit;
		}
		
		if($send_mail){
			$template = getEmailTemplate('NEW_EMP_USER');
			$txt = $template['body'];
			//if(empty($template['sub'])){$template['sub'] = 'ขอรหัสผ่านใหม่ (New employee user)';}
			//var_dump($template); exit;
			$text = str_replace('{RECIPIENT}', $data['name'], $txt);
			$text = str_replace('{COMPANY}', $compinfo[$lang.'_compname'], $text);
			$text = str_replace('{USERNAME}', '<b>'.$username.'</b>', $text);
			$text = str_replace('{PASSWORD}', '<b>'.$password.'</b>', $text);
			$text = str_replace('{CLICK_HERE_LINK}', '<a href="'.$protocol.$_SERVER['SERVER_NAME'].'/hr" style="text-decoration:underline">'.$protocol.$_SERVER['SERVER_NAME'].'/hr</a>', $text);
			//var_dump($template); //exit;
			//var_dump($text); //exit;

			require DIR.'PHPMailer/PHPMailerAutoload.php';	
			$link = "<a href='".ROOT."hr/mob'>".ROOT."hr/mob</a>";
			//var_dump($link); exit;
			$body = "<html>
					<head>
					<meta http-equiv='Content-Type' content='text/html; charset=utf-8' />
					</head>
					<body>".nl2br($text)."</body></html>";
			//echo($body); exit;
			$mail = new PHPMailer;
			$mail->CharSet = 'UTF-8';
			$mail->From = $from_email;
			$mail->FromName = strtoupper($client_prefix).' ผู้ดูแลระบบ (Admin)';
			$mail->addAddress($username, $data['firstname']);
			$mail->isHTML(true);                                  
			$mail->Subject = $template['sub'];
			$mail->Body = $body;
			$mail->WordWrap = 100;                                
			if(!$mail->send()) {
				 //ob_clean(); echo 'connection'; exit;
			}
		}
		ob_clean();
		echo 'success';
		exit; 
	}
	
	
	
						/*<p>เรียนคุณ ".$row['firstname'].",</p>
						<p>เราได้สร้างบัญชีเพื่อให้คุณเข้าสู่ระบบบนแพลตฟอร์มพนักงานมือถือของคุณ</p>
						<p>ชื่อผู้ใช้ : <b>".$username."</b><br>
						รหัสผ่านใหม่ของคุณคือ <b>".$password."</b><br>
						กรุณาเปลี่ยนรหัสผ่านของคุณในครั้งแรก</p>
						<p>ไปที่ลิงค์นี้เพื่อเข้าสู่ระบบ : ".$link."</p>
						<p>ขอแสดงความนับถือ<br>".strtoupper($client_prefix)." ผู้ดูแลระบบ</p>
					<hr>
						<p>Dear ".$data['firstname'].",</p>
						<p>We have created an account for you to login on our mobile employee platform</p>
						<p>Username : <b>".$username."</b><br>
						Password : <b>".$password."</b><br>
						Please change your password on first visit.</p>
						<p>Follow this link to login : ".$link."</p>
						<p>Kind regards,<br>".strtoupper($client_prefix)." Admin</p>*/
	
	
	
	
	
	
	
	
	
	
	
	
	
	if($_REQUEST['val'] == '0'){
		$c_exist = false;
		if($res = $dbc->query("SELECT * FROM ".$cid."_users WHERE LOWER(username) = '".$username."'")){
			$c_exist = $res->fetch_assoc();
		}else{
			var_dump(mysqli_error($dbc)); //exit;
		}
		//var_dump($c_exist); //exit;
		
		if(!$c_exist){
			if($dbc->query("UPDATE ".$cid."_employees SET allow_login = '0' WHERE emp_id = '".$_REQUEST['id']."'")){
				var_dump('UPDATE $cid_employees SET allow_login = 0'); 
			}else{
				var_dump(mysqli_error($dbc)); //exit;
			}
			ob_clean();
			echo 'success';
			exit; 
		}else{
			if($c_exist['type'] == 'emp'){
				if($dbc->query("DELETE FROM ".$cid."_users WHERE id = '".$c_exist['id']."'")){
					var_dump('DELETE FROM $cid_users');
				}else{
					var_dump(mysqli_error($dbc)); //exit;
				}
			}
		}
		
		if($a_exist){
			if($a_exist['type'] == 'emp'){
				if($dbx->query("DELETE FROM rego_all_users WHERE id = '".$c_exist['ref']."'")){
					var_dump('DELETE FROM rego_all_users');
				}else{
					var_dump(mysqli_error($dbx)); //exit;
				}
			}else{

				$access = str_replace($cid.',', '', $a_exist['access']);
				// update rego_all_users
				if($dbx->query("UPDATE rego_all_users SET access = '".$access."', emp_status = '0' WHERE id = '".$c_exist['ref']."'")){
					//ob_clean();
					var_dump('UPDATE rego_all_users SET emp_status = 0'); 
					//echo 'updated'; 
					//exit;
				}else{
					var_dump(mysqli_error($dbx)); //exit;
				}
			}
		}
		
		if($dbc->query("UPDATE ".$cid."_employees SET allow_login = '0' WHERE emp_id = '".$_REQUEST['id']."'")){
			var_dump('UPDATE $cid_employees SET allow_login = 0');
			//echo 'success';
			//exit; 
		}else{
			var_dump(mysqli_error($dbc)); //exit;
		}
		
		ob_clean();
		echo 'success';
		exit; 
	}
	







