<? 
	if(session_id()==''){session_start();}
	ob_start();
	include("../dbconnect/db_connect.php");
	//var_dump($compinfo['comp_name']); exit;
	
	if(empty($_REQUEST['subject']) || empty($_REQUEST['comment'])){
		ob_clean(); 
		echo 'empty'; 
		exit;
	}

	$body = "<html>
				<head>
				<style type='text/css'>
					body, th, td {font-family:Calibri,Verdana; white-space:nowrap} 
				</style>
				<meta http-equiv='Content-Type' content='text/html; charset=utf-8' />
				</head>
				<body>
				<table style='width:100%'>
					<tr>
						<th align='right'>Customer ID : </th>
						<td>".strtoupper(substr($_SESSION['rego']['cid'],0,4).' '.substr($_SESSION['rego']['cid'],4))."</td>
					</tr><tr>
						<th align='right'>Company name : </th>
						<td>".$compinfo[$lang.'_compname']."</td>
					</tr><tr>
						<th align='right'>Name : </th>
						<td>".$_REQUEST['name']."</td>
					</tr><tr>
						<th align='right'>Phone : </th>
						<td style='width:90%'>".$_REQUEST['phone']."</td>
					</tr><tr>
						<th align='right'>eMail : </th>
						<td><a href='mailto:".$_REQUEST['email']."'>".$_REQUEST['email']."</a></td>
					</tr><tr>
						<th align='right' style='vertical-align:top'>Message : </th>
						<td style='line-height:100%;'>".nl2br($_REQUEST['comment'])."</td>
					</tr>
				</table>
				</body>
				</html>";
	
	require(DIR.'PHPMailer/PHPMailerAutoload.php');
	
	$mail = new PHPMailer;
	$mail->CharSet = "UTF-8";
	$mail->From = $_REQUEST['email'];
	$mail->FromName = $_REQUEST['name'];
	$mail->addAddress($support_email, 'REGO HR Admin');
	$mail->isHTML(true);                                  
	$mail->Subject = $_REQUEST['subject'];
	$mail->Body = $body;
	if(isset($_FILES['contactAttach']) && $_FILES['contactAttach']['error'] == 0) {
		 $mail->AddAttachment($_FILES['contactAttach']['tmp_name'], $_FILES['contactAttach']['name']);
	}	
	$mail->WordWrap = 100;   
	
	ob_clean();                           
	if(!$mail->send()) {
		 echo $mail->ErrorInfo;
	}else{
		 echo 'success';
	}
?>