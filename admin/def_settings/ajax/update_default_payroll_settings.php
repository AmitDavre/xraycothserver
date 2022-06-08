<?
	if(session_id()==''){session_start();}
	ob_start();
	include("../../dbconnect/db_connect.php");
	//var_dump($_REQUEST); exit;
	
	$_REQUEST['id_prefix'] = preg_replace("/[^a-zA-Z0-9,]+/", "", $_REQUEST['id_prefix']);
	$tmp = explode(',', $_REQUEST['id_prefix']);
	foreach($tmp as $k=>$v){
		if(empty($v)){unset($tmp[$k]);}
		if(strlen($v) > 3){$tmp[$k] = substr($v,0,3);}
	}
	$_REQUEST['id_prefix'] = implode(',', $tmp);
	
	foreach($_REQUEST['from'] as $k=>$v){
		$taxrules[$k]['from'] = $v;
		$taxrules[$k]['to'] = $_REQUEST['to'][$k];
		$taxrules[$k]['percent'] = $_REQUEST['percent'][$k];
		$taxrules[$k]['net_from'] = $_REQUEST['net_from'][$k];
		$taxrules[$k]['net_to'] = $_REQUEST['net_to'][$k];
	}

	if(!isset($_REQUEST['fix_allow'])){$_REQUEST['fix_allow'] = array();}
	if(!isset($_REQUEST['var_allow'])){$_REQUEST['var_allow'] = array();}
	if(!isset($_REQUEST['fix_deduct'])){$_REQUEST['fix_deduct'] = array();}
	if(!isset($_REQUEST['var_deduct'])){$_REQUEST['var_deduct'] = array();}
	if(!isset($_REQUEST['payslip_field'])){$_REQUEST['payslip_field'] = array();}
	if(!isset($_REQUEST['sso_defaults'])){$_REQUEST['sso_defaults'] = array();}

	$positions = array();
	if(isset($_REQUEST['positions'])){
		foreach($_REQUEST['positions'] as $k=>$v){
			$positions['en'][$v['code']] = $v['en'];
			$positions['th'][$v['code']] = $v['th'];
		}
	}
	//var_dump($positions); exit;
	
	$sql = "UPDATE rego_default_settings SET 
		days_month = '".$dba->real_escape_string($_REQUEST['days_month'])."', 
		hours_day = '".$dba->real_escape_string($_REQUEST['hours_day'])."', 
		sso_rate_emp = '".$dba->real_escape_string($_REQUEST['sso_rate_emp'])."', 
		sso_min_emp = '".$dba->real_escape_string($_REQUEST['sso_min_emp'])."', 
		sso_max_emp = '".$dba->real_escape_string($_REQUEST['sso_max_emp'])."', 
		sso_rate_com = '".$dba->real_escape_string($_REQUEST['sso_rate_com'])."', 
		sso_min_com = '".$dba->real_escape_string($_REQUEST['sso_min_com'])."', 
		sso_max_com = '".$dba->real_escape_string($_REQUEST['sso_max_com'])."', 
		sso_min_wage = '".$dba->real_escape_string($_REQUEST['sso_min_wage'])."', 
		sso_max_wage = '".$dba->real_escape_string($_REQUEST['sso_max_wage'])."', 
		sso_act_max = '".$dba->real_escape_string($_REQUEST['sso_act_max'])."', 
		payslip_template = '".$dba->real_escape_string($_REQUEST['payslip_template'])."', 
		payslip_rate = '".$dba->real_escape_string($_REQUEST['payslip_rate'])."', 
		payslip_field = '".$dba->real_escape_string(serialize($_REQUEST['payslip_field']))."', 

		positions = '".$dba->real_escape_string(serialize($positions))."', 
		
		auto_id = '".$dba->real_escape_string($_REQUEST['auto_id'])."', 
		id_start = '".$dba->real_escape_string($_REQUEST['id_start'])."', 
		id_prefix = '".$dba->real_escape_string($_REQUEST['id_prefix'])."', 
		scan_id = '".$dba->real_escape_string($_REQUEST['scan_id'])."', 
		
		joining_date = '".$dba->real_escape_string($_REQUEST['joining_date'])."', 
		team = '".$dba->real_escape_string($_REQUEST['team'])."', 
		shiftplan_schedule = '".$dba->real_escape_string($_REQUEST['shiftplan_schedule'])."', 
		teams_name = '".$dba->real_escape_string($_REQUEST['teams_name'])."', 
		emp_group = '".$dba->real_escape_string($_REQUEST['emp_group'])."', 
		emp_type = '".$dba->real_escape_string($_REQUEST['emp_type'])."', 
		emp_status = '".$dba->real_escape_string($_REQUEST['emp_status'])."', 
		account_code = '".$dba->real_escape_string($_REQUEST['account_code'])."', 
		position = '".$dba->real_escape_string($_REQUEST['position'])."', 
		date_start = '".$dba->real_escape_string($_REQUEST['date_start'])."', 
		time_reg = '".$dba->real_escape_string($_REQUEST['time_reg'])."', 
		selfie = '".$dba->real_escape_string($_REQUEST['selfie'])."', 
		leeve = '".$dba->real_escape_string($_REQUEST['leeve'])."', 
		pay_type = '".$dba->real_escape_string($_REQUEST['pay_type'])."', 
		
		calc_psf = '".$dba->real_escape_string($_REQUEST['calc_psf'])."', 
		psf_rate_emp = '".$dba->real_escape_string($_REQUEST['psf_rate_emp'])."', 
		psf_rate_com = '".$dba->real_escape_string($_REQUEST['psf_rate_com'])."', 
		calc_pvf = '".$dba->real_escape_string($_REQUEST['calc_pvf'])."', 
		pvf_rate_emp = '".$dba->real_escape_string($_REQUEST['pvf_rate_emp'])."', 
		pvf_rate_com = '".$dba->real_escape_string($_REQUEST['pvf_rate_com'])."', 
		
		calc_method = '".$dba->real_escape_string($_REQUEST['calc_method'])."', 
		calc_tax = '".$dba->real_escape_string($_REQUEST['calc_tax'])."', 
		calc_sso = '".$dba->real_escape_string($_REQUEST['calc_sso'])."', 
		contract_type = '".$dba->real_escape_string($_REQUEST['contract_type'])."', 
		calc_base = '".$dba->real_escape_string($_REQUEST['calc_base'])."', 
		base_ot_rate = '".$dba->real_escape_string($_REQUEST['base_ot_rate'])."', 
		ot_rate = '".$dba->real_escape_string($_REQUEST['ot_rate'])."', 

		fix_allow = '".$dba->real_escape_string(serialize($_REQUEST['fix_allow']))."', 
		var_allow = '".$dba->real_escape_string(serialize($_REQUEST['var_allow']))."', 
		
		fix_deduct = '".$dba->real_escape_string(serialize($_REQUEST['fix_deduct']))."', 
		var_deduct = '".$dba->real_escape_string(serialize($_REQUEST['var_deduct']))."', 

		tax_settings = '".$dba->real_escape_string(serialize($_REQUEST['tax_settings']))."', 
		tax_info_th = '".$dba->real_escape_string(serialize($_REQUEST['tax_info_th']))."', 
		tax_info_en = '".$dba->real_escape_string(serialize($_REQUEST['tax_info_en']))."', 
		tax_err_th = '".$dba->real_escape_string(serialize($_REQUEST['tax_err_th']))."', 
		tax_err_en = '".$dba->real_escape_string(serialize($_REQUEST['tax_err_en']))."', 
		sso_defaults = '".$dba->real_escape_string(serialize($_REQUEST['sso_defaults']))."',

		taxrules = '".$dba->real_escape_string(serialize($taxrules))."'"; 
		
		/*allow_login = '".$dba->real_escape_string($_REQUEST['allow_login'])."', 
		support_email = '".$dba->real_escape_string($_REQUEST['support_email'])."', 
		print_payslip = '".$dba->real_escape_string($_REQUEST['print_payslip'])."', 
		bonus_payinmonth = '".$dba->real_escape_string($_REQUEST['bonus_payinmonth'])."'";*/
		//echo $sql; exit;
	
	ob_clean();
	if($dba->query($sql)){
			echo 'success';
	}else{
			echo mysqli_error($dba);
	}
	//exit;
		
	
?>
