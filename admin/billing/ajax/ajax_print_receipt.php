<?php

	if(session_id()==''){session_start();}
	ob_start();
	include('../../dbconnect/db_connect.php');
	include(DIR.'files/arrays_'.$lang.'.php');

	include('../receipt_pdf.php');
	
	$mpdf->Output($dir,'F');
	
	$dba->query("UPDATE rego_invoices SET pdf_receipt = '".$dba->real_escape_string($root)."' WHERE id = '".$_REQUEST['nr']."'");
	
	require DIR.'PHPMailer/PHPMailerAutoload.php';
	
	$mail = new PHPMailer;
	$mail->CharSet = 'UTF-8';
	$mail->addAttachment($dir);
	$mail->From = $compinfo['info_mail'];
	$mail->FromName = $compinfo['compname_'.$lang];
	$mail->addAddress($data['email'], $data['customer']); 
	//$mail->addReplyTo($_SESSION['xray']['email'], $_SESSION['xray']['name']);
	$mail->isHTML(true);                                  
	$mail->Subject = $lng['Receipt / Tax Invoice'];
	$mail->Body = 'Body';
	$mail->WordWrap = 100;
	//echo $body;
	if(!$mail->send()) {
		echo $mail->ErrorInfo;
	}else{
		echo 'success';
	}


