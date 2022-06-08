<?php

	$text = str_replace('{RECIPIENT}', $_SESSION['rego']['name'], $txt);
	$text = str_replace('{TICKET_ID}', $ticket, $text);
	$text = str_replace('{SUBJECT}', $subject, $text);
	$text = str_replace('{PRIORITY}', $priority, $text);
	$text = str_replace('{STATUS}', $status, $text);
	$text = str_replace('{CLICK_HERE_LINK}', $link, $text);
	$text = str_replace('{SIGNATURE}', 'REHO HR Support team', $text);

	$body = '<html>
					<head>
					<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
					</head>
					<body>'.nl2br($text).'</body>
				</html>';
	
	$mail = new PHPMailer;
	$mail->CharSet = 'UTF-8';
	$mail->From = $support_email;
	$mail->FromName = 'REGO HR Support';
	$mail->addAddress($_SESSION['rego']['username'], $_SESSION['rego']['name']); 
	//$mail->addReplyTo($_SESSION['xray']['email'], $_SESSION['xray']['name']);
	$mail->isHTML(true);                                  
	$mail->Subject = 'Ticket [# '.$ticket.'] '.$subject;
	$mail->Body = $body;
	$mail->WordWrap = 100;
	//echo $body;
	if(!$mail->send()) {
		echo $mail->ErrorInfo;
	}
