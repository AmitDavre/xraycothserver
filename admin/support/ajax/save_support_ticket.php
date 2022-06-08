<?php

	if(session_id()==''){session_start();}
	ob_start();
	include("../../dbconnect/db_connect.php");
	include(DIR.'files/arrays_'.$_SESSION['RGadmin']['lang'].'.php');
	include(DIR.'admin/files/admin_functions.php');
	$_REQUEST['ticket'] = date('dmYHis');
	
	//var_dump($_FILES); //exit;
	//var_dump($_REQUEST); //exit;
	
	$recipients = getXrayUsers('all');
	//var_dump($recipients); //exit;
	$recip = explode(',', $_REQUEST['recipients']);
	//var_dump($recip); //exit;
	$db_recip = '';
	foreach($recip as $v){ 
		$db_recip .= $recipients[$v]['name'].', ';
		$email_recip[] = $recipients[$v];
	}
	$db_recip = substr($db_recip, 0, -2);
	//var_dump($db_recip); //exit;
	//var_dump($email_recip); exit;

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
	
	$sql = "INSERT INTO rego_support_desk (ticket, cid, customer, applicant, recipients, phone, email, type, priority, lang, subject) VALUES (
		'".$dba->real_escape_string($_REQUEST['ticket'])."',
		'".$dba->real_escape_string($_SESSION['SDadmin'])."',
		'".$dba->real_escape_string($_REQUEST['customer'])."',
		'".$dba->real_escape_string($_REQUEST['name'])."',
		'".$dba->real_escape_string($_REQUEST['recipients'])."',
		'".$dba->real_escape_string($_REQUEST['phone'])."',
		'".$dba->real_escape_string($_REQUEST['email'])."',
		'".$dba->real_escape_string($_REQUEST['type'])."',
		'".$dba->real_escape_string($_REQUEST['priority'])."',
		'".$dba->real_escape_string($lang)."',
		'".$dba->real_escape_string($_REQUEST['subject'])."')";
	if(!$dba->query($sql)){
		var_dump(mysqli_error($dba));
	}
	
	$sql = "INSERT INTO rego_support_tickets (ticket, name, recipients, message, platform, attachments) VALUES (
		'".$dba->real_escape_string($_REQUEST['ticket'])."',
		'".$dba->real_escape_string($_SESSION['admin']['name'])."',
		'".$dba->real_escape_string($db_recip)."',
		'".$dba->real_escape_string(nl2br($_REQUEST['message']))."', 
		'".$dba->real_escape_string('REG')."', 
		'".$dba->real_escape_string(serialize($attachments))."')";
	if($dba->query($sql)){
	
		require DIR.'PHPMailer/PHPMailerAutoload.php';	
		
		$template = getEmailTemplate('TICKET_NEW');
		$txt = $template['body'];
		//var_dump($cid); //exit;
		//var_dump($template); exit;
		$ticket = $_REQUEST['ticket'];
		$applicant = $_SESSION['admin']['name'];
		$email = $_SESSION['admin']['email'];
		$subject = $_REQUEST['subject'];
		$priority = $sd_prior[$_REQUEST['priority']][0];
		$status = $sd_status[0][0];
		$signature = 'Support team';
		$link = '<a href="'.ROOT.'admin/index.php?mn=96&id='.$ticket.'">Ticket '.$ticket.'</a>';
		include(DIR.'inc/support_mail.php');	
		
		$template = getEmailTemplate('TICKET_NOTIFY');
		$txt = $template['body'];
		$customer = strtoupper($_REQUEST['cid']).' - '.$_REQUEST['customer'];
		$applicant = $_SESSION['admin']['name'];
		$email = $_SESSION['admin']['email'];
		$signature = $_SESSION['admin']['name'];
		$link = '<a href="'.ROOT.'admin/index.php?mn=91&id='.$ticket.'">Ticket '.$ticket.'</a>';
		include(DIR.'inc/support_notify_mail.php');	
		
		ob_clean();
		echo 'success';
	}else{
		echo mysqli_error($dba);
	}

	

