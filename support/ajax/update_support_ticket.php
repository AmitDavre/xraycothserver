<?php

	if(session_id()==''){session_start();}
	ob_start();
	include("../../dbconnect/db_connect.php");
	include(DIR.'files/arrays_'.$_SESSION['rego']['lang'].'.php');
	include(DIR."files/functions.php");
	//$users = getSCUsers('all');
	//var_dump($support_email); exit;
	
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

	$sql = "INSERT INTO rego_support_tickets (ticket, name, recipients, message, platform, attachments) VALUES (
		'".$dbx->real_escape_string($_REQUEST['ticket'])."',
		'".$dbx->real_escape_string($_SESSION['rego']['name'])."',
		'".$dbx->real_escape_string($_REQUEST['recipients'])."',
		'".$dbx->real_escape_string(nl2br($_REQUEST['message']))."', 
		'".$dbx->real_escape_string('CUS')."', 
		'".$dbx->real_escape_string(serialize($attachments))."')";
	if($dbx->query($sql)){
		
		$res = $dbx->query("SELECT * FROM rego_support_desk  WHERE ticket = '".$_REQUEST["ticket"]."'");
		$row = $res->fetch_assoc();
		//var_dump($row);
		
		require DIR.'PHPMailer/PHPMailerAutoload.php';	
		
		$template = getEmailTemplate('TICKET_REPLY');
		$txt = $template['body'];

		$ticket = $_REQUEST['ticket'];
		$applicant = 'Support team';//$_REQUEST['name'];
		$subject = $row['subject'];
		$priority = $sd_prior[$row['priority']][0];
		$status = $sd_status[0][0];
		$customer = strtoupper($row['cid']).' - '.$row['customer'];
		$email = $_SESSION['rego']['username'];
		$signature = $_SESSION['rego']['name'];
		$link = '<a href="'.ROOT.'admin/index.php?mn=91&id='.$ticket.'">Ticket '.$ticket.'</a>';
		//$link = '<a href="'.ROOT.'admin/index.php?mn=91&id='.$ticket.'">Ticket '.$ticket.'</a>';
		include(DIR.'include/support_notify_mail.php');	
		ob_clean();
		echo 'success';
	}else{
		echo mysqli_error($dbx);
	}
		
