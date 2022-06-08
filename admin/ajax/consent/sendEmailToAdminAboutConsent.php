<?php
	
	if(session_id()==''){session_start();}
	ob_start();
	include("../../dbconnect/db_connect.php");
	include(DIR.'files/functions.php');


	$sql1111 = "SELECT * FROM rego_default_settings"; 
	if($res111 = $dba->query($sql1111)){
		$data1111 = $res111->fetch_assoc();

			$nonemailsss =  unserialize($data1111['non_email']);

	}



	
	require DIR.'PHPMailer/PHPMailerAutoload.php';


	foreach ($nonemailsss as $key => $value) {
		# code...
			$body = "<html>
					<head>
					<meta http-equiv='Content-Type' content='text/html; charset=utf-8' />
					</head>
					<body style='font-size:16px'>

						<p>Dear Admin,</p>
						<p>Username: ".$_SESSION['RGadmin']['username']." is not accepting our website consent <br>
						<p>Kind regards</p>
					</body></html>";


				
			$mail = new PHPMailer;
			$mail->CharSet = 'UTF-8';
			// $mail->From = $_SESSION['RGadmin']['username'];
			$mail->From = 'info@xray.co.th';
			$mail->FromName = strtoupper($client_prefix).' ผู้ดูแลระบบ (Admin)';
			// $mail->FromName = $_SESSION['RGadmin']['username'];
			$mail->addAddress($value, 'Admin');     // Add a recipient
			// $mail->addAddress('lovepreet.wartiz@gmail.com', 'Admin');     // Add a recipient
			$mail->isHTML(true);                                  
			$mail->Subject = 'Consent Details For '.$_SESSION['RGadmin']['username'].'';
			$mail->Body = $body;
			$mail->WordWrap = 100;                                
			if(!$mail->send()) {
				 ob_clean(); 
				 echo 'connection'; 
			}else{
				 ob_clean(); 
				 echo 'success'; 
			}
			echo '1';

		}
	exit;
	



?>








