<?php
	$fix = getFixAllowNames();
	$var = getVarAllowNames();
	
	$data = array();
	$xdata = array();
	$data['salary'] = array(0=>$lng['Salary'],1=>0,2=>0,3=>0,4=>0,5=>0,6=>0,7=>0,8=>0,9=>0,10=>0,11=>0,12=>0,13=>0);
	
	$sql = "SELECT 
		SUM(ot1b) as ot1, 
		SUM(ot15b) as ot15, 
		SUM(ot2b) as ot2, 
		SUM(ot3b) as ot3, 
		SUM(ootb) as oot, 
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
		SUM(tot_deduct_after) as tot_deduct_after, 
		SUM(gross_income) as gross_income 
		FROM ".$_SESSION['rego']['payroll_dbase'];
	if($res = $dbc->query($sql)){
		if($row = $res->fetch_assoc()){
			if($row['ot1'] > 0){$data['ot1b'] = array(0=>$lng['OT 1'],1=>0,2=>0,3=>0,4=>0,5=>0,6=>0,7=>0,8=>0,9=>0,10=>0,11=>0,12=>0,13=>0);}
			if($row['ot15'] > 0){$data['ot15b'] = array(0=>$lng['OT 1.5'],1=>0,2=>0,3=>0,4=>0,5=>0,6=>0,7=>0,8=>0,9=>0,10=>0,11=>0,12=>0,13=>0);}
			if($row['ot2'] > 0){$data['ot2b'] = array(0=>$lng['OT 2'],1=>0,2=>0,3=>0,4=>0,5=>0,6=>0,7=>0,8=>0,9=>0,10=>0,11=>0,12=>0,13=>0);}
			if($row['ot3'] > 0){$data['ot3b'] = array(0=>$lng['OT 3'],1=>0,2=>0,3=>0,4=>0,5=>0,6=>0,7=>0,8=>0,9=>0,10=>0,11=>0,12=>0,13=>0);}
			if($row['oot'] > 0){$data['ootb'] = array(0=>$lng['Other OT'],1=>0,2=>0,3=>0,4=>0,5=>0,6=>0,7=>0,8=>0,9=>0,10=>0,11=>0,12=>0,13=>0);}
			if($row['fix1'] > 0){$data['fix_allow_1'] = array(0=>$fix[1],1=>0,2=>0,3=>0,4=>0,5=>0,6=>0,7=>0,8=>0,9=>0,10=>0,11=>0,12=>0,13=>0);}
			if($row['fix2'] > 0){$data['fix_allow_2'] = array(0=>$fix[2],1=>0,2=>0,3=>0,4=>0,5=>0,6=>0,7=>0,8=>0,9=>0,10=>0,11=>0,12=>0,13=>0);}
			if($row['fix3'] > 0){$data['fix_allow_3'] = array(0=>$fix[3],1=>0,2=>0,3=>0,4=>0,5=>0,6=>0,7=>0,8=>0,9=>0,10=>0,11=>0,12=>0,13=>0);}
			if($row['fix4'] > 0){$data['fix_allow_4'] = array(0=>$fix[4],1=>0,2=>0,3=>0,4=>0,5=>0,6=>0,7=>0,8=>0,9=>0,10=>0,11=>0,12=>0,13=>0);}
			if($row['fix5'] > 0){$data['fix_allow_5'] = array(0=>$fix[5],1=>0,2=>0,3=>0,4=>0,5=>0,6=>0,7=>0,8=>0,9=>0,10=>0,11=>0,12=>0,13=>0);}
			if($row['fix6'] > 0){$data['fix_allow_6'] = array(0=>$fix[6],1=>0,2=>0,3=>0,4=>0,5=>0,6=>0,7=>0,8=>0,9=>0,10=>0,11=>0,12=>0,13=>0);}
			if($row['fix7'] > 0){$data['fix_allow_7'] = array(0=>$fix[7],1=>0,2=>0,3=>0,4=>0,5=>0,6=>0,7=>0,8=>0,9=>0,10=>0,11=>0,12=>0,13=>0);}
			if($row['fix8'] > 0){$data['fix_allow_8'] = array(0=>$fix[8],1=>0,2=>0,3=>0,4=>0,5=>0,6=>0,7=>0,8=>0,9=>0,10=>0,11=>0,12=>0,13=>0);}
			if($row['fix9'] > 0){$data['fix_allow_9'] = array(0=>$fix[9],1=>0,2=>0,3=>0,4=>0,5=>0,6=>0,7=>0,8=>0,9=>0,10=>0,11=>0,12=>0,13=>0);}
			if($row['fix10'] > 0){$data['fix_allow_10'] = array(0=>$fix[10],1=>0,2=>0,3=>0,4=>0,5=>0,6=>0,7=>0,8=>0,9=>0,10=>0,11=>0,12=>0,13=>0);}
			if($row['var1'] > 0){$data['var_allow_1'] = array(0=>$var[1],1=>0,2=>0,3=>0,4=>0,5=>0,6=>0,7=>0,8=>0,9=>0,10=>0,11=>0,12=>0,13=>0);}
			if($row['var2'] > 0){$data['var_allow_2'] = array(0=>$var[2],1=>0,2=>0,3=>0,4=>0,5=>0,6=>0,7=>0,8=>0,9=>0,10=>0,11=>0,12=>0,13=>0);}
			if($row['var3'] > 0){$data['var_allow_3'] = array(0=>$var[3],1=>0,2=>0,3=>0,4=>0,5=>0,6=>0,7=>0,8=>0,9=>0,10=>0,11=>0,12=>0,13=>0);}
			if($row['var4'] > 0){$data['var_allow_4'] = array(0=>$var[4],1=>0,2=>0,3=>0,4=>0,5=>0,6=>0,7=>0,8=>0,9=>0,10=>0,11=>0,12=>0,13=>0);}
			if($row['var5'] > 0){$data['var_allow_5'] = array(0=>$var[5],1=>0,2=>0,3=>0,4=>0,5=>0,6=>0,7=>0,8=>0,9=>0,10=>0,11=>0,12=>0,13=>0);}
			if($row['var6'] > 0){$data['var_allow_6'] = array(0=>$var[6],1=>0,2=>0,3=>0,4=>0,5=>0,6=>0,7=>0,8=>0,9=>0,10=>0,11=>0,12=>0,13=>0);}
			if($row['var7'] > 0){$data['var_allow_7'] = array(0=>$var[7],1=>0,2=>0,3=>0,4=>0,5=>0,6=>0,7=>0,8=>0,9=>0,10=>0,11=>0,12=>0,13=>0);}
			if($row['var8'] > 0){$data['var_allow_8'] = array(0=>$var[8],1=>0,2=>0,3=>0,4=>0,5=>0,6=>0,7=>0,8=>0,9=>0,10=>0,11=>0,12=>0,13=>0);}
			if($row['var9'] > 0){$data['var_allow_9'] = array(0=>$var[9],1=>0,2=>0,3=>0,4=>0,5=>0,6=>0,7=>0,8=>0,9=>0,10=>0,11=>0,12=>0,13=>0);}
			if($row['var10'] > 0){$data['var_allow_10'] = array(0=>$var[10],1=>0,2=>0,3=>0,4=>0,5=>0,6=>0,7=>0,8=>0,9=>0,10=>0,11=>0,12=>0,13=>0);}
			if($row['remaining_salary'] > 0){$data['remaining_salary'] = array(0=>$lng['Remaining salary'],1=>0,2=>0,3=>0,4=>0,5=>0,6=>0,7=>0,8=>0,9=>0,10=>0,11=>0,12=>0,13=>0);}
			if($row['notice_payment'] > 0){$data['notice_payment'] = array(0=>$lng['Notice payment'],1=>0,2=>0,3=>0,4=>0,5=>0,6=>0,7=>0,8=>0,9=>0,10=>0,11=>0,12=>0,13=>0);}
			if($row['paid_leave'] > 0){$data['paid_leave'] = array(0=>$lng['Paid leave'],1=>0,2=>0,3=>0,4=>0,5=>0,6=>0,7=>0,8=>0,9=>0,10=>0,11=>0,12=>0,13=>0);}
			if($row['other_income'] > 0){$data['other_income'] = array(0=>$lng['Other income'],1=>0,2=>0,3=>0,4=>0,5=>0,6=>0,7=>0,8=>0,9=>0,10=>0,11=>0,12=>0,13=>0);}
			if($row['severance'] > 0){$data['severance'] = array(0=>$lng['Severance'],1=>0,2=>0,3=>0,4=>0,5=>0,6=>0,7=>0,8=>0,9=>0,10=>0,11=>0,12=>0,13=>0);}
			if($row['tot_deduct_before'] > 0){$data['tot_deduct_before'] = array(0=>$lng['Deduct before'],1=>0,2=>0,3=>0,4=>0,5=>0,6=>0,7=>0,8=>0,9=>0,10=>0,11=>0,12=>0,13=>0);}
			if($row['tot_deduct_after'] > 0){$data['tot_deduct_after'] = array(0=>$lng['Deduct after'],1=>0,2=>0,3=>0,4=>0,5=>0,6=>0,7=>0,8=>0,9=>0,10=>0,11=>0,12=>0,13=>0);}
		}
	}

	$xdata['social_com'] = array(0=>$lng['SSO'].' '.$lng['Company'],1=>0,2=>0,3=>0,4=>0,5=>0,6=>0,7=>0,8=>0,9=>0,10=>0,11=>0,12=>0,13=>0);
	$xdata['pvf_employer'] = array(0=>$lng['PVF'].' '.$lng['Company'],1=>0,2=>0,3=>0,4=>0,5=>0,6=>0,7=>0,8=>0,9=>0,10=>0,11=>0,12=>0,13=>0);
	
	$sql = "SELECT * FROM ".$_SESSION['rego']['payroll_dbase'];
	if($res = $dbc->query($sql)){
		while($row = $res->fetch_assoc()){
			foreach($data as $key=>$val){
				$data[$key][$row['month']] += (float)$row[$key];
			}
			foreach($xdata as $key=>$val){
				$xdata[$key][$row['month']] += (float)$row[$key];
			}
		}
	}
	
	//var_dump($xdata); exit;
	/*$xdata['pvf_employer'] = $data['pvf_employer'];
	$xdata['social2'] = $data['social'];
	$xdata['social2'][0] = $lng['SSO'].' '.$lng['Company'];
	unset($data['pvf_employer'], $data['social']);*/
	
	$totals = array(1=>0,2=>0,3=>0,4=>0,5=>0,6=>0,7=>0,8=>0,9=>0,10=>0,11=>0,12=>0,13=>0);
	foreach($data as $key=>$val){
		$tot = 0;
		foreach($val as $k=>$v){
			if($k > 0){$tot += $v; $totals[$k] += $v;}
		}
		$data[$key][13] = $tot;
		$totals[13] += $tot;
	}
	
	$xtotals = $totals;
	foreach($xdata as $key=>$val){
		$tot = 0;
		foreach($val as $k=>$v){
			if($k > 0){$tot += $v; $xtotals[$k] += $v;}
		}
		$xdata[$key][13] = $tot;
		$xtotals[13] += $tot;
	}
