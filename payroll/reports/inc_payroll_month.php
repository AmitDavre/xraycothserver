<?php
	$fix = getFixAllowNames();
	$var = getVarAllowNames();
	
	$col = array();
	$sql = "SELECT 
		emp_id, 
		SUM(ot1b) as ot1b, 
		SUM(ot15b) as ot15b, 
		SUM(ot2b) as ot2b, 
		SUM(ot3b) as ot3b, 
		SUM(ootb) as ootb,   
		SUM(absence_b) as absence_b,   
		SUM(late_early_b) as late_early_b,   
		SUM(leave_wop_b) as leave_wop_b, 
		SUM(fix_allow_1) as fix1, 
		SUM(fix_allow_2) as fix2, 
		SUM(fix_allow_3) as fix3, 
		SUM(fix_allow_4) as fix4, 
		SUM(fix_allow_5) as fix5, 
		SUM(fix_allow_6) as fix6, 
		SUM(fix_allow_7) as fix7, 
		SUM(fix_allow_8) as fix8, 
		SUM(fix_allow_9) as fix9, 
		SUM(fix_allow_10) as fix10,
		total_fix_allow, 
		SUM(var_allow_1) as var1, 
		SUM(var_allow_2) as var2, 
		SUM(var_allow_3) as var3, 
		SUM(var_allow_4) as var4, 
		SUM(var_allow_5) as var5, 
		SUM(var_allow_6) as var6, 
		SUM(var_allow_7) as var7, 
		SUM(var_allow_8) as var8, 
		SUM(var_allow_9) as var9, 
		SUM(var_allow_10) as var10,
		total_var_allow, 
		SUM(remaining_salary) as remaining_salary, 
		SUM(notice_payment) as notice_payment, 
		SUM(paid_leave) as paid_leave, 
		SUM(other_income) as other_income, 
		SUM(severance) as severance, 
		SUM(tot_deduct_before) as tot_deduct_before, 
		SUM(tot_deduct_after) as tot_deduct_after, 
		pvf_employee,
		social, 
		tax, 
		gross_income,
		SUM(advance) as advance, 
		SUM(legal_deductions) as legal_deductions, 
		net_income  
		FROM ".$_SESSION['rego']['payroll_dbase']." WHERE month = '".$_GET['m']."'";
	if($res = $dbc->query($sql)){
		if($row = $res->fetch_assoc()){
			$cols['emp_id'] = $lng['ID'];
			$cols['emp_name_'.$lang] = $lng['Employee'];
			$cols['salary'] = $lng['Salary'];
			if($row['ot1b'] > 0){$cols['ot1b'] = $lng['OT 1'];}
			if($row['ot15b'] > 0){$cols['ot15b'] = $lng['OT 1.5'];}
			if($row['ot2b'] > 0){$cols['ot2b'] = $lng['OT 2'];}
			if($row['ot3b'] > 0){$cols['ot3b'] = $lng['OT 3'];}
			if($row['ootb'] > 0){$cols['ootb'] = $lng['Other OT'];}
			if($row['absence_b'] > 0){$cols['absence_b'] = $lng['Absence'];}
			if($row['late_early_b'] > 0){$cols['late_early_b'] = $lng['Late Early'];}
			if($row['leave_wop_b'] > 0){$cols['leave_wop_b'] = $lng['Leave WOP'];}
			/*if($row['fix1'] > 0){$cols['fix_allow_1'] = $fix[1];}
			if($row['fix2'] > 0){$cols['fix_allow_2'] = $fix[2];}
			if($row['fix3'] > 0){$cols['fix_allow_3'] = $fix[3];}
			if($row['fix4'] > 0){$cols['fix_allow_4'] = $fix[4];}
			if($row['fix5'] > 0){$cols['fix_allow_5'] = $fix[5];}
			if($row['fix6'] > 0){$cols['fix_allow_6'] = $fix[6];}
			if($row['fix7'] > 0){$cols['fix_allow_7'] = $fix[7];}
			if($row['fix8'] > 0){$cols['fix_allow_8'] = $fix[8];}
			if($row['fix9'] > 0){$cols['fix_allow_9'] = $fix[9];}
			if($row['fix10'] > 0){$cols['fix_allow_10'] = $fix[10];}*/
			if($row['total_fix_allow'] > 0){$cols['total_fix_allow'] = $lng['Fixed allowances'];}
			/*if($row['var1'] > 0){$cols['var_allow_1'] = $var[1];}
			if($row['var2'] > 0){$cols['var_allow_2'] = $var[2];}
			if($row['var3'] > 0){$cols['var_allow_3'] = $var[3];}
			if($row['var4'] > 0){$cols['var_allow_4'] = $var[4];}
			if($row['var5'] > 0){$cols['var_allow_5'] = $var[5];}
			if($row['var6'] > 0){$cols['var_allow_6'] = $var[6];}
			if($row['var7'] > 0){$cols['var_allow_7'] = $var[7];}
			if($row['var8'] > 0){$cols['var_allow_8'] = $var[8];}
			if($row['var9'] > 0){$cols['var_allow_9'] = $var[9];}
			if($row['var10'] > 0){$cols['var_allow_10'] = $var[10];}*/
			if($row['total_var_allow'] > 0){$cols['total_var_allow'] = $lng['Variable allowances'];}
			if($row['remaining_salary'] > 0){$cols['remaining_salary'] = $lng['Remaining salary'];}
			if($row['notice_payment'] > 0){$cols['notice_payment'] = $lng['Notice payment'];}
			if($row['paid_leave'] > 0){$cols['paid_leave'] = $lng['Paid leave'];}
			if($row['other_income'] > 0){$cols['other_income'] = $lng['Other income'];}
			if($row['severance'] > 0){$cols['severance'] = $lng['Severance'];}
			if($row['tot_deduct_before'] > 0){$cols['tot_deduct_before'] = $lng['Deduct before'];}
			if($row['tot_deduct_after'] > 0){$cols['tot_deduct_after'] = $lng['Deduct after'];}
			$cols['pvf_employee'] = $lng['PVF'];
			$cols['social'] = $lng['SSO'];
			$cols['tax'] = $lng['Tax'];
			$cols['gross_income'] = $lng['Gross income'];
			if($row['advance'] > 0){$cols['advance'] = $lng['Advance'];}
			if($row['legal_deductions'] > 0){$cols['legal_deductions'] = $lng['Legal deductions'];}
			$cols['net_income'] = $lng['Net income'];
		}
	}
	
	$data = array();
	$sql = "SELECT * FROM ".$_SESSION['rego']['payroll_dbase']." WHERE month = '".$_GET['m']."'";
	if($res = $dbc->query($sql)){
		while($row = $res->fetch_assoc()){
			foreach($cols as $k=>$v){
				$data[$row['emp_id']][$k] = $row[$k];
				/*if(!isset($totals[$row['emp_id']])){
					$totals[$row['emp_id']] = $row[$k];
				}else{
					$totals[$row['emp_id']] += $row[$k];
				}*/
			}
		}
	}
	
	$totals = array();
	foreach($data as $key=>$val){
		foreach($val as $k=>$v){
			if(!isset($totals[$k])){
				$totals[$k] = $v;
			}else{
				$totals[$k] += $v;
			}
		}
	}
	$totals['emp_id'] = $lng['Totals'];
	unset($totals['emp_name_'.$lang]);
