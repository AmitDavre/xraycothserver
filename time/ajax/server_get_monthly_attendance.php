<?

	if(session_id()==''){session_start();}
	ob_start();
	include('../../dbconnect/db_connect.php');
	include(DIR.'files/functions.php');
	include(DIR.'time/functions.php');
	
	//$_REQUEST['sdate'] = '2020-09-21';
	//$_REQUEST['edate'] = '2020-09-25';
	//$_REQUEST['type'] = 'hrs';

	$var_allow = getUsedVarAllow($lang);
	$compensations = getCompensations();
	//var_dump($var_allow);
	//var_dump($compensations);

	$date_start = date('Y-m-d', strtotime($_REQUEST['sdate']));
	$date_end = date('Y-m-d', strtotime($_REQUEST['edate']));

	$data = array();
	$emp_steps = array();
	$approved = 0;
	$rows = 0;
	$where = "WHERE date >= '".$date_start."' AND date <= '".$date_end."'";
	if($_REQUEST['team'] != 'all'){
		$where .= " AND shiftteam = '".$_REQUEST['team']."'";
	}
	$sql = "SELECT COUNT(id) as days, 
		SUM(approved) as approved, 
		emp_id, 
		en_name, 
		th_name, 
		plan, 
		SUM(planned_hrs) as planned_hrs, 
		SUM(planned_days) as planned_days, 
		SUM(paid_days) as paid_days, 
		SUM(normal_hrs) as normal_hrs, 
		SUM(planned_ot) as planned_ot, 
		SUM(ot1) as ot1, 
		SUM(ot15) as ot15, 
		SUM(ot2) as ot2, 
		SUM(ot3) as ot3, 
		SUM(paid_late) as paid_late, 
		SUM(paid_early) as paid_early, 
		SUM(unpaid_late) as unpaid_late, 
		SUM(unpaid_early) as unpaid_early, 
		SUM(public) as public, 
		SUM(personal) as personal, 
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
		FROM ".$cid."_attendance ".$where." GROUP BY emp_id";
	//echo $sql; exit;
	if($res = $dbc->query($sql)){
		$nr = 0;
		while($row = $res->fetch_assoc()){
			//$step = getEmployeeStep($row['emp_id']);
			$approved += $row['approved'];
			$rows += $row['days'];
			$days = $row['days'];
			$data[$nr][] = $row['emp_id'];
			$data[$nr][] = $row[$lang.'_name'];
			$paidhrs = round(($row['normal_hrs'] + $row['ot1'] + $row['ot15'] + $row['ot2'] + $row['ot3'] + ($row['public']*8)),2);
			if($_REQUEST['type'] == 'hrs'){
				$data[$nr][] = number_format($row['planned_hrs'],1);
				$data[$nr][] = number_format($row['normal_hrs'],2);
				$data[$nr][] = number_format($paidhrs,2);
			}else{
				$data[$nr][] = $row['planned_days'];
				$data[$nr][] = number_format(($row['normal_hrs']/8),2);
				$data[$nr][] = number_format(($paidhrs/8),2);
			}
			if($_REQUEST['type'] == 'hrs'){
				$data[$nr][] = round($row['ot1'],2);
				$data[$nr][] = round($row['ot15'],2);
				$data[$nr][] = round($row['ot2'],2);
				$data[$nr][] = round($row['ot3'],2);
				$data[$nr][] = round($row['unpaid_leave'],2);
			}else{
				$data[$nr][] = $row['ot1']/8;
				$data[$nr][] = $row['ot15']/8;
				$data[$nr][] = $row['ot2']/8;
				$data[$nr][] = $row['ot3']/8;
				$data[$nr][] = round(($row['unpaid_leave']/8),2);
			}
			if($_REQUEST['type'] == 'hrs'){
				$data[$nr][] = round(($row['unpaid_late']+$row['unpaid_early']),2);
				$data[$nr][] = round(($row['personal']),2);
				$data[$nr][] = round(($row['public']*8),2);
			}else{
				$data[$nr][] = round(($row['unpaid_late']+$row['unpaid_early']),2)/8;
				$data[$nr][] = round(($row['personal']/8),2);
				$data[$nr][] = $row['public'];
			}
			foreach($var_allow as $k=>$v){
				$allow[$k] = 0;
			}
			
			$steps = getEmployeeSteps($row['emp_id'], $cur_month, $cur_year);
			//var_dump($steps);
			//exit;
			foreach($compensations as $k=>$v){
				$step = $steps[$k];
				if($v['type'] == 'permonth'){
					$qty = $days - $row['comp'.$k];
					$data[$nr][] = $qty;
					if($qty < $v['occurance']){ // YES
						if($v['compensation_type'] == 1){
							$allow[$v['allowance']] += $v['step1'];
							$data[$nr][] += $v['step1'];
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
					$data[$nr][] = $row['comp'.$k];
					$allow[$v['allowance']] +=  $row['comp'.$k] * $v['step1'];
				}
				//$steps[$k] = $step;
			}
			//$emp_steps[$row['emp_id']] = $steps;
					
			foreach($allow as $ka=>$va){
				if($va == 0){
					$allow_cols[count($data[$nr])] = count($data[$nr]);
				}else{
					$allow_cols[count($data[$nr])] = 0;
				}
				$data[$nr][] = number_format($va);
			}
			$nr ++;
		}
	}else{
		var_dump(mysqli_error($dbc));
	}
	$allow_cols = array_filter($allow_cols); 
	
	//foreach($emp_steps as $k=>$v){
		//$dbc->query("UPDATE ".$cid."_employees SET new_steps = '".$dbc->real_escape_string(serialize($v))."'	WHERE emp_id = '".$k."'");
	//}
	
	$xdata['draw'] = 0;
	$xdata['cols'] = $allow_cols;
	$xdata['rows'] = $rows - $approved;
	$xdata['recordsTotal'] = 0;
	$xdata['recordsFiltered'] = 0;
	$xdata['data'] = $data;
	
	//var_dump($data); exit;
	ob_clean();
	echo json_encode($xdata);
	exit;
	
