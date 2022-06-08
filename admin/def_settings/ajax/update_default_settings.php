<?
	if(session_id()==''){session_start();}
	ob_start();
	include("../../dbconnect/db_connect.php");
	//var_dump($_REQUEST); exit;
	
	foreach($_REQUEST['from'] as $k=>$v){
		$taxrules[$k]['from'] = $v;
		$taxrules[$k]['to'] = $_REQUEST['to'][$k];
		$taxrules[$k]['percent'] = $_REQUEST['percent'][$k];
		$taxrules[$k]['net_from'] = $_REQUEST['net_from'][$k];
		$taxrules[$k]['net_to'] = $_REQUEST['net_to'][$k];
	}

	if(!isset($_REQUEST['fix_allow'])){$_REQUEST['fix_allow'] = array();}
	if(!isset($_REQUEST['var_allow'])){$_REQUEST['var_allow'] = array();}
	if(!isset($_REQUEST['bank_codes'])){$_REQUEST['bank_codes'] = array();}
	if(!isset($_REQUEST['payslip_field'])){$_REQUEST['payslip_field'] = array();}
	if(!isset($_REQUEST['account_codes'])){$_REQUEST['account_codes'] = array();}

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
		sso_rate = '".$dba->real_escape_string($_REQUEST['sso_rate'])."', 
		sso_min = '".$dba->real_escape_string($_REQUEST['sso_min'])."', 
		sso_max = '".$dba->real_escape_string($_REQUEST['sso_max'])."', 
		sso_min_wage = '".$dba->real_escape_string($_REQUEST['sso_min_wage'])."', 
		sso_max_wage = '".$dba->real_escape_string($_REQUEST['sso_max_wage'])."', 
		pvf_applied = '".$dba->real_escape_string($_REQUEST['pvf_applied'])."', 
		pvf_rate_employee = '".$dba->real_escape_string($_REQUEST['pvf_rate_employee'])."', 
		pvf_rate_employer = '".$dba->real_escape_string($_REQUEST['pvf_rate_employer'])."', 
		tax_calc_method = '".$dba->real_escape_string($_REQUEST['tax_calc_method'])."', 
		payslip_template = '".$dba->real_escape_string($_REQUEST['payslip_template'])."', 
		payslip_rate = '".$dba->real_escape_string($_REQUEST['payslip_rate'])."', 
		payslip_field = '".$dba->real_escape_string(serialize($_REQUEST['payslip_field']))."', 
		support_email = '".$dba->real_escape_string($_REQUEST['support_email'])."', 
		fix_allow = '".$dba->real_escape_string(serialize($_REQUEST['fix_allow']))."', 
		var_allow = '".$dba->real_escape_string(serialize($_REQUEST['var_allow']))."', 
		tax_settings = '".$dba->real_escape_string(serialize($_REQUEST['tax_settings']))."', 
		tax_info_th = '".$dba->real_escape_string(serialize($_REQUEST['tax_info_th']))."', 
		tax_info_en = '".$dba->real_escape_string(serialize($_REQUEST['tax_info_en']))."', 
		tax_err_th = '".$dba->real_escape_string(serialize($_REQUEST['tax_err_th']))."', 
		tax_err_en = '".$dba->real_escape_string(serialize($_REQUEST['tax_err_en']))."', 
		taxrules = '".$dba->real_escape_string(serialize($taxrules))."', 
		positions = '".$dba->real_escape_string(serialize($positions))."', 
		account_codes = '".$dba->real_escape_string(serialize($_REQUEST['account_codes']))."', 
		allocations = '".$dba->real_escape_string(serialize($_REQUEST['allocations']))."', 
		emp_type = '".$dba->real_escape_string($_REQUEST['emp_type'])."', 
		emp_status = '".$dba->real_escape_string($_REQUEST['emp_status'])."', 
		allow_login = '".$dba->real_escape_string($_REQUEST['allow_login'])."', 
		calc_sso = '".$dba->real_escape_string($_REQUEST['calc_sso'])."', 
		calc_pvf = '".$dba->real_escape_string($_REQUEST['calc_pvf'])."', 
		calc_tax = '".$dba->real_escape_string($_REQUEST['calc_tax'])."', 
		print_payslip = '".$dba->real_escape_string($_REQUEST['print_payslip'])."', 
		bonus_payinmonth = '".$dba->real_escape_string($_REQUEST['bonus_payinmonth'])."', 
		
		bank_codes = '".$dba->real_escape_string(serialize($_REQUEST['bank_codes']))."'"; 
		//echo $sql; exit;
	
	ob_clean();
	if($dba->query($sql)){
			echo 'success';
	}else{
			echo mysqli_error($dba);
	}
	//exit;
		
	
?>