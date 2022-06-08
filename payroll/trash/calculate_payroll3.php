<?php
	if(session_id()==''){session_start();}
	ob_start();

	include('../../dbconnect/db_connect.php');
	include(DIR.'files/functions.php');
	include(DIR.'files/payroll_functions.php');
	//var_dump($_REQUEST); exit;
	
	$fix_deductions = unserialize($sys_settings['fix_deduct']);
	$var_deductions = unserialize($sys_settings['var_deduct']);
	//var_dump($sys_settings); exit;
	
	$msg = '';
	$save = $_REQUEST['change'];
	unset($_REQUEST['change']);
	
	if($save == 1){ // SAVE ATTENDANCE DATA !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
		$c = count($_REQUEST['paid_days']);
		for($i=0;$i<$c;$i++){
			$id[$i] = $_REQUEST['empid'][$i];
		}
		unset($_REQUEST['empid']);
		foreach($_REQUEST as $key => $val){
			foreach($val as $k=>$v){
				$data[$key][$id[$k]] = $v; 
			}
		}
		foreach($data as $key => $val){ // save attendance data
			foreach($val as $k=>$v){
				$sql = "UPDATE ".$_SESSION['rego']['payroll_dbase']." SET ".$key." = '".$v."' WHERE id = '".$k.$_SESSION['rego']['cur_month']."'";
				$dbc->query($sql);
			}
		}
	}
	
	$tax_settings = unserialize($rego_settings['tax_settings']);
	$taxrules = unserialize($rego_settings['taxrules']);	
	
	$tax_allow['fix'] = getFixAllowances($pr_settings);
	$tax_allow['var'] = getVarAllowances($pr_settings);

	$workdays = ($rego_settings['days_month'] == 0 ? 30 : $rego_settings['days_month']);
	$dayhours = ($rego_settings['hours_day'] == 0 ? 8 : $rego_settings['hours_day']);
	
	/*$remaining_months = 12;
	if(trim($pr_settings['pr_startdate']) != ''){
		$joinyear = date('Y', strtotime($pr_settings['pr_startdate']));
		$joinmonth = date('n', strtotime($pr_settings['pr_startdate']));
		if($joinyear == $_SESSION['rego']['cur_year'] && $joinmonth > 1){
			$remaining_months = 13-$joinmonth;
		}
	}*/
	
	$cur_month = $_SESSION['rego']['cur_month'];
	$remaining_months = 12 - (int)$_SESSION['rego']['cur_month'];
	$last_day = cal_days_in_month(CAL_GREGORIAN, $_SESSION['rego']['cur_month'], $_SESSION['rego']['cur_year']);
	
	if(isset($_REQUEST['prid'])){ // RECALCULATE EMPLOYEE ////////////////////////////////////////////////////////////////////////
		$sql = "SELECT * FROM ".$_SESSION['rego']['payroll_dbase']." WHERE month = '".$_SESSION['rego']['cur_month']."' AND paid = 'C' AND emp_id = '".$_REQUEST['emp_id']."'";
	}else{
		$sql = "SELECT * FROM ".$_SESSION['rego']['payroll_dbase']." WHERE month = '".$_SESSION['rego']['cur_month']."' AND paid = 'C' ORDER by LENGTH(emp_id) ASC, emp_id ASC";
		//$sql = "SELECT * FROM ".$_SESSION['rego']['payroll_dbase']." WHERE month = '".$_SESSION['rego']['cur_month']."' AND paid = 'C' AND emp_id = 'DEMO-001'";
	}

	if($res = $dbc->query($sql)){
		while($row = $res->fetch_assoc()){
			$emp_id = $row['emp_id'];
			$empinfo = getEmployeeInfo($cid, $row['emp_id']);
			
			$tmp = getEmployeeWorkedDays($empinfo['joining_date'], $empinfo['resign_date'], $workdays);
			$started = $tmp['started'];
			$resigned = $tmp['resigned'];
			$calc_method = $empinfo['calc_method'];
			
			//var_dump($last_day);
			//var_dump($row['paid_days']);
			$calendar_days = $row['paid_days'];
			if($row['paid_days'] == $last_day){
				$worked_days = $workdays;
			}else{
				$worked_days = $row['paid_days'];
			}
			if($empinfo['contract_type'] == 'day'){
				$worked_days = $row['paid_days'];
			}
			
			
			$day_rate = $empinfo['day_rate'];   // get dayrate from employee register /////////////////
			$hour_rate = $empinfo['hour_rate']; // get hourrate from employee register ////////////////

			$msg .= '<br><b>Employee = '.$empinfo['emp_id'].' - '.$empinfo['en_name'].'</b>';
			$msg .= '<br>&nbsp;-&nbsp;Wage Type = '.$empinfo['contract_type'];
			$msg .= '<br>&nbsp;-&nbsp;Calendar days = '.$calendar_days;
			$msg .= '<br>&nbsp;-&nbsp;Worked days = '.$worked_days;
			$msg .= '<br>&nbsp;-&nbsp;Day rate = '.number_format($day_rate,2);
			$msg .= '<br>&nbsp;-&nbsp;Hour rate = '.number_format($hour_rate,2);
			$msg .= '<br>&nbsp;-&nbsp;Current month = '.$cur_month;
			$msg .= '<br>&nbsp;-&nbsp;Remaining months = '.$remaining_months;

			if($empinfo['contract_type'] == 'month'){
				$basic_salary = $empinfo['base_salary']; 
			}else{
				$basic_salary = $empinfo['base_salary'] * 30; 
			}
		
			$this_salary = ($basic_salary / $workdays) * $worked_days; //$this_salary = $basic_salary; 
			
			$msg .= '<br>&nbsp;-&nbsp;Basic Salary from Employee register = <b>'.number_format($basic_salary,2).'</b>';
			$msg .= '<br>&nbsp;-&nbsp;Salary this month ('.$worked_days.' days) = <b>'.number_format($this_salary,2).'</b>';
	
	// PVF & SSO RATES //////////////////////////////////////////////////////////
			$pvf_rate = $empinfo['pvf_rate_emp'];
			$pvf_comp = $empinfo['pvf_rate_com'];
			
			$sso_rate_emp = $pr_settings['sso_rate']/100;
			$max_sso = $pr_settings['sso_max'];
			$min_sso = $pr_settings['sso_min'];
			
			$sso_rate_com = $pr_settings['sso_rate_com']/100;
			$max_sso_com = $pr_settings['sso_max_com'];
			$min_sso_com = $pr_settings['sso_min_com'];
			
	// OVERTIME //////////////////////////////////////////////////////////////////
			$ot1 = $hour_rate * $row['ot1h'];
			$ot15 = ($hour_rate*1.5) * $row['ot15h'];
			$ot2 = ($hour_rate*2) * $row['ot2h'];
			$ot3 = ($hour_rate*3) * $row['ot3h'];
			$oot = $row['ootb'];
			$tot_ot = round(($ot1 + $ot15 + $ot2 + $ot3 + $oot),2);
		
			$msg .= '<b><br><br>Overtime</b>';	
			$msg .= '<br>&nbsp;-&nbsp;OT 1 = '.number_format($ot1,2); 
			$msg .= '<br>&nbsp;-&nbsp;OT 1.5 = '.number_format($ot15,2);
			$msg .= '<br>&nbsp;-&nbsp;OT 2 = '.number_format($ot2,2); 
			$msg .= '<br>&nbsp;-&nbsp;OT 3 = '.number_format($ot3,2); 
			$msg .= '<br>&nbsp;-&nbsp;Other OT = '.number_format($oot,2); 
			$msg .= '<br>&nbsp;-&nbsp;Total OT = '.number_format($tot_ot,2);
			
	// OTHER INCOME - END CONTRACT ///////////////////////////////////////////////
			$other_income = $row['other_income'];
			$severance = $row['severance'];
			$remaining_salary = $row['remaining_salary'];
			$notice_payment = $row['notice_payment'];
			$paid_leave = $row['paid_leave'];
			if(isset($_REQUEST['prid'])){ // RECALCULATE EMPLOYEE ////////////////////////////////////////////////////////////////////////
				$other_income = $_REQUEST['other_income'];
				$severance = $_REQUEST['severance'];
				$remaining_salary = $_REQUEST['remaining_salary'];
				$notice_payment = $_REQUEST['notice_payment'];
				$paid_leave = $_REQUEST['paid_leave'];
			}
			$tot_other_var_income = $other_income + $severance + $remaining_salary + $notice_payment + $paid_leave;
		
			$msg .= '<b><br><br>Other Variable Income (End contract)</b>';	
			$msg .= '<br>&nbsp;-&nbsp;Other Income = '.number_format($other_income,2);
			$msg .= '<br>&nbsp;-&nbsp;Severance = '.number_format($severance,2);
			$msg .= '<br>&nbsp;-&nbsp;Remaining Salary = '.number_format($remaining_salary,2);
			$msg .= '<br>&nbsp;-&nbsp;Notice Payment = '.number_format($notice_payment,2);
			$msg .= '<br>&nbsp;-&nbsp;Paid Leave = '.number_format($paid_leave,2);
			$msg .= '<br>&nbsp;-&nbsp;Total Other Variable Income = '.number_format($tot_other_var_income,2);
	
	// FIXED & VARIABLE ALLOWANCES ///////////////////////////////////////////////
			for($i=1;$i<=10;$i++){
				if($worked_days < $workdays){ // fired or started employee
					$fix_allowances[$i] =(($empinfo['fix_allow_'.$i] / $workdays) * $worked_days);
				}else{
					$fix_allowances[$i] = $empinfo['fix_allow_'.$i];
				}
			}
			for($i=1;$i<=10;$i++){
				$var_allowances[$i] = $row['var_allow_'.$i];
			}

			$tot_fix_all = array_sum($fix_allowances);
			$tot_var_all = array_sum($var_allowances);
			$tot_fix_tax_all = 0;
			$tot_var_tax_all = 0;
			$tot_fix_non_all = 0;
			$tot_var_non_all = 0;
			foreach($fix_allowances as $k => $v){
				if(isset($tax_allow['fix'][$k]) && $tax_allow['fix'][$k]['tax'] == 'N'){
					$tot_fix_non_all += $v;
				}else{
					$tot_fix_tax_all += $v;
				}
			}
			foreach($var_allowances as $k => $v){
				if(isset($tax_allow['var'][$k]) && $tax_allow['var'][$k]['tax'] == 'N'){
					$tot_var_non_all += $v;
				}else{
					$tot_var_tax_all += $v;
				}
			}
			$tot_tax_all = $tot_fix_tax_all + $tot_var_tax_all;
			$tot_non_all = $tot_fix_non_all + $tot_var_non_all;
		
			$msg .= '<b><br><br>Fixed Allowances</b>';	
			$msg .= '<br>&nbsp;-&nbsp;Total fixed allowances = '.number_format($tot_fix_all,2);
			$msg .= '<br>&nbsp;-&nbsp;Total fixed taxable allowances = '.number_format($tot_fix_tax_all,2);
			$msg .= '<br>&nbsp;-&nbsp;Total fixed non-taxable allowances = '.number_format($tot_fix_non_all,2);
		
			$msg .= '<b><br><br>Variable Allowances</b>';	
			$msg .= '<br>&nbsp;-&nbsp;Total variable allowances = '.number_format($tot_var_all,2);
			$msg .= '<br>&nbsp;-&nbsp;Total variable taxable allowances = '.number_format($tot_var_tax_all,2);
			$msg .= '<br>&nbsp;-&nbsp;Total variable non-taxable allowances = '.number_format($tot_var_non_all,2);
	
	// ABSENCE - LEAVE WOP - LATE EARLY //////////////////////////////////////////
			$absence_b = ($row['absence'] / $dayhours) * $day_rate;
			$leave_wop_b = ($row['leave_wop'] / $dayhours) * $day_rate;
			$late_early_b = ($row['late_early'] / $dayhours) * $day_rate;
			$absence_b = round($absence_b,2);
			$leave_wop_b = round($leave_wop_b,2);
			$late_early_b = round($late_early_b,2);
			$tot_absence = $absence_b + $leave_wop_b + $late_early_b;
			$msg .= '<b><br><br>Absence</b>';		
			$msg .= '<br>&nbsp;-&nbsp;Absence = '.number_format($absence_b,2);
			$msg .= '<br>&nbsp;-&nbsp;Leave WOP = '.number_format($leave_wop_b,2);
			$msg .= '<br>&nbsp;-&nbsp;Late Early = '.number_format($late_early_b,2);
			$msg .= '<br>&nbsp;-&nbsp;Total Absence = '.number_format($tot_absence,2);

	// FIXED & VARIABLE DEDUCTIONS ///////////////////////////////////////////////
			$fix_deduct = array();
			$var_deduct = array();
			for($i=1;$i<=5;$i++){
				if($worked_days < $workdays){ // fired or started employee
					$fix_deduct[$i] =(($empinfo['fix_deduct_'.$i] / $workdays) * $worked_days);
				}else{
					$fix_deduct[$i] = $empinfo['fix_deduct_'.$i];
				}
			}
			for($i=1;$i<=5;$i++){
				$var_deduct[$i] = $row['var_deduct_'.$i];
			}
			$fix_deduct_before = 0;
			$fix_deduct_after = 0;
			
			foreach($fix_deduct as $k => $v){
				if(isset($fix_deductions[$k]) && $fix_deductions[$k]['tax'] == '0'){
					$fix_deduct_before += $v;
				}else{
					$fix_deduct_after += $v;
				}
			}
			
			$var_deduct_before = 0;
			$var_deduct_after = 0;
			foreach($var_deduct as $k => $v){
				if(isset($var_deductions[$k]) && $var_deductions[$k]['tax'] == '0'){
					$var_deduct_before += $v;
				}else{
					$var_deduct_after += $v;
				}
			}
			//$var_deduct_before +=  $tot_absence;
			$tot_deduct_before = $fix_deduct_before + $var_deduct_before;
			$tot_deduct_after = $fix_deduct_after + $var_deduct_after;
			
			$msg .= '<br><br><b>Fixed & Variable deductions</b>';	
			$msg .= '<br>&nbsp;-&nbsp;Total fixed deduct before tax = '.number_format($fix_deduct_before,2);
			$msg .= '<br>&nbsp;-&nbsp;Total fixed deduct after tax = '.number_format($fix_deduct_after,2);
			$msg .= '<br>&nbsp;-&nbsp;Total variable deduct before tax = '.number_format($var_deduct_before,2);
			$msg .= '<br>&nbsp;-&nbsp;Total variable deduct after tax = '.number_format($var_deduct_after,2);
			
	// PVF MONTH /////////////////////////////////////////////////////////////////
			$pvf_employee = 0; 
			$pvf_employer = 0;
			$psf_employee = 0; 
			$psf_employer = 0;
			if($empinfo['calc_pvf'] == 'pvf'){
				$pvf_employee = ($this_salary * $pvf_rate)/100; 
				$pvf_employer = ($this_salary * $pvf_comp)/100;
				$msg .= '<b><br><br>PVF this month</b>';	
				$msg .= '<br>&nbsp;-&nbsp;PVF employee ('.$pvf_rate.'%) = '.number_format($pvf_employee,2);
				$msg .= '<br>&nbsp;-&nbsp;PVF employer ('.$pvf_comp.'%) = '.number_format($pvf_employer,2);
			}  
			if($empinfo['calc_pvf'] == 'psf'){
				$psf_employee = ($this_salary * $pvf_rate)/100; 
				$psf_employer = ($this_salary * $pvf_comp)/100;
				$msg .= '<b><br><br>PSF this month</b>';	
				$msg .= '<br>&nbsp;-&nbsp;PSF employee ('.$pvf_rate.'%) = '.number_format($psf_employee,2);
				$msg .= '<br>&nbsp;-&nbsp;PSF employer ('.$pvf_comp.'%) = '.number_format($psf_employer,2);
			}  
			
	// SSO MONTH /////////////////////////////////////////////////////////////////
			$sso_emp = 0;
			$sso_com = 0;
			if($empinfo['calc_sso'] == 'Y'){
				$sso_emp = ($this_salary + $tot_fix_all) * $sso_rate_emp;
				$sso_emp = ($sso_emp > $max_sso ? $max_sso : $sso_emp);
				$sso_emp = ($sso_emp < $min_sso ? $min_sso : $sso_emp);
				$sso_emp = round($sso_emp);
				$sso_com = ($this_salary + $tot_fix_all) * $sso_rate_com;
				$sso_com = ($sso_com > $max_sso_com ? $max_sso_com : $sso_com);
				$sso_com = ($sso_com < $min_sso_com ? $min_sso_com : $sso_com);
				$sso_com = round($sso_com);
			}
			$msg .= '<b><br><br>SSO this month</b>';	
			$msg .= '<br>&nbsp;-&nbsp;SSO employee = ('.$pr_settings['sso_rate'].'%) '.number_format($sso_emp,2);
			$msg .= '<br>&nbsp;-&nbsp;SSO company = ('.$pr_settings['sso_rate_com'].'%) '.number_format($sso_com,2);
	
	// PREVIOUS INCOME ///////////////////////////////////////////////////////////
			$prev_data = 0;
			$prev_basic = 0;
			$prev_salary = 0;
			$prev_fix_income = 0;
			$prev_var_income = 0;
			$prev_ytd_income = 0;
			$prev_fixallow = 0;
			$prev_pvf = 0;
			$prev_sso = 0;
			$prev_tax = 0;
			$prev_irregular = 0;
			if($cur_month > 1){
				if($result = $dbc->query("SELECT basic_salary, salary, tot_fix_income, tot_var_income, ytd_income, total_fix_tax_allow, pvf_employee, social, tax, prev_tax_income FROM ".$_SESSION['rego']['payroll_dbase']." WHERE emp_id = '".$emp_id."' AND month < '".$cur_month."'")){
					while($prev = $result->fetch_object()){
						$prev_basic += $prev->basic_salary;
						$prev_salary += $prev->salary;
						$prev_fix_income += $prev->tot_fix_income;
						$prev_var_income += $prev->tot_var_income;
						$prev_ytd_income += $prev->ytd_income;
						$prev_fixallow += $prev->total_fix_tax_allow;
						$prev_pvf += $prev->pvf_employee;
						$prev_sso += $prev->social;
						$prev_tax += $prev->tax;
					}
				}else{
					var_dump(mysqli_error($dbc));
				}
			}
			$msg .= '<b><br><br>Previous Income</b>';	
			$msg .= '<br>&nbsp;-&nbsp;Previous Basic Salary = '.number_format($prev_basic,2);
			$msg .= '<br>&nbsp;-&nbsp;Previous Salary = '.number_format($prev_salary,2);
			$msg .= '<br>&nbsp;-&nbsp;Previous Fix Income = '.number_format($prev_fix_income,2);
			$msg .= '<br>&nbsp;-&nbsp;Previous YTD Income = '.number_format($prev_ytd_income,2);
			$msg .= '<br>&nbsp;-&nbsp;Previous Fix Taxable allowances = '.number_format($prev_fixallow,2);
			$msg .= '<br>&nbsp;-&nbsp;Previous PVF = '.number_format($prev_pvf,2);
			$msg .= '<br>&nbsp;-&nbsp;Previous SSO = '.number_format($prev_sso,2);
			$msg .= '<br>&nbsp;-&nbsp;Previous Tax = '.number_format($prev_tax,2);

	// BASIC CALCULATIONS ////////////////////////////////////////////////////////
			$fixed_income_this_month = $this_salary + $tot_fix_tax_all - $fix_deduct_before;
			$variable_income_this_month = $tot_ot + $tot_var_tax_all + $tot_other_var_income - $var_deduct_before;
			$gross_income_month = $fixed_income_this_month + $variable_income_this_month + $tot_fix_non_all + $tot_var_non_all - $tot_absence;
			
			//$fixed_month = $fixed_income_this_month;// + $tot_fix_tax_all;
			$fixed_income = $fixed_income_this_month * 12;//($remaining_months+1);
			if($cur_month == 12){
				$fixed_income = $prev_fix_income + $fixed_income_this_month;
			}
			
			if($cur_month == 1){
				$fixed_actual = $fixed_income_this_month * 12;
			}else{
				$fixed_actual = $fixed_income_this_month;
				$fixed_actual += ($fixed_income_this_month * $remaining_months);
				$fixed_actual += $prev_fix_income;
			}
			
			$ytd_year_income = $gross_income_month + $prev_ytd_income;
		
			$msg .= '<br><br><b>Base calculations</b>';	
			$msg .= '<br>&nbsp;-&nbsp;Fixed income this month = '.number_format($fixed_income_this_month,2);
			$msg .= '<br>&nbsp;-&nbsp;Variable income this month = '.number_format($variable_income_this_month,2);
			$msg .= '<br>&nbsp;-&nbsp;Gross income this month = '.number_format($gross_income_month,2);

			$msg .= '<br><br>&nbsp;-&nbsp;Fixed income x12 = '.number_format($fixed_income,2);
			$msg .= '<br>&nbsp;-&nbsp;Fixed actual income = '.number_format($fixed_actual,2);
			$msg .= '<br>&nbsp;-&nbsp;Variable previous income = '.number_format($prev_var_income,2);
			$msg .= '<br>&nbsp;-&nbsp;Variable income this month = '.number_format($variable_income_this_month,2);
			$msg .= '<br>&nbsp;-&nbsp;Income YTD = '.number_format($ytd_year_income,2);
	
	// DEDUCTIONS BEFORE TAX /////////////////////////////////////////////////////
			$sso_year = 0;
			$pvf_year = 0;
			
			if($calc_method == 'ytd'){
				$standard_deduction = $ytd_year_income * ($tax_settings['stdeduct_per']/100);
				$personal_care = $ytd_year_income * 0.4;
				$sso_year = $sso_emp + $prev_sso;
				$pvf_year = $pvf_employee + $prev_pvf;
			}else{
				$standard_deduction = $fixed_actual * ($tax_settings['stdeduct_per']/100);
				$personal_care = $fixed_actual * 0.4;
				if($empinfo['calc_sso'] == 'Y'){
					$sso_year += $prev_sso;
					$sso_year += $sso_emp;
					if(!$resigned){
						$sso_year += $sso_emp * ($remaining_months);
					}
				}	
				if($empinfo['calc_pvf'] == 'pvf'){
					$pvf_year = $prev_pvf;
					$pvf_year += $pvf_employee;
					if(!$resigned){
						$pvf_year += $pvf_employee * ($remaining_months);
					}
				}
			}

			$standard_deduction = ($standard_deduction > $tax_settings['standard_deduction'] ? $tax_settings['standard_deduction'] : $standard_deduction);
			$personal_care = ($personal_care > 60000 ? 60000 : $personal_care);
			
			$tax_allowances = $empinfo['emp_tax_deductions'];
			$total_tax_deductions = $standard_deduction + $personal_care + $sso_year + $pvf_year + $tax_allowances;// + $tot_deduct_before;
			
			$msg .= '<br><br><b>Deductions before Tax</b>';
			$msg .= '<br>&nbsp;-&nbsp;Standard Taxdeduction = '.number_format($standard_deduction,2);
			$msg .= '<br>&nbsp;-&nbsp;Personal care = '.number_format($personal_care,2);
			$msg .= '<br>&nbsp;-&nbsp;Social Security Fund = '.number_format($sso_year,2);
			$msg .= '<br>&nbsp;-&nbsp;Provident Fund = '.number_format($pvf_year,2);
			$msg .= '<br>&nbsp;-&nbsp;Tax allowances employee = '.number_format($tax_allowances,2);
			$msg .= '<br>&nbsp;-&nbsp;Total Tax deductions = '.number_format($total_tax_deductions,2);
	
	// TAXABLE YEAR INCOME AFTER DEDUCTIONS //////////////////////////////////////
			$cam_fix = $fixed_income - $total_tax_deductions;
			$cam_fix_previous = $cam_fix + $prev_var_income;
			$cam_fix_variable = $cam_fix_previous + $variable_income_this_month;
			
			$acm_fix = $fixed_actual - $total_tax_deductions;
			$acm_fix_previous = $acm_fix + $prev_var_income;
			$acm_fix_variable = $acm_fix_previous + $variable_income_this_month;
			
			$ytd_income = $ytd_year_income - $total_tax_deductions;

			$msg .= '<b><br><br>Taxable year income after deductions</b>';	
			$msg .= '<br>&nbsp;-&nbsp;CAM Fix = '.number_format($cam_fix,2);
			$msg .= '<br>&nbsp;-&nbsp;CAM Fix + previous = '.number_format($cam_fix_previous,2);
			$msg .= '<br>&nbsp;-&nbsp;CAM Fix + variable = '.number_format($cam_fix_variable,2);
			
			$msg .= '<br>&nbsp;-&nbsp;ACM Fix = '.number_format($acm_fix,2);
			$msg .= '<br>&nbsp;-&nbsp;ACM Fix + previous = '.number_format($acm_fix_previous,2);
			$msg .= '<br>&nbsp;-&nbsp;ACM Fix + variable = '.number_format($acm_fix_variable,2);
			
			$msg .= '<br>&nbsp;-&nbsp;Total YTD = '.number_format($ytd_income,2);
	
	// TAX CALCULATIONS WHOLE YEAR ///////////////////////////////////////////////
			$cam_tax = calculateAnualTax($cam_fix);
			$cam_tax_previous = calculateAnualTax($cam_fix_previous);
			$cam_tax_variable = calculateAnualTax($cam_fix_variable);
		
			$acm_tax = calculateAnualTax($acm_fix);
			$acm_tax_previous = calculateAnualTax($acm_fix_previous);
			$acm_tax_variable = calculateAnualTax($acm_fix_variable);

			$tax_ytd = calculateAnualTax($ytd_income);
		
			$msg .= '<b><br><br>Tax calculations whole year</b>';	
			$msg .= '<br>&nbsp;-&nbsp;CAM Fix = '.number_format($cam_tax,2);
			$msg .= '<br>&nbsp;-&nbsp;CAM Fix + previous = '.number_format($cam_tax_previous,2);
			$msg .= '<br>&nbsp;-&nbsp;CAM Fix + variable = '.number_format($cam_tax_variable,2);
			
			$msg .= '<br>&nbsp;-&nbsp;ACM Fix = '.number_format($acm_tax,2);
			$msg .= '<br>&nbsp;-&nbsp;ACM Fix + previous = '.number_format($acm_tax_previous,2);
			$msg .= '<br>&nbsp;-&nbsp;ACM Fix + variable = '.number_format($acm_tax_variable,2);
			
			$msg .= '<br>&nbsp;-&nbsp;Tax YTD = '.number_format($tax_ytd,2);

	// TAX SUMMARY ///////////////////////////////////////////////////////////////
			$total_tax_year = 0;
			$tax_previous = 0;
			$tax_remaining = 0;
			$tax_fix_month = 0;
			$tax_var_month = 0;
			$tax_difference = 0;
			$tax_this_month = 0;
			$tax_next_month = 0;
			$modify_tax = $empinfo['modify_tax'];
			
			if($empinfo['calc_tax']){
				//$tot_modified_tax = getTotModifiedTax($row['emp_id']);
				//var_dump($tot_modified_tax);
				
				if($calc_method == 'cam'){
					$gross_year = $fixed_income + $prev_var_income + $variable_income_this_month;
					$taxable_year = $cam_fix_variable;
					
					$total_tax_year = $cam_tax_variable;
					$tax_previous = $prev_tax;
					$tax_remaining = $total_tax_year - $tax_previous;
					$tax_fix_month = $cam_tax / 12;
					$tax_var_month = $cam_tax_variable - $cam_tax_previous;
					
					$tax_difference = 0;
					if($cur_month > 1){
						$diff1 = $cam_tax_previous - $cam_tax - $tax_previous;
						$diff2 = $tax_previous - ($tax_fix_month * ($cur_month-1));
						$diff3 = $cam_tax_previous - $cam_tax - $tax_previous + ($tax_fix_month * ($cur_month-1));
						if($diff1 > 0 && $diff2 > 0){$tax_difference = $diff3;}
					}
					if($cur_month == 12){
						//$tot_modified_tax = getTotModifiedTax($row['emp_id']);
						//var_dump($tot_modified_tax);
						$tax_this_month = $tax_remaining;// + $tot_modified_tax;
					}else{
						$tmp = $tax_remaining - $tax_fix_month - $tax_var_month - $tax_difference;
						if($tmp < 0){
							$tax_this_month = $tax_remaining;	
						}else{
							$tax_this_month = $tax_fix_month + $tax_var_month + $tax_difference;
						}
					}
					
					if($remaining_months > 0){
						$next = ($total_tax_year - $tax_previous - $tax_this_month) / $remaining_months;
					}else{
						$next = $total_tax_year - $tax_previous - $tax_this_month;
					}
					if($next > $tax_fix_month){
						$tax_next_month = $tax_fix_month;
					}else{
						$tax_next_month = $next;
					}
				}
				
				if($calc_method == 'acm'){
					$gross_year = $fixed_actual + $prev_var_income + $variable_income_this_month;
					$taxable_year = $acm_fix_variable;
					
					$total_tax_year = $acm_tax_variable;
					$tax_previous = $prev_tax;
					$tax_remaining = $acm_tax_variable - $tax_previous;
					$tax_var_month = $acm_tax_variable - $acm_tax_previous ;
					$tax_fix_month = ($tax_remaining - $tax_var_month) / ($remaining_months+1);
					if($tax_fix_month < 0){$tax_fix_month = 0;}

					$tmp = $tax_remaining - $tax_fix_month - $tax_var_month;
					if($tmp < 0){
						$tax_this_month = $tax_remaining;	
					}else{
						$tax_this_month = $tax_fix_month + $tax_var_month;
					}
					
					if($remaining_months > 0){
						$next = ($total_tax_year - $tax_previous - $tax_this_month) / $remaining_months;
					}else{
						$next = $total_tax_year - $tax_previous - $tax_this_month;
					}
					if($next > $tax_fix_month){
						$tax_next_month = $tax_fix_month;
					}else{
						$tax_next_month = $next;
					}
				}
				
				if($calc_method == 'ytd'){
					$gross_year = $ytd_year_income;
					$taxable_year = $ytd_income;
					
		 			$total_tax_year = $tax_ytd;
					$tax_previous = $prev_tax;
					$tax_remaining = $tax_ytd - $prev_tax;
					$tax_this_month = $tax_remaining;
					$tax_next_month = 0;
				}
				//if($cur_month < 12){
					//$tax = $tax_this_month;
				$tax_month = $tax_this_month + $modify_tax;
					//$modify_tax = $tax_this_month + $empinfo['modify_tax'];
				//}
				
				if($tax_this_month < 0){$tax_this_month = 0;}
				if($tax_month < 0){$tax_month = 0;}
				if($tax_next_month < 0){$tax_next_month = 0;}
			}else{
				$tax_this_month = 0;
				$tax_month = 0;
				$tax_next_month = 0;
			}
		
		$msg .= '<b><br><br>Tax Summary ('.strtoupper($calc_method).')</b>';	
			$msg .= '<br>&nbsp;-&nbsp;Total Tax Year = '.number_format($total_tax_year,2);
			$msg .= '<br>&nbsp;-&nbsp;Tax Previous = '.number_format($tax_previous,2);
			$msg .= '<br>&nbsp;-&nbsp;Tax Remaining = '.number_format($tax_remaining,2);
			$msg .= '<br>&nbsp;-&nbsp;Tax Fix Month = '.number_format($tax_fix_month,2);
			$msg .= '<br>&nbsp;-&nbsp;Tax Var This Month = '.number_format($tax_var_month,2);
			$msg .= '<br>&nbsp;-&nbsp;Tax Difference = '.number_format($tax_difference,2);
			
			$msg .= '<br><br>&nbsp;-&nbsp;Tax This Month = '.number_format($tax_this_month,2);
			$msg .= '<br>&nbsp;-&nbsp;Tax Next Month = '.number_format($tax_next_month,2);
	
	// PAYROLL RESULT ////////////////////////////////////////////////////////////
			$tot_deductions = $pvf_employee + $psf_employee + $sso_emp + $tot_deduct_after + $tax_month + $row['legal_deductions'] + $row['advance'];
			//$gross_month = $this_salary + $tot_ot + $tot_tax_all + $tot_non_all + $tot_other_var_income;
			$net_month = $gross_income_month - $tot_deductions;
			
			$msg .= '<b><br><br>Gross & Net income this month</b>';	
			$msg .= '<br>&nbsp;-&nbsp;Gross this month = '.number_format($gross_income_month,2);
			$msg .= '<br>&nbsp;-&nbsp;Total deduct = '.number_format($tot_deductions,2);
			$msg .= '<br>&nbsp;-&nbsp;Advance = '.number_format($row['advance'],2);
			$msg .= '<br>&nbsp;-&nbsp;Net this month = '.number_format($net_month,2);

			$msg .= '<br><br>*********************************************************';
			
		//var_dump($gross_year);	
	// PAYROLL OVERVIEW ////////////////////////////////////////////////////////////////
			$taxable['calc_method'] = $calc_method;
			$taxable['emp_id'] = $row['emp_id'];
			$taxable['emp_name_en'] = $row['emp_name_en'];
			$taxable['emp_name_th'] = $row['emp_name_th'];
			
			$taxable['basic_salary'] = $basic_salary;
			$taxable['salary'] = $this_salary;
			
			$taxable['fix_income'] = $fixed_income_this_month;
			$taxable['var_income'] = $variable_income_this_month;
			$taxable['gross_income'] = $gross_income_month;
			$taxable['gross_year'] = $gross_year;
			$taxable['taxable_year'] = $taxable_year;
			$taxable['tax_year'] = $total_tax_year;
			$taxable['tax_modify'] = $modify_tax;
			$taxable['tax_this_month'] = $tax_this_month;
			$taxable['tax_month'] = $tax_month;
			$taxable['tax_next_month'] = $tax_next_month;
			
			$taxable['standard_deduction'] = $standard_deduction;
			$taxable['personal_allowance'] = $personal_care;
			$taxable['sso_year'] = $sso_year;
			$taxable['pvf_year'] = $pvf_year;
			$taxable['spouse_allowance'] = $empinfo['tax_allow_spouse'];
			$taxable['parents_allowance'] = $empinfo['tax_allow_parents'];
			$taxable['parents_inlaw_allowance'] = $empinfo['tax_allow_parents_inlaw'];
			$taxable['disabled_allowance'] = $empinfo['tax_allow_disabled_person'];
			$taxable['child_allowance'] = $empinfo['tax_allow_child_bio'];
			$taxable['child_allowance_2018'] = $empinfo['tax_allow_child_bio_2018'];
			$taxable['child_allowance_adopted'] = $empinfo['tax_allow_child_adopted'];
			$taxable['child_birth_bonus'] = $empinfo['tax_allow_child_birth'];
			$taxable['own_health_insurance'] = $empinfo['tax_allow_own_health'];
			$taxable['own_life_insurance'] = $empinfo['tax_allow_own_life_insurance'];
			$taxable['health_insurance_parent'] = $empinfo['tax_allow_health_parents'];
			$taxable['life_insurance_spouse'] = $empinfo['tax_allow_life_insurance_spouse'];
			$taxable['pension_fund_allowance'] = $empinfo['tax_allow_pension_fund'];
			$taxable['nsf_allowance'] = $empinfo['tax_allow_nsf'];
			$taxable['rmf_allowance'] = $empinfo['tax_allow_rmf'];
			$taxable['ltf_deduction'] = $empinfo['tax_allow_ltf'];
			$taxable['home_loan_interest'] = $empinfo['tax_allow_home_loan_interest'];
			$taxable['donation_charity'] = $empinfo['tax_allow_donation_charity'];
			$taxable['donation_flood'] = $empinfo['tax_allow_donation_flood'];
			$taxable['donation_education'] = $empinfo['tax_allow_donation_education'];
			$taxable['exemp_disabled_under'] = $empinfo['tax_allow_exemp_disabled_under'];
			$taxable['exemp_payer_older'] = $empinfo['tax_allow_exemp_payer_older'];
			$taxable['first_home_allowance'] = $empinfo['tax_allow_first_home'];
			$taxable['year_end_shop_allowance'] = $empinfo['tax_allow_year_end_shopping'];
			$taxable['domestic_tour_allowance'] = $empinfo['tax_allow_domestic_tour'];
			$taxable['other_allowance'] = $empinfo['tax_allow_other'];
			$taxable['tax_deductions'] = $total_tax_deductions;
			//var_dump($taxable); //exit;
	
	// UPDATE DATABASE /////////////////////////////////////////////////////////////////
			$sql = "UPDATE ".$_SESSION['rego']['payroll_dbase']." SET 
				basic_salary = '".$basic_salary."',
				salary = '".$this_salary."',
				paid_days = '".$calendar_days."',
				
				ot1b = '".$ot1."',
				ot15b = '".$ot15."',
				ot2b = '".$ot2."',
				ot3b = '".$ot3."',
				ootb = '".$oot."',
				total_otb = '".$tot_ot."',
		
				absence_b = '".$absence_b."',
				leave_wop_b = '".$leave_wop_b."',
				late_early_b = '".$late_early_b."',
				tot_absence = '".$tot_absence."',";
			foreach($fix_allowances as $k=>$v){
				$sql .= "fix_allow_".$k." = '".$v."',";
			}	
			foreach($var_allowances as $k=>$v){
				$sql .= "var_allow_".$k." = '".$v."',";
			}	
			$sql .= "		
				total_fix_allow = '".$tot_fix_all."',
				total_fix_tax_allow = '".$tot_fix_tax_all."',
				total_fix_non_allow = '".$tot_fix_non_all."',
				total_var_allow = '".$tot_var_all."',
				total_var_tax_allow = '".$tot_var_tax_all."',
				total_var_non_allow = '".$tot_var_non_all."',
				total_tax_allow = '".$tot_tax_all."',
				total_non_allow = '".$tot_non_all."',
				
				pvf_employee = '".$pvf_employee."',
				pvf_employer = '".$pvf_employer."',
				
				psf_employee = '".$psf_employee."',
				psf_employer = '".$psf_employer."',
				
				social = '".$sso_emp."',
				social_com = '".$sso_com."',
				
				modify_tax = '".$modify_tax."',
				tax = '".$tax_this_month."',
				tax_month = '".$tax_month."',
				tax_next = '".$tax_next_month."',
				tax_year = '".$total_tax_year."',
				
				fix_deduct_before = '".$fix_deduct_before."',
				fix_deduct_after = '".$fix_deduct_after."',
				var_deduct_before = '".$var_deduct_before."',
				var_deduct_after = '".$var_deduct_after."',
				
				tot_deduct_before = '".$tot_deduct_before."',
				tot_deduct_after = '".$tot_deduct_after."',
				
				tot_deductions = '".$tot_deductions."',
				
				tot_fix_income = '".$fixed_income_this_month."',
				tot_var_income = '".$variable_income_this_month."',
				ytd_income = '".$gross_income_month."',
				
				gross_income = '".$gross_income_month."',
				net_income = '".$net_month."',
				
				paid = 'C',
				tax_calculation = '".$dbc->real_escape_string(serialize($taxable))."'
				WHERE emp_id = '".$emp_id."' AND month = '".$_SESSION['rego']['cur_month']."'";
				//echo $sql;
				
				if($dbc->query($sql)){
	
					//$msg = 'success';
					
					$period = $_SESSION['rego']['cur_year'].'_'.$_SESSION['rego']['cur_month'];
					$dbc->query("UPDATE ".$cid."_payroll_months SET status = '0' WHERE month = '".$period."'");
					
					$dbc->query("UPDATE ".$_SESSION['rego']['emp_dbase']." SET 
					tax_standard_deduction = '".$standard_deduction."', 
					tax_personal_allowance = '".$personal_care."', 
					tax_allow_pvf = '".$pvf_year."', 
					tax_allow_sso = '".$sso_year."' 
					WHERE emp_id = '".$row['emp_id']."'");
				}else{
					$msg = mysqli_error($dbc);
				}
			
		} // END WHILE ///////////////////////////////////
	}else{
		$msg = mysqli_error($dbc);
	} // END $dbc->query($sql) //////////////////////////
	
	if(!isset($_REQUEST['prid'])){ // RECALCULATE EMPLOYEE ///////////////////////////////////////////////////	
		echo $msg;
	}else{
		echo 'Changes saved';
	}	
	//unset($_SESSION['rego']['ssf_pages']);
	//unset($_SESSION['rego']['pnd1_pages']);
	//ob_clean();
	echo $msg;

?>