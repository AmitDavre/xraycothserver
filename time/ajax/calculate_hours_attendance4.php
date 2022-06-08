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
	//$var_allow = unserialize($time_settings['var_allow']);
	//var_dump($time_settings);
	
	$scans = $time_settings['scans'];
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
		$holiday = getHolidayFromDate($cid, $d);
		$day = date('D', strtotime($d));
		//var_dump($holiday);
		$ot = array();
		if($day == 'Sat'){
			$ot = '1';//$otsa;
		}elseif($day == 'Sun'){
			$ot = '3';//$otsu;
		}else{
			$ot = $otnd;
		}
		if($holiday){
			$ot = '3';//$othd;
		}
		
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

		//var_dump($data); //exit;
		$late = array();
		$early = array();
		
		foreach($data as $k=>$v){
			if($v['plan'] == 'HD'){
			
			}
			
			$late = '00:00';
			$early = '00:00';
			//var_dump($k); //exit;
			$actual_hours = 0;
			$paid_hours = 0;
			$a = new DateTime('0000-00-00 00:00:00');
			$p = new DateTime('0000-00-00 00:00:00');
			
			$xot['1'] = '-';
			$xot['1.5'] = '-';
			$xot['2'] = '-';
			$xot['3'] = '-';

			$dot['1'] = 0;
			$dot['1.5'] = 0;
			$dot['2'] = 0;
			$dot['3'] = 0;
			
			$scans = 4;
			if($scans == 4){
				
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
						$before = $plan_in1->diff($time_in)->format('%H:%I'); //------- OT before
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
					$after = $plan_out2->diff($time_out)->format('%H:%I'); //--------- OT after
					//var_dump($actual2);
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
				//var_dump($actual_hrs);
				
				$p = new DateTime($paid1);
				$pp = new DateInterval("P0000-00-00T$paid2:00");
				$p->add($pp);
				$paid_hrs = intHours($p->format('H:i'));
				//var_dump($paid_hrs);
				
			}else{
				
				$plan_in = new DateTime($v['f1']);
				$plan_out = new DateTime($v['u2']);
				
				if(isValidDate($v['scan1']) && isValidDate($v['scan4'])){		
					$time_in = new DateTime($v['scan1']);
					$time_out = new DateTime($v['scan4']);
					$diff = $time_in->diff($time_out);
					$actual1 = $diff->format('%H:%I'); //---------------------------- actual hours
					$before = '00:00'; //-------------------------------------------- OT before
					$after = $plan_out->diff($time_out)->format('%H:%I'); //--------- OT after
					//var_dump($before);
					if($time_in > $plan_in){ //-------------------------------------- to late
						$tmp = $time_in->diff($plan_in);
						$dif = ($tmp->h*60) + $tmp->i;
						if($dif > $accept_late){
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
							$early += decimalHours($tmp->format('%h:%I'));
						}
						$stime_out = $time_out;
					}else{
						$stime_out = $plan_out;
					}
					$paid1 = $stime_in->diff($stime_out)->format('%H:%I'); // -------- paid hours
					//var_dump($paid1);
					//var_dump($stime_out);
				}
				$a = new DateTime($actual1);
				$p = new DateTime($paid1);
				$actual_hrs = intHours($a->format('H:i'));
				$paid_hrs = intHours($p->format('H:i'));
			}
			//var_dump($a->format('H:i'));
			//var_dump($late.' - '.$early);
			//var_dump($v['plan']);
			
			//$ot = '-';
			$minutes = 0;
			$sot = '-';
			$x = explode(':',$before);
			$min = ((int)$x[0]*60) + (int)$x[1];
			//var_dump($min);
			if($min > $ot_start_after){
				$minutes += floor($min/$ot_period)*$ot_period;
			}
			$x = explode(':',$after);
			$min = ((int)$x[0]*60) + (int)$x[1];
			if($min > $ot_start_after){
				$minutes += floor($min/$ot_period)*$ot_period;
			}
			if($minutes > 0){
				$zero    = new DateTime('@0');
				$offset  = new DateTime('@' . $minutes * 60);
				$diff    = $zero->diff($offset);
				$sot = $diff->format('%h:%I');
				$xot[$ot] = $sot;
				$dot[$ot] = decimalHours($sot);
			}
			//var_dump($xot);
			
			$xdata[$k]['emp_id'] = $v['emp_id'];
			if($actual_hrs == '0:00'){$actual_hrs = '-';}
			$xdata[$k]['actual_hrs'] = $actual_hrs;
			if($paid_hrs == '0:00'){$paid_hrs = '-';}
			$xdata[$k]['paid_hrs'] = $paid_hrs;
			$xdata[$k]['deci_late'] = round(decimalHours($late),2);
			if($late == '00:00'){$late = '-';}
			$xdata[$k]['late'] = $late;
			$xdata[$k]['deci_early'] = round(decimalHours($early),2);
			if($early == '00:00'){$early = '-';}
			$xdata[$k]['early'] = $early;
			$xdata[$k]['ot1'] = $xot['1'];
			$xdata[$k]['ot15'] = $xot['1.5'];
			$xdata[$k]['ot2'] = $xot['2'];
			$xdata[$k]['ot3'] = $xot['3'];
			$xdata[$k]['deci_ot1'] = $dot['1'];
			$xdata[$k]['deci_ot15'] = $dot['1.5'];
			$xdata[$k]['deci_ot2'] = $dot['2'];
			$xdata[$k]['deci_ot3'] = $dot['3'];
			$xdata[$k]['calculate'] = 1;
		
		}
		var_dump($xdata); //exit;
	}
	var_dump($othd);



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
	exit;
	
	
	
	
	
	
	
	
	
	
	
	
	
	foreach($xdata as $key=>$val){
		$sql = "UPDATE ".$cid."_attendance SET ";
		foreach($val as $k=>$v){
			$sql .= "$k = '".$dbc->real_escape_string($v)."', ";
		}
		$sql = substr($sql, 0, -2);
		$sql .= " WHERE id = '$key'";
		$res = $dbc->query($sql);
	}
	//echo mysqli_error($dbc);
	exit;
	//var_dump($xdata); exit;
	//var_dump($data); exit;
	
	
	
	$holiday = getHolidayFromDate($cid, $date);
	$day = date('D', strtotime($date));
	//var_dump($time_settings['otnd']);
	//var_dump(unserialize($time_settings['otsa']));
	//var_dump(unserialize($time_settings['otsu']));
	//var_dump(unserialize($time_settings['othd']));
	
	if($day == 'Sat'){
		$ot = unserialize($time_settings['otsa']);
	}elseif($day == 'Sun'){
		$ot = unserialize($time_settings['otsu']);
	}else{
		$ot = $time_settings['otnd'];
	}
	if($holiday){
		$ot = unserialize($time_settings['othd']);
	}
	
	//var_dump($fixed_break); //exit;
	//var_dump($holiday); //exit;
	//var_dump($time_settings); exit;
	//var_dump($ot); //exit;
	

	$sdate = date('Y-m-d', strtotime($date));//'2018-01-01';
	//var_dump($sdate);
	//$edate = date('Y-m-d', strtotime($_REQUEST['edate']));//'2018-01-31';
	
	//$sql = "SELECT * FROM ".$cid."_attendance WHERE (date BETWEEN '$sdate' AND '$edate') AND status = '0'";
	$sql = "SELECT * FROM ".$cid."_attendance WHERE status = 0";
	if($res = $dbc->query($sql)){
		while($row = $res->fetch_assoc()){
			//$data[] = $row;
			$data[$row['id']]['emp_id'] = $row['emp_id'];
			$data[$row['id']]['f1'] = $row['f1'];
			$data[$row['id']]['u1'] = $row['u1'];
			$data[$row['id']]['f2'] = $row['f2'];
			$data[$row['id']]['u2'] = $row['u2'];
			$data[$row['id']]['plan_rh'] = $row['plan_rh'];
			$data[$row['id']]['plan_ot'] = $row['plan_ot'];
			$data[$row['id']]['plan_break'] = $row['plan_break'];
			$data[$row['id']]['scan1'] = $row['scan1'];
			$data[$row['id']]['scan2'] = $row['scan2'];
			$data[$row['id']]['scan3'] = $row['scan3'];
			$data[$row['id']]['scan4'] = $row['scan4'];
		}
	}
	//var_dump($data); exit;
	
	foreach($data as $k=>$v){
		$late = 0;
		$early = 0;
		$actual_hours = 0;
		$paid_hours = 0;
		$ot1 = 0;
		$ot15 = 0;
		$ot2 = 0;
		$ot3 = 0;
		
		$plan_in = $v['f1'];
		$plan_out = $v['u2'];
		$plan_rh = ltrim($v['plan_rh'],'0');
		$plan_break = $v['plan_break'];

		$start_time = new DateTime($plan_in);
		$end_time = new DateTime($plan_out);
		
		if(isValidDate($v['scan1']) && isValidDate($v['scan4'])){		
			$break_in = new DateTime($v['scan2']);
			$break_out = new DateTime($v['scan3']);
			$actual_break = $break_in->diff($break_out);
			
			list($hours, $minutes) = sscanf($plan_break, '%d:%d');
			$fixed_break = new DateInterval(sprintf('PT%dH%dM', $hours, $minutes));
			
			//var_dump($fixed_break);
			//var_dump($actual_break);

			$time_in = new DateTime($v['scan1']);
			$time_out = new DateTime($v['scan4']);
			$diff = $time_in->diff($time_out);
			
			
			
			$actual_hours = $diff->format('%h:%I'); //------------------- actual hours
			//var_dump($actual_hours);
			$actual_date = new DateTime($actual_hours);
			//var_dump($actual_date);
			if($fbreak){
				$actual_date->sub($fixed_break); // substract breaktime
			}else{
				$actual_date->sub($actual_break); // substract breaktime
			}
			$actual_hours = $actual_date->format('G:i'); //------------------- actual hours

			//var_dump($actual_hours);
			
			//var_dump($actual_date);
		}
		
		if(!empty($v['plan_rh'])){
			if(isValidDate($v['scan1']) && isValidDate($v['scan4'])){	
				$time_in = new DateTime($v['scan1']);
				$time_out = new DateTime($v['scan4']);
				
				if($time_in > $start_time){ //----------------------------- to late
					$tmp = $time_in->diff($start_time);
					$dif = ($tmp->h*60) + $tmp->i;
					if($dif < $accept_late){
						$late = 0;
					}else{
						$late = $tmp->format('%h:%I');
					}
					$stime_in = $time_in;
				}else{ //--------------------------------------------- ok
					$late = 0;
					$stime_in = $start_time;
				}
				if($time_out < $end_time){ //------------------------------ to early
					$tmp = $time_out->diff($end_time);
					$dif = ($tmp->h*60) + $tmp->i;
					if($dif < $accept_early){
						$early = 0;
					}else{
						$early = $tmp->format('%h:%I');
					}
					$stime_out = $time_out;
				}else{ //--------------------------------------------- ok
					$early = 0;
					$stime_out = $end_time;
				}
				
				list($hours, $minutes) = sscanf($plan_break, '%d:%d');
				$break = new DateInterval(sprintf('PT%dH%dM', $hours, $minutes));
				//var_dump($break);
				//$a = new DateTime($v['scan1']);
				//var_dump($a);
				//$a->add($break); // substract breaktime
				//$a->sub(new DateInterval('PT30M')); // substract breaktime
				//var_dump($a);
				//$diff = $a->diff($time_out);
				//var_dump($diff);
				
				$paid_hours = $plan_rh; //----------------------------------- paid hours from shiftplan // if not late and not early
				//var_dump($actual_hours);
				//var_dump($paid_hours);
				
				if($late == '0' && $early == '0'){ //------------------------ not late & not early
					//var_dump('in time');
					//$paid_hours = $plan_rh;//.' - in time';
				}elseif($late != '0' && $early == '0'){ //------------------- late but not early
					//var_dump('late');
					$x = new DateTime($v['scan1']);
					$x->add($break); // substract breaktime
					$diff = $x->diff($stime_out);
					$paid_hours = $diff->format('%h:%I');//.' - late';
				}elseif($late == '0' && $early != '0'){ //------------------- not late and not early
					//var_dump('early');
					$x = new DateTime($plan_in);
					$x->add($break); // substract breaktime
					$diff = $x->diff($time_out);
					$paid_hours = $diff->format('%h:%I');//.' - early';
				}elseif($late != '0' && $early != '0'){ //------------------- late & early
					//var_dump('late & early');
					$x = new DateTime($v['scan1']);
					$x->add($break); // substract breaktime
					$diff = $x->diff($time_out);
					$paid_hours = $diff->format('%h:%I');//.' - late & early';
				}
				
				
				if(!empty($plan_rh)){
					$ah = new DateTime($actual_hours);
					$ph = new DateTime($paid_hours);
					$diff = $ah->diff($ph);
					$tot_ot_time = $diff->format('%h:%I'); //------------------- actual overtime
					//var_dump($ah);
					//var_dump($ph);
					//var_dump($diff);
					$check = $diff->i + ($diff->h*60);
					//var_dump($check);
					//var_dump($paid_hours);
					$xot = array('1'=>'-','1.5'=>'-','2'=>'-','3'=>'-');
					if($check > ($ot_start_after + $ot_period)){
						$ot_time = new DateTime($tot_ot_time);
						//var_dump($ot_time);
						//$ot_time->sub(new DateInterval('PT'.$ot_start_after.'M'));
						//var_dump($ot_time);
						$min = $ot_time->format('i') + ($ot_time->format('h')*60);
						//var_dump($min);
						$tmp = $min / $ot_period;
						//var_dump($tmp);
						$dec = $tmp - (int)$tmp;
						//var_dump($dec);
						$lost_ot = round($dec * $ot_period);
						//var_dump($lost_ot);
						$ot_time->sub(new DateInterval('PT'.$lost_ot.'M'));
						//var_dump($ot_time->format('H:i'));
						
						if(isset($ot['hrs'])){
							if($ot[1] != 0){
								if($ot['hrs'] == 0){
									//var_dump($paid_hours);
									$xot[$ot[1]] = $paid_hours;
								}else{
									//var_dump($paid_hours);
									$xot[$ot[1]] = $paid_hours;
								}
							}
							if($ot[2] != 0){
								$xot[$ot[2]] = $ot_time->format('G:i');
							}
						}else{
							//$xot['1'] = '-';
							$xot[$ot] = ($ot_time->format('H:i') != '00:00') ? $ot_time->format('G:i') : '-';
							//$xot['2'] = '-';
							//$xot['3'] = '-';
						}
						
						//$ot1 = $paid_hours;
						//$ot15 = ($ot_time->format('H:i') != '00:00') ? $ot_time->format('G:i') : '-';
						//$ot2 = '-';
						//$ot3 = '-';
					}else{
						if(isset($ot['hrs'])){
							if($ot[1] != 0){
								if($ot['hrs'] == 0){
									$xot[$ot[1]] = $paid_hours;
								}else{
									$xot[$ot[1]] = $paid_hours;
								}
							}
						}else{
							//$xot['1'] = '-';
							//$xot[$ot] = ($ot_time->format('H:i') != '00:00') ? $ot_time->format('G:i') : '-';
							//$xot['2'] = '-';
							//$xot['3'] = '-';
						}
						//$ot1 = $paid_hours;
						//$ot15 = '-';
						//$ot2 = '-';
						//$ot3 = '-';
					}
				}
			}
		}
		//var_dump($xot);
		
		$xdata[$k]['emp_id'] = $v['emp_id'];
		$xdata[$k]['actual_hrs'] = $actual_hours;
		$xdata[$k]['paid_hrs'] = $paid_hours;
		$xdata[$k]['deci_late'] = round(decimalHours($late),2);
		if($late == '0'){$late = '-';}
		$xdata[$k]['late'] = $late;
		$xdata[$k]['deci_early'] = round(decimalHours($early),2);
		if($early == '0'){$early = '-';}
		$xdata[$k]['early'] = $early;
		$xdata[$k]['ot1'] = $xot[1];
		$xdata[$k]['ot15'] = $xot['1.5'];
		$xdata[$k]['ot2'] = $xot[2];
		$xdata[$k]['ot3'] = $xot[3];
		$xdata[$k]['deci_ot1'] = round(decimalHours($xot[1]),2);
		$xdata[$k]['deci_ot15'] = round(decimalHours($xot['1.5']),2);
		$xdata[$k]['deci_ot2'] = round(decimalHours($xot[2]),2);
		$xdata[$k]['deci_ot3'] = round(decimalHours($xot[3]),2);
	}
	/*foreach($xdata as $k=>$v){
		$emp_allow = getVarAllowEmployee($v['emp_id']);
		//var_dump($emp_allow);
		if($var_allow['dilligence'] == 1){
			$xdata[$k]['dilligence'] = $emp_allow['dilligence'];
		}
		if($var_allow['shift'] == 1){
			$xdata[$k]['shift'] = $emp_allow['shift'];
		}
		if($var_allow['transport'] == 1){
			$xdata[$k]['transport'] = $emp_allow['transport'];
		}
		if($var_allow['meal'] == 1){
			$xdata[$k]['meal'] = $emp_allow['meal'];
		}
		if($var_allow['phone'] == 1){
			$xdata[$k]['phone'] = $emp_allow['phone'];
		}
	}*/
	
	//var_dump($xdata); 
	//exit;

	foreach($xdata as $key=>$val){
		$sql = "UPDATE ".$cid."_attendance SET ";
		foreach($val as $k=>$v){
			$sql .= "$k = '".$dbc->real_escape_string($v)."', ";
		}
		$sql = substr($sql, 0, -2);
		$sql .= " WHERE id = '$key'";
		$res = $dbc->query($sql);
	}
	//echo mysqli_error($dbc).'<br>';
	//echo $sql; exit;

















