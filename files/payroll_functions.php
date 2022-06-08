<?php

	function isValidDate($date, $format = 'H:i') {
		if($date == '-'){return false;}
		$d = DateTime::createFromFormat($format, $date);
		return $d && $d->format($format) == $date;
	}

	function decimalHours($time){
		if(!empty($time)){
			$hms = explode(":", $time);
			if(isset($hms[1])){
				return ($hms[0] + ($hms[1]/60));// + ($hms[2]/3600));
			}else{
				return $hms[0];
			}
		}
	}
	
	function addHours($hours,$add){
		$a = new DateTime($hours);
		$aa = new DateInterval("P0000-00-00T$add:00");
		$a->add($aa);
		$h = (int)$a->format('H');
		return $h.$a->format(':i');
	}
	
	function intHours($time){
		if(!empty($time)){
			$hms = explode(":", $time);
			return (int)$hms[0].':'.$hms[1];
		}
	}
	
	function dateHours($number){
		if(!empty($number)){
			$number = number_format($number,20);
			$tmp = explode(".", $number);
			$deci = '0.'.$tmp[1];
			$deci = $deci*60;
			$deci = number_format(((float)$deci),2);
			return ($tmp[0].':'.sprintf("%02d",$deci));
		}else{
			return '-';
		}
	}

	function dateHoursEmpty($number){
		if(!empty($number)){
			$number = number_format($number,20);
			$tmp = explode(".", $number);
			$deci = '0.'.$tmp[1];
			$deci = $deci*60;
			$deci = number_format(((float)$deci),2);
			return ($tmp[0].':'.sprintf("%02d",$deci));
		}else{
			return '';
		}
	}



	

	function getTotModifiedTax($id){
		global $dbc;
		$modify_tax = 0;
		$sql = "SELECT SUM(modify_tax) as mod_tax FROM ".$_SESSION['rego']['payroll_dbase']." WHERE emp_id = '".$id."'";
		if($res = $dbc->query($sql)){
			if($row = $res->fetch_assoc()){
				$modify_tax = $row['mod_tax'];
			}
		}
		return $modify_tax;
	}
	
	function getUsedAtendanceColumns(){
		global $dbc;
		$var_allow = getUsedVarAllow($_SESSION['rego']['lang']);
		$cols = array();
		//return $var_allow;
		
		$sql = "SELECT * FROM ".$_SESSION['rego']['payroll_dbase']." WHERE month = ".$_SESSION['rego']['curr_month'];
		if($res = $dbc->query($sql)){
			while($row = $res->fetch_assoc()){
				$col = 4;
				if($row['ot1h'] > 0){$cols['ot1h'] = $col;}; $col++;
				if($row['ot15h'] > 0){$cols['ot15h'] = $col;}; $col++;
				if($row['ot2h'] > 0){$cols['ot2h'] = $col;}; $col++;
				if($row['ot3h'] > 0){$cols['ot3h'] = $col;}; $col++;
				if($row['ootb'] > 0){$cols['ootb'] = $col;}; $col++;
				
				if($row['absence'] > 0){$cols['absence'] = $col;}; $col++;
				if($row['late_early'] > 0){$cols['late_early'] = $col;}; $col++;
				if($row['leave_wop'] > 0){$cols['leave_wop'] = $col;}; $col++;
				foreach($var_allow as $k=>$v){
					if($row['var_allow_'.$k] > 0){$cols['var_allow_'.$k] = $col;}; $col++;
				}
				//$col = 14;
				if($row['other_income'] > 0){$cols['other_income'] = $col;}; $col++;
				if($row['severance'] > 0){$cols['severance'] = $col;}; $col++;
				if($row['remaining_salary'] > 0){$cols['remaining_salary'] = $col;}; $col++;
				if($row['notice_payment'] > 0){$cols['notice_payment'] = $col;}; $col++;
				if($row['paid_leave'] > 0){$cols['paid_leave'] = $col;}; $col++;
				
				if($row['var_deduct_1'] > 0){$cols['var_deduct_1'] = $col;}; $col++;
				if($row['var_deduct_2'] > 0){$cols['var_deduct_2'] = $col;}; $col++;
				if($row['var_deduct_3'] > 0){$cols['var_deduct_3'] = $col;}; $col++;
				if($row['var_deduct_4'] > 0){$cols['var_deduct_4'] = $col;}; $col++;
				if($row['var_deduct_5'] > 0){$cols['var_deduct_5'] = $col;}; $col++;
				
				if($row['advance'] > 0){$cols['advance'] = $col;}
			}
		}
		return $cols; exit;
	}

	function getPayrollStatus($period){
		global $dbc;
		global $cid;
		$status = 0;
		$sql = "SELECT status FROM ".$cid."_payroll_months WHERE month = '".$period."'";
		if($res = $dbc->query($sql)){
			if($row = $res->fetch_assoc()){
				$status = $row['status'];
			}
		}
		return $status;
	}

	function getMissingEmployeesFromPayroll($cid, $month){
		global $dbc;
		$existing_emps = array();
		$sql = "SELECT emp_id FROM ".$_SESSION['rego']['payroll_dbase']." WHERE month = '".$month."'";
		if($res = $dbc->query($sql)){
			while($row = $res->fetch_assoc()){
				$existing_emps[] = $row['emp_id'];
			}
		}
		//var_dump($existing_emps);
		$missing_emps = array();
		$sql = "SELECT emp_id, en_name, th_name FROM ".$cid."_employees WHERE emp_status = '1' AND emp_type < 4 ORDER BY emp_id ASC";
		if($res = $dbc->query($sql)){
			while($row = $res->fetch_assoc()){
				if(!in_array($row['emp_id'], $existing_emps)){
					$missing_emps[$row['emp_id']] = $row[$_SESSION['rego']['lang'].'_name'];
				}
			}
		}
		return $missing_emps;
	}

	function getEmptyResultColumns($fix_allow, $var_allow){
		global $dbc;

		$fix_deduct = getUsedFixDeduction($_SESSION['rego']['lang']);
		$var_deduct = getUsedVarDeduction($_SESSION['rego']['lang']);

		

		$ecol= array();
		$sql = "
			SELECT 
			SUM(ot1b)as ot1b, 
			SUM(ot15b)as ot15b, 
			SUM(ot2b)as ot2b, 
			SUM(ot3b)as ot3b, 
			SUM(ootb)as ootb, "; 
			
			foreach($fix_allow as $k=>$v){
				$sql .= "SUM(fix_allow_".$k.")as fix_allow_".$k.", ";
			}
			foreach($var_allow as $k=>$v){
				$sql .= "SUM(var_allow_".$k.")as var_allow_".$k.", ";
			}

			foreach($fix_deduct as $k=>$v){
				$sql .= "SUM(fix_deduct_".$k.")as fix_deduct_".$k.", ";
			}
			foreach($var_deduct as $k=>$v){
				$sql .= "SUM(var_deduct_".$k.")as var_deduct_".$k.", ";
			}
		
		$sql .= "	
			SUM(tax_by_company)as tax_by_company, 
			SUM(sso_by_company)as sso_by_company, 
			SUM(other_income)as other_income, 
			SUM(severance)as severance, 
			SUM(remaining_salary)as remaining_salary, 
			SUM(notice_payment)as notice_payment, 
			SUM(paid_leave)as paid_leave, 
			
			SUM(absence_b)as absence_b, 
			SUM(late_early_b)as late_early_b,
			SUM(leave_wop_b)as leave_wop_b, 
			SUM(tot_deduct_before)as tot_deduct_before, 
			
			SUM(gross_income)as gross_income, 
			
			SUM(tot_deduct_after)as tot_deduct_after, 
			SUM(pvf_employee)as pvf_employee, 
			SUM(psf_employee)as psf_employee, 
			SUM(social)as social, 
			SUM(tax_month)as tax_month, 
			SUM(advance)as advance, 
			SUM(legal_deductions)as legal_deductions, 
			
			SUM(net_income)as net_income  

			FROM ".$_SESSION['rego']['payroll_dbase']." 
			WHERE month = ".$_SESSION['rego']['cur_month']." AND emp_group = '".$_SESSION['rego']['emp_group']."' AND entity IN (".$_SESSION['rego']['selpr_entities'].") ";
		
		if($res = $dbc->query($sql)){
			$row = $res->fetch_assoc();
		}else{
			echo mysqli_error($dbc);
		}
		
		$nr = 8;
		if($row['ot1b']==0){$ecol[$nr] = $nr;}; $nr++;
		if($row['ot15b']==0){$ecol[$nr] = $nr;}; $nr++;
		if($row['ot2b']==0){$ecol[$nr] = $nr;}; $nr++;
		if($row['ot3b']==0){$ecol[$nr] = $nr;}; $nr++;
		if($row['ootb']==0){$ecol[$nr] = $nr;}; $nr++;
		//if($total_otb==0){$ecol[$nr] = $nr;}; $nr++;
		
		foreach($fix_allow as $k=>$v){
			if($row['fix_allow_'.$k]==0){$ecol[$nr] = $nr;}; $nr++;
		}
		foreach($var_allow as $k=>$v){
			if($row['var_allow_'.$k]==0){$ecol[$nr] = $nr;}; $nr++;
		}
		
		if($row['tax_by_company']==0){$ecol[$nr] = $nr;}; $nr++;
		if($row['sso_by_company']==0){$ecol[$nr] = $nr;}; $nr++;
		if($row['other_income']==0){$ecol[$nr] = $nr;}; $nr++;
		if($row['severance']==0){$ecol[$nr] = $nr;}; $nr++;
		if($row['remaining_salary']==0){$ecol[$nr] = $nr;}; $nr++;
		if($row['notice_payment']==0){$ecol[$nr] = $nr;}; $nr++;
		if($row['paid_leave']==0){$ecol[$nr] = $nr;}; $nr++;
		$nr++;
		if($row['absence_b']==0){$ecol[$nr] = $nr;}; $nr++;
		if($row['leave_wop_b']==0){$ecol[$nr] = $nr;}; $nr++;
		if($row['late_early_b']==0){$ecol[$nr] = $nr;}; $nr++;

		foreach($fix_deduct as $k=>$v){
			if($row['fix_deduct_'.$k]==0){$ecol[$nr] = $nr;}; $nr++;
		}
		foreach($var_deduct as $k=>$v){
			if($row['var_deduct_'.$k]==0){$ecol[$nr] = $nr;}; $nr++;
		}

		//if($row['tot_deduct_before']==0){$ecol[$nr] = $nr;}; $nr++;
		//if($row['gross_income']==0){$ecol[$nr] = $nr;}; 
		//if($row['tot_deduct_after']==0){$ecol[$nr] = $nr;}; $nr++;


		//if($row['tot_absence']==0){$ecol[$nr] = $nr;}; $nr++;
		
		if($row['pvf_employee']==0){$ecol[$nr] = $nr;}; $nr++;
		if($row['psf_employee']==0){$ecol[$nr] = $nr;}; $nr++;
		if($row['social']==0){$ecol[$nr] = $nr;}; $nr++;
		if($row['tax_month']==0){$ecol[$nr] = $nr;}; $nr++;
		
		if($row['advance']==0){$ecol[$nr] = $nr;}; $nr++;
		if($row['legal_deductions']==0){$ecol[$nr] = $nr;}; $nr++;
		//if($row['net_income']==0){$ecol[$nr] = $nr;}; $nr++;
		
		return $ecol;
	}

	/*function xxxgetEmptyResultColumns($dbc, $fix_allow, $var_allow){
		$ecol= array();
		$sql = "SELECT * FROM ".$_SESSION['rego']['payroll_dbase']." WHERE month = ".$_SESSION['rego']['cur_month'];
		$salary = 0; $bonus = 0;
		$ot1b = 0; $ot15b = 0; $ot2b = 0; $ot3b = 0; $ootb = 0; $total_otb = 0;
		$absence = 0; $leave_wop = 0; $late_early = 0; 
		$fallow = array();
		foreach($fix_allow as $k=>$v){
			$fallow[$k] = 0;
		}
		$vallow = array();
		foreach($var_allow as $k=>$v){
			$vallow[$k] = 0;
		}
		$tot_allow = 0;
		$other_income = 0; $severance = 0; $legal_deduct = 0; $remaining_salary = 0; $notice_payment = 0; $paid_leave = 0;
		$uniform = 0; $deduct_3 = 0; $pvf = 0; $ssf = 0; $tax = 0; $tot_absence = 0; $tot_deduct = 0;
		$gross = 0; $net = 0; $advance = 0; 
		
		if($res = $dbc->query($sql)){
			while($row = $res->fetch_assoc()){
				$salary += $row['salary'];
				//$bonus += $row['bonus'];
				
				$ot1b += $row['ot1b'];
				$ot15b += $row['ot15b'];
				$ot2b += $row['ot2b'];
				$ot3b += $row['ot3b'];
				$ootb += $row['ootb'];
				$total_otb += $row['total_otb'];
				
				$absence += $row['absence_b'];
				$leave_wop += $row['leave_wop_b'];
				$late_early += $row['late_early_b'];

				foreach($fix_allow as $k=>$v){
					$fallow[$k] += $row['fix_allow_'.$k];
				}
				foreach($var_allow as $k=>$v){
					$vallow[$k] += $row['var_allow_'.$k];
				}
				$tot_allow += $row['total_tax_allow'];
				
				$other_income += $row['other_income'];
				$severance += $row['severance'];
				$remaining_salary += $row['remaining_salary'];
				$notice_payment += $row['notice_payment'];
				$paid_leave += $row['paid_leave'];
				
				//$uniform += $row['uniform'];
				//$deduct_3 += $row['deduct_3'];
				
				$pvf += $row['pvf_employee'];
				$ssf += $row['social'];
				$tax += $row['tax'];
				$tot_absence += $row['tot_absence'];
				$tot_deduct += $row['tot_deductions'];
				
				$gross += $row['gross_income'];
				$net += $row['net_income'];
				$advance += $row['advance'];
				$legal_deduct += $row['legal_deductions'];
			}
		}
		$nr = 6;
		if($salary==0){$ecol[$nr] = $nr;}; $nr++;
		if($bonus==0){$ecol[$nr] = $nr;}; $nr++;
		
		if($ot1b==0){$ecol[$nr] = $nr;}; $nr++;
		if($ot15b==0){$ecol[$nr] = $nr;}; $nr++;
		if($ot2b==0){$ecol[$nr] = $nr;}; $nr++;
		if($ot3b==0){$ecol[$nr] = $nr;}; $nr++;
		if($ootb==0){$ecol[$nr] = $nr;}; $nr++;
		if($total_otb==0){$ecol[$nr] = $nr;}; $nr++;
		
		if($absence==0){$ecol[$nr] = $nr;}; $nr++;
		if($leave_wop==0){$ecol[$nr] = $nr;}; $nr++;
		if($late_early==0){$ecol[$nr] = $nr;}; $nr++;
		
		foreach($fallow as $v){
			if($v==0){$ecol[$nr] = $nr;}; $nr++;
		}
		foreach($vallow as $v){
			if($v==0){$ecol[$nr] = $nr;}; $nr++;
		}
		if($tot_allow==0){$ecol[$nr] = $nr;}; $nr++;
		
		if($other_income==0){$ecol[$nr] = $nr;}; $nr++;
		if($severance==0){$ecol[$nr] = $nr;}; $nr++;
		if($remaining_salary==0){$ecol[$nr] = $nr;}; $nr++;
		if($notice_payment==0){$ecol[$nr] = $nr;}; $nr++;
		if($paid_leave==0){$ecol[$nr] = $nr;}; $nr++;
		
		if($uniform==0){$ecol[$nr] = $nr;}; $nr++;
		if($deduct_3==0){$ecol[$nr] = $nr;}; $nr++;
		if($pvf==0){$ecol[$nr] = $nr;}; $nr++;
		if($ssf==0){$ecol[$nr] = $nr;}; $nr++;
		if($tax==0){$ecol[$nr] = $nr;}; $nr++;
		if($tot_absence==0){$ecol[$nr] = $nr;}; $nr++;
		if($tot_deduct==0){$ecol[$nr] = $nr;}; $nr++;
		
		if($gross==0){$ecol[$nr] = $nr;}; $nr++;
		if($advance==0){$ecol[$nr] = $nr;}; $nr++;
		if($legal_deduct==0){$ecol[$nr] = $nr;}; $nr++;
		if($net==0){$ecol[$nr] = $nr;}; $nr++;
		
		return $ecol;
	}*/

	function taxFomGross($income, $deductions){
		global $rego_settings;
		$taxable = $income - $deductions;
		$rules = unserialize($rego_settings['taxrules']);	
		foreach($rules as $k=>$v){
			$from[$k] = $v['from'];
			$to[$k] = $v['to'];
			$per[$k] = 	$v['percent'];
			$net[$k] = 0;
		}
		$last = max(array_keys($net));
		$pgross = 0;
		foreach($to as $k=>$v){
			if($k == 0){
				if($taxable > $v){
					$gross[$k] = (int)$v;
				}else{
					$gross[$k] = $taxable;
				}
				$pgross += $gross[$k];
			}else if($k == $last){
				if($taxable > $from[$k]){
					$gross[$k] = $taxable - $pgross;
				}else{
					$gross[$k] = 0;
				}
			}else{
				if($taxable < $from[$k]){
					$gross[$k] = 0;
				}else if($taxable > $v){
					$gross[$k] = $v - $pgross;
				}else{
					$gross[$k] = $taxable - $pgross;
				}
				$pgross += $gross[$k];
			}
		}
		$tax_year = 0;
		foreach($gross as $k=>$v){
			$tax[$k] = $gross[$k] * ($per[$k]/100);
			$tax_year += $tax[$k];
		}
		//$data['gross'] = $gross;
		//$data['percent'] = $per;
		//$data['tax'] = $tax;
		
		$data['income'] = $income;
		$data['taxable'] = $taxable;
		$data['tax_year'] = $tax_year;
		$data['tot_gross'] = array_sum($gross);
		if($data['tot_gross'] > 0){
			$data['percent_tax'] = ($tax_year / $data['tot_gross']) * 100;
		}else{
			$data['percent_tax'] = 0;
		}
		$data['net_year'] = $income - $tax_year;
		$data['gross_month'] = $income / 12;
		$data['tax_month'] = $tax_year / 12;
		$data['net_month'] = $data['net_year'] / 12;

		return $data;
	}
	
	function taxFomNet($income, $deductions){
		global $rego_settings;
		$taxable = $income - $deductions;
		$rules = unserialize($rego_settings['taxrules']);	
		foreach($rules as $k=>$v){
			$from[$k] = $v['net_from'];
			$to[$k] = $v['net_to'];
			$per[$k] = 	$v['percent'];
			$net[$k] = 0;
		}
		$last = max(array_keys($net));
		$pnet = 0;
		foreach($to as $k=>$v){
			if($k == 0){
				if($taxable > $v){
					$net[$k] = (int)$v;
				}else{
					$net[$k] = $taxable;
				}
				$pnet += $net[$k];
			}else if($k == $last){
				if($taxable > $from[$k]){
					$net[$k] = $taxable - $pnet;
				}else{
					$net[$k] = 0;
				}
			}else{
				if($taxable < $from[$k]){
					$net[$k] = 0;
				}else if($taxable > $v){
					$net[$k] = $v-$pnet;
				}else{
					$net[$k] = $taxable - $pnet;
				}
				$pnet += $net[$k];
			}
		}
		
		$gross_year = 0;
		foreach($net as $k=>$v){
			$rate = 100 - $per[$k];
			$gross[$k] = $net[$k] / ($rate/100);
			$gross_year += $gross[$k];
		}
		
		$tax_year = 0;
		foreach($gross as $k=>$v){
			$tax[$k] = $gross[$k] * ($per[$k]/100);
			$tax_year += $tax[$k];
		}
		
		//$data['gross'] = $gross;
		//$data['percent'] = $per;
		//$data['tax'] = $tax;
		
		$data['income'] = $income;
		$data['taxable'] = $taxable;
		$data['tax_year'] = $tax_year;
		$data['tot_gross'] = array_sum($gross) + $deductions;
		$data['percent_tax'] = ($tax_year / array_sum($gross)) * 100;
		
		$data['net_year'] = $data['tot_gross'] - $deductions;
		$data['gross_month'] = $data['tot_gross'] / 12;
		$data['tax_month'] = $tax_year / 12;
		$data['net_month'] = $data['gross_month'] - $data['tax_month'];
		
		return $data;
	}

	function calculateAnualTax($taxable, $calc, $sso, $pvf){
		global $rego_settings;
		$rules = unserialize($rego_settings['taxrules']);	
		if($calc == 'gross'){ // Tax from GROSS /////////////////////////////////////
			foreach($rules as $k=>$v){
				$from[$k] = $v['from'];
				$to[$k] = $v['to'];
				$per[$k] = 	$v['percent'];
				$net[$k] = 0;
			}
			$last = max(array_keys($net));
			$pgross = 0;
			foreach($to as $k=>$v){
				if($k == 0){
					if($taxable > $v){
						$gross[$k] = (int)$v;
					}else{
						$gross[$k] = $taxable;
					}
					$pgross += $gross[$k];
				}else if($k == $last){
					if($taxable > $from[$k]){
						$gross[$k] = $taxable - $pgross;
					}else{
						$gross[$k] = 0;
					}
				}else{
					if($taxable < $from[$k]){
						$gross[$k] = 0;
					}else if($taxable > $v){
						$gross[$k] = $v - $pgross;
					}else{
						$gross[$k] = $taxable - $pgross;
					}
					$pgross += $gross[$k];
				}
			}
			$tax_year = 0;
			foreach($gross as $k=>$v){
				$tax[$k] = $gross[$k] * ($per[$k]/100);
				$tax_year += $tax[$k];
			}
		}else{ // Tax from NET ///////////////////////////////////////
			foreach($rules as $k=>$v){
				$from[$k] = $v['net_from'];
				$to[$k] = $v['net_to'];
				$per[$k] = 	$v['percent'];
				$net[$k] = 0;
			}
			$last = max(array_keys($net));
			$pnet = 0;
			$taxable += $sso;
			//$taxable += $pvf;
			foreach($to as $k=>$v){
				if($k == 0){
					if($taxable > $v){
						$net[$k] = (int)$v;
					}else{
						$net[$k] = $taxable;
					}
					$pnet += $net[$k];
				}else if($k == $last){
					if($taxable > $from[$k]){
						$net[$k] = $taxable - $pnet;
					}else{
						$net[$k] = 0;
					}
				}else{
					if($taxable < $from[$k]){
						$net[$k] = 0;
					}else if($taxable > $v){
						$net[$k] = $v-$pnet;
					}else{
						$net[$k] = $taxable - $pnet;
					}
					$pnet += $net[$k];
				}
			}
			$gross_year = 0;
			foreach($net as $k=>$v){
				$rate = 100 - $per[$k];
				$gross[$k] = $net[$k] / ($rate/100);
				$gross_year += $gross[$k];
			}
			
			$tax_year = 0;
			foreach($gross as $k=>$v){
				$tax[$k] = $gross[$k] * ($per[$k]/100);
				$tax_year += $tax[$k];
			}
		}
		
		return $tax_year;
	}

	function getPayslipData($id, $month, $lang, $rate){
		global $dbc;
		global $months;
		global $positions;
		global $compinfo;
		global $sys_settings;
		global $lng;
		
		$fix_allow = getUsedFixAllow($lang);
		$var_allow = getUsedVarAllow($lang);
	
		$fix_deduct = unserialize($sys_settings['fix_deduct']);
		$var_deduct = unserialize($sys_settings['var_deduct']);
		$payslip_field = unserialize($sys_settings['payslip_field']);

		$res = $dbc->query("SELECT * FROM ".$_SESSION['rego']['payroll_dbase']." WHERE emp_id = '".$id."' AND month = '".$month."'");
		$row = $res->fetch_assoc();
		
		$data['emp_id'] = $row['emp_id'];
		$data['account'] = $row['account'];
		$data['bank'] = $row['bank'];

		if($payslip_field['show_empdata'] == 1){

			$res1 = $dbc->query("SELECT * FROM ".$_SESSION['rego']['cid']."_employees WHERE emp_id = '".$row['emp_id']."'");
			$row1 = $res1->fetch_assoc();
			
			$data['name_en'] = $row1['en_name'];
			$data['name_th'] = $row1['th_name'];
			$data['position'] = $positions[$row1['position']][$_SESSION['rego']['lang']];

		}else{

			$data['name_en'] = $row['emp_name_en'];
			$data['name_th'] = $row['emp_name_th'];
			$data['position'] = $positions[$row['position']][$_SESSION['rego']['lang']];
			
		}


		$bsalary = $row['basic_salary'];
		$day_rate = number_format(($bsalary/30),2);
		$hour_rate = number_format((($bsalary/30)/8),2);
		switch($rate){
			case 'em': $data['earnings']['rate'] = array($lng['Rate'],'',''); break;
			case 'dr': $data['earnings']['rate'] = array($lng['Rate'].' '.'('.$lng['day'].')',$day_rate,''); break;
			case 'hr': $data['earnings']['rate'] = array($lng['Rate'].' '.'('.$lng['hour'].')',$hour_rate,''); break;
			default  : $data['earnings']['rate'] = array($lng['Rate'],'',''); break;
		}
		$data['earnings']['salary'] = array($lng['Salary'],number_format($row['paid_days'],2),number_format($row['salary'],2));

		if(!empty($row['ot1b'])){$data['earnings']['OT 1'] = array($lng['OT 1'],$row['ot1h'],number_format($row['ot1b'],2));}
		if(!empty($row['ot15b'])){$data['earnings']['OT 1.5'] = array($lng['OT 1.5'],$row['ot15h'],number_format($row['ot15b'],2));}
		if(!empty($row['ot2b'])){$data['earnings']['OT 2'] = array($lng['OT 2'], $row['ot2h'],number_format($row['ot2b'],2));}
		if(!empty($row['ot3b'])){$data['earnings']['OT 3'] = array($lng['OT 3'],$row['ot3h'],number_format($row['ot3b'],2));}
		if(!empty($row['ootb'])){$data['earnings']['Other OT'] = array($lng['Other OT'],'',number_format($row['ootb'],2));}
		
		foreach($fix_allow as $k=>$v){
			if($row['fix_allow_'.$k] > 0){
				$data['earnings']['fallow_'.$k] = array($v,'',number_format($row['fix_allow_'.$k],2));
			}
		}
		foreach($var_allow as $k=>$v){
			if($row['var_allow_'.$k] > 0){
				$data['earnings']['vallow_'.$k] = array($v,'',number_format($row['var_allow_'.$k],2));
			}
		}
		
		if($row['other_income'] > 0){ $data['earnings']['other_income'] = array($lng['Other income'],'',number_format($row['other_income'],2));}
		if($row['severance'] > 0){ $data['earnings']['severance'] = array($lng['Severance'],'',number_format($row['severance'],2));}
		if($row['remaining_salary'] > 0){ $data['earnings']['remaining_salary'] = array($lng['Remaining salary'],'',number_format($row['remaining_salary'],2));}
		if($row['notice_payment'] > 0){ $data['earnings']['notice_payment'] = array($lng['Notice payment'],'',number_format($row['notice_payment'],2));}
		if($row['paid_leave'] > 0){ $data['earnings']['paid_leave'] = array($lng['Paid leave'],'',number_format($row['paid_leave'],2));}
		
		if($row['tax_by_company'] > 0){ $data['earnings']['tax_by_company'] = array($lng['Tax by company'],'',number_format($row['tax_by_company'],2));}
		
		if($row['sso_by_company'] > 0){ $data['earnings']['sso_by_company'] = array($lng['SSO by company'],'',number_format($row['sso_by_company'],2));}
		
		if($row['pvf_employee'] > 0){ $data['deduct']['pvf'] = array($lng['Provident fund'],'PVF',number_format($row['pvf_employee'],2));}
		if($row['psf_employee'] > 0){ $data['deduct']['psf'] = array($lng['Pension fund'],'PSF',number_format($row['psf_employee'],2));}
		$data['deduct']['ssf'] = array($lng['Social security fund'],'SSF',number_format($row['social'],2));
		$data['deduct']['tax'] = array($lng['Tax'],'PIT',number_format($row['tax_month'],2));

		foreach($fix_deduct as $k=>$v){
			//if($v['tax'] == 1){
				if($row['fix_deduct_'.$k] > 0){
					$data['deduct']['deduct_'.$k] = array($v[$lang],'',number_format($row['fix_deduct_'.$k],2));
				}
			//}
		}
		foreach($var_deduct as $k=>$v){
			//if($v['tax'] == 1){
				if($row['var_deduct_'.$k] > 0){
					$data['deduct']['deduct_'.$k] = array($v[$lang],'',number_format($row['var_deduct_'.$k],2));
				}
			//}
		}
		
		if($row['advance'] > 0){ $data['deduct']['advance'] = array($lng['Advance'],'',number_format($row['advance'],2));}
		if($row['legal_deductions'] > 0){ $data['deduct']['legal_deductions'] = array($lng['Legal deductions'],'',number_format($row['legal_deductions'],2));}
		
		if($row['absence_b'] > 0){ $data['deduct']['absence'] = array($lng['Absence'],$row['absence'],number_format($row['absence_b'],2));}
		if($row['leave_wop_b'] > 0){ $data['deduct']['leave_wop'] = array($lng['Leave WOP'],$row['leave_wop'],number_format($row['leave_wop_b'],2));}
		if($row['late_early_b'] > 0){ $data['deduct']['late_early'] = array($lng['Late Early'],$row['late_early'],number_format($row['late_early_b'],2));}
		
		foreach($fix_deduct as $k=>$v){
			if($v['tax'] == 0){
				if($row['fix_deduct_'.$k] > 0){
					$data['deduct']['deduct_'.$k] = array($v[$lang],'', number_format($row['fix_deduct_'.$k],2));
				}
			}
		}
		foreach($var_deduct as $k=>$v){
			if($v['tax'] == 0){
				if($row['var_deduct_'.$k] > 0){
					$data['deduct']['deduct_'.$k] = array($v[$lang],'', number_format($row['var_deduct_'.$k],2));
				}
			}
		}
		
		
		
		
		$data['gross_income'] = number_format($row['gross_income'],2);
		$data['tot_deductions'] = number_format($row['tot_deductions'],2);
		$data['net_income'] = number_format($row['net_income'],2);
		
		$acc = getAccumulated($id, $month);
		$data['asalary'] = number_format($acc['salary'],2);
		$data['aprovfund'] = number_format($acc['provfund'],2);
		$data['asocial'] = number_format($acc['social'],2);
		$data['atax'] = number_format($acc['tax'],2);
		$data['aother'] = number_format($acc['other'],2);
		$data['period'] = $months[(int)$row['month']].' '.$_SESSION['rego']['year_'.$lang];
		
		return $data;
	}
	
	function getAccumulated($id, $month){
		global $dbc;
		$res = $dbc->query("SELECT * FROM ".$_SESSION['rego']['payroll_dbase']." WHERE emp_id = '".$id."' AND month <= '".$month."'");
		$acc['salary'] = 0; $acc['provfund'] = 0; $acc['social'] = 0; $acc['tax'] = 0; $acc['other'] = 0;
		while($row = $res->fetch_assoc()){
			//$acc['salary'] += $row['gross_income'];
			//$acc['salary'] += $row['gross_income'];
			if($row['ytd_income'] > 0) { 
				$ytd = $row['ytd_income'];
			}else{
				$ytd = $row['gross_income'];
			}
			$acc['salary'] += $ytd;
			$acc['provfund'] += $row['pvf_employee'];
			$acc['provfund'] += $row['psf_employee'];
			$acc['social'] += $row['social'];
			$acc['tax'] += $row['tax_month'];
			$acc['other'] += $row['pvf_employee'] + $row['pvf_employer'] + $row['psf_employee'] + $row['psf_employer'];
		}
		return $acc;
	}

	function getUsedPayrollColumns($dbc){
		$data = array();
		$sql = "SELECT * FROM ".$_SESSION['rego']['payroll_dbase']." WHERE month = ".$_SESSION['rego']['curr_month'];
		if($res = $dbc->query($sql)){
			while($row = $res->fetch_assoc()){

				if($row['absence'] > 0){$data['AB']['absence'] = 1;}
				if($row['leave_wop'] > 0){$data['AB']['leave_wop'] = 1;}
				if($row['late_early'] > 0){$data['AB']['late_early'] = 1;}
				if($row['leave_wp'] > 0){$data['AB']['leave_wp'] = 1;}
				
				//if($row['uniform'] > 0){$data['DD']['uniform'] = 1;}
				//if($row['deduct_2'] > 0){$data['DD']['deduct_2'] = 1;}
				//if($row['deduct_3'] > 0){$data['DD']['deduct_3'] = 1;}

				for($i=1; $i<=10; $i++){
					if($row['fix_allow_'.$i] > 0){$data['FA'][$i] = 1;}
				}
				for($i=1; $i<=10; $i++){
					if($row['var_allow_'.$i] > 0){$data['VA'][$i] = 1;}
				}
				
				if($row['other_income'] > 0){$data['OI']['other_income'] = 1;}
				if($row['severance'] > 0){$data['OI']['severance'] = 1;}
				//if($row['bonus'] > 0){$data['OI']['bonus'] = 1;}
				if($row['ootb'] > 0){$data['OI']['ootb'] = 1;}
				
			}
		}
		//$ucol = array_filter($ucol);
		return $data; exit;
	}

	function getFormNamePosition($cid){
		global $dbc;
		$data['name'] = '';
		$data['position'] = '';
		$sql = "SELECT form_name, form_position FROM ".$cid."_payroll_months WHERE month = '".$_SESSION['rego']['cur_year'].'_'.$_SESSION['rego']['cur_month']."'";
		if($res = $dbc->query($sql)){
			if($row = $res->fetch_assoc()){
				$data['name'] = $row['form_name'];
				$data['position'] = $row['form_position'];
			}
		}
		return $data;
	}

	function checkDataForGovForms($entity){
		global $dbc;
		global $lng;
		global $lang;
		$check = '';
		$msg = '';
		$caret = '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <i class="fa fa-caret-right"></i>&nbsp ';
		if($res = $dbc->query("SELECT * FROM ".$_SESSION['rego']['cid']."_entities_data WHERE ref = $entity")){
			if($row = $res->fetch_assoc()){
				if(empty($row['th_compname'])){$msg .= $caret.$lng['Company name in Thai'].'<br>';}
				if(empty($row['en_compname'])){$msg .= $caret.$lng['Company name in English'].'<br>';}
				if(empty($row['tax_id'])){$msg .= $caret.$lng['Company Tax ID'].'<br>';}
				if(empty($row['revenu_branch'])){$msg .= $caret.'Company Revenu branch'.'<br>';}
				if(empty($row['sso_account'])){$msg .= $caret.'Company SSO account'.'<br>';}
				if(empty($row['th_address'])){$msg .= $caret.'Address in Thai (PDF Forms)'.'<br>';}
				$addr = unserialize($row['th_addr_detail']);
				if(empty($addr['number'])){$msg .=  $caret.$lng['Address details'].' - '.$lng['Number'].'<br>';}
				if(empty($addr['moo'])){$msg .= $caret.$lng['Address details'].' - '.$lng['Moo'].'<br>';}
				//if(empty($addr['lane'])){$msg .= $caret.'Thai address detail - Lane<br>';}
				//if(empty($addr['subdistrict'])){$msg .= $caret.$lng['Address details'].' - '.$lng['Sub district'].'<br>';}
				if(empty($addr['district'])){$msg .= $caret.$lng['Address details'].' - '.$lng['District'].'<br>';}
				if(empty($addr['province'])){$msg .= $caret.$lng['Address details'].' - '.$lng['Province'].'<br>';}
				if(empty($addr['postal'])){$msg .= $caret.$lng['Address details'].' - '.$lng['Postal code'].'<br>';}
				$tmp = unserialize($row['sso_codes']);
				if(empty($tmp[1]['code'])){$msg .=  $caret.'SSO Code<br>';}
				if(empty($tmp[1]['th'])){$msg .= $caret.'SSO Name Thai<br>';}
				if(empty($tmp[1]['line1_th'])){$msg .= $caret.'SSO address Thai<br>';}
				if(empty($tmp[1]['line2_th'])){$msg .= $caret.'SSO District & Province Thai<br>';}
				if(empty($tmp[1]['postal_th'])){$msg .= $caret.'SSO Postal code Thai<br>';}
				//if(empty($row['banks'])){$msg .= $caret.'No bank selected'.'<br>';}
			}
		}
		if(!empty($msg)){$check .= '<i class="fa fa-arrow-circle-right"></i>&nbsp; <b><a href="'.ROOT.'settings/index.php?mn=6011&id=1">'.'Entity settings'.'</a></b><br>'.$msg.'<div style="height:5px"></div>';}
		
		/*$missing = array();
		if($res = $dbc->query("SELECT * FROM ".$_SESSION['rego']['cid']."_settings")){
			if($row = $res->fetch_assoc()){
				if(empty($row['sso_idnr'])){$missing[] = $caret.$lng['Social Security Fund'].' No.<br>';}
				//if(!empty($row['pr_startdate'])){$missing[] = $caret.$lng['Payroll startdate'].'<br>';}
			}
		}
		if($missing){
			$msg .= '<b>&nbsp; <i class="fa fa-arrow-circle-right"></i>&nbsp; <a href="'.ROOT.'settings/index.php?mn=602">System '.$lng['Settings'].'</a></b><br>';
			foreach($missing as $v){
				$msg .= $v;
			}
		}*/
		
		$msg = '';
		$sql = "SELECT emp_id, title, firstname, lastname, th_name, en_name, base_salary, tax_id, joining_date FROM ".$_SESSION['rego']['emp_dbase']." WHERE emp_status <= 4 AND emp_group = '".$_SESSION['rego']['emp_group']."' ORDER BY emp_id ASC";
		if($res = $dbc->query($sql)){
			if($res->num_rows > 0){
				while($row = $res->fetch_assoc()){
					$msg = '';
					$name = $row[$lang.'_name'];
					if($lang == 'en'){
						if(empty($name)){$name = 'no Name';}
					}else{
						if(empty($name)){$name = 'ไม่มีชื่อ';}
					}
					if(empty($row['title'])){
						$msg .= $caret.$lng['Title'].'<br>';
					}
					if(empty($row['firstname'])){
						$msg .= $caret.$lng['First name'].'<br>';
					}
					if(empty($row['lastname'])){
						$msg .= $caret.$lng['Last name'].'<br>';
					}
					if(empty($row['tax_id'])){
						$msg .= $caret.$lng['Tax ID no.'].'<br>';
					}
					if(empty($row['base_salary'])){
						$msg .= $caret.$lng['Basic salary'].'<br>';
					}
					if(empty($row['joining_date'])){
						$msg .= $caret.$lng['Joining date'].'<br>';
					}
					if(!empty($msg)){
						$check .= '<b>&nbsp; <i class="fa fa-arrow-circle-right"></i>&nbsp; '.$row['emp_id'].' - <a href="'.ROOT.'employees/index.php?mn=1021&id='.$row['emp_id'].'">'.$name.'</a></b><br>'.$msg.'<div style="height:5px"></div>';
					}
				}
			}else{
				$check .= '<b>&nbsp; <i class="fa fa-arrow-circle-right"></i>&nbsp;&nbsp;<a href="index.php?mn=101">'.$lng['Employee register is empty'].'</a></b>';
			}
		}
		return $check; exit;
	}

	function getPND1attach($dbname,$month,$entity){
		global $dbc;
		global $title;
		global $lang;
		$pattern = '%-%%%%-%%%%%-%%-%';
		$data = array();

		$data['d'] = array();
		$data['d2'] = array();

		//-----------for PND1 40(1)------------//
		if($res = $dbc->query("SELECT * FROM ".$_SESSION['rego']['payroll_dbase']." WHERE month = '".$month."' AND entity IN (".$entity.") AND emp_group = '".$_SESSION['rego']['emp_group']."' AND calc_tax = 1 ORDER by emp_id ASC")){
			$nr=1; $tot_income = 0; $tot_tax = 0;
			while($row = $res->fetch_assoc()){
				$empinfo = getEmployeeInfo($_SESSION['rego']['cid'], $row['emp_id']);

				if($empinfo['calc_tax'] != 2){
					if($row['calc_tax']){
						$data['d'][$nr]['emp_id'] = $row['emp_id'];
						$data['d'][$nr]['title'] = $empinfo['title'];
						$data['d'][$nr]['en_name'] = $empinfo['en_name'];
						$data['d'][$nr]['th_name'] = $empinfo['th_name'];
						$data['d'][$nr]['firstname'] = $empinfo['firstname'];
						$data['d'][$nr]['lastname'] = $empinfo['lastname'];
						$data['d'][$nr]['idcard_nr'] = $empinfo['idcard_nr'];
						$data['d'][$nr]['tax_id'] = $empinfo['tax_id'];	
						if(strlen($empinfo['idcard_nr']) == 13){
							$data['d'][$nr]['idcard_nr'] = vsprintf(str_replace('%', '%s', $pattern), str_split($empinfo['idcard_nr']));
						}
						if(strlen($empinfo['tax_id']) == 13){
							$data['d'][$nr]['tax_id'] = vsprintf(str_replace('%', '%s', $pattern), str_split($empinfo['tax_id']));	
						}
						$gross = $row['gross_income'] - $row['tot_absence'] - $row['total_non_allow'];// - $row->tot_absence - $row->tot_deduct_after;
						$data['d'][$nr]['tax'] = number_format($row['tax_month'],2);
						if($row['calc_base'] == 'gross'){
							$data['d'][$nr]['type'] = 1;
						}else{
							$data['d'][$nr]['type'] = 2;
							$gross -= $row['tax_month'];
						}
						$data['d'][$nr]['grossincome'] = number_format($gross,2);

						$tot_income += $gross;
						$tot_tax += $row['tax_month'];
						$nr++;
					}
				}
			}
			$data['tot_income'] =  number_format($tot_income,2);
			$data['tot_tax'] =  number_format($tot_tax,2);
		}

		//-----------for PND1 40(2)------------//
		if($res = $dbc->query("SELECT * FROM ".$_SESSION['rego']['payroll_dbase']." WHERE month = '".$month."' AND entity IN (".$entity.") AND emp_group = '".$_SESSION['rego']['emp_group']."' AND calc_tax = 1 ORDER by emp_id ASC")){
			$nr=1; $tot_income = 0; $tot_tax = 0;
			while($row = $res->fetch_assoc()){
				$empinfo = getEmployeeInfo($_SESSION['rego']['cid'], $row['emp_id']);

				if($empinfo['calc_tax'] == 2){

					if($row['calc_tax']){
						$data['d2'][$nr]['emp_id'] = $row['emp_id'];
						$data['d2'][$nr]['title'] = $empinfo['title'];
						$data['d2'][$nr]['en_name'] = $empinfo['en_name'];
						$data['d2'][$nr]['th_name'] = $empinfo['th_name'];
						$data['d2'][$nr]['firstname'] = $empinfo['firstname'];
						$data['d2'][$nr]['lastname'] = $empinfo['lastname'];
						$data['d2'][$nr]['idcard_nr'] = $empinfo['idcard_nr'];
						$data['d2'][$nr]['tax_id'] = $empinfo['tax_id'];	
						if(strlen($empinfo['idcard_nr']) == 13){
							$data['d2'][$nr]['idcard_nr'] = vsprintf(str_replace('%', '%s', $pattern), str_split($empinfo['idcard_nr']));
						}
						if(strlen($empinfo['tax_id']) == 13){
							$data['d2'][$nr]['tax_id'] = vsprintf(str_replace('%', '%s', $pattern), str_split($empinfo['tax_id']));	
						}
						$gross = $row['gross_income'] - $row['tot_absence'] - $row['total_non_allow'];// - $row->tot_absence - $row->tot_deduct_after;
						$data['d2'][$nr]['tax'] = number_format($row['tax_month'],2);
						if($row['calc_base'] == 'gross'){
							$data['d2'][$nr]['type'] = 1;
						}else{
							$data['d2'][$nr]['type'] = 2;
							$gross -= $row['tax_month'];
						}
						$data['d2'][$nr]['grossincome'] = number_format($gross,2);

						$tot_income += $gross;
						$tot_tax += $row['tax_month'];
						$nr++;
					}
				}
			}
			$data['tot_income2'] =  number_format($tot_income,2);
			$data['tot_tax2'] =  number_format($tot_tax,2);
		}


		return $data;
	}

	function getPND3attach($dbname,$month,$entity){
		global $dbc;
		global $title;
		global $lang;
		$pattern = '%-%%%%-%%%%%-%%-%';
		$data = array();
		$data['d'] = array();
		if($res = $dbc->query("SELECT * FROM ".$_SESSION['rego']['payroll_dbase']." WHERE month = '".$month."' AND entity IN (".$entity.") AND emp_group = '".$_SESSION['rego']['emp_group']."' AND calc_tax = 3 ORDER by emp_id ASC")){
			$nr=1; $tot_income = 0; $tot_tax = 0;
			while($row = $res->fetch_assoc()){
				$empinfo = getEmployeeInfo($_SESSION['rego']['cid'], $row['emp_id']);
				if($row['calc_tax']){
					$data['d'][$nr]['emp_id'] = $row['emp_id'];
					$data['d'][$nr]['title'] = $empinfo['title'];
					$data['d'][$nr]['en_name'] = $empinfo['en_name'];
					$data['d'][$nr]['th_name'] = $empinfo['th_name'];
					$data['d'][$nr]['firstname'] = $empinfo['firstname'];
					$data['d'][$nr]['lastname'] = $empinfo['lastname'];
					$data['d'][$nr]['address'] = $empinfo['reg_address'].' '.$empinfo['sub_district'].' '.$empinfo['district'].' '.$empinfo['province'].' '.$empinfo['postnr'];
					$data['d'][$nr]['idcard_nr'] = $empinfo['idcard_nr'];
					$data['d'][$nr]['tax_id'] = $empinfo['tax_id'];	
					if(strlen($empinfo['idcard_nr']) == 13){
						$data['d'][$nr]['idcard_nr'] = vsprintf(str_replace('%', '%s', $pattern), str_split($empinfo['idcard_nr']));
					}
					if(strlen($empinfo['tax_id']) == 13){
						$data['d'][$nr]['tax_id'] = vsprintf(str_replace('%', '%s', $pattern), str_split($empinfo['tax_id']));	
					}
					$gross = $row['gross_income'];
					
					$data['d'][$nr]['tax'] = number_format($row['tax_month'],2);
					if($row['calc_base'] == 'gross'){
						$data['d'][$nr]['type'] = 1;
					}else{
						$data['d'][$nr]['type'] = 2;
						$gross -= $row['tax_month'];
					}
					$data['d'][$nr]['grossincome'] = number_format($gross,2);
					
					$tot_income += $gross;
					$tot_tax += $row['tax_month'];
					$nr++;
				}
			}
			$data['tot_income'] =  number_format($tot_income,2);
			$data['tot_tax'] =  number_format($tot_tax,2);
		}
		return $data;
	}

	function getSSOactMax($cid){
		global $dbc;
		$data = '';
		$sql = "SELECT sso_act_max FROM ".$cid."_payroll_months WHERE month = '".$_SESSION['rego']['cur_year'].'_'.$_SESSION['rego']['cur_month']."'";
		if($res = $dbc->query($sql)){
			if($row = $res->fetch_assoc()){$data = $row['sso_act_max'];}
		}
		if(empty($data)){$data = 'max';}
		return $data;
	}

	function getSSOEmpRate($cid){
		global $dbc;
		$data = '';
		$sql = "SELECT sso_eRate, sso_eMax, sso_eMin, sso_cRate, sso_cMax, sso_cMin FROM ".$cid."_payroll_months WHERE month = '".$_SESSION['rego']['cur_year'].'_'.$_SESSION['rego']['cur_month']."'";
		if($res = $dbc->query($sql)){
			if($row = $res->fetch_assoc()){

				$data = array(
								"rate" => $row['sso_eRate'],
								"max" => $row['sso_eMax'],
								"min" => $row['sso_eMin'],
								"crate" => $row['sso_cRate'],
								"cmax" => $row['sso_cMax'],
								"cmin" => $row['sso_cMin'],
							);
			}
		}
		
		return $data;
	}

	function getSSOattach($dbname, $month, $lang, $act_max){
		global $dbc;
		global $cid;
		global $rego_settings;
		global $sys_settings;
		global $title;
		$pattern = '%-%%%%-%%%%%-%%-%';
		$title[''] = '? ?';
		//$tax_settings = unserialize($compinfo['pr_settings']);	
		$data = array();
		$data['d'] = array();
		if($res = $dbc->query("SELECT * FROM ".$dbname." WHERE month = '".$month."' AND entity = '".$_SESSION['rego']['gov_entity']."' AND branch = '".$_SESSION['rego']['gov_branch']."' AND emp_group = '".$_SESSION['rego']['emp_group']."' AND social > 0 ORDER by emp_id ASC")){
			$nr=1; $tot_salary = 0; $tot_social = 0;
			while($row = $res->fetch_assoc()){
				$data['d'][$nr]['emp_id'] = $row['emp_id'];
				$empinfo = getEmployeeInfo($cid, $row['emp_id']);
				$fix_allow = 0; 
				for($i=1;$i<=10;$i++){
					$fix_allow += $row['fix_allow_'.$i]; // ????????????????????? from payroll database
				}
				$data['d'][$nr]['title'] = $title[$empinfo['title']];
				$data['d'][$nr]['firstname'] = $empinfo['firstname'];
				$data['d'][$nr]['lastname'] = $empinfo['lastname'];
				$data['d'][$nr]['name'] = $empinfo[$lang.'_name'];
				$data['d'][$nr]['idcard_nr'] = $empinfo['idcard_nr'];
				$data['d'][$nr]['tax_id'] = $empinfo['tax_id'];	
				if(strlen($empinfo['idcard_nr']) == 13){
					$data['d'][$nr]['idcard_nr'] = vsprintf(str_replace('%', '%s', $pattern), str_split($empinfo['idcard_nr']));
				}
				if(strlen($empinfo['tax_id']) == 13){
					$data['d'][$nr]['tax_id'] = vsprintf(str_replace('%', '%s', $pattern), str_split($empinfo['tax_id']));	
				}

				//$basicsalary = $row['salary'] + $fix_allow;
				
				$basicsalary = (($row['salary'] + $fix_allow + $row['remaining_salary']) - ($row['leave_wop_b'] + $row['absence_b'] + $row['late_early_b']) );
				//$basicsalary = $row['gross_income'] - $row['tot_absence'] - $row['total_non_allow'];

				if($act_max == 'max'){
					$basic_salary = ($basicsalary < $rego_settings['sso_min_wage'] ? $rego_settings['sso_min_wage'] : $basicsalary);
					$basic_salary = ($basicsalary > $rego_settings['sso_max_wage'] ? $rego_settings['sso_max_wage'] : $basicsalary);
				}else{
					$basic_salary = $basicsalary;
				}

				$data['d'][$nr]['basic_salary'] = number_format($basic_salary,2);
				$data['d'][$nr]['sso'] = number_format($row['social']);
				
				$tot_salary += $basic_salary; 
				$tot_social += $row['social'];
				$nr++;
			}
			$data['tot_salary'] = number_format($tot_salary,2); 
			$data['tot_social'] = number_format($tot_social);
		}
		return $data;
	}	

	function getPVFattach($dbname,$month,$lang){
		global $dbc;
		global $cid;
		global $title;
		$title[''] = '?';
		$pattern = '%-%%%%-%%%%%-%%-%';
		
		$data = array();
		$data['d'] = array();
		if($res = $dbc->query("SELECT * FROM ".$dbname." WHERE month = '".$month."' AND entity = '".$_SESSION['rego']['gov_entity']."' AND emp_group = '".$_SESSION['rego']['emp_group']."' ORDER by emp_id ASC")){
			$nr=1; $tot_pvf = 0; $total_employee = 0; $total_employer = 0; $total_pvf = 0;
			while($row = $res->fetch_object()){
				if($row->pvf_employee > 0 || $row->pvf_employer > 0){
					$data['d'][$nr]['emp_id'] = $row->emp_id;
					$empinfo = getEmployeeInfo($cid, $row->emp_id);
					$data['d'][$nr]['idcard_nr'] = $empinfo['idcard_nr'];
					$data['d'][$nr]['tax_id'] = $empinfo['tax_id'];
					if(strlen($empinfo['idcard_nr']) == 13){
						$data['d'][$nr]['idcard_nr'] = vsprintf(str_replace('%', '%s', $pattern), str_split($empinfo['idcard_nr']));
					}
					if(strlen($empinfo['tax_id']) == 13){
						$data['d'][$nr]['tax_id'] = vsprintf(str_replace('%', '%s', $pattern), str_split($empinfo['tax_id']));	
					}
					$data['d'][$nr]['title'] = $title[$empinfo['title']];
					$data['d'][$nr]['firstname'] = $empinfo['firstname'];
					$data['d'][$nr]['lastname'] = $empinfo['lastname'];
					$data['d'][$nr]['en_name'] = $empinfo['en_name'];
					$data['d'][$nr]['th_name'] = $empinfo['th_name'];
					$data['d'][$nr]['pvf_employee'] = number_format($row->pvf_employee,2);
					$data['d'][$nr]['pvf_employer'] = number_format($row->pvf_employer,2);
					$tot_pvf = $row->pvf_employee + $row->pvf_employer; 
					$data['d'][$nr]['tot_pvf'] = number_format($tot_pvf,2);
					$total_employee += $row->pvf_employee;
					$total_employer += $row->pvf_employer;
					$nr++;
				}
			}
			$total_pvf = $total_employee + $total_employer;
			$data['total_employee'] = number_format($total_employee,2);
			$data['total_employer'] = number_format($total_employer,2);
			$data['total_pvf'] = number_format($total_pvf,2);
		}
		return $data;
	}	

	function getPND1attachYear($entity){
		global $dbc;
		global $cid;
		global $title;
		$month = $_SESSION['rego']['cur_month'];
		$title[''] = '? ?';
		$pattern = '%-%%%%-%%%%%-%%-%';
		$data = array();
		$values = array();
		$data['rows'] = array();
		
		//if($res = $dbc->query("SELECT * FROM $dbname WHERE month = '".$month."' AND entity = '".$entity."' AND emp_group = '".$_SESSION['rego']['emp_group']."' ORDER by emp_id ASC")){
		
		if($res = $dbc->query("SELECT emp_id, gross_income, tax_month FROM ".$_SESSION['rego']['payroll_dbase']." WHERE entity = '".$entity."' AND emp_group = '".$_SESSION['rego']['emp_group']."' ORDER by emp_id ASC")){
			$nr=1; $tot_income = 0; $tot_tax = 0;
			while($row = $res->fetch_object()){
				$values[$row->emp_id]['gross'] = 0;
				$values[$row->emp_id]['tax'] = 0;
			}
			$res = $dbc->query("SELECT emp_id, gross_income, tax_month FROM ".$_SESSION['rego']['payroll_dbase']." WHERE entity = '".$entity."' AND emp_group = '".$_SESSION['rego']['emp_group']."' ORDER by emp_id ASC");
			while($row = $res->fetch_object()){
				$values[$row->emp_id]['gross'] += $row->gross_income;
				$values[$row->emp_id]['tax'] += $row->tax_month;
			}
			foreach($values as $k=>$v){
				$data['rows'][$nr]['emp_id'] = $k;
				$empinfo = getEmployeeInfo($cid, $k);
				$data['rows'][$nr]['title'] = $title[$empinfo['title']];
				$data['rows'][$nr]['firstname'] = $empinfo['firstname'];
				$data['rows'][$nr]['lastname'] = $empinfo['lastname'];
				$data['rows'][$nr]['name'] = $empinfo[$_SESSION['rego']['lang'].'_name'];
				$data['rows'][$nr]['address'] = $empinfo['reg_address'].' '.$empinfo['sub_district'].' '.$empinfo['district'].' '.$empinfo['province'].' '.$empinfo['postnr'];
				//$data['d'][$nr]['idcard_nr'] = $empinfo['idcard_nr'];
				$data['rows'][$nr]['tax_id'] = $empinfo['tax_id'];
				if(strlen($empinfo['idcard_nr']) == 13){
					$data['rows'][$nr]['idcard_nr'] = vsprintf(str_replace('%', '%s', $pattern), str_split($empinfo['idcard_nr']));
				}
				if(strlen($empinfo['tax_id']) == 13){
					$data['rows'][$nr]['tax_id'] = vsprintf(str_replace('%', '%s', $pattern), str_split($empinfo['tax_id']));	
				}
				//$gross = $row->gross_income - $row->total_non_allow - $row->tot_absence - $row->deduct_2;
				$data['rows'][$nr]['grossincome'] = number_format($v['gross'],2);
				$data['rows'][$nr]['tax'] = number_format($v['tax'],2);
				$tot_income += $v['gross'];
				$tot_tax += $v['tax'];
				$nr++;
			}
			$data['tot_income'] =  number_format($tot_income,2);
			$data['tot_tax'] =  number_format($tot_tax,2);
		}
		return $data;
	}

	function prGetEmployeeInfo($id){
		global $dbc;
		$sql = "SELECT * FROM ".$_SESSION['rego']['emp_dbase']." WHERE emp_id = '".$id."'";
		if($res = $dbc->query($sql)){
			if($row = $res->fetch_assoc()){return $row;}
		}
	}

	function getEmployeeWorkedDays($start_date, $resign_date, $workdays){
		$started = false;
		$resigned = false;
		$worked_days = (int)$workdays;
		$calendar_days = cal_days_in_month(CAL_GREGORIAN, $_SESSION['rego']['cur_month'], $_SESSION['rego']['cur_year']);
		$worked_days = $calendar_days;

		$first = date($_SESSION['rego']['cur_year'].'-'.$_SESSION['rego']['curr_month'].'-01');
		$Ymd = $_SESSION['rego']['cur_year'].'-'.$_SESSION['rego']['curr_month'].'-10';
		$last = date("Y-m-t", strtotime(date($Ymd)));

		$dFirst = new DateTime($first);
		$dLast = new DateTime($last);
		
		$dStart  = new DateTime($start_date);
		$dResign  = false;
		if(!empty($resign_date)){
			$dResign  = new DateTime($resign_date);
		}
		
		if(!$dResign || $dResign > $dLast){
			if($dStart >= $dFirst){
				$dDiff = $dStart->diff($dLast);
				$worked_days = $dDiff->days+1;
				$started = true;
			}
		}
		if($dResign){
			if($dResign >= $dFirst && $dResign <= $dLast){
				$dDiff = $dFirst->diff($dResign);
				$worked_days = $dDiff->days+1;
				$resigned = true;
			}
			if($dStart >= $dFirst && $dResign >= $dFirst && $dResign <= $dLast){
				$dDiff = $dStart->diff($dResign);
				$worked_days = $dDiff->days+1;
				$started = true;
			}
		}
		return array('worked_days'=>$worked_days, 'started'=>$started, 'resigned'=>$resigned, 'calendar_days'=>$calendar_days);
	}

	function getEmptyPayrollColsOLD($f, $t){
		global $dbc;
		$col['ot1b'] = 0; 
		$col['ot15b'] = 0; 
		$col['ot2b'] = 0; 
		$col['ot3b'] = 0; 
		$col['ootb'] = 0; 
		for($i=1;$i<=10;$i++){
			$col['fix_allow_'.$i] = 0;
		}
		for($i=1;$i<=10;$i++){
			$col['var_allow_'.$i] = 0;
		}
		$col['other_income'] = 0;
		$col['severance'] = 0;
		$col['remaining_salary'] = 0; 
		$col['notice_payment'] = 0; 
		$col['paid_leave'] = 0; 
		
		$col['absence_b'] = 0; 
		$col['leave_wop_b'] = 0; 
		$col['late_early_b'] = 0; 
		
		$col['tot_deduct_before'] = 0; 
		$col['tot_deduct_after'] = 0; 
		$col['pvf_employee'] = 0; 
		$col['psf_employee'] = 0; 
		$col['social'] = 0; 
		$col['tax'] = 0; 
		
		$col['advance'] = 0; 
		$col['legal_deductions'] = 0; 
		
		$data = array();
		if($f == $t){
			$res = $dbc->query("SELECT * FROM ".$_SESSION['rego']['payroll_dbase']." WHERE month = '".sprintf("%02d", $f)."'");
		}else{
			$res = $dbc->query("SELECT * FROM ".$_SESSION['rego']['payroll_dbase']." WHERE month >= '".sprintf("%02d", $f)."' AND month <= '".$t."'");
		}

		while($row = $res->fetch_assoc()){
			$data[] = $row;
		}
		if($data){
			foreach($data as $k=>$v){
				$col['ot1b'] += $v['ot1b'];
				$col['ot15b'] += $v['ot15b'];
				$col['ot2b'] += $v['ot2b'];
				$col['ot3b'] += $v['ot3b'];
				$col['ootb'] += $v['ootb'];
				for($i=1;$i<=10;$i++){
					$col['fix_allow_'.$i] += $v['fix_allow_'.$i];
				}
				for($i=1;$i<=10;$i++){
					$col['var_allow_'.$i] += $v['var_allow_'.$i];
				}
				$col['other_income'] += $v['other_income'];
				$col['severance'] += $v['severance'];
				$col['remaining_salary'] += $v['remaining_salary']; 
				$col['notice_payment'] += $v['notice_payment']; 
				$col['paid_leave'] += $v['paid_leave']; 

				$col['absence_b'] += $v['absence_b'];
				$col['leave_wop_b'] += $v['leave_wop_b'];
				$col['late_early_b'] += $v['late_early_b'];
				
				$col['tot_deduct_before'] += $v['tot_deduct_before']; 
				$col['tot_deduct_after'] += $v['tot_deduct_after']; 
				$col['pvf_employee'] += $v['pvf_employee'];
				$col['psf_employee'] += $v['psf_employee'];
				$col['social'] = 99;
				$col['tax'] = 99;
				
				$col['advance'] += $v['advance'];
				$col['legal_deductions'] += $v['legal_deductions']; 
			}
		}
		foreach($col as $k=>$v){
			if($v > 0){
				unset($col[$k]);
			}
		}
		//$col = array_filter($col);
		//$col2 = array_filter($col2);
		//$cols['col'] = $col;
		//$cols['col2'] = $col2;
		return $col;
	}

	function getEmptyPayrollCols($f, $t){
		global $dbc;
		$col['ot1b'] = 0; 
		$col['ot15b'] = 0; 
		$col['ot2b'] = 0; 
		$col['ot3b'] = 0; 
		$col['ootb'] = 0; 

		for($i=1;$i<=10;$i++){
			$col['fix_allow_'.$i] = 0;
		}
		for($i=1;$i<=10;$i++){
			$col['var_allow_'.$i] = 0;
		}

		$col['tax_by_company'] = 0;
		$col['sso_by_company'] = 0;

		$col['other_income'] = 0;
		$col['severance'] = 0;
		$col['remaining_salary'] = 0; 
		$col['notice_payment'] = 0; 
		$col['paid_leave'] = 0; 

		for($i=1;$i<=10;$i++){
			$col['fix_deduct_'.$i] = 0;
		}
		for($i=1;$i<=10;$i++){
			$col['var_deduct_'.$i] = 0;
		}
		
		$col['absence_b'] = 0; 
		$col['leave_wop_b'] = 0; 
		$col['late_early_b'] = 0; 
		
		$col['tot_deduct_before'] = 0; 
		$col['tot_deduct_after'] = 0; 
		$col['pvf_employee'] = 0; 
		$col['psf_employee'] = 0; 
		$col['social'] = 0; 
		$col['tax'] = 0; 

		$col['gov_house_banking'] = 0; 
		$col['savings'] = 0;
		$col['legal_execution'] = 0; 
		$col['kor_yor_sor'] = 0;  
		
		$col['advance'] = 0; 
		$col['legal_deductions'] = 0; 
		
		$data = array();
		if($f == $t){
			$res = $dbc->query("SELECT * FROM ".$_SESSION['rego']['payroll_dbase']." WHERE month = '".sprintf("%02d", $f)."'");
		}else{
			$res = $dbc->query("SELECT * FROM ".$_SESSION['rego']['payroll_dbase']." WHERE month >= '".sprintf("%02d", $f)."' AND month <= '".$t."'");
		}

		while($row = $res->fetch_assoc()){
			$data[] = $row;
		}
		if($data){
			foreach($data as $k=>$v){

				$employeeInfo = getEmployeeInfoForPayroll($v['emp_id']);

				$col['ot1b'] += $v['ot1b'];
				$col['ot15b'] += $v['ot15b'];
				$col['ot2b'] += $v['ot2b'];
				$col['ot3b'] += $v['ot3b'];
				$col['ootb'] += $v['ootb'];

				for($i=1;$i<=10;$i++){
					$col['fix_allow_'.$i] += $v['fix_allow_'.$i];
				}
				for($i=1;$i<=10;$i++){
					$col['var_allow_'.$i] += $v['var_allow_'.$i];
				}

				$col['tax_by_company'] += $v['tax_by_company'];
				$col['sso_by_company'] += $v['sso_by_company'];


				$col['other_income'] += $v['other_income'];
				$col['severance'] += $v['severance'];
				$col['remaining_salary'] += $v['remaining_salary']; 
				$col['notice_payment'] += $v['notice_payment']; 
				$col['paid_leave'] += $v['paid_leave']; 

				for($i=1;$i<=10;$i++){
					$col['fix_deduct_'.$i] += $v['fix_deduct_'.$i];
				}
				for($i=1;$i<=10;$i++){
					$col['var_deduct_'.$i] += $v['var_deduct_'.$i];
				}

				$col['absence_b'] += $v['absence_b'];
				$col['leave_wop_b'] += $v['leave_wop_b'];
				$col['late_early_b'] += $v['late_early_b'];
				
				$col['tot_deduct_before'] += $v['tot_deduct_before']; 
				$col['tot_deduct_after'] += $v['tot_deduct_after']; 
				$col['pvf_employee'] += $v['pvf_employee'];
				$col['psf_employee'] += $v['psf_employee'];
				$col['social'] = 99;
				$col['tax'] = 99;

				$col['gov_house_banking'] += $employeeInfo['gov_house_banking']; 
				$col['savings'] += $employeeInfo['savings'];
				$col['legal_execution'] += $employeeInfo['legal_execution']; 
				$col['kor_yor_sor'] += $employeeInfo['kor_yor_sor']; 
				
				$col['advance'] += $v['advance'];
				$col['legal_deductions'] += $v['legal_deductions']; 
			}
		}
		foreach($col as $k=>$v){
			if($v > 0){
				unset($col[$k]);
			}
		}
		//$col = array_filter($col);
		//$col2 = array_filter($col2);
		//$cols['col'] = $col;
		//$cols['col2'] = $col2;
		return $col;
	}

	function getPayrollData($f,$t){
		global $dbc;
		/*if($f == $t){
			$sql = "SELECT * FROM ".$_SESSION['rego']['payroll_dbase']." WHERE month = '".sprintf("%02d", $f)."' ORDER by LENGTH(emp_id) ASC, emp_id ASC";
		}else{
			$sql = "SELECT * FROM ".$_SESSION['rego']['payroll_dbase']." WHERE month >= '".sprintf("%02d", $f)."' AND month <= '".$t."' ORDER by LENGTH(emp_id) ASC, emp_id ASC";
		}*/
		
		$sql = "SELECT * FROM ".$_SESSION['rego']['payroll_dbase']." WHERE month = '".$_SESSION['rego']['cur_month']."' AND entity = '".$_SESSION['rego']['gov_entity']."' AND emp_group = '".$_SESSION['rego']['emp_group']."' ORDER by LENGTH(emp_id) ASC, emp_id ASC";

		$res = $dbc->query($sql);
		$data = array();
		
		while($row = $res->fetch_assoc()){
			$data[$row['emp_id']]['emp_id'] = $row['emp_id'];
			$data[$row['emp_id']]['emp_name_en'] = $row['emp_name_'.$_SESSION['rego']['lang']];
			//$data[$row['emp_id']]['basic_salary'] = 0;
			$data[$row['emp_id']]['paid_days'] = 0;
			$data[$row['emp_id']]['salary'] = 0;
			//$data[$row['emp_id']]['actual_days'] = 0;
			$data[$row['emp_id']]['ot1b'] = 0;
			$data[$row['emp_id']]['ot15b'] = 0;
			$data[$row['emp_id']]['ot2b'] = 0;
			$data[$row['emp_id']]['ot3b'] = 0;
			$data[$row['emp_id']]['ootb'] = 0;
			for($i=1;$i<=10;$i++){
				$data[$row['emp_id']]['fix_allow_'.$i] = 0;
			}
			for($i=1;$i<=10;$i++){
				$data[$row['emp_id']]['var_allow_'.$i] = 0;
			}
			$data[$row['emp_id']]['tax_by_company'] = 0;
			$data[$row['emp_id']]['sso_by_company'] = 0;
			$data[$row['emp_id']]['other_income'] = 0;
			$data[$row['emp_id']]['severance'] = 0;
			$data[$row['emp_id']]['remaining_salary'] = 0;
			$data[$row['emp_id']]['notice_payment'] = 0;
			$data[$row['emp_id']]['paid_leave'] = 0;
			
			$data[$row['emp_id']]['absence_b'] = 0;
			$data[$row['emp_id']]['late_early_b'] = 0;
			$data[$row['emp_id']]['leave_wop_b'] = 0;

			$data[$row['emp_id']]['tot_deduct_before'] = 0;
			$data[$row['emp_id']]['tot_deduct_after'] = 0;
			$data[$row['emp_id']]['pvf_employee'] = 0;
			$data[$row['emp_id']]['psf_employee'] = 0;
			$data[$row['emp_id']]['social'] = 0;
			$data[$row['emp_id']]['tax'] = 0;

			$data[$row['emp_id']]['gross_income'] = 0;
			$data[$row['emp_id']]['tot_deductions'] = 0;
			$data[$row['emp_id']]['advance'] = 0;
			$data[$row['emp_id']]['legal_deductions'] = 0;
			$data[$row['emp_id']]['net_income'] = 0;
		}	
		
		$res = $dbc->query($sql);		
		while($row = $res->fetch_assoc()){
			//$data[$row['emp_id']]['basic_salary'] += $row['basic_salary'];
			$data[$row['emp_id']]['salary'] += $row['salary'];
			//$data[$row['emp_id']]['actual_days'] += $row['actual_days'];
			$data[$row['emp_id']]['paid_days'] += $row['paid_days'];
			$data[$row['emp_id']]['ot1b'] += $row['ot1b'];
			$data[$row['emp_id']]['ot15b'] += $row['ot15b'];
			$data[$row['emp_id']]['ot2b'] += $row['ot2b'];
			$data[$row['emp_id']]['ot3b'] += $row['ot3b'];
			$data[$row['emp_id']]['ootb'] += $row['ootb'];
			for($i=1;$i<=10;$i++){
				$data[$row['emp_id']]['fix_allow_'.$i] += $row['fix_allow_'.$i];
			}
			for($i=1;$i<=10;$i++){
				$data[$row['emp_id']]['var_allow_'.$i] += $row['var_allow_'.$i];
			}
			
			$data[$row['emp_id']]['absence_b'] += $row['absence_b'];
			$data[$row['emp_id']]['late_early_b'] += $row['late_early_b'];
			$data[$row['emp_id']]['leave_wop_b'] += $row['leave_wop_b'];

			$data[$row['emp_id']]['tax_by_company'] += $row['tax_by_company'];
			$data[$row['emp_id']]['sso_by_company'] += $row['sso_by_company'];
			$data[$row['emp_id']]['other_income'] += $row['other_income'];
			$data[$row['emp_id']]['severance'] += $row['severance'];
			$data[$row['emp_id']]['remaining_salary'] += $row['remaining_salary'];
			$data[$row['emp_id']]['notice_payment'] += $row['notice_payment'];
			$data[$row['emp_id']]['paid_leave'] += $row['paid_leave'];
			
			$data[$row['emp_id']]['tot_deduct_before'] += $row['tot_deduct_before'];
			$data[$row['emp_id']]['tot_deduct_after'] += $row['tot_deduct_after'];
			$data[$row['emp_id']]['pvf_employee'] += $row['pvf_employee'];
			$data[$row['emp_id']]['psf_employee'] += $row['psf_employee'];
			$data[$row['emp_id']]['social'] += $row['social'];
			$data[$row['emp_id']]['tax'] += $row['tax_month'];

			$data[$row['emp_id']]['gross_income'] += $row['gross_income'];
			$data[$row['emp_id']]['tot_deductions'] += $row['tot_deductions'];
			$data[$row['emp_id']]['advance'] += $row['advance'];
			$data[$row['emp_id']]['legal_deductions'] += $row['legal_deductions'];
			$data[$row['emp_id']]['net_income'] += $row['net_income'];
		}
		return $data;
	}

	


	function getEmployeeInfoForPayroll($id){
		global $cid;
		global $dbc;
		$info = '';
		if($res = $dbc->query("SELECT gov_house_banking, savings, legal_execution, kor_yor_sor FROM ".$cid."_employees WHERE emp_id = '".$id."'")){
			if($row = $res->fetch_assoc()){
				$info = array(
								"gov_house_banking" => $row['gov_house_banking'],
								"savings" => $row['savings'],
								"legal_execution" => $row['legal_execution'],
								"kor_yor_sor" => $row['kor_yor_sor'],
							);
			}
		}
		
		return $info;
	}

	function getPayrollDataDep(){ //fn for no department
		global $dbc;

		$fix_deduct = getUsedFixDeduction($_SESSION['rego']['lang']);
		$var_deduct = getUsedVarDeduction($_SESSION['rego']['lang']);

		$sbranches = str_replace(',', "','", $_SESSION['rego']['selpr_branches']);
		$sdivisions = str_replace(',', "','", $_SESSION['rego']['selpr_divisions']);
		$sdepartments = str_replace(',', "','", $_SESSION['rego']['selpr_departments']);
		$steams = str_replace(',', "','", $_SESSION['rego']['selpr_teams']);

		$whereCondition = " AND branch IN ('".$sbranches."') AND division IN ('".$sdivisions."') AND department IN ('".$sdepartments."') AND team IN ('".$steams."')";
		
		$sql = "SELECT * FROM ".$_SESSION['rego']['payroll_dbase']." WHERE month = '".$_SESSION['rego']['cur_month']."' AND entity = '".$_SESSION['rego']['gov_entity']."' AND emp_group = '".$_SESSION['rego']['emp_group']."' ".$whereCondition." ORDER by LENGTH(emp_id) ASC, emp_id ASC";

		$data = array();
		$res = $dbc->query($sql);		
		while($row = $res->fetch_assoc()){

			$employeeInfo = getEmployeeInfoForPayroll($row['emp_id']);

			$data[$row['department']][$row['emp_id']]['emp_id'] = $row['emp_id'];
			$data[$row['department']][$row['emp_id']]['emp_name_en'] = $row['emp_name_'.$_SESSION['rego']['lang']];
			$data[$row['department']][$row['emp_id']]['paid_days'] = $row['paid_days'];
			$data[$row['department']][$row['emp_id']]['salary'] = $row['salary'];
			//$data[$row['emp_id']]['actual_days'] += $row['actual_days'];
			$data[$row['department']][$row['emp_id']]['ot1b'] = $row['ot1b'];
			$data[$row['department']][$row['emp_id']]['ot15b'] = $row['ot15b'];
			$data[$row['department']][$row['emp_id']]['ot2b'] = $row['ot2b'];
			$data[$row['department']][$row['emp_id']]['ot3b'] = $row['ot3b'];
			$data[$row['department']][$row['emp_id']]['ootb'] = $row['ootb'];
			for($i=1;$i<=10;$i++){
				$data[$row['department']][$row['emp_id']]['fix_allow_'.$i] = $row['fix_allow_'.$i];
			}
			for($i=1;$i<=10;$i++){
				$data[$row['department']][$row['emp_id']]['var_allow_'.$i] = $row['var_allow_'.$i];
			}

			//if($row['tax_by_company'] > 0){
				$data[$row['department']][$row['emp_id']]['tax_by_company'] = $row['tax_by_company'];
			///}

			//if($row['sso_by_company'] > 0){
				$data[$row['department']][$row['emp_id']]['sso_by_company'] = $row['sso_by_company'];
			//}
			
			
			$data[$row['department']][$row['emp_id']]['other_income'] = $row['other_income'];
			$data[$row['department']][$row['emp_id']]['severance'] = $row['severance'];
			$data[$row['department']][$row['emp_id']]['remaining_salary'] = $row['remaining_salary'];
			$data[$row['department']][$row['emp_id']]['notice_payment'] = $row['notice_payment'];
			$data[$row['department']][$row['emp_id']]['paid_leave'] = $row['paid_leave'];
			
			$data[$row['department']][$row['emp_id']]['gross_income'] = $row['gross_income'];

			foreach($fix_deduct as $k => $v){
					$data[$row['department']][$row['emp_id']]['fix_deduct_'.$k] = $row['fix_deduct_'.$k];
			}
			foreach($var_deduct as $k => $v){
					$data[$row['department']][$row['emp_id']]['var_deduct_'.$k] = $row['var_deduct_'.$k];
			}
			
			$data[$row['department']][$row['emp_id']]['absence_b'] = $row['absence_b'];
			$data[$row['department']][$row['emp_id']]['late_early_b'] = $row['late_early_b'];
			$data[$row['department']][$row['emp_id']]['leave_wop_b'] = $row['leave_wop_b'];
			//$data[$row['department']][$row['emp_id']]['tot_deduct_before'] = $row['tot_deduct_before'];
			//$data[$row['department']][$row['emp_id']]['tot_deduct_after'] = $row['tot_deduct_after'];
			$data[$row['department']][$row['emp_id']]['pvf_employee'] = $row['pvf_employee'];
			//$data[$row['department']][$row['emp_id']]['psf_employee'] = $row['psf_employee'];
			$data[$row['department']][$row['emp_id']]['social'] = $row['social'];
			$data[$row['department']][$row['emp_id']]['tax'] = $row['tax_month'];
			$data[$row['department']][$row['emp_id']]['gov_house_banking'] = $employeeInfo['gov_house_banking'];
			$data[$row['department']][$row['emp_id']]['savings'] = $employeeInfo['savings'];
			$data[$row['department']][$row['emp_id']]['legal_execution'] = $employeeInfo['legal_execution'];
			$data[$row['department']][$row['emp_id']]['kor_yor_sor'] = $employeeInfo['kor_yor_sor'];

			$data[$row['department']][$row['emp_id']]['advance'] = $row['advance'];

			
			//$data[$row['department']][$row['emp_id']]['legal_deductions'] = $row['legal_deductions'];
			$data[$row['department']][$row['emp_id']]['tot_deductions'] = $row['tot_deductions'];
			
			$data[$row['department']][$row['emp_id']]['net_income'] = $row['net_income'];
		}
		return $data;
	}


	function getPayrollDataWithoutDep(){  //fn for department but download without department
		global $dbc;

		$fix_deduct = getUsedFixDeduction($_SESSION['rego']['lang']);
		$var_deduct = getUsedVarDeduction($_SESSION['rego']['lang']);

		$sbranches = str_replace(',', "','", $_SESSION['rego']['selpr_branches']);
		$sdivisions = str_replace(',', "','", $_SESSION['rego']['selpr_divisions']);
		$sdepartments = str_replace(',', "','", $_SESSION['rego']['selpr_departments']);
		$steams = str_replace(',', "','", $_SESSION['rego']['selpr_teams']);

		$whereCondition = " AND branch IN ('".$sbranches."') AND division IN ('".$sdivisions."') AND department IN ('".$sdepartments."') AND team IN ('".$steams."')";
		
		$sql = "SELECT * FROM ".$_SESSION['rego']['payroll_dbase']." WHERE month = '".$_SESSION['rego']['cur_month']."' AND entity = '".$_SESSION['rego']['gov_entity']."' AND emp_group = '".$_SESSION['rego']['emp_group']."' ".$whereCondition." ORDER by LENGTH(emp_id) ASC, emp_id ASC";

		$data = array();
		$res = $dbc->query($sql);		
		while($row = $res->fetch_assoc()){

			$employeeInfo = getEmployeeInfoForPayroll($row['emp_id']);

			$data[$row['emp_id']]['emp_id'] = $row['emp_id'];
			$data[$row['emp_id']]['emp_name_en'] = $row['emp_name_'.$_SESSION['rego']['lang']];
			$data[$row['emp_id']]['paid_days'] = $row['paid_days'];
			$data[$row['emp_id']]['salary'] = $row['salary'];
			//$data[$row['emp_id']]['actual_days'] += $row['actual_days'];
			$data[$row['emp_id']]['ot1b'] = $row['ot1b'];
			$data[$row['emp_id']]['ot15b'] = $row['ot15b'];
			$data[$row['emp_id']]['ot2b'] = $row['ot2b'];
			$data[$row['emp_id']]['ot3b'] = $row['ot3b'];
			$data[$row['emp_id']]['ootb'] = $row['ootb'];
			for($i=1;$i<=10;$i++){
				$data[$row['emp_id']]['fix_allow_'.$i] = $row['fix_allow_'.$i];
			}
			for($i=1;$i<=10;$i++){
				$data[$row['emp_id']]['var_allow_'.$i] = $row['var_allow_'.$i];
			}

			$data[$row['emp_id']]['tax_by_company'] = $row['tax_by_company'];
			
			$data[$row['emp_id']]['sso_by_company'] = $row['sso_by_company'];
			
			
			$data[$row['emp_id']]['other_income'] = $row['other_income'];
			$data[$row['emp_id']]['severance'] = $row['severance'];
			$data[$row['emp_id']]['remaining_salary'] = $row['remaining_salary'];
			$data[$row['emp_id']]['notice_payment'] = $row['notice_payment'];
			$data[$row['emp_id']]['paid_leave'] = $row['paid_leave'];
			
			$data[$row['emp_id']]['gross_income'] = $row['gross_income'];

			foreach($fix_deduct as $k => $v){
					$data[$row['emp_id']]['fix_deduct_'.$k] = $row['fix_deduct_'.$k];
			}
			foreach($var_deduct as $k => $v){
					$data[$row['emp_id']]['var_deduct_'.$k] = $row['var_deduct_'.$k];
			}
			
			$data[$row['emp_id']]['absence_b'] = $row['absence_b'];
			$data[$row['emp_id']]['late_early_b'] = $row['late_early_b'];
			$data[$row['emp_id']]['leave_wop_b'] = $row['leave_wop_b'];
			//$data[$row['department']][$row['emp_id']]['tot_deduct_before'] = $row['tot_deduct_before'];
			//$data[$row['department']][$row['emp_id']]['tot_deduct_after'] = $row['tot_deduct_after'];
			$data[$row['emp_id']]['pvf_employee'] = $row['pvf_employee'];
			//$data[$row['department']][$row['emp_id']]['psf_employee'] = $row['psf_employee'];
			$data[$row['emp_id']]['social'] = $row['social'];
			$data[$row['emp_id']]['tax'] = $row['tax_month'];
			$data[$row['emp_id']]['gov_house_banking'] = $employeeInfo['gov_house_banking'];
			$data[$row['emp_id']]['savings'] = $employeeInfo['savings'];
			$data[$row['emp_id']]['legal_execution'] = $employeeInfo['legal_execution'];
			$data[$row['emp_id']]['kor_yor_sor'] = $employeeInfo['kor_yor_sor'];


			$data[$row['emp_id']]['advance'] = $row['advance'];
			//$data[$row['emp_id']]['legal_deductions'] = $row['legal_deductions'];
			$data[$row['emp_id']]['tot_deductions'] = $row['tot_deductions'];
			
			$data[$row['emp_id']]['net_income'] = $row['net_income'];
		}
		return $data;
	}


	function getPayrollDataDepNEW(){ //fn for download department by Name ASC
		global $dbc;

		$fix_deduct = getUsedFixDeduction($_SESSION['rego']['lang']);
		$var_deduct = getUsedVarDeduction($_SESSION['rego']['lang']);

		$sbranches = str_replace(',', "','", $_SESSION['rego']['selpr_branches']);
		$sdivisions = str_replace(',', "','", $_SESSION['rego']['selpr_divisions']);
		$sdepartments = str_replace(',', "','", $_SESSION['rego']['selpr_departments']);
		$steams = str_replace(',', "','", $_SESSION['rego']['selpr_teams']);

		$whereCondition = " AND p.branch IN ('".$sbranches."') AND p.division IN ('".$sdivisions."') AND p.department IN ('".$sdepartments."') AND p.team IN ('".$steams."')";
		
		//$sql = "SELECT * FROM ".$_SESSION['rego']['payroll_dbase']." WHERE month = '".$_SESSION['rego']['cur_month']."' AND entity = '".$_SESSION['rego']['gov_entity']."' AND emp_group = '".$_SESSION['rego']['emp_group']."' ORDER by LENGTH(emp_id) ASC, emp_id ASC";
		$sql = "SELECT * FROM ".$_SESSION['rego']['payroll_dbase']." as p JOIN ".$_SESSION['rego']['cid']."_departments as d ON p.department = d.id WHERE p.month = '".$_SESSION['rego']['cur_month']."' AND p.entity = '".$_SESSION['rego']['gov_entity']."' AND p.emp_group = '".$_SESSION['rego']['emp_group']."' ".$whereCondition." ORDER by d.".$_SESSION['RGadmin']['lang']." ASC, LENGTH(p.emp_id) ASC";

		$data = array();
		$res = $dbc->query($sql);		
		while($row = $res->fetch_assoc()){

			$employeeInfo = getEmployeeInfoForPayroll($row['emp_id']);

			$data[$row['department']][$row['emp_id']]['emp_id'] = $row['emp_id'];
			$data[$row['department']][$row['emp_id']]['emp_name_en'] = $row['emp_name_'.$_SESSION['rego']['lang']];
			$data[$row['department']][$row['emp_id']]['paid_days'] = $row['paid_days'];
			$data[$row['department']][$row['emp_id']]['salary'] = $row['salary'];
			//$data[$row['emp_id']]['actual_days'] += $row['actual_days'];
			$data[$row['department']][$row['emp_id']]['ot1b'] = $row['ot1b'];
			$data[$row['department']][$row['emp_id']]['ot15b'] = $row['ot15b'];
			$data[$row['department']][$row['emp_id']]['ot2b'] = $row['ot2b'];
			$data[$row['department']][$row['emp_id']]['ot3b'] = $row['ot3b'];
			$data[$row['department']][$row['emp_id']]['ootb'] = $row['ootb'];
			for($i=1;$i<=10;$i++){
				$data[$row['department']][$row['emp_id']]['fix_allow_'.$i] = $row['fix_allow_'.$i];
			}
			for($i=1;$i<=10;$i++){
				$data[$row['department']][$row['emp_id']]['var_allow_'.$i] = $row['var_allow_'.$i];
			}

			$data[$row['department']][$row['emp_id']]['tax_by_company'] = $row['tax_by_company'];
			
			$data[$row['department']][$row['emp_id']]['sso_by_company'] = $row['sso_by_company'];
			
			$data[$row['department']][$row['emp_id']]['other_income'] = $row['other_income'];
			$data[$row['department']][$row['emp_id']]['severance'] = $row['severance'];
			$data[$row['department']][$row['emp_id']]['remaining_salary'] = $row['remaining_salary'];
			$data[$row['department']][$row['emp_id']]['notice_payment'] = $row['notice_payment'];
			$data[$row['department']][$row['emp_id']]['paid_leave'] = $row['paid_leave'];
			
			$data[$row['department']][$row['emp_id']]['gross_income'] = $row['gross_income'];

			foreach($fix_deduct as $k => $v){
					$data[$row['department']][$row['emp_id']]['fix_deduct_'.$k] = $row['fix_deduct_'.$k];
			}
			foreach($var_deduct as $k => $v){
					$data[$row['department']][$row['emp_id']]['var_deduct_'.$k] = $row['var_deduct_'.$k];
			}
			
			$data[$row['department']][$row['emp_id']]['absence_b'] = $row['absence_b'];
			$data[$row['department']][$row['emp_id']]['late_early_b'] = $row['late_early_b'];
			$data[$row['department']][$row['emp_id']]['leave_wop_b'] = $row['leave_wop_b'];
			//$data[$row['department']][$row['emp_id']]['tot_deduct_before'] = $row['tot_deduct_before'];
			//$data[$row['department']][$row['emp_id']]['tot_deduct_after'] = $row['tot_deduct_after'];
			$data[$row['department']][$row['emp_id']]['pvf_employee'] = $row['pvf_employee'];
			//$data[$row['department']][$row['emp_id']]['psf_employee'] = $row['psf_employee'];
			$data[$row['department']][$row['emp_id']]['social'] = $row['social'];
			$data[$row['department']][$row['emp_id']]['tax'] = $row['tax_month'];
			$data[$row['department']][$row['emp_id']]['gov_house_banking'] = $employeeInfo['gov_house_banking'];
			$data[$row['department']][$row['emp_id']]['savings'] = $employeeInfo['savings'];
			$data[$row['department']][$row['emp_id']]['legal_execution'] = $employeeInfo['legal_execution'];
			$data[$row['department']][$row['emp_id']]['kor_yor_sor'] = $employeeInfo['kor_yor_sor'];

			$data[$row['department']][$row['emp_id']]['advance'] = $row['advance'];

			
			//$data[$row['department']][$row['emp_id']]['legal_deductions'] = $row['legal_deductions'];
			$data[$row['department']][$row['emp_id']]['tot_deductions'] = $row['tot_deductions'];
			
			$data[$row['department']][$row['emp_id']]['net_income'] = $row['net_income'];
		}
		return $data;
	}


	function getPayrollDataDepRANK(){ //fn for download department by Code ASC
		global $dbc;

		$fix_deduct = getUsedFixDeduction($_SESSION['rego']['lang']);
		$var_deduct = getUsedVarDeduction($_SESSION['rego']['lang']);

		$sbranches = str_replace(',', "','", $_SESSION['rego']['selpr_branches']);
		$sdivisions = str_replace(',', "','", $_SESSION['rego']['selpr_divisions']);
		$sdepartments = str_replace(',', "','", $_SESSION['rego']['selpr_departments']);
		$steams = str_replace(',', "','", $_SESSION['rego']['selpr_teams']);

		$whereCondition = " AND p.branch IN ('".$sbranches."') AND p.division IN ('".$sdivisions."') AND p.department IN ('".$sdepartments."') AND p.team IN ('".$steams."')";
		
		//$sql = "SELECT * FROM ".$_SESSION['rego']['payroll_dbase']." WHERE month = '".$_SESSION['rego']['cur_month']."' AND entity = '".$_SESSION['rego']['gov_entity']."' AND emp_group = '".$_SESSION['rego']['emp_group']."' ORDER by LENGTH(emp_id) ASC, emp_id ASC";
		$sql = "SELECT * FROM ".$_SESSION['rego']['payroll_dbase']." as p JOIN ".$_SESSION['rego']['cid']."_departments as d ON p.department = d.id WHERE p.month = '".$_SESSION['rego']['cur_month']."' AND p.entity = '".$_SESSION['rego']['gov_entity']."' AND p.emp_group = '".$_SESSION['rego']['emp_group']."' ".$whereCondition." ORDER by d.id ASC, LENGTH(p.emp_id) ASC";

		$data = array();
		$res = $dbc->query($sql);		
		while($row = $res->fetch_assoc()){

			$employeeInfo = getEmployeeInfoForPayroll($row['emp_id']);

			$data[$row['department']][$row['emp_id']]['emp_id'] = $row['emp_id'];
			$data[$row['department']][$row['emp_id']]['emp_name_en'] = $row['emp_name_'.$_SESSION['rego']['lang']];
			$data[$row['department']][$row['emp_id']]['paid_days'] = $row['paid_days'];
			$data[$row['department']][$row['emp_id']]['salary'] = $row['salary'];
			//$data[$row['emp_id']]['actual_days'] += $row['actual_days'];
			$data[$row['department']][$row['emp_id']]['ot1b'] = $row['ot1b'];
			$data[$row['department']][$row['emp_id']]['ot15b'] = $row['ot15b'];
			$data[$row['department']][$row['emp_id']]['ot2b'] = $row['ot2b'];
			$data[$row['department']][$row['emp_id']]['ot3b'] = $row['ot3b'];
			$data[$row['department']][$row['emp_id']]['ootb'] = $row['ootb'];
			for($i=1;$i<=10;$i++){
				$data[$row['department']][$row['emp_id']]['fix_allow_'.$i] = $row['fix_allow_'.$i];
			}
			for($i=1;$i<=10;$i++){
				$data[$row['department']][$row['emp_id']]['var_allow_'.$i] = $row['var_allow_'.$i];
			}

			$data[$row['department']][$row['emp_id']]['tax_by_company'] = $row['tax_by_company'];
			
			$data[$row['department']][$row['emp_id']]['sso_by_company'] = $row['sso_by_company'];
			
			$data[$row['department']][$row['emp_id']]['other_income'] = $row['other_income'];
			$data[$row['department']][$row['emp_id']]['severance'] = $row['severance'];
			$data[$row['department']][$row['emp_id']]['remaining_salary'] = $row['remaining_salary'];
			$data[$row['department']][$row['emp_id']]['notice_payment'] = $row['notice_payment'];
			$data[$row['department']][$row['emp_id']]['paid_leave'] = $row['paid_leave'];
			
			$data[$row['department']][$row['emp_id']]['gross_income'] = $row['gross_income'];

			foreach($fix_deduct as $k => $v){
					$data[$row['department']][$row['emp_id']]['fix_deduct_'.$k] = $row['fix_deduct_'.$k];
			}
			foreach($var_deduct as $k => $v){
					$data[$row['department']][$row['emp_id']]['var_deduct_'.$k] = $row['var_deduct_'.$k];
			}
			
			$data[$row['department']][$row['emp_id']]['absence_b'] = $row['absence_b'];
			$data[$row['department']][$row['emp_id']]['late_early_b'] = $row['late_early_b'];
			$data[$row['department']][$row['emp_id']]['leave_wop_b'] = $row['leave_wop_b'];
			//$data[$row['department']][$row['emp_id']]['tot_deduct_before'] = $row['tot_deduct_before'];
			//$data[$row['department']][$row['emp_id']]['tot_deduct_after'] = $row['tot_deduct_after'];
			$data[$row['department']][$row['emp_id']]['pvf_employee'] = $row['pvf_employee'];
			//$data[$row['department']][$row['emp_id']]['psf_employee'] = $row['psf_employee'];
			$data[$row['department']][$row['emp_id']]['social'] = $row['social'];
			$data[$row['department']][$row['emp_id']]['tax'] = $row['tax_month'];
			$data[$row['department']][$row['emp_id']]['gov_house_banking'] = $employeeInfo['gov_house_banking'];
			$data[$row['department']][$row['emp_id']]['savings'] = $employeeInfo['savings'];
			$data[$row['department']][$row['emp_id']]['legal_execution'] = $employeeInfo['legal_execution'];
			$data[$row['department']][$row['emp_id']]['kor_yor_sor'] = $employeeInfo['kor_yor_sor'];

			$data[$row['department']][$row['emp_id']]['advance'] = $row['advance'];
			//$data[$row['department']][$row['emp_id']]['legal_deductions'] = $row['legal_deductions'];
			$data[$row['department']][$row['emp_id']]['tot_deductions'] = $row['tot_deductions'];
			
			$data[$row['department']][$row['emp_id']]['net_income'] = $row['net_income'];
		}
		return $data;
	}

