<?php
	if(session_id()==''){session_start();}
	ob_start();
	include('../../dbconnect/db_connect.php');
	include(DIR.'files/functions.php');
	//var_dump($_REQUEST); //exit;
	
	$new_password = generateStrongPassword();//'xxx';//
	$password = hash('sha256', $new_password); 
	$err_msg = '';
	
	$sql = "SELECT * FROM rego_all_users WHERE LOWER(username) = '".strtolower($_REQUEST['femail'])."'";
	if($res = $dbx->query($sql)){
		if($res->num_rows == 0){
			ob_clean(); echo 'suspended'; exit;
		}else{
			$row = $res->fetch_assoc();
			if($dbx->query("UPDATE rego_all_users SET password = '".$dbx->real_escape_string($password)."' WHERE LOWER(username) = '".strtolower($_REQUEST['femail'])."'")){
				require DIR.'PHPMailer/PHPMailerAutoload.php';
				$body = "<html>
						<head>
						<meta http-equiv='Content-Type' content='text/html; charset=utf-8' />
						</head>
						<body style='font-size:16px'>
							<p>เรียนคุณ ".$row['firstname'].",</p>
							<p>รหัสผ่านใหม่ของคุณคือ <b>".$new_password."</b><br>
							กรุณาเปลี่ยนรหัสผ่านของคุณในครั้งแรก</p>
							<p>ขอแสดงความนับถือ<br>".strtoupper($client_prefix)." ผู้ดูแลระบบ</p>
						<hr>
							<p>Dear ".$row['firstname'].",</p>
							<p>Your new password is <b>".$new_password."</b><br>
							Please change your password on first visit.</p>
							<p>Kind regards,<br>".strtoupper($client_prefix)." Admin</p>
						</body></html>";

				$mail = new PHPMailer;
				$mail->CharSet = 'UTF-8';
				$mail->From = $from_email;
				$mail->FromName = strtoupper($client_prefix).' ผู้ดูแลระบบ (Admin)';
				$mail->addAddress($_REQUEST['femail'], $row['name']);
				//$mail->addBCC($cinfo['imail']);
				//$mail->addReplyTo($reply_email, strtoupper($cid));
				$mail->isHTML(true);                                  
				$mail->Subject = 'ขอรหัสผ่านใหม่ (New password request)';
				$mail->Body = $body;
				$mail->WordWrap = 100;                                
				if(!$mail->send()) {
					 ob_clean(); echo 'connection'; exit;
				} else {
					 ob_clean(); echo 'success'; exit;
				}
			}
			
		}
	}
	
	
	exit;
	
	// CHECK IF EMPLOYEE
	if(empty($err_msg)){	
		$sql = "SELECT emp_id, username, personal_email, en_name, personal_phone, image, allow_login FROM ".$cid."_employees WHERE username = '".$_REQUEST['fusername']."' AND personal_email = '".$_REQUEST['femail']."'";
		if($res = $dbc->query($sql)){
			if($res->num_rows > 0){
				$row = $res->fetch_assoc();
				//var_dump($row['allow_login']); exit;
				if($row['allow_login'] != 'Y'){
					ob_clean(); echo 'allowed'; exit;
				}else{
					//var_dump('employee');
					//var_dump($new_password);
					//var_dump($row);
					if($dbc->query("UPDATE ".$cid."_employees SET password = '".$dba->real_escape_string($password)."' WHERE emp_id = '".$row['emp_id']."'")){
						require '../PHPMailer/PHPMailerAutoload.php';
						$body = "<html>
								<head>
								<style type='text/css'>
									body { font-family:Verdana, Arial; font-size:12px; color:#333; margin: 10px 20px;} 
									p {line-height:160%;}
									a:link, a:visited {color: #7D0000;text-decoration:none;}
									a:hover {color: #7D0000;text-decoration: underline;}
								</style>
								<meta http-equiv='Content-Type' content='text/html; charset=utf-8' />
								</head>
								<body>
									<p>Dear ".$row['en_name']."</p>
									<p>Your new password is <b>".$new_password."</b><br>
									Please change your password on first visit.</p>
									<p>Kind regards,<br>".
									strtoupper($cid)." Administrator</p>
								</body></html>";
		
						$mail = new PHPMailer;
						$mail->CharSet = 'UTF-8';
						$mail->From = $from_email;
						$mail->FromName = strtoupper($cid);
						$mail->addAddress($_REQUEST['femail'], $row['en_name']);     // Add a recipient
						//$mail->addBCC($cinfo['imail']);
						$mail->addReplyTo($reply_email, strtoupper($cid));
						$mail->isHTML(true);                                  
						$mail->Subject = 'New password request';
						$mail->Body = $body;
						$mail->WordWrap = 100;                                
						if(!$mail->send()) {
							 ob_clean(); echo 'connection'; exit;
						} else {
							 ob_clean(); echo 'success'; exit;
						}
					}
				}
			}
		}
		//var_dump($err_msg); exit;
		
		// CHECK IF SYSTEM OR COMPANY USER
		if(empty($err_msg)){
			$sql = "SELECT * FROM ".$cid."_users WHERE username = '".$_REQUEST['fusername']."' AND email = '".$_REQUEST['femail']."'";
			if($res = $dbc->query($sql)){
				if($res->num_rows > 0){
					$row = $res->fetch_assoc();
					if($row['status'] != 1){
						ob_clean(); echo 'suspended'; exit;
					}else{
						//var_dump('user');
						//var_dump($new_password);
						//var_dump($row); exit;
						if($dbc->query("UPDATE ".$cid."_users SET password = '".$dba->real_escape_string($password)."' WHERE user_id = '".$row['user_id']."'")){
							require '../PHPMailer/PHPMailerAutoload.php';
							$body = "<html>
									<head>
									<style type='text/css'>
										body { font-family:Verdana, Arial; font-size:12px; color:#333; margin: 10px 20px;} 
										p {line-height:160%;}
										a:link, a:visited {color: #7D0000;text-decoration:none;}
										a:hover {color: #7D0000;text-decoration: underline;}
									</style>
									<meta http-equiv='Content-Type' content='text/html; charset=utf-8' />
									</head>
									<body>
										<p>Dear ".$row['name']."</p>
										<p>Your new password is <b>".$new_password."</b><br>
										Please change your password on first visit.</p>
										<p>Kind regards,<br>".
										strtoupper($cid)." Administrator</p>
									</body></html>";
			
							$mail = new PHPMailer;
							$mail->CharSet = 'UTF-8';
							$mail->From = $from_email;
							$mail->FromName = strtoupper($cid);
							$mail->addAddress($_REQUEST['femail'], $row['name']);     // Add a recipient
							//$mail->addBCC($cinfo['imail']);
							$mail->addReplyTo($reply_email, strtoupper($cid));
							$mail->isHTML(true);                                  
							$mail->Subject = 'New password request';
							$mail->Body = $body;
							$mail->WordWrap = 100;                                
							if(!$mail->send()) {
								 ob_clean(); echo 'connection'; exit;
							} else {
								 ob_clean(); echo 'success'; exit;
							}
						}
						//echo $err_msg; exit;
					}
				}else{
					//$err_msg = '<div class="msg_alert nomargin">Wrong Username or eMail</div>';
					ob_clean(); echo 'wrong'; exit;
				}
			}else{
				ob_clean(); echo '<div class="msg_alert nomargin">Error: '.mysqli_error($dbc).'</div>'; exit;
			}
		}
	}

	
?>












