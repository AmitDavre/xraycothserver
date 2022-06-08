<?php

	if(session_id()==''){session_start();}
	ob_start();
	include("../../dbconnect/db_connect.php");
	include(DIR.'files/functions.php');
	//include(DIR.'time/functions.php');
	
	$time_period = getTimePeriod();
	$date_start = date('Y-m-d', strtotime($time_period['start']));
	$date_end = date('Y-m-d', strtotime($time_period['end']));
	
	$employees = getEmployees($cid,0);

	$var_allow = getUsedVarAllow($lang);
	$compensations = getCompensations();
	$reply['unlocked'] = 0;
	$reply['error'] = '';
	$reply['success'] = '';
	
	$sql = "SELECT COUNT(id) as qty, SUM(locked) as locked FROM ".$cid."_attendance WHERE date >= '".$date_start."' AND date <= '".$date_end."'";
	if($res = $dbc->query($sql)){
		if($row = $res->fetch_assoc()){
			$unlocked = $row['qty'] - $row['locked'];
			//var_dump($row['qty']);
			//var_dump($unlocked);
			if($unlocked > 0){
				$reply['unlocked'] = $unlocked;
				ob_clean(); 
				echo json_encode($reply);
				exit;
			}
		}
	}else{
		$reply['error'] = mysqli_error($dbc);
		ob_clean(); 
		echo json_encode($reply);
		exit;
	}
	
	$data = array();
	$sql = "SELECT COUNT(id) as days, 
		emp_id,
		personal,
		public,
		SUM(planned_hrs) as planned_hrs, 
		SUM(planned_days) as planned_days, 
		SUM(paid_days) as paid_days, 
		SUM(normal_hrs) as normal_hrs, 
		SUM(ot1) as ot1, 
		SUM(ot15) as ot15, 
		SUM(ot2) as ot2, 
		SUM(ot3) as ot3, 
		SUM(unpaid_late) as unpaid_late, 
		SUM(unpaid_early) as unpaid_early, 
		SUM(unpaid_leave) as unpaid_leave, 
		SUM(comp1) as comp1, 
		SUM(comp2) as comp2, 
		SUM(comp3) as comp3, 
		SUM(comp4) as comp4, 
		SUM(comp5) as comp5, 
		SUM(comp6) as comp6, 
		SUM(comp7) as comp7, 
		SUM(comp8) as comp8, 
		SUM(comp9) as comp9, 
		SUM(comp10) as comp10 
		FROM ".$cid."_attendance 
		WHERE date >= '".$date_start."' 
		AND date <= '".$date_end."' 
		AND locked = 1 
		GROUP BY emp_id";
	//echo $sql; exit;
	if($res = $dbc->query($sql)){
		while($row = $res->fetch_assoc()){
			$id = $row['emp_id'].$cur_month;
			$days = $row['days'];
			//$paid[$id] = round(((($row['personal'] + $row['normal_hrs'])/8) + $row['public']),2);
			if($employees[$row['emp_id']]['wage_type'] != 'month'){
				$data[$id]['paid_days'] = round(((($row['personal'] + $row['normal_hrs'])/8) + $row['public']),2);
			}
			//$paidhrs = round(($row['normal_hrs'] + $row['ot1'] + $row['ot15'] + $row['ot2'] + $row['ot3'] + ($row['public']*8)),2);
			//$data[$id]['paid_days'] = number_format(($paidhrs/8),2);
			//$data[$id]['paid_days'] = $row['days'];
			$data[$id]['ot1h'] = round($row['ot1'],2);
			$data[$id]['ot15h'] = round($row['ot15'],2);
			$data[$id]['ot2h'] = round($row['ot2'],2);
			$data[$id]['ot3h'] = round($row['ot3'],2);
			$data[$id]['absence'] = round($row['unpaid_leave'],2);
			$data[$id]['late_early'] = round(($row['unpaid_late']+$row['unpaid_early']),2);
			foreach($var_allow as $k=>$v){
				$allow[$k] = 0;
			}
			$steps = getEmployeeSteps($row['emp_id'], $cur_month, $cur_year);
			//var_dump($steps); 
			//var_dump($employees[$row['emp_id']]['wage_type']); 
			foreach($compensations as $k=>$v){
				$step = $steps[$k];
				if($v['type'] == 'permonth'){
					$qty = $days - $row['comp'.$k];
					//$data[$id][] = $qty;
					if($qty < $v['occurance']){ // YES
						if($v['compensation_type'] == 1){
							$allow[$v['allowance']] += $v['step1'];
							//$data[$id][] += $v['step1'];
						}else{
							if($step < $v['compensation_type']){
								$step ++;
								$allow[$v['allowance']] += $v['step'.$step];
							}
						}
					}else{ // NO
						if($v['compensation_type'] > 1){
							if($v['failure'] == 'decending'){
								if($step > 1){
									$step --;
									$allow[$v['allowance']] += $v['step'.$step];
								}
							}
						}
					}
				}else{
					$allow[$v['allowance']] += $row['comp'.$k] * $v['step1'];
				}
				$steps[$k] = $step;
			}
			
			$cm = $cur_month + 1;
			$cy = $cur_year;
			if($cm > 12){$cm = 1; $cy = $cur_year +1;}
			$emp_steps[$row['emp_id'].'_'.$cy.'_'.$cm] = $steps;
			
			foreach($allow as $ka=>$va){
				$data[$id]['var_allow_'.$ka] = number_format($va);
			}
		}
	}else{
		$reply['error'] = mysqli_error($dbc);
		ob_clean(); 
		echo json_encode($reply);
		exit;
	}
	//var_dump($emp_steps); exit; 
	
	//var_dump($data); exit; 
	
	foreach($data as $key=>$val){
		$sql = "UPDATE ".$_SESSION['rego']['payroll_dbase']." SET "; 
		foreach($val as $k=>$v){
			$sql .= $k." = '".round($v,2)."', ";
		}
		$sql = substr($sql,0,-2);
		$sql .= " WHERE id = '".$key."'";
		
		//echo $sql.'<br>';
		if(!$dbc->query($sql)){
			$reply['error'] = mysqli_error($dbc);
			ob_clean(); 
			echo json_encode($reply);
			exit;
		}
	}
	
	// UPDATE NEW STEPS IN EMPLOYEE REGISTER /////////////////////////////////////////////////////////////////////////
	foreach($emp_steps as $k=>$v){
		$dbc->query("INSERT INTO ".$cid."_employee_steps (id, steps) VALUES ('".$k."', '".$dbc->real_escape_string(serialize($v))."') ON DUPLICATE KEY UPDATE steps = VALUES(steps)");
		//$dbc->query("UPDATE ".$cid."_employees SET steps = '".$dbc->real_escape_string(serialize($v))."' WHERE emp_id = '".$k."'");
	}
	
	//var_dump($data); 
	//exit;
	
	ob_clean(); 
	$reply['success'] = 'success';
	echo json_encode($reply);
	exit;
	
	
	
	//echo json_encode((float)$row['basic_salary']);
	
	
	
