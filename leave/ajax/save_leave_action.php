<?
	if(session_id()==''){session_start();}
	ob_start();
	include('../../dbconnect/db_connect.php');


		// GET REGO EMAIL TEMPLATES  

	$my_dbanamea = $prefix.'admin';


	$dba = new mysqli($my_database,$my_username,$my_password,$my_dbanamea);
	mysqli_set_charset($dba,"utf8");
	if($dba->connect_error) {
		echo'<p style="width:900px; margin:0 auto; margin-top:20px;" class="box_err">Error: ('.$dba->connect_errno.') '.$dba->connect_error.'<br>Please try again later or report this error to <a href="mailto:admin@regohr.com">admin@regohr.com</a></p>';
	}
	

	function getEmailTemplates($field){
		global $dba;
		global $lang;
		$data['sub'] = '';
		$data['body'] = '';
		$sql = "SELECT * FROM rego_default_email_templates WHERE name = '".$field."'";

		if($res = $dba->query($sql)){
			if($row = $res->fetch_assoc()){
				$data['sub'] = $row['subject_'.$lang];
				$data['body'] = $row['body_'.$lang];
			}
		}
		return $data;
	}

	function getLeaveTimeSettings(){
		global $dbc;
		$row = array();
		if($res = $dbc->query("SELECT * FROM ".$_SESSION['rego']['cid']."_leave_time_settings")){
			$row = $res->fetch_assoc();
		}
		return $row;
	}

		// EMPLOYEES /////////////////////////////////////////////////////////////////////
	function getSystemUsers($team){
		global $dbc;

		$explodeArr= explode(',', $team);

		foreach ($explodeArr as $key => $value) {
			// code...

			$sql = "SELECT * FROM ".$_SESSION['rego']['cid']."_users WHERE id = '".$value."'";
			if($res = $dbc->query($sql)){
				while($row = $res->fetch_assoc()){
					$users[$row['name']] = $row['username'];
				}
			}

		}


		
		return $users;
	}




		





	
	//var_dump($_REQUEST); exit;

	if($_REQUEST['action'] == 'approve'){

		$sqlx = "UPDATE ".$cid."_leaves SET 
			status = 'AP', 
			updated = '".date('d-m-Y @ H:i')."', 
			updated_by = '".$_SESSION['rego']['name']."', 
			approved = '".date('d-m-Y @ H:i')."', 
			approved_by = '".$_SESSION['rego']['name']."' 
			WHERE id = '".$_REQUEST['row_id']."'";
		
		if($dbc->query($sqlx)){
			ob_clean(); echo 'success';
			$dbc->query("UPDATE ".$cid."_leaves_data SET status = 'AP' WHERE leave_id = '".$_REQUEST['row_id']."'");

			// SEND MAIL TO THE EMPLOYEE OF WHOSE EMAIL IS APPROVED

						$sql1 = "SELECT * FROM ".$cid."_leaves WHERE id = '".$_REQUEST['row_id']."'";

			if($res1 = $dbc->query($sql1)){
				if($row1 = $res1->fetch_assoc()){
	

					$emp_id_value = $row1['emp_id'];
					$emp_leave_type = $row1['leave_type'];
					$emp_leave_type_details = unserialize($row1['details']);
					$emp_leave_reason = $row1['comment'];
					$emp_date_req = $row1['created'];
					$emp_leave_status = $row1['status'];
				}
			}

			$sql2 = "SELECT * FROM ".$cid."_employees WHERE emp_id = '".$emp_id_value."'";

			if($res2 = $dbc->query($sql2)){
				if($row2 = $res2->fetch_assoc()){
					$workEmail = $row2['personal_email'];
					$namevalue = $row2[$lang.'_name'];
					$leave_approve = $row2['leave_approve'];
					$teamvalue = $row2['teams'];
				}
			}


		// $sql21 = "SELECT * FROM ".$cid."_employees WHERE emp_id = '".$emp_id_value."'";

		// 	if($res21 = $dbc->query($sql21)){
		// 		if($row21 = $res21->fetch_assoc()){
		// 			$leave_approve1 = $row21['leave_approve'];
		// 			$teamvalue1 = $row21['teams'];
		// 		}
		// 	}


			$approve_by = getSystemUsers($leave_approve);

			$approve_by[$workEmail] = $workEmail;
			


			$leave_settings = getLeaveTimeSettings();
			$leave_types = unserialize($leave_settings['leave_types']);


			if($emp_leave_status == 'RJ')
			{
				$statusval = 'Rejected by '. $_SESSION['rego']['name'];
			} 
			else if($emp_leave_status == 'AP')
			{
				$statusval = 'Approved by '.$_SESSION['rego']['name'];
			}			
			else if($emp_leave_status == 'CA')
			{
				$statusval = 'Canceled by '.$_SESSION['rego']['name'];
			}
				

			$protocol = 'http://';
			if(isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on') { $protocol = 'https://';}		

			$template = getEmailTemplates('APPROVE_LEAVE_REQUEST');
			$txt = $template['body'];
			$text = str_replace('{EMPLOYEE_NAME}', $emp_id_value.' - '.$namevalue, $txt);
			$text = str_replace('{LEAVE_TYPE}', $leave_types[$emp_leave_type][$lang], $text);
			foreach($emp_leave_type_details as $v){
				$tmp = $v['day'];
				if($v['day'] == 'full'){$tmp = $lng['Full day'];}
				if($v['day'] == 'first'){$tmp = $lng['First half'];}
				if($v['day'] == 'second'){$tmp = $lng['Second half'];}
				 // $temps = date('d-m-Y', strtotime($v['date'])).' '.$tmp;
				 $temps .= date('d-m-Y', strtotime($v['date'])).' '.$tmp .'<br>'.' &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
		

			}


			$text = str_replace('{LEAVE_DETAILS}', $temps, $text);
			$text = str_replace('{LEAVE_REASON}', $emp_leave_reason, $text);
			$text = str_replace('{DATE_REQUEST}', $emp_date_req, $text);
			$text = str_replace('{CLICK_HERE_LINK}', 'https://regothailand.com/hr', $text);
			$text = str_replace('{RECIPIENT}', $namevalue, $text);
			$text = str_replace('{STATUS}', $statusval, $text);



			require DIR.'PHPMailer/PHPMailerAutoload.php';	

			$body = '<html>
						<head>
						<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
						</head>
						<body style="font-size:16px">'.nl2br($text).'</body>
					</html>';

			$mail = new PHPMailer;
			$mail->CharSet = 'UTF-8';
			// $mail->From = $_SESSION['rego']['username'];
			// $mail->FromName = $_SESSION['rego']['name'];
			$mail->From = $from_email;
			$mail->FromName = strtoupper($client_prefix).' ผู้ดูแลระบบ (Admin)';
			foreach($approve_by as $k=>$v){
				$mail->addAddress($v); 
			}
			// $mail->addAddress($workEmail); 
			// $mail->addReplyTo($_SESSION['rego']['username'], $_SESSION['rego']['name']);
			$mail->isHTML(true);                                  
			$mail->Subject = 'Leave request status for '.$namevalue;
			$mail->Body = $body;
			if(!empty($attachment)) {
				$mail->AddAttachment(ROOT.$attachment);
			}	
			$mail->WordWrap = 100;
			//echo $body;
			if(!$mail->send()) {
				echo $mail->ErrorInfo;
			}






		}else{
			echo mysqli_error($dbc);
		}
		exit;
	}
	
	if($_REQUEST['action'] == 'reject'){

		$sqlx = "UPDATE ".$cid."_leaves SET 
			status = 'RJ', 
			updated = '".date('d-m-Y @ H:i')."', 
			updated_by = '".$_SESSION['rego']['name']."', 
			comment = '".$_REQUEST['comment']."', 
			rejected = '".date('d-m-Y @ H:i')."', 
			rejected_by = '".$_SESSION['rego']['name']."' 
			WHERE id = '".$_REQUEST['row_id']."'";
			
		if($dbc->query($sqlx)){
			ob_clean(); echo 'success';
			$dbc->query("UPDATE ".$cid."_leaves_data SET status = 'RJ' WHERE leave_id = '".$_REQUEST['row_id']."'");


						$sql1 = "SELECT * FROM ".$cid."_leaves WHERE id = '".$_REQUEST['row_id']."'";

			if($res1 = $dbc->query($sql1)){
				if($row1 = $res1->fetch_assoc()){
					$emp_id_value = $row1['emp_id'];
					$emp_leave_type = $row1['leave_type'];
					$emp_leave_type_details = unserialize($row1['details']);
					$emp_leave_reason = $row1['comment'];
					$emp_date_req = $row1['created'];
					$emp_leave_status = $row1['status'];
				}
			}

			$sql2 = "SELECT * FROM ".$cid."_employees WHERE emp_id = '".$emp_id_value."'";

			if($res2 = $dbc->query($sql2)){
				if($row2 = $res2->fetch_assoc()){
					$workEmail = $row2['work_email'];
					$namevalue = $row2[$lang.'_name'];
					$leave_approve = $row2['leave_approve'];

				}
			}


			$approve_by = getSystemUsers($leave_approve);

			$approve_by[$workEmail] = $workEmail;



			$leave_settings = getLeaveTimeSettings();
			$leave_types = unserialize($leave_settings['leave_types']);


			if($emp_leave_status == 'RJ')
			{
				$statusval = 'Rejected by '.$_SESSION['rego']['name'] ;
			} 
			else if($emp_leave_status == 'AP')
			{
				$statusval = 'Approved by '.$_SESSION['rego']['name'] ;
			}			
			else if($emp_leave_status == 'CA')
			{
				$statusval = 'Canceled by '.$_SESSION['rego']['name'];
			}
				

			$protocol = 'http://';
			if(isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on') { $protocol = 'https://';}		

			$template = getEmailTemplates('APPROVE_LEAVE_REQUEST');
			$txt = $template['body'];
			$text = str_replace('{EMPLOYEE_NAME}', $emp_id_value.' - '.$namevalue, $txt);
			$text = str_replace('{LEAVE_TYPE}', $leave_types[$emp_leave_type][$lang], $text);
			foreach($emp_leave_type_details as $v){
				$tmp = $v['day'];
				if($v['day'] == 'full'){$tmp = $lng['Full day'];}
				if($v['day'] == 'first'){$tmp = $lng['First half'];}
				if($v['day'] == 'second'){$tmp = $lng['Second half'];}
				 // $temps = date('d-m-Y', strtotime($v['date'])).' '.$tmp;
				 $temps .= date('d-m-Y', strtotime($v['date'])).' '.$tmp .'<br>'.' &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
		

			}
			$text = str_replace('{LEAVE_DETAILS}', $temps, $text);
			$text = str_replace('{LEAVE_REASON}', $emp_leave_reason, $text);
			$text = str_replace('{DATE_REQUEST}', $emp_date_req, $text);
			$text = str_replace('{CLICK_HERE_LINK}', 'https://regothailand.com/hr', $text);
			$text = str_replace('{RECIPIENT}', $namevalue, $text);
			$text = str_replace('{STATUS}', $statusval, $text);



			require DIR.'PHPMailer/PHPMailerAutoload.php';	

			$body = '<html>
						<head>
						<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
						</head>
						<body style="font-size:16px">'.nl2br($text).'</body>
					</html>';

			$mail = new PHPMailer;
			$mail->CharSet = 'UTF-8';
			// $mail->From = $_SESSION['rego']['username'];
			// $mail->FromName = $_SESSION['rego']['name'];
			$mail->From = $from_email;
			$mail->FromName = strtoupper($client_prefix).' ผู้ดูแลระบบ (Admin)';

			// $mail->addAddress($workEmail); 

			foreach($approve_by as $k=>$v){
				$mail->addAddress($v); 
			}

			// $mail->addReplyTo($_SESSION['rego']['username'], $_SESSION['rego']['name']);
			$mail->isHTML(true);                                  
			$mail->Subject = 'Leave request status for '.$namevalue;
			$mail->Body = $body;
			if(!empty($attachment)) {
				$mail->AddAttachment(ROOT.$attachment);
			}	
			$mail->WordWrap = 100;
			//echo $body;
			if(!$mail->send()) {
				echo $mail->ErrorInfo;
			}


		}else{
			echo mysqli_error($dbc);
		}
		exit;
	}
	
	if($_REQUEST['action'] == 'cancel'){
		$sqlx = "UPDATE ".$cid."_leaves SET 
			status = 'CA', 
			updated = '".date('d-m-Y @ H:i')."', 
			updated_by = '".$_SESSION['rego']['name']."', 
			comment = '".$_REQUEST['comment']."', 
			canceled = '".date('d-m-Y @ H:i')."', 
			canceled_by = '".$_SESSION['rego']['name']."' 
			WHERE id = '".$_REQUEST['row_id']."'";
		if($dbc->query($sqlx)){
			ob_clean(); echo 'success';
			$dbc->query("UPDATE ".$cid."_leaves_data SET status = 'CA' WHERE leave_id = '".$_REQUEST['row_id']."'");


						$sql1 = "SELECT * FROM ".$cid."_leaves WHERE id = '".$_REQUEST['row_id']."'";

			if($res1 = $dbc->query($sql1)){
				if($row1 = $res1->fetch_assoc()){
					$emp_id_value = $row1['emp_id'];
					$emp_leave_type = $row1['leave_type'];
					$emp_leave_type_details = unserialize($row1['details']);
					$emp_leave_reason = $row1['comment'];
					$emp_date_req = $row1['created'];
					$emp_leave_status = $row1['status'];
				}
			}

			$sql2 = "SELECT * FROM ".$cid."_employees WHERE emp_id = '".$emp_id_value."'";

			if($res2 = $dbc->query($sql2)){
				if($row2 = $res2->fetch_assoc()){
					$workEmail = $row2['work_email'];
					$namevalue = $row2[$lang.'_name'];
					$leave_approve = $row2['leave_approve'];


				}
			}


			$approve_by = getSystemUsers($leave_approve);

			$approve_by[$workEmail] = $workEmail;



			$leave_settings = getLeaveTimeSettings();
			$leave_types = unserialize($leave_settings['leave_types']);


			if($emp_leave_status == 'RJ')
			{
				$statusval = 'Rejected by '.$_SESSION['rego']['name'];
			} 
			else if($emp_leave_status == 'AP')
			{
				$statusval = 'Approved by '.$_SESSION['rego']['name'];
			}			
			else if($emp_leave_status == 'CA')
			{
				$statusval = 'Canceled by '.$_SESSION['rego']['name'];
			}
				

			$protocol = 'http://';
			if(isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on') { $protocol = 'https://';}		

			$template = getEmailTemplates('APPROVE_LEAVE_REQUEST');
			$txt = $template['body'];
			$text = str_replace('{EMPLOYEE_NAME}', $emp_id_value.' - '.$namevalue, $txt);
			$text = str_replace('{LEAVE_TYPE}', $leave_types[$emp_leave_type][$lang], $text);
			foreach($emp_leave_type_details as $v){
				$tmp = $v['day'];
				if($v['day'] == 'full'){$tmp = $lng['Full day'];}
				if($v['day'] == 'first'){$tmp = $lng['First half'];}
				if($v['day'] == 'second'){$tmp = $lng['Second half'];}
				 // $temps = date('d-m-Y', strtotime($v['date'])).' '.$tmp;
				 $temps .= date('d-m-Y', strtotime($v['date'])).' '.$tmp .'<br>'.' &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
		

			}
			$text = str_replace('{LEAVE_DETAILS}', $temps, $text);
			$text = str_replace('{LEAVE_REASON}', $emp_leave_reason, $text);
			$text = str_replace('{DATE_REQUEST}', $emp_date_req, $text);
			$text = str_replace('{CLICK_HERE_LINK}', 'https://regothailand.com/hr', $text);
			$text = str_replace('{RECIPIENT}', $namevalue, $text);
			$text = str_replace('{STATUS}', $statusval, $text);



			require DIR.'PHPMailer/PHPMailerAutoload.php';	

			$body = '<html>
						<head>
						<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
						</head>
						<body style="font-size:16px">'.nl2br($text).'</body>
					</html>';

			$mail = new PHPMailer;
			$mail->CharSet = 'UTF-8';
			// $mail->From = $_SESSION['rego']['username'];
			// $mail->FromName = $_SESSION['rego']['name'];
			$mail->From = $from_email;
			$mail->FromName = strtoupper($client_prefix).' ผู้ดูแลระบบ (Admin)';
			
			// $mail->addAddress($workEmail); 

			foreach($approve_by as $k=>$v){
				$mail->addAddress($v); 
			}


			// $mail->addReplyTo($_SESSION['rego']['username'], $_SESSION['rego']['name']);
			$mail->isHTML(true);                                  
			$mail->Subject = 'Leave request status for '.$namevalue;
			$mail->Body = $body;
			if(!empty($attachment)) {
				$mail->AddAttachment(ROOT.$attachment);
			}	
			$mail->WordWrap = 100;
			//echo $body;
			if(!$mail->send()) {
				echo $mail->ErrorInfo;
			}



		}else{
			echo mysqli_error($dbc);
		}
		exit;
	}

?>
















