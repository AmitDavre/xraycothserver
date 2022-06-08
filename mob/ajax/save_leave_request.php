<?

	if(session_id()==''){session_start();} 
	ob_start();
	include('../../dbconnect/db_connect.php');
	include('../../files/functions.php');




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



	//var_dump($_REQUEST); //exit;
	//var_dump($_FILES);
	$empinfo = getEmployeeInfo($cid, $_REQUEST['emp_id']);
	
	$uploadmap = DIR.$cid.'/leave/';
	//var_dump($empinfo); exit;
	
	$certificate = 'NA';
	$attachment = '';
	if(!empty($_FILES['attach']['tmp_name'])){
		$ext = pathinfo($_FILES['attach']['name'], PATHINFO_EXTENSION);		
		$file = $_REQUEST['emp_id'].'_'.$_REQUEST['leave_type'].'_'.time().'.'.$ext;
		$filename = $uploadmap.$file;
		if(move_uploaded_file($_FILES['attach']['tmp_name'],$filename)){
			$attachment = ROOT.$cid.'/leave/'.$file;
			$certificate = 'Y';
		}
	}else{
		//$attachment = $_REQUEST['attachment'];
	}
	//var_dump($attachment);
	
	$leave_settings = getLeaveTimeSettings();
	$dayhours = $leave_settings['dayhours'];
	$leave_types = unserialize($leave_settings['leave_types']);

	$dates = $_REQUEST['date'];
	$startdate = $_REQUEST['date'][0];
	$enddate = $_REQUEST['date'][count($dates)-1];


	//check if leave already taken...
	$checkleaves = "SELECT * FROM ".$cid."_leaves_data WHERE date = '".$startdate."'  AND `status`='RQ' AND `emp_id`= '".$_REQUEST['emp_id']."'";
	$resleave = $dbc->query($checkleaves);
	$num_rows = $resleave->num_rows;
	if($num_rows > 0){
		ob_clean();
		echo 'error';
		exit;
	}

	//echo '<pre>';
	//print_r($resleave);
	//echo $startdate;
	//echo '<br>';
	//echo $enddate;
	//echo '</pre>';
	//exit;
	
	$days = $_REQUEST['day'];
	$leave_id = time();
	$tot_days = 0;
	$hours = 0;
	//if(!isset($_REQUEST['certificate'])){$_REQUEST['certificate'] = '';}

	foreach($dates as $k=>$v){
		
		$range[$k]['emp_id'] = $_REQUEST['emp_id'];
		$range[$k]['name'] = $empinfo[$lang.'_name'];
		$range[$k]['phone'] = $empinfo['personal_phone'];
		$range[$k]['leave_type'] = $_REQUEST['leave_type'];
		$range[$k]['date'] = $v;
		$range[$k]['day'] = $days[$k];
		
		if($days[$k] == 'full'){
			$d = 1;
		}else if($days[$k] == 'first' || $days[$k] == 'second'){
			$d = 0.5;
		}else{
			$hrs = explode(' - ', $days[$k]);
			//var_dump($hrs);
			$hourdiff = round((strtotime($hrs[1]) - strtotime($hrs[0]))/3600, 1);
			//var_dump($hourdiff);
			$range[$k]['days'] = $hourdiff/8; //$dayhours
			$d = $hourdiff/8; //$dayhours
		}
		$range[$k]['days'] = $d;
		$range[$k]['status'] = 'RQ';
		$range[$k]['certificate'] = $certificate;
		
		$details[$k]['date'] = $v;
		$details[$k]['day'] = $days[$k];
		$details[$k]['days'] = $d;
		$tot_days += $d;
	}
	$hours = $tot_days * $dayhours;

	//var_dump($details); //exit;

	$email = array();
	// get system user name who is team head 

	// echo '<pre>';
	// print_r($details);
	// echo '</pre>';

	// die();


	
	$sql = "INSERT INTO ".$cid."_leaves (emp_id, name, phone, entity, branch, division, department, team, emp_group, leave_type, start, end, details, days, planned, paid, status, attach, certificate, created, created_by, reason) VALUES (
		'".$dbc->real_escape_string($_REQUEST['emp_id'])."', 
		'".$dbc->real_escape_string($empinfo[$lang.'_name'])."', 
		'".$dbc->real_escape_string($empinfo['personal_phone'])."', 
		
		'".$dbc->real_escape_string($empinfo['entity'])."', 
		'".$dbc->real_escape_string($empinfo['branch'])."', 
		'".$dbc->real_escape_string($empinfo['division'])."', 
		'".$dbc->real_escape_string($empinfo['department'])."', 
		'".$dbc->real_escape_string($empinfo['team'])."', 
		'".$dbc->real_escape_string($empinfo['emp_group'])."', 
		
		'".$dbc->real_escape_string($_REQUEST['leave_type'])."', 
		'".$dbc->real_escape_string($startdate)."', 
		'".$dbc->real_escape_string($enddate)."', 
		'".$dbc->real_escape_string(serialize($details))."', 
		'".$dbc->real_escape_string($tot_days)."',
		
		'".$dbc->real_escape_string($leave_types[$_REQUEST['leave_type']]['planned'])."', 
		'".$dbc->real_escape_string($leave_types[$_REQUEST['leave_type']]['paid'])."', 

		'".$dbc->real_escape_string('RQ')."', 
		'".$dbc->real_escape_string($attachment)."', 
		'".$dbc->real_escape_string($certificate)."', 
		'".$dbc->real_escape_string(date('d-m-Y @ H:i'))."', 
		'".$dbc->real_escape_string($empinfo[$lang.'_name'])."',
		'".$dbc->real_escape_string($_REQUEST['reason'])."')";
	//echo $sql;	
	
	$leave_id = 0;
	if(!$dbc->query($sql)){
		echo mysqli_error($dbc);
	}else{
		$leave_id = $dbc->insert_id;
	}
	//exit;
	
	$sql11 = "INSERT INTO ".$cid."_leaves_data (emp_id, name, phone, entity, branch, division, department, team, emp_group, leave_type, date, day, days, planned, paid, hours, status, certificate, reason, leave_id) VALUES ";
	foreach($range as $k=>$v){
		$sql11 .= "
			('".$dbc->real_escape_string($_REQUEST['emp_id'])."', 
			'".$dbc->real_escape_string($empinfo[$lang.'_name'])."', 
			'".$dbc->real_escape_string($empinfo['personal_phone'])."', 
			
			'".$dbc->real_escape_string($empinfo['entity'])."', 
			'".$dbc->real_escape_string($empinfo['branch'])."', 
			'".$dbc->real_escape_string($empinfo['division'])."', 
			'".$dbc->real_escape_string($empinfo['department'])."', 
			'".$dbc->real_escape_string($empinfo['team'])."', 
			'".$dbc->real_escape_string($empinfo['emp_group'])."', 
			
			'".$dbc->real_escape_string($_REQUEST['leave_type'])."', 
			'".$dbc->real_escape_string($v['date'])."', 
			'".$dbc->real_escape_string($v['day'])."', 
			'".$dbc->real_escape_string($v['days'])."',
		
			'".$dbc->real_escape_string($leave_types[$_REQUEST['leave_type']]['planned'])."', 
			'".$dbc->real_escape_string($leave_types[$_REQUEST['leave_type']]['paid'])."', 

			'".$dbc->real_escape_string($hours)."', 
			'".$dbc->real_escape_string($v['status'])."', 
			'".$dbc->real_escape_string($certificate)."', 
			'".$dbc->real_escape_string($_REQUEST['reason'])."', 
			'".$dbc->real_escape_string($leave_id)."'),";
		
	}
	$sql11 = substr($sql11, 0, -1);

	if(!$dbc->query($sql11)){
		echo mysqli_error($dbc);
	}

	//echo $sql;	


	$sql = "SELECT * FROM ".$cid."_leaves WHERE id= '".$leave_id."' ";
	if($res = $dbc->query($sql)){
		while($row = $res->fetch_assoc()){ 

			$teamsid= $row['team'];
		}
	}		


	$sql123123 = "SELECT * FROM ".$cid."_employees WHERE emp_id = '".$_REQUEST['emp_id']."' ";
	if($refsdfs = $dbc->query($sql123123)){
		while($roadsdw = $refsdfs->fetch_assoc()){ 

			$teamsidsss= $roadsdw['leave_approve'];
		}
	}	

	// echo $teamsidsss;

	// die();

	// $sql111 = "SELECT * FROM ".$cid."_users WHERE find_in_set (".$teamsid.",teams) ";
	// if($res111 = $dbc->query($sql111)){
	// 	while($row111 = $res111->fetch_assoc()){ 

	// 		$teamsid111[$row111['username']]= $row111['name'];
	// 	}
	// }
		
	// echo $teamsidsss;
	// die();

	$explodeteamarr = explode(',', $teamsidsss);
	$team_numbers = "'" . implode("','", $explodeteamarr) . "'";

	$sql111 = "SELECT * FROM ".$cid."_users WHERE id in (".$team_numbers.")";
	if($res111 = $dbc->query($sql111)){
		while($row111 = $res111->fetch_assoc()){ 

			$teamsid111[$row111['username']]= $row111['name'];

		}
	}
	






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
	$text = str_replace('{RECIPIENT}', 'System User', $text);


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

	foreach($teamsid111 as $k=>$v){
		$mail->addAddress($k, $v); 
	}
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






	








?>










