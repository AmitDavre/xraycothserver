<?php
	
	if(session_id()==''){session_start();}
	ob_start();
	include('../db_connect.php');
	include(DIR.'files/functions_rego.php');
	//var_dump($_REQUEST); exit;
	//$lng = getLangVariables($lang);
	$new_password = generateStrongPassword();
	$password = hash('sha256', $new_password); 
	$err_msg = '';
	
	$sql = "SELECT * FROM rego_agents WHERE email = '".$_REQUEST['femail']."' AND status = '1'";
	if($res = $dbx->query($sql)){
		if($res->num_rows == 0){
			ob_clean(); echo 'suspended'; exit;
		}else{
			$row = $res->fetch_assoc();
			if($row['email'] != $_REQUEST['femail']){
				ob_clean(); echo 'email'; exit;
			}else{
				$id = $row['id'];
				if($dbx->query("UPDATE rego_agents SET password = '".$password."' WHERE id = '".$id."'")){
					//echo $password; exit;
					require DIR.'PHPMailer/PHPMailerAutoload.php';
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
								<p>Kind regards,<br>
								REGO HR Team</p>
								</body></html>";
								
								/*<br><hr>
								<p>เรียนคุณ ".$row['name']."</p>
								<p>รหัสผ่านใหม่ของคุณคือ <b>".$new_password."</b><br>
								กรุณาเปลี่ยนรหัสผ่านของคุณในครั้งแรก.</p>
								<p>ขอแสดงความนับถือ,<br>
								 ผู้ดูแลระบบ ".strtoupper($cid)."</p>*/
	
					$mail = new PHPMailer;
					$mail->CharSet = 'UTF-8';
					$mail->From = $from_email;
					$mail->FromName = strtoupper($cid);
					$mail->addAddress($_REQUEST['femail'], $row['name']);     // Add a recipient
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
	}else{
		var_dump(mysqli_error($dba));
	}
	exit;
	











