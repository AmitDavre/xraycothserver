<?php

	if(session_id()==''){session_start();}
	ob_start();
	include('../../dbconnect/db_connect.php');
	include('../../files/admin_functions.php');

	include('../invoice_pdf.php');
	
	$mpdf->Output($dir,'F');
	
	//var_dump($compinfo); 
	//var_dump($compinfo['compname_'.$lang]); 
	//var_dump($data['email']); 
	//var_dump($data['customer']); 
	//var_dump($dir); 
	
	//exit;
	
	$dba->query("UPDATE rego_invoices SET pdf_invoice = '".$dba->real_escape_string($root)."' WHERE id = '".$_REQUEST['nr']."'");
	
	require DIR.'PHPMailer/PHPMailerAutoload.php';
	
	$mail = new PHPMailer;
	$mail->CharSet = 'UTF-8';
	//$mail->addAttachment($dir);
	$mail->From = $compinfo['admin_mail'];
	$mail->FromName = $compinfo['compname_'.$lang];
	$mail->addAddress($data['email'], $data['customer']); 
	//$mail->addReplyTo($_SESSION['xray']['email'], $_SESSION['xray']['name']);
	$mail->isHTML(true);                                  
	$mail->Subject = $lng['Invoice'];
	$mail->Body = 'Lorem ipsum dolar sit amet.<br>Invoice : <a href="http://census/admin/uploads/invoices/IV202005-001.pdf">IV202005-001.pdf</a>';
	$mail->WordWrap = 100;
	//echo $body;
	if(!$mail->send()) {
		echo $mail->ErrorInfo;
	}else{
		echo 'success';
	}


