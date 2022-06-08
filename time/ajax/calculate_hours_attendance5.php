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
	
	//$date = $_REQUEST['date'];
	$time_settings = getTimeSettings();
	$compensations = unserialize($time_settings['compensations']);
	//$var_allow = unserialize($time_settings['var_allow']);
	//var_dump($compensations[1]); //exit;
	
	//$scans = $time_settings['scans'];
	
	$accept_late = $time_settings['accept_late'];
	$accept_early = $time_settings['accept_early'];
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
		$sql = "SELECT * FROM ".$cid."_attendance WHERE date = '$d' AND status = 0";
		if($res = $dbc->query($sql)){
			while($row = $res->fetch_assoc()){
				//$data[] = $row;
				$data[$row['id']]['emp_id'] = $row['emp_id'];
				$data[$row['id']]['f1'] = $row['f1'];
				$data[$row['id']]['u1'] = $row['u1'];
				$data[$row['id']]['f2'] = $row['f2'];
				$data[$row['id']]['u2'] = $row['u2'];
				$data[$row['id']]['plan'] = $row['plan'];
				$data[$row['id']]['hd'] = $row['hd'];
				$data[$row['id']]['dnr'] = $row['dnr'];
				$data[$row['id']]['plan_rh'] = $row['plan_rh'];
				$data[$row['id']]['plan_ot'] = $row['plan_ot'];
				$data[$row['id']]['plan_break'] = $row['plan_break'];
				$data[$row['id']]['scan1'] = $row['scan1'];
				$data[$row['id']]['scan2'] = $row['scan2'];
				$data[$row['id']]['scan3'] = $row['scan3'];
				$data[$row['id']]['scan4'] = $row['scan4'];
				$data[$row['id']]['calculate'] = $row['calculate'];
			}
		}
		$shiftplan = getFullShiftplan();
		//var_dump($shiftplan); exit;
		
		//var_dump($data); exit;
		$late = array();
		$early = array();
		
		foreach($data as $k=>$v){
			
			//$scans = getScansPerDay($v['plan']);
			//var_dump($v); //exit;
			$scans = $shiftplan[$v['plan']]['scans'];
			
			$late = '00:00';
			$early = '00:00';
			//var_dump($k); //exit;
			$actual_hrs = '0:00';
			$actual_hours = 0;
			$actual_deci = 0;
			$paid_hrs = '0:00';
			$paid_hours = 0;
			$paid_deci = 0;
			$a = new DateTime('0000-00-00 00:00:00');
			$p = new DateTime('0000-00-00 00:00:00');
			
			$xot['1'] = 0;
			$xot['1.5'] = 0;
			$xot['2'] = 0;
			$xot['3'] = 0;

			//$scans = 4;
			
			if($scans == 44){ // 4 SCANS PER DAY //////////////////////////////////////
				$plan_in1 = new DateTime($v['f1']);
				$plan_out1 = new DateTime($v['u1']);
				//var_dump($v['f1']);
				//var_dump($plan_out1);
				$plan_in2 = new DateTime($v['f2']);
				$plan_out2 = new DateTime($v['u2']);
				$before = '00:00';
				$after = '00:00';
				
				if(isValidDate($v['scan1']) && isValidDate($v['scan2'])){
					$time_in = new DateTime($v['scan1']);
					$time_out = new DateTime($v['scan2']);
					$diff = $time_in->diff($time_out);
					$actual1 = $diff->format('%H:%I'); //----------------------------- actual hours
					
					//var_dump($actual1);
					if($time_in > $plan_in1){ //-------------------------------------- to late
						$tmp = $time_in->diff($plan_in1);
						$dif = ($tmp->h*60) + $tmp->i;
						if($dif > $accept_late){
							$late = addHours($late,$tmp->format('%H:%I'));
						}
						$stime_in = $time_in;
					}else{
						$stime_in = $plan_in1;
						$before = $time_in->diff($plan_in1)->format('%r%H:%I'); //------- OT before
					}
					if($time_out < $plan_out1){ //------------------------------------ to early
						$tmp = $time_out->diff($plan_out1);
						$dif = ($tmp->h*60) + $tmp->i;
						if($dif > $accept_early){
							$early = addHours($early,$tmp->format('%H:%I'));
						}
						$stime_out = $time_out;
					}else{
						$stime_out = $plan_out1;
					}
					$paid1 = $stime_in->diff($stime_out)->format('%H:%I'); // -------- paid hours
					//var_dump($paid1);
					//var_dump($stime_out);
				}else{
					$actual1 = '00:00';
					$paid1 = '00:00';
				}
				
				if(isValidDate($v['scan3']) && isValidDate($v['scan4'])){		
					
					$time_in = new DateTime($v['scan3']);
					$time_out = new DateTime($v['scan4']);
					$diff = $time_in->diff($time_out);
					$actual2 = $diff->format('%H:%I'); //----------------------------- actual hours
					
					$after = $plan_out2->diff($time_out)->format('%r%H:%I'); //--------- OT after
					//var_dump($plan_out2->diff($time_out));
					
					if($time_in > $plan_in2){ //-------------------------------------- to late
						$tmp = $time_in->diff($plan_in2);
						$dif = ($tmp->h*60) + $tmp->i;
						if($dif > $accept_late){
							$late = addHours($late,$tmp->format('%H:%I'));
						}
						$stime_in = $time_in;
					}else{
						$stime_in = $plan_in2;
					}
					if($time_out < $plan_out2){ //------------------------------------ to early
						$tmp = $time_out->diff($plan_out2);
						$dif = ($tmp->h*60) + $tmp->i;
						if($dif > $accept_early){
							$early = addHours($early,$tmp->format('%H:%I'));
						}
						$stime_out = $time_out;
					}else{
						$stime_out = $plan_out2;
					}
					$paid2 = $stime_in->diff($stime_out)->format('%H:%I'); // -------- paid hours
					//var_dump($paid2);
				}else{
					$actual2 = '00:00';
					$paid2 = '00:00';
				}
				
				$a = new DateTime($actual1);
				$aa = new DateInterval("P0000-00-00T$actual2:00");
				$a->add($aa);
				$actual_hrs = intHours($a->format('H:i'));
				$actual_deci = decimalHours($actual_hrs);
				//var_dump('Actual : '.$actual_hrs.' | '.$actual_deci);
				
				$p = new DateTime($paid1);
				$pp = new DateInterval("P0000-00-00T$paid2:00");
				$p->add($pp);
				$paid_hrs = intHours($p->format('H:i'));
				$paid_deci = decimalHours($paid_hrs);
				//var_dump('Paid : '.$paid_hrs.' | '.$paid_deci);
				
				$before_deci = decimalHours($before);
				$after_deci = decimalHours($after);

				//var_dump('Before : '.$before.' | '.$before_deci);
				//var_dump('After : '.$after.' | '.$after_deci);
				//var_dump('---------------------------------------------');
			
			
			}else{ // 2 SCANS PER DAY //////////////////////////////////////
				$plan_in = new DateTime($v['f1']);
				$plan_out = new DateTime($v['u2']);
				$actual1 = '';
				$paid1 = '';
				$before = '00:00';
				$after = '00:00';
				//var_dump($actual_hours);
				//var_dump(isValidDate($v['scan1']));
				
				if(isValidDate($v['scan1']) && isValidDate($v['scan2'])){	
					//var_dump($actual_hours);
					//var_dump($v['plan']);
					$time_in = new DateTime($v['scan1']);
					$time_out = new DateTime($v['scan2']);
					$diff = $time_in->diff($time_out);
					$actual1 = $diff->format('%H:%I'); //---------------------------- actual hours
					$before = '00:00'; //-------------------------------------------- OT before
					$after = $plan_out->diff($time_out)->format('%H:%I'); //--------- OT after
					//var_dump($before);
					if($time_in > $plan_in){ //-------------------------------------- to late
						$tmp = $time_in->diff($plan_in);
						$dif = ($tmp->h*60) + $tmp->i;
						if($dif > $accept_late){
							//var_dump($tmp->format('%h:%I')); //exit;
							//var_dump(decimalHours($tmp->format('%h:%I'))); //exit;
							
							$late += decimalHours($tmp->format('%h:%I'));
						}
						$stime_in = $time_in;
					}else{
						$stime_in = $plan_in;
						$before = $plan_in->diff($time_in)->format('%H:%I'); //------- OT before
					}
					if($time_out < $plan_out){ //------------------------------------ to early
						$tmp = $time_out->diff($plan_out);
						$dif = ($tmp->h*60) + $tmp->i;
						if($dif > $accept_early){
							//var_dump($tmp->format('%h:%I'));
							$early += decimalHours($tmp->format('%h:%I'));
						}
						$stime_out = $time_out;
					}else{
						$stime_out = $plan_out;
					}
					$break = $v['plan_break'];
					
					$paidx = $stime_in->diff($stime_out)->format('%H:%I'); // -------- paid hours
					
					$a = new DateTime($paidx);
					$aa = new DateInterval("P0000-00-00T$break:00");
					$a->sub($aa);
					$paid1 = intHours($a->format('H:i'));
					//$actual_deci = decimalHours($actual_hrs);
					//var_dump($paidx);
					//var_dump($paid1);
				//}
				//var_dump($actual1);
				//if(!empty($actual1)){
					$a = new DateTime($actual1);
					$p = new DateTime($paid1);
					$actual_hrs = intHours($a->format('H:i'));
					$actual_deci = decimalHours($actual_hrs);
					$paid_hrs = intHours($p->format('H:i'));
					$paid_deci = decimalHours($paid_hrs);
					$before_deci = decimalHours($before);
					$after_deci = decimalHours($after);
				//}else{
				
				//}
			//}
			
					$minutes = 0;
					$min = $before_deci * 60;
					//var_dump($min);
					if($min > $ot_start_after){
						$minutes += floor($min / $ot_period) * $ot_period;
					}
					$min = $after_deci * 60;
					if($min > $ot_start_after){
						$minutes += floor($min / $ot_period) * $ot_period;
					}
					//var_dump('Minutes OT : '.$minutes);
					
					if($minutes > 0){
						$minutes = $minutes /60;
					}
					
					$tot_hrs = $paid_deci + $minutes;
					//var_dump($tot_hrs);
			
					if($v['hd'] == 1){
						if($othd['hrs'] == 0){$othd['hrs'] = $v['plan_rh'];}
						if($tot_hrs > $othd['hrs']){
							$first = $othd['hrs'];
							$second = $tot_hrs - $first;
						}else{
							$first = $tot_hrs;
							$second = 0;
						}
						if($othd[1] != 0){
							//$oot = $first * ($othd[1]-1);
							$xot[$othd[1]] += $first;
						}
						if($othd[2] != 0){
							//$oot = $second * ($othd[2]-1);
							$xot[$othd[2]] += $second;
						}
						$paid_deci = 0;
						$paid_hours = 0;
					}elseif($v['dnr'] == 6){ // saterday
						if($otsa['hrs'] == 0){$otsa['hrs'] = $v['plan_rh'];}
						if($tot_hrs > $otsa['hrs']){
							$first = $otsa['hrs'];
							$second = $tot_hrs - $first;
						}else{
							$first = $tot_hrs;
							$second = 0;
						}
						if($otsa[1] != 0){
							//$oot = $first * ($otsa[1]-1);
							$xot[$otsa[1]] += $first;
						}
						if($otsa[2] != 0){
							//$oot = $second * ($otsa[2]-1);
							$xot[$otsa[2]] += $second;
						}
						$paid_deci = 0;
						$paid_hours = 0;
					}elseif($v['dnr'] == 0){ // sunday
						if($otsu['hrs'] == 0){$otsu['hrs'] = $v['plan_rh'];}
						if($tot_hrs > $otsu['hrs']){
							$first = $otsu['hrs'];
							$second = $tot_hrs - $first;
						}else{
							$first = $tot_hrs;
							$second = 0;
						}
						if($otsu[1] != 0){
							//$oot = $first * ($otsu[1]-1);
							$xot[$otsu[1]] += $first;
						}
						if($otsu[2] != 0){
							//$oot = $second * ($otsu[2]-1);
							$xot[$otsu[2]] += $second;
						}
						$paid_deci = 0;
						$paid_hours = 0;
					}else{
						$xot[$otnd] += $minutes;
					}
					/*if($minutes > 0){
						$minutes = $minutes /60;
						if($v['plan'] == 'HD'){
							$xot[$othd[2]] += $minutes * ($othd[2]-1);
						}elseif($v['dnr'] == 6){ // saterday
							$xot[$otsa[2]] += $minutes;
						}elseif($v['dnr'] == 0){ // sunday
							$xot[$otsu[2]] += $minutes;
						}else{
							$xot[$otnd] += $minutes;
						}
					}*/
					//var_dump($xot);
					//var_dump($v['plan'].$actual_hrs);
					//var_dump($v['plan'].' - '.$actual_hrs);
				}
				//var_dump($actual_hours);
				//var_dump($v['plan'].' - '.$actual_hrs);
			}
			//var_dump($actual_hrs);
			
			$xdata[$k]['emp_id'] = $v['emp_id'];
			if($actual_hrs == '0:00'){$actual_hrs = '-';}
			$xdata[$k]['actual_hrs'] = dateHours($actual_deci);
			if($paid_hrs == '0:00'){$paid_hrs = '-';}
			$xdata[$k]['paid_hrs'] = dateHours($paid_deci);
			$xdata[$k]['deci_late'] = decimalHours($late);
			if($late == '00:00'){$late = '-';}else{$late = dateHours($late);}
			$xdata[$k]['late'] = $late;
			$xdata[$k]['deci_early'] = decimalHours($early);
			if($early == '00:00'){$early = '-';}else{$early = dateHours($early);}
			$xdata[$k]['early'] = $early;
			$xdata[$k]['ot1'] = dateHours($xot['1']);
			$xdata[$k]['ot15'] = dateHours($xot['1.5']);
			$xdata[$k]['ot2'] = dateHours($xot['2']);
			$xdata[$k]['ot3'] = dateHours($xot['3']);
			$xdata[$k]['deci_ot1'] = $xot['1'];
			$xdata[$k]['deci_ot15'] = $xot['1.5'];
			$xdata[$k]['deci_ot2'] = $xot['2'];
			$xdata[$k]['deci_ot3'] = $xot['3'];
			$xdata[$k]['calculate'] = 1;
		
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
								if($val['condition'] == 'presence' && $actual_deci > 0 && $actual_deci > 0){
									$xdata[$k]['comp'.$key] = 1;
								}
								if($val['condition'] == 'nolateearly' && $xdata[$k]['deci_late'] == 0 && $xdata[$k]['deci_early'] == 0 && $actual_deci > 0){
									var_dump('nolateearly');
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
	//var_dump($othd);
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














