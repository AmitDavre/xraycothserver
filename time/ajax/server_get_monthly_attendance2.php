<?

	if(session_id()==''){session_start();}
	ob_start();
	include('../../dbconnect/db_connect.php');
	include(DIR.'files/functions.php');
	include(DIR.'time/functions.php');
	
	$_REQUEST['sdate'] = '2020-09-21';
	$_REQUEST['edate'] = '2020-09-25';
	$_REQUEST['type'] = 'hrs';

	$var_allow = getUsedVarAllow($lang);
	$compensations = getCompensations();
	
	$date_start = date('Y-m-d', strtotime($_REQUEST['sdate']));
	$date_end = date('Y-m-d', strtotime($_REQUEST['edate']));

	$data = array();
	$sql = "SELECT COUNT(id) as days, 
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
		FROM ".$cid."_attendance WHERE scan1 !='-' AND date >= '".$date_start."' AND date <= '".$date_end."' GROUP BY emp_id";
	//echo $sql; exit;
	if($res = $dbc->query($sql)){
		while($row = $res->fetch_assoc()){
			$step = getEmployeeStep($row['emp_id']);
			$days = $row['days'];
			$data[$row['emp_id']]['emp_id'] = $row['emp_id'];
			$data[$row['emp_id']]['en_name'] = $row[$lang.'_name'];
			$paidhrs = round(($row['normal_hrs'] + $row['ot1'] + $row['ot15'] + $row['ot2'] + $row['ot3'] + ($row['public']*8)),2);
			if($_REQUEST['type'] == 'hrs'){
				$data[$row['emp_id']]['planned'] = number_format($row['planned_hrs'],1);
				$data[$row['emp_id']]['normal'] = number_format($row['normal_hrs'],2);
				$data[$row['emp_id']]['paid'] = number_format($paidhrs,2);
			}else{
				$data[$row['emp_id']]['planned'] = $row['planned_days'];
				$data[$row['emp_id']]['normal'] = number_format(($row['normal_hrs']/8),2);
				$data[$row['emp_id']]['paid'] = number_format(($paidhrs/8),2);
			}
			if($_REQUEST['type'] == 'hrs'){
				$data[$row['emp_id']]['ot1h'] = $row['ot1'];
				$data[$row['emp_id']]['ot15h'] = $row['ot15'];
				$data[$row['emp_id']]['ot2h'] = $row['ot2']; 
				$data[$row['emp_id']]['ot3h'] = $row['ot3'];
				$data[$row['emp_id']]['absence'] = round($row['unpaid_leave'],2);
			}else{
				$data[$row['emp_id']]['ot1h'] = $row['ot1']/8;
				$data[$row['emp_id']]['ot15h'] = $row['ot15']/8;
				$data[$row['emp_id']]['ot2h'] = $row['ot2']/8;
				$data[$row['emp_id']]['ot3h'] = $row['ot3']/8;
				$data[$row['emp_id']]['absence'] = round(($row['unpaid_leave']/8),2);
			}
			if($_REQUEST['type'] == 'hrs'){
				//$data[$row['emp_id']]['late_early_paid'] = round(($row['paid_late']+$row['paid_early']),2);
				$data[$row['emp_id']]['late_early_unpaid'] = round(($row['unpaid_late']+$row['unpaid_early']),2);
				$data[$row['emp_id']]['personal'] = round(($row['personal']),2);
				$data[$row['emp_id']]['public'] = round(($row['public']*8),2);
			}else{
				//$data[$row['emp_id']]['late_early_paid'] = round(($row['paid_late']+$row['paid_early']),2)/8;
				$data[$row['emp_id']]['late_early_unpaid'] = round(($row['unpaid_late']+$row['unpaid_early']),2)/8;
				$data[$row['emp_id']]['personal'] = round(($row['personal']/8),2);
				$data[$row['emp_id']]['public'] = $row['public'];
			}
			foreach($var_allow as $k=>$v){
				$allow[$k] = 0;
				$allow_cols[$k] = 0;
			}
			foreach($compensations as $k=>$v){
				if($v['type'] == 'permonth'){
					$qty = $days - $row['comp'.$k];
					$data[$row['emp_id']]['comp'.$k] = $qty;
					if($qty < $v['occurance']){ // YES
						if($v['compensation_type'] == 1){
							$allow[$v['allowance']] += $v['step1'];
							$data[$row['emp_id']]['var_allow_'.$v['allowance']] += $v['step1'];
							$allow_cols[$v['allowance']] = $v['allowance'];
						}else{
							if($step < $v['compensation_type']){
								$step ++;
								//$data[$row['emp_id']]['var_allow_'.$v['allowance']] += $v['step'.$step];
								$allow[$v['allowance']] += $v['step'.$step];
								$allow_cols[$v['allowance']] = $v['allowance'];
							}
						}
					}else{ // NO
						if($v['compensation_type'] > 1){
							if($v['failure'] == 'decending'){
								if($step > 1){
									$step --;
									$allow[$v['allowance']] += $v['step'.$step];
									$allow_cols[$v['allowance']] = $v['allowance'];
								}
							}
						}
					}
				}else{
					$data[$row['emp_id']]['comp'.$k] = $row['comp'.$k];
					//$data[$row['emp_id']]['var_allow_'.$v['allowance']] +=  $row['comp'.$k] * $v['step1'];
					$allow[$v['allowance']] +=  $row['comp'.$k] * $v['step1'];
					$allow_cols[$v['allowance']] = $v['allowance'];
				}
			}
			//$data[$row['emp_id']]['step'] = $step;
			foreach($allow as $ka=>$va){
				//if($allow_cols[$ka] > 0){
					$data[$row['emp_id']]['var_allow_'.$ka] = $va;
				//}
			}
			//var_dump($allow); //exit;
		}
	}else{
		var_dump(mysqli_error($dbc));
	}
	$allow_cols = array_filter($allow_cols); 
	$allow_cols = implode(',',$allow_cols); 
	
	//var_dump($allow_cols); //exit;
	//var_dump($data); exit;
	
	$_data = array();
	$nr = 0;
	foreach($data as $key=>$val){
		foreach($val as $k=>$v){
			$_data[$nr][] = $v;
		}
		$nr ++;
	}
	$xdata['draw'] = 0;
	$xdata['cols'] = $allow_cols;
	$xdata['recordsTotal'] = 0;
	$xdata['recordsFiltered'] = 0;
	$xdata['data'] = $_data;
	
	//var_dump($xdata); exit;
	ob_clean();
	echo json_encode($xdata);
	exit;
	
