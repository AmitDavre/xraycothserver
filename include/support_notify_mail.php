<?php

	$text = str_replace('{CUSTOMER}', $customer, $txt);
	$text = str_replace('{RECIPIENT}', 'REGO Support,', $text);
	$text = str_replace('{APPLICANT}', $_SESSION['rego']['name'], $text);
	$text = str_replace('{TICKET_ID}', $ticket, $text);
	$text = str_replace('{SUBJECT}', $subject, $text);
	$text = str_replace('{PRIORITY}', $priority, $text);
	$text = str_replace('{STATUS}', $status, $text);
	$text = str_replace('{CLICK_HERE_LINK}', $link, $text);
	$text = str_replace('{SIGNATURE}', $signature, $text);
	$body = '<html>
					<head>
					<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
					</head>
					<body>'.nl2br($text).'</body>
				</html>';
	
	$mail = new PHPMailer;
	$mail->CharSet = 'UTF-8';
	$mail->From = $_SESSION['rego']['username'];
	$mail->FromName = $_SESSION['rego']['name'];
	$mail->addAddress($support_email, 'REGO HR Support');
	//$mail->addReplyTo($noreply_email, $noreply_email);
	$mail->isHTML(true);                                  
	$mail->Subject = 'Ticket [# '.$ticket.'] '.$subject;
	$mail->Body = $body;
	$mail->WordWrap = 100;
	//echo $body;
	if(!$mail->send()) {
		echo $mail->ErrorInfo;
	}
