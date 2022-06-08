<?php

	$taxrules = unserialize($rego_settings['taxrules']);	
	$tax_settings = unserialize($rego_settings['tax_settings']);
	//var_dump($pr_settings);
	$empinfo = getEmployeeInfo($cid, $_REQUEST['id']);
	$getAllmonthSSo = getAllmonthSSo();
	
	$basic_salary = $empinfo['base_salary'];
	$fix_allow = 0;
	for($i=1;$i<=10;$i++){
		$fix_allow += $empinfo['fix_allow_'.$i];
	}
		
	$sso_rate_emp = $pr_settings['sso_rate_emp']/100;
	$min_sso = $pr_settings['sso_min_emp'];
	$max_sso = $pr_settings['sso_max_emp'];
	/*if($_SESSION['rego']['cur_month'] <= 2 && $_SESSION['rego']['cur_year'] == 2020){
		$sso_rate = 0.05;
		$max_sso = 750;
		$min_sso = 83;
	}*/

	$pvf_rate = $empinfo['pvf_rate_emp']/100;
	$calc_method = $empinfo['calc_method'];

	if($_SESSION['rego']['login_link'] == 'mob'){
		$curyear = $_SESSION['rego']['mob_year'];
	}else{
		$curyear = $_SESSION['rego']['cur_year'];
	}
	
	$data = array();
	$first = 0;
	if($res = $dbc->query("SELECT month FROM ".$cid."_payroll_".$curyear." WHERE emp_id = '".$_REQUEST['id']."' ORDER by month ASC LIMIT 1")){
		if($row = $res->fetch_assoc()){
			$first = $row['month'];
		}
	}
	//var_dump($first); //exit;
	if($first > 1){
		for($i=1;$i<$first;$i++){
			$data[$i] = array(
				'date'=>$short_months[$i].' '.$curyear,
				'paid'=>'-',
				'salary'=>'-',
				'allow'=>'-',
				'ot'=>'-',
				'other_income'=>'-',
				'deduct_before'=>'-',
				'gross'=>'-',
				'deduct_after'=>'-',
				'pvf'=>'-',
				'sso'=>'-',
				'tax_month'=>'-',
				'deductions'=>'-',
				'net'=>'-',
				'class'=>'igrey');
		}
	}
	//var_dump($data); //exit;
	
	$tot_salary = 0;
	$tot_fix_allow = 0;
	$tot_ot = 0;
	$tot_var_income = 0;
	$tot_deduct_before = 0;
	$tot_gross = 0;
	$tot_deduct_after = 0;
	$tot_pvf = 0;
	$tot_sso = 0;
	$tot_tax = 0;
	$tot_tax_month = 0;
	$tot_deductions = 0;
	$tot_net = 0;
	$tax_year = 0;
	$tax_return = 0;
	$tax_by_company = 0;
	$tax_calculation = 0;
	
	$nr = 1;


	if($_SESSION['rego']['login_link'] == 'mob'){
		$condition = "AND paid IN ('H', 'Y')";
	}else{
		$condition = "";
	}
	
	if($res = $dbc->query("SELECT * FROM ".$cid."_payroll_".$curyear." WHERE emp_id = '".$_REQUEST['id']."' AND month <= '".$_SESSION['rego']['cur_month']."' ".$condition." ORDER by month ASC")){
		while($row = $res->fetch_assoc()){

			/*if($row['calc_base'] == 'net'){
				$DeductTaxbyCom = $row['tax_by_company'];
			}else{
				$DeductTaxbyCom = 0;
			}*/
			
			$data[(int)$row['month']] = array(
				'date'=>$short_months[(int)$row['month']].' '.$curyear,
				'paid'=>$row['paid'],
				'salary'=>(float)$row['salary'],
				'allow'=>(float)$row['total_fix_allow'],// + (float)$row['total_var_allow'],
				'ot'=>(float)$row['total_otb'],
				'other_income'=>(float)$row['other_income']+(float)$row['severance']+(float)$row['total_var_allow']+(float)$row['remaining_salary']+(float)$row['notice_payment']+(float)$row['paid_leave']+(float)$row['tax_by_company']+(float)$row['sso_by_company'],
				'deduct_before'=>(float)$row['tot_deduct_before'],
				'gross'=>(float)$row['gross_income'],
				'deduct_after'=>(float)$row['tot_deduct_after'],
				'pvf'=>(float)$row['pvf_employee'] + (float)$row['psf_employee'],
				'sso'=>(float)$row['social'],
				'tax_month'=>round((float)$row['tax_month'],2),
				'deductions'=>(float)$row['tot_deduct_before'] + (float)$row['tot_deduct_after'], //(float)$row['tot_deductions'],
				'net'=>(float)$row['net_income'],
				'class'=>'');
			
			$tax_year = $row['tax_year'];
			$tax_next_month = $row['tax_next'];
			$next_month = $row['tax_next'];
			
			$tot_salary += $row['salary'];
			$tot_fix_allow += $row['total_fix_allow'];
			$tot_ot += $row['total_otb'];
			$tot_var_income += ($row['other_income']+$row['severance']+$row['total_var_allow']+$row['remaining_salary']+$row['notice_payment']+$row['paid_leave'])+$row['tax_by_company']+$row['sso_by_company'];
			$tot_deduct_before += $row['tot_deduct_before'];
			$tot_gross += $row['gross_income'];
			$tot_deduct_after += $row['tot_deduct_after'];
			$tot_pvf += $row['pvf_employee'];
			$tot_sso += $row['social'];
			$tot_tax += $row['tax'];
			$tot_tax_month += $row['tax_month'];
			$tot_deductions += $row['tot_deduct_before'] + $row['tot_deduct_after']; //$row['tot_deductions'];
			$tot_net += $row['net_income'];
			//$tot_modify += $row['modify_tax'];
			
			//var_dump($tot_modify);
			end($data);
			$nr = key($data)+1;
			
			$basic_salary = $row['basic_salary']; 
			$fix_allow = $row['total_fix_allow'];
			$pvf_employee = $row['pvf_employee']; 
			$sso_emp = $row['social'];
			$tax_by_company = $row['tax_by_company'];
			$tax_calculation = unserialize($row['tax_calculation']);
			
		}
	}
	//var_dump($tot_tax);
	//var_dump($tax_year);
	$tax_return = $tot_tax - $tax_year;
	//var_dump($tax_return);
	
	if($data){
		for($i=$nr;$i<=12;$i++){
			/*$pvf_employee = 0; 
			if($pr_settings['pvf_applied'] == 'Y' && $empinfo['calc_pvf'] != 'non'){
				$pvf_employee = ($basic_salary * $pvf_rate); 
			}*/  
			/*$sso_emp = 0;
			if($empinfo['calc_sso'] == 'Y'){
				$sso_emp = ($basic_salary + $fix_allow) * $sso_rate_emp;
				$sso_emp = ($sso_emp > $max_sso ? $max_sso : $sso_emp);
				$sso_emp = ($sso_emp < $min_sso ? $min_sso : $sso_emp);
				$sso_emp = round($sso_emp);
			}*/
			
			if($i == 12){
				//$tax_next_month = $tax_year - $tot_tax + $tot_modify;
			}
		
			if($calc_method == 'ytd'){
				$data[$i] = array(
					'date'=>$short_months[$i].' '.$curyear,
					'paid'=>'-',
					'salary'=>'-',
					'allow'=>'-',
					'ot'=>'-',
					'other_income'=>'-',
					'deduct_before'=>'-',
					'gross'=>'-',
					'pvf'=>'-',
					'sso'=>'-',
					'tax_month'=>'-',
					'deduct_after'=>'-',
					'deductions'=>'-',
					'net'=>'-',
					'class'=>'igrey');
			}else{
				if($i == 12 && $tax_next_month > 0){
					$tax_next_month = $tax_year - $tot_tax_month;
					//if($tax_by_company > 0){
						//$tax_by_company = $tax_next_month;
					//}
					//$next_month = $tax_year - $tot_tax_month;
				}

				$sso_emp = $getAllmonthSSo[$curyear.'_'.$i];

				$data[$i] = array(
					'date'=>$short_months[$i].' '.$curyear,
					'paid'=>'N',
					'salary'=>(float)$basic_salary,
					'allow'=>(float)$fix_allow,
					'ot'=>0,
					'other_income'=>$tax_by_company,
					'deduct_before'=>(float)0,
					'gross'=>$basic_salary + $fix_allow + $tax_by_company,
					'deduct_after'=>(float)0,
					'pvf'=>$pvf_employee,
					'sso'=>$sso_emp,
					'tax_month'=>round($tax_next_month,2),
					'deductions'=>$pvf_employee + $sso_emp + round($tax_next_month,2),
					'net'=>($basic_salary + $fix_allow + $tax_by_company) - ($pvf_employee + $sso_emp + round($tax_next_month,2)),
					'class'=>'igrey');
				$tot_salary += $basic_salary;
				$tot_fix_allow += $fix_allow;
				$tot_pvf += $pvf_employee;
				$tot_sso += $sso_emp;
				$tot_tax += $tax_next_month;
				$tot_tax_month += $tax_next_month;
				$tot_deduct_after += 0;
				$tot_deductions += ($pvf_employee + $sso_emp + round($tax_next_month,2));
				$tot_gross += ($basic_salary + $fix_allow + $tax_by_company);
				$tot_net += (($basic_salary + $fix_allow + $tax_by_company) - ($pvf_employee + $sso_emp + round($tax_next_month,2)));

				$tot_var_income += $tax_by_company;
			}
			
		}
		//var_dump($tot_salary);
		
		$data[13] = array(
			'date'=>$lng['Totals'],
			'paid'=>'',
			'salary'=>$tot_salary,
			'allow'=>$tot_fix_allow,
			'ot'=>$tot_ot,
			'other_income'=>$tot_var_income,
			'deduct_before'=>$tot_deduct_before,
			'gross'=>$tot_gross,
			'deduct_after'=>$tot_deduct_after,
			'pvf'=>$tot_pvf,
			'sso'=>$tot_sso,
			'tax_month'=>round($tot_tax_month,2),
			'deductions'=>$tot_deductions,
			'net'=>$tot_net,
			'class'=>'');
	}
	
	return;
