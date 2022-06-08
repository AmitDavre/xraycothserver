<?php

	$year['en'] = date('Y');
	$year['th'] = date('Y')+543;
	$sso_rate = number_format((float)$pr_settings['sso_rate_emp'], 2, '.', '').'%';
	
	$s = str_replace('-','',$edata['sso_account']);
	if(strlen($s)!== 10){$s = '??????????';}
	$sso = str_split($s);
	$branch = sprintf("%06d",$sso_codes[$_SESSION['rego']['gov_branch']]['code']);

	$emps = 0; $income = 0; $social = 0; $social_com = 0;
	if($res = $dbc->query("SELECT * FROM ".$_SESSION['rego']['payroll_dbase']." WHERE month = '".$_SESSION['rego']['gov_month']."' AND entity = '".$_SESSION['rego']['gov_entity']."' AND branch = '".$_SESSION['rego']['gov_branch']."' AND emp_group = '".$_SESSION['rego']['emp_group']."' AND social > 0 ")){
		while($row = $res->fetch_assoc()){
			$empinfo = getEmployeeInfo($cid, $row['emp_id']);
			$fix_allow = 0; 
			for($i=1;$i<=10;$i++){
				$fix_allow += $row['fix_allow_'.$i]; // ????????????????????? from payroll database
			}
			//$basicsalary = $row['salary'] + $fix_allow;
			$basicsalary = (($row['salary'] + $fix_allow + $row['remaining_salary']) - ($row['leave_wop_b'] + $row['absence_b'] + $row['late_early_b']) );
			$basic_salary = ($basicsalary > $rego_settings['sso_max_wage'] ? $rego_settings['sso_max_wage'] : $basicsalary);
			$emps ++;
			$income += $basic_salary;
			$social += $row['social'];
			$social_com += $row['social_com'];
		}
	}
	$total = $social + $social_com;

	
	$data = getSSOattach($_SESSION['rego']['payroll_dbase'], $_SESSION['rego']['gov_month'], $lang, $pr_settings['sso_act_max']);
	
	$s = number_format((float)$total, 2, '.', '');
	if($lang == 'en'){
		$locale = 'en_US';
		$fmt = numfmt_create($locale, NumberFormatter::SPELLOUT);
		$chars = numfmt_format($fmt, round($total,2));
	}else{
		$chars = getThaiCharNumber($s);
	}
	
	$inc = number_format((float)str_replace(',','',$data['tot_salary']), 2, '.', ',');
	$income = explode('.', $inc);
	
	$social = number_format($social);
	$social_com = number_format($social_com);
	$total = number_format($total);
	
	$pages = 1;
	if($emps > 10){
		$pages = ceil($emps/10);
	}
	
	if(!$_REQUEST){
		$_REQUEST['rr'] = 1;
		$_REQUEST['other'] = '';
	}

?>