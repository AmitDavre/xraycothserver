<?php

	function checkBalance($balance, $type, $leave, $days){
		if($type != '0'){
			//var_dump($type);
			if($balance[$type] < $days){
				return 'Not enough days left - Balance <b>'.$type.'</b> '.$leave.' = '.$balance[$type].'&nbsp; - &nbsp;Requested : '.$days;
			}
		}
	}

	function getLeaveTypes($cid){
		global $dbc;
		$data = array();
		$sql = "SELECT `leave_types` FROM `".$cid."_leave_time_settings`";
		if($res = $dbc->query($sql)){
			if($row = $res->fetch_object()){
				$data = unserialize($row->leave_types);
			}
		}
		return $data;
	}

	function getLeaveReqBefore($cid){
		global $dbc;
		$data = array();
		$sql = "SELECT `request` FROM `".$cid."_leave_time_settings`";
		if($res = $dbc->query($sql)){
			if($row = $res->fetch_object()){
				$data = $row->request;
			}
		}
		return $data;
	}

	function getSelLeaves($cid){
		global $dbc;
		$data = array();
		$sql = "SELECT `leave_types` FROM `".$cid."_leave_time_settings`";
		if($res = $dbc->query($sql)){
			if($row = $res->fetch_object()){
				$tmp = unserialize($row->leave_types);
			}
		}
		foreach($tmp as $k=>$v){
			if($v['activ'] == 1){
				$data[$k] = $k;
			}
		}
		return $data;
	}

	function getEmpLeaveTypes($cid){
		global $dbc;
		$data = array();
		$sql = "SELECT leave_types FROM ".$cid."_leave_time_settings";
		if($res = $dbc->query($sql)){
			if($row = $res->fetch_object()){
				$tmp = unserialize($row->leave_types);
			}
		}
		foreach($tmp as $k=>$v){
			//if($v['activ'] == 1 && $v['emp_request'] == 1){
			if($v['activ'] == 1){
				$data[$k] = $v;
			}
		}
		return $data;
	}

	function getSelLeaveTypes($cid){
		global $dbc;
		$data = array();
		$sql = "SELECT `leave_types` FROM `".$cid."_leave_time_settings`";
		if($res = $dbc->query($sql)){
			if($row = $res->fetch_object()){
				$tmp = unserialize($row->leave_types);
			}
		}
		foreach($tmp as $k=>$v){
			if($v['activ'] == 1){
				$data[$k] = $v;
			}
		}
		return $data;
	}

	function getYTDworkedDays($cid){
		global $dbc;
		/*$hol = array();
		$hd = getHolidays($cid);
		foreach($hd as $k=>$v){
			$hol[] = strtotime($v['cdate']);
		}*/
		
		$data = array();
		$xdata = array();
		$days = array();
		$today = strtotime(date('Y-m-d'));
		$sql = "SELECT * FROM ".$cid."_monthly_shiftplans_".$_SESSION['rego']['cur_year'];
		if($res = $dbc->query($sql)){
			while($row = $res->fetch_assoc()){
				$data[] = $row;
			}
		}
		foreach($data as $k=>$v){
			$end = cal_days_in_month(CAL_GREGORIAN,$v['month'],$_SESSION['rego']['cur_year']);
			for($i=1; $i<=$end; $i++){
				if($v['D'.$i] != 'OFF'){
					$xdata[$v['month']][$i] = strtotime($i.'-'.$v['month'].'-'.$_SESSION['rego']['cur_year']);
				}
			}
		}
		
		$xdata = array_map('array_values', $xdata);
		//return $xdata;
		foreach($xdata as $key=>$val){
			foreach($val as $k=>$v){
				if($v > $today){
					$days[$key] = $k;
					break;
				}
			}
		}
		//return $days;
		for($i=count($days);$i<=12;$i++){
			$days[$i] = 0;
		}
		return $days;
	}

	function getALemployee($cid, $id){
		global $dbc;
		$data = 0;
		$res = $dbc->query("SELECT annual_leave FROM ".$cid."_employees WHERE emp_id = '".$id."'");
		if($row = $res->fetch_assoc()){
			$data = $row['annual_leave'];
		}
		return $data;
	}	
	function getALemployeeOther($cid, $id, $cur_year){
		global $dbc;
		$data = 0;
		$res = $dbc->query("SELECT annual_leave FROM ".$cid."_employee_per_year_records WHERE emp_id = '".$id."' AND year = '".$cur_year."' ");
		if($row = $res->fetch_assoc()){
			$data = $row['annual_leave'];
		}
		else
		{
			$res1 = $dbc->query("SELECT annual_leave FROM ".$cid."_employees WHERE emp_id = '".$id."'");
			if($row1 = $res1->fetch_assoc()){
				$data = $row1['annual_leave'];
			}
		}
		return $data;
	}

	function getUsedLeaveEmployee($cid, $id, $balance){
		global $dbc;
		$res = $dbc->query("SELECT * FROM ".$cid."_leaves_data WHERE emp_id = '".$id."'"); 
		while($row = $res->fetch_assoc()){
			if($row['status'] == 'RQ' || $row['status'] == 'AP'){
				$balance[$row['leave_type']]['pending'] += $row['days'];
			}elseif($row['status'] == 'TA'){
				$balance[$row['leave_type']]['used'] += $row['days'];
			}
		}
		return $balance;
	}	

	function getUsedLeaveEmployeeWithBalOLD($cid, $id, $balance, $currentyear,$cur_year){
		global $dbc;

		// if yes then use 2021 for AL and 2022 for other leaves 


	

		$sql511 = "SELECT other_fields FROM ".$cid."_employee_per_year_records WHERE emp_id  = '".$id."' and year = '".$cur_year."'";


		if($res511 = $dbc->query($sql511)){
			if($row511 = $res511->fetch_assoc()){
				$requestRowid = unserialize($row511['other_fields']);

				$requestRowidValue = $requestRowid['leaveForwardorNot'];

					// echo '<pre>';
					// print_r($requestRowidValue);
					// echo '</pre>';

			}
		}

	

		// die();

		$res20002 = $dbc->query("SELECT * FROM ".$cid."_leave_periods WHERE leave_period_year = '".$cur_year."'"); 
		if($row20002 = $res20002->fetch_assoc()){

			$periodStartDate = $row20002['leave_period_start'];
			$periodEndDate   = $row20002['leave_period_end'];

		}

		if($requestRowidValue == '1')
		{	
			if($cur_year > 2021)
			{

				// $currentyears = $cur_year-1;
				$currentyears = $cur_year-1;

				$currentyearss= '%'.$currentyears.'%';
				$cur_yearsss= '%'.$cur_year.'%';


					// get balance of previous period here 


						$res20007 = $dbc->query("SELECT * FROM ".$cid."_leave_periods WHERE leave_period_year = '".$currentyears."'"); 
						if($row20007 = $res20007->fetch_assoc()){

							$periodStartDate7 = $row20007['leave_period_start'];
							$periodEndDate7   = $row20007['leave_period_end'];

						}



					$sql20003 = "SELECT * FROM ".$cid."_leaves_data WHERE emp_id = '".$id."' AND DATE(date) BETWEEN '".$periodStartDate7."' AND '".$periodEndDate7."'"; 
					if($res1 = $dbc->query($sql20003))
					{
						while($row1 = $res1->fetch_assoc()){


							if($row1['leave_type']== 'AL')
							{
								if($row1['status'] == 'RQ' || $row1['status'] == 'AP'){
									$balance[$row1['leave_type']]['pending'] += $row1['days'];
								}elseif($row1['status'] == 'TA'){
									$balance[$row1['leave_type']]['used'] += $row1['days'];
								}
							}

						}
					}






				$sql20004 = "SELECT * FROM ".$cid."_leaves_data WHERE emp_id = '".$id."' AND DATE(date) BETWEEN '".$periodStartDate."' AND '".$periodEndDate."'"; 
					if($res111 = $dbc->query($sql20004))
					{
						while($row = $res111->fetch_assoc()){

							if($row['leave_type']!= 'AL')
							{
								if($row['status'] == 'RQ' || $row['status'] == 'AP'){
									$balance[$row['leave_type']]['pending'] += $row['days'];
								}elseif($row['status'] == 'TA'){
									$balance[$row['leave_type']]['used'] += $row['days'];
								}
							}
						}
					}
				
			}
			else
			{	

				$sql20005 = "SELECT * FROM ".$cid."_leaves_data WHERE emp_id = '".$id."' AND DATE(date) BETWEEN '".$periodStartDate."' AND '".$periodEndDate."'"; 
					if($res1115 = $dbc->query($sql20005))
					{
						while($row = $res1115->fetch_assoc())
						{
							if($row['status'] == 'RQ' || $row['status'] == 'AP')
							{
								$balance[$row['leave_type']]['pending'] += $row['days'];
							}
							elseif($row['status'] == 'TA')
							{
								$balance[$row['leave_type']]['used'] += $row['days'];
							}
						}
					}

			}
				return $balance;


			
		}
		else
		{

			$sql20005 = "SELECT * FROM ".$cid."_leaves_data WHERE emp_id = '".$id."' AND DATE(date) BETWEEN '".$periodStartDate."' AND '".$periodEndDate."'"; 
			if($res1115 = $dbc->query($sql20005))
			{
				while($row = $res1115->fetch_assoc())
				{
					if($row['status'] == 'RQ' || $row['status'] == 'AP'){
						$balance[$row['leave_type']]['pending'] += $row['days'];
					}elseif($row['status'] == 'TA'){
						$balance[$row['leave_type']]['used'] += $row['days'];
					}
				}
			}

			return $balance;

		}




	}


	function getUsedLeaveEmployeeWithBal($cid, $id, $balance, $currentyear,$cur_year){
		global $dbc;

		// if yes then use 2021 for AL and 2022 for other leaves 


	

		$sql511 = "SELECT other_fields FROM ".$cid."_employee_per_year_records WHERE emp_id  = '".$id."' and year = '".$cur_year."'";


		if($res511 = $dbc->query($sql511)){
			if($row511 = $res511->fetch_assoc()){
				$requestRowid = unserialize($row511['other_fields']);

				$requestRowidValue = $requestRowid['leaveForwardorNot'];

					// echo '<pre>';
					// print_r($requestRowidValue);
					// echo '</pre>';

			}
		}

	

		// die();


		// if the year is 2022 and the period end date is still not completed from the previous year 2021 then need to get the values for 2021 
		// get the current period on the basis of current date 
		// period start 1 june 2021 - 30 june 2022 
		// current date  24 jan 2022 
		// if current date between the period then fetch the start and end date of that period 

		$getCurrentDateForQuery = date('Y-m-d');

		$res20002 = $dbc->query("SELECT * FROM ".$cid."_leave_periods WHERE DATE('".$getCurrentDateForQuery."') BETWEEN DATE(leave_period_start) AND DATE(leave_period_end)"); 
		if($row20002 = $res20002->fetch_assoc()){

			$periodStartDate = $row20002['leave_period_start'];
			$periodEndDate   = $row20002['leave_period_end'];

		}
		else
		{
			$periodStartDate = 'nodate';
		}


		// get leave end period for the previous year 

		// get currentDate of the year 

		$getCurrentDate = date('Y-m-d');

		// echo $periodStartDate;
		// echo $getCurrentDate;

		// if($requestRowidValue == '1')
		// {	
			// if the leave period is over then run this 
			if($periodStartDate != 'nodate')
			{


				if($getCurrentDate >= $periodStartDate)
				{


					// $currentyears = $cur_year-1;
					$currentyears = $cur_year-1;

					$currentyearss= '%'.$currentyears.'%';
					$cur_yearsss= '%'.$cur_year.'%';


						// get balance of previous period here 


							$res20007 = $dbc->query("SELECT * FROM ".$cid."_leave_periods WHERE DATE('".$getCurrentDateForQuery."') BETWEEN DATE(leave_period_start) AND DATE(leave_period_end)"); 
							if($row20007 = $res20007->fetch_assoc()){

								$periodStartDate7 = $row20007['leave_period_start'];
								$periodEndDate7   = $row20007['leave_period_end'];

							}



						$sql20003 = "SELECT * FROM ".$cid."_leaves_data WHERE emp_id = '".$id."' AND DATE(date) BETWEEN '".$periodStartDate7."' AND '".$periodEndDate7."'"; 

						if($res1 = $dbc->query($sql20003))
						{
							while($row1 = $res1->fetch_assoc()){


								if($row1['leave_type']== 'AL' || $row1['leave_type']== 'AU')
								{
									if($row1['status'] == 'RQ' || $row1['status'] == 'AP'){
										$balance[$row1['leave_type']]['pending'] += $row1['days'];
									}elseif($row1['status'] == 'TA'){
										$balance[$row1['leave_type']]['used'] += $row1['days'];
									}
								}

							}
						}






					$sql20004 = "SELECT * FROM ".$cid."_leaves_data WHERE emp_id = '".$id."' AND DATE(date) BETWEEN '".$periodStartDate."' AND '".$periodEndDate."'";

						if($res111 = $dbc->query($sql20004))
						{
							while($row111 = $res111->fetch_assoc()){

								if($row111['leave_type'] != 'AL' && $row111['leave_type'] != 'AU')
								{
									if($row111['status'] == 'RQ' || $row111['status'] == 'AP'){
										$balance[$row111['leave_type']]['pending'] += $row111['days'];
									}elseif($row111['status'] == 'TA'){
										$balance[$row111['leave_type']]['used'] += $row111['days'];
									}
								}
							}
						}


				}
				else
				{	
					// if current date is less than start date 

					$cureentyearsssssssss= $cur_year -1 ;

					$res20011 = $dbc->query("SELECT * FROM ".$cid."_leave_periods WHERE DATE('".$getCurrentDateForQuery."') BETWEEN DATE(leave_period_start) AND DATE(leave_period_end)"); 

					if($row20011 = $res20011->fetch_assoc()){

						$periodStartDate20011 = $row20011['leave_period_start'];
						$periodEndDate20011   = $row20011['leave_period_end'];

					}



					$sql20005 = "SELECT * FROM ".$cid."_leaves_data WHERE emp_id = '".$id."' AND DATE(date) BETWEEN '".$periodStartDate20011."' AND '".$periodEndDate20011."'"; 

			
						if($res1115 = $dbc->query($sql20005))
						{
							while($row = $res1115->fetch_assoc())
							{
								if($row['status'] == 'RQ' || $row['status'] == 'AP')
								{
									$balance[$row['leave_type']]['pending'] += $row['days'];
								}
								elseif($row['status'] == 'TA')
								{
									$balance[$row['leave_type']]['used'] += $row['days'];
								}
							}
						}

				}
			
					return $balance;
			}
			else
			{
				// if there is no period saved in database 
				$cureentyearsssssssss= $cur_year -1 ;

				$res20011 = $dbc->query("SELECT * FROM ".$cid."_leave_periods WHERE DATE('".$getCurrentDateForQuery."') BETWEEN DATE(leave_period_start) AND DATE(leave_period_end)"); 

				if($row20011 = $res20011->fetch_assoc()){

					$periodStartDate20011 = $row20011['leave_period_start'];
					$periodEndDate20011   = $row20011['leave_period_end'];

				}



				$sql20005 = "SELECT * FROM ".$cid."_leaves_data WHERE emp_id = '".$id."' AND DATE(date) BETWEEN '".$periodStartDate20011."' AND '".$periodEndDate20011."'"; 

		
					if($res1115 = $dbc->query($sql20005))
					{
						while($row = $res1115->fetch_assoc())
						{
							if($row['status'] == 'RQ' || $row['status'] == 'AP')
							{
								$balance[$row['leave_type']]['pending'] += $row['days'];
							}
							elseif($row['status'] == 'TA')
							{
								$balance[$row['leave_type']]['used'] += $row['days'];
							}
						}
					}

				return $balance;

			}


			
		// }
		// else
		// {


		// 	$res20011 = $dbc->query("SELECT * FROM ".$cid."_leave_periods WHERE DATE('".$getCurrentDateForQuery."') BETWEEN DATE(leave_period_start) AND DATE(leave_period_end)"); 

		// 	if($row20011 = $res20011->fetch_assoc()){

		// 		$periodStartDate2001 = $row20011['leave_period_start'];
		// 		$periodEndDate20011   = $row20011['leave_period_end'];

		// 	}

		// 	// no check if current date is greater than period start 


		// 	if($getCurrentDate >= $periodStartDate2001)
		// 	{	

		// 		$res20011111 = $dbc->query("SELECT * FROM ".$cid."_leave_periods WHERE DATE('".$getCurrentDateForQuery."') BETWEEN DATE(leave_period_start) AND DATE(leave_period_end)"); 

		// 		if($row20011111 = $res20011111->fetch_assoc()){

		// 			$periodStartDate2011101 = $row20011111['leave_period_start'];
		// 			$periodEndDate201111011   = $row20011111['leave_period_end'];

		// 		}

		// 		$sql20005 = "SELECT * FROM ".$cid."_leaves_data WHERE emp_id = '".$id."' AND DATE(date) BETWEEN '".$periodStartDate2011101."' AND '".$periodEndDate201111011."'"; 
		// 		if($res1115 = $dbc->query($sql20005))
		// 		{
		// 			while($row = $res1115->fetch_assoc())
		// 			{
		// 				if($row['status'] == 'RQ' || $row['status'] == 'AP'){
		// 					$balance[$row['leave_type']]['pending'] += $row['days'];
		// 				}elseif($row['status'] == 'TA'){
		// 					$balance[$row['leave_type']]['used'] += $row['days'];
		// 				}
		// 			}
		// 		}

		// 	}
		// 	else
		// 	{

		// 		$curyearsadas = $cur_year -1 ;
		// 		$res20033311111 = $dbc->query("SELECT * FROM ".$cid."_leave_periods WHERE DATE('".$getCurrentDateForQuery."') BETWEEN DATE(leave_period_start) AND DATE(leave_period_end)"); 

		// 		if($row20333011111 = $res20033311111->fetch_assoc()){

		// 			$periodStartDate2014441101 = $row20333011111['leave_period_start'];
		// 			$periodEndDate20111144011   = $row20333011111['leave_period_end'];

		// 		}


		// 		$sql20005 = "SELECT * FROM ".$cid."_leaves_data WHERE emp_id = '".$id."' AND DATE(date) BETWEEN '".$periodStartDate2014441101."' AND '".$periodEndDate20111144011."'"; 
		// 		if($res1115 = $dbc->query($sql20005))
		// 		{
		// 			while($row = $res1115->fetch_assoc())
		// 			{
		// 				if($row['status'] == 'RQ' || $row['status'] == 'AP'){
		// 					$balance[$row['leave_type']]['pending'] += $row['days'];
		// 				}elseif($row['status'] == 'TA'){
		// 					$balance[$row['leave_type']]['used'] += $row['days'];
		// 				}
		// 			}
		// 		}


		// 	}




		// 	return $balance;

		// }




	}




	function getStrictBalanceEmployee($cid, $dleave, $id){
		global $dbc;
		$data = array();
		foreach($dleave as $k=>$v){
			$data[$k] = $v['maxdays'];
		}
		$res = $dbc->query("SELECT * FROM ".$cid."_leaves WHERE emp_id = '".$id."'"); 
		while($row = $res->fetch_assoc()){
			if($row['status'] == 'RQ' || $row['status'] == 'AP' || $row['status'] == 'TA'){
				if(isset($data[$row['leave_type']])){
					$data[$row['leave_type']] -= $row['days'];
					//$data = $row['type'];
				}
			}
		}
		return $data;
	}

	function getPendingDays($cid, $id, $leave_id){
		global $dbc;
		$data = array();
		$sql = "SELECT date, days, day FROM ".$cid."_leaves_data WHERE emp_id = '".$id."' AND leave_id <> '".$leave_id."'";
		if($res = $dbc->query($sql)){
			while($row = $res->fetch_assoc()){
				//$data[strtotime($row['date'])] = strtotime($row['date']);
				$data['date'][strtotime($row['date'])] = strtotime($row['date']);
				//$data[strtotime($row['date'])]['date'] = $row['date'];
				$data['days'][strtotime($row['date'])] = $row['days'];
				$data['day'][strtotime($row['date'])] = $row['day'];
			}
		}
		return $data;
	}

	function getHolidays($year){
		global $dbc;
		$data = array();
		$sql = "SELECT * FROM ".$_SESSION['rego']['cid']."_holidays WHERE year = '".$year."'";
		if($res = $dbc->query($sql)){
			while($row = $res->fetch_assoc()){
				$data[] = $row;
			}
		}
		return $data;
	}

	function getHoliDates($year){
		global $dbc;
		$data = array();
		$sql = "SELECT * FROM ".$_SESSION['rego']['cid']."_holidays WHERE year = '".$year."'";
		if($res = $dbc->query($sql)){
			while($row = $res->fetch_assoc()){
				$data[] = $row['cdate'];
			}
		}
		return $data;
	}

	function getAllHoliDates($year){
		global $dbc;
		$data = array();
		$sql = "SELECT * FROM ".$_SESSION['rego']['cid']."_holidays WHERE year = '".$year."'";
		if($res = $dbc->query($sql)){
			while($row = $res->fetch_assoc()){
				$data[] = date('d-m-Y', strtotime($row['cdate']));
			}
		}
		return $data;
	}

	function updateLeaveDatabase($cid){
		global $dbc;
		//global $leave_types;
		$leave_types = getSelLeaveTypes($cid);
		//global $leave_periods;
		// apply pending and taken leave
		
		/*$data = array();
		$sql = "SELECT id, type, attach FROM ".$cid."_leaves";
		if($res = $dbc->query($sql)){
			while($row = $res->fetch_assoc()){
				if($leave_types[$row['type']]['certificate'] == 1 && $row['attach'] == ''){
					$data[$row['id']] = 0;
				}else{
					$data[$row['id']] = 1;
				}
			}
		}
		if($data){ 
			foreach($data as $k=>$v){
				$sql = "UPDATE ".$cid."_leaves_data SET certificate = '".$v."' WHERE leave_id = '".$k."'"; 
				$dbc->query($sql);
			} 
		}*/
		//var_dump($data);
	
		$data = array(); // SET PASSED DAYS AS TAKEN IF STATUS = APPROVED
		$sql = "SELECT id, end, status FROM ".$cid."_leaves WHERE end <= CURDATE() AND status = 'AP'";
		if($res = $dbc->query($sql)){
			while($row = $res->fetch_assoc()){
				$data[$row['id']] = $row['end'];
			}
		}
		if($data){ 
			foreach($data as $k=>$v){
				$sql = "UPDATE ".$cid."_leaves SET status = 'TA' WHERE id = '".$k."'"; 
				$dbc->query($sql);
			} 
		}
		
		$data = array();
		$sql = "SELECT id, date, status FROM ".$cid."_leaves_data WHERE date <= CURDATE() AND status = 'AP'";
		if($res = $dbc->query($sql)){
			while($row = $res->fetch_assoc()){
				$data[$row['id']] = $row['date'];
			}
		}
		if($data){ 
			foreach($data as $k=>$v){
				$sql = "UPDATE ".$cid."_leaves_data SET status = 'TA' WHERE id = '".$k."'"; 
				$dbc->query($sql);
			} 
		}
		//var_dump($data); exit;
	}

	function getMonthlyPeriod($period_start){
		$year = $_SESSION['rego']['cur_year'];
		$month = $_SESSION['rego']['curr_month'];
		if($period_start == 0){
			$start = 1;
		}else{
			if($month == 1){
				$month = 12;
				$year -= 1;
			}else{
				$month = sprintf('%02d', ($month -= 1));
			}
		}
		$d = date('t', strtotime($year.'-'.$month.'-01'));
		if($period_start == 31){
			$start = $d;
		}
		if($period_start != 0 && $period_start != 31){
			$start = $d - $period_start;
		}

		$sdate = date('Y-m-d', strtotime($year.'-'.$month.'-'.sprintf('%02d', $start)));
		$data['start'] = $sdate;
		
		if($month == 12){
			$month = 1;
			$year += 1;
		}else{
			$month = sprintf('%02d', ($month += 1));
		}
		$d = date('t', strtotime($year.'-'.$month.'-01'));
		if($period_start == 31){
			$start = $d;
		}
		if($period_start != 0 && $period_start != 31){
			$start = $d - $period_start;
		}
		$edate = date('Y-m-d', strtotime($year.'-'.$month.'-'.sprintf('%02d', $start)));

		$data['end'] = $edate;
		return $data;
	}























