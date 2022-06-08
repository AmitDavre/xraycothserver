<?
	
	$dbc = new mysqli($my_database,$my_username,$my_password,$prefix.$cid);
	mysqli_set_charset($dbc,"utf8");
	
	$err_msg .= '<div style="color:#a00; font-size:16px; font-weight:600; border-bottom:1px solid #ccc; margin:5px 10px 2px 0">Create Databases</div>';
	
	$db_name = $cid."_employees";
	if(!$dbc->query("DESCRIBE `$db_name`")) {
		$sql = "CREATE TABLE IF NOT EXISTS `$db_name` (
		  `emp_id` varchar(20) COLLATE utf8_bin NOT NULL,
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
		  `bonus_months` int(11) NOT NULL,
		  `bonus_cash` double NOT NULL,
		  `yearbonus` double NOT NULL,
		  `bonus_payinmonth` int(11) NOT NULL,
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
		  `ssf_current_year_employee` double NOT NULL,
		  `pvf_current_year_employee` double NOT NULL,
		  `ssf_current_year_employer` double NOT NULL,
		  `pvf_current_year_employer` double NOT NULL,
		  `pvf_prior_years_employee` double NOT NULL,
		  `pvf_prior_years_employer` double NOT NULL,
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
		  PRIMARY KEY (`emp_id`)
		) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin";
		if(!$dbc->query($sql)){
			$err_msg .= '<span style="color:#c00"><i class="fa fa-times-circle"></i>&nbsp; Create database <b>Employees</b> failed. Error : Database <b>'.$prefix.$cid.'</b> does not exist.</span><br>';
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
		  `cur_month` int(11) NOT NULL,
		  `pr_startdate` varchar(20) COLLATE utf8_bin NOT NULL,
		  `th_addr_detail` text COLLATE utf8_bin NOT NULL,
		  `en_addr_detail` text COLLATE utf8_bin NOT NULL,
		  `sso_idnr` varchar(20) COLLATE utf8_bin NOT NULL,
		  `sso_act_max` varchar(5) COLLATE utf8_bin NOT NULL,
		  `pvf_idnr` varchar(20) COLLATE utf8_bin NOT NULL,
		  `pvf_applied` varchar(5) COLLATE utf8_bin NOT NULL DEFAULT 'N',
		  `pvf_rate_employee` int(11) NOT NULL,
		  `pvf_rate_employer` int(11) NOT NULL,
		  `tax_idnr` varchar(50) COLLATE utf8_bin NOT NULL,
		  `personal_idnr` varchar(20) COLLATE utf8_bin NOT NULL,
		  `tax_calc_method` varchar(10) COLLATE utf8_bin NOT NULL,
		  `positions` text COLLATE utf8_bin NOT NULL,
		  `fix_allow` text COLLATE utf8_bin NOT NULL,
		  `var_allow` text COLLATE utf8_bin NOT NULL,
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
		  `bonus_payinmonth` int(11) NOT NULL, 
		  `history` int(11) NOT NULL, 
		  `account_codes` text COLLATE utf8_bin NOT NULL, 
		  `account_allocations` text COLLATE utf8_bin NOT NULL, 
		  `demo` int(11) NOT NULL, 
		  PRIMARY KEY (`id`) 
		) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin";
		if(!$dbc->query($sql)){
			$err_msg .= '<span style="color:#c00"><i class="fa fa-times-circle"></i>&nbsp; Create database <b>Settings</b> failed. Error : Database <b>'.$prefix.$cid.'</b> does not exist.</span><br>';
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
		  `pvf_employee` double NOT NULL,
		  `tot_deduct` double NOT NULL,
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
			$err_msg .= '<span style="color:#c00"><i class="fa fa-times-circle"></i>&nbsp; Create database <b>Historic data</b> failed. Error : Database <b>'.$prefix.$cid.'</b> does not exist.</span><br>';
			$error = true;
		}else{
			$err_msg .= '<i class="fa fa-check-square-o"></i>&nbsp; Database <b>Historic data</b> created successfuly.<br>';
		}
	}else{
		$err_msg .= '<i class="fa fa-check-square-o"></i>&nbsp; Database <b>Historic data</b> exists already.<br>';
	}
	
	$db_name = $cid."_payroll_months";
	if(!$dbc->query("DESCRIBE `$db_name`")) {
		$sql = "CREATE TABLE IF NOT EXISTS `$db_name` (
		  `month` varchar(10) COLLATE utf8_bin NOT NULL,
		  `paydate` varchar(20) COLLATE utf8_bin NOT NULL,
		  `formdate` varchar(20) COLLATE utf8_bin NOT NULL,
		  `form_name` varchar(50) COLLATE utf8_bin NOT NULL,
		  `form_position` varchar(50) COLLATE utf8_bin NOT NULL,
		  `sso_act_max` varchar(10) COLLATE utf8_bin NOT NULL DEFAULT 'max',
		  `locked` int(11) NOT NULL,
		  `accounting` text COLLATE utf8_bin NOT NULL, 
		  PRIMARY KEY (`month`)
		) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_bin";
		if(!$dbc->query($sql)){
			$err_msg .= '<span style="color:#c00"><i class="fa fa-times-circle"></i>&nbsp; Create database <b>Payroll months</b> failed. Error : Database <b>'.$prefix.$cid.'</b> does not exist.</span><br>';
			$error = true;
		}else{
			$err_msg .= '<i class="fa fa-check-square-o"></i>&nbsp; Database <b>Payroll months</b> created successfuly.<br>';
		}
	}else{
		$err_msg .= '<i class="fa fa-check-square-o"></i>&nbsp; Database <b>Payroll months</b> exists already.<br>';
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
			$err_msg .= '<span style="color:#c00"><i class="fa fa-times-circle"></i>&nbsp; Create database <b>Documents</b> failed. Error : Database <b>'.$prefix.$cid.'</b> does not exist.</span><br>';
			$error = true;
		}else{
			$err_msg .= '<i class="fa fa-check-square-o"></i>&nbsp; Database <b>Documents</b> created successfuly.<br>';
		}
	}else{
		$err_msg .= '<i class="fa fa-check-square-o"></i>&nbsp; Database <b>Documents</b> exists already.<br>';
	}
	
	$db_name = $cid."_company_settings";
	if(!$dbc->query("DESCRIBE `$db_name`")) {
		$sql = "CREATE TABLE IF NOT EXISTS `$db_name` (
		  `id` int(11) NOT NULL,
		  `en_compname` varchar(50) COLLATE utf8_bin NOT NULL,
		  `th_compname` varchar(50) COLLATE utf8_bin NOT NULL,
		  `abreviation` varchar(10) COLLATE utf8_bin NOT NULL,
		  `years` text COLLATE utf8_bin NOT NULL,
		  `cur_year` int(11) NOT NULL,
		  `branch` varchar(10) COLLATE utf8_bin NOT NULL,
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
		  `positions` text COLLATE utf8_bin NOT NULL,
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
			$err_msg .= '<span style="color:#c00"><i class="fa fa-times-circle"></i>&nbsp; Create database <b>Company settings</b> failed. Error : Database <b>'.$prefix.$cid.'</b> does not exist.</span><br>';
			$error = true;
		}else{
			$err_msg .= '<i class="fa fa-check-square-o"></i>&nbsp; Database <b>Company settings</b> created successfuly.<br>';
		}
	}else{
		$err_msg .= '<i class="fa fa-check-square-o"></i>&nbsp; Database <b>Company settings</b> exists already.<br>';
	}

	$db_name = $cid."_payroll_".$year;
	if(!$dbc->query("DESCRIBE `$db_name`")) { // Staff payroll
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
		  `tot_fix_income` double NOT NULL,
		  `tot_var_income` double NOT NULL,
		  `remaining_salary` double NOT NULL,
		  `notice_payment` double NOT NULL,
		  `paid_leave` double NOT NULL,
		  `other_income` double NOT NULL,
		  `severance` double NOT NULL,
		  `total_fix_allow` double NOT NULL,
		  `total_var_allow` double NOT NULL,
		  `total_fix_tax_allow` double NOT NULL,
		  `total_fix_non_allow` double NOT NULL,
		  `total_var_tax_allow` double NOT NULL,
		  `total_var_non_allow` double NOT NULL,
		  `total_tax_allow` double NOT NULL,
		  `total_non_allow` double NOT NULL,
		  `prev_tax_income` double NOT NULL,
		  `bonus` double NOT NULL,
		  `bonus_months` double NOT NULL,
		  `bonus_cash` double NOT NULL,
		  `bonus_taxable` varchar(10) COLLATE utf8_bin NOT NULL,
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
		  `social` double NOT NULL,
		  `tax` double NOT NULL,
		  `prev_tax` double NOT NULL,
		  `uniform` double NOT NULL,
		  `deduct_2` double NOT NULL,
		  `deduct_3` double NOT NULL,
		  `tot_deduct` double NOT NULL,
		  `tot_deductions` double NOT NULL,
		  `legal_deductions` DOUBLE NOT NULL,
		  `advance` double NOT NULL,
		  `gross_income` double NOT NULL,
		  `net_income` double NOT NULL,
		  `paid` varchar(5) COLLATE utf8_bin NOT NULL,
		  `comment` text COLLATE utf8_bin NOT NULL,
		  `tax_calculation` text COLLATE utf8_bin NOT NULL, 
		  PRIMARY KEY (`id`),
		  UNIQUE KEY `id` (`id`)
		) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin";
		if(!$dbc->query($sql)){
			$err_msg .= '<span style="color:#c00"><i class="fa fa-times-circle"></i>&nbsp; Create database <b>Payroll '.$year.'</b> failed. Error : Database <b>'.$prefix.$cid.'</b> does not exist.</span><br>';
			$error = true;
		}else{
			$err_msg .= '<i class="fa fa-check-square-o"></i>&nbsp; Database <b>Payroll '.$year.'</b> created successfuly.<br>';
		}
	}else{
		$err_msg .= '<i class="fa fa-check-square-o"></i>&nbsp; Database <b>Payroll '.$year.'</b> exists already.<br>';
	}
	
	//echo $err_msg; exit;

?>










