<?php
	if(session_id()==''){session_start(); ob_start();}
	//$cid = $_SESSION['xhr']['cid'];
	include("../../files/functions.php");
	include(DIR.'files/functions.php');

	//var_dump($_REQUEST); exit;
	
	$lng = getLangVariables($_SESSION['xhr']['lang']);
	
	$dbname = $cid.'_emp_history';//.$_SESSION['xhr']['cur_year'];
	$_REQUEST['effdate'] = date('Y-m-d', strtotime($_REQUEST['effdate']));
	
	$sql = "INSERT INTO `".$dbname."` (id, emp_id, ".$_REQUEST['field'].", effdate) VALUES (
		'".$dbc->real_escape_string($_REQUEST['emp_id'].'_sal_'.strtotime($_REQUEST['effdate']))."',
		'".$dbc->real_escape_string($_REQUEST['emp_id'])."',
		'".$dbc->real_escape_string($_REQUEST['val'])."',
		'".$dbc->real_escape_string($_REQUEST['effdate'])."')";

	//echo $sql;
	//exit;
		
	if($dbc->query($sql)){
		$err_msg = '<div class="msg_success">'.$lng['Employee updated successfuly'].'</div>';
		//writeToLogfile($_SESSION['xhr']['cid'], 'action', 'Update / Add employee : '.$_REQUEST['en_name'].' ('.$_REQUEST['emp_id'].')');

		// notify XRAY from changes ---------------------------------------------------------------------------------------------------------
		/*require '../PHPMailer/PHPMailerAutoload.php';	
		
		$mail_subject = 'Employee register from  '.strtoupper($cid).' - '.$compinfo['th_compname'].' has been updated.';
		$from = $info_email;
		$from_name = $compname;
		$body = '<html>
				<head>
				<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
				</head>
				<body>Employee '.$_REQUEST['emp_id'].' : '.$_REQUEST['firstname'].' '.$_REQUEST['lastname'].' ('.$_REQUEST['eng_name'].') has been updated on '.date('d-m-Y @ H:i').'</body>
			</html>';
		$mailto = $info_email;
		$nameto = "X-RAY HR";
		
		$mail = new PHPMailer;
		$mail->CharSet = 'UTF-8';
		$mail->From = $from;
		$mail->FromName = $compname;
		$mail->addAddress($mailto, $nameto); 
		//$mail->addReplyTo($_SESSION['xray']['email'], $_SESSION['xray']['name']);
		$mail->isHTML(true);                                  
		$mail->Subject = $mail_subject;
		$mail->Body = $body;
		$mail->WordWrap = 100;*/
		//echo $body;
		/*if(!$mail->send()) {
			//echo $mail->ErrorInfo;
		}*/
		
	}else{
		$err_msg = '<div class="msg_error">'.$lng['Error'].': '.mysqli_error($dbc).'</div>';
		
	}
	echo $err_msg;
	exit;
	
?>