<?php

	if(session_id()==''){session_start();}
	ob_start();
	include('../../dbconnect/db_connect.php');
	//include(DIR.'payroll/inc/payroll_functions.php');
	//include(DIR.'payroll/inc/tax_modulle.php');
	include(DIR.'employees/tax/calculate_year_tax.php');
	$net = array();
	$val = 150000;
	$add = 1052.63;
	for($i=151000; $i<293000; $i+=1000){
		$val += $add;
		//var_dump($val);
		$net[$i] = round($val);
	}
	$val = 300555.56;
	$net[293000] = round($val);
	$add = 1111.11;
	for($i=294000; $i<473000; $i+=1000){
		$val += $add;
		//var_dump($val);
		$net[$i] = round($val);
	}
	$val = 500588.24;
	$net[473000] = round($val);
	$add = 1176.47;
	for($i=474000; $i<686000; $i+=1000){
		$val += $add;
		//var_dump($val);
		$net[$i] = round($val);
	}
	$val = 751250;
	$net[686000] = round($val);
	$add = 1250;
	for($i=687000; $i<886000; $i+=1000){
		$val += $add;
		//var_dump($val);
		$net[$i] = round($val);
	}
	$val = 1001333.33;
	$net[886000] = round($val);
	$add = 1333.333;
	for($i=887000; $i<1635000; $i+=1000){
		$val += $add;
		//var_dump($val);
		$net[$i] = round($val);
	}
	$val = 2000000;
	$net[1635000] = round($val);
	$add = 1428.5715;
	for($i=1635000; $i<3735000; $i+=1000){
		$val += $add;
		//var_dump($val);
		$net[$i] = round($val);
	}
	$val = 5000000;
	$net[3735000] = round($val);
	$add = 1537.246;
	for($i=3735000; $i<10000000; $i+=1000){
		$val += $add;
		//var_dump($val);
		$net[$i] = round($val);
	}
	//var_dump($net); exit;
	var_dump($_REQUEST); //exit;
	
	$fix = $_REQUEST['xfix'];
	$var = $_REQUEST['xvar'];
	$tot = array_sum($fix) + array_sum($var);
	
	$sso_rate = .05; //$pr_settings['sso_rate']/100;
	$max_sso = 750; //$pr_settings['sso_max'];
	$pvf_rate = $_REQUEST['pvf']/100; //$empinfo['pvf_rate_employee']/100;
	$calc_method = 'acm'; //($empinfo['calc_method'] == '' || $empinfo['calc_method'] == 'def' ? 'cam' : $empinfo['calc_method']);
	$employee_deductions = $_REQUEST['ndeduct'];//$empinfo['total_tax_deductions'];
	
	$tot_tax = 0;
	$tot_gross = 0;
	$tot_sso = 0;
	$tot_pvf = 0;
	$prev_tax = 0;
	$prev_income = 0;
	
	//$income = array_sum($var) - $employee_deductions;
	//$year = $net[round($income,-3)];
	//var_dump($income);
	//var_dump($year);
	//exit;
	
	foreach($fix as $k=>$v){
		
		$income = ($v + $var[$k]);
		$income += ($v + $var[$k]) * (12-$k);
		$income = $income + $prev_income;
		//$income = $income;
		var_dump($income);
		$prev_income += ($v + $var[$k]);
		
		$year = $income;//$net[round($income,-3)];
		var_dump($year);
		
		$taxable = calculateYearTaxNet($year - $employee_deductions);
		
		//$taxable = calculateYearTaxNet($income);
		//var_dump($year - $employee_deductions);
		
		$tax = ($taxable['year']-$prev_tax) / (13-$k);
		var_dump($taxable['year']);
		$prev_tax += $tax;
		//var_dump(13-$k);
		var_dump($tax);
		
		$sso = $v * $sso_rate;
		$sso = ($sso > $max_sso ? (float)$max_sso : $sso);
		$pvf = $v * $pvf_rate;
		//$gross = $v + $var[$k] + $sso + $pvf + $tax;
		$gross = $v + $var[$k] + $tax;
		$tot_gross += $gross;
		$tot_tax += $tax;
		
		$data[$k]['xfix'] = $v;
		$data[$k]['xvar'] = $var[$k];
		$data[$k]['xtot'] = $v + $var[$k];
		$data[$k]['xgross'] = $gross;
		$data[$k]['xtax'] = $tax;
		$data[$k]['xsso'] = $sso;
		$data[$k]['xpvf'] = $pvf;
		$tot_sso += $sso;
		$tot_pvf += $pvf;

		/*$data[$k]['xtot'] = $v + $var[$k];
		$sso = $v * $sso_rate;
		$sso = ($sso > $max_sso ? (float)$max_sso : $sso);
		$pvf = $v * $pvf_rate;
		$gross = $v + $var[$k] + $sso + ($pvf/.7821235);
		$income = $gross * 12;
		var_dump($income);
		//var_dump($net[round($income,-3)]);
		
		$taxable = calculateYearTaxNet($income - $employee_deductions);
		$tax = $taxable['year'] / 12;
		
		$tot_tax += $tax;
		$tot_gross += $gross;
		$gross += $tax;
		$data[$k]['xgross'] = $gross;
		$data[$k]['xtax'] = $tax;
		$data[$k]['xsso'] = $sso;
		$data[$k]['xpvf'] = $pvf;
		$tot_sso += $sso;
		$tot_pvf += $pvf;*/
		
		//var_dump($tax);
		
		
		
	}
	$data[13]['xfix'] = array_sum($fix);
	$data[13]['xvar'] = array_sum($var);
	$data[13]['xtot'] = $tot;
	$data[13]['xgross'] = $tot_gross;
	$data[13]['xsso'] = $tot_sso;
	$data[13]['xpvf'] = $tot_pvf;
	$data[13]['xtax'] = $tot_tax;
	
		//var_dump($prev_tax);
	
	//exit;
	
	$dbc->query("UPDATE ".$cid."_tax_simulation SET gross_from_net = '".serialize($data)."' WHERE emp_id = '".$_REQUEST['emp_id']."'");
	
	//var_dump($data); exit;
	ob_clean();
	echo json_encode($data);
	exit;
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	$tot_salary = array_sum($_REQUEST['sal']);
	$tot_fixallow = array_sum($_REQUEST['fix']);
	$tot_varincome = array_sum($_REQUEST['var']);
	$income = $tot_salary + $tot_fixallow + $tot_varincome;
	
	//$basic_salary = $_REQUEST['avg_sal'];	
	//$fix_allow = $_REQUEST['avg_fix'];
	//$var_allow = $_REQUEST['avg_var'];
	
	//var_dump($basic_salary); exit;
	
	$year_bonus = 0;	
	
	$prev_salary = 0;
	$prev_fixallow = 0;
	$prev_varincome = 0;
	$prev_sso = 0;
	$prev_pvf = 0;
	$prev_tax = 0;
	
	//$prev_fix_allow = 0;
	//$prev_irregular = 0;
	$tot_tax3 = 0;
	$tot_tax = 0;
		
		
		
		
			
		/*if($empinfo['calc_sso'] == 'N'){
			$sso = 0;
			$tax_sso = 0;
		}
		if($empinfo['calc_pvf'] == 'N'){
			$pvf = 0;
			$tax_pvfo = 0;
		}*/
		
		if($calc_method == 'acm'){ // ACM ----------------------------------------------------------------
			
			foreach($salary as $k=>$v){
				
				$sso = $v * $sso_rate;
				$sso = ($sso > $max_sso ? (float)$max_sso : $sso);
				$pvf = $v * $pvf_rate;
				
				$prev_varincome += $var_income[$k];
				
				if($k == 1){
					$year1 = ($v + $fix_allow[$k]) * 12;
					$tax_sso = $sso * 12;
					$tax_pvf = $pvf * 12;
				}else{
					$year1 = $prev_salary + $prev_fixallow;
					$year1 += ($v + $fix_allow[$k]) * (13-$k);
					$tax_sso = $prev_sso + $sso * (13-$k);
					$tax_pvf = $prev_pvf + $pvf * (13-$k);
				}
				$year1 += $year_bonus;
				$year1 += $prev_varincome;
				//var_dump($tax_pvf);
				
				
				$year2 = $year1;// + $tot_irregular;
				//var_dump($year2);
				$bonus = 0;
				//if($bim == $k){$bonus = $year_bonus;}
				$prev_salary += $v;
				$prev_fixallow += $fix_allow[$k];
				$prev_sso += $sso;
				$prev_pvf += $pvf;
				
				$taxable = calculateYearTax($year1 - $employee_deductions);
				$tax1 = $taxable['year'];
				
				$tax_this_month = $tax1 - $prev_tax;
				$tax_this_month = $tax_this_month /(13-$k);
				$prev_tax += $tax_this_month;
				
				//$tax_this_month += $empinfo['modify_tax'];
				$tax_this_month = ($tax_this_month < 0 ? 0 : $tax_this_month);
				
				$gross = $v + $fix_allow[$k] + $bonus + $var_income[$k];
				$deduct = $sso + $pvf + $tax_this_month;
				$net = $gross - $deduct;
				
				$data[$k]['sal'] = $v;
				$data[$k]['fix'] = $fix_allow[$k];
				$data[$k]['var'] = $var_income[$k];
				$data[$k]['gross'] = $gross;
				$data[$k]['sso'] = $sso;
				$data[$k]['pvf'] = $pvf;
				$data[$k]['tax'] = $tax_this_month;
				$data[$k]['net'] = $net;
			}
		}
		$data[13]['sal'] = $tot_salary;
		$data[13]['fix'] = $tot_fixallow;
		$data[13]['var'] = $tot_varincome;
		$data[13]['gross'] = $income;
		$data[13]['sso'] = $tax_sso;
		$data[13]['pvf'] = $tax_pvf;
		$data[13]['tax'] = $tax1;
		$data[13]['net'] = $income - $tax_sso - $tax_pvf - $tax1;


	//var_dump($taxable); //exit;
	//var_dump($data); exit;
	ob_clean();
	echo json_encode($data);
	exit;
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
