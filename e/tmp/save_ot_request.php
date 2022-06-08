<?
	if(session_id()==''){session_start();}
	ob_start();

	//var_dump($_REQUEST); exit;

	include('../../dbconnect/db_connect.php');
	include(DIR.'files/functions.php');
	include(DIR.'files/arrays_'.$_SESSION['xhr']['lang'].'.php');
	
	$emp_info = getEmployeeInfo($cid, $_REQUEST['emp_id']);
	//var_dump($emp_info); exit;

	if($_REQUEST['ot_id'] == 0){ // New request ------------------------------------------------------------------------
			
		$sql = "INSERT INTO ".$cid."_ot_requests (emp_id, emp_name, start, end, hours, otat, type, status, remarks, created, created_by_id, created_by_name) VALUES (
			'".$dbc->real_escape_string($_REQUEST['emp_id'])."', 
			'".$dbc->real_escape_string($_REQUEST['emp_name'])."', 
			'".$dbc->real_escape_string(date('Y-m-d', strtotime($_REQUEST['startdate'])))."', 
			'".$dbc->real_escape_string(date('Y-m-d', strtotime($_REQUEST['enddate'])))."', 
			'".$dbc->real_escape_string($_REQUEST['hours'])."', 
			'".$dbc->real_escape_string($_REQUEST['otat'])."', 
			'".$dbc->real_escape_string($_REQUEST['type'])."', 
			'".$dbc->real_escape_string($_REQUEST['status'])."', 
			'".$dbc->real_escape_string($_REQUEST['remarks'])."', 
			'".$dbc->real_escape_string(date('d-m-Y @ H:i'))."', 
			'".$dbc->real_escape_string($_REQUEST['by_id'])."', 
			'".$dbc->real_escape_string($_REQUEST['by_name'])."')";
		
		ob_clean();
		if($dbc->query($sql)){
			echo 'ok';
			
			$toEmail = $emp_info['lim_email'];
			$toName = $emp_info['lim_name'];
			if(empty($toEmail)){$toEmail = $from_email;}
			if(empty($toName)){$toName = $compinfo['en_compname'];}
			
			require '../../PHPMailer/PHPMailerAutoload.php';	
	
			$body = '<html>
					<head>
					<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
					<style>
						.etable {border-collapse:collapse}
						.etable th, .etable td {padding:2px 5px; line-height:105%; vertical-align:top}
						.etable th {padding-left:0; text-align:right; white-space:nowrap}
					</style>
					</head>
					<body>
						<table class="etable" border="0">
							<tr>
								<th>Employee : </th><td>'.$_REQUEST['emp_name'].' ( '.$emp_info['th_name'].' )</td>
							</tr>
							<tr>
								<th>Employee ID : </th><td>'.$_REQUEST['emp_id'].'</td>
							</tr>
							<tr>
								<th>First day : </th><td style="text-decoration:none !important">'.$_REQUEST['startdate'].'</td>
							</tr>
							<tr>
								<th>Last day : </th><td style="text-decoration:none !important">'.$_REQUEST['enddate'].'</td>
							</tr>
							<tr>
								<th>Total hours : </th><td>'.$_REQUEST['hours'].'</td>
							</tr>
							<tr>
								<th>OT at : </th><td>'.$overtime_at[$_REQUEST['otat']].'</td>
							</tr>
							<tr>
								<th>OT type : </th><td>'.$overtime_type[$_REQUEST['type']].'</td>
							</tr>
							<tr>
								<th>Remarks : </th><td>'.nl2br($_REQUEST['remarks']).'</td>
							</tr>
					</body>
				</html>';
	
			$mail = new PHPMailer;
			$mail->CharSet = 'UTF-8';
			$mail->From = $_SESSION['xhr']['email'];
			$mail->FromName = $_SESSION['xhr']['name'];
			$mail->addAddress($toEmail, $toName); 
			//$mail->addReplyTo($_SESSION['xhr']['email'], $_SESSION['xhr']['name']);
			$mail->isHTML(true);                                  
			$mail->Subject = 'OT Request';
			$mail->Body = $body;
			$mail->WordWrap = 100;
			//echo $body;
			if(!$mail->send()) {
				//echo $mail->ErrorInfo;
			}
		}else{
			echo mysqli_error($dbc);
		}
			
	}else{ // Edit request ---------------------------------------------------------------------------------

	}

	exit;	
	
