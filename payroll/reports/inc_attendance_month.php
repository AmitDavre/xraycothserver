<?php

	$var = getVarAllowNames();
	
	$col = array();
	$sql = "SELECT 
		emp_id, 
		SUM(ot1h) as ot1h, 
		SUM(ot15h) as ot15h, 
		SUM(ot2h) as ot2h, 
		SUM(ot3h) as ot3h, 
		SUM(ootb) as ootb,   
		SUM(absence) as absence,   
		SUM(late_early) as late_early,   
		SUM(leave_wop) as leave_wop, 
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
		SUM(remaining_salary) as remaining_salary, 
		SUM(notice_payment) as notice_payment, 
		SUM(paid_leave) as paid_leave, 
		SUM(other_income) as other_income, 
		SUM(severance) as severance, 
		SUM(tot_deduct_before) as tot_deduct_before, 
		SUM(tot_deduct_after) as tot_deduct_after 
		FROM ".$_SESSION['rego']['payroll_dbase']." WHERE month = '".$_GET['m']."'";
	if($res = $dbc->query($sql)){
		if($row = $res->fetch_assoc()){
			$cols['emp_id'] = $lng['ID'];
			$cols['emp_name_'.$lang] = $lng['Employee'];
			$cols['paid_days'] = $lng['Days paid'];
			if($row['ot1h'] > 0){$cols['ot1h'] = $lng['OT 1'];}
			if($row['ot15h'] > 0){$cols['ot15h'] = $lng['OT 1.5'];}
			if($row['ot2h'] > 0){$cols['ot2h'] = $lng['OT 2'];}
			if($row['ot3h'] > 0){$cols['ot3h'] = $lng['OT 3'];}
			if($row['ootb'] > 0){$cols['ootb'] = $lng['Other OT'];}
			if($row['absence'] > 0){$cols['absence'] = $lng['Absence'];}
			if($row['late_early'] > 0){$cols['late_early'] = $lng['Late Early'];}
			if($row['leave_wop'] > 0){$cols['leave_wop'] = $lng['Leave WOP'];}
			if($row['var1'] > 0){$cols['var_allow_1'] = $var[1];}
			if($row['var2'] > 0){$cols['var_allow_2'] = $var[2];}
			if($row['var3'] > 0){$cols['var_allow_3'] = $var[3];}
			if($row['var4'] > 0){$cols['var_allow_4'] = $var[4];}
			if($row['var5'] > 0){$cols['var_allow_5'] = $var[5];}
			if($row['var6'] > 0){$cols['var_allow_6'] = $var[6];}
			if($row['var7'] > 0){$cols['var_allow_7'] = $var[7];}
			if($row['var8'] > 0){$cols['var_allow_8'] = $var[8];}
			if($row['var9'] > 0){$cols['var_allow_9'] = $var[9];}
			if($row['var10'] > 0){$cols['var_allow_10'] = $var[10];}
			if($row['remaining_salary'] > 0){$cols['remaining_salary'] = $lng['Remaining salary'];}
			if($row['notice_payment'] > 0){$cols['notice_payment'] = $lng['Notice payment'];}
			if($row['paid_leave'] > 0){$cols['paid_leave'] = $lng['Paid leave'];}
			if($row['other_income'] > 0){$cols['other_income'] = $lng['Other income'];}
			if($row['severance'] > 0){$cols['severance'] = $lng['Severance'];}
			if($row['tot_deduct_before'] > 0){$cols['tot_deduct_before'] = $lng['Deduct before'];}
			if($row['tot_deduct_after'] > 0){$cols['tot_deduct_after'] = $lng['Deduct after'];}
		}
	}
	
	$data = array();
	$sql = "SELECT * FROM ".$_SESSION['rego']['payroll_dbase']." WHERE month = '".$_GET['m']."'";
	if($res = $dbc->query($sql)){
		while($row = $res->fetch_assoc()){
			foreach($cols as $k=>$v){
				$data[$row['emp_id']][$k] = $row[$k];
			}
		}
	}
