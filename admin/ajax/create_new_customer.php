<?
	header("Access-Control-Allow-Origin: *");
	
	if(session_id()==''){session_start();}
	ob_start();
	include("../dbconnect/db_connect.php");
	include(DIR."admin/files/admin_functions.php");
	//var_dump($_REQUEST); exit;


	$error = false;
	
	if(!filter_var($_REQUEST['email'], FILTER_VALIDATE_EMAIL)) {
		echo 'email';
		exit;
	}
	
	$cid = getFirstCustomerID();
	//var_dump($cid); exit;
	
	if(!isset($_REQUEST['agent'])){$_REQUEST['agent'] = 'Admin';}
	if(isset($_REQUEST['pass1'])){
		$_REQUEST['password'] = hash('sha256', $_REQUEST['pass1']);
		unset($_REQUEST['pass1'], $_REQUEST['pass2']);
	}
	$_REQUEST['email'] = strtolower(preg_replace('/\s+/', '', $_REQUEST['email']));
	
	$password = '';
	//$visit = 1;
	if(strlen($_REQUEST['password']) < 100){
		$password = $_REQUEST['password'];
		$_REQUEST['password'] = hash('sha256', $password);
		//$visit = 0;
	}
	//$_REQUEST['password'] = 'cd2eb0837c9b4c962c22d2ff8b5441b7b45805887f051d39bf133b583baf6860';
	
	
	$address = '';
	if(!empty($_REQUEST['address'])){
		$address .= $_REQUEST['address'].PHP_EOL;
		$address .= $_REQUEST['subdistrict'].' '.$_REQUEST['district'].PHP_EOL;
		$address .= $_REQUEST['province'].' '.$_REQUEST['postcode'];
	}
	$_REQUEST['en_billing'] = $address;
	$_REQUEST['th_billing'] = $address;
	$_REQUEST['comp_phone'] = $_REQUEST['phone'];
	$_REQUEST['comp_fax'] = '';
	$_REQUEST['comp_email'] = $_REQUEST['email'];
	$_REQUEST['joiningdate'] = $_REQUEST['date'];
	$_REQUEST['price_year'] = 0;
	$_REQUEST['price'] = 0;
	$_REQUEST['discount'] = 0;
	$_REQUEST['vat'] = 0;
	$_REQUEST['wht'] = $_REQUEST['certificate'];
	$_REQUEST['net'] = 0;
	$_REQUEST['emp_platform'] = 1;
	if($_REQUEST['version'] == 10){$_REQUEST['emp_platform'] = 0;}
	//$_REQUEST['wht'] = 0;
	//if($_REQUEST['certificate'] == 'Y'){$_REQUEST['wht'] = 1;}
	//$_REQUEST['position'] = '';
	$_REQUEST['email'] = preg_replace('/\s+/', '', strtolower($_REQUEST['email']));
	$_REQUEST['username'] = $_REQUEST['email'];
	
	$year = date('Y');

	//var_dump($_REQUEST); exit;


	
	$err_msg = '';
	
	$dir = '../../';
	$uploadmap[] = $cid;
	$uploadmap[] = $cid.'/approvals';
	$uploadmap[] = $cid.'/archive';
	$uploadmap[] = $cid.'/documents';
	$uploadmap[] = $cid.'/employees/img';
	$uploadmap[] = $cid.'/gov_forms';
	$uploadmap[] = $cid.'/leave';
	$uploadmap[] = $cid.'/payroll';
	$uploadmap[] = $cid.'/reports';
	$uploadmap[] = $cid.'/time';
	$uploadmap[] = $cid.'/uploads';

	foreach($uploadmap as $key=>$val){
		if(!file_exists($dir.$val)) {
			$oldmask = umask(0);
			if(!mkdir($dir.$val, 0777, true)){
				$err_msg .= '<b style="color:#c00"><i class="fa fa-times-circle"></i>&nbsp; '.$lng['Create subdirectory'].' '.$val.' '.$lng['failed'].'</b><br>';
				$error = true;
			}
			umask($oldmask);
		}
	}
	//echo $err_msg; exit;
	include("create_database.php");
	//echo $err_msg; exit;

	$err_msg .= '<div style="color:#a00; font-size:16px; font-weight:600; border-bottom:1px solid #ccc; margin:5px 10px 2px 0">Update Databases</div>';

	$data = array();
	$res = $dba->query("SELECT * FROM rego_default_settings");
	if($row = $res->fetch_assoc()){
		$data = $row;
	}
	//var_dump($data); exit;
	
	$sql = "INSERT INTO ".$cid."_company_settings (id, en_compname, th_compname, billing_th, billing_en, tax_id, wht, email, logofile, logtime, txt_color) VALUES (
			'".$dbc->real_escape_string(1)."',
			'".$dbc->real_escape_string($_REQUEST['en_compname'])."',
			'".$dbc->real_escape_string($_REQUEST['th_compname'])."',
			'".$dbc->real_escape_string($_REQUEST['th_billing'])."', 
			'".$dbc->real_escape_string($_REQUEST['en_billing'])."',
			'".$dbc->real_escape_string($_REQUEST['tax_id'])."', 
			'".$dbc->real_escape_string($_REQUEST['wht'])."', 
			'".$dbc->real_escape_string($_REQUEST['email'])."', 
			'".$dbc->real_escape_string($default_logo)."', 
			'".$dbc->real_escape_string(3600)."', 
			'".$dbc->real_escape_string('red')."') 
				ON DUPLICATE KEY UPDATE 
				en_compname = VALUES(en_compname),
				th_compname = VALUES(th_compname),
				billing_th = VALUES(billing_th),
				billing_en = VALUES(billing_en),
				tax_id = VALUES(tax_id),
				wht = VALUES(wht),
				email = VALUES(email),
				logofile = VALUES(logofile),
				logtime = VALUES(logtime),
				txt_color = VALUES(txt_color)";
		if(!$dbc->query($sql)){
			$err_msg .= '<span style="color:#c00"><i class="fa fa-times-circle"></i>&nbsp; Saving <b>Default company settings</b> failed. Error : Database <b>'.mysqli_error($dbc).'</b></span><br>';
			$error = true;
		}else{
			$err_msg .= '<i class="fa fa-check-square-o"></i>&nbsp; <b>Default company settings</b> saved successfuly.<br>';
		}
	
	$sso_codes[1]['code'] = '000000';
	$sso_codes[1]['th'] = 'สำนักงานใหญ่';
	$sso_codes[1]['en'] = 'Head office';
	$sso_codes[1]['line1_th'] = '';
	$sso_codes[1]['line2_th'] = '';
	$sso_codes[1]['postal_th'] = '';
	$sso_codes[1]['line1_en'] = '';
	$sso_codes[1]['line2_en'] = '';
	$sso_codes[1]['postal_en'] = '';
	
	$sql = "INSERT INTO ".$cid."_entities_data (ref, code, revenu_branch, en_compname, th_compname, en_addr_detail, th_addr_detail, sso_codes, tax_id, comp_email, logofile) VALUES (
			'".$dbc->real_escape_string(1)."',
			'".$dbc->real_escape_string('MAIN')."',
			'".$dbc->real_escape_string('00000')."',
			'".$dbc->real_escape_string($_REQUEST['en_compname'])."',
			'".$dbc->real_escape_string($_REQUEST['th_compname'])."',
			'".$dbc->real_escape_string('a:13:{s:8:"building";s:0:"";s:7:"village";s:0:"";s:4:"room";s:0:"";s:5:"floor";s:0:"";s:6:"number";s:0:"";s:3:"moo";s:0:"";s:4:"lane";s:0:"";s:4:"road";s:0:"";s:11:"subdistrict";s:0:"";s:8:"district";s:0:"";s:8:"province";s:0:"";s:6:"postal";s:0:"";s:7:"country";s:0:"";}')."',
			'".$dbc->real_escape_string('a:13:{s:8:"building";s:0:"";s:7:"village";s:0:"";s:4:"room";s:0:"";s:5:"floor";s:0:"";s:6:"number";s:0:"";s:3:"moo";s:0:"";s:4:"lane";s:0:"";s:4:"road";s:0:"";s:11:"subdistrict";s:0:"";s:8:"district";s:0:"";s:8:"province";s:0:"";s:6:"postal";s:0:"";s:7:"country";s:0:"";}')."',
			'".$dbc->real_escape_string(serialize($sso_codes))."',
			'".$dbc->real_escape_string($_REQUEST['tax_id'])."', 
			'".$dbc->real_escape_string($_REQUEST['email'])."', 
			'".$dbc->real_escape_string($default_logo)."') 
				ON DUPLICATE KEY UPDATE 
				code = VALUES(code),
				en_compname = VALUES(en_compname),
				th_compname = VALUES(th_compname),
				tax_id = VALUES(tax_id),
				comp_email = VALUES(comp_email),
				logofile = VALUES(logofile)";
		if(!$dbc->query($sql)){
			$err_msg .= '<span style="color:#c00"><i class="fa fa-times-circle"></i>&nbsp; Saving <b>Default Entity data</b> failed. Error : Database <b>'.mysqli_error($dbc).'</b></span><br>';
			$error = true;
		}else{
			$err_msg .= '<i class="fa fa-check-square-o"></i>&nbsp; <b>Default Entity data</b> saved successfuly.<br>';
		}
	
	$teamdata = 'a:3:{s:2:"en";a:1:{s:4:"MAIN";s:9:"Team #001";}s:2:"th";a:1:{s:4:"MAIN";s:9:"Team #001";}s:7:"code_id";a:1:{s:4:"MAIN";s:4:"MAIN";}}';


	$sql = "INSERT INTO ".$cid."_sys_settings (id, cur_month, cur_year, years, pr_startdate, fix_allow, var_allow, fix_deduct, var_deduct, payslip_template, payslip_rate, payslip_field, support_email, account_codes, auto_id, id_start, scan_id, id_prefix, joining_date, team,teams,shiftplan_schedule,teams_name, emp_group, emp_type, emp_status, account_code, position, date_start, time_reg, selfie, leeve, pay_type, calc_psf, psf_rate_emp, psf_rate_com, calc_pvf, pvf_rate_emp, pvf_rate_com, calc_method, calc_tax, calc_sso, contract_type, calc_base, base_ot_rate, ot_rate) VALUES (
			'".$dbc->real_escape_string(1)."',
			'".$dbc->real_escape_string(1)."',
			'".$dbc->real_escape_string(date('Y'))."',
			'".$dbc->real_escape_string(date('Y'))."',
			'".$dbc->real_escape_string('01-01-'.$year)."',
			'".$dbc->real_escape_string($data['fix_allow'])."',
			'".$dbc->real_escape_string($data['var_allow'])."', 
			'".$dbc->real_escape_string($data['fix_deduct'])."',
			'".$dbc->real_escape_string($data['var_deduct'])."', 
			'".$dbc->real_escape_string($data['payslip_template'])."', 
			'".$dbc->real_escape_string($data['payslip_rate'])."', 
			'".$dbc->real_escape_string($data['payslip_field'])."', 
			'".$dbc->real_escape_string($data['support_email'])."', 
			'".$dbc->real_escape_string($data['account_codes'])."', 
			'".$dbc->real_escape_string($data['auto_id'])."', 
			'".$dbc->real_escape_string($data['id_start'])."', 
			'".$dbc->real_escape_string($data['scan_id'])."', 
			'".$dbc->real_escape_string($data['id_prefix'])."', 
			'".$dbc->real_escape_string($data['joining_date'])."', 
			'".$dbc->real_escape_string($data['team'])."', 
			'".$dbc->real_escape_string($teamdata)."', 
			'".$dbc->real_escape_string($data['shiftplan_schedule'])."', 
			'".$dbc->real_escape_string($data['teams_name'])."', 
			'".$dbc->real_escape_string($data['emp_group'])."', 
			'".$dbc->real_escape_string($data['emp_type'])."', 
			'".$dbc->real_escape_string($data['emp_status'])."', 
			'".$dbc->real_escape_string($data['account_code'])."', 
			'".$dbc->real_escape_string($data['position'])."', 
			'".$dbc->real_escape_string($data['date_start'])."', 
			'".$dbc->real_escape_string($data['time_reg'])."', 
			'".$dbc->real_escape_string($data['selfie'])."', 
			'".$dbc->real_escape_string($data['leeve'])."', 
			'".$dbc->real_escape_string($data['pay_type'])."', 
			'".$dbc->real_escape_string($data['calc_psf'])."', 
			'".$dbc->real_escape_string($data['psf_rate_emp'])."', 
			'".$dbc->real_escape_string($data['psf_rate_com'])."', 
			'".$dbc->real_escape_string($data['calc_pvf'])."', 
			'".$dbc->real_escape_string($data['pvf_rate_emp'])."', 
			'".$dbc->real_escape_string($data['pvf_rate_com'])."', 
			'".$dbc->real_escape_string($data['calc_method'])."', 
			'".$dbc->real_escape_string($data['calc_tax'])."', 
			'".$dbc->real_escape_string($data['calc_sso'])."', 
			'".$dbc->real_escape_string($data['contract_type'])."', 
			'".$dbc->real_escape_string($data['calc_base'])."', 
			'".$dbc->real_escape_string($data['base_ot_rate'])."', 
			'".$dbc->real_escape_string($data['ot_rate'])."') 
				ON DUPLICATE KEY UPDATE 
				id = VALUES(id)";
		if(!$dbc->query($sql)){
			$err_msg .= '<span style="color:#c00"><i class="fa fa-times-circle"></i>&nbsp; Saving <b>Default settings</b> failed. Error : '.mysqli_error($dbc).'</span><br>';
			$error = true;
		}else{
			$err_msg .= '<i class="fa fa-check-square-o"></i>&nbsp; <b>Default settings</b> saved successfuly.<br>';
		}
		
		$sql = "INSERT INTO ".$cid."_payroll_months (month, time_start, time_end, leave_start, leave_end, payroll_start, payroll_end, paydate, formdate, sso_eRate, sso_eMax, sso_eMin, sso_cRate, sso_cMax, sso_cMin, wht, sso_act_max) VALUES ";
		for($i=1;$i<=12;$i++){
			$last = date('t', strtotime($year.'-'.sprintf('%02d', $i).'-01'));
			$date = $last.'-'.sprintf('%02d', $i).'-'.$year;
			$start = '26-'.sprintf('%02d', ($i-1)).'-'.$year;
			$end = '25-'.sprintf('%02d', $i).'-'.$year;
			if($i == 1){$start = '26-12-'.($year-1);}
			$sql .= "('".$dbc->real_escape_string($year.'_'.$i)."',";
			$sql .= "'".$dbc->real_escape_string($start)."',";
			$sql .= "'".$dbc->real_escape_string($end)."',";
			$sql .= "'".$dbc->real_escape_string($start)."',";
			$sql .= "'".$dbc->real_escape_string($end)."',";
			$sql .= "'".$dbc->real_escape_string($start)."',";
			$sql .= "'".$dbc->real_escape_string($end)."',";
			$sql .= "'".$dbc->real_escape_string($date)."',";
			$sql .= "'".$dbc->real_escape_string($date)."',";
			$sql .= "'".$dbc->real_escape_string($data['sso_rate_emp'])."',";
			$sql .= "'".$dbc->real_escape_string($data['sso_max_emp'])."',";
			$sql .= "'".$dbc->real_escape_string($data['sso_min_emp'])."',";
			$sql .= "'".$dbc->real_escape_string($data['sso_rate_com'])."',";
			$sql .= "'".$dbc->real_escape_string($data['sso_max_com'])."',";
			$sql .= "'".$dbc->real_escape_string($data['sso_min_com'])."',";
			$sql .= "'".$dbc->real_escape_string(3)."',";
			$sql .= "'".$dbc->real_escape_string($data['sso_act_max'])."'),";
		}
		$sql = substr($sql, 0, -1)." ON DUPLICATE KEY UPDATE 
			month = VALUES(month)";
		if(!$dbc->query($sql)){
			$err_msg .= '<span style="color:#c00"><i class="fa fa-times-circle"></i>&nbsp; Saving <b>Payroll months</b> failed. Error : '.mysqli_error($dbc).'</span><br>';
			$error = true;
		}else{
			$err_msg .= '<i class="fa fa-check-square-o"></i>&nbsp; <b>Payroll months</b> saved successfuly.<br>';
		}

	$sql = "INSERT INTO ".$cid."_entities (id, code, th, en) VALUES (
			'".$dbc->real_escape_string(1)."',
			'".$dbc->real_escape_string('MAIN')."',
			'".$dbc->real_escape_string('เอนทิตี #001')."',
			'".$dbc->real_escape_string('Entity #001')."')
				ON DUPLICATE KEY UPDATE 
				code = VALUES(code),
				th = VALUES(th),
				en = VALUES(en)";
		if(!$dbc->query($sql)){
			$err_msg .= '<span style="color:#c00"><i class="fa fa-times-circle"></i>&nbsp; Saving <b>Default Entity</b> failed. Error : Database <b>'.mysqli_error($dbc).'</b></span><br>';
			$error = true;
		}else{
			$err_msg .= '<i class="fa fa-check-square-o"></i>&nbsp; <b>Default Entity</b> saved successfuly.<br>';
		}
	
	$sql = "INSERT INTO ".$cid."_branches (id, code, th, en, entity) VALUES (
			'".$dbc->real_escape_string(1)."',
			'".$dbc->real_escape_string('HO')."',
			'".$dbc->real_escape_string('Head office')."',
			'".$dbc->real_escape_string('Head office')."',
			'".$dbc->real_escape_string(1)."')
				ON DUPLICATE KEY UPDATE 
				code = VALUES(code),
				th = VALUES(th),
				en = VALUES(en),
				entity = VALUES(entity)";
		if(!$dbc->query($sql)){
			$err_msg .= '<span style="color:#c00"><i class="fa fa-times-circle"></i>&nbsp; Saving <b>Default Branch</b> failed. Error : Database <b>'.mysqli_error($dbc).'</b></span><br>';
			$error = true;
		}else{
			$err_msg .= '<i class="fa fa-check-square-o"></i>&nbsp; <b>Default Branch</b> saved successfuly.<br>';
		}	


		$sql = "INSERT INTO ".$cid."_branches_data (ref, perimeter, gps, bra_name_th, bra_name_en) VALUES (
			'".$dbc->real_escape_string(1)."',
			'".$dbc->real_escape_string('0')."',
			'".$dbc->real_escape_string('0')."',
			'".$dbc->real_escape_string('Head office')."',
			'".$dbc->real_escape_string('Head office')."')";
		if(!$dbc->query($sql)){
			$err_msg .= '<span style="color:#c00"><i class="fa fa-times-circle"></i>&nbsp; Saving <b>Default Branch Data</b> failed. Error : Database <b>'.mysqli_error($dbc).'</b></span><br>';
			$error = true;
		}else{
			$err_msg .= '<i class="fa fa-check-square-o"></i>&nbsp; <b>Default Branch Data</b> saved successfuly.<br>';
		}
	
	$sql = "INSERT INTO ".$cid."_divisions (id, code, th, en) VALUES (
			'".$dbc->real_escape_string(1)."',
			'".$dbc->real_escape_string('NODIV')."',
			'".$dbc->real_escape_string('no Division')."',
			'".$dbc->real_escape_string('no Division')."')
				ON DUPLICATE KEY UPDATE 
				code = VALUES(code),
				th = VALUES(th),
				en = VALUES(en)";
		if(!$dbc->query($sql)){
			$err_msg .= '<span style="color:#c00"><i class="fa fa-times-circle"></i>&nbsp; Saving <b>Default Division</b> failed. Error : Database <b>'.mysqli_error($dbc).'</b></span><br>';
			$error = true;
		}else{
			$err_msg .= '<i class="fa fa-check-square-o"></i>&nbsp; <b>Default Division</b> saved successfuly.<br>';
		}
	
	$sql = "INSERT INTO ".$cid."_departments (id, code, th, en) VALUES (
			'".$dbc->real_escape_string(1)."',
			'".$dbc->real_escape_string('NODEP')."',
			'".$dbc->real_escape_string('no Department')."',
			'".$dbc->real_escape_string('no Department')."')
				ON DUPLICATE KEY UPDATE 
				code = VALUES(code),
				th = VALUES(th),
				en = VALUES(en)";
		if(!$dbc->query($sql)){
			$err_msg .= '<span style="color:#c00"><i class="fa fa-times-circle"></i>&nbsp; Saving <b>Default Department</b> failed. Error : Database <b>'.mysqli_error($dbc).'</b></span><br>';
			$error = true;
		}else{
			$err_msg .= '<i class="fa fa-check-square-o"></i>&nbsp; <b>Default Department</b> saved successfuly.<br>';
		}
	
	$sql = "INSERT INTO ".$cid."_positions (id, code, th, en) VALUES (
			'".$dbc->real_escape_string(1)."',
			'".$dbc->real_escape_string('NOPOS')."',
			'".$dbc->real_escape_string('ไม่มีตำแหน่ง')."',
			'".$dbc->real_escape_string('no Position')."')
				ON DUPLICATE KEY UPDATE 
				code = VALUES(code),
				th = VALUES(th),
				en = VALUES(en)";
		if(!$dbc->query($sql)){
			$err_msg .= '<span style="color:#c00"><i class="fa fa-times-circle"></i>&nbsp; Saving <b>Default Position</b> failed. Error : Database <b>'.mysqli_error($dbc).'</b></span><br>';
			$error = true;
		}else{
			$err_msg .= '<i class="fa fa-check-square-o"></i>&nbsp; <b>Default Position</b> saved successfuly.<br>';
		}
	
	$sql = "INSERT INTO ".$cid."_teams (id, code, th, en, entity, branch, division, department) VALUES (
			'".$dbc->real_escape_string(1)."',
			'".$dbc->real_escape_string('MAIN')."',
			'".$dbc->real_escape_string('Team #001')."',
			'".$dbc->real_escape_string('Team #001')."',
			'".$dbc->real_escape_string(1)."',
			'".$dbc->real_escape_string(1)."',
			'".$dbc->real_escape_string(1)."',
			'".$dbc->real_escape_string(1)."')
				ON DUPLICATE KEY UPDATE 
				code = VALUES(code),
				th = VALUES(th),
				en = VALUES(en),
				entity = VALUES(entity),
				branch = VALUES(branch),
				division = VALUES(division),
				department = VALUES(department)";
		if(!$dbc->query($sql)){
			$err_msg .= '<span style="color:#c00"><i class="fa fa-times-circle"></i>&nbsp; Saving <b>Default Team</b> failed. Error : Database <b>'.mysqli_error($dbc).'</b></span><br>';
			$error = true;
		}else{
			$err_msg .= '<i class="fa fa-check-square-o"></i>&nbsp; <b>Default Team</b> saved successfuly.<br>';
		}
	
	$last_id = false;
	$res = $dba->query("SELECT * FROM rego_all_users WHERE LOWER(username) = '".$_REQUEST['email']."'");
	$exist = $res->fetch_assoc();
	
	if($exist){

		//echo '1111'; exit;
		$last_id = $exist['id'];
		if(preg_match("/{$cid}/i", $exist['access']) == 0) {
			$access = $exist['access'] .= ','.$cid;
			if($res = $dba->query("UPDATE rego_all_users SET 
				access = '".$dba->real_escape_string($access)."', type='sys', sys_status='1' 
				WHERE username = '".$_REQUEST['email']."'")){
				$err_msg .= '<i class="fa fa-check-square-o"></i>&nbsp; <b>1st User login</b> saved successfuly.<br>';
			}else{
				$err_msg .= '<span style="color:#c00"><i class="fa fa-times-circle"></i><b>&nbsp;&nbsp;Saving 1st User login failed. Error : </b>'.mysqli_error($dba).'</span><br>';
				$error = true;
			}
		}else{
			$err_msg .= '<i class="fa fa-check-square-o"></i>&nbsp;&nbsp;This Subscriber exist already in the database.<br>';
		}
	}else{
		$sql = "INSERT INTO rego_all_users (username, password, access, last, sys_status, type) VALUES ("; 
			$sql .= "'".$dba->real_escape_string($_REQUEST['email'])."',";
			$sql .= "'".$dba->real_escape_string($_REQUEST['password'])."',";
			$sql .= "'".$dba->real_escape_string($cid)."',";
			$sql .= "'".$dba->real_escape_string($cid)."',";
			$sql .= "'".$dba->real_escape_string('1')."',";
			$sql .= "'".$dba->real_escape_string('sys')."')";
		//echo $sql;
		if($dba->query($sql)){
			$err_msg .= '<i class="fa fa-check-square-o"></i>&nbsp; <b>Subscriber</b> saved successfuly.<br>';
			$last_id = $dba->insert_id;
		}else{
			$err_msg .= '<span style="color:#c00"><i class="fa fa-times-circle"></i><b>&nbsp;&nbsp;Saving Subscriber failed. Error : </b>'.mysqli_error($dba).'</span><br>';
			$error = true;
		}
	
	}
	//echo $err_msg;	exit;
	//var_dump($last_id);
				
	$access_selection = '<tr><td></td><td class="vat">'.$lng['All Entities'].'</td><td class="vat">'.$lng['All Branches'].'</td><td class="vat">'.$lng['All Divisions'].'</td><td class="vat">'.$lng['All Departments'].'</td><td class="vat">'.$lng['All Teams'].'</td><td></td></tr>';
							
	if($last_id){
		include(DIR.'settings/ajax/sys_permissions.php');
		$sql = "INSERT INTO ".$cid."_users (ref, username, firstname, name, type, entities, branches, emp_group, divisions, departments, teams, permissions, access_selection, img, status) VALUES ("; 
			$sql .= "'".$dbc->real_escape_string($last_id)."',";
			$sql .= "'".$dbc->real_escape_string($_REQUEST['email'])."',";
			$sql .= "'".$dbc->real_escape_string($_REQUEST['firstname'])."',";
			$sql .= "'".$dbc->real_escape_string($_REQUEST['firstname'].' '.$_REQUEST['lastname'])."',";
			$sql .= "'".$dbc->real_escape_string('sys')."',";
			$sql .= "'".$dbc->real_escape_string('1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20')."',";
			$sql .= "'".$dbc->real_escape_string('1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20')."',";
			$sql .= "'".$dbc->real_escape_string('s')."',";
			$sql .= "'".$dbc->real_escape_string('1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20')."',";
			$sql .= "'".$dbc->real_escape_string('1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20')."',";
			$sql .= "'".$dbc->real_escape_string('1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23,24,25,26,27,28,29,30,31,32,33,34,35,36,37,38,39,40,41,42,43,44,45,46,47,48,49,50')."',";
			$sql .= "'".$dbc->real_escape_string($sys_permissions)."',";
			$sql .= "'".$dbc->real_escape_string($access_selection)."',";
			$sql .= "'".$dbc->real_escape_string('images/profile_image.jpg')."',";
			$sql .= "'".$dbc->real_escape_string(1)."')";
			//echo $sql;
		if(!$dbc->query($sql)){
			//ob_clean();
			echo mysqli_error($dbc);
		}else{
			//ob_clean();
			echo $last_id;
		}
	}
	//echo $err_msg;	exit;
	
	if(!$error){
		
	$sql = "INSERT INTO rego_customers (clientID, th_compname, en_compname, th_billing, en_billing, tax_id, certificate, price_year, comp_phone, comp_fax, comp_email, firstname, lastname, name, phone, email, joiningdate, expiredate, period_start, period_end, version, employees, price, discount, vat, wht, net, emp_platform, agent, status, remarks) VALUES (
		'".$dba->real_escape_string($cid)."', 
		'".$dba->real_escape_string($_REQUEST['th_compname'])."', 
		'".$dba->real_escape_string($_REQUEST['en_compname'])."', 
		'".$dba->real_escape_string($_REQUEST['th_billing'])."', 
		'".$dba->real_escape_string($_REQUEST['en_billing'])."', 
		'".$dba->real_escape_string($_REQUEST['tax_id'])."', 
		'".$dba->real_escape_string($_REQUEST['certificate'])."', 
		'".$dba->real_escape_string($_REQUEST['price_year'])."', 
		'".$dba->real_escape_string($_REQUEST['comp_phone'])."', 
		'".$dba->real_escape_string($_REQUEST['comp_fax'])."', 
		'".$dba->real_escape_string($_REQUEST['comp_email'])."', 
		'".$dba->real_escape_string($_REQUEST['firstname'])."', 
		'".$dba->real_escape_string($_REQUEST['lastname'])."', 
		'".$dba->real_escape_string($_REQUEST['firstname'].' '.$_REQUEST['lastname'])."', 
		'".$dba->real_escape_string($_REQUEST['phone'])."', 
		'".$dba->real_escape_string($_REQUEST['email'])."', 
		'".$dba->real_escape_string($_REQUEST['joiningdate'])."', 
		'".$dba->real_escape_string($_REQUEST['period_end'])."', 
		'".$dba->real_escape_string($_REQUEST['period_start'])."', 
		'".$dba->real_escape_string($_REQUEST['period_end'])."', 
		'".$dba->real_escape_string($_REQUEST['version'])."', 
		'".$dba->real_escape_string($_REQUEST['employees'])."', 
		'".$dba->real_escape_string($_REQUEST['price'])."', 
		'".$dba->real_escape_string($_REQUEST['discount'])."', 
		'".$dba->real_escape_string($_REQUEST['vat'])."', 
		'".$dba->real_escape_string($_REQUEST['wht'])."', 
		'".$dba->real_escape_string($_REQUEST['net'])."', 
		'".$dba->real_escape_string($_REQUEST['emp_platform'])."', 
		'".$dba->real_escape_string($_REQUEST['agent'])."', 
		'".$dba->real_escape_string(1)."', 
		'".$dba->real_escape_string($_REQUEST['remarks'])."')"; 
		
		if(!$res = $dba->query($sql)){
			
			$msg = '<div style="background:#a00; color:#fff; font-size:16px; font-weight:600; margin:5px 10px 10px 0; padding:5px 10px"><i class="fa fa-times-circle"></i>&nbsp; Error saving new customer.<br><span style="font-size:13px; font-weight:400"><b>Error :</b> '.mysqli_error($dba).'</span></div>';
		
		}else{
			
			/*if(isset($_REQUEST['admin']) && $_REQUEST['version'] > 0){
				unset($form['admin']);
				$form['clientID'] = $cid;
				$form['date'] = date('Y-m-d H:i:s');
				//var_dump($form);
				//exit;
				
				$sql = "INSERT INTO rego_waiting_customers (";
				foreach($form as $k=>$v){
					$sql .= $k.", "; 
				}
				$sql = substr($sql,0,-2).") VALUES (";
				foreach($form as $k=>$v){
					$sql .= "'".$dba->real_escape_string($v)."', "; 
				}
				$sql = substr($sql,0,-2).")";
				//echo $sql;
				if($res = $dba->query($sql)){
					echo 'success';
				}else{
					echo mysqli_error($dba);
				}
				//exit;
			}else{
				$dba->query("UPDATE rego_waiting_customers SET 
					clientID = '".$cid."',
					status = 'inv' 
					WHERE id = ".$_REQUEST['id']);
			}*/
			
			$protocol = 'http://';
			if(isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on') { $protocol = 'https://';}
			
			if(!empty($password)){
				$template = getEmailTemplate('NEW_COMPANY');
				$txt = $template['body'];
				$text = str_replace('{RECIPIENT}', $_REQUEST['firstname'], $txt);
				$text = str_replace('{COMPANY}', $_REQUEST[$lang.'_compname'], $text);
				$text = str_replace('{USERNAME}', $_REQUEST['email'], $text);
				$text = str_replace('{PASSWORD}', $password, $text);
				$text = str_replace('{CLICK_HERE_LINK}', $protocol.$_SERVER['SERVER_NAME'].'/hr', $text);
				//var_dump($template); //exit;
				//var_dump($text); //exit;
			}else{
				$template = getEmailTemplate('EXISTING_USER');
				$txt = $template['body'];
				$text = str_replace('{RECIPIENT}', $_REQUEST['firstname'], $txt);
				$text = str_replace('{COMPANY}', $_REQUEST[$lang.'_compname'], $text);
				$text = str_replace('{CLICK_HERE_LINK}', $protocol.$_SERVER['SERVER_NAME'].'/hr', $text);
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
			$mail->From = $from_email;
			$mail->FromName = strtoupper($client_prefix).' Admin';
			$mail->addAddress($_REQUEST['email'], $_REQUEST['firstname'].' '.$_REQUEST['lastname']); 
			$mail->isHTML(true);                                  
			$mail->Subject = $mail_subject;
			$mail->Body = $body;
			$mail->WordWrap = 100;
			if(!$mail->send()) {
				//$err_msg .= $mail->ErrorInfo;
				$err_msg .= '<span style="color:#c00"><i class="fa fa-times-circle"></i>&nbsp; <b>Send eMail failed. Error : </b>'.$mail->ErrorInfo.'</span><br>';
			}else{
				$err_msg .= '<i class="fa fa-check-square-o"></i>&nbsp;&nbsp;eMail send to <b>'.$_REQUEST['firstname'].' '.$_REQUEST['lastname'].'</b>.<br>';
			}
			
			$msg = '<div style="background:green; color:#fff; font-size:16px; font-weight:600; margin:0 10px 5px 0; padding:5px 10px"><i class="fa fa-check-square-o"></i>&nbsp; New customer created successfuly : '.strtoupper($cid).'</div>';
			
		}

	}else{
		$msg = '<div style="background:#a00; color:#fff; font-size:14px; font-weight:600; margin:0px 10px 15px 0; padding:5px 10px"><i class="fa fa-times-circle"></i>&nbsp; Sorry but something went wrong, please contact the site administrator.</div>';
	} // END If no errors save new client in database
	
	ob_clean();
	//echo $msg.$err_msg; exit;
	if(isset($_REQUEST['token'])){
		echo 'success';
	}else{
		echo $msg.$err_msg.'<br>';
	}
	exit;
	
?>













