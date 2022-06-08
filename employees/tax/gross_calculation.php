<?php

	if(session_id()==''){session_start();}
	ob_start();
	include('../../dbconnect/db_connect.php');
	//include(DIR.'files/functions.php');
	include(DIR.'files/payroll_functions.php');
	include('calculate_year_tax2.php');
	
	//var_dump($pr_settings); //exit;
	//var_dump($_REQUEST); //exit;
	
	$row = array();
	$sql = "SELECT calc_sso, calc_pvf, calc_tax, modify_tax, pvf_rate_emp FROM ".$cid."_tax_simulation WHERE emp_id = '".$_REQUEST['emp_id']."'";
	if($res = $dbc->query($sql)){
		$row = $res->fetch_assoc();
	}
	//var_dump($row); exit;
	
	$salary = $_REQUEST['sal'];
	$fix_allow = $_REQUEST['fix'];
	$var_income = $_REQUEST['var'];
	
	
	$tot_salary = array_sum($_REQUEST['sal']);
	$tot_fixallow = array_sum($_REQUEST['fix']);
	$tot_varincome = array_sum($_REQUEST['var']);
	$income = $tot_salary + $tot_fixallow + $tot_varincome;
	
	//$basic_salary = $_REQUEST['avg_sal'];	
	//$fix_allow = $_REQUEST['avg_fix'];
	//$var_allow = $_REQUEST['avg_var'];
	
	//var_dump($pr_settings); exit;
	
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
		
		
		$sso_rate = $pr_settings['sso_rate_emp']/100;
		$min_sso = $pr_settings['sso_min_emp'];
		$max_sso = $pr_settings['sso_max_emp'];
		$pvf_rate = $row['pvf_rate_emp']/100; //$empinfo['pvf_rate_employee']/100;
		
		foreach($salary as $k=>$v){
			$employee_deductions = $_REQUEST['deduct'];
			$sso = 0;
			$tax_sso = 0;
			if($row['calc_sso']){
				$sso = $v * $sso_rate;
				$sso = ($sso > $max_sso ? (float)$max_sso : $sso);
				$sso = ($sso < $min_sso ? (float)$min_sso : $sso);
				if($k == 1){
					$tax_sso = $sso * 12;
				}else{
					$tax_sso = $prev_sso + $sso * (13-$k);
				}
			}
			$employee_deductions += $tax_sso;
			
			$pvf = 0;
			$tax_pvf = 0;
			if($row['calc_pvf']){
				$pvf = $v * $pvf_rate;
				$tax_pvf = $pvf * 12;
				if($k == 1){
					$tax_sso = $sso * 12;
				}else{
					$tax_pvf = $prev_pvf + $pvf * (13-$k);
				}
			}
			$employee_deductions += $tax_pvf;

			$prev_varincome += $var_income[$k];
			
			if($k == 1){
				$year1 = ($v + $fix_allow[$k]) * 12;
			}else{
				$year1 = $prev_salary + $prev_fixallow;
				$year1 += ($v + $fix_allow[$k]) * (13-$k);
			}
			$year1 += $year_bonus;
			$year1 += $prev_varincome;

			$year2 = $year1;// + $tot_irregular;
			$bonus = 0;
			$prev_salary += $v;
			$prev_fixallow += $fix_allow[$k];
			$prev_sso += $sso;
			$prev_pvf += $pvf;
		
			$tmp = taxFomGross($year1, $employee_deductions);
			$tax1 = $tmp['tax_year'];
			
			$tax_this_month = $tax1 - $prev_tax;
			$tax_this_month = $tax_this_month /(13-$k);
			$prev_tax += $tax_this_month;
			
			//$tax_this_month += $empinfo['modify_tax'];
			$tax_this_month = ($tax_this_month < 0 ? 0 : $tax_this_month);
			$tot_tax += $tax_this_month;
			
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
		
		$data[13]['sal'] = $tot_salary;
		$data[13]['fix'] = $tot_fixallow;
		$data[13]['var'] = $tot_varincome;
		$data[13]['gross'] = $income;
		$data[13]['sso'] = $tax_sso;
		$data[13]['pvf'] = $tax_pvf;
		$data[13]['tax'] = $tax1;
		$data[13]['net'] = $income - $tax_sso - $tax_pvf - $tax1;

	$dbc->query("UPDATE ".$cid."_tax_simulation SET net_from_gross = '".serialize($data)."' WHERE emp_id = '".$_REQUEST['emp_id']."'");
	
	//var_dump($taxable); //exit;
	//var_dump($data); exit;
	ob_clean();
	echo json_encode($data);
	exit;
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
