<?

	if(session_id()==''){session_start(); ob_start();}
	include("../dbconnect/db_connect.php");
	

	$id = $_REQUEST['id'];
	unset($_REQUEST['id']);
	$_REQUEST['name'] = $_REQUEST['firstname'].' '.$_REQUEST['lastname'];
	//var_dump($_REQUEST); exit;
	$company_id = $_REQUEST['hidden_company_id'];
	$pcompany_id = $prefix.$_REQUEST['hidden_company_id'];

	// Update Columns
	// Insert Default Values
	// Update Columns 
	// Insert 2 new tables 

	// echo '<pre>';
	// print_r($_REQUEST);
	// echo '</pre>';

	// die();



	$my_dbcname = $prefix.$company_id;
	$dbc = new mysqli($my_database,$my_username,$my_password,$my_dbcname);
	if($dbc->connect_error) {
		echo '<p style="width:900px; margin:0 auto; margin-top:20px;" class="box_err">Error: ('.$dbc->connect_errno.') '.$dbc->connect_error.'<br>Please try again later or report this error to <a href="mailto:admin@regohr.com">admin@regohr.com</a></p>';
	}else{
		mysqli_set_charset($dbc,"utf8");
	}




	// attendance table

	if ($dbc->query("SELECT filename FROM ".$company_id."_attendance " ))
	{
	        //my_column exists in my_table
	}
	else
	{
	        //my_column doesn't exist in my_table
			// insert filename coulmn 
			$dbc->query("ALTER TABLE ".$company_id."_attendance  ADD `filename` VARCHAR(255) NOT NULL AFTER `actual_days` " ) ;
	}



	// branch_ data table 

	if ($dbc->query("SELECT loc1 FROM ".$company_id."_branches_data" ))
	{
	        //my_column exists in my_table
	}
	else
	{
	        //my_column doesn't exist in my_table
			$dbc->query("ALTER TABLE ".$company_id."_branches_data ADD `common_branch_id` VARCHAR(255) NOT NULL AFTER `sso_address_en`, ADD `qrcodedata` TEXT NOT NULL AFTER `common_branch_id`, ADD `serialno` VARCHAR(255) NOT NULL AFTER `qrcodedata`, ADD `loc1` TEXT NOT NULL AFTER `serialno`, ADD `loc2` TEXT NOT NULL AFTER `loc1`, ADD `loc3` TEXT NOT NULL AFTER `loc2`, ADD `loc4` TEXT NOT NULL AFTER `loc3`, ADD `loc5` TEXT NOT NULL AFTER `loc4` " ) ;
	}


	// employee table 

	if ($dbc->query("SELECT team_name FROM ".$company_id."_employees" ))
	{
	        //my_column exists in my_table
	}
	else
	{
	        //my_column doesn't exist in my_table
			$dbc->query("ALTER TABLE ".$company_id."_employees ADD `teams` VARCHAR(255) NOT NULL AFTER `ot_rate`, ADD `team_name` VARCHAR(255) NOT NULL AFTER `teams`" ) ;
	}




	// metascandata table 

	if ($dbc->query("SELECT id FROM ".$company_id."_metascandata" ))
	{
	        //my_column exists in my_table
	}
	else
	{
	        //my_column doesn't exist in my_table
			$dbc->query("CREATE TABLE  ".$company_id."_metascandata (
				  `id` int(11) NOT NULL,
				  `filename` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
				  `datescan` date NOT NULL,
				  `datescanout` date NOT NULL,
				  `timescan` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
				  `emp_id` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
				  `emp_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
				  `shift_plan_date` date NOT NULL,
				  `shift_plan_start_time` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
				  `shift_plan_value` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
				  `scan_in` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
				  `scan_out` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
				  `status` int(11) NOT NULL,
				  `picture` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
				  `scan_id` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
				  `scandata_id` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
				  `in_or_out` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
				  `linkedPlan` varchar(255) COLLATE utf8_unicode_ci NOT NULL
				) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
				 " ) ;

			$dbc->query("ALTER TABLE ".$company_id."_metascandata ADD PRIMARY KEY (`id`)" ) ;
			$dbc->query("ALTER TABLE  ".$company_id."_metascandata MODIFY `id` int(11) NOT NULL AUTO_INCREMENT" ) ;
	}

	
	// monthly_shiftplans_year

	if ($dbc->query("SELECT bal_off FROM ".$company_id."_monthly_shiftplans_2021" ))
	{
	        //my_column exists in my_table
	}
	else
	{
	        //my_column doesn't exist in my_table
			$dbc->query("ALTER TABLE  ".$company_id."_monthly_shiftplans_2021 ADD `shiftteam_name` VARCHAR(255) NOT NULL AFTER `shiftteam`, ADD `wkd` VARCHAR(255) NOT NULL AFTER `shiftteam_name`, ADD `pub` VARCHAR(255) NOT NULL AFTER `wkd`, ADD `off` VARCHAR(255) NOT NULL AFTER `pub`, ADD `vod` VARCHAR(255) NOT NULL AFTER `off`, ADD `off_day_used` VARCHAR(255) NOT NULL AFTER `vod`, ADD `bal_off` TEXT NOT NULL AFTER `off_day_used`" ) ;
	}



	// scandata table 

	if ($dbc->query("SELECT id FROM ".$company_id."_scandata" ))
	{
	        //my_column exists in my_table
	}
	else
	{
	        //my_column doesn't exist in my_table
			$dbc->query("CREATE TABLE ".$company_id."_scandata (
					  `id` int(11) NOT NULL,
					  `filename` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
					  `datescan` date NOT NULL,
					  `timescan` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
					  `emp_id` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
					  `emp_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
					  `shift_plan_date` date NOT NULL,
					  `shift_plan_start_time` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
					  `scan_in` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
					  `scan_out` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
					  `status` int(11) NOT NULL,
					  `picture` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
					  `scan_id` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
					  `checkbox` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
					  `datescanout` date NOT NULL,
					  `linkedPlan` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
					  `all_scan_values` text COLLATE utf8_unicode_ci NOT NULL
					) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
				 " ) ;

			$dbc->query("ALTER TABLE ".$company_id."_scandata ADD PRIMARY KEY (`id`)" ) ;
			$dbc->query("ALTER TABLE ".$company_id."_scandata MODIFY `id` int(11) NOT NULL AUTO_INCREMENT" ) ;
	}



	// scanfiles

	if ($dbc->query("SELECT scansystem FROM ".$company_id."_scanfiles" ))
	{
	        //my_column exists in my_table
	}
	else
	{
	        //my_column doesn't exist in my_table
			$dbc->query("ALTER TABLE ".$company_id."_scanfiles ADD `scansystem` VARCHAR(50) NOT NULL AFTER `status`, ADD `in_out` VARCHAR(50) NOT NULL AFTER `scansystem`" ) ;
	}	


	// shiftplans 2021

	if ($dbc->query("SELECT cycle_details FROM ".$company_id."_shiftplans_2021" ))
	{
	        //my_column exists in my_table
	}
	else
	{
	        //my_column doesn't exist in my_table
			$dbc->query("ALTER TABLE ".$company_id."_shiftplans_2021 ADD `new_plan` TEXT NOT NULL AFTER `ss_data`, ADD `cycle_details` TEXT NOT NULL AFTER `new_plan`, ADD `wh_code` TEXT NOT NULL AFTER `cycle_details`, ADD `wkd` TEXT NOT NULL AFTER `wh_code`" ) ;
	}	

	// sys setting

	if ($dbc->query("SELECT show_position FROM ".$company_id."_sys_settings" ))
	{
	        //my_column exists in my_table
	}
	else
	{
	        //my_column doesn't exist in my_table
			$dbc->query("ALTER TABLE  ".$company_id."_sys_settings ADD `show_position` INT NOT NULL DEFAULT '1' AFTER `show_bankinfo`, ADD `show_department` INT NOT NULL DEFAULT '1' AFTER `show_position`" ) ;
			$dbc->query("ALTER TABLE ".$company_id."_sys_settings ADD `shiftplan_schedule` TEXT NOT NULL AFTER `team`, ADD `teams_name` TEXT NOT NULL AFTER `shiftplan_schedule`, ADD `teams` TEXT NOT NULL AFTER `teams_name`" ) ;
			$dbc->query("ALTER TABLE ".$company_id."_sys_settings CHANGE `team` `team` TEXT NOT NULL;" ) ;
			$dbc->query("UPDATE ".$company_id."_sys_settings SET `team` = 'main', `shiftplan_schedule` = 'Default Shift Schedule', `teams_name` = 'Team #001', `teams` = 'a:3:{s:2:\"en\";a:1:{s:4:\"MAIN\";s:9:\"Team #001\";}s:2:\"th\";a:1:{s:4:\"MAIN\";s:9:\"Team #001\";}s:7:\"code_id\";a:1:{s:4:\"MAIN\";s:4:\"MAIN\";}}' WHERE ".$company_id."_sys_settings.`id` = 1" ) ;

			// get teams from the teams table and update it in sys setting team column
			$teams = array();
			$sql11 = "SELECT * FROM ".$company_id."_teams";
			if($ress = $dbc->query($sql11))
			{
				while($rows = $ress->fetch_assoc())
				{

					$rowss[] = $rows;
					
				}
			}

			foreach ($rowss as $keys => $valuess) {

				$teams['en'][$valuess['code']] = $valuess['en'];
				$teams['th'][$valuess['code']] = $valuess['th'];
				$teams['code_id'][$valuess['code']] = $valuess['code'];

			}
			$dbc->query("UPDATE ".$company_id."_sys_settings SET  `teams` = '".serialize($teams)."' WHERE ".$company_id."_sys_settings.`id` = 1" ) ;

			$dbc->query("UPDATE ".$company_id."_leave_time_settings SET `shiftplan` = 'a:3:{s:3:\"DWD\";a:14:{s:4:\"code\";s:3:\"DWD\";s:2:\"bg\";s:7:\"#EC6F86\";s:4:\"name\";s:15:\"Default workday\";s:2:\"f1\";s:5:\"08:00\";s:2:\"u1\";s:5:\"12:00\";s:2:\"f2\";s:5:\"13:00\";s:2:\"u2\";s:5:\"17:00\";s:5:\"first\";s:5:\"04:00\";s:12:\"firstThidden\";s:5:\"04:00\";s:6:\"second\";s:5:\"04:00\";s:13:\"secondThidden\";s:5:\"04:00\";s:5:\"hours\";s:5:\"08:00\";s:5:\"break\";s:5:\"01:00\";s:8:\"addbreak\";s:5:\"00:00\";}s:3:\"ES8\";a:14:{s:4:\"code\";s:3:\"ES8\";s:2:\"bg\";s:7:\"#FFDD75\";s:4:\"name\";s:24:\"Early shift 06:00 -14:00\";s:2:\"f1\";s:5:\"06:00\";s:2:\"u1\";s:5:\"10:00\";s:2:\"f2\";s:5:\"10:30\";s:2:\"u2\";s:5:\"14:00\";s:5:\"first\";s:5:\"04:00\";s:12:\"firstThidden\";s:5:\"04:00\";s:6:\"second\";s:5:\"03:30\";s:13:\"secondThidden\";s:5:\"03:30\";s:5:\"hours\";s:5:\"07:30\";s:5:\"break\";s:5:\"00:30\";s:8:\"addbreak\";s:5:\"00:00\";}s:3:\"LS8\";a:14:{s:4:\"code\";s:3:\"LS8\";s:2:\"bg\";s:7:\"#9FF3C3\";s:4:\"name\";s:23:\"Late shift 14:00 -22:00\";s:2:\"f1\";s:5:\"14:00\";s:2:\"u1\";s:5:\"18:00\";s:2:\"f2\";s:5:\"18:30\";s:2:\"u2\";s:5:\"22:00\";s:5:\"first\";s:5:\"04:00\";s:12:\"firstThidden\";s:5:\"04:00\";s:6:\"second\";s:5:\"03:30\";s:13:\"secondThidden\";s:5:\"03:30\";s:5:\"hours\";s:5:\"07:30\";s:5:\"break\";s:5:\"00:30\";s:8:\"addbreak\";s:5:\"00:00\";}}' WHERE ".$company_id."_leave_time_settings.`id` = 1;" ) ;




	}


	$teamsarr  = array();
	$sql12 = "SELECT * FROM ".$company_id."_teams ";


	if($res12 = $dbc->query($sql12)){
		while($row12 = $res12->fetch_assoc())
		{
			$trimmedVal = preg_replace('/\s+/', '', $row12['code']);
			 $teamsarr[$row12['id']] = strtolower($trimmedVal);

		}
	}
	


	$employee_arr =  array();
	$sql13 = "SELECT * FROM ".$company_id."_employees ";

	if($res13 = $dbc->query($sql13))
	{
		while($row13 = $res13->fetch_assoc())
		{
			$employee_arr[]=$row13 ;
		}
	}
		



	
	foreach ($employee_arr as $key3 => $value3) 
	{
			$value4 = $teamsarr[$value3['team']];

			$dbc->query("UPDATE ".$company_id."_employees SET  `teams` = '".$value4."' , team_name = '".strtoupper($value4)."' WHERE ".$company_id."_employees.`team` = '".$value3['team']."'" ) ;
			
	}

	


	unset($_REQUEST['hidden_company_id']);



	if($_REQUEST['version'] == '400' || $_REQUEST['version'] == '300' )
	{
		// update customer who is in 300 standard leave setting values in elite 
	}
	else
	{

		// UPDATE LEAVE TYPE IN LEAVE TIME SETTING TABLE 
		// GET ADMIN DEFAULT LEAVE TYPE 

		$leave_type_arr =  array();
		$sql14 = "SELECT * FROM rego_default_leave_time_settings ";

		if($res14 = $dba->query($sql14))
		{
			while($row14 = $res14->fetch_assoc())
			{
				$leave_types		=$row14['leave_types'] ;
				$request 			=$row14['request'] ;
				$workingdays 		=$row14['workingdays'] ; //daysperweek
				$dayhours 			=$row14['dayhours'] ; //hoursperday
				$calc_attendance 	=$row14['calc_attendance'] ; //attendancecalaculation
				$attendance_target 	=$row14['attendance_target'] ; //attendancetarget

				$dbc->query("UPDATE ".$company_id."_leave_time_settings SET  `leave_types` = '".$leave_types."' , `request` = '".$request."', `workingdays` = '".$workingdays."', `dayhours` = '".$dayhours."', `calc_attendance` = '".$calc_attendance."', `attendance_target` = '".$attendance_target."' WHERE `id` = '1'" ) ;
			}
		}
	}


	// $_REQUEST['version']; // 300





	$sql = "UPDATE rego_customers SET ";
	foreach($_REQUEST as $k=>$v){
		$sql .= $k." = '".$dba->real_escape_string($v)."', ";
	}
	$sql = substr($sql,0,-2);
	$sql .= " WHERE id = '".$id."'";
	//echo $sql; exit;
	ob_clean();	
	if($dba->query($sql)){
		echo 'success';
	}else{
		echo mysqli_error($dba);
	}
