<?
	if(session_id()==''){session_start(); ob_start();}
	include("../../dbconnect/db_connect.php");


	$sql102 = "SELECT * FROM rego_company_settings WHERE id = '1'";

	if($res102 = $dba->query($sql102)){
		if($res102->num_rows > 0){
			if($row102 = $res102->fetch_assoc())
				{
					$admin_mail_value = $row102['admin_mail'];  // SELECTED TEAMS STORED IN SESSION 
						
				}
		}
	}


	$SixDigitRandomNumber = mt_rand(100000,999999);


	$sql_reset = "UPDATE rego_users SET 
		authenticated_code = '".$dba->real_escape_string($SixDigitRandomNumber)."' WHERE username = '".$_SESSION['RGadmin']['username']."'";
	$dba->query($sql_reset);


	require '../../../PHPMailer/PHPMailerAutoload.php';
	$body = "<html>
			<head>
			<meta http-equiv='Content-Type' content='text/html; charset=utf-8' />
			</head>
			<body style='font-size:16px'>
				<p>Dear ".$_SESSION['RGadmin']['name'].",</p>
				<p>Your 6 digit authentication code is <b>".$SixDigitRandomNumber."</b><br>
				</p>
				<p>Kind regards,<br>".strtoupper($client_prefix)." Admin</p>
			</body></html>";

	$mail = new PHPMailer;
	$mail->CharSet = 'UTF-8';
	$mail->From = $admin_mail_value;
	$mail->FromName = strtoupper($client_prefix).' ผู้ดูแลระบบ (Admin)';
	$mail->addAddress($_SESSION['RGadmin']['username']);     // Add a recipient
	$mail->isHTML(true);                                  
	$mail->Subject = '6 Digit Code';
	$mail->Body = $body;
	$mail->WordWrap = 100;                                
	$mail->send();



	echo 'success';



	


