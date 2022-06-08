<?
	
	if(session_id()==''){session_start();}
	ob_start();
	//var_dump($_REQUEST); exit;
	//var_dump($_FILES); //exit;
	
	include('../../dbconnect/db_connect.php');
	include(DIR.'files/functions.php');
	include(DIR.'leave/functions.php');
	include(DIR.'files/arrays_'.$lang.'.php');

	// echo '<pre>';
	// print_r($_REQUEST);
	// echo '</pre>';
	// exit;
	
	$attachment = '';
	$uploadmap = DIR.$cid.'/leave/';
	//var_dump($uploadmap); //exit;
	
	if(!empty($_FILES['attach']['tmp_name'])){
		$ext = pathinfo($_FILES['attach']['name'], PATHINFO_EXTENSION);		
		$file = $_REQUEST['emp_id'].'_'.$_REQUEST['leave_type'].'_'.time().'.'.$ext;
		$filename = $uploadmap.$file;
		if(move_uploaded_file($_FILES['attach']['tmp_name'],$filename)){
			$attachment = ROOT.$cid.'/leave/'.$file;
		}
	}else{
		//$attachment = $_REQUEST['attachment'];
	}
	//var_dump($attachment);
	
	$leave_id = $_REQUEST['leave_id'];
	$leave_settings = getLeaveTimeSettings();
	$leave_types = unserialize($leave_settings['leave_types']);
	foreach($leave_types as $k=>$v){
		$ebalance[$k] = array('th'=>$v['th'], 'en'=>$v['en'], 'maxdays'=>$v['max'][$_SESSION['rego']['emp_group']], 'maxpaid'=>$v['pay'][$_SESSION['rego']['emp_group']], 'pending'=>0, 'used'=>0);
	}
	$dayhours = $leave_settings['dayhours'];
	$pending = getPendingDays($cid, $_REQUEST['emp_id'], $leave_id);
	$pending_days = array();
	if(isset($pending['date'])){
		$pending_days = $pending['date'];
	}
	//var_dump($leave_types); //exit;
	//var_dump($pending_days); //exit;
	
	$emp_info = getEmployeeInfo($cid, $_REQUEST['emp_id']);
	$ALemp = $emp_info['annual_leave'];
	$ebalance['AL']['maxdays'] = $ALemp;
	//var_dump($ebalance); exit;
	$balance = getStrictBalanceEmployee($cid, $ebalance, $_REQUEST['emp_id']);
	
	$dates = $_REQUEST['date'];
	$startdate = $_REQUEST['date'][0];
	$enddate = $_REQUEST['date'][count($dates)-1];
	$days = $_REQUEST['day'];
	$tot_days = 0;
	$hours = 0;
	
	//var_dump($emp_info); exit;
	//var_dump($days); 


	foreach($dates as $k=>$v){
		$stt_date = strtotime($v);
		if(in_array($stt_date, $pending_days)){
			$nDay = $days[$k];
			$pDay = $pending['day'][$stt_date];
			if($nDay == $pDay || $nDay == 'full' || $pDay == 'full'){
				echo 'Date already used in other request : '.date('D d-m-Y', strtotime($v)); exit;
			}
			if(strpos($nDay, ':') !== false){
				echo 'Hours already used in other request : '.date('D d-m-Y', strtotime($v)); exit;
			}
		}
	}
	//exit;
	
	//var_dump($dates); //exit;
	//var_dump($days); //exit;
	
	foreach($dates as $k=>$v){
		$range[$k]['emp_id'] = $_REQUEST['emp_id'];
		$range[$k]['name'] = $_REQUEST['name'];
		$range[$k]['phone'] = $_REQUEST['phone'];
		$range[$k]['leave_type'] = $_REQUEST['leave_type'];
		$range[$k]['date'] = $v;
		$range[$k]['day'] = $days[$k];
		
		if($days[$k] == 'full'){
			$d = 1;
		}else if($days[$k] == 'first' || $days[$k] == 'second'){
			$d = 0.5;
		}else{
			$hrs = explode(' - ', $days[$k]);
			$hourdiff = round((strtotime($hrs[1]) - strtotime($hrs[0]))/3600, 1);
			$range[$k]['days'] = $hourdiff / $dayhours;
			$d = $hourdiff/$dayhours;
		}
		$range[$k]['days'] = $d;
		$range[$k]['status'] = $_REQUEST['status'];
		$range[$k]['certificate'] = $_REQUEST['certificate'];
		
		$details[$k]['date'] = $v;
		$details[$k]['day'] = $days[$k];
		$details[$k]['days'] = $d;
		$tot_days += $d;
	}
	$hours = $tot_days * $dayhours;
	if($leave_id == 0){
		if($leave_types[$_REQUEST['leave_type']]['max'] > 0){
			$bal = checkBalance($balance, $_REQUEST['leave_type'], $leave_types[$_REQUEST['leave_type']][$lang], $tot_days);
			if($bal){echo $bal; exit;}
		}
	}
	//var_dump($leave_types[$_REQUEST['leave_type']]['max']);
	//var_dump($details);
	//var_dump($tot_days);
	//exit;




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





	
	if($leave_id == 0){ // NEW REQUEST //////////////////////////////////////
	
		$sql = "INSERT INTO ".$cid."_leaves (emp_id, name, phone, entity, branch, division, department, team, emp_group, leave_type, start, end, details, days, planned, paid, status, attach, certificate, created, created_by, reason) VALUES (
			'".$dbc->real_escape_string($_REQUEST['emp_id'])."', 
			'".$dbc->real_escape_string($_REQUEST['name'])."', 
			'".$dbc->real_escape_string($_REQUEST['phone'])."',
			
			'".$dbc->real_escape_string($emp_info['entity'])."',
			'".$dbc->real_escape_string($emp_info['branch'])."',
			'".$dbc->real_escape_string($emp_info['division'])."',
			'".$dbc->real_escape_string($emp_info['department'])."',
			'".$dbc->real_escape_string($emp_info['team'])."',
			'".$dbc->real_escape_string($emp_info['emp_group'])."',
			 
			'".$dbc->real_escape_string($_REQUEST['leave_type'])."', 
			'".$dbc->real_escape_string($startdate)."', 
			'".$dbc->real_escape_string($enddate)."', 
			'".$dbc->real_escape_string(serialize($details))."', 
			'".$dbc->real_escape_string($tot_days)."', 
			
			'".$dbc->real_escape_string($leave_types[$_REQUEST['leave_type']]['planned'])."', 
			'".$dbc->real_escape_string($leave_types[$_REQUEST['leave_type']]['paid'])."', 
			
			'".$dbc->real_escape_string($_REQUEST['status'])."', 
			'".$dbc->real_escape_string($attachment)."', 
			'".$dbc->real_escape_string($_REQUEST['certificate'])."', 
			'".$dbc->real_escape_string(date('d-m-Y @ H:i'))."', 
			'".$dbc->real_escape_string($_REQUEST['by_name'])."',
			'".$dbc->real_escape_string($_REQUEST['reason'])."')";
		
		//echo $sql;	
		
		if(!$dbc->query($sql)){
			echo '1 - '.mysqli_error($dbc);
		}else{
			$leave_id = $dbc->insert_id;

			
			$protocol = 'http://';
			if(isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on') { $protocol = 'https://';}		

			$template = getEmailTemplates('NEW_LEAVE_REQUEST');
			$txt = $template['body'];
			$text = str_replace('{EMPLOYEE_NAME}', $_REQUEST['emp_id'].' - '.$empinfo[$lang.'_name'], $txt);
			$text = str_replace('{LEAVE_TYPE}', $leave_types[$_REQUEST['leave_type']][$lang], $text);
				foreach($details as $v){
				$tmp = $v['day'];
				if($v['day'] == 'full'){$tmp = $lng['Full day'];}
				if($v['day'] == 'first'){$tmp = $lng['First half'];}
				if($v['day'] == 'second'){$tmp = $lng['Second half'];}
				 $temps .= date('d-m-Y', strtotime($v['date'])).' '.$tmp .'<br>'.' &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
			}
			$text = str_replace('{LEAVE_DETAILS}', $temps, $text);
			if(!empty($attachment))
			{
				$temps2 = '<a href="'.$attachment.'">'.$lng['Certificate'].'</a>';
			}
			else
			{
				$temps2 = $certificate;
			}
			$text = str_replace('{CERTIFICATE_DETAILS}', $temps2, $text);
			$text = str_replace('{LEAVE_REASON}', nl2br($_REQUEST['reason']), $text);
			$text = str_replace('{DATE_REQUEST}', date('d-m-Y @ H:i'), $text);
			$text = str_replace('{CLICK_HERE_LINK}', 'https://regothailand.com/hr', $text);
			$text = str_replace('{RECIPIENT}', $_REQUEST['systemuseremailhtml'], $text);




			// Send email ---------------------------------------------------------------------------------------------------------
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
			// foreach($teamsid111 as $k=>$v){
				$mail->addAddress($_REQUEST['systemuseremail']); 
			// }
			// $mail->addReplyTo($_SESSION['rego']['username'], $_SESSION['rego']['name']);
			$mail->isHTML(true);                                  
			$mail->Subject = 'Leave request from '.$empinfo[$lang.'_name'];
			$mail->Body = $body;
			if(!empty($attachment)) {
				$mail->AddAttachment(ROOT.$attachment);
			}	
			$mail->WordWrap = 100;
			//echo $body;
			if(!$mail->send()) {
				echo $mail->ErrorInfo;
			}

		}
		//exit;
	
	}else{ // UPDATE REQUEST ///////////////////////////////////////////////////////
	
		$sql = "UPDATE ".$cid."_leaves SET 
			leave_type = '".$dbc->real_escape_string($_REQUEST['leave_type'])."', 
			start = '".$dbc->real_escape_string($startdate)."',  
			end = '".$dbc->real_escape_string($enddate)."', 
			days = '".$dbc->real_escape_string($tot_days)."', 
			details = '".serialize($details)."', 
			attach = '".$dbc->real_escape_string($attachment)."', 
			certificate = '".$dbc->real_escape_string($_REQUEST['certificate'])."', 
			updated = '".$dbc->real_escape_string(date('d-m-Y @ H:i'))."', 
			updated_by = '".$dbc->real_escape_string($_SESSION['rego']['name'])."', 
			reason = '".$dbc->real_escape_string($_REQUEST['reason'])."' 
			WHERE id = '".$leave_id."'";
		
		if(!$dbc->query($sql)){
			//ob_clean();
			echo '2 - '.mysqli_error($dbc); exit;
		}
	
		if(!$dbc->query("DELETE FROM ".$cid."_leaves_data WHERE leave_id = '".$leave_id."'")){
			echo '3 - '.mysql_error($dbc);
			exit;
		}

	}	
	
	$sql = "INSERT INTO ".$cid."_leaves_data (leave_id, emp_id, name, phone, entity, branch, division, department, team, emp_group, leave_type, date, day, days, hours, planned, paid, status, certificate, reason) VALUES ";
	foreach($range as $k=>$v){
		$sql .= "
			('".$dbc->real_escape_string($leave_id)."', 
			'".$dbc->real_escape_string($_REQUEST['emp_id'])."', 
			'".$dbc->real_escape_string($_REQUEST['name'])."', 
			'".$dbc->real_escape_string($_REQUEST['phone'])."', 
			
			'".$dbc->real_escape_string($emp_info['entity'])."',
			'".$dbc->real_escape_string($emp_info['branch'])."',
			'".$dbc->real_escape_string($emp_info['division'])."',
			'".$dbc->real_escape_string($emp_info['department'])."',
			'".$dbc->real_escape_string($emp_info['team'])."',
			'".$dbc->real_escape_string($emp_info['emp_group'])."',
			
			'".$dbc->real_escape_string($_REQUEST['leave_type'])."', 
			'".$dbc->real_escape_string($v['date'])."', 
			'".$dbc->real_escape_string($v['day'])."', 
			'".$dbc->real_escape_string($v['days'])."', 
			'".$dbc->real_escape_string($hours)."', 
			
			'".$dbc->real_escape_string($leave_types[$_REQUEST['leave_type']]['planned'])."', 
			'".$dbc->real_escape_string($leave_types[$_REQUEST['leave_type']]['paid'])."', 
			
			'".$dbc->real_escape_string($v['status'])."', 
			'".$dbc->real_escape_string($_REQUEST['certificate'])."', 
			'".$dbc->real_escape_string($_REQUEST['reason'])."'),"; 
		
	}
	$sql = substr($sql, 0, -1);
	//echo '<br><br>'.$sql;	
	
	ob_clean();
	if(!$dbc->query($sql)){
		echo '4 - '.mysqli_error($dbc);
	}else{
		echo 'success';
	}
	//updateLeaveDatabase($cid);
	
	
	
	
	
	exit;























	$recipient = array();
	$sql = "SELECT name, username FROM rego_company_users WHERE cid = '".$cid."' AND approve != 'non'";
	if($res = $dbx->query($sql)){
		while($row = $res->fetch_assoc()){ 
			$recipient[$row['username']] = $row['name'];
		}
	}
	//var_dump($email); exit;
	
	if(!empty($recipient)){
		// Send email ---------------------------------------------------------------------------------------------------------
		require DIR.'PHPMailer/PHPMailerAutoload.php';	
	
		$body = '<html>
				<head>
				<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
				</head>
				<style>
					table {font-size:16px;}
					table th, table td {padding:1px 0 1px 10px; vertical-align:top}
					table th {text-align:right; white-space:nowrap;}
				</style>
				<body>
					<table border="0">
						<tr>
							<th>'.$lng['Employee'].' : </th><td>'.$emp_info['emp_id'].' - '.$emp_info[$lang.'_name'].'</td>
						</tr>
						<tr>
							<th>'.$lng['Leave type'].' : </th><td>'.$leave_types[$_REQUEST['leave_type']][$lang].'</td>
						</tr>
						<tr>
							<th>'.$lng['Details'].' : </th>
							<td>';
							foreach($details as $v){
								$tmp = $v['day'];
								if($v['day'] == 'full'){$tmp = $lng['Full day'];}
								if($v['day'] == 'first'){$tmp = $lng['First half'];}
								if($v['day'] == 'second'){$tmp = $lng['Second half'];}
								$body .= date('d-m-Y', strtotime($v['date'])).' &nbsp;&nbsp;&bull;&nbsp;&nbsp; '.$tmp.'<br>';
							}
						$body .= '</td>
						</tr>
						<tr>
							<th>'.$lng['Certificate'].' : </th><td>';
							if($_REQUEST['certificate'] == 'Y'){$body .= '<a href="'.$attachment.'">'.$lng['Certificate'].'</a>';}
							if($_REQUEST['certificate'] == 'H'){$body .= $lng['Handed to HR department or supervisor'];}
							if($_REQUEST['certificate'] == 'N'){$body .= $lng['No certificate'];}
							if($_REQUEST['certificate'] == 'NA'){$body .= $lng['NA'];}
						$body .= '</td>
						</tr>
						<tr>
							<th>'.$lng['Reason'].' : </th><td>'.nl2br($_REQUEST['reason']).'</td>
						</tr>
						<tr>
							<th>'.$lng['Date request'].' : </th><td>'.date('d-m-Y @ H:i').'</td>
						</tr>
				</body>
			</html>';
	
		$mail = new PHPMailer;
		$mail->CharSet = 'UTF-8';
		$mail->From = $_SESSION['rego']['username'];
		$mail->FromName = $emp_info[$lang.'_name'];
		foreach($recipient as $k=>$v){
			$mail->addAddress($k, $v); 
		}
		$mail->addReplyTo($_SESSION['rego']['username'], $emp_info[$lang.'_name']);
		$mail->isHTML(true);                                  
		$mail->Subject = $lng['Leave request from'].' '.$emp_info[$lang.'_name'];
		$mail->Body = $body;
		/*if(!empty($attachment)) {
			$mail->AddAttachment(ROOT.$attachment);
		}	*/
		$mail->WordWrap = 100;
		//echo $body;
		if(!$mail->send()) {
			echo $mail->ErrorInfo;
		}
	
	}
	
	
	
	
	
	
	
	
