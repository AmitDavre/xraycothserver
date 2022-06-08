<?php
	if(session_id()==''){session_start();}
	ob_start();
	include('../dbconnect/db_connect.php');
	include('../../files/functions.php');
	//var_dump($_REQUEST); exit;
	$new_password = generateStrongPassword();
	$password = hash('sha256', $new_password); 
	$err_msg = '';
	
	$sql = "SELECT * FROM rego_users WHERE LOWER(username) = '".strtolower($_REQUEST['forgot_username'])."' AND status = '1'";
	if($res = $dba->query($sql)){
		if($res->num_rows == 0){
			ob_clean(); echo 'suspended'; exit;
		}else{
			$row = $res->fetch_assoc();
			$id = $row['user_id'];
			if($dba->query("UPDATE rego_users SET password = '".$password."' WHERE user_id = '".$row['user_id']."'")){
				//echo 'saved new password';
				require DIR.'PHPMailer/PHPMailerAutoload.php';
				$body = "<html>
						<head>
						<meta http-equiv='Content-Type' content='text/html; charset=utf-8' />
						</head>
						<body style='font-size:16px'>
							<p>เรียนคุณ ".$row['name'].",</p>
							<p>รหัสผ่านใหม่ของคุณคือ <b>".$new_password."</b><br>
							กรุณาเปลี่ยนรหัสผ่านของคุณในครั้งแรก</p>
							<p>ขอแสดงความนับถือ<br>".strtoupper($client_prefix)." ผู้ดูแลระบบ</p>
						<hr>
							<p>Dear ".$row['name'].",</p>
							<p>Your new password is <b>".$new_password."</b><br>
							Please change your password on first visit.</p>
							<p>Kind regards,<br>".strtoupper($client_prefix)." Admin</p>
						</body></html>";
				
				$mail = new PHPMailer;
				$mail->CharSet = 'UTF-8';
				$mail->From = $from_email;
				$mail->FromName = strtoupper($client_prefix).' ผู้ดูแลระบบ (Admin)';
				$mail->addAddress($row['email'], $row['en_name']);     // Add a recipient
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
	}else{
		var_dump(mysqli_error($dba));
	}
	exit;
	











