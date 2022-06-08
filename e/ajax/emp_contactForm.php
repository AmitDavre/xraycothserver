<? 
	if(session_id()==''){session_start(); ob_start();}
	include("../../dbconnect/db_connect.php");
	include(DIR.'files/arrays_'.$_SESSION['rego']['lang'].'.php');

	var_dump($_FILES); exit;
	
	$filename = '';
	if(!empty($_FILES['contactAttach']['tmp_name'])){
		$ext = pathinfo($_FILES['contactAttach']['name'], PATHINFO_EXTENSION);		
		$filename = $dir.$_SESSION['xhr']['id'].'_'.uniqid().'.'.$ext;
		if(move_uploaded_file($_FILES['contactAttach']['tmp_name'],$filename)){
			//$_REQUEST['attach'] = $cid.'/leave/'.$filename;
			//$fname = substr($filename,3);
			//$dbc->query("UPDATE `".$cid."_leaves_".$_SESSION['xhr']['cur_year']."` SET 
				//attach = '".$dba->real_escape_string($cid.'/leave/'.$filename)."' 
				//WHERE id = '".$_REQUEST['recid']."'"); 
			//echo mysqli_error($dba);
		}
	}
	//var_dump($_REQUEST); exit;
	//var_dump($compinfo['comp_email']); exit;

	//if(empty($_REQUEST['flater']) || empty($_REQUEST['pandora']) || empty($_REQUEST['zeggenschap'])){ob_clean(); echo 'empty'; exit;}
	//$mail_subject = 'Message from employee '.$_SESSION['xhr']['id'].' : '.$_SESSION['xhr']['name'];
	//$mailto = $xray_info['imail'];//getInfomail($db);
	//$mailto = "info@yrayhr.com";
	//$compname = $_SESSION['xhr']['cid'].' - '.$compinfo[$_SESSION['xhr']['lang'].'_compname'];//getCompname($db, $_SESSION['xray_lng']);
	//$spam = false;
	require DIR.'PHPMailer/PHPMailerAutoload.php';
	/*if(!empty($_REQUEST['name']) || !empty($_REQUEST['email']) || !empty($_REQUEST['url']) || !empty($_REQUEST['message'])){$spam = true;}
	$tmp = strtolower($_REQUEST['zeggenschap']);
	if(preg_match('~(http|url=|bcc:|cc:|content-type)~', $tmp)){$spam = true;}
	$tmp = strtolower($_REQUEST['flater']);
	if(preg_match('~(http|url=|bcc:|cc:|content-type)~', $tmp)){$spam = true;}
	$tmp = strtolower($_REQUEST['pandora']);
	if(preg_match('~(http|url=|bcc:|cc:|content-type)~', $tmp)){$spam = true;}
	$tmp = strtolower($_REQUEST['phone']);
	if(preg_match('~(http|url=|bcc:|cc:|content-type)~', $tmp)){$spam = true;}
	if($_REQUEST['flater'] == $_REQUEST['phone']){$spam = true;}*/
	//echo 'ok'; exit;
	//if(!$spam){	
		$body = "<html>
					<head>
					<style type='text/css'>
						body, th, td {font-family:Calibri,Verdana;} 
						table th {white-space:nowrap;} 
					</style>
					<meta http-equiv='Content-Type' content='text/html; charset=utf-8' />
					</head>
					<body>
					<table width='100%'>
						<tr>
							<th align='right'>Employee ID : </th>
							<td style='width:95%'>".$_SESSION['rego']['emp_id']."</td>
						</tr><tr>
							<th align='right'>".$lng['Name']." : </th>
							<td>".$_REQUEST['name']."</td>
						</tr><tr>
							<th align='right'>".$lng['email']." : </th>
							<td><a href='mailto:".$_REQUEST['email']."'>".$_REQUEST['email']."</a></td>
						</tr><tr>
							<th align='right'>".$lng['Subject']." : </th>
							<td>".$_REQUEST['subject']."</td>
						</tr><tr>
							<th align='right' style='vertical-align:top'>Message : </th>
							<td style='line-height:100%;'>".nl2br($_REQUEST['comment'])."</td>
						</tr><tr>
							<th align='right' style='vertical-align:top'>".$lng['Attachment']." : </th>
							<td>";
					if(!empty($filename)){
						$body .= "<a href='".$filename."' target='_blank'>".$lng['Attached document']."</a></td>";
					}
					$body .= "</td>
						</tr>
					</table>
					</body>
					</html>";
					
		//echo $body;
		$mail = new PHPMailer;
		$mail->CharSet = "UTF-8";
		$mail->From = $_REQUEST['email'];//$mailto;
		$mail->FromName = $_REQUEST['name'];//$lng['Contactform'];
		$mail->addAddress($compinfo['comp_email'], $compinfo['en_compname']);
		$mail->addReplyTo($_REQUEST['email'], $_REQUEST['name']);
		$mail->isHTML(true);                                  
		$mail->Subject = $_REQUEST['subject'];
		$mail->Body = $body;
		$mail->WordWrap = 100;   
		
		//ob_clean();                           
		if(!$mail->send()) {
			 echo 'err'; // $lng['No connection to the mailserver try again later'];
			 //$err .= '<br>Mailer Error: ' . $mail->ErrorInfo;
		} else {
			 echo 'ok';// $lng['Thank you for interest respond soon as possible'];
		}
	//}else{
		//echo 'spam';// $lng['Spam detected'];
	//}
?>