<?
	if(session_id()==''){session_start(); ob_start();}
	//$cid = strtolower($_SESSION['xhr']['cid']);
	include("../../dbconnect/db_connect.php");
	include("../../files/functions.php");
	include("../../payroll/inc/payroll_functions.php");
	$lng = getLangVariables($_SESSION['xhr']['lang']);
	
	//var_dump($_REQUEST); exit;
	
	foreach($_REQUEST['from'] as $k=>$v){
		$taxrules[$k]['from'] = $v;
		$taxrules[$k]['to'] = $_REQUEST['to'][$k];
		$taxrules[$k]['percent'] = $_REQUEST['percent'][$k];
	}
	//if(!isset($_REQUEST['allow'])){$_REQUEST['allow'] = array();}

	if(!isset($_REQUEST['th_addr_detail'])){$_REQUEST['th_addr_detail'] = array();}
	if(!isset($_REQUEST['en_addr_detail'])){$_REQUEST['en_addr_detail'] = array();}
	if(!isset($_REQUEST['ot_type'])){$_REQUEST['ot_type'] = array();}
	if(!isset($_REQUEST['fix_name'])){$_REQUEST['fix_name'] = array();}
	if(!isset($_REQUEST['var_name'])){$_REQUEST['var_name'] = array();}
	if(!isset($_REQUEST['pr_report'])){$_REQUEST['pr_report'] = array();}
	if(!isset($_REQUEST['ot_report'])){$_REQUEST['ot_report'] = array();}
	if(!isset($_REQUEST['payslip'])){$_REQUEST['payslip'] = array();}
	if(!isset($_REQUEST['pr_periods'])){$_REQUEST['pr_periods'] = array();}
	
	if(isset($_REQUEST['tax_settings'])){$_REQUEST['tax_settings'] = serialize($_REQUEST['tax_settings']);}
	if(isset($_REQUEST['tax_info_th'])){$_REQUEST['tax_info_th'] = serialize($_REQUEST['tax_info_th']);}
	if(isset($_REQUEST['tax_info_en'])){$_REQUEST['tax_info_en'] = serialize($_REQUEST['tax_info_en']);}
	if(isset($_REQUEST['tax_err_th'])){$_REQUEST['tax_err_th'] = serialize($_REQUEST['tax_err_th']);}
	if(isset($_REQUEST['tax_err_en'])){$_REQUEST['tax_err_en'] = serialize($_REQUEST['tax_err_en']);}
	
	
	$sql = "UPDATE ".$_SESSION['SDadmin']."_def_payroll_settings SET 
		tax_settings = '".$dbc->real_escape_string($_REQUEST['tax_settings'])."', 
		tax_info_th = '".$dbc->real_escape_string($_REQUEST['tax_info_th'])."', 
		tax_info_en = '".$dbc->real_escape_string($_REQUEST['tax_info_en'])."', 
		tax_err_th = '".$dbc->real_escape_string($_REQUEST['tax_err_th'])."', 
		tax_err_en = '".$dbc->real_escape_string($_REQUEST['tax_err_en'])."', 
		
		pr_periods = '".$dbc->real_escape_string(serialize($_REQUEST['pr_periods']))."', 
		
		days_month = '".$dbc->real_escape_string($_REQUEST['days_month'])."', 
		hours_day = '".$dbc->real_escape_string($_REQUEST['hours_day'])."', 
		th_addr_detail = '".$dbc->real_escape_string(serialize($_REQUEST['th_addr_detail']))."', 
		en_addr_detail = '".$dbc->real_escape_string(serialize($_REQUEST['en_addr_detail']))."', 

		sso_rate = '".$dbc->real_escape_string($_REQUEST['sso_rate'])."', 
		sso_min = '".$dbc->real_escape_string($_REQUEST['sso_min'])."', 
		sso_max = '".$dbc->real_escape_string($_REQUEST['sso_max'])."', 
		sso_min_wage = '".$dbc->real_escape_string($_REQUEST['sso_min_wage'])."', 
		sso_max_wage = '".$dbc->real_escape_string($_REQUEST['sso_max_wage'])."', 

		pvf_applied = '".$dbc->real_escape_string($_REQUEST['pvf_applied'])."', 
		pvf_rate_employee = '".$dbc->real_escape_string($_REQUEST['pvf_rate_employee'])."', 
		pvf_rate_employer = '".$dbc->real_escape_string($_REQUEST['pvf_rate_employer'])."', 
		tax_calc_method = '".$dbc->real_escape_string($_REQUEST['tax_calc_method'])."', 
		ot_cutoff = '".$dbc->real_escape_string($_REQUEST['ot_cutoff'])."', 
		ot_type = '".$dbc->real_escape_string(serialize($_REQUEST['ot_type']))."', 
		allowances = '".$dbc->real_escape_string(serialize($_REQUEST['allow']))."', 
		pr_report = '".$dbc->real_escape_string(serialize($_REQUEST['pr_report']))."', 
		ot_report = '".$dbc->real_escape_string(serialize($_REQUEST['ot_report']))."', 
		payslip = '".$dbc->real_escape_string(serialize($_REQUEST['payslip']))."', 
		taxrules = '".$dbc->real_escape_string(serialize($taxrules))."'";
		//echo $sql; exit;
	
	
	
	
	/*$sql = "UPDATE xhr_default_payroll_settings SET 
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
		ot_cutoff = '".$dba->real_escape_string($_REQUEST['ot_cutoff'])."', 
		ot_type = '".$dba->real_escape_string(serialize($_REQUEST['ot_type']))."', 
		allowances = '".$dba->real_escape_string(serialize($_REQUEST['allow']))."', 
		pr_report = '".$dba->real_escape_string(serialize($_REQUEST['pr_report']))."', 
		ot_report = '".$dba->real_escape_string(serialize($_REQUEST['ot_report']))."', 
		payslip = '".$dba->real_escape_string(serialize($_REQUEST['payslip']))."', 
		stdeduct_per = '".$dba->real_escape_string($_REQUEST['stdeduct_per'])."', 
		standard_deduction = '".$dba->real_escape_string($_REQUEST['standard_deduction'])."', 
		personal_allowance = '".$dba->real_escape_string($_REQUEST['personal_allowance'])."', 
		spouse_allowance = '".$dba->real_escape_string($_REQUEST['spouse_allowance'])."', 
		parents_allowance = '".$dba->real_escape_string($_REQUEST['parents_allowance'])."', 
		parents_inlaw = '".$dba->real_escape_string($_REQUEST['parents_inlaw'])."', 
		child = '".$dba->real_escape_string($_REQUEST['child'])."', 
		max_children = '".$dba->real_escape_string($_REQUEST['max_children'])."', 
		disabled_person = '".$dba->real_escape_string($_REQUEST['disabled_person'])."', 
		interest = '".$dba->real_escape_string($_REQUEST['interest'])."', 
		donation_general = '".$dba->real_escape_string($_REQUEST['donation_general'])."', 
		donation_education = '".$dba->real_escape_string($_REQUEST['donation_education'])."', 
		social = '".$dba->real_escape_string($_REQUEST['social'])."', 
		life_insurance = '".$dba->real_escape_string($_REQUEST['life_insurance'])."', 
		life_insurance_ret = '".$dba->real_escape_string($_REQUEST['life_insurance_ret'])."', 
		life_insurance_par = '".$dba->real_escape_string($_REQUEST['life_insurance_par'])."', 
		rmf = '".$dba->real_escape_string($_REQUEST['rmf'])."', 
		ltf = '".$dba->real_escape_string($_REQUEST['ltf'])."', 
		max_rmf = '".$dba->real_escape_string($_REQUEST['max_rmf'])."', 
		max_ltf = '".$dba->real_escape_string($_REQUEST['max_ltf'])."', 
		taxrules = '".$dba->real_escape_string(serialize($taxrules))."'";*/
		//echo $sql; exit;

	if($dba->query($sql)){
			$err_msg = '<div style="margin:0px" class="msg_success">'.$lng['Database updated successfuly'].'</div>';
	}else{
			$err_msg = '<div class="msg_error">'.$lng['Error'].': '.mysqli_error($dba).'</div>';
	}
	
	echo $err_msg;
	//exit;
		
	
?>