<?php

	if(session_id()==''){session_start();}
	ob_start();
	include("../../dbconnect/db_connect.php");
	include(DIR.'files/arrays_'.$_SESSION['RGadmin']['lang'].'.php');
	include(DIR."admin/files/admin_functions.php");
	//var_dump($_REQUEST); exit;
	$_REQUEST['recipients'] = '';
	
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
			if(move_uploaded_file($tmp,DIR.'admin/uploads/support/'.$filename)){
				$attachments[] = ROOT.'admin/uploads/support/'.$filename;
			}
		}
	}
	//var_dump($attachments);
	//exit;
	$dba->query("UPDATE rego_support_desk SET recipients = '".$dba->real_escape_string($_REQUEST['recipients'])."', alert = '1' WHERE ticket = '".$_REQUEST['ticket']."'");

	$sql = "INSERT INTO rego_support_tickets (ticket, name, recipients, message, platform, attachments) VALUES (
		'".$dba->real_escape_string($_REQUEST['ticket'])."',
		'".$dba->real_escape_string('REGO Support')."',
		'".$dba->real_escape_string($_REQUEST['recipients'])."',
		'".$dba->real_escape_string(nl2br($_REQUEST['message']))."', 
		'".$dba->real_escape_string('REG')."', 
		'".$dba->real_escape_string(serialize($attachments))."')";
	
	if($dba->query($sql)){
		
		$res = $dba->query("SELECT applicant, subject, priority, status, email, cid FROM rego_support_desk WHERE ticket = '".$_REQUEST['ticket']."'");
		$row = $res->fetch_assoc();
		
		require DIR.'PHPMailer/PHPMailerAutoload.php';	
		
		$template = getEmailTemplate('TICKET_REPLY');
		$txt = $template['body'];
		
		$ticket = $_REQUEST['ticket'];
		$applicant = 'REGO Support';//$_SESSION['admin']['name'];
		$email = $_SESSION['RGadmin']['email'];
		$subject = $row['subject'];
		$priority = $sd_prior[$row['priority']][0];
		$status = $sd_status[$row['status']][0];
		$signature = 'REGO Support';//$_SESSION['admin']['name'];
		$link = '<a href="'.ROOT.'admin/index.php?mn=91&id='.$ticket.'">Ticket '.$ticket.'</a>';
		
		include(DIR.'include/support_mail.php');	
		
		echo 'success';
	}else{
		echo mysqli_error($dba);
	}




