<?
	function xgetEmployeeTaxdata($id, $pr_dbase, $emp_dbase){
		global $pr_settings;
		global $dbc;
		global $short_months;
		
		$title = getAppMetadata('title', $_SESSION['xhr']['lang']);
		//$emp_dbase = $_SESSION['xhr']['emp_dbase'];
		//$pr_dbase = $_SESSION['payroll']['payroll_dbase'];
		$empinfo = getEmpinfo($emp_dbase, $id);
		$basic_salary = $empinfo['base_salary'];
		$bim = $empinfo['bonus_payinmonth'];
		$bom = $empinfo['bonus_months'];
		$year_bonus = $bom * $basic_salary;
		$fix_allow = 0;
		for($i=1;$i<=15;$i++){
			$fix_allow += $empinfo['fix_allow_'.$i];
		}
		$sso_rate = $pr_settings['sso_rate']/100;
		$max_sso = $pr_settings['sso_max'];
		$pvf_rate = $empinfo['pvf_rate_employee']/100;
		$calc_method = ($empinfo['calc_method'] == '' || $empinfo['calc_method'] == 'def' ? 'cam' : $empinfo['calc_method']);
		$employee_deductions = $empinfo['total_tax_deductions'];
		
		$prev = array();
		$tot_salary = 0;
		$tot_pvf = 0;
		$tot_sso = 0;
		$tot_tax = 0;
		$tot_fix_allow = 0;
		$tot_irregular = 0;
		$tot_tax3 = 0;
	
		$data = array();
		
		$res = $dbc->query("SELECT * FROM $pr_dbase WHERE emp_id = '".$id."' ORDER by month ASC");
		while($row = $res->fetch_assoc()){
			$prev[(int)$row['month']] = array(
				'date'=>$short_months[(int)$row['month']].' '.substr($_SESSION['xhr']['cur_year'],2),
				'paid'=>$row['paid'],
				'salary'=>(float)$row['salary'],
				'allow'=>(float)$row['total_fix_allow'] + (float)$row['total_var_allow'],
				'ot'=>(float)$row['total_otb'],
				'bonus'=>(float)$row['bonus'],
				'other_income'=>(float)$row['other_income']+(float)$row['severance'],
				'pvf'=>(float)$row['pvf_employee'],
				'sso'=>(float)$row['social'],
				'tax'=>(float)$row['tax'],
				'deduct'=>(float)$row['tot_deduct'],
				'deductions'=>(float)$row['tot_deductions'],
				'gross'=>(float)$row['gross_income'],
				'net'=>(float)$row['net_income'],
				'class'=>''
			);
			$tot_salary += $row['salary'];
			$tot_pvf += $row['pvf_employee'];
			$tot_sso += $row['social'];
			$tot_tax += $row['tax'];
	
			$tot_fix_allow += $row['total_fix_allow'];
			$tot_irregular += ($row['total_var_allow']+$row['total_otb']+$row['other_income']+$row['severance']);
			$tot_tax3 += $row['tax3'];
		}
		
		reset($prev);
		if(key($prev) > 1){ 
			for($n=1;$n<key($prev);$n++){// Empty leading months
			$data[$n] = array(
				'date'=>$short_months[$n].' '.substr($_SESSION['xhr']['cur_year'],2),
				'paid'=>'N',
				'salary'=>'-',
				'allow'=>'-',
				'ot'=>'-',
				'bonus'=>'-',
				'other_income'=>'-',
				'pvf'=>'-',
				'sso'=>'-',
				'tax'=>'-',
				'deduct'=>'-',
				'deductions'=>'-',
				'gross'=>'-',
				'net'=>'-',
				'class'=>'');
			} 
		}
	
		$data +=$prev;
	
		end($prev);
		$prev_months = key($prev);
		$rem_months = 12 - $prev_months;
		
		$sso = $basic_salary * ($sso_rate);
		$sso = ($sso > $max_sso ? (float)$max_sso : $sso);
		$tax_sso = ($sso * $rem_months) + $tot_sso;
		$pvf = $basic_salary * ($pvf_rate);
		$tax_pvf = ($pvf * $rem_months) + $tot_pvf;
			
		if($calc_method == 'cam'){ // CAM ------------------------------------------------------------------
			
			for($i=$prev_months+1;$i<=12;$i++){
				
				//var_dump($i);
				$tot_salary += $basic_salary;
				$tot_fix_allow += $fix_allow;
				
				if($i == 12){
					$year1 = $tot_salary + $tot_fix_allow;
				}else{
					$year1 = ($basic_salary + $fix_allow) * 12;
				}
				//var_dump($year1);
				
				$year2 = $year1 + $tot_irregular;
				
				$bonus = 0;
				if($bim == $i){$year2 += $year_bonus; $bonus = $year_bonus;}
				//var_dump($year2);
	
				$taxable = calculateTax3($year1, $tax_sso, $tax_pvf, $employee_deductions);
				$tax1 = $taxable['year_tax'];
				//var_dump($tax1);
				
				$taxable = calculateTax3($year2, $tax_sso, $tax_pvf, $employee_deductions);
				$tax2 = $taxable['year_tax'];
				//var_dump($tax2);
				
				if($i == 12){
					$tax3 = $tot_tax;
				}else{
					$tax3 = $tax2 - $tax1 - $tot_tax3;
				}
				$tot_tax3 += $tax3;
				//var_dump($tax3);
				
				if($i == 12){
					$tax_this_month = $tax2 - $tax3;
				}else{
					$tax_this_month = ($tax1/12) + $tax3;
				}
				$tax_this_month = ($tax_this_month < 0 ? 0 : $tax_this_month);
				$tot_tax += $tax_this_month;
				//var_dump($tax_this_month);
				
				$gross = $basic_salary + $fix_allow;
				$deduct = $sso + $pvf + $tax_this_month;
				$net = $gross - $deduct;
				
				$data[$i] = array(
					'date'=>$short_months[$i].' '.substr($_SESSION['xhr']['cur_year'],2),
					'paid'=>'N',
					'salary'=>$basic_salary,
					'allow'=>$fix_allow,
					'ot'=>0,
					'bonus'=>$bonus,
					'other_income'=>0,
					'pvf'=>$pvf,
					'sso'=>$sso,
					'tax'=>$tax_this_month,
					'deduct'=>0,
					'deductions'=>$deduct,
					'gross'=>$gross,
					'net'=>$net,
					'class'=>'igrey'
				);
				//var_dump('xxx');
			}
		}
		
		if($calc_method == 'acm'){ // ACM ----------------------------------------------------------------
			
			for($i=$prev_months+1;$i<=12;$i++){
				
				if($i == 1){
					$year1 = ($basic_salary + $fix_allow) * 12;
				}else{
					$year1 = $tot_salary + $tot_fix_allow;
					$year1 += ($basic_salary + $fix_allow) * $rem_months;
				}
				$year1 += $year_bonus;
				//var_dump($year1);
	
				$year2 = $year1 + $tot_irregular;
				//var_dump($year2);
				
				$taxable = calculateTax3($year1, $tax_sso, $tax_pvf, $employee_deductions);
				$tax1 = $taxable['year_tax'];
				//var_dump($tax1);
				$taxable = calculateTax3($year2, $tax_sso, $tax_pvf, $employee_deductions);
				$tax2 = $taxable['year_tax'];
				//var_dump($tax2);
				
				if($i == 12){
					$tax3 = $tot_tax;
				}else{
					$tax3 = $tax2 - $tax1 - $tot_tax3;
				}
				$tot_tax3 += $tax3;
				//var_dump($tax3);
				
				if($i == 12){
					$tax_this_month = $tax2 - $tax3;
				}else{
					$tax_this_month = $tax1 - $tot_tax;
					$tax_this_month += $tot_tax3;
					$tax_this_month = $tax_this_month/(12-($i-1));
					$tax_this_month += $tax3;
				}
				
				$tax_this_month = ($tax_this_month < 0 ? 0 : $tax_this_month);
				$tot_tax += $tax_this_month;
				//var_dump($tax_this_month);
				
				$gross = $basic_salary + $fix_allow;
				$deduct = $sso + $pvf + $tax_this_month;
				$net = $gross - $deduct;
				
				$data[$i] = array(
					'date'=>$short_months[$i].' '.substr($_SESSION['xhr']['cur_year'],2),
					'paid'=>'N',
					'salary'=>$basic_salary,
					'allow'=>$fix_allow,
					'ot'=>0,
					'bonus'=>$year_bonus,
					'other_income'=>0,
					'pvf'=>$pvf,
					'sso'=>$sso,
					'tax'=>$tax_this_month,
					'deduct'=>0,
					'deductions'=>$deduct,
					'gross'=>$gross,
					'net'=>$net,
					'class'=>'igrey'
				);
			}
		}
		
		$tot_salary = 0;
		$tot_ot = 0;
		$tot_allow = 0;
		$tot_bonus = 0;
		$tot_other_income = 0;
		$tot_gross = 0;
		$tot_pvf = 0;
		$tot_sso = 0;
		$tot_tax = 0;
		$tot_other_deduct = 0;
		$tot_deductions = 0;
		$tot_net = 0;
		
		foreach($data as $k=>$v){ 
			$tot_salary += $v['salary'];
			$tot_ot += $v['ot'];
			$tot_allow += $v['allow'];
			$tot_bonus += $v['bonus'];
			$tot_other_income += $v['other_income'];
			$tot_gross += $v['gross'];
			$tot_pvf += $v['pvf'];
			$tot_sso += $v['sso'];
			$tot_tax += $v['tax'];
			$tot_other_deduct += $v['deduct'];
			$tot_deductions += $v['deductions'];
			$tot_net += $v['net'];
		}	
		
		$data[13] = array(
			'date'=>'Totals',
			'paid'=>'',
			'salary'=>$tot_salary,
			'allow'=>$tot_allow,
			'ot'=>$tot_ot,
			'bonus'=>$tot_bonus,
			'other_income'=>$tot_other_income,
			'pvf'=>$tot_pvf,
			'sso'=>$tot_sso,
			'tax'=>$tot_tax,
			'deduct'=>$tot_other_deduct,
			'deductions'=>$tot_deductions,
			'gross'=>$tot_gross,
			'net'=>$tot_net,
			'class'=>''
		);
		
		$xdata['calc_method'] = $calc_method;
		$xdata['emp_name'] = $id.' : '.$title[$empinfo['title']].' '.$empinfo[$_SESSION['xhr']['lang'].'_name'];
		$xdata['data'] = $data;
		
		return $xdata;
		
	}
	
	function getEmployeeTaxoverview($pr_dbase, $emp_dbase, $id){
		global $dbc;
		global $compinfo;
		global $pr_settings;
		$tot_income = 0;
		$tot_irregular = 0;
		$tot_pvf = 0;
		$tot_sso = 0;
		$tot_tax = 0;
		$tot_tax3 = 0;
		$tot_deduct = 0;
		$res = $dbc->query("SELECT * FROM $pr_dbase WHERE emp_id = '".$id."' ORDER by month ASC");
		while($row = $res->fetch_assoc()){
			//$data[(int)$row['month']] = $row;
			$prev[(int)$row['month']] = array(
				'paid'=>$row['paid'],
				'salary'=>$row['salary'],
				'allow'=>$row['total_fix_allow']+$row['total_var_allow'],
				'ot'=>$row['total_otb'],
				'bonus'=>$row['bonus'],
				'other_income'=>$row['other_income']+$row['severance'],
				'pvf'=>$row['pvf_employee'],
				'sso'=>$row['social'],
				'tax'=>$row['tax'],
				'deduct'=>$row['tot_deduct'],
				'deductions'=>$row['tot_deductions'],
				'gross'=>$row['gross_income'],
				'net'=>$row['net_income'],
				'class'=>''
			);
			$tot_income += ($row['salary']+$row['total_fix_allow']+$row['total_var_allow']+$row['total_otb']+$row['other_income']+$row['severance']-$row['tot_deduct']);
			$tot_irregular += ($row['total_var_allow']+$row['total_otb']+$row['other_income']+$row['severance']);
			$tot_pvf += $row['pvf_employee'];
			$tot_sso += $row['social'];
			$tot_tax += $row['tax'];
			$tot_tax3 += $row['tax3'];
			$tot_deduct += $row['tot_deduct'];
		}
		//unset($prev[1]);
		//unset($prev[2]);
		//unset($prev[3]);
		
		$empinfo = getEmpinfo($emp_dbase, $id);
		$basic = $empinfo['base_salary'];
		$bim = $empinfo['bonus_payinmonth'];
		$bom = $empinfo['bonus_months'];
		$fix_allow = 0;
		for($i=1;$i<=15;$i++){
			$fix_allow += $empinfo['fix_allow_'.$i];
		}
		//$tax_settings = unserialize($pr_settings['tax_settings']);//getOtherTaxSettings();
		$sso_rate = $pr_settings['sso_rate']/100;
		$max_sso = $pr_settings['sso_max'];
		$pvf_rate = $empinfo['pvf_rate_employee'];
		$calc_method = ($empinfo['calc_method'] == '' || $empinfo['calc_method'] == 'def' ? 'cam' : $empinfo['calc_method']);
		
		$data = array();
		if(!empty($prev)){
		reset($prev);
		$remaining_months = 12;//13-key($prev);
		if(key($prev) > 1){ 
			for($n=1;$n<key($prev);$n++){// Empty leading months
				$data[$n] = array(
					'paid'=>'-',
					'salary'=>0,
					'allow'=>0,
					'ot'=>0,
					'bonus'=>0,
					'other_income'=>0,
					'pvf'=>0,
					'sso'=>0,
					'tax'=>0,
					'deduct'=>0,
					'deductions'=>0,
					'gross'=>0,
					'net'=>0,
					'class'=>'igrey'
				);
			} 
		}

		$data += $prev;
		end($data);
		$key = key($data)+1;
		
		$pvf = ($basic * $pvf_rate)/100;
		$sso = ($basic + $fix_allow) * $sso_rate;
		$sso = ($sso > $max_sso ? $max_sso : $sso);
		$tax_pvf = $pvf * 12;
		$tax_sso = $sso * 12;
		
		for($i=$key;$i<=12;$i++){ // Next months
			$tot_pvf += $pvf;
			$tot_sso += $sso;
			
			$this_bonus = 0;
			$year_bonus = $basic * $bom;
			if($bim == $i){$this_bonus = $year_bonus;}
			$year1 = (($basic + $fix_allow) * $remaining_months);
			$year1 = round($year1,2);
			
			if($calc_method == 'cam'){
				$taxable = calculateTax2($year1,$tax_sso,$tax_pvf,$empinfo);
				$tax1 = round($taxable['taxyear'],2);
				
				$year2 = $year1 + $tot_irregular + $this_bonus - $tot_deduct;
				$taxable = calculateTax2($year2,$tax_sso,$tax_pvf,$empinfo);
				$tax2 = round($taxable['taxyear'],2);
				$tax3 = $tax2 - $tax1 - $tot_tax3;
				$tot_tax3 += $tax3;
				
				//var_dump($tax1);
				$tax = ($tax1/$remaining_months) + $tax3;
	
				$gross = $basic + $fix_allow + $this_bonus;
				$tot_income += $gross;
				
				if($i == 12){
					$year2 = $tot_income - $tot_deduct;
					$year2 = round($year2,2);
					$taxable = calculateTax2($year2,$tot_sso,$tot_pvf,$empinfo);
					$tax2 = round($taxable['taxyear'],2);
					$tax = $tax2 - $tot_tax;
				}
				
				$tot_tax += $tax;
				$deductions = $pvf + $sso + $tax;
				$net = $gross - $deductions;
				
			}else{ // if $calc_method == 'acm'
				$taxable = calculateTax2($year1,$tax_sso,$tax_pvf,$empinfo);
				$tax1 = round($taxable['taxyear'],2);
				
				$year2 = $year1 + $this_bonus;
				$taxable = calculateTax2($year2,$tax_sso,$tax_pvf,$empinfo);
				$tax2 = round($taxable['taxyear'],2);
				$tax3 = $tax2 - $tax1 - $tot_tax3;
				$tot_tax3 += $tax3;
				
				$tax = ($tax1 - $tot_tax + $tot_tax3)/($remaining_months-($i-1));
	
				$gross = $basic + $fix_allow + $this_bonus;
				$tot_income += $gross;
				
				if($i == 12){
					$year2 = $tot_income - $tot_deduct;
					$year2 = round($year2,2);
					$taxable = calculateTax2($year2,$tot_sso,$tot_pvf,$empinfo);
					$tax2 = round($taxable['taxyear'],2);
					$tax = $tax2 - $tot_tax;
				}
				
				$tot_tax += $tax;
				$deductions = $pvf + $sso + $tax;
				$net = $gross - $deductions;
			}
			
			$data[$i] = array(
				'paid'=>'N',
				'salary'=>$basic,
				'allow'=>$fix_allow,
				'ot'=>0,
				'bonus'=>$this_bonus,
				'other_income'=>0,
				'pvf'=>$pvf,
				'sso'=>$sso,
				'tax'=>$tax,
				'deduct'=>0,
				'deductions'=>$deductions,
				'gross'=>$gross,
				'net'=>$net,
				'class'=>'igrey'
			);
		}
		}
		return $data;
	}
	
	function xgetEmpinfo($db_employee, $id){
		global $dbc;
		//global $db_employee;
		//return $db_employee; exit;
		$data = array();	
		$sql = "(SELECT * FROM $db_employee WHERE emp_id = '".$id."')";
		if($res = $dbc->query($sql)){
			if($row = $res->fetch_assoc()){
				$data = $row;
			}
		}
		return $data;
	}
?>
