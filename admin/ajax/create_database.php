<?php

	$dbc = @new mysqli($my_database,$my_username,$my_password);
	$dbname = $prefix.$cid;
	if(empty(mysqli_fetch_array(mysqli_query($dbc,"SHOW DATABASES LIKE '$dbname'")))){
		 echo '<div style="color:#a00; font-size:16px; font-weight:600; border-bottom:1px solid #ccc; margin:0px 10px 2px 0">Database '.strtoupper($cid).' not exist, please contact the site administrator.</div>';
		 exit; 
	}else{
		 $dbc = @new mysqli($my_database,$my_username,$my_password,$prefix.$cid);
		 mysqli_set_charset($dbc,"utf8");
	}	
	
	$err_msg .= '<div style="color:#a00; font-size:16px; font-weight:600; border-bottom:1px solid #ccc; margin:0px 10px 2px 0">Create Databases</div>';
	
	$db_name = $cid."_approvals";
	if(!$dbc->query("DESCRIBE `$db_name`")) {
		$sql = "CREATE TABLE IF NOT EXISTS `$db_name` (
		  `id` int(11) NULL AUTO_INCREMENT,
		  `month` varchar(20) COLLATE utf8_bin  NULL,
		  `year` varchar(11)  NULL,
		  `type` varchar(10) COLLATE utf8_bin  NULL,
		  `by_name` varchar(50) COLLATE utf8_bin  NULL,
		  `by_id` varchar(20) COLLATE utf8_bin  NULL,
		  `on_date` timestamp  NULL DEFAULT CURRENT_TIMESTAMP,
		  `action` varchar(5) COLLATE utf8_bin  NULL,
		  `comment` text COLLATE utf8_bin  NULL,
		  `attachment` varchar(255) COLLATE utf8_bin  NULL,
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
	
	$db_name = $cid."_attendance";
	if(!$dbc->query("DESCRIBE `$db_name`")) {
		$sql = "CREATE TABLE IF NOT EXISTS `$db_name` (
			`id` varchar(50) COLLATE utf8_bin NULL,
			`month` varchar(11) NULL,
			`emp_id` varchar(50) COLLATE utf8_bin NULL,
			`en_name` varchar(50) COLLATE utf8_bin NULL,
			`th_name` varchar(50) COLLATE utf8_bin NULL,
			`date` date NULL,
			`day` varchar(20) COLLATE utf8_bin NULL,
			`dnr` varchar(11) NULL,
			`plan` varchar(50) COLLATE utf8_bin NULL,
			`plan_hrs` varchar(50) COLLATE utf8_bin NULL,
			`hd` varchar(11) NULL,
			`shiftteam` varchar(50) COLLATE utf8_bin NULL,
			`f1` varchar(20) COLLATE utf8_bin NULL,
			`u1` varchar(20) COLLATE utf8_bin NULL,
			`f2` varchar(20) COLLATE utf8_bin NULL,
			`u2` varchar(20) COLLATE utf8_bin NULL,
			`ot_plan` varchar(11) NULL,
			`ot_from` varchar(10) COLLATE utf8_bin NULL,
			`ot_until` varchar(10) COLLATE utf8_bin NULL,
			`ot_hrs` varchar(255) NULL,
			`ot_break` varchar(255) NULL,
			`ot_type` varchar(10) COLLATE utf8_bin NULL,
			`ot_compensations` text COLLATE utf8_bin NULL,
			`scan1` varchar(10) COLLATE utf8_bin NULL DEFAULT '-',
			`scan2` varchar(10) COLLATE utf8_bin NULL DEFAULT '-',
			`scan3` varchar(10) COLLATE utf8_bin NULL DEFAULT '-',
			`scan4` varchar(10) COLLATE utf8_bin NULL DEFAULT '-',
			`scan5` varchar(10) COLLATE utf8_bin NULL,
			`scan6` varchar(10) COLLATE utf8_bin NULL,
			`scan7` varchar(10) COLLATE utf8_bin NULL,
			`scan8` varchar(10) COLLATE utf8_bin NULL,
			`scan9` varchar(10) COLLATE utf8_bin NULL,
			`all_scans` varchar(255) COLLATE utf8_bin NULL,
			`img1` varchar(30) COLLATE utf8_bin NULL,
			`img2` varchar(30) COLLATE utf8_bin NULL,
			`img3` varchar(30) COLLATE utf8_bin NULL,
			`img4` varchar(30) COLLATE utf8_bin NULL,
			`img5` varchar(30) COLLATE utf8_bin NULL,
			`img6` varchar(30) COLLATE utf8_bin NULL,
			`img7` varchar(30) COLLATE utf8_bin NULL,
			`img8` varchar(30) COLLATE utf8_bin NULL,
			`img9` varchar(30) COLLATE utf8_bin NULL,
			`loc1` varchar(50) COLLATE utf8_bin NULL,
			`loc2` varchar(50) COLLATE utf8_bin NULL,
			`loc3` varchar(50) COLLATE utf8_bin NULL,
			`loc4` varchar(50) COLLATE utf8_bin NULL,
			`loc5` varchar(50) COLLATE utf8_bin NULL,
			`loc6` varchar(50) COLLATE utf8_bin NULL,
			`loc7` varchar(50) COLLATE utf8_bin NULL,
			`loc8` varchar(50) COLLATE utf8_bin NULL,
			`loc9` varchar(50) COLLATE utf8_bin NULL,
			`planned_days` varchar(255) NULL,
			`planned_hrs` varchar(255) NULL,
			`actual_hrs` varchar(255) NULL,
			`normal_hrs` varchar(255) NULL,
			`paid_hrs` varchar(255) NULL,
			`plan_ot` varchar(20) COLLATE utf8_bin NULL,
			`planned_ot` varchar(255) NULL,
			`plan_break` varchar(255) NULL,
			`paid_late` varchar(255) NULL,
			`paid_early` varchar(255) NULL,
			`unpaid_late` varchar(255) NULL,
			`unpaid_early` varchar(255) NULL,
			`public` varchar(255) NULL,
			`personal` varchar(255) NULL,
			`unpaid_leave` varchar(255) NULL,
			`ot1` varchar(255) NULL,
			`ot15` varchar(255) NULL,
			`ot2` varchar(255) NULL,
			`ot3` varchar(255) NULL,
			`leave_type` varchar(10) COLLATE utf8_bin NULL,
			`leave_days` varchar(255) NULL,
			`leave_day` varchar(255) COLLATE utf8_bin NULL,
			`leave_paid` varchar(255) COLLATE utf8_bin NULL,
			`leave_hrs` varchar(255) NULL,
			`comp1` varchar(11) NULL,
			`comp2` varchar(11) NULL,
			`comp3` varchar(11) NULL,
			`comp4` varchar(11) NULL,
			`comp5` varchar(11) NULL,
			`comp6` varchar(11) NULL,
			`comp7` varchar(11) NULL,
			`comp8` varchar(11) NULL,
			`comp9` varchar(11) NULL,
			`comp10` varchar(11) NULL,
			`var_allow_1` varchar(255) NULL,
			`var_allow_2` varchar(255) NULL,
			`var_allow_3` varchar(255) NULL,
			`var_allow_4` varchar(255) NULL,
			`var_allow_5` varchar(255) NULL,
			`var_allow_6` varchar(255) NULL,
			`var_allow_7` varchar(255) NULL,
			`var_allow_8` varchar(255) NULL,
			`var_allow_9` varchar(255) NULL,
			`var_allow_10` varchar(255) NULL,
			`remarks` varchar(255) COLLATE utf8_bin NULL,
			`comment` varchar(11) NULL,
			`status` varchar(11) NULL,
			`approved` varchar(11) NULL,
			`locked` varchar(11)  NULL,
			`normal_days` varchar(255) NULL,
			`paid_days` varchar(255) NULL,
			`actual_days` varchar(255) NULL,
			`filename` varchar(255) COLLATE utf8_bin NULL,
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
	
	$db_name = $cid."_branches";
	if(!$dbc->query("DESCRIBE `$db_name`")) {
		$sql = "CREATE TABLE IF NOT EXISTS `$db_name` (
			`id` int(11) NULL AUTO_INCREMENT,
			`code` varchar(20) COLLATE utf8_bin NULL,
			`th` varchar(100) COLLATE utf8_bin NULL,
			`en` varchar(100) COLLATE utf8_bin NULL,
			`entity` varchar(10) COLLATE utf8_bin NULL,		  
			PRIMARY KEY (`id`)
		) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_bin";
		if(!$dbc->query($sql)){
			$err_msg .= '<span style="color:#c00"><i class="fa fa-times-circle"></i>&nbsp; Create database <b>Branches</b> failed. Error : <b>'.mysqli_error($dbc).'</b></span><br>';
			$error = true;
		}else{
			$err_msg .= '<i class="fa fa-check-square-o"></i>&nbsp; Database <b>Branches</b> created successfuly.<br>';
		}
	}else{
		$err_msg .= '<i class="fa fa-check-square-o"></i>&nbsp; Database <b>Branches</b> exists already.<br>';
	}
	
	$db_name = $cid."_branches_data";
	if(!$dbc->query("DESCRIBE `$db_name`")) {
		$sql = "CREATE TABLE IF NOT EXISTS `$db_name` (
		  `ref` int(11) NULL AUTO_INCREMENT,
			`scan_system` varchar(20) COLLATE utf8_bin  NULL,
			`loc_name` varchar(50) COLLATE utf8_bin  NULL,
			`loc_code` varchar(100) COLLATE utf8_bin  NULL,
			`loc_qr` varchar(100) COLLATE utf8_bin  NULL,
			`latitude` varchar(20) COLLATE utf8_bin  NULL,
			`longitude` varchar(20) COLLATE utf8_bin  NULL,
			`perimeter` varchar(11)  NULL,
			`gps` varchar(11)  NULL,
			`bra_code` varchar(10) COLLATE utf8_bin  NULL,
			`bra_name_th` varchar(50) COLLATE utf8_bin  NULL,
			`bra_name_en` varchar(50) COLLATE utf8_bin  NULL,
			`bra_address_th` text COLLATE utf8_bin  NULL,
			`bra_address_en` text COLLATE utf8_bin  NULL,
			`ent_code` varchar(10) COLLATE utf8_bin  NULL,
			`ent_name_th` varchar(50) COLLATE utf8_bin  NULL,
			`ent_name_en` varchar(50) COLLATE utf8_bin  NULL,
			`ent_sso_account` varchar(20) COLLATE utf8_bin  NULL,
			`sso_code` varchar(10) COLLATE utf8_bin  NULL,
			`sso_name_th` varchar(50) COLLATE utf8_bin  NULL,
			`sso_name_en` varchar(50) COLLATE utf8_bin  NULL,
			`sso_address_th` text COLLATE utf8_bin  NULL,
			`sso_address_en` text COLLATE utf8_bin  NULL,
			`common_branch_id` varchar(255) COLLATE utf8_bin  NULL,
			`qrcodedata` text COLLATE utf8_bin  NULL,
			`serialno` varchar(255) COLLATE utf8_bin  NULL,
			`loc1` text COLLATE utf8_bin  NULL,
			`loc2` text COLLATE utf8_bin  NULL,
			`loc3` text COLLATE utf8_bin  NULL,
			`loc4` text COLLATE utf8_bin  NULL,
			`loc5` text COLLATE utf8_bin  NULL,
			PRIMARY KEY (`ref`)
		) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_bin";
		if(!$dbc->query($sql)){
			$err_msg .= '<span style="color:#c00"><i class="fa fa-times-circle"></i>&nbsp; Create database <b>Branches data</b> failed. Error : <b>'.mysqli_error($dbc).'</b></span><br>';
			$error = true;
		}else{
			$err_msg .= '<i class="fa fa-check-square-o"></i>&nbsp; Database <b>Branches data</b> created successfuly.<br>';
		}
	}else{
		$err_msg .= '<i class="fa fa-check-square-o"></i>&nbsp; Database <b>Branches data</b> exists already.<br>';
	}
	
	$db_name = $cid."_company_settings";
	if(!$dbc->query("DESCRIBE `$db_name`")) {
		$sql = "CREATE TABLE IF NOT EXISTS `$db_name` (
		  `id` varchar(11) NULL,
			`th_compname` varchar(50) COLLATE utf8_bin NULL,
			`en_compname` varchar(50) COLLATE utf8_bin NULL,
			`billing_th` text COLLATE utf8_bin NULL,
			`billing_en` text COLLATE utf8_bin NULL,
			`tax_id` varchar(20) COLLATE utf8_bin NULL,
			`wht` varchar(11) NULL,
			`email` varchar(50) COLLATE utf8_bin NULL,
			`logofile` varchar(50) COLLATE utf8_bin NULL,
			`latitude` varchar(20) COLLATE utf8_bin  NULL,
			`longitude` varchar(20) COLLATE utf8_bin  NULL,
			`logtime` varchar(11)  NULL,
			`emp_group` varchar(11)  NULL,
			`txt_color` varchar(10) COLLATE utf8_bin  NULL,
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
	
	$db_name = $cid."_departments";
	if(!$dbc->query("DESCRIBE `$db_name`")) {
		$sql = "CREATE TABLE IF NOT EXISTS `$db_name` (
			`id` int(11) NULL AUTO_INCREMENT,
			`code` varchar(20) COLLATE utf8_bin NULL,
			`th` varchar(100) COLLATE utf8_bin NULL,
			`en` varchar(100) COLLATE utf8_bin NULL,			
			PRIMARY KEY (`id`)
		) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_bin";
		if(!$dbc->query($sql)){
			$err_msg .= '<span style="color:#c00"><i class="fa fa-times-circle"></i>&nbsp; Create database <b>Departments</b> failed. Error : <b>'.mysqli_error($dbc).'</b></span><br>';
			$error = true;
		}else{
			$err_msg .= '<i class="fa fa-check-square-o"></i>&nbsp; Database <b>Departments</b> created successfuly.<br>';
		}
	}else{
		$err_msg .= '<i class="fa fa-check-square-o"></i>&nbsp; Database <b>Departments</b> exists already.<br>';
	}
	
	$db_name = $cid."_divisions";
	if(!$dbc->query("DESCRIBE `$db_name`")) {
		$sql = "CREATE TABLE IF NOT EXISTS `$db_name` (
			`id` int(11) NULL AUTO_INCREMENT,
			`code` varchar(20) COLLATE utf8_bin NULL,
			`th` varchar(100) COLLATE utf8_bin NULL,
			`en` varchar(100) COLLATE utf8_bin NULL,			
			PRIMARY KEY (`id`)
		) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_bin";
		if(!$dbc->query($sql)){
			$err_msg .= '<span style="color:#c00"><i class="fa fa-times-circle"></i>&nbsp; Create database <b>Divisions</b> failed. Error : <b>'.mysqli_error($dbc).'</b></span><br>';
			$error = true;
		}else{
			$err_msg .= '<i class="fa fa-check-square-o"></i>&nbsp; Database <b>Divisions</b> created successfuly.<br>';
		}
	}else{
		$err_msg .= '<i class="fa fa-check-square-o"></i>&nbsp; Database <b>Divisions</b> exists already.<br>';
	}
	
	$db_name = $cid."_documents";
	if(!$dbc->query("DESCRIBE `$db_name`")) {
		$sql = "CREATE TABLE IF NOT EXISTS `$db_name` (
		  `id` int(11) NULL AUTO_INCREMENT,
		  `filename` varchar(255) COLLATE utf8_bin NULL,
		  `name` varchar(100) COLLATE utf8_bin NULL,
		  `month` varchar(11) NULL,
		  `year` varchar(11) NULL,
		  `size` varchar(255) NULL,
		  `type` varchar(10) COLLATE utf8_bin NULL,
		  `date` varchar(20) COLLATE utf8_bin NULL,
		  `user_name` varchar(50) COLLATE utf8_bin NULL,
		  `link` varchar(255) COLLATE utf8_bin NULL,
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
	
	$db_name = $cid."_employees";
	if(!$dbc->query("DESCRIBE `$db_name`")) {
		$sql = "CREATE TABLE IF NOT EXISTS `$db_name` (
			`emp_id` varchar(20) COLLATE utf8_bin NULL,
			`sid` varchar(20) COLLATE utf8_bin NULL,
			`title` varchar(10) COLLATE utf8_bin NULL,
			`firstname` varchar(50) COLLATE utf8_bin  NULL,
			`lastname` varchar(50) COLLATE utf8_bin NULL,
			`th_name` varchar(50) COLLATE utf8_bin NULL,
			`en_name` varchar(50) COLLATE utf8_bin NULL,
			`birthdate` varchar(20) COLLATE utf8_bin NULL,
			`nationality` varchar(50) COLLATE utf8_bin NULL,
			`gender` varchar(20) COLLATE utf8_bin NULL,
			`maritial` varchar(20) COLLATE utf8_bin NULL,
			`religion` varchar(50) COLLATE utf8_bin NULL,
			`military_status` varchar(50) COLLATE utf8_bin NULL,
			`height` varchar(10) COLLATE utf8_bin NULL,
			`weight` varchar(10) COLLATE utf8_bin NULL,
			`bloodtype` varchar(20) COLLATE utf8_bin NULL,
			`drvlicense_nr` varchar(50) COLLATE utf8_bin NULL,
			`drvlicense_exp` varchar(20) COLLATE utf8_bin NULL,
			`idcard_nr` varchar(30) COLLATE utf8_bin NULL,
			`idcard_exp` varchar(20) COLLATE utf8_bin NULL,
			`tax_id` varchar(30) COLLATE utf8_bin NULL,
			`reg_address` varchar(50) COLLATE utf8_bin NULL,
			`cur_address` varchar(50) COLLATE utf8_bin NULL,
			`sub_district` varchar(50) COLLATE utf8_bin NULL,
			`district` varchar(50) COLLATE utf8_bin NULL,
			`province` varchar(50) COLLATE utf8_bin NULL,
			`postnr` varchar(10) COLLATE utf8_bin NULL,
			`country` varchar(50) COLLATE utf8_bin NULL,
			`personal_phone` varchar(20) COLLATE utf8_bin NULL,
			`personal_email` varchar(50) COLLATE utf8_bin NULL,
			`work_phone` varchar(20) COLLATE utf8_bin NULL,
			`work_email` varchar(50) COLLATE utf8_bin NULL,
			`emergency_contacts` text COLLATE utf8_bin NULL,
			`hospitals` text COLLATE utf8_bin NULL,
			`joining_date` varchar(20) COLLATE utf8_bin NULL,
			`probation_date` varchar(20) COLLATE utf8_bin NULL,
			`entity` varchar(5) COLLATE utf8_bin NULL,
			`branch` varchar(5) COLLATE utf8_bin  NULL,
			`division` varchar(5) COLLATE utf8_bin  NULL,
			`department` varchar(5) COLLATE utf8_bin  NULL,
			`team` varchar(5) COLLATE utf8_bin NULL,
			`emp_group` varchar(5) COLLATE utf8_bin NULL,
			`emp_type` varchar(1) COLLATE utf8_bin NULL,
			`resign_date` varchar(20) COLLATE utf8_bin NULL,
			`resign_reason` text COLLATE utf8_bin NULL,
			`emp_status` varchar(1) COLLATE utf8_bin NULL,
			`account_code` varchar(11) NULL,
			`position` varchar(11) NULL,
			`head_branch` varchar(100) COLLATE utf8_bin NULL,
			`head_division` varchar(20) COLLATE utf8_bin NULL,
			`head_department` varchar(100) COLLATE utf8_bin NULL,
			`line_manager` varchar(100) COLLATE utf8_bin NULL,
			`team_supervisor` varchar(100) COLLATE utf8_bin NULL,
			`date_position` varchar(20) COLLATE utf8_bin NULL,
			`shift_team` varchar(100) COLLATE utf8_bin NULL,
			`time_reg` varchar(1) COLLATE utf8_bin NULL,
			`selfie` varchar(1) COLLATE utf8_bin NULL,
			`annual_leave` varchar(100) NULL,
			`leave_approve` varchar(100) COLLATE utf8_bin NULL,
			`notice_date` varchar(20) COLLATE utf8_bin NULL,
			`remaining_salary` varchar(55) NULL,
			`notice_payment` varchar(55) NULL,
			`paid_leave` varchar(55) NULL,
			`severance` varchar(55) NULL,
			`other_income` varchar(255) NULL,
			`remarks` text COLLATE utf8_bin NULL,
			`attachments` text COLLATE utf8_bin NULL,
			`shiftplan` varchar(50) COLLATE utf8_bin NULL,
			`pay_type` varchar(10) COLLATE utf8_bin NULL,
			`bank_name` varchar(50) COLLATE utf8_bin NULL,
			`bank_code` varchar(20) COLLATE utf8_bin NULL,
			`bank_account` varchar(50) COLLATE utf8_bin NULL,
			`bank_branch` varchar(10) COLLATE utf8_bin NULL,
			`bank_account_name` varchar(100) COLLATE utf8_bin NULL,
			`bank_transfer` varchar(11) NULL,
			`base_salary` varchar(255) NULL,
			`day_rate` varchar(55) NULL,
			`hour_rate` varchar(55) NULL,
			`contract_type` varchar(10) COLLATE utf8_bin NULL,
			`calc_base` varchar(10) COLLATE utf8_bin NULL,
			`gov_house_banking` varchar(255) NULL,
			`savings` varchar(55) NULL,
			`legal_execution` varchar(55) NULL,
			`kor_yor_sor` varchar(55) NULL,
			`fix_allow_1` varchar(55) NULL,
			`fix_allow_2` varchar(55) NULL,
			`fix_allow_3` varchar(55) NULL,
			`fix_allow_4` varchar(55) NULL,
			`fix_allow_5` varchar(55) NULL,
			`fix_allow_6` varchar(55) NULL,
			`fix_allow_7` varchar(55) NULL,
			`fix_allow_8` varchar(55) NULL,
			`fix_allow_9` varchar(55) NULL,
			`fix_allow_10` varchar(55) NULL,
			`fix_deduct_1` varchar(55) NULL,
			`fix_deduct_2` varchar(55) NULL,
			`fix_deduct_3` varchar(55) NULL,
			`fix_deduct_4` varchar(55) NULL,
			`fix_deduct_5` varchar(55) NULL,
			`tax_standard_deduction` varchar(55) NULL,
			`tax_personal_allowance` varchar(55) NULL,
			`tax_spouse` varchar(5) COLLATE utf8_bin NULL,
			`tax_allow_spouse` varchar(55) NULL,
			`tax_parents` varchar(55) NULL,
			`tax_allow_parents` varchar(55) NULL,
			`tax_parents_inlaw` varchar(55) NULL,
			`tax_allow_parents_inlaw` varchar(55) NULL,
			`tax_child_bio_2018` varchar(55) NULL,
			`tax_allow_child_bio_2018` varchar(55) NULL,
			`tax_child_adopted` varchar(55) NULL,
			`tax_allow_child_adopted` varchar(55) NULL,
			`tax_child_bio` varchar(55) NULL,
			`tax_allow_child_bio` varchar(55) NULL,
			`tax_allow_child_birth` varchar(55) NULL,
			`tax_disabled_person` varchar(55) NULL,
			`tax_allow_disabled_person` varchar(55) NULL,
			`tax_allow_home_loan_interest` varchar(55) NULL,
			`tax_allow_first_home` varchar(55) NULL,
			`tax_allow_donation_charity` varchar(55) NULL,
			`tax_allow_donation_education` varchar(55) NULL,
			`tax_allow_donation_flood` varchar(55) NULL,
			`tax_allow_own_health` varchar(55) NULL,
			`tax_health_parents` varchar(55) NULL,
			`tax_allow_health_parents` varchar(55) NULL,
			`tax_allow_own_life_insurance` varchar(55) NULL,
			`tax_allow_life_insurance_spouse` varchar(55) NULL,
			`tax_allow_pension_fund` varchar(55) NULL,
			`tax_allow_rmf` varchar(55) NULL,
			`tax_allow_ltf` varchar(55) NULL,
			`tax_exemp_disabled_under` varchar(5) COLLATE utf8_bin NULL,
			`tax_allow_exemp_disabled_under` varchar(55) NULL,
			`tax_exemp_payer_older` varchar(5) COLLATE utf8_bin NULL,
			`tax_allow_exemp_payer_older` varchar(55) NULL,
			`tax_allow_domestic_tour` varchar(55) NULL,
			`tax_allow_year_end_shopping` varchar(255) NULL,
			`tax_allow_other` varchar(55) NULL,
			`emp_tax_deductions` varchar(55) NULL,
			`total_tax_deductions` varchar(55) NULL,
			`tax_allow_nsf` varchar(55) NULL,
			`tax_allow_pvf` varchar(55) NULL,
			`tax_allow_sso` varchar(55) NULL,
			`former_salary_rate` varchar(55) NULL,
			`income_current_year` varchar(55) NULL,
			`tax_paid_current_year` varchar(55) NULL,
			`pvf_nr` varchar(50) COLLATE utf8_bin NULL,
			`pvf_reg_date` varchar(20) COLLATE utf8_bin NULL,
			`calc_pvf` varchar(1) COLLATE utf8_bin NULL,
			`pvf_rate_emp` varchar(55) NULL,
			`pvf_rate_com` varchar(55) NULL,
			`pvf_prev_years_emp` varchar(55) NULL,
			`pvf_prev_years_com` varchar(55) NULL,
			`calc_psf` varchar(1) COLLATE utf8_bin NULL,
			`psf_rate_emp` varchar(55) NULL,
			`psf_rate_com` varchar(55) NULL,
			`psf_prev_years_emp` varchar(55) NULL,
			`psf_prev_years_com` varchar(55) NULL,
			`calc_sso` varchar(1) COLLATE utf8_bin NULL,
			`sso_by` varchar(1) COLLATE utf8_bin NULL,
			`calc_tax` varchar(1) COLLATE utf8_bin NULL,
			`calc_method` varchar(10) COLLATE utf8_bin NULL,
			`modify_tax` varchar(55) NULL,
			`image` varchar(50) COLLATE utf8_bin NULL,
			`att_idcard` varchar(100) COLLATE utf8_bin NULL,
			`att_housebook` varchar(100) COLLATE utf8_bin NULL,
			`attach1` varchar(100) COLLATE utf8_bin NULL,
			`attach2` varchar(100) COLLATE utf8_bin NULL,
			`attach3` varchar(100) COLLATE utf8_bin NULL,
			`attach4` varchar(100) COLLATE utf8_bin NULL,
			`att_bankbook` varchar(100) COLLATE utf8_bin NULL,
			`att_contract` varchar(100) COLLATE utf8_bin NULL,
			`attach5` varchar(100) COLLATE utf8_bin NULL,
			`attach6` varchar(100) COLLATE utf8_bin NULL,
			`attach7` varchar(100) COLLATE utf8_bin NULL,
			`attach8` varchar(100) COLLATE utf8_bin NULL,
			`pr_calculation` varchar(5) COLLATE utf8_bin NULL,
			`allow_login` varchar(10) COLLATE utf8_bin NULL,
			`log_status` varchar(10) COLLATE utf8_bin NULL,
			`pr_status` varchar(11) NULL,
			`print_payslip` varchar(5) COLLATE utf8_bin NULL,
			`issued` varchar(50) COLLATE utf8_bin NULL,
			`med_contact` varchar(50) COLLATE utf8_bin NULL,
			`med_phone` varchar(50) COLLATE utf8_bin NULL,
			`med_smoker` varchar(5) COLLATE utf8_bin NULL,
			`med_alert` tinytext COLLATE utf8_bin NULL,
			`med_allergies` tinytext COLLATE utf8_bin NULL,
			`med_disabilities` tinytext COLLATE utf8_bin NULL,
			`med_medication` tinytext COLLATE utf8_bin NULL,
			`med_attachments` text COLLATE utf8_bin NULL,
			`sso_hospital` varchar(255) COLLATE utf8_bin NULL,
			`pnd` varchar(10) COLLATE utf8_bin NULL,
			`base_ot_rate` varchar(10) COLLATE utf8_bin NULL,
			`ot_rate` varchar(255) NULL,		
			`teams` varchar(255) COLLATE utf8_bin NULL,
			`team_name` varchar(255) COLLATE utf8_bin NULL,  
			`latitude` varchar(255) COLLATE utf8_bin  NULL,  
			`longitude` varchar(255) COLLATE utf8_bin  NULL,  
			`workFromHome` varchar(255) COLLATE utf8_bin NULL,  
			`ping_expire` varchar(255) COLLATE utf8_bin NULL,  
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
	
	$db_name = $cid."_employee_assets";
	if(!$dbc->query("DESCRIBE `$db_name`")) {
		$sql = "CREATE TABLE IF NOT EXISTS `$db_name` (
			`id` int(11) NULL AUTO_INCREMENT,
			`emp_id` varchar(20) COLLATE utf8_bin NULL,
			`asset` varchar(255) COLLATE utf8_bin NULL,
			`description` varchar(255) COLLATE utf8_bin NULL,
			`reference` varchar(255) COLLATE utf8_bin NULL,
			`assign_date` varchar(20) COLLATE utf8_bin NULL,
			`return_date` varchar(20) COLLATE utf8_bin NULL,
			`value` varchar(255) COLLATE utf8_bin NULL,
			`cost` varchar(255) NULL,
			`paidby` varchar(11) NULL,
			`remarks` text COLLATE utf8_bin NULL,
			`attachments` text COLLATE utf8_bin NULL,			
			PRIMARY KEY (`id`)
		) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_bin";
		if(!$dbc->query($sql)){
			$err_msg .= '<span style="color:#c00"><i class="fa fa-times-circle"></i>&nbsp; Create database <b>Employee assets</b> failed. Error : <b>'.mysqli_error($dbc).'</b></span><br>';
			$error = true;
		}else{
			$err_msg .= '<i class="fa fa-check-square-o"></i>&nbsp; Database <b>Employee assets</b> created successfuly.<br>';
		}
	}else{
		$err_msg .= '<i class="fa fa-check-square-o"></i>&nbsp; Database <b>Employee assets</b> exists already.<br>';
	}
	
	$db_name = $cid."_employee_career";
	if(!$dbc->query("DESCRIBE `$db_name`")) {
		$sql = "CREATE TABLE IF NOT EXISTS `$db_name` (
			`id` int(11) NULL AUTO_INCREMENT,
			`emp_id` varchar(20) COLLATE utf8_bin NULL,
			`position` varchar(100) COLLATE utf8_bin NULL,
			`department` varchar(100) COLLATE utf8_bin NULL,
			`classification` text COLLATE utf8_bin NULL,
			`start_date` varchar(20) COLLATE utf8_bin NULL,
			`end_date` varchar(20) COLLATE utf8_bin NULL,
			`salary` varchar(255) NULL,
			`benefits` text COLLATE utf8_bin NULL,
			`remarks` text COLLATE utf8_bin NULL,
			`attachments` text COLLATE utf8_bin NULL,
			PRIMARY KEY (`id`)
		) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_bin";
		if(!$dbc->query($sql)){
			$err_msg .= '<span style="color:#c00"><i class="fa fa-times-circle"></i>&nbsp; Create database <b>Employee career</b> failed. Error : <b>'.mysqli_error($dbc).'</b></span><br>';
			$error = true;
		}else{
			$err_msg .= '<i class="fa fa-check-square-o"></i>&nbsp; Database <b>Employee career</b> created successfuly.<br>';
		}
	}else{
		$err_msg .= '<i class="fa fa-check-square-o"></i>&nbsp; Database <b>Employee career</b> exists already.<br>';
	}
	
	$db_name = $cid."_employee_discipline";
	if(!$dbc->query("DESCRIBE `$db_name`")) {
		$sql = "CREATE TABLE IF NOT EXISTS `$db_name` (
			`id` int(11) NULL AUTO_INCREMENT,
			`emp_id` varchar(20) COLLATE utf8_bin NULL,
			`date` varchar(20) COLLATE utf8_bin NULL,
			`status` varchar(11) NULL,
			`warning` varchar(11) NULL,
			`violation` varchar(11) NULL,
			`infraction` text COLLATE utf8_bin NULL,
			`damage` varchar(255) NULL,
			`improvement` text COLLATE utf8_bin NULL,
			`consequences` text COLLATE utf8_bin NULL,
			`employee` text COLLATE utf8_bin NULL,
			`employer` text COLLATE utf8_bin NULL,
			`witness` text COLLATE utf8_bin NULL,
			`attachments` text COLLATE utf8_bin NULL,
			PRIMARY KEY (`id`)
		) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_bin";
		if(!$dbc->query($sql)){
			$err_msg .= '<span style="color:#c00"><i class="fa fa-times-circle"></i>&nbsp; Create database <b>Employee discipline</b> failed. Error : <b>'.mysqli_error($dbc).'</b></span><br>';
			$error = true;
		}else{
			$err_msg .= '<i class="fa fa-check-square-o"></i>&nbsp; Database <b>Employee discipline</b> created successfuly.<br>';
		}
	}else{
		$err_msg .= '<i class="fa fa-check-square-o"></i>&nbsp; Database <b>Employee discipline</b> exists already.<br>';
	}
	
	$db_name = $cid."_employee_equipment";
	if(!$dbc->query("DESCRIBE `$db_name`")) {
		$sql = "CREATE TABLE IF NOT EXISTS `$db_name` (
			`id` int(11) NULL AUTO_INCREMENT,
			`emp_id` varchar(20) COLLATE utf8_bin NULL,
			`asset` varchar(255) COLLATE utf8_bin NULL,
			`description` varchar(255) COLLATE utf8_bin NULL,
			`reference` varchar(255) COLLATE utf8_bin NULL,
			`assign_date` varchar(20) COLLATE utf8_bin NULL,
			`return_date` varchar(20) COLLATE utf8_bin NULL,
			`cost` varchar(255) NULL,
			`paidby` varchar(11) NULL,
			`remarks` text COLLATE utf8_bin NULL,
			`attachments` text COLLATE utf8_bin NULL,		
			PRIMARY KEY (`id`)
		) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_bin";
		if(!$dbc->query($sql)){
			$err_msg .= '<span style="color:#c00"><i class="fa fa-times-circle"></i>&nbsp; Create database <b>Employee equipment</b> failed. Error : <b>'.mysqli_error($dbc).'</b></span><br>';
			$error = true;
		}else{
			$err_msg .= '<i class="fa fa-check-square-o"></i>&nbsp; Database <b>Employee equipment</b> created successfuly.<br>';
		}
	}else{
		$err_msg .= '<i class="fa fa-check-square-o"></i>&nbsp; Database <b>Employee equipment</b> exists already.<br>';
	}
	
	$db_name = $cid."_employee_events";
	if(!$dbc->query("DESCRIBE `$db_name`")) {
		$sql = "CREATE TABLE IF NOT EXISTS `$db_name` (
			`id` int(11) NULL AUTO_INCREMENT,
			`emp_id` varchar(20) COLLATE utf8_bin NULL,
			`date` varchar(20) COLLATE utf8_bin NULL,
			`completed` varchar(20) COLLATE utf8_bin NULL,
			`event` varchar(255) COLLATE utf8_bin NULL,
			`hours` varchar(100) COLLATE utf8_bin NULL,
			`certification` varchar(255) COLLATE utf8_bin NULL,
			`cost` varchar(100) COLLATE utf8_bin NULL,
			`remarks` text COLLATE utf8_bin NULL,
			`attachments` text COLLATE utf8_bin NULL,		
			PRIMARY KEY (`id`)
		) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_bin";
		if(!$dbc->query($sql)){
			$err_msg .= '<span style="color:#c00"><i class="fa fa-times-circle"></i>&nbsp; Create database <b>Employee events</b> failed. Error : <b>'.mysqli_error($dbc).'</b></span><br>';
			$error = true;
		}else{
			$err_msg .= '<i class="fa fa-check-square-o"></i>&nbsp; Database <b>Employee events</b> created successfuly.<br>';
		}
	}else{
		$err_msg .= '<i class="fa fa-check-square-o"></i>&nbsp; Database <b>Employee events</b> exists already.<br>';
	}
	
	$db_name = $cid."_employee_log";
	if(!$dbc->query("DESCRIBE `$db_name`")) {
		$sql = "CREATE TABLE IF NOT EXISTS `$db_name` (
		  `id` int(11) NULL AUTO_INCREMENT,
			`emp_id` varchar(20) COLLATE utf8_bin NULL,
			`field` varchar(50) COLLATE utf8_bin DEFAULT NULL,
			`prev` varchar(50) COLLATE utf8_bin NULL,
			`new` varchar(50) COLLATE utf8_bin NULL,
			`user` varchar(50) COLLATE utf8_bin NULL,
			`date` timestamp NULL DEFAULT current_timestamp(),
		PRIMARY KEY (`id`)
		) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_bin";
		if(!$dbc->query($sql)){
			$err_msg .= '<span style="color:#c00"><i class="fa fa-times-circle"></i>&nbsp; Create database <b>Employee log</b> failed. Error : <b>'.mysqli_error($dbc).'</b></span><br>';
			$error = true;
		}else{
			$err_msg .= '<i class="fa fa-check-square-o"></i>&nbsp; Database <b>Employee log</b> created successfuly.<br>';
		}
	}else{
		$err_msg .= '<i class="fa fa-check-square-o"></i>&nbsp; Database <b>Employee log</b> exists already.<br>';
	}
	
	$db_name = $cid."_employee_medical";
	if(!$dbc->query("DESCRIBE `$db_name`")) {
		$sql = "CREATE TABLE IF NOT EXISTS `$db_name` (
		  `id` int(11) NULL AUTO_INCREMENT,
			`emp_id` varchar(20) COLLATE utf8_bin NULL,
			`date` date NULL,
			`date_from` varchar(20) COLLATE utf8_bin NULL,
			`date_until` varchar(20) COLLATE utf8_bin NULL,
			`emp_condition` varchar(255) COLLATE utf8_bin NULL,
			`certificate` varchar(100) COLLATE utf8_bin NULL,
			`doctor` varchar(100) COLLATE utf8_bin NULL,
			`remarks` text COLLATE utf8_bin NULL,
			`attachments` text COLLATE utf8_bin NULL,
		PRIMARY KEY (`id`)
		) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_bin";
		if(!$dbc->query($sql)){
			$err_msg .= '<span style="color:#c00"><i class="fa fa-times-circle"></i>&nbsp; Create database <b>Employee medical</b> failed. Error : <b>'.mysqli_error($dbc).'</b></span><br>';
			$error = true;
		}else{
			$err_msg .= '<i class="fa fa-check-square-o"></i>&nbsp; Database <b>Employee medical</b> created successfuly.<br>';
		}
	}else{
		$err_msg .= '<i class="fa fa-check-square-o"></i>&nbsp; Database <b>Employee medical</b> exists already.<br>';
	}
	
	$db_name = $cid."_employee_privileges";
	if(!$dbc->query("DESCRIBE `$db_name`")) {
		$sql = "CREATE TABLE IF NOT EXISTS `$db_name` (
		  `id` int(11) NULL AUTO_INCREMENT,
			`emp_id` varchar(20) COLLATE utf8_bin NULL,
			`date` varchar(20) COLLATE utf8_bin NULL,
			`completed` varchar(20) COLLATE utf8_bin NULL,
			`privilege` varchar(255) COLLATE utf8_bin NULL,
			`hours` varchar(100) COLLATE utf8_bin NULL,
			`certification` varchar(255) COLLATE utf8_bin NULL,
			`cost` varchar(100) COLLATE utf8_bin NULL,
			`remarks` text COLLATE utf8_bin NULL,
			`attachments` text COLLATE utf8_bin NULL,
		PRIMARY KEY (`id`)
		) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_bin";
		if(!$dbc->query($sql)){
			$err_msg .= '<span style="color:#c00"><i class="fa fa-times-circle"></i>&nbsp; Create database <b>Employee privileges</b> failed. Error : <b>'.mysqli_error($dbc).'</b></span><br>';
			$error = true;
		}else{
			$err_msg .= '<i class="fa fa-check-square-o"></i>&nbsp; Database <b>Employee privileges</b> created successfuly.<br>';
		}
	}else{
		$err_msg .= '<i class="fa fa-check-square-o"></i>&nbsp; Database <b>Employee privileges</b> exists already.<br>';
	}
	
	$db_name = $cid."_employee_training";
	if(!$dbc->query("DESCRIBE `$db_name`")) {
		$sql = "CREATE TABLE IF NOT EXISTS `$db_name` (
		  `id` int(11) NULL AUTO_INCREMENT,
			`emp_id` varchar(20) COLLATE utf8_bin NULL,
			`date` varchar(20) COLLATE utf8_bin NULL,
			`completed` varchar(20) COLLATE utf8_bin NULL,
			`training` varchar(255) COLLATE utf8_bin NULL,
			`hours` varchar(100) COLLATE utf8_bin NULL,
			`certification` varchar(255) COLLATE utf8_bin NULL,
			`cost` varchar(100) COLLATE utf8_bin NULL,
			`remarks` text COLLATE utf8_bin NULL,
			`attachments` text COLLATE utf8_bin NULL,
		PRIMARY KEY (`id`)
		) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_bin";
		if(!$dbc->query($sql)){
			$err_msg .= '<span style="color:#c00"><i class="fa fa-times-circle"></i>&nbsp; Create database <b>Employee training</b> failed. Error : <b>'.mysqli_error($dbc).'</b></span><br>';
			$error = true;
		}else{
			$err_msg .= '<i class="fa fa-check-square-o"></i>&nbsp; Database <b>Employee training</b> created successfuly.<br>';
		}
	}else{
		$err_msg .= '<i class="fa fa-check-square-o"></i>&nbsp; Database <b>Employee training</b> exists already.<br>';
	}
	
	$db_name = $cid."_entities";
	if(!$dbc->query("DESCRIBE `$db_name`")) {
		$sql = "CREATE TABLE IF NOT EXISTS `$db_name` (
		 `id` int(11) NULL AUTO_INCREMENT,
			`code` varchar(20) COLLATE utf8_bin NULL,
			`th` varchar(100) COLLATE utf8_bin NULL,
			`en` varchar(100) COLLATE utf8_bin NULL,
		PRIMARY KEY (`id`)
		) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_bin";
		if(!$dbc->query($sql)){
			$err_msg .= '<span style="color:#c00"><i class="fa fa-times-circle"></i>&nbsp; Create database <b>Entities</b> failed. Error : <b>'.mysqli_error($dbc).'</b></span><br>';
			$error = true;
		}else{
			$err_msg .= '<i class="fa fa-check-square-o"></i>&nbsp; Database <b>Entities</b> created successfuly.<br>';
		}
	}else{
		$err_msg .= '<i class="fa fa-check-square-o"></i>&nbsp; Database <b>Entities</b> exists already.<br>';
	}
	
	$db_name = $cid."_entities_data";
	if(!$dbc->query("DESCRIBE `$db_name`")) {
		$sql = "CREATE TABLE IF NOT EXISTS `$db_name` (
		  `ref` int(11) NULL AUTO_INCREMENT,
			`code` varchar(10) COLLATE utf8_bin NULL,
			`en_compname` varchar(50) COLLATE utf8_bin NULL,
			`th_compname` varchar(50) COLLATE utf8_bin NULL,
			`comp_phone` varchar(20) COLLATE utf8_bin  NULL,
			`comp_fax` varchar(20) COLLATE utf8_bin  NULL,
			`comp_email` varchar(50) COLLATE utf8_bin NULL,
			`tax_id` varchar(20) COLLATE utf8_bin NULL,
			`revenu_branch` varchar(10) COLLATE utf8_bin NULL,
			`sso_account` varchar(50) COLLATE utf8_bin  NULL,
			`sso_codes` text COLLATE utf8_bin NULL,
			`banks` text COLLATE utf8_bin  NULL,
			`logofile` varchar(50) COLLATE utf8_bin  NULL,
			`dig_stamp` varchar(100) COLLATE utf8_bin  NULL,
			`digi_stamp` varchar(11)  NULL,
			`dig_signature` varchar(100) COLLATE utf8_bin  NULL,
			`digi_signature` varchar(11)  NULL,
			`th_address` text COLLATE utf8_bin  NULL,
			`en_address` text COLLATE utf8_bin  NULL,
			`th_addr_detail` text COLLATE utf8_bin  NULL,
			`en_addr_detail` text COLLATE utf8_bin  NULL,
			`bus_reg` varchar(100) COLLATE utf8_bin  NULL,
			`comp_affi` varchar(100) COLLATE utf8_bin  NULL,
			`house_reg` varchar(100) COLLATE utf8_bin  NULL,
			`vat_reg` varchar(100) COLLATE utf8_bin  NULL,
			`socsec_fund` varchar(100) COLLATE utf8_bin  NULL,
			`bankbook` varchar(100) COLLATE utf8_bin  NULL,
			`passfs` varchar(100) COLLATE utf8_bin  NULL,
			`paw_tax` varchar(50) COLLATE utf8_bin  NULL,
			`attach1` varchar(50) COLLATE utf8_bin  NULL,
			`attach2` varchar(50) COLLATE utf8_bin  NULL,
			`attach3` varchar(50) COLLATE utf8_bin  NULL,
			PRIMARY KEY (`ref`)
		) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_bin";
		if(!$dbc->query($sql)){
			$err_msg .= '<span style="color:#c00"><i class="fa fa-times-circle"></i>&nbsp; Create database <b>Entities data</b> failed. Error : <b>'.mysqli_error($dbc).'</b></span><br>';
			$error = true;
		}else{
			$err_msg .= '<i class="fa fa-check-square-o"></i>&nbsp; Database <b>Entities data</b> created successfuly.<br>';
		}
	}else{
		$err_msg .= '<i class="fa fa-check-square-o"></i>&nbsp; Database <b>Entities data</b> exists already.<br>';
	}
	
	$db_name = $cid."_historic_data";
	if(!$dbc->query("DESCRIBE `$db_name`")) {
		$sql = "CREATE TABLE IF NOT EXISTS `$db_name` (
		  `id` varchar(50) COLLATE utf8_bin NULL,
			`emp_id` varchar(50) COLLATE utf8_bin NULL,
			`month` varchar(11) NULL,
			`emp_name_en` varchar(50) COLLATE utf8_bin NULL,
			`emp_name_th` varchar(50) COLLATE utf8_bin NULL,
			`entity` varchar(5) COLLATE utf8_bin NULL,
			`branch` varchar(5) COLLATE utf8_bin  NULL,
			`division` varchar(5) COLLATE utf8_bin NULL,
			`department` varchar(5) COLLATE utf8_bin NULL,
			`team` varchar(5) COLLATE utf8_bin NULL,
			`emp_group` varchar(5) COLLATE utf8_bin NULL,
			`position` varchar(5) COLLATE utf8_bin NULL,
			`salary` varchar(255) NULL,
			`ot1b` varchar(255) NULL,
			`ot15b` varchar(255) NULL,
			`ot2b` varchar(255) NULL,
			`ot3b` varchar(255) NULL,
			`ootb` varchar(255) NULL,
			`total_otb` varchar(255) NULL,
			`fix_allow_1` varchar(255) NULL,
			`fix_allow_2` varchar(255) NULL,
			`fix_allow_3` varchar(255) NULL,
			`fix_allow_4` varchar(255) NULL,
			`fix_allow_5` varchar(255) NULL,
			`fix_allow_6` varchar(255) NULL,
			`fix_allow_7` varchar(255) NULL,
			`fix_allow_8` varchar(255) NULL,
			`fix_allow_9` varchar(255) NULL,
			`fix_allow_10` varchar(255) NULL,
			`var_allow_1` varchar(255) NULL,
			`var_allow_2` varchar(255) NULL,
			`var_allow_3` varchar(255) NULL,
			`var_allow_4` varchar(255) NULL,
			`var_allow_5` varchar(255) NULL,
			`var_allow_6` varchar(255) NULL,
			`var_allow_7` varchar(255) NULL,
			`var_allow_8` varchar(255) NULL,
			`var_allow_9` varchar(255) NULL,
			`var_allow_10` varchar(255) NULL,
			`total_fix_allow` varchar(255) NULL,
			`total_var_allow` varchar(255) NULL,
			`total_tax_allow` varchar(255) NULL,
			`tax_by_company` varchar(255) NULL,
			`sso_by_company` varchar(255) NULL,
			`other_income` varchar(255) NULL,
			`social` varchar(255) NULL,
			`social_com` varchar(255) NULL,
			`pvf_employee` varchar(255) NULL,
			`pvf_employer` varchar(255) NULL,
			`psf_employee` varchar(255) NULL,
			`tot_deduct_before` varchar(255) NULL,
			`tot_deduct_after` varchar(255) NULL,
			`tot_deductions` varchar(255) NULL,
			`tax` varchar(255) NULL,
			`tot_fix_income` varchar(255) NULL,
			`tot_var_income` varchar(255) NULL,
			`gross_income` varchar(255) NULL,
			`net_income` varchar(255) NULL,
			`paid` varchar(5) COLLATE utf8_bin NULL DEFAULT 'H',
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
	
	$db_name = $cid."_leaves";
	if(!$dbc->query("DESCRIBE `$db_name`")) {
		$sql = "CREATE TABLE IF NOT EXISTS `$db_name` (
			`id` int(11) NULL AUTO_INCREMENT,
			`emp_id` varchar(20) COLLATE utf8_bin NULL,
			`name` varchar(50) COLLATE utf8_bin NULL,
			`phone` varchar(20) COLLATE utf8_bin NULL,
			`entity` varchar(11) NULL,
			`branch` varchar(11)  NULL,
			`division` varchar(11) NULL,
			`department` varchar(11) NULL,
			`team` varchar(11) NULL,
			`emp_group` varchar(5) COLLATE utf8_bin NULL,
			`leave_type` varchar(50) COLLATE utf8_bin NULL,
			`planned` varchar(11) NULL,
			`paid` varchar(11) NULL,
			`start` date NULL,
			`end` date NULL,
			`days` varchar(255) NULL,
			`details` text COLLATE utf8_bin NULL,
			`status` varchar(50) COLLATE utf8_bin NULL,
			`comment` text COLLATE utf8_bin NULL,
			`reason` varchar(255) COLLATE utf8_bin NULL,
			`certificate` varchar(10) COLLATE utf8_bin NULL,
			`attach` varchar(100) COLLATE utf8_bin NULL,
			`created` varchar(50) COLLATE utf8_bin NULL,
			`created_by` varchar(50) COLLATE utf8_bin NULL,
			`updated` varchar(50) COLLATE utf8_bin NULL,
			`updated_by` varchar(50) COLLATE utf8_bin NULL,
			`approved` varchar(50) COLLATE utf8_bin NULL,
			`approved_by` varchar(50) COLLATE utf8_bin NULL,
			`rejected` varchar(50) COLLATE utf8_bin NULL,
			`rejected_by` varchar(50) COLLATE utf8_bin NULL,
			`canceled` varchar(50) COLLATE utf8_bin NULL,
			`canceled_by` varchar(50) COLLATE utf8_bin NULL,
			`log` varchar(11) NULL DEFAULT 1,
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
	
	$db_name = $cid."_leaves_data";
	if(!$dbc->query("DESCRIBE `$db_name`")) {
		$sql = "CREATE TABLE IF NOT EXISTS `$db_name` (
		  `id` int(11) NULL AUTO_INCREMENT,
			`leave_id` varchar(20) COLLATE utf8_bin NULL,
			`emp_id` varchar(20) COLLATE utf8_bin NULL,
			`name` varchar(50) COLLATE utf8_bin NULL,
			`phone` varchar(20) COLLATE utf8_bin NULL,
			`entity` varchar(11) NULL,
			`branch` varchar(11)  NULL,
			`division` varchar(11) NULL,
			`department` varchar(11) NULL,
			`team` varchar(11) NULL,
			`emp_group` varchar(10) COLLATE utf8_bin NULL,
			`leave_type` varchar(10) COLLATE utf8_bin NULL,
			`days` varchar(255) NULL,
			`day` varchar(20) COLLATE utf8_bin NULL,
			`half` varchar(11) NULL,
			`date` date NULL,
			`hours` varchar(255) NULL,
			`reason` varchar(255) COLLATE utf8_bin NULL,
			`planned` varchar(11) NULL,
			`paid` varchar(11) NULL,
			`status` varchar(5) COLLATE utf8_bin NULL,
			`certificate` varchar(11) NULL DEFAULT 1,
			`lock` varchar(11) NULL,
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


	$db_name = $cid."_employee_per_year_records";
	if(!$dbc->query("DESCRIBE `$db_name`")) {
		$sql = "CREATE TABLE IF NOT EXISTS `$db_name` (
		  `id` int(11) NOT NULL  AUTO_INCREMENT,
			  `emp_id` varchar(255) DEFAULT NULL,
			  `annual_leave` varchar(255) DEFAULT NULL,
			  `year` varchar(255) DEFAULT NULL,
			  `other_fields` text,
			  PRIMARY KEY (`id`)
		) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_bin";
		if(!$dbc->query($sql)){
		}else{
		}
	}else{
	}	

	$db_name = $cid."_leave_periods";
	if(!$dbc->query("DESCRIBE `$db_name`")) {
		$sql = "CREATE TABLE IF NOT EXISTS `$db_name` (
		   `id` int(11) NOT NULL AUTO_INCREMENT,
		  `leave_period_year` varchar(255) DEFAULT NULL,
		  `leave_period_start` varchar(255) DEFAULT NULL,
		  `leave_period_end` varchar(255) DEFAULT NULL,
		  PRIMARY KEY (`id`)
		) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_bin";
		if(!$dbc->query($sql)){
		}else{
		}
	}else{
	}
	
	$db_name = $cid."_monthly_shiftplans_".$year;
	if(!$dbc->query("DESCRIBE `$db_name`")) {
		$sql = "CREATE TABLE IF NOT EXISTS `$db_name` (
		  `id` varchar(50) COLLATE utf8_bin NULL,
		  `month` varchar(5) COLLATE utf8_bin NULL,
		  `emp_id` varchar(50) COLLATE utf8_bin NULL,
		  `sid` varchar(20) COLLATE utf8_bin NULL,
		  `en_name` varchar(50) COLLATE utf8_bin NULL,
		  `th_name` varchar(50) COLLATE utf8_bin NULL,
		  `shiftteam` varchar(50) COLLATE utf8_bin NULL,
		  `shiftteam_name` varchar(255) COLLATE utf8_bin NULL,
		  `wkd` varchar(255) COLLATE utf8_bin NULL,
		  `pub` varchar(255) COLLATE utf8_bin NULL,
		  `off` varchar(255) COLLATE utf8_bin NULL,
		  `vod` varchar(255) COLLATE utf8_bin NULL,
		  `off_day_used` varchar(255) COLLATE utf8_bin NULL,
		  `bal_off` text COLLATE utf8_bin NULL,
		  `D1` varchar(20) COLLATE utf8_bin NULL,
		  `D2` varchar(20) COLLATE utf8_bin NULL,
		  `D3` varchar(20) COLLATE utf8_bin NULL,
		  `D4` varchar(20) COLLATE utf8_bin NULL,
		  `D5` varchar(20) COLLATE utf8_bin NULL,
		  `D6` varchar(20) COLLATE utf8_bin NULL,
		  `D7` varchar(20) COLLATE utf8_bin NULL,
		  `D8` varchar(20) COLLATE utf8_bin NULL,
		  `D9` varchar(20) COLLATE utf8_bin NULL,
		  `D10` varchar(20) COLLATE utf8_bin NULL,
		  `D11` varchar(20) COLLATE utf8_bin NULL,
		  `D12` varchar(20) COLLATE utf8_bin NULL,
		  `D13` varchar(20) COLLATE utf8_bin NULL,
		  `D14` varchar(20) COLLATE utf8_bin NULL,
		  `D15` varchar(20) COLLATE utf8_bin NULL,
		  `D16` varchar(20) COLLATE utf8_bin NULL,
		  `D17` varchar(20) COLLATE utf8_bin NULL,
		  `D18` varchar(20) COLLATE utf8_bin NULL,
		  `D19` varchar(20) COLLATE utf8_bin NULL,
		  `D20` varchar(20) COLLATE utf8_bin NULL,
		  `D21` varchar(20) COLLATE utf8_bin NULL,
		  `D22` varchar(20) COLLATE utf8_bin NULL,
		  `D23` varchar(20) COLLATE utf8_bin NULL,
		  `D24` varchar(20) COLLATE utf8_bin NULL,
		  `D25` varchar(20) COLLATE utf8_bin NULL,
		  `D26` varchar(20) COLLATE utf8_bin NULL,
		  `D27` varchar(20) COLLATE utf8_bin NULL,
		  `D28` varchar(20) COLLATE utf8_bin NULL,
		  `D29` varchar(20) COLLATE utf8_bin NULL,
		  `D30` varchar(20) COLLATE utf8_bin NULL,
		  `D31` varchar(20) COLLATE utf8_bin NULL, 
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
	
	$db_name = $cid."_payroll_".$year;
	if(!$dbc->query("DESCRIBE `$db_name`")) { 
		$sql = "CREATE TABLE IF NOT EXISTS `$db_name` (
		  `id` varchar(50) COLLATE utf8_bin NULL,
			`period` varchar(10) COLLATE utf8_bin NULL,
			`emp_id` varchar(20) COLLATE utf8_bin NULL,
			`month` varchar(11) NULL,
			`bank` varchar(50) COLLATE utf8_bin NULL,
			`account` varchar(20) COLLATE utf8_bin NULL,
			`emp_name_en` varchar(50) COLLATE utf8_bin NULL,
			`emp_name_th` varchar(50) COLLATE utf8_bin NULL,
			`entity` varchar(5) COLLATE utf8_bin NULL,
			`branch` varchar(5) COLLATE utf8_bin  NULL,
			`division` varchar(5) COLLATE utf8_bin NULL,
			`department` varchar(5) COLLATE utf8_bin NULL,
			`team` varchar(5) COLLATE utf8_bin NULL,
			`emp_group` varchar(5) COLLATE utf8_bin NULL,
			`position` varchar(50) COLLATE utf8_bin NULL,
			`basic_salary` varchar(50) NULL,
			`salary` varchar(55) NULL,
			`actual_days` varchar(55) NULL,
			`paid_days` varchar(55) NULL,
			`ot1h` varchar(15) COLLATE utf8_bin NULL,
			`ot1b` varchar(255) NULL,
			`ot15h` varchar(15) COLLATE utf8_bin NULL,
			`ot15b` varchar(55) NULL,
			`ot2h` varchar(15) COLLATE utf8_bin NULL,
			`ot2b` varchar(255) NULL,
			`ot3h` varchar(15) COLLATE utf8_bin NULL,
			`ot3b` varchar(255) NULL,
			`ooth` varchar(15) COLLATE utf8_bin NULL,
			`ootb` varchar(255) NULL,
			`total_oth` varchar(15) COLLATE utf8_bin NULL,
			`total_otb` varchar(55) NULL,
			`fix_allow_1` varchar(55) NULL,
			`fix_allow_2` varchar(55) NULL,
			`fix_allow_3` varchar(55) NULL,
			`fix_allow_4` varchar(55) NULL,
			`fix_allow_5` varchar(55) NULL,
			`fix_allow_6` varchar(55) NULL,
			`fix_allow_7` varchar(55) NULL,
			`fix_allow_8` varchar(55) NULL,
			`fix_allow_9` varchar(55) NULL,
			`fix_allow_10` varchar(55) NULL,
			`var_allow_1` varchar(55) NULL,
			`var_allow_2` varchar(55) NULL,
			`var_allow_3` varchar(55) NULL,
			`var_allow_4` varchar(55) NULL,
			`var_allow_5` varchar(55) NULL,
			`var_allow_6` varchar(55) NULL,
			`var_allow_7` varchar(55) NULL,
			`var_allow_8` varchar(55) NULL,
			`var_allow_9` varchar(55) NULL,
			`var_allow_10` varchar(55) NULL,
			`total_fix_allow` varchar(55) NULL,
			`total_var_allow` varchar(55) NULL,
			`total_fix_tax_allow` varchar(55) NULL,
			`total_fix_non_allow` varchar(55) NULL,
			`total_var_tax_allow` varchar(55) NULL,
			`total_var_non_allow` varchar(55) NULL,
			`total_tax_allow` varchar(55) NULL,
			`total_non_allow` varchar(55) NULL,
			`other_income` varchar(55) NULL,
			`severance` varchar(55) NULL,
			`notice_payment` varchar(55) NULL,
			`remaining_salary` varchar(55) NULL,
			`paid_leave` varchar(55) NULL,
			`absence` varchar(15) COLLATE utf8_bin NULL,
			`absence_b` varchar(55) NULL,
			`leave_wop` varchar(15) COLLATE utf8_bin NULL,
			`leave_wop_b` varchar(55) NULL,
			`late_early` varchar(15) COLLATE utf8_bin NULL,
			`late_early_b` varchar(55) NULL,
			`tot_absence` varchar(55) NULL,
			`leave_wp` varchar(55) NULL,
			`pvf_employee` varchar(55) NULL,
			`pvf_employer` varchar(55) NULL,
			`psf_employee` varchar(55) NULL,
			`psf_employer` varchar(55) NULL,
			`social` varchar(55) NULL,
			`social_com` varchar(55) NULL,
			`fix_deduct_1` varchar(55) NULL,
			`fix_deduct_2` varchar(55) NULL,
			`fix_deduct_3` varchar(55) NULL,
			`fix_deduct_4` varchar(55) NULL,
			`fix_deduct_5` varchar(55) NULL,
			`var_deduct_1` varchar(55) NULL,
			`var_deduct_2` varchar(55) NULL,
			`var_deduct_3` varchar(55) NULL,
			`var_deduct_4` varchar(55) NULL,
			`var_deduct_5` varchar(55) NULL,
			`fix_deduct_before` varchar(55) NULL,
			`fix_deduct_after` varchar(55) NULL,
			`var_deduct_before` varchar(55) NULL,
			`var_deduct_after` varchar(55) NULL,
			`tot_deduct_before` varchar(55) NULL,
			`tot_deduct_after` varchar(55) NULL,
			`tot_deductions` varchar(55) NULL,
			`modify_tax` varchar(55) NULL,
			`tax` varchar(55) NULL,
			`tax_month` varchar(55) NULL,
			`tax_next` varchar(55) NULL,
			`tax_year` varchar(55) NULL,
			`tot_fix_income` varchar(55) NULL,
			`tot_var_income` varchar(55) NULL,
			`ytd_income` varchar(55) NULL,
			`prev_tax_income` varchar(55) NULL,
			`gross_income` varchar(55) NULL,
			`advance` varchar(55) NULL,
			`legal_deductions` varchar(55) NULL,
			`net_income` varchar(55) NULL,
			`paid` varchar(5) COLLATE utf8_bin NULL,
			`comment` text COLLATE utf8_bin NULL,
			`tax_calculation` text COLLATE utf8_bin NULL,
			`calc_tax` varchar(5) COLLATE utf8_bin NULL,
			`calc_sso` varchar(5) COLLATE utf8_bin NULL,
			`sso_by` varchar(11) NULL,
			`calc_pvf` varchar(5) COLLATE utf8_bin NULL,
			`calc_method` varchar(5) COLLATE utf8_bin NULL,
			`sso_rate_emp` varchar(55) NULL,
			`sso_rate_com` varchar(55) NULL,
			`pvf_rate_emp` varchar(55) NULL,
			`pvf_rate_com` varchar(55) NULL,
			`psf_rate_emp` varchar(55) NULL,
			`psf_rate_com` varchar(55) NULL,
			`contract_type` varchar(10) COLLATE utf8_bin NULL,
			`calc_base` varchar(10) COLLATE utf8_bin NULL,
			`base_ot_rate` varchar(10) COLLATE utf8_bin NULL,
			`ot_rate` varchar(255) NULL,
			`tax_by_company` varchar(55) NULL,
			`sso_by_company` varchar(55) NULL,
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
	
	$db_name = $cid."_payroll_months";
	if(!$dbc->query("DESCRIBE `$db_name`")) {
		$sql = "CREATE TABLE IF NOT EXISTS `$db_name` (
			`month` varchar(10) COLLATE utf8_bin NULL,
			`time_start` varchar(20) COLLATE utf8_bin NULL,
			`time_end` varchar(20) COLLATE utf8_bin NULL,
			`leave_start` varchar(20) COLLATE utf8_bin NULL,
			`leave_end` varchar(20) COLLATE utf8_bin NULL,
			`payroll_start` varchar(20) COLLATE utf8_bin NULL,
			`payroll_end` varchar(20) COLLATE utf8_bin NULL,
			`paydate` varchar(20) COLLATE utf8_bin NULL,
			`formdate` varchar(20) COLLATE utf8_bin NULL,
			`sso_eRate` varchar(255) NULL,
			`sso_eMax` varchar(255) NULL,
			`sso_eMin` varchar(255) NULL,
			`sso_cRate` varchar(255) NULL,
			`sso_cMax` varchar(255) NULL,
			`sso_cMin` varchar(255) NULL,
			`wht` varchar(255) NULL,
			`form_name` varchar(50) COLLATE utf8_bin  NULL,
			`form_position` varchar(50) COLLATE utf8_bin  NULL,
			`sso_act_max` varchar(10) COLLATE utf8_bin NULL DEFAULT 'max',
			`locked` varchar(11)  NULL,
			`status` varchar(11)  NULL,
			`accounting` text COLLATE utf8_bin  NULL,
			`var_allowances` text COLLATE utf8_bin  NULL,
			`compensations` text COLLATE utf8_bin  NULL, 
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
	
	$db_name = $cid."_positions";
	if(!$dbc->query("DESCRIBE `$db_name`")) {
		$sql = "CREATE TABLE IF NOT EXISTS `$db_name` (
		`id` int(11) NULL AUTO_INCREMENT,
		`code` varchar(20) COLLATE utf8_bin NULL,
		`th` varchar(100) COLLATE utf8_bin NULL,
		`en` varchar(100) COLLATE utf8_bin NULL, 
		PRIMARY KEY (`id`)
		) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_bin";
		if(!$dbc->query($sql)){
			$err_msg .= '<span style="color:#c00"><i class="fa fa-times-circle"></i>&nbsp; Create database <b>Positions</b> failed. Error : <b>'.mysqli_error($dbc).'</b></span><br>';
			$error = true;
		}else{
			$err_msg .= '<i class="fa fa-check-square-o"></i>&nbsp; Database <b>Positions</b> created successfuly.<br>';
		}
	}else{
		$err_msg .= '<i class="fa fa-check-square-o"></i>&nbsp; Database <b>Positions</b> exists already.<br>';
	}
	
	$db_name = $cid."_scanfiles";
	if(!$dbc->query("DESCRIBE `$db_name`")) {
		$sql = "CREATE TABLE IF NOT EXISTS `$db_name` (
		  `id` int(11) NULL AUTO_INCREMENT,
		  `date` date NULL,
		  `period` varchar(50) COLLATE utf8_bin NULL,
		  `content` text COLLATE utf8_bin NULL,
		  `filename` varchar(50) COLLATE utf8_bin NULL,
		  `import` varchar(11) NULL,
		  `status` varchar(11) NULL, 
		   `scansystem` varchar(50) COLLATE utf8_bin NULL,
		  `in_out` varchar(50) COLLATE utf8_bin NULL,
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
	
	$db_name = $cid."_sys_settings";
	if(!$dbc->query("DESCRIBE `$db_name`")) {
		$sql = "CREATE TABLE IF NOT EXISTS `$db_name` (
		  `id` int(11) NULL,
			`cur_month` varchar(11) NULL,
			`cur_year` varchar(11) NULL,
			`years` text COLLATE utf8_bin NULL,
			`pr_startdate` varchar(20) COLLATE utf8_bin NULL,
			`sso_idnr` varchar(20) COLLATE utf8_bin  NULL,
			`sso_act_max` varchar(5) COLLATE utf8_bin  NULL,
			`tax_idnr` varchar(50) COLLATE utf8_bin  NULL,
			`personal_idnr` varchar(20) COLLATE utf8_bin  NULL,
			`fix_allow` text COLLATE utf8_bin  NULL,
			`var_allow` text COLLATE utf8_bin  NULL,
			`fix_deduct` text COLLATE utf8_bin  NULL,
			`var_deduct` text COLLATE utf8_bin  NULL,
			`paydate` varchar(20) COLLATE utf8_bin  NULL,
			`att_cols` text COLLATE utf8_bin  NULL,
			`att_showhide_cols` varchar(255) COLLATE utf8_bin  NULL,
			`history` varchar(11)  NULL,
			`his_cols` text COLLATE utf8_bin  NULL,
			`his_showhide_cols` varchar(255) COLLATE utf8_bin  NULL,
			`emp_export_fields` text COLLATE utf8_bin  NULL,
			`payslip_template` varchar(10) COLLATE utf8_bin  NULL,
			`payslip_field` text COLLATE utf8_bin  NULL,
			`payslip_rate` varchar(10) COLLATE utf8_bin  NULL,
			`show_address` varchar(11) NULL DEFAULT 1,
			`show_bankinfo` varchar(11) NULL DEFAULT 1,
			`show_position` varchar(11) NULL DEFAULT 1,
			`show_department` varchar(11) NULL DEFAULT 1,
			`support_email` varchar(50) COLLATE utf8_bin  NULL,
			`account_codes` text COLLATE utf8_bin  NULL,
			`account_allocations` text COLLATE utf8_bin  NULL,
			`demo` varchar(11)  NULL,
			`joining_date` varchar(10) COLLATE utf8_bin  NULL,
			`emp_group` varchar(10) COLLATE utf8_bin  NULL,
			`team` text COLLATE utf8_bin  NULL,
			`shiftplan_schedule` text COLLATE utf8_bin  NULL,
			`teams_name` text COLLATE utf8_bin  NULL,
			`teams` text COLLATE utf8_bin  NULL,
			`position` varchar(11)  NULL,
			`date_start` varchar(10) COLLATE utf8_bin  NULL,
			`shift_team` varchar(20) COLLATE utf8_bin  NULL,
			`time_reg` varchar(11)  NULL,
			`selfie` varchar(11)  NULL,
			`leeve` varchar(11)  NULL,
			`emp_type` varchar(11)  NULL,
			`emp_status` varchar(11)  NULL,
			`account_code` varchar(11)  NULL,
			`pay_type` varchar(10) COLLATE utf8_bin  NULL,
			`bank_transfer` varchar(11)  NULL,
			`calc_tax` varchar(11)  NULL,
			`calc_method` varchar(10) COLLATE utf8_bin  NULL,
			`pnd` varchar(11)  NULL,
			`calc_sso` varchar(11)  NULL,
			`contract_type` varchar(10) COLLATE utf8_bin  NULL,
			`calc_base` varchar(10) COLLATE utf8_bin  NULL,
			`base_ot_rate` varchar(10) COLLATE utf8_bin  NULL,
			`ot_rate` varchar(11)  NULL,
			`calc_psf` varchar(11)  NULL,
			`psf_name` varchar(50) COLLATE utf8_bin  NULL,
			`psf_rate_emp` varchar(11) NULL,
			`psf_rate_com` varchar(11)  NULL,
			`psf_thb_emp` varchar(11)  NULL,
			`psf_thb_com` varchar(11)  NULL,
			`calc_pvf` varchar(11)  NULL,
			`pvf_idnr` varchar(20) COLLATE utf8_bin  NULL,
			`pvf_name` varchar(50) COLLATE utf8_bin  NULL,
			`pvf_rate_emp` varchar(11)  NULL,
			`auto_id` varchar(11)  NULL,
			`pvf_rate_com` varchar(11)  NULL,
			`id_start` varchar(11)  NULL,
			`id_prefix` varchar(50) COLLATE utf8_bin  NULL,
			`scan_id` varchar(11)  NULL, 
			`two_factor_authentication` varchar(255)  NULL, 
		  PRIMARY KEY (`id`) 
		) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin";
		if(!$dbc->query($sql)){
			$err_msg .= '<span style="color:#c00"><i class="fa fa-times-circle"></i>&nbsp; Create database <b>System settings</b> failed. Error : <b>'.mysqli_error($dbc).'</b></span><br>';
			$error = true;
		}else{
			$err_msg .= '<i class="fa fa-check-square-o"></i>&nbsp; Database <b>System settings</b> created successfuly.<br>';
		}
	}else{
		$err_msg .= '<i class="fa fa-check-square-o"></i>&nbsp; Database <b>System settings</b> exists already.<br>';
	}
	
	$db_name = $cid."_tax_simulation";
	if(!$dbc->query("DESCRIBE `$db_name`")) {
		$sql = "CREATE TABLE IF NOT EXISTS `$db_name` (
			`emp_id` varchar(50) COLLATE utf8_bin NULL,
			`en_name` varchar(50) COLLATE utf8_bin NULL,
			`th_name` varchar(50) COLLATE utf8_bin NULL,
			`basic_salary` varchar(255) NULL,
			`fix_allow` varchar(255) NULL,
			`year_bonus` varchar(255) NULL,
			`avg_var_allow` varchar(255) NULL,
			`avg_overtime` varchar(255) NULL,
			`pvf_rate_emp` varchar(255) NULL,
			`pvf_rate_com` varchar(255) NULL,
			`pvf_rate_empr` varchar(255) NULL,
			`tax_deductions` varchar(255) NULL,
			`modify_tax` varchar(255) NULL,
			`calc_method` varchar(20) COLLATE utf8_bin NULL,
			`calc_sso` varchar(20) COLLATE utf8_bin NULL,
			`calc_pvf` varchar(20) COLLATE utf8_bin NULL,
			`calc_tax` varchar(20) COLLATE utf8_bin NULL,
			`gross_income_year` varchar(255) NULL,
			`taxable_gross` varchar(255) NULL,
			`pers_tax_deduct_gross` varchar(255) NULL,
			`pers_tax_deduct_net` varchar(255) NULL,
			`net_income_year` varchar(255) NULL,
			`taxable_net` varchar(255) NULL,
			`calculate_gross` text COLLATE utf8_bin NULL,
			`net_from_gross` text COLLATE utf8_bin NULL,
			`calculate_net` text COLLATE utf8_bin NULL,
			`gross_from_net` text COLLATE utf8_bin NULL,
			`calculate_current` text COLLATE utf8_bin NULL,
			PRIMARY KEY (`emp_id`)
			) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_bin";
		if(!$dbc->query($sql)){
			$err_msg .= '<span style="color:#c00"><i class="fa fa-times-circle"></i>&nbsp; Create database <b>Tax simulation</b> failed. Error : <b>'.mysqli_error($dbc).'</b></span><br>';
			$error = true;
		}else{
			$err_msg .= '<i class="fa fa-check-square-o"></i>&nbsp; Database <b>Tax simulation</b> created successfuly.<br>';
		}
	}else{
		$err_msg .= '<i class="fa fa-check-square-o"></i>&nbsp; Database <b>Tax simulation</b> exists already.<br>';
	}
	
	$db_name = $cid."_teams";
	if(!$dbc->query("DESCRIBE `$db_name`")) {
		$sql = "CREATE TABLE IF NOT EXISTS `$db_name` (
		`id` int(11) NULL AUTO_INCREMENT,
		`code` varchar(20) COLLATE utf8_bin NULL,
		`th` varchar(100) COLLATE utf8_bin NULL,
		`en` varchar(100) COLLATE utf8_bin NULL,
		`entity` varchar(10) COLLATE utf8_bin NULL,
		`branch` varchar(10) COLLATE utf8_bin  NULL,
		`division` varchar(10) COLLATE utf8_bin NULL,
		`department` varchar(10) COLLATE utf8_bin NULL, 
		PRIMARY KEY (`id`)
		) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_bin";
		if(!$dbc->query($sql)){
			$err_msg .= '<span style="color:#c00"><i class="fa fa-times-circle"></i>&nbsp; Create database <b>Teams</b> failed. Error : <b>'.mysqli_error($dbc).'</b></span><br>';
			$error = true;
		}else{
			$err_msg .= '<i class="fa fa-check-square-o"></i>&nbsp; Database <b>Teams</b> created successfuly.<br>';
		}
	}else{
		$err_msg .= '<i class="fa fa-check-square-o"></i>&nbsp; Database <b>Teams</b> exists already.<br>';
	}
	
	$db_name = $cid."_users";
	if(!$dbc->query("DESCRIBE `$db_name`")) {
		$sql = "CREATE TABLE IF NOT EXISTS `$db_name` (
			`id` int(11) NULL AUTO_INCREMENT,
			`ref` varchar(11) NULL,
			`username` varchar(50) COLLATE utf8_bin NULL,
			`emp_id` varchar(10) COLLATE utf8_bin NULL,
			`firstname` varchar(20) COLLATE utf8_bin  NULL,
			`name` varchar(50) COLLATE utf8_bin NULL,
			`phone` varchar(20) COLLATE utf8_bin NULL,
			`type` varchar(10) COLLATE utf8_bin NULL,
			`access` varchar(11) NULL,
			`entities` varchar(255) COLLATE utf8_bin NULL,
			`branches` varchar(255) COLLATE utf8_bin NULL,
			`divisions` varchar(255) COLLATE utf8_bin NULL,
			`departments` varchar(255) COLLATE utf8_bin NULL,
			`teams` varchar(255) COLLATE utf8_bin NULL,
			`access_selection` text COLLATE utf8_bin NULL,
			`permissions` text COLLATE utf8_bin NULL,
			`emp_group` varchar(10) COLLATE utf8_bin NULL,
			`img` varchar(100) COLLATE utf8_bin NULL,
			`status` varchar(11) NULL,
			`emp_cols` text COLLATE utf8_bin NULL,
			`att_cols` text COLLATE utf8_bin NULL, 	  
			PRIMARY KEY (`id`) 
		) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_bin";
		if(!$dbc->query($sql)){
			$err_msg .= '<span style="color:#c00"><i class="fa fa-times-circle"></i>&nbsp; Create database <b>System users</b> failed. Error : <b>'.mysqli_error($dbc).'</b></span><br>';
			$error = true;
		}else{
			$err_msg .= '<i class="fa fa-check-square-o"></i>&nbsp; Database <b>System users</b> created successfuly.<br>';
		}
	}else{
		$err_msg .= '<i class="fa fa-check-square-o"></i>&nbsp; Database <b>System users</b> exists already.<br>';
	}
	

	$db_name = $cid."_scandata";
	if(!$dbc->query("DESCRIBE `$db_name`")) {
		$sql = "CREATE TABLE IF NOT EXISTS `$db_name` (
		  `id` int(11) NULL AUTO_INCREMENT,
		  `filename` varchar(255) COLLATE utf8_bin NULL,
		  `datescan` date NULL,
		  `timescan` varchar(50) COLLATE utf8_bin NULL,
		  `emp_id` varchar(50) COLLATE utf8_bin NULL,
		  `emp_name` varchar(255) COLLATE utf8_bin NULL,
		  `shift_plan_date` date NULL,
		  `shift_plan_start_time` varchar(50) COLLATE utf8_bin NULL,
		  `scan_in` varchar(50) COLLATE utf8_bin NULL,
		  `scan_out` varchar(50) COLLATE utf8_bin NULL,
		  `status` varchar(11) NULL,
		  `picture` varchar(255) COLLATE utf8_bin NULL,
		  `scan_id` varchar(50) COLLATE utf8_bin NULL,
		  `checkbox` varchar(10) COLLATE utf8_bin NULL,
		  `datescanout` date NULL,
		  `linkedPlan` varchar(255) COLLATE utf8_bin NULL,
		  `all_scan_values` text COLLATE utf8_bin NULL,



		  PRIMARY KEY (`id`) 
		) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_bin";
		if(!$dbc->query($sql)){
			$err_msg .= '<span style="color:#c00"><i class="fa fa-times-circle"></i>&nbsp; Create database <b>Scan Data</b> failed. Error : <b>'.mysqli_error($dbc).'</b></span><br>';
			$error = true;
		}else{
			$err_msg .= '<i class="fa fa-check-square-o"></i>&nbsp; Database <b>Scan Data</b> created successfuly.<br>';
		}
	}else{
		$err_msg .= '<i class="fa fa-check-square-o"></i>&nbsp; Database <b>Scan Data</b> exists already.<br>';
	}	

	$db_name = $cid."_metascandata";
	if(!$dbc->query("DESCRIBE `$db_name`")) {
		$sql = "CREATE TABLE IF NOT EXISTS `$db_name` (
		  `id` int(11) NULL AUTO_INCREMENT,
		  `filename` varchar(255) COLLATE utf8_bin NULL,
		  `datescan` date NULL,
		  `datescanout` date NULL,
		  `timescan` varchar(50) COLLATE utf8_bin NULL,
		  `emp_id` varchar(50) COLLATE utf8_bin NULL,
		  `emp_name` varchar(255) COLLATE utf8_bin NULL,
		  `shift_plan_date` date NULL,
		  `shift_plan_start_time` varchar(50) COLLATE utf8_bin NULL,
		  `shift_plan_value` varchar(200) COLLATE utf8_bin NULL,
		  `scan_in` varchar(50) COLLATE utf8_bin NULL,
		  `scan_out` varchar(50) COLLATE utf8_bin NULL,
		  `status` varchar(11) NULL,
		  `picture` varchar(255) COLLATE utf8_bin NULL,
		  `scan_id` varchar(50) COLLATE utf8_bin NULL,
		  `scandata_id` varchar(200) COLLATE utf8_bin NULL,
		  `in_or_out` varchar(50) COLLATE utf8_bin NULL,
		  `linkedPlan` varchar(255) COLLATE utf8_bin NULL,

		  PRIMARY KEY (`id`) 
		) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_bin";
		if(!$dbc->query($sql)){
			$err_msg .= '<span style="color:#c00"><i class="fa fa-times-circle"></i>&nbsp; Create database <b>Meta Scan Data</b> failed. Error : <b>'.mysqli_error($dbc).'</b></span><br>';
			$error = true;
		}else{
			$err_msg .= '<i class="fa fa-check-square-o"></i>&nbsp; Database <b>Meta Scan Data</b> created successfuly.<br>';
		}
	}else{
		$err_msg .= '<i class="fa fa-check-square-o"></i>&nbsp; Database <b>Meta Scan Data</b> exists already.<br>';
	}

	$db_name = $cid."_location";
	if(!$dbc->query("DESCRIBE `$db_name`")) {
		$sql = "CREATE TABLE IF NOT EXISTS `$db_name` (
			 `loc_id` int(11) NULL AUTO_INCREMENT,
			  `loc_name` text,
			  `address` text,
			  `code` varchar(255) DEFAULT NULL,
			  `qr` text,
			  `latitude` varchar(255) DEFAULT NULL,
			  `longitude` varchar(255) DEFAULT NULL,
			  `perimeter` varchar(255) DEFAULT NULL,
			  `contact_name` varchar(255) DEFAULT NULL,
			  `contact_email` varchar(255) DEFAULT NULL,
			  `link_expire` varchar(11) NULL DEFAULT '0',
			  `scan_in_confirmation` varchar(11) NULL DEFAULT '0',
			  `scan_out_confirmation` varchar(11) NULL DEFAULT '0',
		  PRIMARY KEY (`loc_id`)
		) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_bin";
		if(!$dbc->query($sql)){
			$err_msg .= '<span style="color:#c00"><i class="fa fa-times-circle"></i>&nbsp; Create database <b>Location</b> failed. Error : <b>'.mysqli_error($dbc).'</b></span><br>';
			$error = true;
		}else{
			$err_msg .= '<i class="fa fa-check-square-o"></i>&nbsp; Database <b>Location</b> created successfuly.<br>';
		}
	}else{
		$err_msg .= '<i class="fa fa-check-square-o"></i>&nbsp; Database <b>Location</b> exists already.<br>';
	}
		
	$db_name = $cid."_ot_plans";
	if(!$dbc->query("DESCRIBE `$db_name`")) {
		$sql = "CREATE TABLE IF NOT EXISTS `$db_name` (
			`id` int(11) NULL AUTO_INCREMENT,
			`date` date NULL,
			`shiftteam` varchar(20) COLLATE utf8_bin NULL,
			`plan` varchar(10) COLLATE utf8_bin NULL,
			`plan_f1` varchar(10) COLLATE utf8_bin NULL,
			`plan_u2` varchar(10) COLLATE utf8_bin NULL,
			`ot_from` varchar(10) COLLATE utf8_bin NULL,
			`ot_until` varchar(10) COLLATE utf8_bin NULL,
			`ot_break` varchar(10) COLLATE utf8_bin NULL,
			`hours` varchar(10) COLLATE utf8_bin NULL,
			`type` varchar(10) COLLATE utf8_bin NULL,
			`compensations` text COLLATE utf8_bin NULL,
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
			`id` varchar(50) COLLATE utf8_bin NULL,
			`ot_plan` varchar(11) NULL,
			`month` varchar(11) NULL,
			`date` varchar(20) COLLATE utf8_bin NULL,
			`emp_id` varchar(50) COLLATE utf8_bin NULL,
			`en_name` varchar(50) COLLATE utf8_bin NULL,
			`th_name` varchar(50) COLLATE utf8_bin NULL,
			`shiftteam` varchar(50) COLLATE utf8_bin NULL,
			`position` varchar(11) NULL,
			`ot_from` varchar(10) COLLATE utf8_bin NULL,
			`ot_until` varchar(10) COLLATE utf8_bin NULL,
			`ot_hours` varchar(10) COLLATE utf8_bin NULL,
			`ot_break` varchar(10) COLLATE utf8_bin NULL,
			`ot_type` varchar(10) COLLATE utf8_bin NULL,
			`ot_invited` varchar(11) NULL,
			`ot_confirmed` varchar(11) NULL,
			`ot_assigned` varchar(11) NULL,
			`ot_compensations` text COLLATE utf8_bin NULL,		  
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
	
	$db_name = $cid."_workpermit";
	if(!$dbc->query("DESCRIBE `$db_name`")) { 
		$sql = "CREATE TABLE IF NOT EXISTS `$db_name` (
		  `emp_id` varchar(20) COLLATE utf8_bin NULL,
		  `title` varchar(20) COLLATE utf8_bin NULL,
		  `name_en` varchar(100) COLLATE utf8_bin NULL,
		  `name_th` varchar(100) COLLATE utf8_bin NULL,
		  `image` varchar(100) COLLATE utf8_bin NULL,
		  `nationality` varchar(50) COLLATE utf8_bin NULL,
		  `maritial` varchar(50) COLLATE utf8_bin NULL,
		  `blood_group` varchar(20) COLLATE utf8_bin NULL,
		  `birthdate` varchar(20) COLLATE utf8_bin NULL,
		  `address` tinytext COLLATE utf8_bin NULL,
		  `position` varchar(100) COLLATE utf8_bin NULL,
		  `job_en` tinytext COLLATE utf8_bin NULL,
		  `job_th` tinytext COLLATE utf8_bin NULL,
		  `family` text COLLATE utf8_bin NULL,
		  `attach_passport` varchar(255) COLLATE utf8_bin NULL,
		  `attach_medical` varchar(255) COLLATE utf8_bin NULL,
		  `attach_job_en` varchar(255) COLLATE utf8_bin NULL,
		  `per_attach1` varchar(255) COLLATE utf8_bin NULL,
		  `per_attach2` varchar(255) COLLATE utf8_bin NULL,
		  `per_attach3` varchar(255) COLLATE utf8_bin NULL,
		  `per_attach4` varchar(255) COLLATE utf8_bin NULL,
		  `per_attach5` varchar(255) COLLATE utf8_bin NULL,
		  `attach6` varchar(255) COLLATE utf8_bin NULL,
		  `attach7` varchar(255) COLLATE utf8_bin NULL,
		  `attach8` varchar(255) COLLATE utf8_bin NULL,
		  `attach9` varchar(255) COLLATE utf8_bin NULL,
		  `attach10` varchar(255) COLLATE utf8_bin NULL,
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
	}
	
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
	
	$oldDir = $prefix.'admin.';
	$newDir = $prefix.$cid.'.';
	
	$old_db_name = $oldDir.'rego_default_holidays';
	$new_db_name = $newDir.$cid.'_holidays';
	
	//var_dump($old_db_name);
	//var_dump($new_db_name); exit;

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
	
	//var_dump($err_msg); exit;








