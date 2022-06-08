<?php

	if(session_id()==''){session_start();}
	ob_start();
	include('../../dbconnect/db_connect.php');
	include(DIR.'files/functions.php');
	
	//var_dump($_REQUEST); exit;
	
	$cols = date('t', strtotime($cur_year.'-'.$_REQUEST['month'].'-01'));
	//var_dump($cols);
	$tmp = getActiveInactiveEmployees();
	$emps = $tmp[$_REQUEST['emp_id']];
	//$holidays = getHolidaysDates();
	//var_dump($emps); exit;

	// echo '<pre>';
	// print_r($emps);
	// echo '</pre>';
	// die();
	
	$dates = array();
	$res = $dbc->query("SELECT dates FROM ".$cid."_shiftplans_".$cur_year." WHERE id = '".$emps['shiftplan']."'");
	if(!mysqli_error($dbc)){
		if($row = $res->fetch_assoc()){
			$dates = unserialize($row['dates']);
		}
	}	

	$cycDates = array();
	$res2 = $dbc->query("SELECT cycle_details FROM ".$cid."_shiftplans_".$cur_year." WHERE id = '".$emps['teams']."'");
	if(!mysqli_error($dbc)){
		if($row2 = $res2->fetch_assoc()){
			$cycDates = unserialize($row2['cycle_details']);
		}
	}

	// echo '<pre>';
	// print_r($cycDates);
	// echo '</pre>';
	// die();
	//var_dump($dates);
	$data['id'] = $_REQUEST['emp_id'].'-'.$_REQUEST['month'];
	$data['sid'] = $emps['sid'];
	$data['month'] = $_REQUEST['month'];
	$data['emp_id'] = $_REQUEST['emp_id'];
	$data['en_name'] = $emps['en_name'];
	$data['th_name'] = $emps['th_name'];
	
	if(!empty($v['shiftplan'])){
		$data['shiftteam'] = $emps['shiftplan'];
		$data['shiftteam_name'] = $emps['teams'];
		$shifteamname= $emps['teams'];
	}else{
		$data['shiftteam'] = 'DT';
		$data['shiftteam_name'] = $emps['teams'];
		$shifteamname= $emps['teams'];


	}
	// $data['emp_status'] = $emps['emp_status'];

	$serial =  '01';

	if($_REQUEST['month'] == '1')
	{
		$monthVar = 'January';
	}
	else if($_REQUEST['month'] == '2')
	{
		$monthVar = 'February';
	}	
	else if($_REQUEST['month'] == '3')
	{
		$monthVar = 'March';
	}	
	else if($_REQUEST['month'] == '4')
	{
		$monthVar = 'April';
	}	
	else if($_REQUEST['month'] == '5')
	{
		$monthVar = 'May';
	}	
	else if($_REQUEST['month'] == '6')
	{
		$monthVar = 'June';
	}
	else if($_REQUEST['month'] == '7')
	{
		$monthVar = 'July';
	}	
	else if($_REQUEST['month'] == '8')
	{
		$monthVar = 'August';
	}	
	else if($_REQUEST['month'] == '9')
	{
		$monthVar = 'September';
	}	
	else if($_REQUEST['month'] == '10')
	{
		$monthVar = 'October';
	}	
	else if($_REQUEST['month'] == '11')
	{
		$monthVar = 'November';
	}	
	else if($_REQUEST['month'] == '12')
	{
		$monthVar = 'December';
	}

	for($i=1;$i<=$cols;$i++){

		$countVal = $serial ++ ;
		$formatCount = str_pad($countVal,2,"0",STR_PAD_LEFT); // add leading 0 eg 01,02,03

		if($cycDates[$monthVar][$formatCount]['s1'] != '') 
		{
			$data['D'.$i] = $cycDates[$monthVar][$formatCount]['s1'];
		}
		else
		{
			$data['D'.$i] = '';
		}

		
	}


	// echo '<pre>';
	// print_r($data);
	// echo '</pre>';
	// die();
	$sql = "INSERT INTO ".$cid."_monthly_shiftplans_".$cur_year." (";
	foreach($data as $k=>$v){
		$sql .= $k.',';
	}
	$sql = substr($sql,0,-1);
	$sql .= ") VALUES ("; 
	foreach($data as $k=>$v){
		$sql .= "'".mysqli_real_escape_string($dbc,$v)."',";
	}
		$sql = substr($sql,0,-1);
		$sql .= '),(';
	$sql = substr($sql,0,-2);


	/*$sql .= " ON DUPLICATE KEY UPDATE ";
	foreach($data[0] as $k=>$v){
		$sql .= $k.' = VALUES('.$k.'),';
		//var_dump($v.' = VALUES('.$v.')');
	}
	$sql = substr($sql,0,-1);*/
	// echo $sql;
	// exit;


	// Fetch Working Days according to month 

	

	ob_clean();



	if($dbc->query($sql)){

		$sql3 = "SELECT * FROM ".$cid."_shiftplans_".$cur_year." WHERE id = '".$shifteamname."'";
	if($res3 = $dbc->query($sql3)){
		if($row3 = $res3->fetch_assoc()){
			$workDays = unserialize($row3['wkd']);
		}
	}

	if($_REQUEST['month'] == '1')
	{
		$workDayVal = $workDays['January']['wkd'];
		$pubDayVal  = $workDays['January']['pub'];
		$offDayVal  = $workDays['January']['off'];
		$noffDayVal  = $workDays['January']['noff'];
		$workinNormal  = $workDays['January']['workonly'];
	}
	else if($_REQUEST['month'] == '2')
	{
		$workDayVal = $workDays['February']['wkd'];
		$pubDayVal  = $workDays['February']['pub'];
		$offDayVal  = $workDays['February']['off'];
		$noffDayVal  = $workDays['February']['noff'];
		$workinNormal  = $workDays['February']['workonly'];
	}
	else if($_REQUEST['month'] == '3')
	{
		$workDayVal = $workDays['March']['wkd'];
		$pubDayVal  = $workDays['March']['pub'];
		$offDayVal  = $workDays['March']['off'];
		$noffDayVal  = $workDays['March']['noff'];
		$workinNormal  = $workDays['March']['workonly'];
	}
	else if($_REQUEST['month'] == '4')
	{
		$workDayVal = $workDays['April']['wkd'];
		$pubDayVal  = $workDays['April']['pub'];
		$offDayVal  = $workDays['April']['off'];
		$noffDayVal  = $workDays['April']['noff'];
		$workinNormal  = $workDays['April']['workonly'];
	}	
	else if($_REQUEST['month'] == '5')
	{
		$workDayVal = $workDays['May']['wkd'];
		$pubDayVal  = $workDays['May']['pub'];
		$offDayVal  = $workDays['May']['off'];
		$noffDayVal  = $workDays['May']['noff'];
		$workinNormal  = $workDays['May']['workonly'];
	}	
	else if($_REQUEST['month'] == '6')
	{
		$workDayVal = $workDays['June']['wkd'];
		$pubDayVal  = $workDays['June']['pub'];
		$offDayVal  = $workDays['June']['off'];
		$noffDayVal  = $workDays['June']['noff'];
		$workinNormal  = $workDays['June']['workonly'];
	}	
	else if($_REQUEST['month'] == '7')
	{
		$workDayVal = $workDays['July']['wkd'];
		$pubDayVal  = $workDays['July']['pub'];
		$offDayVal  = $workDays['July']['off'];
		$noffDayVal  = $workDays['July']['noff'];
		$workinNormal  = $workDays['July']['workonly'];
	}	
	else if($_REQUEST['month'] == '8')
	{
		$workDayVal = $workDays['August']['wkd'];
		$pubDayVal  = $workDays['August']['pub'];
		$offDayVal  = $workDays['August']['off'];
		$noffDayVal  = $workDays['August']['noff'];
		$workinNormal  = $workDays['August']['workonly'];
	}	
	else if($_REQUEST['month'] == '9')
	{
		$workDayVal = $workDays['September']['wkd'];
		$pubDayVal  = $workDays['September']['pub'];
		$offDayVal  = $workDays['September']['off'];
		$noffDayVal  = $workDays['September']['noff'];
		$workinNormal  = $workDays['September']['workonly'];
	}	
	else if($_REQUEST['month'] == '10')
	{
		$workDayVal = $workDays['October']['wkd'];
		$pubDayVal  = $workDays['October']['pub'];
		$offDayVal  = $workDays['October']['off'];
		$noffDayVal  = $workDays['October']['noff'];
		$workinNormal  = $workDays['October']['workonly'];
	}	
	else if($_REQUEST['month'] == '11')
	{
		$workDayVal = $workDays['November']['wkd'];
		$pubDayVal  = $workDays['November']['pub'];
		$offDayVal  = $workDays['November']['off'];
		$noffDayVal  = $workDays['November']['noff'];
		$workinNormal  = $workDays['November']['workonly'];
	}	
	else if($_REQUEST['month'] == '12')
	{
		$workDayVal = $workDays['December']['wkd'];
		$pubDayVal  = $workDays['December']['pub'];
		$offDayVal  = $workDays['December']['off'];
		$noffDayVal  = $workDays['December']['noff'];
		$workinNormal  = $workDays['December']['workonly'];
	}

	//get variable off days value of employee from shift plans 

	$sql5 = "SELECT * FROM ".$cid."_shiftplans_".$cur_year." WHERE id = '".$shifteamname."'";
	if($res5 = $dbc->query($sql5)){
		if($row5 = $res5->fetch_assoc()){
			$variableOFFDays = unserialize($row5['ss_data']);
			$variableoffdayssss = unserialize($row5['ss_data']);

		}
	}


	
	$empVOD = $variableOFFDays['variableOffDaysVal'];

	// Update working days of the employee

	$employeeID = $_REQUEST['emp_id'].'-'.$_REQUEST['month'] ;

	$bal_off = array(
		'off_day_pending' => $empVOD,
		'off_day_used' => '0',

	);


				$variableoffdaysssssss = $variableoffdayssss['variable_off_days'];
				$variableoffdaysssssssss = $variableoffdayssss['variableOffDaysVal'];

				if($variableoffdaysssssss == 'na')
				{
					$offDayVal = '0';
				}
				else
				{
					if($variableoffdaysssssssss == '0' || $variableoffdaysssssssss == '')
					{
						$offDayVal = '0';
					} 
					else
					{
						$offDayVal = $offDayVal;
					}
				}




		$sql4 = "UPDATE ".$cid."_monthly_shiftplans_".$cur_year." SET  wkd = '".$dbc->real_escape_string($workinNormal)."' , pub = '".$dbc->real_escape_string($pubDayVal)."', off = '".$dbc->real_escape_string($noffDayVal)."',vod = '".$dbc->real_escape_string($offDayVal)."',off_day_used = '".$dbc->real_escape_string('0')."',bal_off = '".$dbc->real_escape_string(serialize($bal_off))."' WHERE id= '".$employeeID."'";
		$dbc->query($sql4);

		echo 'success';
	}else{
		echo mysqli_error($dbc);
	}
	
	
	
	
	
	
	
	
	
	
	
	
	
	
