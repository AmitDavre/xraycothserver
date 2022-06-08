<?
	header("Access-Control-Allow-Origin: *");
	
	if(session_id()==''){session_start();}
	ob_start();
	include("../dbconnect/db_connect.php");
	include(DIR."admin/files/admin_functions.php");

	if(isset($_REQUEST['token'])){
		if($_REQUEST['token'] == '2ghuK!@njhAF'){
			$valid = true;
			if(empty($_REQUEST['lang']) || 
				$_REQUEST['version'] == '' || 
				empty($_REQUEST['firstname']) || 
				empty($_REQUEST['lastname']) || 
				empty($_REQUEST['company']) || 
				empty($_REQUEST['email']) || 
				empty($_REQUEST['phone']) || 
				empty($_REQUEST['pass1']) || 
				empty($_REQUEST['pass2'])){
				//$valid = false;
				echo json_encode('Empty fields dedected');
				exit;
			}
			if($_REQUEST['pass1'] != $_REQUEST['pass2']){
				$valid = false;
				echo json_encode('Passwords are not the same');
				exit;
			}
			if(strlen($_REQUEST['pass1']) < 8){
				$valid = false;
				echo json_encode('Password need at least 8 characters ');
				exit;
			}
			if($valid){
				echo json_encode('success');
			}else{
				echo json_encode('failed');
			}
			exit;
		}else{
			echo json_encode('Wrong Token'); 
			exit;
		}
	}
	//var_dump($_REQUEST); //exit;
	//exit;
	
	$error = false;
	$cid = getNewCustomerID();
	$visit = 0;
	$password_txt = $_REQUEST['email'];
	$password = hash('sha256', $password_txt);
	//var_dump($cid); exit;
	
	//echo '<pre>';
	//var_dump($_REQUEST);
	//echo '</pre>';

	if(isset($_REQUEST['token'])){
		if(empty($_REQUEST['company'])){
			$_REQUEST['th_compname'] = $_REQUEST['firstname'].' '.$_REQUEST['lastname'];
			$_REQUEST['en_compname'] = $_REQUEST['th_compname'];
		}else{
			$_REQUEST['th_compname'] = $_REQUEST['company'];
			$_REQUEST['en_compname'] = $_REQUEST['company'];
		}
		$_REQUEST['comp_phone'] = '';
		$_REQUEST['comp_fax'] = '';
		$_REQUEST['comp_email'] = '';
		$_REQUEST['remarks'] = '';
		$_REQUEST['joiningdate'] = date('d-m-Y');
		//$_REQUEST['version'] = '0';
		$_REQUEST['employees'] = 20;
		$_REQUEST['emp_platform'] = 1;
		$_REQUEST['th_address'] = '';
		$_REQUEST['en_address'] = '';
		$_REQUEST['th_billing'] = '';
		$_REQUEST['en_billing'] = '';
		$_REQUEST['tax_id'] = '';
		$_REQUEST['position'] = '';
		$_REQUEST['tax_id'] = '';
		$_REQUEST['username'] = $_REQUEST['email'];
		//$_REQUEST['password'] = $_REQUEST['email'];
		$_REQUEST['duration'] = 1;
		$visit = 1;
		$lang = $_REQUEST['lang'];
		$_SESSION['rego']['lang'] = $_REQUEST['lang'];
		$password_txt = $_REQUEST['pass2'];
		$password = hash('sha256', $password_txt);
	}
	//echo '<pre>';
	//var_dump($_REQUEST);
	//echo '</pre>';
	//exit;
	
	//$year = date('Y',strtotime($_REQUEST['joiningdate']));
	$year = date('Y');
	$_REQUEST['expiredate'] = date('d-m-Y', strtotime('+'.$_REQUEST['duration'].' month', strtotime($_REQUEST['joiningdate'])));

	//var_dump($_REQUEST); exit;
	
	$err_msg = '<div style="font-weight:400;line-height:20px;"><div style="color:#a00; font-size:16px; font-weight:600; border-bottom:1px solid #ccc; margin:0 10px 2px 0;">Create Folders</div>';
	
	$dir = '../../';
	$uploadmap[] = $cid;
	$uploadmap[] = $cid.'/archive';
	$uploadmap[] = $cid.'/documents';
	$uploadmap[] = $cid.'/uploads';
	$uploadmap[] = $cid.'/reports';
	$uploadmap[] = $cid.'/employees';
	$uploadmap[] = $cid.'/payroll';
	$uploadmap[] = $cid.'/gov_forms';

	foreach($uploadmap as $key=>$val){
		if(!file_exists($dir.$val)) {
			$oldmask = umask(0);
			if(mkdir($dir.$val, 0777, true)){
				$err_msg .= '<i class="fa fa-check-square-o"></i>&nbsp; '.$lng['Directory'].' <b>'.$val.'</b> '.$lng['created successfuly'].'<br>';
			}else{
				$err_msg .= '<b style="color:#c00"><i class="fa fa-times-circle"></i>&nbsp; '.$lng['Create subdirectory'].' '.$val.' '.$lng['failed'].'</b><br>';
				$error = true;
			}
			umask($oldmask);
		}else{
			$err_msg .= '<i class="fa fa-check-square-o"></i>&nbsp; '.$lng['Directory'].' <b>'.$val.'</b> '.$lng['exist already'].'<br>';
		}
	}
	//echo $err_msg; exit;
	include("create_database.php");
	//echo $err_msg; exit;

	$err_msg .= '<div style="color:#a00; font-size:16px; font-weight:600; border-bottom:1px solid #ccc; margin:5px 10px 2px 0">Update Databases</div>';

	$data = array();
	$res = $dba->query("SELECT * FROM rego_default_settings");
	if($row = $res->fetch_assoc()){
		$data['pvf_rate_employee'] = $row['pvf_rate_employee'];
		$data['pvf_rate_employer'] = $row['pvf_rate_employer'];
		$data['tax_calc_method'] = $row['tax_calc_method'];
		$data['fix_allow'] = $row['fix_allow'];
		$data['var_allow'] = $row['var_allow'];
		$data['payslip_template'] = $row['payslip_template'];
		$data['payslip_rate'] = $row['payslip_rate'];
		$data['payslip_field'] = $row['payslip_field'];
		$data['support_email'] = $row['support_email'];
		$data['bonus_payinmonth'] = $row['bonus_payinmonth'];
		$data['positions'] = $row['positions'];
		$data['account_codes'] = $row['account_codes'];
		$data['account_allocations'] = $row['allocations'];
	}
	//var_dump($data); //exit;
	
	$sql = "INSERT INTO ".$cid."_company_settings (id, en_compname, th_compname, branch, cur_year, years, th_address, en_address, th_addr_detail, en_addr_detail, tax_id, comp_phone, comp_fax, comp_email, logofile) VALUES (
			'".$dbc->real_escape_string(1)."',
			'".$dbc->real_escape_string($_REQUEST['en_compname'])."',
			'".$dbc->real_escape_string($_REQUEST['th_compname'])."',
			'".$dbc->real_escape_string('00000')."',
			'".$dbc->real_escape_string(date('Y'))."',
			'".$dbc->real_escape_string(date('Y'))."',
			'".$dbc->real_escape_string($_REQUEST['th_address'])."', 
			'".$dbc->real_escape_string($_REQUEST['en_address'])."',
			'".$dbc->real_escape_string('a:13:{s:8:"building";s:0:"";s:7:"village";s:0:"";s:4:"room";s:0:"";s:5:"floor";s:0:"";s:6:"number";s:0:"";s:3:"moo";s:0:"";s:4:"lane";s:0:"";s:4:"road";s:0:"";s:11:"subdistrict";s:0:"";s:8:"district";s:0:"";s:8:"province";s:0:"";s:6:"postal";s:0:"";s:7:"country";s:0:"";}')."',
			'".$dbc->real_escape_string('a:13:{s:8:"building";s:0:"";s:7:"village";s:0:"";s:4:"room";s:0:"";s:5:"floor";s:0:"";s:6:"number";s:0:"";s:3:"moo";s:0:"";s:4:"lane";s:0:"";s:4:"road";s:0:"";s:11:"subdistrict";s:0:"";s:8:"district";s:0:"";s:8:"province";s:0:"";s:6:"postal";s:0:"";s:7:"country";s:0:"";}')."',
			'".$dbc->real_escape_string($_REQUEST['tax_id'])."', 
			'".$dbc->real_escape_string($_REQUEST['comp_phone'])."', 
			'".$dbc->real_escape_string($_REQUEST['comp_fax'])."', 
			'".$dbc->real_escape_string($_REQUEST['comp_email'])."', 
			'".$dbc->real_escape_string('images/xray_logo.png')."') 
				ON DUPLICATE KEY UPDATE 
				en_compname = VALUES(en_compname),
				th_compname = VALUES(th_compname),
				branch = VALUES(branch),
				th_address = VALUES(th_address),
				en_address = VALUES(en_address),
				tax_id = VALUES(tax_id),
				comp_phone = VALUES(comp_phone),
				comp_fax = VALUES(comp_fax),
				comp_email = VALUES(comp_email),
				logofile = VALUES(logofile),
				positions = VALUES(positions)";
		if(!$dbc->query($sql)){
			$err_msg .= '<span style="color:#c00"><i class="fa fa-times-circle"></i>&nbsp; Saving <b>Default company settings</b> failed. Error : Database <b>'.mysqli_error($dbc).'</b></span><br>';
			
			$error = true;
		}else{
			$err_msg .= '<i class="fa fa-check-square-o"></i>&nbsp; <b>Default company settings</b> saved successfuly.<br>';
		}
	
	$sql = "INSERT INTO ".$cid."_settings (id, cur_month, pr_startdate, pvf_rate_employee, pvf_rate_employer, tax_calc_method, positions, fix_allow, var_allow, sso_act_max, payslip_template, payslip_rate, payslip_field, support_email, bonus_payinmonth, account_codes) VALUES (
			'".$dbc->real_escape_string(1)."',
			'".$dbc->real_escape_string(1)."',
			'".$dbc->real_escape_string('01-01-'.$year)."',
			'".$dbc->real_escape_string($data['pvf_rate_employee'])."',
			'".$dbc->real_escape_string($data['pvf_rate_employer'])."',
			'".$dbc->real_escape_string($data['tax_calc_method'])."',
			'".$dbc->real_escape_string($data['positions'])."', 
			'".$dbc->real_escape_string($data['fix_allow'])."',
			'".$dbc->real_escape_string($data['var_allow'])."', 
			'".$dbc->real_escape_string('act')."', 
			'".$dbc->real_escape_string($data['payslip_template'])."', 
			'".$dbc->real_escape_string($data['payslip_rate'])."', 
			'".$dbc->real_escape_string($data['payslip_field'])."', 
			'".$dbc->real_escape_string($data['support_email'])."', 
			'".$dbc->real_escape_string($data['bonus_payinmonth'])."', 
			'".$dbc->real_escape_string($data['account_codes'])."') 
				ON DUPLICATE KEY UPDATE 
				id = VALUES(id)";
		if(!$dbc->query($sql)){
			$err_msg .= '<span style="color:#c00"><i class="fa fa-times-circle"></i>&nbsp; Saving <b>Default settings</b> failed. Error : '.mysqli_error($dbc).'</span><br>';
			$error = true;
		}else{
			$err_msg .= '<i class="fa fa-check-square-o"></i>&nbsp; <b>Default settings</b> saved successfuly.<br>';
		}
	
	$res = $dba->query("SELECT * FROM rego_company_users WHERE LOWER(username) = '".strtolower($_REQUEST['email'])."'");
	$exist = $res->fetch_assoc();
	if($exist){
		if(strstr($exist['access'], $cid) == false) {
			$access = $exist['access'] .= ','.$cid;
			if($res = $dba->query("UPDATE rego_company_users SET 
				access = '".$dba->real_escape_string($access)."',
				type = '".$dba->real_escape_string('sys')."' 
				WHERE username = '".strtolower($_REQUEST['email']."'"))){
				$err_msg .= '<i class="fa fa-check-square-o"></i>&nbsp; <b>1st User login</b> saved successfuly.<br>';
			}else{
				$err_msg .= '<span style="color:#c00"><i class="fa fa-times-circle"></i><b>&nbsp;&nbsp;Saving 1st User login failed. Error : </b>'.mysqli_error($dba).'</span><br>';
				$error = true;
			}
		}else{
			$err_msg .= '<i class="fa fa-check-square-o"></i>&nbsp;&nbsp;This user exist already in the database.<br>';
		}
	}else{
		$sql = "INSERT INTO rego_company_users (username, password, access, type, firstname, lastname, name, img, status, last, visit) VALUES ("; 
			$sql .= "'".$dba->real_escape_string(strtolower($_REQUEST['email']))."',";
			$sql .= "'".$dba->real_escape_string($password)."',";
			$sql .= "'".$dba->real_escape_string($cid)."',";
			$sql .= "'".$dba->real_escape_string('sys')."',"; // FIRST
			$sql .= "'".$dba->real_escape_string($_REQUEST['firstname'])."',";
			$sql .= "'".$dba->real_escape_string($_REQUEST['lastname'])."',";
			$sql .= "'".$dba->real_escape_string($_REQUEST['firstname'].' '.$_REQUEST['lastname'])."',";
			$sql .= "'".$dba->real_escape_string('images/profile_image.jpg')."',";
			$sql .= "'".$dba->real_escape_string(1)."',";
			$sql .= "'".$dba->real_escape_string($cid)."',";
			$sql .= "'".$dba->real_escape_string($visit)."')";
		if($dba->query($sql)){
			$err_msg .= '<i class="fa fa-check-square-o"></i>&nbsp; <b>1st User login</b> saved successfuly.<br>';
		}else{
			$err_msg .= '<span style="color:#c00"><i class="fa fa-times-circle"></i><b>&nbsp;&nbsp;Saving 1st User login failed. Error : </b>'.mysqli_error($dba).'</span><br>';
			$error = true;
		}
	}
	
	//echo $err_msg;	exit;
	
	if(!$error){
		
		$joiningdate = date('d-m-Y');
		$period_start = $joiningdate;
		if($_REQUEST['version'] == '0'){
			$expiredate = date('d-m-Y', strtotime('+1 months', strtotime($period_start)));
		}else{
			$expiredate = date('d-m-Y', strtotime('+12 months', strtotime($period_start)));
		}
		$period_end = $expiredate;
		
	$sql = "INSERT INTO rego_customers (clientID, th_compname, en_compname, th_address, en_address, tax_id, comp_phone, comp_fax, comp_email, firstname, lastname, name, position, phone, email, joiningdate, expiredate, period_start, period_end, version, employees, emp_platform, status, remarks) VALUES (
		'".$dba->real_escape_string($cid)."', 
		'".$dba->real_escape_string($_REQUEST['th_compname'])."', 
		'".$dba->real_escape_string($_REQUEST['en_compname'])."', 
		'".$dba->real_escape_string($_REQUEST['th_address'])."', 
		'".$dba->real_escape_string($_REQUEST['en_address'])."', 
		'".$dba->real_escape_string($_REQUEST['tax_id'])."', 
		'".$dba->real_escape_string($_REQUEST['comp_phone'])."', 
		'".$dba->real_escape_string($_REQUEST['comp_fax'])."', 
		'".$dba->real_escape_string(strtolower($_REQUEST['comp_email']))."', 
		'".$dba->real_escape_string($_REQUEST['firstname'])."', 
		'".$dba->real_escape_string($_REQUEST['lastname'])."', 
		'".$dba->real_escape_string($_REQUEST['firstname'].' '.$_REQUEST['lastname'])."', 
		'".$dba->real_escape_string($_REQUEST['position'])."', 
		'".$dba->real_escape_string($_REQUEST['phone'])."', 
		'".$dba->real_escape_string(strtolower($_REQUEST['email']))."', 
		'".$dba->real_escape_string($joiningdate)."', 
		'".$dba->real_escape_string($expiredate)."', 
		'".$dba->real_escape_string($period_start)."', 
		'".$dba->real_escape_string($period_end)."', 
		'".$dba->real_escape_string($_REQUEST['version'])."', 
		'".$dba->real_escape_string($_REQUEST['employees'])."', 
		'".$dba->real_escape_string($_REQUEST['emp_platform'])."', 
		'".$dba->real_escape_string(1)."', 
		'".$dba->real_escape_string($_REQUEST['remarks'])."')"; 
		
		if(!$res = $dba->query($sql)){
			$msg = '<div style="background:#a00; color:#fff; font-size:16px; font-weight:600; margin:5px 10px 10px 0; padding:5px 10px"><i class="fa fa-times-circle"></i>&nbsp; Error saving new customer.<br><span style="font-size:13px; font-weight:400"><b>Error :</b> '.mysqli_error($dba).'</span></div>';
		}else{
			$protocol = 'http://';
			if(isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on') { $protocol = 'https://';}
			if(!$exist){
				$template = getEmailTemplate('NEW_COMPANY');
				$txt = $template['body'];
				$text = str_replace('{RECIPIENT}', $_REQUEST['firstname'], $txt);
				$text = str_replace('{COMPANY}', $_REQUEST[$lang.'_compname'], $text);
				$text = str_replace('{USERNAME}', $_REQUEST['email'], $text);
				$text = str_replace('{PASSWORD}', $password_txt, $text);
				$text = str_replace('{CLICK_HERE_LINK}', $protocol.$_SERVER['SERVER_NAME'], $text);
				$text = str_replace('{SIGNATURE}', 'REGO HR Team', $text);
				//var_dump($template); //exit;
				//var_dump($text); //exit;
			}else{
				$template = getEmailTemplate('EXISTING_USER');
				$txt = $template['body'];
				$text = str_replace('{RECIPIENT}', $_REQUEST['firstname'], $txt);
				$text = str_replace('{COMPANY}', $_REQUEST[$lang.'_compname'], $text);
				$text = str_replace('{CLICK_HERE_LINK}', $protocol.$_SERVER['SERVER_NAME'], $text);
				$text = str_replace('{SIGNATURE}', 'REGO HR Team', $text);
				//var_dump($template); exit;
			}
			// notify New User -------------------------------------------------------------------------------
			require DIR.'PHPMailer/PHPMailerAutoload.php';	
			$mail_subject = 'New user Login for '.$_REQUEST['firstname'].' '.$_REQUEST['lastname'];
			$body = '<html>
							<head>
							<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
							</head>
							<body style="font-size:16px">'.nl2br($text).'</body>
						</html>';
			
			$mail = new PHPMailer;
			$mail->CharSet = 'UTF-8';
			$mail->From = $compinfo['admin_mail'];
			$mail->FromName = 'REGO HR Admin';
			$mail->addAddress(strtolower($_REQUEST['email']), $_REQUEST['firstname'].' '.$_REQUEST['lastname']); 
			$mail->isHTML(true);                                  
			$mail->Subject = $mail_subject;
			$mail->Body = $body;
			$mail->WordWrap = 100;
			if(!$mail->send()) {
				$err_msg = $mail->ErrorInfo;
			}else{
				$err_msg .= '<i class="fa fa-check-square-o"></i>&nbsp;&nbsp;eMail send to <b>'.$_REQUEST['firstname'].' '.$_REQUEST['lastname'].'</b>.<br>';
			}
			
			$msg = '<div style="background:green; color:#fff; font-size:16px; font-weight:600; margin:5px 10px 15px 0; padding:5px 10px"><i class="fa fa-check-square-o"></i>&nbsp; New customer created successfuly.</div>';
			
		}

	}else{
		$msg = '<div style="background:#a00; color:#fff; font-size:14px; font-weight:600; margin:5px 10px 15px 0; padding:5px 10px"><i class="fa fa-times-circle"></i>&nbsp; Sorry but something went wrong, please contact the site administrator.</div>';
	} // END If no errors save new client in database
	
	ob_clean();
	if(isset($_REQUEST['token'])){
		echo 'success';
		//echo $msg.$err_msg;
	}else{
		echo $msg.$err_msg;
	}
	exit;
	
?>













