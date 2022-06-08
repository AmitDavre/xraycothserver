<?
	if(session_id()==''){session_start();}
	ob_start();
	include("../dbconnect/db_connect.php");
	include(DIR."admin/files/admin_functions.php");
	
	$cid = getNewCustomerID();
	//$cid = 'rego0105';//strtolower($_REQUEST['clientID']);
	//$_REQUEST['company'] = strtoupper($cid).' - Your company name';
	//$_REQUEST['firstname'] = 'Pascal';
	//$_REQUEST['lastname'] = 'Baetens';
	//$_REQUEST['email'] = 'admin@root.com';
	//$_REQUEST['version'] = 10;
	//var_dump($cid); //exit;
	//var_dump($_REQUEST); exit;
	if(empty($_REQUEST['company'])){
		$_REQUEST['company'] = $_REQUEST['firstname'].' '.$_REQUEST['lastname'];
	}
	
	$error = false;
	$year = date('Y');
	//var_dump($_REQUEST); exit;
	
	if($res = $dba->query("SELECT * FROM rego_customers WHERE clientID = '".$cid."'")){
		if($res->num_rows > 0){
			echo '<div class="msg_error">Client ID <b>'.strtoupper($cid).'</b> exist already !</div>'; 
			exit;
		}
	}

	$err_msg = '<div style="font-weight:400;line-height:20px;"><div style="color:#a00; font-size:16px; font-weight:600; border-bottom:1px solid #ccc; margin:0 10px 2px 0;">Create Folders</div>';
	
	$dir = '../../';
	$uploadmap[] = $cid;
	$uploadmap[] = $cid.'/archive';
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
	}
	
	/*$sql = "INSERT INTO ".$cid."_company_settings (id, en_compname, th_compname, branch, th_address, en_address, tax_id, comp_phone, comp_fax, comp_email, logofile, positions) VALUES (
			'".$dbc->real_escape_string(1)."',
			'".$dbc->real_escape_string($_REQUEST['en_compname'])."',
			'".$dbc->real_escape_string($_REQUEST['th_compname'])."',
			'".$dbc->real_escape_string($_REQUEST['branch'])."',
			'".$dbc->real_escape_string($_REQUEST['th_address'])."', 
			'".$dbc->real_escape_string($_REQUEST['en_address'])."', 
			'".$dbc->real_escape_string($_REQUEST['tax_id'])."', 
			'".$dbc->real_escape_string($_REQUEST['comp_phone'])."', 
			'".$dbc->real_escape_string($_REQUEST['comp_fax'])."', 
			'".$dbc->real_escape_string($_REQUEST['comp_email'])."', 
			'".$dbc->real_escape_string('images/xray_logo.png')."', 
			'".$dbc->real_escape_string($data['positions'])."') 
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
			$err_msg .= '<span style="color:#c00"><i class="fa fa-times-circle"></i>&nbsp; Saving <b>Default company settings</b> failed. Error : Database <b>'.mysqli_error($dbc).'</b> does not exist.</span><br>';
			
			$error = true;
		}else{
			$err_msg .= '<i class="fa fa-check-square-o"></i>&nbsp; <b>Default company settings</b> saved successfuly.<br>';
		}*/
	
	$sql = "INSERT INTO ".$cid."_company_settings (id, en_compname, th_compname, logofile, positions) VALUES (
			'".$dbc->real_escape_string(1)."',
			'".$dbc->real_escape_string($_REQUEST['company'])."',
			'".$dbc->real_escape_string($_REQUEST['company'])."',
			'".$dbc->real_escape_string('images/xray_logo.png')."', 
			'".$dbc->real_escape_string($data['positions'])."') 
				ON DUPLICATE KEY UPDATE 
				en_compname = VALUES(en_compname),
				th_compname = VALUES(th_compname),
				logofile = VALUES(logofile),
				positions = VALUES(positions)";
		if(!$dbc->query($sql)){
			$err_msg .= '<span style="color:#c00"><i class="fa fa-times-circle"></i>&nbsp; Saving <b>Default company settings</b> failed. Error : Database <b>'.mysqli_error($dbc).'</b> does not exist.</span><br>';
			
			$error = true;
		}else{
			$err_msg .= '<i class="fa fa-check-square-o"></i>&nbsp; <b>Default company settings</b> saved successfuly.<br>';
		}
	
	$sql = "INSERT INTO ".$cid."_settings (id, pvf_rate_employee, pvf_rate_employer, tax_calc_method, fix_allow, var_allow, payslip_template, payslip_rate, payslip_field, support_email, bonus_payinmonth) VALUES (
			'".$dbc->real_escape_string(1)."',
			'".$dbc->real_escape_string($data['pvf_rate_employee'])."',
			'".$dbc->real_escape_string($data['pvf_rate_employer'])."',
			'".$dbc->real_escape_string($data['tax_calc_method'])."',
			'".$dbc->real_escape_string($data['fix_allow'])."',
			'".$dbc->real_escape_string($data['var_allow'])."', 
			'".$dbc->real_escape_string($data['payslip_template'])."', 
			'".$dbc->real_escape_string($data['payslip_rate'])."', 
			'".$dbc->real_escape_string($data['payslip_field'])."', 
			'".$dbc->real_escape_string($data['support_email'])."', 
			'".$dbc->real_escape_string($data['bonus_payinmonth'])."') 
				ON DUPLICATE KEY UPDATE 
				id = VALUES(id)";
		if(!$dbc->query($sql)){
			$err_msg .= '<span style="color:#c00"><i class="fa fa-times-circle"></i>&nbsp; Saving <b>Default settings</b> failed. Error : '.mysqli_error($dbc).'</span><br>';
			$error = true;
		}else{
			$err_msg .= '<i class="fa fa-check-square-o"></i>&nbsp; <b>Default settings</b> saved successfuly.<br>';
		}
	
	$res = $dba->query("SELECT * FROM rego_company_users WHERE username = '".$_REQUEST['email']."'");
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
		$sql = "INSERT INTO rego_company_users (username, password, access, type, firstname, lastname, name, img, status, last) VALUES ("; 
			$sql .= "'".$dba->real_escape_string(strtolower($_REQUEST['email']))."',";
			$sql .= "'".$dba->real_escape_string(hash('sha256', strtolower($_REQUEST['email'])))."',";
			$sql .= "'".$dba->real_escape_string($cid)."',";
			$sql .= "'".$dba->real_escape_string('sys')."',";
			$sql .= "'".$dba->real_escape_string($_REQUEST['firstname'])."',";
			$sql .= "'".$dba->real_escape_string($_REQUEST['lastname'])."',";
			$sql .= "'".$dba->real_escape_string($_REQUEST['firstname'].' '.$_REQUEST['lastname'])."',";
			$sql .= "'".$dba->real_escape_string('images/profile_image.jpg')."',";
			$sql .= "'".$dba->real_escape_string(1)."',";
			$sql .= "'".$dba->real_escape_string($cid)."')";
		if($dba->query($sql)){
			$err_msg .= '<i class="fa fa-check-square-o"></i>&nbsp; <b>1st User login</b> saved successfuly.<br>';
		}else{
			$err_msg .= '<span style="color:#c00"><i class="fa fa-times-circle"></i><b>&nbsp;&nbsp;Saving 1st User login failed. Error : </b>'.mysqli_error($dba).'</span><br>';
			$error = true;
		}
	}
	
	//echo $err_msg;	exit;
	
	if(!$error){
		
	/*$sql = "INSERT INTO rego_customers (clientID, th_compname, en_compname, branch, th_address, en_address, tax_id, comp_phone, comp_fax, comp_email, firstname, lastname, name, position, phone, email, joiningdate, version, employees, status, remarks) VALUES (
		'".$dba->real_escape_string($cid)."', 
		'".$dba->real_escape_string($_REQUEST['th_compname'])."', 
		'".$dba->real_escape_string($_REQUEST['en_compname'])."', 
		'".$dba->real_escape_string($_REQUEST['branch'])."', 
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
		'".$dba->real_escape_string($_REQUEST['joiningdate'])."', 
		'".$dba->real_escape_string($_REQUEST['version'])."', 
		'".$dba->real_escape_string($_REQUEST['employees'])."', 
		'".$dba->real_escape_string(1)."', 
		'".$dba->real_escape_string($_REQUEST['remarks'])."')"; */
		
	$sql = "INSERT INTO rego_customers (clientID, th_compname, en_compname, firstname, lastname, name, email, phone, joiningdate, version, status) VALUES (
		'".$dba->real_escape_string($cid)."', 
		'".$dba->real_escape_string($cid)."', 
		'".$dba->real_escape_string($cid)."', 
		'".$dba->real_escape_string($_REQUEST['firstname'])."', 
		'".$dba->real_escape_string($_REQUEST['lastname'])."', 
		'".$dba->real_escape_string($_REQUEST['firstname'].' '.$_REQUEST['lastname'])."', 
		'".$dba->real_escape_string(strtolower($_REQUEST['email']))."', 
		'".$dba->real_escape_string($_REQUEST['phone'])."', 
		'".$dba->real_escape_string(date('d-m-Y'))."', 
		'".$dba->real_escape_string(0)."', 
		'".$dba->real_escape_string(1)."')"; 
		
		if(!$res = $dba->query($sql)){
			$msg = '<div style="background:#a00; color:#fff; font-size:16px; font-weight:600; margin:5px 10px 10px 0; padding:5px 10px"><i class="fa fa-times-circle"></i>&nbsp; Error saving new customer.<br><span style="font-size:13px; font-weight:400"><b>Error :</b> '.mysqli_error($dba).'</span></div>';
		}else{
			$protocol = 'http://';
			if(isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on') { $protocol = 'https://';}
			if(!$exist){
				$template = getEmailTemplate('NEW_COMPANY');
				$txt = $template['body'];
				$text = str_replace('{RECIPIENT}', $_REQUEST['firstname'], $txt);
				//$text = str_replace('{COMPANY}', $_REQUEST[$lang.'_compname'], $text);
				$text = str_replace('{COMPANY}', $_REQUEST['company'], $text);
				$text = str_replace('{USERNAME}', '<a href="#" style="text-decoration:none; color:#000; cursor:text">'.$_REQUEST['email'].'</a>', $text);
				$text = str_replace('{PASSWORD}', '<a href="#" style="text-decoration:none; color:#000; cursor:text">'.$_REQUEST['email'].'</a>', $text);
				$text = str_replace('{CLICK_HERE_LINK}', $protocol.$_SERVER['SERVER_NAME'], $text);
				$text = str_replace('{SIGNATURE}', 'REGO HR Team', $text);
				//var_dump($template); exit;
			}else{
				$template = getEmailTemplate('EXISTING_USER');
				$txt = $template['body'];
				$text = str_replace('{RECIPIENT}', $_REQUEST['firstname'], $txt);
				//$text = str_replace('{COMPANY}', $_REQUEST[$lang.'_compname'], $text);
				$text = str_replace('{COMPANY}', $_REQUEST['company'], $text);
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
				//$err_msg = $mail->ErrorInfo;
			}else{
				$err_msg .= '<i class="fa fa-check-square-o"></i>&nbsp;&nbsp;eMail send to <b>'.$_REQUEST['firstname'].' '.$_REQUEST['lastname'].'</b>.<br>';
			}
			
			$msg = '<div style="background:green; color:#fff; font-size:16px; font-weight:600; margin:5px 10px 15px 0; padding:5px 10px"><i class="fa fa-check-square-o"></i>&nbsp; New customer created successfuly.</div>';
			
		}

	}else{
		$msg = '<div style="background:#a00; color:#fff; font-size:14px; font-weight:600; margin:5px 10px 15px 0; padding:5px 10px"><i class="fa fa-times-circle"></i>&nbsp; Sorry but something went wrong, please contact the site administrator.</div>';
	} // END If no errors save new client in database
	
	echo $msg.$err_msg;
	exit;
	
?>













