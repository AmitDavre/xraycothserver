<?php
	
	$fix = getFixAllowNames();
	$var = getVarAllowNames();

	$data = array();
	$payroll = array();
	$service_years = '';
	if(isset($_SESSION['rego']['report_id'])){
		$sql = "SELECT emp_id, title, th_name, en_name, joining_date, position FROM ".$_SESSION['rego']['emp_dbase']." WHERE emp_id = '".$_SESSION['rego']['report_id']."'";
		if($res = $dbc->query($sql)){
			if($row = $res->fetch_assoc()){
				$data = $row;
				$service_years = getAge($data['joining_date']);
			}
		}
		//var_dump($data); exit;
		$pr = array();
		$pr['paid_days'] = array(0=>$lng['Days paid'],1=>0,2=>0,3=>0,4=>0,5=>0,6=>0,7=>0,8=>0,9=>0,10=>0,11=>0,12=>0,13=>0);
		$pr['salary'] = array(0=>$lng['Salary'],1=>0,2=>0,3=>0,4=>0,5=>0,6=>0,7=>0,8=>0,9=>0,10=>0,11=>0,12=>0,13=>0);

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
			SUM(tot_deduct_before) as tot_deduct_before, 
			SUM(tot_deduct_after) as tot_deduct_after, 
			SUM(remaining_salary) as remaining_salary, 
			SUM(notice_payment) as notice_payment, 
			SUM(paid_leave) as paid_leave, 
			SUM(other_income) as other_income, 
			SUM(severance) as severance  
			FROM ".$_SESSION['rego']['payroll_dbase']." WHERE emp_id = '".$_SESSION['rego']['report_id']."'";
		if($res = $dbc->query($sql)){
			if($row = $res->fetch_assoc()){
				if($row['ot1'] > 0){$pr['ot1b'] = array(0=>$lng['OT 1'],1=>0,2=>0,3=>0,4=>0,5=>0,6=>0,7=>0,8=>0,9=>0,10=>0,11=>0,12=>0,13=>0);}
				if($row['ot15'] > 0){$pr['ot15b'] = array(0=>$lng['OT 1.5'],1=>0,2=>0,3=>0,4=>0,5=>0,6=>0,7=>0,8=>0,9=>0,10=>0,11=>0,12=>0,13=>0);}
				if($row['ot2'] > 0){$pr['ot2b'] = array(0=>$lng['OT 2'],1=>0,2=>0,3=>0,4=>0,5=>0,6=>0,7=>0,8=>0,9=>0,10=>0,11=>0,12=>0,13=>0);}
				if($row['ot3'] > 0){$pr['ot3b'] = array(0=>$lng['OT 3'],1=>0,2=>0,3=>0,4=>0,5=>0,6=>0,7=>0,8=>0,9=>0,10=>0,11=>0,12=>0,13=>0);}
				if($row['oot'] > 0){$pr['ootb'] = array(0=>$lng['Other OT'],1=>0,2=>0,3=>0,4=>0,5=>0,6=>0,7=>0,8=>0,9=>0,10=>0,11=>0,12=>0,13=>0);}
				
				if($row['fix1'] > 0){$pr['fix_allow_1'] = array(0=>$fix[1],1=>0,2=>0,3=>0,4=>0,5=>0,6=>0,7=>0,8=>0,9=>0,10=>0,11=>0,12=>0,13=>0);}
				if($row['fix2'] > 0){$pr['fix_allow_2'] = array(0=>$fix[2],1=>0,2=>0,3=>0,4=>0,5=>0,6=>0,7=>0,8=>0,9=>0,10=>0,11=>0,12=>0,13=>0);}
				if($row['fix3'] > 0){$pr['fix_allow_3'] = array(0=>$fix[3],1=>0,2=>0,3=>0,4=>0,5=>0,6=>0,7=>0,8=>0,9=>0,10=>0,11=>0,12=>0,13=>0);}
				if($row['fix4'] > 0){$pr['fix_allow_4'] = array(0=>$fix[4],1=>0,2=>0,3=>0,4=>0,5=>0,6=>0,7=>0,8=>0,9=>0,10=>0,11=>0,12=>0,13=>0);}
				if($row['fix5'] > 0){$pr['fix_allow_5'] = array(0=>$fix[5],1=>0,2=>0,3=>0,4=>0,5=>0,6=>0,7=>0,8=>0,9=>0,10=>0,11=>0,12=>0,13=>0);}
				if($row['fix6'] > 0){$pr['fix_allow_6'] = array(0=>$fix[6],1=>0,2=>0,3=>0,4=>0,5=>0,6=>0,7=>0,8=>0,9=>0,10=>0,11=>0,12=>0,13=>0);}
				if($row['fix7'] > 0){$pr['fix_allow_7'] = array(0=>$fix[7],1=>0,2=>0,3=>0,4=>0,5=>0,6=>0,7=>0,8=>0,9=>0,10=>0,11=>0,12=>0,13=>0);}
				if($row['fix8'] > 0){$pr['fix_allow_8'] = array(0=>$fix[8],1=>0,2=>0,3=>0,4=>0,5=>0,6=>0,7=>0,8=>0,9=>0,10=>0,11=>0,12=>0,13=>0);}
				if($row['fix9'] > 0){$pr['fix_allow_9'] = array(0=>$fix[9],1=>0,2=>0,3=>0,4=>0,5=>0,6=>0,7=>0,8=>0,9=>0,10=>0,11=>0,12=>0,13=>0);}
				if($row['fix10'] > 0){$pr['fix_allow_10'] = array(0=>$fix[10],1=>0,2=>0,3=>0,4=>0,5=>0,6=>0,7=>0,8=>0,9=>0,10=>0,11=>0,12=>0,13=>0);}
				if($row['var1'] > 0){$pr['var_allow_1'] = array(0=>$var[1],1=>0,2=>0,3=>0,4=>0,5=>0,6=>0,7=>0,8=>0,9=>0,10=>0,11=>0,12=>0,13=>0);}
				if($row['var2'] > 0){$pr['var_allow_2'] = array(0=>$var[2],1=>0,2=>0,3=>0,4=>0,5=>0,6=>0,7=>0,8=>0,9=>0,10=>0,11=>0,12=>0,13=>0);}
				if($row['var3'] > 0){$pr['var_allow_3'] = array(0=>$var[3],1=>0,2=>0,3=>0,4=>0,5=>0,6=>0,7=>0,8=>0,9=>0,10=>0,11=>0,12=>0,13=>0);}
				if($row['var4'] > 0){$pr['var_allow_4'] = array(0=>$var[4],1=>0,2=>0,3=>0,4=>0,5=>0,6=>0,7=>0,8=>0,9=>0,10=>0,11=>0,12=>0,13=>0);}
				if($row['var5'] > 0){$pr['var_allow_5'] = array(0=>$var[5],1=>0,2=>0,3=>0,4=>0,5=>0,6=>0,7=>0,8=>0,9=>0,10=>0,11=>0,12=>0,13=>0);}
				if($row['var6'] > 0){$pr['var_allow_6'] = array(0=>$var[6],1=>0,2=>0,3=>0,4=>0,5=>0,6=>0,7=>0,8=>0,9=>0,10=>0,11=>0,12=>0,13=>0);}
				if($row['var7'] > 0){$pr['var_allow_7'] = array(0=>$var[7],1=>0,2=>0,3=>0,4=>0,5=>0,6=>0,7=>0,8=>0,9=>0,10=>0,11=>0,12=>0,13=>0);}
				if($row['var8'] > 0){$pr['var_allow_8'] = array(0=>$var[8],1=>0,2=>0,3=>0,4=>0,5=>0,6=>0,7=>0,8=>0,9=>0,10=>0,11=>0,12=>0,13=>0);}
				if($row['var9'] > 0){$pr['var_allow_9'] = array(0=>$var[9],1=>0,2=>0,3=>0,4=>0,5=>0,6=>0,7=>0,8=>0,9=>0,10=>0,11=>0,12=>0,13=>0);}
				if($row['var10'] > 0){$pr['var_allow_10'] = array(0=>$var[10],1=>0,2=>0,3=>0,4=>0,5=>0,6=>0,7=>0,8=>0,9=>0,10=>0,11=>0,12=>0,13=>0);}
				if($row['remaining_salary'] > 0){$pr['remaining_salary'] = array(0=>$lng['Remaining salary'],1=>0,2=>0,3=>0,4=>0,5=>0,6=>0,7=>0,8=>0,9=>0,10=>0,11=>0,12=>0,13=>0);}
				
				if($row['tot_deduct_before'] > 0){$pr['tot_deduct_before'] = array(0=>$lng['Deduct before'],1=>0,2=>0,3=>0,4=>0,5=>0,6=>0,7=>0,8=>0,9=>0,10=>0,11=>0,12=>0,13=>0);}
				if($row['tot_deduct_after'] > 0){$pr['tot_deduct_after'] = array(0=>$lng['Deduct after'],1=>0,2=>0,3=>0,4=>0,5=>0,6=>0,7=>0,8=>0,9=>0,10=>0,11=>0,12=>0,13=>0);}
				
				if($row['notice_payment'] > 0){$pr['notice_payment'] = array(0=>$lng['Notice payment'],1=>0,2=>0,3=>0,4=>0,5=>0,6=>0,7=>0,8=>0,9=>0,10=>0,11=>0,12=>0,13=>0);}
				if($row['paid_leave'] > 0){$pr['paid_leave'] = array(0=>$lng['Paid leave'],1=>0,2=>0,3=>0,4=>0,5=>0,6=>0,7=>0,8=>0,9=>0,10=>0,11=>0,12=>0,13=>0);}
				if($row['other_income'] > 0){$pr['other_income'] = array(0=>$lng['Other income'],1=>0,2=>0,3=>0,4=>0,5=>0,6=>0,7=>0,8=>0,9=>0,10=>0,11=>0,12=>0,13=>0);}
				if($row['severance'] > 0){$pr['severance'] = array(0=>$lng['Severance'],1=>0,2=>0,3=>0,4=>0,5=>0,6=>0,7=>0,8=>0,9=>0,10=>0,11=>0,12=>0,13=>0);}
			}
		}
		//var_dump($col);
		
		$pr['social'] = array(0=>$lng['SSO'],1=>0,2=>0,3=>0,4=>0,5=>0,6=>0,7=>0,8=>0,9=>0,10=>0,11=>0,12=>0,13=>0);
		$pr['pvf_employee'] = array(0=>$lng['PVF'],1=>0,2=>0,3=>0,4=>0,5=>0,6=>0,7=>0,8=>0,9=>0,10=>0,11=>0,12=>0,13=>0);
		//$pr['tax'] = array(0=>$lng['Tax'],1=>0,2=>0,3=>0,4=>0,5=>0,6=>0,7=>0,8=>0,9=>0,10=>0,11=>0,12=>0,13=>0);
		$pr['tax_month'] = array(0=>$lng['Tax'],1=>0,2=>0,3=>0,4=>0,5=>0,6=>0,7=>0,8=>0,9=>0,10=>0,11=>0,12=>0,13=>0);
		$pr['gross_income'] = array(0=>$lng['Gross income'],1=>0,2=>0,3=>0,4=>0,5=>0,6=>0,7=>0,8=>0,9=>0,10=>0,11=>0,12=>0,13=>0);
		$pr['tot_deductions'] = array(0=>$lng['Total deductions'],1=>0,2=>0,3=>0,4=>0,5=>0,6=>0,7=>0,8=>0,9=>0,10=>0,11=>0,12=>0,13=>0);
		$pr['net_income'] = array(0=>$lng['Net income'],1=>0,2=>0,3=>0,4=>0,5=>0,6=>0,7=>0,8=>0,9=>0,10=>0,11=>0,12=>0,13=>0);
		
		
		$sql = "SELECT * FROM ".$_SESSION['rego']['payroll_dbase']." WHERE emp_id = '".$_SESSION['rego']['report_id']."'";
		if($res = $dbc->query($sql)){
			while($row = $res->fetch_assoc()){
				foreach($pr as $key=>$val){
					foreach($val as $k=>$v){
						$pr[$key][$row['month']] = (float)$row[$key];
					}
				}
			}
		}
		
		foreach($pr as $key=>$val){
			$tot = 0;
			foreach($val as $k=>$v){
				if($k > 0){$tot += $v;}
			}
			$pr[$key][13] = $tot;
		}
	}
	//var_dump($pr); exit;
	
	
	
	
	
	
	
	
	
	