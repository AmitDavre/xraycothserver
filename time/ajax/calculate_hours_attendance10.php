<?php

	if(session_id()==''){session_start(); ob_start();}
	include('../../dbconnect/db_connect.php');
	include(DIR.'files/functions.php');
	include(DIR.'time/functions.php');
	//var_dump($_REQUEST); exit;
	//var_dump(addHours('00:20','01:15')); exit;

	//$sdate = '2020-01-31';//date('Y-m-d', strtotime($_REQUEST['sdate']));
	//$edate = '2020-01-31';//date('Y-m-d', strtotime($_REQUEST['edate']));
	$sdate = date('Y-m-d', strtotime($_REQUEST['sdate']));
	$edate = date('Y-m-d', strtotime($_REQUEST['edate']));
	$dates = dateRange($sdate, $edate, '+1 day', 'Y-m-d');
	//var_dump($dates); exit;
	
	//$date = $_REQUEST['date'];
	$time_settings = getTimeSettings();
	//$compensations = unserialize($time_settings['compensations']);
	//var_dump($compensations); //exit;

	$var_allow = getUsedVarAllow('both');
	$compensations = getCompensations();
	//var_dump($var_allow); exit;
	//var_dump($compensations); //exit;
	
	$accept_late = $time_settings['accept_late']/60;
	$accept_early = $time_settings['accept_early']/60;
	$ot_start_after = $time_settings['ot_start_after'];
	//var_dump($ot_start_after);
	$ot_period = $time_settings['ot_period'];
	//var_dump($ot_period);
	$ot_roundup = $time_settings['ot_roundup'];
	$fbreak = ($time_settings['fixed_break'] == 'Y') ? true : false;
	//$fbreak = false;
	$otnd = $time_settings['otnd'];
	//var_dump($otnd);
	$otsa = unserialize($time_settings['otsa']);
	//var_dump($otsa);
	$otsu = unserialize($time_settings['otsu']);
	//var_dump($otsu);
	$othd = unserialize($time_settings['othd']);
	//var_dump($othd);

	$data = array();

	foreach($dates as $d){
		//var_dump($d);
		$holiday = getHolidayFromDate($cid, $d);
		$day = date('D', strtotime($d));
		//var_dump($holiday);
		/*$ot = array();
		if($day == 'Sat'){
			$ot = '1';//$otsa;
		}elseif($day == 'Sun'){
			$ot = '3';//$otsu;
		}else{
			$ot = $otnd;
		}
		if($holiday){
			$ot = '3';//$othd;
		}*/
		
		$data = array();
		if(isset($_REQUEST['id'])){
			$sql = "SELECT * FROM ".$cid."_attendance WHERE id = '".$_REQUEST['id']."'";
		}else{
			$sql = "SELECT * FROM ".$cid."_attendance WHERE date = '$d' AND approved = 0";
		}
		if($res = $dbc->query($sql)){
			while($row = $res->fetch_assoc()){
				//$data[] = $row;
				$data[$row['id']]['emp_id'] = $row['emp_id'];
				$data[$row['id']]['f1'] = $row['f1'];
				$data[$row['id']]['u1'] = $row['u1'];
				$data[$row['id']]['f2'] = $row['f2'];
				$data[$row['id']]['u2'] = $row['u2'];
				
				$data[$row['id']]['time_in'] = $row['f1'];
				$data[$row['id']]['time_out'] = $row['u2'];
				if($row['ot_from'] == $row['u2']){
					$data[$row['id']]['time_in'] = $row['f1'];
					$data[$row['id']]['time_out'] = $row['ot_until'];
				}
				if($row['ot_until'] == $row['f1']){
					$data[$row['id']]['time_in'] = $row['ot_from'];
					$data[$row['id']]['time_out'] = $row['u2'];
				}
				
				$data[$row['id']]['plan'] = $row['plan'];
				if(empty($row['plan'])){$data[$row['id']]['plan'] = 'OFF';}
				$data[$row['id']]['hd'] = $row['hd'];
				$data[$row['id']]['dnr'] = $row['dnr'];
				$data[$row['id']]['planned_hrs'] = $row['planned_hrs'];
				$data[$row['id']]['plan_ot'] = $row['plan_ot'];
				$data[$row['id']]['plan_break'] = $row['plan_break'];
				$data[$row['id']]['scan1'] = $row['scan1'];
				$data[$row['id']]['scan2'] = $row['scan2'];
				$data[$row['id']]['scan3'] = $row['scan3'];
				$data[$row['id']]['scan4'] = $row['scan4'];
				//$data[$row['id']]['calculate'] = $row['calculate'];
				
				$leaves = getLeaveData($d, $row['emp_id']);
				$data[$row['id']]['leavesum'] = 0;
				$data[$row['id']]['leavetype'] = '';
				$data[$row['id']]['leavepart'] = array();
				$data[$row['id']]['leavedays'] = array();
				$data[$row['id']]['leavehours'] = array();
				$data[$row['id']]['leavepaid'] = array();
				if($leaves){
					foreach($leaves as $key=>$val){
						foreach($val as $k=>$v){
							$data[$row['id']]['leavesum'] += $v['hours'];
							$data[$row['id']]['leavetype'] = implode('|',array_keys($val));
							$data[$row['id']]['leavepart'][] = $v['day'];
							$data[$row['id']]['leavedays'][] = $v['days'];
							$data[$row['id']]['leavehours'][] = $v['hours'];
							$data[$row['id']]['leavepaid'][] = $v['paid'];
						}	
						//var_dump($leavesum);
						//var_dump($leavetype);
						//var_dump($v['leavepart']);
						//var_dump($v['leavedays']);
						//var_dump($leavehours);
						//var_dump($v['leavepaid']);
					}
				}
				//var_dump($leaves);
			}
		}
		$shiftplan = getFullShiftplan();
		//var_dump($shiftplan); //exit;
		//var_dump($data); exit;
		
		foreach($data as $k=>$v){
			//var_dump($v['plan']); //exit;
			$scans = $shiftplan[$v['plan']]['scans'];
			$first = decimalHours($shiftplan[$v['plan']]['first'])*60*60;
			$second = decimalHours($shiftplan[$v['plan']]['second'])*60*60;
			//var_dump($first*60*60); //exit;
			//var_dump($second*60*60); //exit;
			
			$late = 0;
			$early = 0;
			$late1 = 0;
			$late2 = 0;
			$early1 = 0;
			$early2 = 0;
			
			$paid_late1 = 0;
			$unpaid_late1 = 0;
			$paid_early1 = 0;
			$unpaid_early1 = 0;
			
			$paid_late2 = 0;
			$unpaid_late2 = 0;
			$paid_early2 = 0;
			$unpaid_early2 = 0;
			
			$paid_late = 0;
			$unpaid_late = 0;
			$paid_early = 0;
			$unpaid_early = 0;
			
			$public = 0;
			$personal = 0;
			$unpaid_leave = 0;
			$plan_hrs = '';
			$actual_hrs = 0;
			$normal_hrs = 0;
			$before = 0;
			$after = 0;
			$xot['1'] = 0;
			$xot['1.5'] = 0;
			$xot['2'] = 0;
			$xot['3'] = 0;
			$break = decimalHours($v['plan_break'])*60*60;
			$dbreak = $break/60/60;
			$plan = $v['planned_hrs'];
			$calculate = false;

			$pl_in = strtotime($v['time_in']);
			$pl_out = strtotime($v['time_out']);

			if($scans == 4){ // 4 SCANS PER DAY BEGIN ////////////////////////////
				$plan_in1 = strtotime($v['f1']);
				$plan_out1 = strtotime($v['u1']);
				$plan_in2 = strtotime($v['f2']);
				$plan_out2 = strtotime($v['u2']);
				
				if(isValidDate($v['scan1']) && isValidDate($v['scan2']) && isValidDate($v['scan3']) && isValidDate($v['scan4'])){
					
					$time_in1 = strtotime($v['scan1']);
					$time_out1 = strtotime($v['scan2']);
					$time_in2 = strtotime($v['scan3']);
					$time_out2 = strtotime($v['scan4']);
					$calculate = true;
				}
			}else{ // 2 SCANS PER DAY BEGIN //////////////////////////////////////
				$plan_in1 = strtotime($v['f1']);
				$plan_out2 = strtotime($v['u2']);
				$plan_out1 = $plan_in1 + $first;;
				$plan_in2 = $plan_out2 - $second;
				
				if(isValidDate($v['scan1']) && isValidDate($v['scan2'])){	
					
					$scan1 = strtotime($v['scan1']);
					$scan2 = strtotime($v['scan2']);
					
					if($scan1 < $plan_out1 && $scan2 > $plan_in2){
						$time_in1 = $scan1;
						$time_out1 = $plan_out1;
						$time_in2 = $plan_in2;
						$time_out2 = $scan2;
					}
					if($scan2 <= $plan_in2){
						$time_in1 = $scan1;
						$time_out1 = $scan2;
						$time_in2 = $plan_in2;
						$time_out2 = $plan_in2;
					}
					if($scan1 >= $plan_out1){
						$time_in1 = $plan_out1;
						$time_out1 = $plan_out1;
						$time_in2 = $scan1;
						$time_out2 = $scan2;
					}
					$calculate = true;
				}
			}// SCANS PER DAY END //////////////////////////////////////
				
			if($calculate){
				
				if($time_in1 > $plan_in1){
					$late1 = ($time_in1-$plan_in1)/60/60;
				}else{
					$before += ($plan_in1-$time_in1)/60/60;
				}
				if($time_out1 < $plan_out1){
					$early1 = ($plan_out1-$time_out1)/60/60;
				}
				if($time_in2 > $plan_in2){
					$late2 = ($time_in2-$plan_in2)/60/60;
				}
				if($time_out2 < $plan_out2){
					$early2 = ($plan_out2-$time_out2)/60/60;
				}else{
					$after += ($time_out2-$plan_out2)/60/60;
				}
				
				if($time_in1 > $pl_in){
					$late1 = ($time_in1-$pl_in)/60/60;
				}
				if($time_out2 < $pl_out){
					$early2 = ($pl_out-$time_out2)/60/60;
				}
				
				if($late1 == 0 && $early1 == 0 && $late2 == 0 && $early2 == 0){
					$normal_hrs += $plan;
				}else{
					if($late1 <=  $accept_late){
						$t1 = $plan_in1;
						$paid_late1 += $late1;
					}else{
						$t1 = $time_in1;
						$unpaid_late1 += $late1;
					}
					if($early1 <=  $accept_early){
						$t2 = $plan_out1;
						$paid_early1 += $early1;
					}else{
						$t2 = $time_out1;
						$unpaid_early1 += $early1;
					}
					$normal_hrs += ($t2-$t1)/60/60;
					
					if($late2 <=  $accept_late){
						$t1 = $plan_in2;
						$paid_late2 += $late2;
					}else{
						$t1 = $time_in2;
						$unpaid_late2 += $late2;
					}
					if($early2 <=  $accept_early){
						$t2 = $plan_out2;
						$paid_early2 += $early2;
					}else{
						$t2 = $time_out2;
						$unpaid_early2 += $early2;
					}
					$normal_hrs += ($t2-$t1)/60/60;
				}
				
				if(array_sum($v['leavedays']) == 0.5){
					if($v['leavepart'][0] == 'first'){
						$early2 = 0;
						$unpaid_early2 = 0;
					}else{
						$late1 = 0;
						$unpaid_late1 = 0;
					}
				}

				$late = $late1 + $late2;
				$early = $early1 + $early2;
				$paid_late = $paid_late1 + $paid_late2;
				$unpaid_late = $unpaid_late1 + $unpaid_late2;
				$paid_early = $paid_early1 + $paid_early2;
				$unpaid_early = $unpaid_early1 + $unpaid_early2;
				
				if(array_sum($v['leavedays']) > 0 && array_sum($v['leavedays']) < 0.5){
					if($normal_hrs + $v['leavepart'][0] >= $plan){
						$late = 0;
						$early = 0;
						$paid_late = 0;
						$unpaid_late = 0;
						$paid_early = 0;
						$unpaid_early = 0;
					}
				}
				$actual_hrs += (($time_out1-$time_in1) + ($time_out2-$time_in2))/60/60;
			}		
			
			if($v['leavesum'] == 8){
				if(count($v['leavepaid']) == 1){
					if($v['leavepaid'][0] == 1){
						$normal_hrs = $plan;
						$personal += $normal_hrs;
					}else{
						$unpaid_leave += $plan;
					}
				}else{
					if($v['leavepaid'][0] == 1){
						if($v['leavepart'][0] == 'first'){
							$normal_hrs += $first/60/60;
						}else{
							$normal_hrs += $second/60/60;
						}
						$personal += $normal_hrs;
					}else{
						if($v['leavepart'][0] == 'first'){
							$unpaid_leave += $first/60/60;
						}else{
							$unpaid_leave += $second/60/60;
						}
					}
					if($v['leavepaid'][1] == 1){
						if($v['leavepart'][1] == 'first'){
							$normal_hrs += $first/60/60;
						}else{
							$normal_hrs += $second/60/60;
						}
						$personal += $normal_hrs;
					}else{
						if($v['leavepart'][1] == 'first'){
							$unpaid_leave += $first/60/60;
						}else{
							$unpaid_leave += $second/60/60;
						}
					}
				}
			}
			
			if(array_sum($v['leavedays']) == 0.5){
				if($v['leavepaid'][0] == 1){
					if($v['leavepart'][0] == 'first'){
						$normal_hrs += $first/60/60;
					}else{
						$normal_hrs += $second/60/60;
					}
					$personal += $normal_hrs;
				}
			}
			
			if(array_sum($v['leavedays']) > 0 && array_sum($v['leavedays']) < 0.5){
				if($v['leavepaid'][0] == 1){
					$normal_hrs += $v['leavepart'][0];
					if($normal_hrs > $plan){$normal_hrs = $plan;}
					$personal += $v['leavepart'][0];
				}else{
					$unpaid_leave += $v['leavepart'][0];
				}
			}
			
			$minutes = 0;
			$min = $before *60;
			//var_dump($min);
			if($min > $ot_start_after){
				if($ot_period > 0){
					$minutes += floor($min / $ot_period) * $ot_period;
				}else{
					$minutes += $min;
				}
			}
			//var_dump('minutes before : '.$min);
			
			$min = $after *60;
			if($min > $ot_start_after){
				if($ot_period > 0){
					$minutes += floor($min / $ot_period) * $ot_period;
				}else{
					$minutes += $min;
				}
			}
			//var_dump('Minutes OT : '.$minutes);
			if($minutes > 0){
				$minutes = $minutes /60;
			}
			
			//var_dump('minutes after : '.$min);
			
			$tot_hrs = $normal_hrs + $minutes;
			//var_dump($tot_hrs);
	
			if($v['hd'] == 1){
				$paid_late = 0;
				$unpaid_late = 0;
				$paid_early = 0;
				$unpaid_early = 0;
				$public = 1;
				if($othd['hrs'] == 0){$othd['hrs'] = $v['planned_hrs'];}
				if($tot_hrs > $othd['hrs']){
					$first = $othd['hrs'];
					$second = $tot_hrs - $first;
				}else{
					$first = $tot_hrs;
					$second = 0;
				}
				if($othd[1] != 0){
					$xot[$othd[1]] += $first;
				}
				if($othd[2] != 0){
					$xot[$othd[2]] += $second;
				}
				$normal_hrs = 0;
			}elseif($v['plan'] == 'OFF' && $v['dnr'] == 6){ // saterday
				$paid_late = 0;
				$unpaid_late = 0;
				$paid_early = 0;
				$unpaid_early = 0;
				if($otsa['hrs'] == 0){$otsa['hrs'] = $v['planned_hrs'];}
				if($tot_hrs > $otsa['hrs']){
					$first = $otsa['hrs'];
					$second = $tot_hrs - $first;
				}else{
					$first = $tot_hrs;
					$second = 0;
				}
				if($otsa[1] != 0){
					$xot[$otsa[1]] += $first;
				}
				if($otsa[2] != 0){
					$xot[$otsa[2]] += $second;
				}
				$normal_hrs = 0;
			}elseif($v['plan'] == 'OFF' && $v['dnr'] == 0){ // sunday
				$paid_late = 0;
				$unpaid_late = 0;
				$paid_early = 0;
				$unpaid_early = 0;
				if($otsu['hrs'] == 0){$otsu['hrs'] = $v['planned_hrs'];}
				if($tot_hrs > $otsu['hrs']){
					$first = $otsu['hrs'];
					$second = $tot_hrs - $first;
				}else{
					$first = $tot_hrs;
					$second = 0;
				}
				if($otsu[1] != 0){
					$xot[$otsu[1]] += $first;
				}
				if($otsu[2] != 0){
					$xot[$otsu[2]] += $second;
				}
				$normal_hrs = 0;
			}else{
				$xot[$otnd] += $minutes;
			}
			
			$xdata[$k]['emp_id'] = $v['emp_id'];
			$xdata[$k]['actual_hrs'] = $actual_hrs;
			$xdata[$k]['normal_hrs'] = $normal_hrs;
			$xdata[$k]['paid_hrs'] = $normal_hrs + $xot['1'] + $xot['1.5'] + $xot['2'] + $xot['3'];
			$xdata[$k]['paid_days'] = 0;
			if($actual_hrs > 0 || $normal_hrs > 0){
				$xdata[$k]['paid_days'] = 1;
			}
			$xdata[$k]['paid_late'] = $paid_late;
			$xdata[$k]['unpaid_late'] = $unpaid_late;
			//if($late == 0){$late = '-';}else{$late = dateHours($late);}
			//$xdata[$k]['late'] = $late;
			$xdata[$k]['paid_early'] = $paid_early;
			$xdata[$k]['unpaid_early'] = $unpaid_early;
			//if($early == 0){$early = '-';}else{$early = dateHours($early);}
			//$xdata[$k]['early'] = $early;
			$xdata[$k]['ot1'] = $xot['1'];
			$xdata[$k]['ot15'] = $xot['1.5'];
			$xdata[$k]['ot2'] = $xot['2'];
			$xdata[$k]['ot3'] = $xot['3'];
			//$xdata[$k]['calculate'] = 1;
			$xdata[$k]['unpaid_leave'] = $unpaid_leave;
			$xdata[$k]['public'] = $public;
			$xdata[$k]['personal'] = $personal;
			$xdata[$k]['leave_hrs'] = $v['leavesum'];
			$xdata[$k]['leave_type'] = $v['leavetype'];

			// CALCULATE COMPENSATIONS BEGIN ////////////////////////////
			foreach($shiftplan as $sk=>$sv){ 
				if($v['plan'] == $sk){
					//var_dump($sv['compensations']); //exit;
					if(!empty($sv['compensations'])){
						$tmp = explode(',', $sv['compensations']);
						$comps = array();
						if($tmp){
							foreach($tmp as $c){
								$comps[$c] = $compensations[$c];
							}
						}
						if($comps){
							foreach($comps as $key=>$val){
								$xdata[$k]['comp'.$key] = 0;
								if($val['condition'] == 'presence' && $actual_hrs > 0 && $actual_hrs > 0){
									$xdata[$k]['comp'.$key] = 1;
								}
								if($val['condition'] == 'nolateearly' && $xdata[$k]['unpaid_late'] == 0 && $xdata[$k]['unpaid_early'] == 0 && $actual_hrs > 0){
									$xdata[$k]['comp'.$key] = 1;
								}
							}
						}
					}
				}
			}
			// CALCULATE COMPENSATIONS END ////////////////////////////
		}
	
	}	
	//var_dump($xdata);
	//exit;
			
	foreach($xdata as $key=>$val){
		$sql = "UPDATE ".$cid."_attendance SET ";
		foreach($val as $k=>$v){
			$sql .= "$k = '".$dbc->real_escape_string($v)."', ";
		}
		$sql = substr($sql, 0, -2);
		$sql .= " WHERE id = '$key'";
		if(!$res = $dbc->query($sql)){
			var_dump(mysqli_error($dbc));
		}
	}











