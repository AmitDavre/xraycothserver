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
	//$compensations = unserialize($time_settings['compensations']);
	//var_dump($compensations); //exit;

	$var_allow = getUsedVarAllow('both');
	$compensations = getCompensations();
	//var_dump($var_allow); exit;
	//var_dump($compensations); //exit;
	
	$sql = "UPDATE ".$cid."_payroll_months SET 
		var_allowances = '".$dbc->real_escape_string(serialize($var_allow))."',
		compensations = '".$dbc->real_escape_string(serialize($compensations))."' 
		WHERE month = '".$cur_year.'_'.$cur_month."'";
		if(!$res = $dbc->query($sql)){
			var_dump(mysqli_error($dbc));
		}
	//var_dump($sql); //exit;
	//exit;
	
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
		//var_dump($shiftplan); //exit;
		
		foreach($data as $k=>$v){
			
			$scans = $shiftplan[$v['plan']]['scans'];
			$first = decimalHours($shiftplan[$v['plan']]['first'])*60*60;
			$second = decimalHours($shiftplan[$v['plan']]['second'])*60*60;
			//var_dump($first*60*60); //exit;
			//var_dump($second*60*60); //exit;
			
			$late = 0;
			$early = 0;
			$plan_hrs = '';
			$actual = 0;
			$actual_hrs = '-';
			$paid = 0;
			$paid_hrs = '-';
			$before = 0;
			$after = 0;
			$xot['1'] = 0;
			$xot['1.5'] = 0;
			$xot['2'] = 0;
			$xot['3'] = 0;
			$break = decimalHours($v['plan_break'])*60*60;
			$dbreak = $break/60/60;
			$plan = decimalHours($v['plan_rh']);

			if($scans == 4){ // 4 SCANS PER DAY BEGIN ////////////////////////////
				$plan_in1 = strtotime($v['f1']);
				$plan_out1 = strtotime($v['u1']);
				$plan_in2 = strtotime($v['f2']);
				$plan_out2 = strtotime($v['u2']);
				$late1 = 0;
				$late2 = 0;
				$early1 = 0;
				$early2 = 0;
				
				if(isValidDate($v['scan1']) && isValidDate($v['scan2']) && isValidDate($v['scan3']) && isValidDate($v['scan4'])){
					
					$time_in1 = strtotime($v['scan1']);
					$time_out1 = strtotime($v['scan2']);
					$time_in2 = strtotime($v['scan3']);
					$time_out2 = strtotime($v['scan4']);
					
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
				
					if($late1 == 0 && $early1 == 0 && $late2 == 0 && $early2 == 0){
						$paid += $plan;
						$paid_hrs = dateHours($paid);
					}else{
						if($late1 == 0){
							$t1 = $plan_in1;
						}else{
							$t1 = $time_in1;
						}
						if($early1 == 0){
							$t2 = $plan_out1;
						}else{
							$t2 = $time_out1;
						}
						$paid += ($t2-$t1)/60/60;
						if($late2 == 0){
							$t1 = $plan_in2;
						}else{
							$t1 = $time_in2;
						}
						if($early2 == 0){
							$t2 = $plan_out2;
						}else{
							$t2 = $time_out2;
						}
						$paid += ($t2-$t1)/60/60;
						//$paid -= $break;
					}
					
					$late = $late1 + $late2;
					$early = $early1 + $early2;
					$paid_hrs = dateHours($paid);
					
					$actual += (($time_out1-$time_in1) + ($time_out2-$time_in2))/60/60;
					$actual_hrs = dateHours($actual);
					
					/*var_dump('late 1 : '.$late1);
					var_dump('early 1 : '.$early1);
					var_dump('late 2 : '.$late2);
					var_dump('early 2 : '.$early2);
					var_dump('paid hrs : '.$paid_hrs);
					var_dump('before : '.dateHours($before));
					var_dump('after : '.dateHours($after));
					var_dump('late : '.$late.' min');
					var_dump('early : '.$early.' min');
					var_dump('actual hrs : '.$actual_hrs);
					var_dump('actual : '.$actual);
					echo '<br>';*/

				}
			
			}else{ // 2 SCANS PER DAY BEGIN //////////////////////////////////////
				
				$plan_in = strtotime($v['f1']);
				$plan_out = strtotime($v['u2']);
				
				$pl_in1 = strtotime($v['f1']);
				$pl_out2 = strtotime($v['u2']);
				$pl_out1 = $pl_in1 + $first;
				$pl_in2 = $pl_out2 - $second;
				
				//var_dump($pl_out1-$pl_in1);
				//var_dump($pl_out1);
				//var_dump($pl_out2 - $pl_in2);
				//var_dump($pl_out2);
				//echo '<br>';
				
				if(isValidDate($v['scan1']) && isValidDate($v['scan2'])){	
					
					$time_in = strtotime($v['scan1']);
					$time_out = strtotime($v['scan2']);

					if($time_in > $plan_in && $time_in <= $pl_out1){
						$late = ($time_in-$plan_in)/60/60;
					}elseif($time_in >= $pl_out1 && $time_in <= $pl_in2){
						$late = ($first/60/60);
					}elseif($time_in > $pl_in2){
						$late = ($time_in-$plan_in-$break)/60/60;
					}
					
					if($time_out <= $pl_out1){
						$early = ($plan_out-$time_out-$break)/60/60;
					}elseif($time_out > $pl_out1 && $time_out <= $pl_out1+$break){
						$early = ($second/60/60);
					}else{
						$early = ($plan_out-$time_out)/60/60;
					}
					
					if($time_in > $plan_in){
						$before = ($plan_in-$time_in)/60/60;
					}
					if($time_out > $plan_out){
						$after = ($time_out-$plan_out)/60/60;
					}

					//$actual += ($time_out-$time_in-$break)/60/60;
					$actual += ($time_out-$time_in)/60/60;
					//var_dump($actual); //exit;
					//var_dump($first+$break/60/60); //exit;
					if($actual >= ($first+$break)){
						//$actual -= ($break/60/60);
						//var_dump($actual); //exit;
					}
					
					$actual_hrs = dateHours($actual);
					
					if($late == 0 && $early == 0){
						$paid += $plan;
						$paid_hrs = dateHours($paid);
					}else{
						if($late == 0){
							$t1 = $plan_in;
						}else{
							$t1 = $time_in;
						}
						if($early == 0){
							$t2 = $plan_out;
						}else{
							$t2 = $time_out;
						}
						$paid += ($t2-$t1-$break)/60/60;
						$paid_hrs = dateHours($paid);
					}
					
					
					
				}
			}// SCANS PER DAY END //////////////////////////////////////

			$minutes = 0;
			$min = $before *60;
			//var_dump($min);
			if($min > $ot_start_after){
				$minutes += floor($min / $ot_period) * $ot_period;
			}
			//var_dump('minutes before : '.$min);
			
			$min = $after *60;
			if($min > $ot_start_after){
				$minutes += floor($min / $ot_period) * $ot_period;
			}
			//var_dump('Minutes OT : '.$minutes);
			if($minutes > 0){
				$minutes = $minutes /60;
			}
			
			//var_dump('minutes after : '.$min);
			
			$tot_hrs = $paid + $minutes;
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
					$xot[$othd[1]] += $first;
				}
				if($othd[2] != 0){
					$xot[$othd[2]] += $second;
				}
				$paid = 0;
				$paid_hrs = '-';
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
					$xot[$otsa[1]] += $first;
				}
				if($otsa[2] != 0){
					$xot[$otsa[2]] += $second;
				}
				$paid = 0;
				$paid_hrs = '-';
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
					$xot[$otsu[1]] += $first;
				}
				if($otsu[2] != 0){
					$xot[$otsu[2]] += $second;
				}
				$paid = 0;
				$paid_hrs = '-';
			}else{
				$xot[$otnd] += $minutes;
			}
			
			$xdata[$k]['emp_id'] = $v['emp_id'];
			$xdata[$k]['actual_hrs'] = $actual_hrs;
			$xdata[$k]['paid_hrs'] = $paid_hrs;
			$xdata[$k]['deci_late'] = $late;
			if($late == 0){$late = '-';}else{$late = dateHours($late);}
			$xdata[$k]['late'] = $late;
			$xdata[$k]['deci_early'] = $early;
			if($early == 0){$early = '-';}else{$early = dateHours($early);}
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
								if($val['condition'] == 'presence' && $actual > 0 && $actual > 0){
									$xdata[$k]['comp'.$key] = 1;
								}
								if($val['condition'] == 'nolateearly' && $xdata[$k]['deci_late'] == 0 && $xdata[$k]['deci_early'] == 0 && $actual > 0){
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











