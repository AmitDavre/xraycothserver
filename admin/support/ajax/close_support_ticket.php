<?php
	
	if(session_id()==''){session_start();}
	ob_start();
	include("../../dbconnect/db_connect.php");
	include(DIR.'files/arrays_'.$_SESSION['RGadmin']['lang'].'.php');
	include(DIR."admin/files/admin_functions.php");
	//var_dump($_REQUEST); exit;
	
	$sql = "UPDATE rego_support_desk SET status = '1' WHERE ticket = '".$_REQUEST['ticket']."'";
	if($dba->query($sql)){
	
		$res = $dba->query("SELECT applicant, subject, priority, status, email, cid FROM rego_support_desk WHERE ticket = '".$_REQUEST['ticket']."'");
		$row = $res->fetch_assoc();
		
		require DIR.'PHPMailer/PHPMailerAutoload.php';	
		
		$template = getEmailTemplate('TICKET_CLOSED');
		$txt = $template['body'];
		
		$ticket = $_REQUEST['ticket'];
		$applicant = $row['applicant'];
		$email = $row['email'];
		$subject = $row['subject'];
		$priority = $sd_prior[$row['priority']][0];
		$status = $sd_status[$row['status']][0];
		$signature = 'REGO Support team';
		$link = '<a href="'.ROOT.'index.php?mn=706&id='.$ticket.'">Ticket '.$ticket.'</a>';
		
		include(DIR.'include/support_mail.php');	
		
		echo 'success';
	}else{
		echo mysqli_error($dba);
	}
