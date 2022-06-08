<?

	if(session_id()==''){session_start();}
	ob_start();
	include("../../dbconnect/db_connect.php");
	include(DIR.'files/functions.php');

	
	$error = 0;
	$new_year = $cur_year+1;
	
	$old_db_name = $cid."_shiftplans_".$cur_year;
	$new_db_name = $cid."_shiftplans_".$new_year;
	if(!$dbc->query("DESCRIBE $new_db_name")) {
		$sql = "CREATE TABLE $new_db_name LIKE $old_db_name";
		if(!$dbc->query($sql)){
			echo '<br>16'.mysqli_error($dbc);
			$error = 1;
		}
	}
	
	$old_db_name = $cid."_payroll_".$cur_year;
	$new_db_name = $cid."_payroll_".$new_year;
	if(!$dbc->query("DESCRIBE $new_db_name")) {
		$sql = "CREATE TABLE $new_db_name LIKE $old_db_name";
		if(!$dbc->query($sql)){
			echo '<br>26'.mysqli_error($dbc);
			$error = 1;
		}
	}
	
	$old_db_name = $cid."_monthly_shiftplans_".$cur_year;
	$new_db_name = $cid."_monthly_shiftplans_".$new_year;
	if(!$dbc->query("DESCRIBE $new_db_name")) {
		$sql = "CREATE TABLE $new_db_name LIKE $old_db_name";
		if(!$dbc->query($sql)){
			echo '<br>36'.mysqli_error($dbc);
			$error = 1;
		}
	}
	
	// UPDATE SCHIFTPLANS ///////////////////////////////////////////////////
	$sql = "SELECT * FROM ".$cid."_shiftplans_".$cur_year;
	if($res = $dbc->query($sql)){
		while($row = $res->fetch_assoc()){
			$xdates[$row['code']] = unserialize($row['dates']);
			$plan[$row['code']] = unserialize($row['plan']);
			$alldata[$row['code']] = $row;
		}
	}
	$shiftplan = getDefaultShiftplan($cid);
	//var_dump($plan); exit;
	
	end($xdates);
	foreach($xdates as $k=>$v){

		end($v);
		$end = date('Y-m-d', key($v));
		$enddate[$k] = $end;
	}
	$ss_dataval = 'a:36:{s:7:"team_id";s:2:"t5";s:4:"name";s:2:"t5";s:11:"description";s:0:"";s:5:"scan2";s:1:"1";s:9:"schedule1";s:6:"select";s:10:"range_day1";s:0:"";s:8:"t_hours1";s:0:"";s:8:"b_hours1";s:0:"";s:9:"schedule2";s:3:"off";s:10:"range_day2";s:0:"";s:8:"t_hours2";s:0:"";s:8:"b_hours2";s:0:"";s:9:"schedule3";s:6:"select";s:10:"range_day3";s:0:"";s:8:"t_hours3";s:0:"";s:8:"b_hours3";s:0:"";s:9:"schedule4";s:3:"off";s:10:"range_day4";s:0:"";s:8:"t_hours4";s:0:"";s:8:"b_hours4";s:0:"";s:9:"schedule5";s:6:"select";s:10:"range_day5";s:0:"";s:8:"t_hours5";s:0:"";s:8:"b_hours5";s:0:"";s:9:"schedule6";s:3:"off";s:10:"range_day6";s:0:"";s:8:"t_hours6";s:0:"";s:8:"b_hours6";s:0:"";s:9:"schedule7";s:6:"select";s:10:"range_day7";s:0:"";s:8:"t_hours7";s:0:"";s:8:"b_hours7";s:0:"";s:9:"schedule8";s:3:"off";s:10:"range_day8";s:0:"";s:8:"t_hours8";s:0:"";s:8:"b_hours8";s:0:"";}';
	foreach($alldata as $key=>$val){

		$sql = "INSERT INTO ".$cid."_shiftplans_".$new_year." (id,code,name,th_name,ss_data) VALUES(
		'".$dbc->real_escape_string($val['id'])."',
		'".$dbc->real_escape_string($val['code'])."',
		'".$dbc->real_escape_string($val['name'])."',
		'".$dbc->real_escape_string($val['th_name'])."',
		'".$dbc->real_escape_string($ss_dataval)."')";
		//exit;
			
		if(!$dbc->query($sql)){
			echo '<br>187'.mysqli_error($dbc);
			$error = 1;
		}
	}
	// echo '<pre>';
	// print_r($sql);
	// echo '</pre>';
	// var_dump($sql);

	
	
	// UPDATE PAYROLL MONTHS //////////////////////////////////////////////////
	$res = $dbx->query("SELECT * FROM rego_default_settings");
	if($row = $res->fetch_assoc()){


		$sso_defaults =  unserialize($row['sso_defaults']);


		$sso_rate = $row['sso_rate_emp'];
		$sso_min = $row['sso_min_emp'];
		$sso_max = $row['sso_max_emp'];
		$sso_rate_com = $row['sso_rate_com'];
		$sso_min_com = $row['sso_min_com'];
		$sso_max_com = $row['sso_max_com'];
	}
	//var_dump($row); //exit;
	
	// $time_end = '25-12-'.$cur_year;
	// $leave_end = '25-12-'.$cur_year;
	// $payroll_end = '25-12-'.$cur_year;
	$sql = "SELECT * FROM ".$cid."_payroll_months WHERE month = '".$cur_year."_12'";
	if($res = $dbc->query($sql)){
		while($row = $res->fetch_assoc()){
			$time_end = date('d-m-Y', strtotime($row['time_end'].'+1 days'));
			$time_end_new = date('d', strtotime($row['time_end']));
			$leave_end = date('d-m-Y', strtotime($row['leave_end'].'+1 days'));
			$payroll_end = date('d-m-Y', strtotime($row['payroll_end'].'+1 days'));
			$max = $row['sso_act_max'];
		}
	}
	

	// get last year decmeber date 
	// get current year 
	//$getDecEnDateTime = date('d', strtotime($time_end));

	$arrayDays = array(
					1 => 31,
					2 => 28,
					3 => 31,
					4 => 30,
					5 => 31,
					6 => 30,
					7 => 31,
					8 => 31,
					9 => 30,
					10 => 31,
					11 => 30,
					12 => 31,
				);


	

	$sql = "INSERT INTO ".$cid."_payroll_months (month, time_start, time_end, leave_start, leave_end, payroll_start, payroll_end, paydate, formdate, sso_eRate, sso_eMax, sso_eMin, sso_cRate, sso_cMax, sso_cMin, wht, sso_act_max) VALUES ";
	for($i=1;$i<=12;$i++){
		$last = date('t', strtotime($new_year.'-'.sprintf('%02d', $i).'-01'));
		$date = $last.'-'.sprintf('%02d', $i).'-'.$new_year;


		if($time_end_new =='31')
		{

			$time_start = date('01-'.sprintf('%02d', $i).'-'.$new_year);
			$enddate = date('t', strtotime($time_start));
			$end = $enddate.'-'.sprintf('%02d', $i).'-'.$new_year;

			$leave_start = $time_start;
			$payroll_start = $time_start;

		}
		else
		{
			if($i == 1){

				$end = date($time_end_new.'-'.sprintf('%02d', $i).'-'.$new_year);
				$time_start = $time_end;
				$leave_start = $leave_end;
				$payroll_start = $payroll_end;

			}else{

				
				//$daypre = $arrayDays[$i];
				$timeend = date('d', strtotime($time_end));

				//$day = $arrayDays[$i];
				$time_start = $timeend.'-'.sprintf('%02d', ($i-1)).'-'.$new_year;
				$leave_start = $time_start;
				$payroll_start = $time_start;
				//$end = '25-'.sprintf('%02d', $i).'-'.$new_year;

				//$time_start = date('d-m-Y', strtotime($time_start.'-'.$ia.' days'));
				$end = date('d-m-Y', strtotime($time_start.'+'.$day.' days'));
				$end = date($time_end_new.'-'.sprintf('%02d', $i).'-'.$new_year);


			}

		}



		// echo '<pre>';
		// echo $time_start .'===='. $end;
		// echo '</pre>';


		$sql .= "('".$dbc->real_escape_string($new_year.'_'.$i)."',";
		$sql .= "'".$dbc->real_escape_string($time_start)."',";
		$sql .= "'".$dbc->real_escape_string($end)."',";
		$sql .= "'".$dbc->real_escape_string($leave_start)."',";
		$sql .= "'".$dbc->real_escape_string($end)."',";
		$sql .= "'".$dbc->real_escape_string($payroll_start)."',";
		$sql .= "'".$dbc->real_escape_string($end)."',";
		$sql .= "'".$dbc->real_escape_string($date)."',";
		$sql .= "'".$dbc->real_escape_string($date)."',";
		$sql .= "'".$dbc->real_escape_string($sso_defaults[$i]['sso_eRate'])."',";
		$sql .= "'".$dbc->real_escape_string($sso_defaults[$i]['sso_eMax'])."',";
		$sql .= "'".$dbc->real_escape_string($sso_defaults[$i]['sso_eMin'])."',";
		$sql .= "'".$dbc->real_escape_string($sso_defaults[$i]['sso_cRate'])."',";
		$sql .= "'".$dbc->real_escape_string($sso_defaults[$i]['sso_cMax'])."',";
		$sql .= "'".$dbc->real_escape_string($sso_defaults[$i]['sso_cMin'])."',";
		$sql .= "'".$dbc->real_escape_string(3)."',";
		$sql .= "'".$dbc->real_escape_string($max)."'),";
	}
	$sql = substr($sql, 0, -1)." ON DUPLICATE KEY UPDATE 
		time_start = VALUES(time_start), 
		time_end = VALUES(time_end), 
		leave_start = VALUES(leave_start), 
		leave_end = VALUES(leave_end), 
		payroll_start = VALUES(payroll_start), 
		payroll_end = VALUES(payroll_end), 
		paydate = VALUES(paydate), 
		formdate = VALUES(formdate), 
		sso_eRate = VALUES(sso_eRate), 
		sso_eMax = VALUES(sso_eMax), 
		sso_eMin = VALUES(sso_eMin), 
		sso_cRate = VALUES(sso_cRate), 
		sso_cMax = VALUES(sso_cMax), 
		sso_cMin = VALUES(sso_cMin), 
		sso_act_max = VALUES(sso_act_max)";

		
	//echo $sql; exit;
	//echo $err_msg;	exit;


// exit;
		
		
	if(!$dbc->query($sql)){
		echo '<br>270'.mysqli_error($dbc);
		$error = 1;
	}
	
	if(!$error){
		$sql = "UPDATE ".$cid."_sys_settings SET years = CONCAT(years, ',',".$new_year.")";
		if(!$dbc->query($sql)){
			echo '<br>277'.mysqli_error($dbc);
		}else{
			ob_clean(); 
			echo 'success';
		}
	}else{
		ob_clean(); 
		echo '12error';
	}
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
