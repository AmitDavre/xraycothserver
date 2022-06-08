<?php

	if(session_id()==''){session_start();}
	ob_start();
	include("../../dbconnect/db_connect.php");
	include(DIR.'files/arrays_'.$_SESSION['rego']['lang'].'.php');
	include(DIR.'files/functions.php');
	$_REQUEST['ticket'] = date('dmYHis');
	//var_dump($_REQUEST); //exit;

	$templ = 'rego_default_email_templates';
	
	$attachments = array();
	foreach($_FILES['attachments']['tmp_name'] as $k=>$v){
		if(!empty($v)){
			$tmp = $v;
			$filename = $_FILES['attachments']['name'][$k];	
			$baseName = pathinfo($filename, PATHINFO_FILENAME );
			$extension = pathinfo($filename, PATHINFO_EXTENSION );
			$counter = 1;				
			while(file_exists(DIR.'admin/uploads/support/'.$filename)) {
				 $filename = $baseName.'('.$counter.').'.$extension;
				 $counter++;
			};
			if(move_uploaded_file($tmp, DIR.'admin/uploads/support/'.$filename)){
				$attachments[] = ROOT.'admin/uploads/support/'.$filename;
			}
		}
	}
	//var_dump($attachments);
	//exit;
		
	$sql = "INSERT INTO rego_support_desk (ticket, cid, customer, applicant, recipients, email, type, priority, lang, subject) VALUES (
		'".$dbx->real_escape_string($_REQUEST['ticket'])."',
		'".$dbx->real_escape_string($_REQUEST['cid'])."',
		'".$dbx->real_escape_string($_REQUEST['customer'])."',
		'".$dbx->real_escape_string($_REQUEST['name'])."',
		'".$dbx->real_escape_string($_REQUEST['email'])."',
		'".$dbx->real_escape_string($_REQUEST['email'])."',
		'".$dbx->real_escape_string($_REQUEST['type'])."',
		'".$dbx->real_escape_string($_REQUEST['priority'])."',
		'".$dbx->real_escape_string($lang)."',
		'".$dbx->real_escape_string($_REQUEST['subject'])."')";
	if(!$dbx->query($sql)){
		var_dump(mysqli_error($dbx));
	}
	
	$sql = "INSERT INTO rego_support_tickets (ticket, name, recipients, message, platform, attachments) VALUES (
		'".$dbx->real_escape_string($_REQUEST['ticket'])."',
		'".$dbx->real_escape_string($_REQUEST['name'])."',
		'".$dbx->real_escape_string($_REQUEST['email'])."',
		'".$dbx->real_escape_string(nl2br($_REQUEST['message']))."', 
		'".$dbx->real_escape_string('CUS')."', 
		'".$dbx->real_escape_string(serialize($attachments))."')";
	if($dbx->query($sql)){
		
		require DIR.'PHPMailer/PHPMailerAutoload.php';	
		
		
		// SEND MAIL TO REGO SUPPORT ////////////////////////////////////////////////////
		$template = getEmailTemplate('TICKET_NOTIFY');
		$txt = $template['body'];
		$ticket = $_REQUEST['ticket'];
		$subject = $_REQUEST['subject'];
		$customer = strtoupper(substr($_REQUEST['cid'],0,4).' '.substr($_REQUEST['cid'],4)).' - '.$_REQUEST['customer'];
		$priority = $sd_prior[$_REQUEST['priority']][0];
		$status = $sd_status[0][0];
		$link = '<a href="'.ROOT.'index.php?mn=706&id='.$ticket.'">Ticket '.$ticket.'</a>';
		include(DIR.'include/support_notify_mail.php');	
		
		// SEND MAIL TO APPLICANT //////////////////////////////////////////////////////////////////
		$template = getEmailTemplate('TICKET_NEW');
		$txt = $template['body'];
		$link = '<a href="'.ROOT.'admin/index.php?mn=91&id='.$ticket.'">Ticket '.$ticket.'</a>';
		include(DIR.'include/support_mail.php');	
		
		ob_clean();
		echo 'success';
	}else{
		echo mysqli_error($dbx);
	}

	

