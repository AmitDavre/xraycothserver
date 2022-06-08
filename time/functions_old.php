<?php
	
	function getWorkingHours($cid){
		global $dbc;
		$data = array();
		$sql = "SELECT `shiftplan` FROM `".$cid."_leave_time_settings`";
		if($res = $dbc->query($sql)){
			if($row = $res->fetch_assoc()){
				$data = unserialize($row['shiftplan']);
			}
		}
		return $data;
	}

	function getCompensations(){
		global $cid;
		global $dbc;
		$data = array();
		$sql = "SELECT `compensations` FROM `".$cid."_leave_time_settings`";
		if($res = $dbc->query($sql)){
			if($row = $res->fetch_assoc()){
				$data = unserialize($row['compensations']);
				foreach($data as $k=>$v){
					if($v['apply'] == 0){
						unset($data[$k]);
					}
				}
			}
		}
		return $data;
	}

	function hoursRange($lower = 0, $upper = 86400, $step = 1800, $format = 'h:i a'){
		 $times = array();
		 foreach(range($lower, $upper, $step) as $increment){
			  $increment = gmdate('H:i', $increment);
			  list($hour, $minutes) = explode(':', $increment);
			  $date = new DateTime($hour . ':' . $minutes);
			  $times[(string)$increment] = $date->format($format);
		 }
		 return $times;
	}	

	function getTimeSettings(){
		global $dbc;
		$row = array();
		if($res = $dbc->query("SELECT * FROM ".$_SESSION['rego']['cid']."_leave_time_settings")){
			$row = $res->fetch_assoc();
		}
		return $row;
	}

	function getEmployeeNameId($cid){
		global $dbc;
		$data = array();
		$res = $dbc->query("SELECT emp_id, en_name FROM ".$cid."_employees ORDER BY emp_id ASC");
		if($res->num_rows > 0){
			while($row = $res->fetch_object()){
				$data[$row->emp_id] = $row->en_name;
			}
		}
		ksort($data);
		return $data;
	}

	function getEmployeesBySID($cid){
		global $dbc;
		$data = array();
		//$res = $dbc->query("SELECT * FROM ".$cid."_employees WHERE emp_group = '".$_SESSION['xhr']['emp_group']."' ORDER BY emp_id ASC");
		$res = $dbc->query("SELECT * FROM ".$cid."_employees ORDER BY emp_id ASC");
		//$res = $dbc->query("SELECT * FROM ".$cid."_employees ORDER BY emp_id ASC");
		if($res->num_rows > 0){
			$nr = 1;
			while($row = $res->fetch_assoc()){
				//$row['sid'] = $nr;
				$data[$row['sid']]['emp_id'] = $row['emp_id'];
				$data[$row['sid']]['title'] = $row['title'];
				$data[$row['sid']]['firstname'] = $row['firstname'];
				$data[$row['sid']]['lastname'] = $row['lastname'];
				$data[$row['sid']]['th_name'] = $row['th_name'];
				$data[$row['sid']]['en_name'] = $row['en_name'];
				$data[$row['sid']]['shiftplan'] = $row['shiftplan'];
				$nr ++;
			}
		}
		return $data;
	}

	function getEmployee($id){
		global $dbc;
		global $cid;
		$data = array();
		$res = $dbc->query("SELECT * FROM ".$cid."_employees WHERE emp_id = '$id'");
		if($res->num_rows > 0){
			if($row = $res->fetch_assoc()){
				$data['emp_id'] = $row['title'];
				$data['title'] = $row['title'];
				$data['firstname'] = $row['firstname'];
				$data['lastname'] = $row['lastname'];
				$data['th_name'] = $row['th_name'];
				$data['en_name'] = $row['en_name'];
				$data['shiftplan'] = $row['shiftplan'];
			}
		}
		return $data;
	}
	
	function getEmployeeSteps($id, $month, $year){
		global $dbc;
		global $cid;
		$sid = $id.'_'.$year.'_'.$month;
		$steps = array();
		if($res = $dbc->query("SELECT * FROM ".$cid."_employee_steps WHERE id = '$sid'")){
			if($row = $res->fetch_assoc()){
				$steps = unserialize($row['steps']);
			}
		}
		if(!$steps){
			$steps = unserialize('a:10:{i:1;i:0;i:2;i:0;i:3;i:0;i:4;i:0;i:5;i:0;i:6;i:0;i:7;i:0;i:8;i:0;i:9;i:0;i:10;i:0;}');
		}
		return $steps;
	}

	function xxgetEmployeeSteps($id, $month){
		global $dbc;
		global $cid;
		$steps = array();
		if($res = $dbc->query("SELECT * FROM ".$cid."_employee_steps_".$_SESSION['rego']['cur_year']." WHERE emp_id = '$id'")){
			if($row = $res->fetch_assoc()){
				$steps = unserialize($row[$month]);
			}
		}
		if(!$steps){
			//$steps = unserialize('a:10:{i:1;i:0;i:2;i:0;i:3;i:0;i:4;i:0;i:5;i:0;i:6;i:0;i:7;i:0;i:8;i:0;i:9;i:0;i:10;i:0;}');
		}
		return $steps;
	}

	function cgetEmployeeSteps($id){
		global $dbc;
		global $cid;
		$steps = array();
		if($res = $dbc->query("SELECT steps FROM ".$cid."_employees WHERE emp_id = '$id'")){
			if($row = $res->fetch_assoc()){
				$steps = unserialize($row['steps']);
			}
		}
		return $steps;
	}

	function getShiftplanList($cid){
		global $dbc;
		$data = array();
		$sql = "SELECT * FROM ".$cid."_shiftplans_".$_SESSION['rego']['cur_year'];
		//$sql = "SELECT * FROM `".$cid."_shiftplans`";
		if($res = $dbc->query($sql)){
			while($row = $res->fetch_object()){
				$data[$row->code] = $row->description;
			}
		}
		return $data;
	}

	function getShifTeams(){
		global $cid;
		global $dbc;
		$data = array();
		$sql = "SELECT * FROM ".$_SESSION['rego']['cid']."_shiftplans_".$_SESSION['rego']['cur_year'];
		//$sql = "SELECT * FROM `".$cid."_shiftplans`";
		if($res = $dbc->query($sql)){
			while($row = $res->fetch_object()){
				$data[$row->id] = $row->code.' - '.$row->name;
			}
		}
		return $data;
	}

	// Create a new function for regothailand to fetch teams from teams table 

	function getAllTeams(){
		global $cid;
		global $dbc;
		$data = array();
		$sql = "SELECT * FROM ".$_SESSION['rego']['cid']."_sys_settings";
		//$sql = "SELECT * FROM `".$cid."_shiftplans`";
		if($res = $dbc->query($sql)){
			while($row = $res->fetch_object()){
				$data[$row->id] = unserialize($row->team);
			}
		}
		return $data;
	}

	function getShiftPlan($id, $month, $day){
		global $cid;
		global $dbc;
		$data = array();
		$sql = "SELECT D".$day.", emp_id, en_name, th_name FROM ".$cid."_monthly_shiftplans_".$_SESSION['rego']['cur_year']." WHERE emp_id = '".$id."' AND month = '".$month."'";
		//var_dump($sql);
		if($res = $dbc->query($sql)){
			if($row = $res->fetch_assoc()){
				$data['emp_id'] = $row['emp_id'];
				$data['en_name'] = $row['en_name'];
				$data['th_name'] = $row['th_name'];
				$data['plan'] = $row['D'.$day];
			}else{
				return 'not found';
			}
		}
		return $data;
	}

	function getShiftHoursValidate($cid, $plan, $date){
		global $dbc;
		$hours = array();
		$sql = "SELECT shiftplan FROM ".$cid."_leave_time_settings";
		if($res = $dbc->query($sql)){
			if($row = $res->fetch_assoc()){
				$tmp = unserialize($row['shiftplan']);
				if(isset($tmp[$plan])){
					$hours = $tmp[$plan];
				}
			}
		}
		return $hours;
	}

	function getScansPerDay($plan){
		global $cid;
		global $dbc;
		$scans = 2;
		$sql = "SELECT shiftplan FROM ".$cid."_leave_time_settings";
		if($res = $dbc->query($sql)){
			if($row = $res->fetch_assoc()){
				$tmp = unserialize($row['shiftplan']);
				if(isset($tmp[$plan])){
					$scans = $tmp[$plan]['scans'];
				}
			}
		}
		return $scans;
	}
	function getFullShiftplan(){
		global $cid;
		global $dbc;
		$plan = array();
		$sql = "SELECT shiftplan FROM ".$cid."_leave_time_settings";
		if($res = $dbc->query($sql)){
			if($row = $res->fetch_assoc()){
				$plan = unserialize($row['shiftplan']);
			}
		}
		return $plan;
	}

	function xxxgetTimePeriod(){
		global $dbc;
		$data = array();
		if($res = $dbc->query("SELECT * FROM ".$_SESSION['rego']['cid']."_leave_time_settings")){
			$row = $res->fetch_assoc();
			
			$stime = $row['time_start'];
			$etime = $row['time_end'];
			if($stime == 0){$stime = 1;}
			if($etime == 0){$etime = 31;}
			$smonth = sprintf("%02d", $_SESSION['rego']['cur_month']);
			$emonth = $smonth;
			$syear = $_SESSION['rego']['cur_year'];
			$eyear = $syear;
			$lastday = date("t", strtotime($_SESSION['rego']['cur_year'].'-'.sprintf("%02d", $_SESSION['rego']['cur_month']).'-01'));
			if($etime == '31'){$etime = $lastday;}else{$etime = $row['time_end'];}
			if($stime != 1){$smonth --;}
			if($smonth == 0){$smonth = 12; $syear --;}
			$data['speriod'] = sprintf("%02d", $stime).'-'.sprintf("%02d", $smonth).'-'.$syear;
			$data['eperiod'] = sprintf("%02d", $etime).'-'.sprintf("%02d", $emonth).'-'.$eyear;
		}
		return $data;
	}

	/*function dateRange($first, $last, $step = '+1 day', $output_format = 'd-m-Y') {
		 $dates = array();
		 $current = strtotime($first);
		 $last = strtotime($last);
		 while( $current <= $last ) {
			  //$dates[date('n',$current)][date('d-m-Y',$current)] = array(substr(date('l',$current),0,2), date($output_format, $current), date('w',$current));
			  $dates[] = date($output_format, $current);
			  $current = strtotime($step, $current);
		 }
		 return $dates;
	}*/

	function getHolidayFromDate($cid, $date){
		global $dbc;
		$data = array();
		$holiday = false;
		$sql = "SELECT `holidays` FROM `".$cid."_leave_time_settings`";
		if($res = $dbc->query($sql)){
			if($row = $res->fetch_object()){
				$data = unserialize($row->holidays);
			}
		}
		if($data){
			foreach($data as $k=>$v){
				if($v['date'] == $date){$holiday = true; break;}	
			}
		}
		return $holiday;
	}

	function isValidDate($date, $format = 'H:i') {
		if($date == '-'){return false;}
		$d = DateTime::createFromFormat($format, $date);
		return $d && $d->format($format) == $date;
	}

	function decimalHours($time){
		if(!empty($time)){
			$hms = explode(":", $time);
			if(isset($hms[1])){
				return ($hms[0] + ($hms[1]/60));// + ($hms[2]/3600));
			}else{
				return $hms[0];
			}
		}
	}
	
	function addHours($hours,$add){
		$a = new DateTime($hours);
		$aa = new DateInterval("P0000-00-00T$add:00");
		$a->add($aa);
		$h = (int)$a->format('H');
		return $h.$a->format(':i');
	}
	
	function intHours($time){
		if(!empty($time)){
			$hms = explode(":", $time);
			return (int)$hms[0].':'.$hms[1];
		}
	}
	
	function dateHours($number){
		if(!empty($number)){
			$number = number_format($number,20);
			$tmp = explode(".", $number);
			$deci = '0.'.$tmp[1];
			$deci = $deci*60;
			$deci = number_format(((float)$deci),2);
			return ($tmp[0].':'.sprintf("%02d",$deci));
		}else{
			return '-';
		}
	}

	function dateHoursEmpty($number){
		if(!empty($number)){
			$number = number_format($number,20);
			$tmp = explode(".", $number);
			$deci = '0.'.$tmp[1];
			$deci = $deci*60;
			$deci = number_format(((float)$deci),2);
			return ($tmp[0].':'.sprintf("%02d",$deci));
		}else{
			return '';
		}
	}

	// function getVarAllowEmployee($id){
	// 	//global $cid;
	// 	global $dbc;
	// 	$data = array();
	// 	return $data;
	// 	if($res = $dbc->query("SELECT var_allow_dilligence, var_allow_shift, var_allow_transport, var_allow_meal, var_allow_phone, dilligence_status FROM ".$_SESSION['xhr']['cid']."_employees WHERE emp_id = '".$id."'")){
	// 		if($row = $res->fetch_assoc()){
	// 			$data['dilligence'] = $row['var_allow_dilligence'];
	// 			$data['shift'] = $row['var_allow_shift'];
	// 			$data['transport'] = $row['var_allow_transport'];
	// 			$data['meal'] = $row['var_allow_meal'];
	// 			$data['phone'] = $row['var_allow_phone'];
	// 			$data['dilligence_status'] = $row['dilligence_status'];
	// 		}
	// 	}else{
	// 		return mysqli_error($dbc);
	// 	}
	// 	return $data;
	// }
































