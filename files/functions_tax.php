<?php
	
	function getEmployeeTaxdata($id){
		global $pr_settings;
		global $dbc;
		global $title;
		global $short_months;
		$historic_data = false;
		$his_salary = 0;
		$his_pvf = 0;
		$his_sso = 0;
		$his_tax = 0;
		$his_fix_allow = 0;
		$his_irregular = 0;
		//$his_tax3 = 0;
		$res = $dbc->query("SELECT * FROM ".$_SESSION['rego']['cid']."_payroll_".$_SESSION['rego']['cur_year']." WHERE emp_id = '".$id."' ORDER by month ASC");
		while($row = $res->fetch_assoc()){
			$prev[(int)$row['month']] = array(
				'date'=>$short_months[(int)$row['month']].' '.substr($_SESSION['rego']['cur_year'],2),
				'paid'=>$row['paid'],
				'salary'=>(float)$row['salary'],
				'allow'=>(float)$row['total_fix_allow'] + (float)$row['total_var_allow'],
				'ot'=>(float)$row['total_otb'],
				'other_income'=>(float)$row['other_income']+(float)$row['severance'],
				'pvf'=>(float)$row['pvf_employee'],
				'sso'=>(float)$row['social'],
				'tax'=>(float)$row['tax'],
				'deduct'=>0,
				'deductions'=>(float)$row['tot_deductions'],
				'gross'=>(float)$row['gross_income'],
				'net'=>(float)$row['net_income'],
				'class'=>''
			);
			//$basic_salary += $row['salary'];
			//$tot_salary += $row['salary'];
			//$tot_pvf += $row['pvf_employee'];
			//$tot_sso += $row['social'];
			//$tot_tax += $row['tax'];
	
			//$tot_fix_allow += $row['total_fix_allow'];
			//$fix_allow = $row['total_fix_allow'];
			//$tot_irregular += ($row['total_var_allow']+$row['total_otb']+$row['other_income']+$row['severance']);
			//$tot_tax3 += $row['tax3'];
			$data['calc_method'] = $row['calc_method'];
		}
		$data['data'] = $prev;
		return $data;
		
	}
