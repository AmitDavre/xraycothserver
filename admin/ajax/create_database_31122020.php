<?

	$dbc = @new mysqli($my_database,$my_username,$my_password);
	$dbname = $prefix.$cid;
	if(empty(mysqli_fetch_array(mysqli_query($dbc,"SHOW DATABASES LIKE '$dbname'")))){
		 echo "Database not exist, please contact the site administrator.";
		 exit; 
	}else{
		 $dbc = @new mysqli($my_database,$my_username,$my_password,$prefix.$cid);
		 mysqli_set_charset($dbc,"utf8");
	}	

	$err_msg .= '<div style="color:#a00; font-size:16px; font-weight:600; border-bottom:1px solid #ccc; margin:0px 10px 2px 0">Create Databases</div>';
	
	$db_name = $cid."_employees";
	if(!$dbc->query("DESCRIBE `$db_name`")) {
		$sql = "CREATE TABLE IF NOT EXISTS `$db_name` (
			`emp_id` varchar(20) COLLATE utf8_bin NOT NULL,
			`sid` varchar(20) COLLATE utf8_bin NOT NULL,
			`title` varchar(10) COLLATE utf8_bin NOT NULL,
			`firstname` varchar(50) COLLATE utf8_bin NOT NULL,
			`lastname` varchar(50) COLLATE utf8_bin NOT NULL,
			`th_name` varchar(50) COLLATE utf8_bin NOT NULL,
			`en_name` varchar(50) COLLATE utf8_bin NOT NULL,
			`birthdate` varchar(20) COLLATE utf8_bin NOT NULL,
			`gender` varchar(20) COLLATE utf8_bin NOT NULL,
			`maritial` varchar(20) COLLATE utf8_bin NOT NULL,
			`nationality` varchar(50) COLLATE utf8_bin NOT NULL,
			`religion` varchar(50) COLLATE utf8_bin NOT NULL,
			`military_status` varchar(50) COLLATE utf8_bin NOT NULL,
			`idcard_nr` varchar(30) COLLATE utf8_bin NOT NULL,
			`idcard_exp` varchar(20) COLLATE utf8_bin NOT NULL,
			`drvlicense_nr` varchar(50) COLLATE utf8_bin NOT NULL,
			`drvlicense_exp` varchar(20) COLLATE utf8_bin NOT NULL,
			`issued` varchar(50) COLLATE utf8_bin NOT NULL,
			`address1` varchar(50) COLLATE utf8_bin NOT NULL,
			`address2` varchar(50) COLLATE utf8_bin NOT NULL,
			`district` varchar(50) COLLATE utf8_bin NOT NULL,
			`sub_district` varchar(50) COLLATE utf8_bin NOT NULL,
			`province` varchar(50) COLLATE utf8_bin NOT NULL,
			`country` varchar(50) COLLATE utf8_bin NOT NULL,
			`postnr` varchar(10) COLLATE utf8_bin NOT NULL,
			`personal_phone` varchar(20) COLLATE utf8_bin NOT NULL,
			`personal_email` varchar(50) COLLATE utf8_bin NOT NULL,
			`tax_id` varchar(30) COLLATE utf8_bin NOT NULL,
			`emp_type` varchar(50) COLLATE utf8_bin NOT NULL,
			`startdate` varchar(20) COLLATE utf8_bin NOT NULL,
			`probation_date` varchar(20) COLLATE utf8_bin NOT NULL,
			`position` int(11) NOT NULL,
			`emp_status` varchar(20) COLLATE utf8_bin NOT NULL,
			`resign_date` varchar(20) COLLATE utf8_bin NOT NULL,
			`resign_reason` text COLLATE utf8_bin NOT NULL,
			`notice_date` varchar(20) COLLATE utf8_bin NOT NULL,
			`remaining_salary` double NOT NULL,
			`notice_payment` double NOT NULL,
			`paid_leave` double NOT NULL,
			`severance` double NOT NULL,
			`other_income` double NOT NULL,
			`remarks` text COLLATE utf8_bin NOT NULL,
			`attachments` text COLLATE utf8_bin NOT NULL,
			`shiftplan` varchar(50) COLLATE utf8_bin NOT NULL,
			`annual_leave` int(11) NOT NULL,
			`pay_type` varchar(20) COLLATE utf8_bin NOT NULL,
			`bank_name` varchar(50) COLLATE utf8_bin NOT NULL,
			`bank_code` varchar(20) COLLATE utf8_bin NOT NULL,
			`bank_account` varchar(50) COLLATE utf8_bin NOT NULL,
			`bank_branch` varchar(10) COLLATE utf8_bin NOT NULL,
			`bank_account_name` varchar(100) COLLATE utf8_bin NOT NULL,
			`bank_transfer` varchar(10) COLLATE utf8_bin NOT NULL,
			`base_salary` double NOT NULL,
			`day_rate` double NOT NULL,
			`hour_rate` double NOT NULL,
			`salary_type` varchar(10) COLLATE utf8_bin NOT NULL DEFAULT 'gross',
			`wage_type` varchar(10) COLLATE utf8_bin NOT NULL DEFAULT 'month',
			`gov_house_banking` double NOT NULL,
			`savings` double NOT NULL,
			`legal_execution` double NOT NULL,
			`kor_yor_sor` double NOT NULL,
			`fix_allow_1` double NOT NULL,
			`fix_allow_2` double NOT NULL,
			`fix_allow_3` double NOT NULL,
			`fix_allow_4` double NOT NULL,
			`fix_allow_5` double NOT NULL,
			`fix_allow_6` double NOT NULL,
			`fix_allow_7` double NOT NULL,
			`fix_allow_8` double NOT NULL,
			`fix_allow_9` double NOT NULL,
			`fix_allow_10` double NOT NULL,
			`fix_deduct_1` double NOT NULL,
			`fix_deduct_2` double NOT NULL,
			`fix_deduct_3` double NOT NULL,
			`fix_deduct_4` double NOT NULL,
			`fix_deduct_5` double NOT NULL,
			`tax_standard_deduction` float NOT NULL,
			`tax_personal_allowance` float NOT NULL,
			`tax_spouse` varchar(5) COLLATE utf8_bin NOT NULL,
			`tax_allow_spouse` float NOT NULL,
			`tax_parents` float NOT NULL,
			`tax_allow_parents` float NOT NULL,
			`tax_parents_inlaw` float NOT NULL,
			`tax_allow_parents_inlaw` float NOT NULL,
			`tax_child_bio_2018` float NOT NULL,
			`tax_allow_child_bio_2018` float NOT NULL,
			`tax_child_adopted` float NOT NULL,
			`tax_allow_child_adopted` float NOT NULL,
			`tax_child_bio` float NOT NULL,
			`tax_allow_child_bio` double NOT NULL,
			`tax_allow_child_birth` double NOT NULL,
			`tax_disabled_person` float NOT NULL,
			`tax_allow_disabled_person` double NOT NULL,
			`tax_allow_home_loan_interest` double NOT NULL,
			`tax_allow_first_home` double NOT NULL,
			`tax_allow_donation_charity` double NOT NULL,
			`tax_allow_donation_education` double NOT NULL,
			`tax_allow_donation_flood` float NOT NULL,
			`tax_allow_own_health` double NOT NULL,
			`tax_health_parents` float NOT NULL,
			`tax_allow_health_parents` double NOT NULL,
			`tax_allow_own_life_insurance` double NOT NULL,
			`tax_allow_life_insurance_spouse` double NOT NULL,
			`tax_allow_pension_fund` double NOT NULL,
			`tax_allow_rmf` double NOT NULL,
			`tax_allow_ltf` double NOT NULL,
			`tax_exemp_disabled_under` varchar(5) COLLATE utf8_bin NOT NULL,
			`tax_allow_exemp_disabled_under` float NOT NULL,
			`tax_exemp_payer_older` varchar(5) COLLATE utf8_bin NOT NULL,
			`tax_allow_exemp_payer_older` float NOT NULL,
			`tax_allow_domestic_tour` float NOT NULL,
			`tax_allow_year_end_shopping` float NOT NULL,
			`tax_allow_other` float NOT NULL,
			`emp_tax_deductions` double NOT NULL,
			`total_tax_deductions` float NOT NULL,
			`tax_allow_nsf` float NOT NULL,
			`tax_allow_pvf` float NOT NULL,
			`tax_allow_sso` float NOT NULL,
			`pvf_nr` varchar(50) COLLATE utf8_bin NOT NULL,
			`pvf_reg_date` varchar(20) COLLATE utf8_bin NOT NULL,
			`pvf_rate_employee` double NOT NULL,
			`pvf_rate_employer` double NOT NULL,
			`former_salary_rate` double NOT NULL,
			`income_current_year` double NOT NULL,
			`tax_paid_current_year` double NOT NULL,
			`pvf_prev_years_employee` double NOT NULL,
			`pvf_prev_years_employer` double NOT NULL,
			`psf_prev_years_employee` double NOT NULL,
			`psf_prev_years_employer` double NOT NULL,
			`contribute` varchar(10) COLLATE utf8_bin NOT NULL,
			`calc_sso` varchar(5) COLLATE utf8_bin NOT NULL,
			`calc_pvf` varchar(5) COLLATE utf8_bin NOT NULL,
			`calc_tax` varchar(5) COLLATE utf8_bin NOT NULL,
			`calc_method` varchar(10) COLLATE utf8_bin NOT NULL,
			`modify_tax` double NOT NULL,
			`image` varchar(50) COLLATE utf8_bin NOT NULL,
			`att_idcard` varchar(100) COLLATE utf8_bin NOT NULL,
			`att_housebook` varchar(100) COLLATE utf8_bin NOT NULL,
			`att_bankbook` varchar(100) COLLATE utf8_bin NOT NULL,
			`att_contract` varchar(100) COLLATE utf8_bin NOT NULL,
			`att_employment` varchar(100) COLLATE utf8_bin NOT NULL,
			`attach1` varchar(100) COLLATE utf8_bin NOT NULL,
			`attach2` varchar(100) COLLATE utf8_bin NOT NULL,
			`pr_calculation` varchar(5) COLLATE utf8_bin NOT NULL,
			`allow_login` varchar(10) COLLATE utf8_bin NOT NULL,
			`username` varchar(50) COLLATE utf8_bin NOT NULL,
			`password` varchar(255) COLLATE utf8_bin NOT NULL,
			`log_status` varchar(10) COLLATE utf8_bin NOT NULL,
			`emergency_contacts` text COLLATE utf8_bin NOT NULL,
			`pr_status` int(11) NOT NULL,
			`account_code` int(11) NOT NULL,
			`print_payslip` varchar(5) COLLATE utf8_bin NOT NULL,
			`selfie` int(11) NOT NULL,
			`steps` text COLLATE utf8_bin NOT NULL,
			`new_steps` text COLLATE utf8_bin NOT NULL,
			`clock_in` varchar(50) COLLATE utf8_bin NOT NULL,
		  PRIMARY KEY (`emp_id`)
		) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin";
		if(!$dbc->query($sql)){
			$err_msg .= '<span style="color:#c00"><i class="fa fa-times-circle"></i>&nbsp; Create database <b>Employees</b> failed. Error : <b>'.mysqli_error($dbc).'</b></span><br>';
			$error = true;
		}else{
			$err_msg .= '<i class="fa fa-check-square-o"></i>&nbsp; Database <b>Employees</b> created successfuly.<br>';
		}
	}else{
		$err_msg .= '<i class="fa fa-check-square-o"></i>&nbsp; Database <b>Employees</b> exists already.<br>';
	}
	
	$db_name = $cid."_settings";
	if(!$dbc->query("DESCRIBE `$db_name`")) {
		$sql = "CREATE TABLE IF NOT EXISTS `$db_name` (
		  `id` int(11) NOT NULL,
			`logtime` int(11) NOT NULL,
			`latitude` varchar(50) COLLATE utf8_bin NOT NULL,
			`longitude` varchar(50) COLLATE utf8_bin NOT NULL,
			`preflang` varchar(10) COLLATE utf8_bin NOT NULL,
			`cur_month` int(11) NOT NULL,
			`pr_startdate` varchar(20) COLLATE utf8_bin NOT NULL,
			`sso_idnr` varchar(20) COLLATE utf8_bin NOT NULL,
			`sso_act_max` varchar(5) COLLATE utf8_bin NOT NULL,
			`pvf_idnr` varchar(20) COLLATE utf8_bin NOT NULL,
			`pvf_applied` varchar(5) COLLATE utf8_bin NOT NULL DEFAULT 'N',
			`pvf_rate_employee` int(11) NOT NULL,
			`pvf_rate_employer` int(11) NOT NULL,
			`tax_idnr` varchar(50) COLLATE utf8_bin NOT NULL,
			`personal_idnr` varchar(20) COLLATE utf8_bin NOT NULL,
			`tax_calc_method` varchar(10) COLLATE utf8_bin NOT NULL,
			`branches` text COLLATE utf8_bin NOT NULL,
			`entities` text COLLATE utf8_bin NOT NULL,
			`groups` text COLLATE utf8_bin NOT NULL,
			`departments` text COLLATE utf8_bin NOT NULL,
			`teams` text COLLATE utf8_bin NOT NULL,
			`positions` text COLLATE utf8_bin NOT NULL,
			`fix_allow` text COLLATE utf8_bin NOT NULL,
			`var_allow` text COLLATE utf8_bin NOT NULL,
			`fix_deduct` text COLLATE utf8_bin NOT NULL,
			`var_deduct` text COLLATE utf8_bin NOT NULL,
			`paydate` varchar(20) COLLATE utf8_bin NOT NULL,
			`att_cols` text COLLATE utf8_bin NOT NULL,
			`att_showhide_cols` varchar(255) COLLATE utf8_bin NOT NULL,
			`his_cols` text COLLATE utf8_bin NOT NULL,
			`his_showhide_cols` varchar(255) COLLATE utf8_bin NOT NULL,
			`emp_export_fields` text COLLATE utf8_bin NOT NULL,
			`payslip_template` varchar(10) COLLATE utf8_bin NOT NULL,
			`payslip_field` text COLLATE utf8_bin NOT NULL,
			`payslip_rate` varchar(10) COLLATE utf8_bin NOT NULL,
			`support_email` varchar(50) COLLATE utf8_bin NOT NULL,
			`history` int(11) NOT NULL,
			`account_codes` text COLLATE utf8_bin NOT NULL,
			`account_allocations` text COLLATE utf8_bin NOT NULL,
			`demo` int(11) NOT NULL,
		  PRIMARY KEY (`id`) 
		) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin";
		if(!$dbc->query($sql)){
			$err_msg .= '<span style="color:#c00"><i class="fa fa-times-circle"></i>&nbsp; Create database <b>Settings</b> failed. Error : <b>'.mysqli_error($dbc).'</b></span><br>';
			$error = true;
		}else{
			$err_msg .= '<i class="fa fa-check-square-o"></i>&nbsp; Database <b>Settings</b> created successfuly.<br>';
		}
	}else{
		$err_msg .= '<i class="fa fa-check-square-o"></i>&nbsp; Database <b>Settings</b> exists already.<br>';
	}

	$db_name = $cid."_historic_data";
	if(!$dbc->query("DESCRIBE `$db_name`")) {
		$sql = "CREATE TABLE IF NOT EXISTS `$db_name` (
		  `id` varchar(50) COLLATE utf8_bin NOT NULL,
		  `emp_id` varchar(50) COLLATE utf8_bin NOT NULL,
		  `month` int(11) NOT NULL,
		  `emp_name_en` varchar(50) COLLATE utf8_bin NOT NULL,
		  `emp_name_th` varchar(50) COLLATE utf8_bin NOT NULL,
		  `salary` double NOT NULL,
		  `ot1b` double NOT NULL,
		  `ot15b` double NOT NULL,
		  `ot2b` double NOT NULL,
		  `ot3b` double NOT NULL,
		  `ootb` double NOT NULL,
		  `total_otb` double NOT NULL,
		  `fix_allow_1` double NOT NULL,
		  `fix_allow_2` double NOT NULL,
		  `fix_allow_3` double NOT NULL,
		  `fix_allow_4` double NOT NULL,
		  `fix_allow_5` double NOT NULL,
		  `fix_allow_6` double NOT NULL,
		  `fix_allow_7` double NOT NULL,
		  `fix_allow_8` double NOT NULL,
		  `fix_allow_9` double NOT NULL,
		  `fix_allow_10` double NOT NULL,
		  `var_allow_1` double NOT NULL,
		  `var_allow_2` double NOT NULL,
		  `var_allow_3` double NOT NULL,
		  `var_allow_4` double NOT NULL,
		  `var_allow_5` double NOT NULL,
		  `var_allow_6` double NOT NULL,
		  `var_allow_7` double NOT NULL,
		  `var_allow_8` double NOT NULL,
		  `var_allow_9` double NOT NULL,
		  `var_allow_10` double NOT NULL,
		  `total_fix_allow` double NOT NULL,
		  `total_var_allow` double NOT NULL,
		  `total_tax_allow` double NOT NULL,
		  `other_income` double NOT NULL,
		  `social` double NOT NULL,
		  `social_com` double NOT NULL,
		  `pvf_employee` double NOT NULL,
		  `psf_employee` double NOT NULL,
		  `tot_deduct_before` double NOT NULL,
		  `tot_deduct_after` double NOT NULL,
		  `tot_deductions` double NOT NULL,
		  `tax` double NOT NULL,
		  `tot_fix_income` double NOT NULL,
		  `tot_var_income` double NOT NULL,
		  `gross_income` double NOT NULL,
		  `net_income` double NOT NULL,
		  `paid` varchar(5) COLLATE utf8_bin NOT NULL DEFAULT 'H', 
		  PRIMARY KEY (`id`)
		) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_bin";
		if(!$dbc->query($sql)){
			$err_msg .= '<span style="color:#c00"><i class="fa fa-times-circle"></i>&nbsp; Create database <b>Historic data</b> failed. Error : <b>'.mysqli_error($dbc).'</b></span><br>';
			$error = true;
		}else{
			$err_msg .= '<i class="fa fa-check-square-o"></i>&nbsp; Database <b>Historic data</b> created successfuly.<br>';
		}
	}else{
		$err_msg .= '<i class="fa fa-check-square-o"></i>&nbsp; Database <b>Historic data</b> exists already.<br>';
	}
	
	$db_name = $cid."_ot_plans";
	if(!$dbc->query("DESCRIBE `$db_name`")) {
		$sql = "CREATE TABLE IF NOT EXISTS `$db_name` (
			`id` int(11) NOT NULL AUTO_INCREMENT,
			`date` date NOT NULL,
			`shiftteam` varchar(20) COLLATE utf8_bin NOT NULL,
			`plan` varchar(10) COLLATE utf8_bin NOT NULL,
			`plan_f1` varchar(10) COLLATE utf8_bin NOT NULL,
			`plan_u2` varchar(10) COLLATE utf8_bin NOT NULL,
			`ot_from` varchar(10) COLLATE utf8_bin NOT NULL,
			`ot_until` varchar(10) COLLATE utf8_bin NOT NULL,
			`ot_break` varchar(10) COLLATE utf8_bin NOT NULL,
			`hours` varchar(10) COLLATE utf8_bin NOT NULL,
			`type` varchar(10) COLLATE utf8_bin NOT NULL,
			`compensations` text COLLATE utf8_bin NOT NULL,
		  PRIMARY KEY (`id`)
		) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_bin";
		if(!$dbc->query($sql)){
			$err_msg .= '<span style="color:#c00"><i class="fa fa-times-circle"></i>&nbsp; Create database <b>OT Plans</b> failed. Error : <b>'.mysqli_error($dbc).'</b></span><br>';
			$error = true;
		}else{
			$err_msg .= '<i class="fa fa-check-square-o"></i>&nbsp; Database <b>OT Plans</b> created successfuly.<br>';
		}
	}else{
		$err_msg .= '<i class="fa fa-check-square-o"></i>&nbsp; Database <b>OT Plans</b> exists already.<br>';
	}
	
	$db_name = $cid."_ot_employees";
	if(!$dbc->query("DESCRIBE `$db_name`")) {
		$sql = "CREATE TABLE IF NOT EXISTS `$db_name` (
			`id` varchar(50) COLLATE utf8_bin NOT NULL,
			`ot_plan` int(11) NOT NULL,
			`month` int(11) NOT NULL,
			`date` varchar(20) COLLATE utf8_bin NOT NULL,
			`emp_id` varchar(50) COLLATE utf8_bin NOT NULL,
			`en_name` varchar(50) COLLATE utf8_bin NOT NULL,
			`th_name` varchar(50) COLLATE utf8_bin NOT NULL,
			`shiftteam` varchar(50) COLLATE utf8_bin NOT NULL,
			`position` int(11) NOT NULL,
			`ot_from` varchar(10) COLLATE utf8_bin NOT NULL,
			`ot_until` varchar(10) COLLATE utf8_bin NOT NULL,
			`ot_hours` varchar(10) COLLATE utf8_bin NOT NULL,
			`ot_break` varchar(10) COLLATE utf8_bin NOT NULL,
			`ot_type` varchar(10) COLLATE utf8_bin NOT NULL,
			`ot_invited` int(11) NOT NULL,
			`ot_confirmed` int(11) NOT NULL,
			`ot_assigned` int(11) NOT NULL,
			`ot_compensations` text COLLATE utf8_bin NOT NULL,		  
			PRIMARY KEY (`id`)
		) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_bin";
		if(!$dbc->query($sql)){
			$err_msg .= '<span style="color:#c00"><i class="fa fa-times-circle"></i>&nbsp; Create database <b>OT Employees</b> failed. Error : <b>'.mysqli_error($dbc).'</b></span><br>';
			$error = true;
		}else{
			$err_msg .= '<i class="fa fa-check-square-o"></i>&nbsp; Database <b>OT Employees</b> created successfuly.<br>';
		}
	}else{
		$err_msg .= '<i class="fa fa-check-square-o"></i>&nbsp; Database <b>OT Employees</b> exists already.<br>';
	}
	
	$db_name = $cid."_payroll_months";
	if(!$dbc->query("DESCRIBE `$db_name`")) {
		$sql = "CREATE TABLE IF NOT EXISTS `$db_name` (
			`month` varchar(10) COLLATE utf8_bin NOT NULL,
			`time_start` varchar(20) COLLATE utf8_bin NOT NULL,
			`time_end` varchar(20) COLLATE utf8_bin NOT NULL,
			`leave_start` varchar(20) COLLATE utf8_bin NOT NULL,
			`leave_end` varchar(20) COLLATE utf8_bin NOT NULL,
			`payroll_start` varchar(20) COLLATE utf8_bin NOT NULL,
			`payroll_end` varchar(20) COLLATE utf8_bin NOT NULL,
			`paydate` varchar(20) COLLATE utf8_bin NOT NULL,
			`formdate` varchar(20) COLLATE utf8_bin NOT NULL,
			`form_name` varchar(50) COLLATE utf8_bin NOT NULL,
			`form_position` varchar(50) COLLATE utf8_bin NOT NULL,
			`sso_eRate` int(11) NOT NULL,
			`sso_eMax` int(11) NOT NULL,
			`sso_eMin` int(11) NOT NULL,
			`sso_cRate` int(11) NOT NULL,
			`sso_cMax` int(11) NOT NULL,
			`sso_cMin` int(11) NOT NULL,
			`sso_act_max` varchar(10) COLLATE utf8_bin NOT NULL DEFAULT 'max',
			`locked` int(11) NOT NULL,
			`status` int(11) NOT NULL,
			`accounting` text COLLATE utf8_bin NOT NULL,
			`var_allowances` text COLLATE utf8_bin NOT NULL,
			`compensations` text COLLATE utf8_bin NOT NULL, 
		  PRIMARY KEY (`month`)
		) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_bin";
		if(!$dbc->query($sql)){
			$err_msg .= '<span style="color:#c00"><i class="fa fa-times-circle"></i>&nbsp; Create database <b>Payroll months</b> failed. Error : <b>'.mysqli_error($dbc).'</b></span><br>';
			$error = true;
		}else{
			$err_msg .= '<i class="fa fa-check-square-o"></i>&nbsp; Database <b>Payroll months</b> created successfuly.<br>';
		}
	}else{
		$err_msg .= '<i class="fa fa-check-square-o"></i>&nbsp; Database <b>Payroll months</b> exists already.<br>';
	}
	
	$db_name = $cid."_employee_steps";
	if(!$dbc->query("DESCRIBE `$db_name`")) {
		$sql = "CREATE TABLE IF NOT EXISTS `$db_name` (
			`id` varchar(50) COLLATE utf8_bin NOT NULL,
  		`steps` text COLLATE utf8_bin NOT NULL, 
		  PRIMARY KEY (`id`)
		) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_bin";
		if(!$dbc->query($sql)){
			$err_msg .= '<span style="color:#c00"><i class="fa fa-times-circle"></i>&nbsp; Create database <b>Employee steps</b> failed. Error : <b>'.mysqli_error($dbc).'</b></span><br>';
			$error = true;
		}else{
			$err_msg .= '<i class="fa fa-check-square-o"></i>&nbsp; Database <b>Employee steps</b> created successfuly.<br>';
		}
	}else{
		$err_msg .= '<i class="fa fa-check-square-o"></i>&nbsp; Database <b>Employee steps</b> exists already.<br>';
	}
	
	$db_name = $cid."_approvals";
	if(!$dbc->query("DESCRIBE `$db_name`")) {
		$sql = "CREATE TABLE IF NOT EXISTS `$db_name` (
		  `id` int(11) NOT NULL,
		  `month` varchar(20) COLLATE utf8_bin NOT NULL,
		  `year` int(11) NOT NULL,
		  `type` varchar(10) COLLATE utf8_bin NOT NULL,
		  `by_name` varchar(50) COLLATE utf8_bin NOT NULL,
		  `by_id` varchar(20) COLLATE utf8_bin NOT NULL,
		  `on_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
		  `action` varchar(5) COLLATE utf8_bin NOT NULL,
		  `comment` text COLLATE utf8_bin NOT NULL,
		  `attachment` varchar(255) COLLATE utf8_bin NOT NULL,
		  PRIMARY KEY (`id`) 
		) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_bin";
		if(!$dbc->query($sql)){
			$err_msg .= '<span style="color:#c00"><i class="fa fa-times-circle"></i>&nbsp; Create database <b>Approvals</b> failed. Error : <b>'.mysqli_error($dbc).'</b></span><br>';
			$error = true;
		}else{
			$err_msg .= '<i class="fa fa-check-square-o"></i>&nbsp; Database <b>Approvals</b> created successfuly.<br>';
		}
	}else{
		$err_msg .= '<i class="fa fa-check-square-o"></i>&nbsp; Database <b>Approvals</b> exists already.<br>';
	}
	
	$db_name = $cid."_users";
	if(!$dbc->query("DESCRIBE `$db_name`")) {
		$sql = "CREATE TABLE IF NOT EXISTS `$db_name` (
			`id` int(11) NOT NULL AUTO_INCREMENT,
			`ref` int(11) NOT NULL,
			`username` varchar(50) COLLATE utf8_bin NOT NULL,
			`emp_id` varchar(10) COLLATE utf8_bin NOT NULL,
			`firstname` varchar(20) COLLATE utf8_bin NOT NULL,
			`name` varchar(50) COLLATE utf8_bin NOT NULL,
			`phone` varchar(20) COLLATE utf8_bin NOT NULL,
			`type` varchar(10) COLLATE utf8_bin NOT NULL,
			`entities` varchar(255) COLLATE utf8_bin NOT NULL,
			`branches` varchar(255) COLLATE utf8_bin NOT NULL,
			`emp_group` varchar(10) COLLATE utf8_bin NOT NULL,
			`groups` varchar(255) COLLATE utf8_bin NOT NULL,
			`departments` varchar(255) COLLATE utf8_bin NOT NULL,
			`teams` varchar(255) COLLATE utf8_bin NOT NULL,
			`permissions` text COLLATE utf8_bin NOT NULL,
			`img` varchar(100) COLLATE utf8_bin NOT NULL,
			`status` int(11) NOT NULL,
			`emp_cols` text COLLATE utf8_bin NOT NULL,
			`att_cols` text COLLATE utf8_bin NOT NULL,		  
			PRIMARY KEY (`id`) 
		) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_bin";
		if(!$dbc->query($sql)){
			$err_msg .= '<span style="color:#c00"><i class="fa fa-times-circle"></i>&nbsp; Create database <b>User permissions</b> failed. Error : <b>'.mysqli_error($dbc).'</b></span><br>';
			$error = true;
		}else{
			$err_msg .= '<i class="fa fa-check-square-o"></i>&nbsp; Database <b>User permissions</b> created successfuly.<br>';
		}
	}else{
		$err_msg .= '<i class="fa fa-check-square-o"></i>&nbsp; Database <b>User permissions</b> exists already.<br>';
	}
	
	$db_name = $cid."_documents";
	if(!$dbc->query("DESCRIBE `$db_name`")) {
		$sql = "CREATE TABLE IF NOT EXISTS `$db_name` (
		  `id` int(11) NOT NULL AUTO_INCREMENT,
		  `filename` varchar(255) COLLATE utf8_bin NOT NULL,
		  `name` varchar(100) COLLATE utf8_bin NOT NULL,
		  `month` int(11) NOT NULL,
		  `year` int(11) NOT NULL,
		  `size` double NOT NULL,
		  `type` varchar(10) COLLATE utf8_bin NOT NULL,
		  `date` varchar(20) COLLATE utf8_bin NOT NULL,
		  `user_name` varchar(50) COLLATE utf8_bin NOT NULL,
		  `link` varchar(255) COLLATE utf8_bin NOT NULL,
		  PRIMARY KEY (`id`) 
		) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_bin";
		if(!$dbc->query($sql)){
			$err_msg .= '<span style="color:#c00"><i class="fa fa-times-circle"></i>&nbsp; Create database <b>Documents</b> failed. Error : <b>'.mysqli_error($dbc).'</b></span><br>';
			$error = true;
		}else{
			$err_msg .= '<i class="fa fa-check-square-o"></i>&nbsp; Database <b>Documents</b> created successfuly.<br>';
		}
	}else{
		$err_msg .= '<i class="fa fa-check-square-o"></i>&nbsp; Database <b>Documents</b> exists already.<br>';
	}
	
	$db_name = $cid."_scanfiles";
	if(!$dbc->query("DESCRIBE `$db_name`")) {
		$sql = "CREATE TABLE IF NOT EXISTS `$db_name` (
		  `id` int(11) NOT NULL AUTO_INCREMENT,
		  `date` date NOT NULL,
		  `period` varchar(50) COLLATE utf8_bin NOT NULL,
		  `content` text COLLATE utf8_bin NOT NULL,
		  `filename` varchar(50) COLLATE utf8_bin NOT NULL,
		  `import` int(11) NOT NULL,
		  `status` int(11) NOT NULL, 
		  PRIMARY KEY (`id`) 
		) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_bin";
		if(!$dbc->query($sql)){
			$err_msg .= '<span style="color:#c00"><i class="fa fa-times-circle"></i>&nbsp; Create database <b>Scan files</b> failed. Error : <b>'.mysqli_error($dbc).'</b></span><br>';
			$error = true;
		}else{
			$err_msg .= '<i class="fa fa-check-square-o"></i>&nbsp; Database <b>Scan files</b> created successfuly.<br>';
		}
	}else{
		$err_msg .= '<i class="fa fa-check-square-o"></i>&nbsp; Database <b>Scan files</b> exists already.<br>';
	}
	
	$db_name = $cid."_monthly_shiftplans_".$year;
	if(!$dbc->query("DESCRIBE `$db_name`")) {
		$sql = "CREATE TABLE IF NOT EXISTS `$db_name` (
		  `id` varchar(50) COLLATE utf8_bin NOT NULL,
		  `month` varchar(5) COLLATE utf8_bin NOT NULL,
		  `emp_id` varchar(50) COLLATE utf8_bin NOT NULL,
		  `sid` varchar(20) COLLATE utf8_bin NOT NULL,
		  `en_name` varchar(50) COLLATE utf8_bin NOT NULL,
		  `th_name` varchar(50) COLLATE utf8_bin NOT NULL,
		  `shiftteam` varchar(50) COLLATE utf8_bin NOT NULL,
		  `D1` varchar(20) COLLATE utf8_bin NOT NULL,
		  `D2` varchar(20) COLLATE utf8_bin NOT NULL,
		  `D3` varchar(20) COLLATE utf8_bin NOT NULL,
		  `D4` varchar(20) COLLATE utf8_bin NOT NULL,
		  `D5` varchar(20) COLLATE utf8_bin NOT NULL,
		  `D6` varchar(20) COLLATE utf8_bin NOT NULL,
		  `D7` varchar(20) COLLATE utf8_bin NOT NULL,
		  `D8` varchar(20) COLLATE utf8_bin NOT NULL,
		  `D9` varchar(20) COLLATE utf8_bin NOT NULL,
		  `D10` varchar(20) COLLATE utf8_bin NOT NULL,
		  `D11` varchar(20) COLLATE utf8_bin NOT NULL,
		  `D12` varchar(20) COLLATE utf8_bin NOT NULL,
		  `D13` varchar(20) COLLATE utf8_bin NOT NULL,
		  `D14` varchar(20) COLLATE utf8_bin NOT NULL,
		  `D15` varchar(20) COLLATE utf8_bin NOT NULL,
		  `D16` varchar(20) COLLATE utf8_bin NOT NULL,
		  `D17` varchar(20) COLLATE utf8_bin NOT NULL,
		  `D18` varchar(20) COLLATE utf8_bin NOT NULL,
		  `D19` varchar(20) COLLATE utf8_bin NOT NULL,
		  `D20` varchar(20) COLLATE utf8_bin NOT NULL,
		  `D21` varchar(20) COLLATE utf8_bin NOT NULL,
		  `D22` varchar(20) COLLATE utf8_bin NOT NULL,
		  `D23` varchar(20) COLLATE utf8_bin NOT NULL,
		  `D24` varchar(20) COLLATE utf8_bin NOT NULL,
		  `D25` varchar(20) COLLATE utf8_bin NOT NULL,
		  `D26` varchar(20) COLLATE utf8_bin NOT NULL,
		  `D27` varchar(20) COLLATE utf8_bin NOT NULL,
		  `D28` varchar(20) COLLATE utf8_bin NOT NULL,
		  `D29` varchar(20) COLLATE utf8_bin NOT NULL,
		  `D30` varchar(20) COLLATE utf8_bin NOT NULL,
		  `D31` varchar(20) COLLATE utf8_bin NOT NULL, 
		  PRIMARY KEY (`id`) 
		) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_bin";
		if(!$dbc->query($sql)){
			$err_msg .= '<span style="color:#c00"><i class="fa fa-times-circle"></i>&nbsp; Create database <b>Monthly shiftplans</b> failed. Error : <b>'.mysqli_error($dbc).'</b></span><br>';
			$error = true;
		}else{
			$err_msg .= '<i class="fa fa-check-square-o"></i>&nbsp; Database <b>Monthly shiftplans</b> created successfuly.<br>';
		}
	}else{
		$err_msg .= '<i class="fa fa-check-square-o"></i>&nbsp; Database <b>Monthly shiftplans</b> exists already.<br>';
	}
	
	$db_name = $cid."_attendance";
	if(!$dbc->query("DESCRIBE `$db_name`")) {
		$sql = "CREATE TABLE IF NOT EXISTS `$db_name` (
			`id` varchar(50) COLLATE utf8_bin NOT NULL,
			`month` int(11) NOT NULL,
			`emp_id` varchar(50) COLLATE utf8_bin NOT NULL,
			`en_name` varchar(50) COLLATE utf8_bin NOT NULL,
			`th_name` varchar(50) COLLATE utf8_bin NOT NULL,
			`date` date NOT NULL,
			`day` varchar(20) COLLATE utf8_bin NOT NULL,
			`dnr` int(11) NOT NULL,
			`plan` varchar(50) COLLATE utf8_bin NOT NULL,
			`plan_hrs` varchar(50) COLLATE utf8_bin NOT NULL,
			`hd` int(11) NOT NULL,
			`shiftteam` varchar(50) COLLATE utf8_bin NOT NULL,
			`f1` varchar(20) COLLATE utf8_bin NOT NULL,
			`u1` varchar(20) COLLATE utf8_bin NOT NULL,
			`f2` varchar(20) COLLATE utf8_bin NOT NULL,
			`u2` varchar(20) COLLATE utf8_bin NOT NULL,
			`ot_plan` int(11) NOT NULL,
			`ot_from` varchar(10) COLLATE utf8_bin NOT NULL,
			`ot_until` varchar(10) COLLATE utf8_bin NOT NULL,
			`ot_hrs` double NOT NULL,
			`ot_break` double NOT NULL,
			`ot_type` varchar(10) COLLATE utf8_bin NOT NULL,
			`ot_compensations` text COLLATE utf8_bin NOT NULL,
			`scan1` varchar(10) COLLATE utf8_bin NOT NULL DEFAULT '-',
			`scan2` varchar(10) COLLATE utf8_bin NOT NULL DEFAULT '-',
			`scan3` varchar(10) COLLATE utf8_bin NOT NULL DEFAULT '-',
			`scan4` varchar(10) COLLATE utf8_bin NOT NULL DEFAULT '-',
			`scan5` varchar(10) COLLATE utf8_bin NOT NULL,
			`scan6` varchar(10) COLLATE utf8_bin NOT NULL,
			`scan7` varchar(10) COLLATE utf8_bin NOT NULL,
			`scan8` varchar(10) COLLATE utf8_bin NOT NULL,
			`scan9` varchar(10) COLLATE utf8_bin NOT NULL,
			`all_scans` varchar(255) COLLATE utf8_bin NOT NULL,
			`img1` varchar(30) COLLATE utf8_bin NOT NULL,
			`img2` varchar(30) COLLATE utf8_bin NOT NULL,
			`img3` varchar(30) COLLATE utf8_bin NOT NULL,
			`img4` varchar(30) COLLATE utf8_bin NOT NULL,
			`img5` varchar(30) COLLATE utf8_bin NOT NULL,
			`img6` varchar(30) COLLATE utf8_bin NOT NULL,
			`img7` varchar(30) COLLATE utf8_bin NOT NULL,
			`img8` varchar(30) COLLATE utf8_bin NOT NULL,
			`img9` varchar(30) COLLATE utf8_bin NOT NULL,
			`loc1` varchar(50) COLLATE utf8_bin NOT NULL,
			`loc2` varchar(50) COLLATE utf8_bin NOT NULL,
			`loc3` varchar(50) COLLATE utf8_bin NOT NULL,
			`loc4` varchar(50) COLLATE utf8_bin NOT NULL,
			`loc5` varchar(50) COLLATE utf8_bin NOT NULL,
			`loc6` varchar(50) COLLATE utf8_bin NOT NULL,
			`loc7` varchar(50) COLLATE utf8_bin NOT NULL,
			`loc8` varchar(50) COLLATE utf8_bin NOT NULL,
			`loc9` varchar(50) COLLATE utf8_bin NOT NULL,
			`planned_days` double NOT NULL,
			`planned_hrs` double NOT NULL,
			`actual_hrs` double NOT NULL,
			`normal_hrs` double NOT NULL,
			`paid_hrs` double NOT NULL,
			`plan_ot` varchar(20) COLLATE utf8_bin NOT NULL,
			`planned_ot` double NOT NULL,
			`plan_break` double NOT NULL,
			`paid_late` double NOT NULL,
			`paid_early` double NOT NULL,
			`unpaid_late` double NOT NULL,
			`unpaid_early` double NOT NULL,
			`public` double NOT NULL,
			`personal` double NOT NULL,
			`unpaid_leave` double NOT NULL,
			`ot1` double NOT NULL,
			`ot15` double NOT NULL,
			`ot2` double NOT NULL,
			`ot3` double NOT NULL,
			`leave_type` varchar(10) COLLATE utf8_bin NOT NULL,
			`leave_days` double NOT NULL,
			`leave_day` varchar(255) COLLATE utf8_bin NOT NULL,
			`leave_paid` varchar(255) COLLATE utf8_bin NOT NULL,
			`leave_hrs` double NOT NULL,
			`comp1` int(11) NOT NULL,
			`comp2` int(11) NOT NULL,
			`comp3` int(11) NOT NULL,
			`comp4` int(11) NOT NULL,
			`comp5` int(11) NOT NULL,
			`comp6` int(11) NOT NULL,
			`comp7` int(11) NOT NULL,
			`comp8` int(11) NOT NULL,
			`comp9` int(11) NOT NULL,
			`comp10` int(11) NOT NULL,
			`var_allow_1` double NOT NULL,
			`var_allow_2` double NOT NULL,
			`var_allow_3` double NOT NULL,
			`var_allow_4` double NOT NULL,
			`var_allow_5` double NOT NULL,
			`var_allow_6` double NOT NULL,
			`var_allow_7` double NOT NULL,
			`var_allow_8` double NOT NULL,
			`var_allow_9` double NOT NULL,
			`var_allow_10` double NOT NULL,
			`remarks` varchar(255) COLLATE utf8_bin NOT NULL,
			`comment` int(11) NOT NULL,
			`status` int(11) NOT NULL,
			`approved` int(11) NOT NULL,
			`locked` int(11) NOT NULL,
			`normal_days` double NOT NULL,
			`paid_days` double NOT NULL,
			`actual_days` double NOT NULL,
		  PRIMARY KEY (`id`) 
		) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_bin";
		if(!$dbc->query($sql)){
			$err_msg .= '<span style="color:#c00"><i class="fa fa-times-circle"></i>&nbsp; Create database <b>Attendance (Time)</b> failed. Error : <b>'.mysqli_error($dbc).'</b></span><br>';
			$error = true;
		}else{
			$err_msg .= '<i class="fa fa-check-square-o"></i>&nbsp; Database <b>Attendance (Time)</b> created successfuly.<br>';
		}
	}else{
		$err_msg .= '<i class="fa fa-check-square-o"></i>&nbsp; Database <b>Attendance (Time)</b> exists already.<br>';
	}
	
	$db_name = $cid."_leaves_data";
	if(!$dbc->query("DESCRIBE `$db_name`")) {
		$sql = "CREATE TABLE IF NOT EXISTS `$db_name` (
		  `id` int(11) NOT NULL AUTO_INCREMENT,
		  `leave_id` int(11) NOT NULL,
		  `emp_id` varchar(20) COLLATE utf8_bin NOT NULL,
		  `name` varchar(50) COLLATE utf8_bin NOT NULL,
		  `phone` varchar(20) COLLATE utf8_bin NOT NULL,
		  `leave_type` varchar(10) COLLATE utf8_bin NOT NULL,
		  `date` date NOT NULL,
		  `day` varchar(20) COLLATE utf8_bin NOT NULL,
		  `days` float NOT NULL,
		  `paid` int(11) NOT NULL,
			`hours` double NOT NULL,
		  `status` varchar(5) COLLATE utf8_bin NOT NULL,
		  `certificate` int(11) NOT NULL DEFAULT '1',
		  `reason` varchar(255) COLLATE utf8_bin NOT NULL,
		  `lock` int(11) NOT NULL,
		  PRIMARY KEY (`id`) 
		) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_bin";
		if(!$dbc->query($sql)){
			$err_msg .= '<span style="color:#c00"><i class="fa fa-times-circle"></i>&nbsp; Create database <b>Leaves data</b> failed. Error : <b>'.mysqli_error($dbc).'</b></span><br>';
			$error = true;
		}else{
			$err_msg .= '<i class="fa fa-check-square-o"></i>&nbsp; Database <b>Leaves data</b> created successfuly.<br>';
		}
	}else{
		$err_msg .= '<i class="fa fa-check-square-o"></i>&nbsp; Database <b>Leaves data</b> exists already.<br>';
	}
	
/*	$db_name = $cid."_prepayroll";
	if(!$dbc->query("DESCRIBE `$db_name`")) {
		$sql = "CREATE TABLE IF NOT EXISTS `$db_name` (
		    `id` varchar(50) COLLATE utf8_bin NOT NULL,
				`month` int(11) NOT NULL,
				`emp_id` varchar(50) COLLATE utf8_bin NOT NULL,
				`en_name` varchar(50) COLLATE utf8_bin NOT NULL,
				`th_name` varchar(50) COLLATE utf8_bin NOT NULL,
				`planned_days` double NOT NULL,
				`normal_days` double NOT NULL,
				`paid_days` double NOT NULL,
				`planned_hrs` double NOT NULL,
				`normal_hrs` double NOT NULL,
				`paid_hrs` double NOT NULL,
				`normal` double NOT NULL,
				`step` int(11) NOT NULL,
				`ot1h` double NOT NULL,
				`ot15h` double NOT NULL,
				`ot2h` double NOT NULL,
				`ot3h` double NOT NULL,
				`absence` double NOT NULL,
				`unpaid_leave` double NOT NULL,
				`late_early_paid` double NOT NULL,
				`late_early_unpaid` double NOT NULL,
				`personal` double NOT NULL,
				`public` double NOT NULL,
				`comp1` int(11) NOT NULL,
				`comp2` int(11) NOT NULL,
				`comp3` int(11) NOT NULL,
				`comp4` int(11) NOT NULL,
				`comp5` int(11) NOT NULL,
				`comp6` int(11) NOT NULL,
				`comp7` int(11) NOT NULL,
				`comp8` int(11) NOT NULL,
				`comp9` int(11) NOT NULL,
				`comp10` int(11) NOT NULL,
				`var_allow_1` double NOT NULL,
				`var_allow_2` double NOT NULL,
				`var_allow_3` double NOT NULL,
				`var_allow_4` double NOT NULL,
				`var_allow_5` double NOT NULL,
				`var_allow_6` double NOT NULL,
				`var_allow_7` double NOT NULL,
				`var_allow_8` double NOT NULL,
				`var_allow_9` double NOT NULL,
				`var_allow_10` double NOT NULL,
				`status` int(11) NOT NULL,
		  PRIMARY KEY (`id`) 
		) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_bin";
		if(!$dbc->query($sql)){
			$err_msg .= '<span style="color:#c00"><i class="fa fa-times-circle"></i>&nbsp; Create database <b>Pre-Payroll</b> failed. Error : <b>'.mysqli_error($dbc).'</b></span><br>';
			$error = true;
		}else{
			$err_msg .= '<i class="fa fa-check-square-o"></i>&nbsp; Database <b>Pre-Payroll</b> created successfuly.<br>';
		}
	}else{
		$err_msg .= '<i class="fa fa-check-square-o"></i>&nbsp; Database <b>Pre-Payroll</b> exists already.<br>';
	}
*/	

$db_name = $cid."_leaves";
	if(!$dbc->query("DESCRIBE `$db_name`")) {
		$sql = "CREATE TABLE IF NOT EXISTS `$db_name` (
		  `id` int(11) NOT NULL AUTO_INCREMENT,
		  `emp_id` varchar(20) COLLATE utf8_bin NOT NULL,
		  `name` varchar(50) COLLATE utf8_bin NOT NULL,
		  `phone` varchar(20) COLLATE utf8_bin NOT NULL,
		  `leave_type` varchar(50) COLLATE utf8_bin NOT NULL,
		  `start` date NOT NULL,
		  `end` date NOT NULL,
		  `days` float NOT NULL,
		  `details` text COLLATE utf8_bin NOT NULL,
		  `status` varchar(50) COLLATE utf8_bin NOT NULL,
		  `comment` text COLLATE utf8_bin NOT NULL,
		  `reason` varchar(255) COLLATE utf8_bin NOT NULL,
		  `certificate` varchar(10) COLLATE utf8_bin NOT NULL,
		  `attach` varchar(100) COLLATE utf8_bin NOT NULL,
		  `created` varchar(50) COLLATE utf8_bin NOT NULL,
		  `created_by` varchar(50) COLLATE utf8_bin NOT NULL,
		  `updated` varchar(50) COLLATE utf8_bin NOT NULL,
		  `updated_by` varchar(50) COLLATE utf8_bin NOT NULL,
		  `approved` varchar(50) COLLATE utf8_bin NOT NULL,
		  `approved_by` varchar(50) COLLATE utf8_bin NOT NULL,
		  `rejected` varchar(50) COLLATE utf8_bin NOT NULL,
		  `rejected_by` varchar(50) COLLATE utf8_bin NOT NULL,
		  `canceled` varchar(50) COLLATE utf8_bin NOT NULL,
		  `canceled_by` varchar(50) COLLATE utf8_bin NOT NULL,
		  `log` int(11) NOT NULL DEFAULT '1',
		  PRIMARY KEY (`id`) 
		) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_bin";
		if(!$dbc->query($sql)){
			$err_msg .= '<span style="color:#c00"><i class="fa fa-times-circle"></i>&nbsp; Create database <b>Leaves</b> failed. Error : <b>'.mysqli_error($dbc).'</b></span><br>';
			$error = true;
		}else{
			$err_msg .= '<i class="fa fa-check-square-o"></i>&nbsp; Database <b>Leaves</b> created successfuly.<br>';
		}
	}else{
		$err_msg .= '<i class="fa fa-check-square-o"></i>&nbsp; Database <b>Leaves</b> exists already.<br>';
	}
	
	$db_name = $cid."_company_settings";
	if(!$dbc->query("DESCRIBE `$db_name`")) {
		$sql = "CREATE TABLE IF NOT EXISTS `$db_name` (
		  `id` int(11) NOT NULL,
		  `en_compname` varchar(50) COLLATE utf8_bin NOT NULL,
		  `th_compname` varchar(50) COLLATE utf8_bin NOT NULL,
		  `bank_acc_name` varchar(50) COLLATE utf8_bin NOT NULL,
		  `years` text COLLATE utf8_bin NOT NULL,
		  `cur_year` int(11) NOT NULL,
		  `branch` varchar(10) COLLATE utf8_bin NOT NULL,
		  `branch_sso` varchar(10) COLLATE utf8_bin NOT NULL,
		  `tax_id` varchar(20) COLLATE utf8_bin NOT NULL,
		  `comp_phone` varchar(20) COLLATE utf8_bin NOT NULL,
		  `comp_fax` varchar(20) COLLATE utf8_bin NOT NULL,
		  `comp_email` varchar(50) COLLATE utf8_bin NOT NULL,
		  `en_address` text COLLATE utf8_bin NOT NULL,
		  `th_address` text COLLATE utf8_bin NOT NULL,
		  `th_addr_detail` text COLLATE utf8_bin NOT NULL,
		  `en_addr_detail` text COLLATE utf8_bin NOT NULL,
		  `logofile` varchar(50) COLLATE utf8_bin NOT NULL,
		  `dig_stamp` varchar(100) COLLATE utf8_bin NOT NULL,
		  `dig_signature` varchar(100) COLLATE utf8_bin NOT NULL,
		  `digi_stamp` int(11) NOT NULL,
		  `digi_signature` int(11) NOT NULL,
		  `bus_reg` varchar(100) COLLATE utf8_bin NOT NULL,
		  `comp_affi` varchar(100) COLLATE utf8_bin NOT NULL,
		  `house_reg` varchar(100) COLLATE utf8_bin NOT NULL,
		  `vat_reg` varchar(100) COLLATE utf8_bin NOT NULL,
		  `socsec_fund` varchar(100) COLLATE utf8_bin NOT NULL,
		  `bankbook` varchar(100) COLLATE utf8_bin NOT NULL,
		  `passfs` varchar(100) COLLATE utf8_bin NOT NULL,
		  `paw_tax` varchar(50) COLLATE utf8_bin NOT NULL,
		  `attach1` varchar(50) COLLATE utf8_bin NOT NULL,
		  `attach2` varchar(50) COLLATE utf8_bin NOT NULL,
		  `attach3` varchar(50) COLLATE utf8_bin NOT NULL,
		  `bank_name` varchar(50) COLLATE utf8_bin NOT NULL,
		  `bank_account` varchar(20) COLLATE utf8_bin NOT NULL,
		  PRIMARY KEY (`id`)
			) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_bin";
		if(!$dbc->query($sql)){
			$err_msg .= '<span style="color:#c00"><i class="fa fa-times-circle"></i>&nbsp; Create database <b>Company settings</b> failed. Error : <b>'.mysqli_error($dbc).'</b></span><br>';
			$error = true;
		}else{
			$err_msg .= '<i class="fa fa-check-square-o"></i>&nbsp; Database <b>Company settings</b> created successfuly.<br>';
		}
	}else{
		$err_msg .= '<i class="fa fa-check-square-o"></i>&nbsp; Database <b>Company settings</b> exists already.<br>';
	}

	$db_name = $cid."_payroll_".$year;
	if(!$dbc->query("DESCRIBE `$db_name`")) { 
		$sql = "CREATE TABLE IF NOT EXISTS `$db_name` (
		  `id` varchar(50) COLLATE utf8_bin NOT NULL,
		  `period` varchar(10) COLLATE utf8_bin NOT NULL,
		  `emp_id` varchar(20) COLLATE utf8_bin NOT NULL,
		  `month` int(11) NOT NULL,
		  `bank` varchar(50) COLLATE utf8_bin NOT NULL,
		  `account` varchar(20) COLLATE utf8_bin NOT NULL,
		  `emp_name_en` varchar(50) COLLATE utf8_bin NOT NULL,
		  `emp_name_th` varchar(50) COLLATE utf8_bin NOT NULL,
		  `position` varchar(50) COLLATE utf8_bin NOT NULL,
		  `basic_salary` double NOT NULL,
		  `salary` double NOT NULL,
		  `actual_days` double NOT NULL,
		  `paid_days` double NOT NULL,
		  `ot1h` double NOT NULL,
		  `ot1b` double NOT NULL,
		  `ot15h` double NOT NULL,
		  `ot15b` double NOT NULL,
		  `ot2h` double NOT NULL,
		  `ot2b` double NOT NULL,
		  `ot3h` double NOT NULL,
		  `ot3b` double NOT NULL,
		  `ooth` double NOT NULL,
		  `ootb` double NOT NULL,
		  `total_oth` double NOT NULL,
		  `total_otb` double NOT NULL,
		  `fix_allow_1` double NOT NULL,
		  `fix_allow_2` double NOT NULL,
		  `fix_allow_3` double NOT NULL,
		  `fix_allow_4` double NOT NULL,
		  `fix_allow_5` double NOT NULL,
		  `fix_allow_6` double NOT NULL,
		  `fix_allow_7` double NOT NULL,
		  `fix_allow_8` double NOT NULL,
		  `fix_allow_9` double NOT NULL,
		  `fix_allow_10` double NOT NULL,
		  `var_allow_1` double NOT NULL,
		  `var_allow_2` double NOT NULL,
		  `var_allow_3` double NOT NULL,
		  `var_allow_4` double NOT NULL,
		  `var_allow_5` double NOT NULL,
		  `var_allow_6` double NOT NULL,
		  `var_allow_7` double NOT NULL,
		  `var_allow_8` double NOT NULL,
		  `var_allow_9` double NOT NULL,
		  `var_allow_10` double NOT NULL,
		  `total_fix_allow` double NOT NULL,
		  `total_var_allow` double NOT NULL,
		  `total_fix_tax_allow` double NOT NULL,
		  `total_fix_non_allow` double NOT NULL,
		  `total_var_tax_allow` double NOT NULL,
		  `total_var_non_allow` double NOT NULL,
		  `total_tax_allow` double NOT NULL,
		  `total_non_allow` double NOT NULL,
		  `notice_payment` double NOT NULL,
		  `remaining_salary` double NOT NULL,
		  `paid_leave` double NOT NULL,
		  `other_income` double NOT NULL,
		  `severance` double NOT NULL,
		  `absence` double NOT NULL,
		  `absence_b` double NOT NULL,
		  `leave_wop` double NOT NULL,
		  `leave_wop_b` double NOT NULL,
		  `late_early` double NOT NULL,
		  `late_early_b` double NOT NULL,
		  `tot_absence` double NOT NULL,
		  `leave_wp` double NOT NULL,
		  `pvf_employee` double NOT NULL,
		  `pvf_employer` double NOT NULL,
		  `psf_employee` double NOT NULL,
		  `psf_employer` double NOT NULL,
		  `social` double NOT NULL,
		  `social_com` double NOT NULL,
		  `fix_deduct_1` double NOT NULL,
		  `fix_deduct_2` double NOT NULL,
		  `fix_deduct_3` double NOT NULL,
		  `fix_deduct_4` double NOT NULL,
		  `fix_deduct_5` double NOT NULL,
		  `var_deduct_1` double NOT NULL,
		  `var_deduct_2` double NOT NULL,
		  `var_deduct_3` double NOT NULL,
		  `var_deduct_4` double NOT NULL,
		  `var_deduct_5` double NOT NULL,
		  `fix_deduct_before` double NOT NULL,
		  `fix_deduct_after` double NOT NULL,
		  `var_deduct_before` double NOT NULL,
		  `var_deduct_after` double NOT NULL,
		  `tot_deduct_before` double NOT NULL,
		  `tot_deduct_after` float NOT NULL,
		  `tot_deductions` double NOT NULL,
		  `modify_tax` double NOT NULL,
		  `tax` double NOT NULL,
		  `tax_month` double NOT NULL,
		  `tax_next` double NOT NULL,
		  `tax_year` double NOT NULL,
		  `tot_fix_income` double NOT NULL,
		  `tot_var_income` double NOT NULL,
		  `ytd_income` double NOT NULL,
		  `prev_tax_income` double NOT NULL,
		  `gross_income` double NOT NULL,
		  `advance` double NOT NULL,
		  `net_income` double NOT NULL,
		  `paid` varchar(5) COLLATE utf8_bin NOT NULL,
		  `comment` text COLLATE utf8_bin NOT NULL,
		  `tax_calculation` text COLLATE utf8_bin NOT NULL,
		  `legal_deductions` double NOT NULL,
		  `calc_tax` varchar(5) COLLATE utf8_bin NOT NULL,
		  `calc_sso` varchar(5) COLLATE utf8_bin NOT NULL,
		  `calc_pvf` varchar(5) COLLATE utf8_bin NOT NULL,
		  PRIMARY KEY (`id`)
		) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin";
		if(!$dbc->query($sql)){
			$err_msg .= '<span style="color:#c00"><i class="fa fa-times-circle"></i>&nbsp; Create database <b>Payroll '.$year.'</b> failed. Error : <b>'.mysqli_error($dbc).'</b></span><br>';
			$error = true;
		}else{
			$err_msg .= '<i class="fa fa-check-square-o"></i>&nbsp; Database <b>Payroll '.$year.'</b> created successfuly.<br>';
		}
	}else{
		$err_msg .= '<i class="fa fa-check-square-o"></i>&nbsp; Database <b>Payroll '.$year.'</b> exists already.<br>';
	}
	
	/*$db_name = $cid."_workpermit";
	if(!$dbc->query("DESCRIBE `$db_name`")) { 
		$sql = "CREATE TABLE IF NOT EXISTS `$db_name` (
		  `emp_id` varchar(20) COLLATE utf8_bin NOT NULL,
		  `title` varchar(20) COLLATE utf8_bin NOT NULL,
		  `name_en` varchar(100) COLLATE utf8_bin NOT NULL,
		  `name_th` varchar(100) COLLATE utf8_bin NOT NULL,
		  `image` varchar(100) COLLATE utf8_bin NOT NULL,
		  `nationality` varchar(50) COLLATE utf8_bin NOT NULL,
		  `maritial` varchar(50) COLLATE utf8_bin NOT NULL,
		  `blood_group` varchar(20) COLLATE utf8_bin NOT NULL,
		  `birthdate` varchar(20) COLLATE utf8_bin NOT NULL,
		  `address` tinytext COLLATE utf8_bin NOT NULL,
		  `position` varchar(100) COLLATE utf8_bin NOT NULL,
		  `job_en` tinytext COLLATE utf8_bin NOT NULL,
		  `job_th` tinytext COLLATE utf8_bin NOT NULL,
		  `family` text COLLATE utf8_bin NOT NULL,
		  `attach_passport` varchar(255) COLLATE utf8_bin NOT NULL,
		  `attach_medical` varchar(255) COLLATE utf8_bin NOT NULL,
		  `attach_job_en` varchar(255) COLLATE utf8_bin NOT NULL,
		  `per_attach1` varchar(255) COLLATE utf8_bin NOT NULL,
		  `per_attach2` varchar(255) COLLATE utf8_bin NOT NULL,
		  `per_attach3` varchar(255) COLLATE utf8_bin NOT NULL,
		  `per_attach4` varchar(255) COLLATE utf8_bin NOT NULL,
		  `per_attach5` varchar(255) COLLATE utf8_bin NOT NULL,
		  `attach6` varchar(255) COLLATE utf8_bin NOT NULL,
		  `attach7` varchar(255) COLLATE utf8_bin NOT NULL,
		  `attach8` varchar(255) COLLATE utf8_bin NOT NULL,
		  `attach9` varchar(255) COLLATE utf8_bin NOT NULL,
		  `attach10` varchar(255) COLLATE utf8_bin NOT NULL,
		  PRIMARY KEY (`emp_id`)
		) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin";
		if(!$dbc->query($sql)){
			$err_msg .= '<span style="color:#c00"><i class="fa fa-times-circle"></i>&nbsp; Create database <b>Workpermit</b> failed. Error : <b>'.mysqli_error($dbc).'</b></span><br>';
			$error = true;
		}else{
			$err_msg .= '<i class="fa fa-check-square-o"></i>&nbsp; Database <b>Workpermit</b> created successfuly.<br>';
		}
	}else{
		$err_msg .= '<i class="fa fa-check-square-o"></i>&nbsp; Database <b>Workpermit</b> exists already.<br>';
	}*/
	
	//echo $err_msg; exit;
	
	$dbc = @new mysqli($my_database,$my_username,$my_password);
	$dbname = $prefix.$cid;
	if(empty(mysqli_fetch_array(mysqli_query($dbc,"SHOW DATABASES LIKE '$dbname'")))){
		 echo "Database not exist, please contact the site administrator.";
		 exit; 
	}else{
		 $dbc = @new mysqli($my_database,$my_username,$my_password,$prefix.$cid);
		 mysqli_set_charset($dbc,"utf8");
	}	
	
	$oldDir = $prefix.'regoadmin.';
	$newDir = $prefix.$cid.'.';
	
	$old_db_name = $oldDir.'rego_default_holidays';
	$new_db_name = $newDir.$cid.'_holidays';
	if(!$dbc->query("DESCRIBE $new_db_name")) {
		if($dbc->query("CREATE TABLE $new_db_name LIKE $old_db_name")){
			$sql = "INSERT IGNORE INTO $new_db_name SELECT * FROM $old_db_name";
			if(!$dbc->query($sql)){
				$err_msg .= '<span style="color:#c00"><i class="fa fa-times-circle"></i>&nbsp; Create database <b>Holidays</b> failed. Error 1 : <b>'.mysqli_error($dbc).'</b></span><br>';
				$error = true;
			}else{
				$err_msg .= '<i class="fa fa-check-square-o"></i>&nbsp; Database <b>Holidays</b> created successfuly.<br>';
			}
		}else{
			$err_msg .= '<span style="color:#c00"><i class="fa fa-times-circle"></i>&nbsp; Create database <b>Holidays</b> failed. Error 2 : <b>'.mysqli_error($dbc).'</b></span><br>';
			$error = true;
		}
	}else{
		$err_msg .= '<i class="fa fa-check-square-o"></i>&nbsp; Database <b>Holidays</b> exists already.<br>';
	}
	
	$old_db_name = $oldDir.'rego_default_leave_time_settings';
	$new_db_name = $newDir.$cid.'_leave_time_settings';
	if(!$dbc->query("DESCRIBE $new_db_name")) {
		if($dbc->query("CREATE TABLE $new_db_name LIKE $old_db_name")){
			$sql = "INSERT IGNORE INTO $new_db_name SELECT * FROM $old_db_name";
			if(!$dbc->query($sql)){
				$err_msg .= '<span style="color:#c00"><i class="fa fa-times-circle"></i>&nbsp; Create database <b>Leave & Time settings</b> failed. Error 1 : <b>'.mysqli_error($dbc).'</b></span><br>';
				$error = true;
			}else{
				$err_msg .= '<i class="fa fa-check-square-o"></i>&nbsp; Database <b>Leave & Time settings</b> created successfuly.<br>';
			}
		}else{
			$err_msg .= '<span style="color:#c00"><i class="fa fa-times-circle"></i>&nbsp; Create database <b>Leave & Time settings</b> failed. Error 2 : <b>'.mysqli_error($dbc).'</b></span><br>';
			$error = true;
		}
	}else{
		$err_msg .= '<i class="fa fa-check-square-o"></i>&nbsp; Database <b>Leave & Time settings</b> exists already.<br>';
	}
	
	$old_db_name = $oldDir.'rego_default_shiftplans';
	$new_db_name = $newDir.$cid.'_shiftplans_'.$year;
	if(!$dbc->query("DESCRIBE $new_db_name")) {
		if($dbc->query("CREATE TABLE $new_db_name LIKE $old_db_name")){
			$sql = "INSERT IGNORE INTO $new_db_name SELECT * FROM $old_db_name";
			if(!$dbc->query($sql)){
				$err_msg .= '<span style="color:#c00"><i class="fa fa-times-circle"></i>&nbsp; Create database <b>Shiftplans</b> failed. Error 1 : <b>'.mysqli_error($dbc).'</b></span><br>';
				$error = true;
			}else{
				$err_msg .= '<i class="fa fa-check-square-o"></i>&nbsp; Database <b>Shiftplans</b> created successfuly.<br>';
			}
		}else{
			$err_msg .= '<span style="color:#c00"><i class="fa fa-times-circle"></i>&nbsp; Create database <b>Shiftplans</b> failed. Error 2 : <b>'.mysqli_error($dbc).'</b></span><br>';
			$error = true;
		}
	}else{
		$err_msg .= '<i class="fa fa-check-square-o"></i>&nbsp; Database <b>Shiftplans</b> exists already.<br>';
	}
	









