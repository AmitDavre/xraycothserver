<?php

	if(session_id()==''){session_start();}
	ob_start();
	include('../../dbconnect/db_connect.php');
	include(DIR.'files/payroll_functions.php');
	include('calculate_year_tax2.php');
	
	//var_dump($_REQUEST); exit;
	
	$row = array();
	$sql = "SELECT calc_sso, calc_pvf, calc_tax, modify_tax, pvf_rate_emp FROM ".$cid."_tax_simulation WHERE emp_id = '".$_REQUEST['emp_id']."'";
	if($res = $dbc->query($sql)){
		$row = $res->fetch_assoc();
	}
	//var_dump($row); exit;

	$fix = $_REQUEST['xfix'];
	$var = $_REQUEST['xvar'];
	
	$sso_rate = $pr_settings['sso_rate_emp']/100;
	$min_sso = $pr_settings['sso_min_emp'];
	$max_sso = $pr_settings['sso_max_emp'];
	$pvf_rate = $row['pvf_rate_emp'];

	$prev_salary = 0;
	$prev_sso = 0;
	$prev_pvf = 0;
	$prev_tax = 0;
	$tot_salary = 0;
	$tot_tax = 0;
	$tot_sso = 0;
	$tot_pvf = 0;
	
	foreach($fix as $k=>$v){
		$employee_deductions = $_REQUEST['ndeduct'];
		
		$salary = $v + $var[$k];
		$tot_salary += $salary;
		
		$sso = 0;
		$tax_sso = 0;
		if($row['calc_sso']){
			$sso = $salary * $sso_rate;
			$sso = ($sso > $max_sso ? (float)$max_sso : $sso);
			$sso = ($sso < $min_sso ? (float)$min_sso : $sso);
			if($k == 1){
				$tax_sso = $sso * 12;
			}else{
				$tax_sso = $prev_sso + $sso * (13-$k);
			}
		}
		$tot_sso += $sso;
		$employee_deductions += $tax_sso;
		
		$pvf = 0;
		$tax_pvf = 0;
		if($row['calc_pvf']){
			$pvf = $salary * $pvf_rate;
			$tax_pvf = $pvf * 12;
			if($k == 1){
				$tax_sso = $sso * 12;
			}else{
				$tax_pvf = $prev_pvf + $pvf * (13-$k);
			}
		}
		$tot_pvf += $pvf;
		$employee_deductions += $tax_pvf;
		if($k == 1){
			$year1 = $salary * 12;
		}else{
			$year1 = $prev_salary;
			$year1 += $salary * (13-$k);
		}
		$tmp = taxFomNet($year1, $employee_deductions);
		$tax1 = $tmp['tax_year'];
		
		$tax_this_month = $tax1 - $prev_tax;
		$tax_this_month = $tax_this_month /(13-$k);
		$prev_tax += $tax_this_month;
		
		$tax_this_month = ($tax_this_month < 0 ? 0 : $tax_this_month);
		$tot_tax += $tax_this_month;
		
		$prev_salary += $salary;
		$prev_sso += $sso;
		$prev_pvf += $pvf;

		$data[$k]['fix'] = $v;
		$data[$k]['var'] = $var[$k];
		$data[$k]['net'] = $salary;
		$data[$k]['gross'] = $salary + $sso + $pvf + $tax_this_month;
		$data[$k]['sso'] = $sso;
		$data[$k]['pvf'] = $pvf;
		$data[$k]['tax'] = $tax_this_month;
	}
	
	$data[13]['xfix'] = array_sum($fix);
	$data[13]['xvar'] = array_sum($var);
	$data[13]['xtot'] = $tot_salary;
	$data[13]['xgross'] = $tot_salary + $tot_sso + $tot_pvf + $tot_tax;
	$data[13]['xsso'] = $tot_sso;
	$data[13]['xpvf'] = $tot_pvf;
	$data[13]['xtax'] = $tot_tax;

	$dbc->query("UPDATE ".$cid."_tax_simulation SET gross_from_net = '".serialize($data)."' WHERE emp_id = '".$_REQUEST['emp_id']."'");

	//var_dump($data); exit;
	ob_clean();
	echo json_encode($data);
	exit;
	
	
	
