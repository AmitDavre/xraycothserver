<?php

	if(session_id()==''){session_start(); ob_start();}
	include('../../dbconnect/db_connect.php');
	include(DIR.'files/functions.php');
	include(DIR.'time/functions.php');
	//var_dump($_REQUEST); exit;
	//var_dump(addHours('00:20','01:15')); exit;



	// get if scan is 2 scan or 4 scan 

	// if one scan  donot calcualte 




	// get plan hours 
	// get normal hours 
	// get scan in 
	// get scan out 


	// calculate actual hours  =  late / early + break + working time
	// calculate normal hours  = from plan setting

	// calcualte late   =  plan start time - scanin
	// calcualte early  = 	 plan end time - scan out  

	// if pub donot calcualte 
	// if off donot calcualte 












	// check if all teams have shiftplans if yes then calculate other wise exit 

	// select teams from shiftplan table and check cycle details 



		$sql_shiftplans = "SELECT * FROM ".$cid."_shiftplans_".$cur_year."";

		if($result_shiftplans = $dbc->query($sql_shiftplans))
		{
			while($row_shiftplans = $result_shiftplans->fetch_assoc())
			{
				if($row_shiftplans['cycle_details'] == '')
				{
					$check_cycle_details[] =  'yes';
				}

			}
		}



		// if(!empty($check_cycle_details))
		// {
		// 	echo '1';
		// 	die();
		// }

		









function getTimeDiff($dtime,$atime)
{
    $nextDay=$dtime>$atime?1:0;
    $dep=explode(':',$dtime);
    $arr=explode(':',$atime);


    $diff=abs(mktime($dep[0],$dep[1],0,date('n'),date('j'),date('y'))-mktime($arr[0],$arr[1],0,date('n'),date('j')+$nextDay,date('y')));

    //Hour

    $hours=floor($diff/(60*60));

    //Minute 

    $mins=floor(($diff-($hours*60*60))/(60));

    //Second

    $secs=floor(($diff-(($hours*60*60)+($mins*60))));

    if(strlen($hours)<2)
    {
        $hours="0".$hours;
    }

    if(strlen($mins)<2)
    {
        $mins="0".$mins;
    }

    if(strlen($secs)<2)
    {
        $secs="0".$secs;
    }

    return $hours.':'.$mins;

}






	// NOTE : NORMAL HOURS NEEDS TO BE SAME AS FIELD IN ATTENDANCE TABLE PLANNED HOURS ACTUAL HOURS CAN BE DIFFERENT 

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


				// GET SHIFT PLAN START TIME 

				$sql3 = "SELECT * FROM ".$cid."_leave_time_settings WHERE id= '1'";

				if($res3 = $dbc->query($sql3))
				{
					if($row3 = $res3->fetch_assoc())
					{
						$planStartTime = unserialize($row3['shiftplan']);
					}
				}

				$plannedTime = $planStartTime[$row['plan']]['hours'];  // PLANNED TIME WITHOUT BREAK 
				$plannedTimeF1 = $planStartTime[$row['plan']]['f1'];  // PLANNED TIME WITHOUT BREAK 
				$plannedTimeU2 = $planStartTime[$row['plan']]['u2'];  // PLANNED TIME WITHOUT BREAK 


				$data[$row['id']]['plannedTime'] = $plannedTime;
				$data[$row['id']]['plannedTimeF1'] = $plannedTimeF1;
				$data[$row['id']]['plannedTimeU2'] = $plannedTimeU2;
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
				//if(empty($row['plan'])){$data[$row['id']]['plan'] = 'OFF';}
				$data[$row['id']]['hd'] = $row['hd'];
				$data[$row['id']]['dnr'] = $row['dnr'];
				$data[$row['id']]['planned_hrs'] = $row['planned_hrs'];
				$data[$row['id']]['plan_ot'] = $row['plan_ot'];
				$data[$row['id']]['plan_break'] = $row['plan_break'];
				
				$data[$row['id']]['ot_from'] = $row['ot_from'];
				$data[$row['id']]['ot_until'] = $row['ot_until'];
				$data[$row['id']]['ot_hrs'] = $row['ot_hrs'];
				$data[$row['id']]['ot_break'] = $row['ot_break'];
				$data[$row['id']]['shiftteam'] = $row['shiftteam'];
				$data[$row['id']]['id'] = $row['id'];
				
				
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
			

		// echo '<pre>';
		// print_r($shiftplan);
		// echo '</pre>';
		// die();


/////////////////////////////////////UPDATING OVERTIME HOURS IN OT_hours COLUMN IN ATTENDANCETABLE/////////////////////////////////////////
		foreach($data as $k1=>$v1){

			if(($v1['scan1'] != '' && $v1['scan1'] != '-') && ($v1['scan2'] != '' && $v1['scan2'] != '-')  && ($v1['scan3'] != '' && $v1['scan3'] != '-') && ($v1['scan4'] != '' && $v1['scan4'] != '-' ))
			{
				$countValue1 = '4';
			}
			else if($v1['scan1'] != '' && $v1['scan2'] != '')
			{
				$countValue1 = '2';
			}




			// GET THE PLAN DEFAULT HOURS FROM LEAVE TIME SETTING TABLE USING PLAN NAME EXAMPLE DWD 

				$sql13= "SELECT * FROM ".$cid."_leave_time_settings WHERE id ='1' ";	
				if($res13 = $dbc->query($sql13))
				{
					if($row13 = $res13->fetch_assoc())
					{
						$shiftplanValue = unserialize($row13['shiftplan']);
						$accept_lateD = $row13['accept_late']; 
						$accept_earlyD = $row13['accept_early']; 
						$otwd = $row13['otnd']; 
						$otsArrayvalue = unserialize($row13['otsa']); 
						$otsuArrayvalue = unserialize($row13['otsu']); 
						$othdArrayvalue = unserialize($row13['othd']); 
						$ot_start_afterr = $row13['ot_start_after']; 
					}
				}

				foreach ($shiftplanValue as $key13 => $value13) 
				{
					if($key13 == $v1['plan'])
					{
						$startTime11 = $value13['f1'];
						$endTime11 = $value13['u2'];
					}
				}



				$daynumeicvalue = $v1['dnr'];

				$daysArray = array('1','2','3','4','5');
				// calculate OT hours using shift plan time and default OT time

				// Get shiftplan start time and end time 

				// Get which OT is set for the date 
				if (in_array($v1['dnr'], $daysArray)) {
				    $otvalue = $otwd; // value here 1,1.5,2,3
				}
				else
				{
					 $otvalue = '';
				}

				$shiftplanStartTime =  $startTime11;   // shiftplan start time 
				$shiftplanEndTime 	=  $endTime11;	// shiftplan end time 

				$scanValue1  		=  $v1['scan1']; // scan 1 value 
				if($countValue1 == '2')
				{
					$scanValue2  		=  $v1['scan2'];	// scan 2 value 
				}
				else if($countValue1 == '4')
				{
					$scanValue2  		=  $v1['scan4'];	// scan 2 value 
				}

				$acceptEarlyTime = '00:'.$accept_earlyD ;		// accepted early minutes  convert them to compare 
				$acceptedLateTime = '00:'.$accept_lateD;	//accepted late minutes convert them to compare 




				// compare plan time with OT start after ,Acceptable late, Acceptable early	

				// get the final ot hours , ot starts from , ot untill 
				$strtofacceptedlate = strtotime($acceptedLateTime); // strtotime of accepted late 


				$strtofshiftendtime = strtotime($shiftplanEndTime); // shiftendtime strtotime
				$strtofshiftstarttime = strtotime($shiftplanStartTime); // shiftstarttime strtotime

				$strtofscanout = strtotime($scanValue2); // strtofscanout strtotime
				$strtofscanin = strtotime($scanValue1); // strtofscanin strtotime

				// start OT when strtofshiftendtime is > ot starts after + shift end time 

				$ot_start_afterrr = '00:'.$ot_start_afterr;
				$decimalacceptlate = decimalHours($ot_start_afterrr);
				$decimalshiftentime = decimalHours($shiftplanEndTime);

				$decimalshiftstarttime = decimalHours($shiftplanStartTime);


				$bewtest= $decimalacceptlate+$decimalshiftentime; // 17.5

				$bewteststart= $decimalshiftstarttime -$decimalacceptlate; // 17.5 // end 


				$decimalshiftend =  decimalHours($shiftplanEndTime);


				// if scan in < plan scan in then also calculate ot hrs 


				if($bewtest > $decimalshiftend) 
				{

					$newendttimeshift = dateHours($bewtest);
					$newendttimeshiftstart = dateHours($bewteststart);

					$newstrtotimedatetime = strtotime($newendttimeshift);


					$newstrtotimedatetimein = strtotime($newendttimeshiftstart); // start

					if($strtofscanout >= $newstrtotimedatetime)
					{
						$otHours  = getTimeDiff($shiftplanEndTime,$scanValue2);

						$otfrom = $shiftplanEndTime;
						$otuntill = $scanValue2 ;
					}
					else if($strtofscanin <= $newstrtotimedatetimein  )
					{
						$otHours  = getTimeDiff($shiftplanStartTime,$scanValue1);

						$otfrom = $scanValue1;
						$otuntill = $shiftplanStartTime ;

					}
					else
					{
						$otHours = '';

						$otfrom = '';
						$otuntill = '' ;
					}


					// N/A - 0
					// OT1 - 1
					// OT 1.5 - 1.5
					// OT 2 - 2
					// OT 3 - 3

					if($otvalue == '1') 
					{
						$otfieldname = 'ot1';
					}
					else if($otvalue == '1.5') 
					{
						$otfieldname = 'ot15';
					}
					else if($otvalue == '2') 
					{
						$otfieldname = 'ot2';
					}				
					else if($otvalue == '3') 
					{
						$otfieldname = 'ot3';
					}

					if($otvalue != '0')
					{
						$sql11= "UPDATE ".$cid."_attendance SET  ot_hrs = '".decimalHours($otHours)."', ".$otfieldname." ='".decimalHours($otHours)."'  WHERE id = '".$v1['id']."'";
						$dbc->query($sql11);


					}
				}
				// else if($decimalshiftstarttime > $bewtest_beforescan)
				// {
				// 	$newendttimeshift = dateHours($bewtest_beforescan);

				// 	$newstrtotimedatetime = strtotime($newendttimeshift);
				// 	if($strtofscanin > $newstrtotimedatetime)
				// 	{
				// 		$otHours  = getTimeDiff($shiftplanStartTime,$scanValue1);

				// 		$otfrom = $shiftplanStartTime;
				// 		$otuntill = $scanValue1 ;
				// 	}
				// 	else
				// 	{
				// 		$otHours = '';

				// 		$otfrom = '';
				// 		$otuntill = '' ;
				// 	}


				// 	// N/A - 0
				// 	// OT1 - 1
				// 	// OT 1.5 - 1.5
				// 	// OT 2 - 2
				// 	// OT 3 - 3

				// 	if($otvalue == '1') 
				// 	{
				// 		$otfieldname = 'ot1';
				// 	}
				// 	else if($otvalue == '1.5') 
				// 	{
				// 		$otfieldname = 'ot15';
				// 	}
				// 	else if($otvalue == '2') 
				// 	{
				// 		$otfieldname = 'ot2';
				// 	}				
				// 	else if($otvalue == '3') 
				// 	{
				// 		$otfieldname = 'ot3';
				// 	}

				// 	if($otvalue != '0')
				// 	{
				// 		$sql11= "UPDATE ".$cid."_attendance SET  ot_hrs = '".decimalHours($otHours)."', ".$otfieldname." ='".decimalHours($otHours)."'  WHERE id = '".$v1['id']."'";
				// 		$dbc->query($sql11);


				// 	}

				// }



		}



// die();

//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////







		foreach($data as $k=>$v){


			if(($v['scan1'] != '' && $v['scan1'] != '-') && ($v['scan2'] != '' && $v['scan2'] != '-')  && ($v['scan3'] != '' && $v['scan3'] != '-') && ($v['scan4'] != '' && $v['scan4'] != '-' ))
			{
				$countValue = '4';
			}
			else if($v['scan1'] != '' && $v['scan2'] != '')
			{
				$countValue = '2';
			}
			
			
			// echo $countValue;
			// die();

			//var_dump($v['plan']); //exit;
			$scans = $shiftplan[$v['plan']]['scans'];
			$first = decimalHours($shiftplan[$v['plan']]['first'])*60*60;
			$second = decimalHours($shiftplan[$v['plan']]['second'])*60*60;
			$breaktime = decimalHours($shiftplan[$v['plan']]['break'])*60*60;
			$addbreak = decimalHours($shiftplan[$v['plan']]['addbreak'])*60*60;

		

			$totalBrk =  $breaktime + $addbreak;
			$otplan = 0;
			if($v['ot_from'] != '')
			{
				$otplan = 1;
			}
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
			$ot_hours = 0;
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

			$ot_from = 0;
			$ot_until = 0;
			$ot_break = 0;
			$ot_hrs = 0;
			$ot_in = 0;
			$ot_out = 0;

			//$pl_in = strtotime($v['time_in']);
			//$pl_out = strtotime($v['time_out']);


			if($otplan){


				$ot_from = strtotime($v['ot_from']);
				$ot_until = strtotime($v['ot_until']);
				$ot_hrs = $v['ot_hrs'];
				$ot_break = $v['ot_break'];
			}
			//var_dump($ot_from);
			//var_dump($ot_until);
			// var_dump($scans);
			
			// if($scans == 4){ // 4 SCANS PER DAY BEGIN ////////////////////////////
			if($countValue == 4){ // 4 SCANS PER DAY BEGIN ////////////////////////////

				// die('4');
				$newPlannedTime = strtotime($v['plannedTime']);
				$plannedTimeU2 = strtotime($v['plannedTimeU2']);
				$plannedTimeU2_second = strtotime($v['plannedTimeU2']);
				$plannedTimeF1 = strtotime($v['plannedTimeF1']);

				$plan_in1 = $plannedTimeF1;
				// $plan_in1 = strtotime($v['f1']);
				$plan_out2 =$plannedTimeU2;
				// $plan_out2 = strtotime($v['u2']);



				if($plan_out2 < $plan_in1){$plan_out2 += 86400;}

				$plan_out1 = $plan_in1 + $first;
				$plan_in2 = $plan_out2 - $second;

				if(isValidDate($v['scan1']) && isValidDate($v['scan2']) && isValidDate($v['scan3']) && isValidDate($v['scan4'])){
					
					$time_in1 = strtotime($v['scan1']);
					$time_out1 = strtotime($v['scan2']);
					if($time_out1 < $time_in1){$time_out1 += 86400;}
					$time_in2 = strtotime($v['scan3']);
					if($time_in2 < $time_in1){$time_in2 += 86400;}
					$time_out2 = strtotime($v['scan4']);
					if($time_out2 < $time_in1){$time_out2 += 86400;}
					$scan1 = strtotime($v['scan1']);
					$scan2 = strtotime($v['scan4']);
					if($scan2 < $scan1){$scan2 += 86400;}
					$calculate = true;


					// echo '<pre>';
					// print_r($time_in1. '<br>');
					// print_r($time_out1. '<br>');
					// print_r($time_in2. '<br>');
					// print_r($time_out2. '<br>');
					// echo '</pre>';

				}
			}else{ // 2 SCANS PER DAY BEGIN //////////////////////////////////////

				// die('2');





				$newPlannedTime = strtotime($v['plannedTime']);
				$plannedTimeU2 = strtotime($v['plannedTimeU2']);
				$plannedTimeU2_second = strtotime($v['plannedTimeU2']);
				$plannedTimeF1 = strtotime($v['plannedTimeF1']);

				$plan_in1 = $plannedTimeF1;
				// $plan_in1 = strtotime($v['f1']);
				$plan_out2 =$plannedTimeU2;
				// $plan_out2 = strtotime($v['u2']);



				if($plan_out2 < $plan_in1){$plan_out2 += 86400;}

				$plan_out1 = $plan_in1 + $first;
				$plan_in2 = $plan_out2 - $second;

				// echo '<pre>';
				// print_r($plan_in2);
				// echo '</pre>';
				
				// if(isValidDate($v['scan1']) && isValidDate($v['scan2'])){	
					
					$scan1 = strtotime($v['scan1']);
					$scan2 = strtotime($v['scan2']);

					$time_in1 = strtotime($v['scan1']);
					$time_out2 = strtotime($v['scan2']);
					if($time_out2 < $time_in1){$time_out2 += 86400;}



					


					// if($scan2 < $scan1){$scan2 += 86400;}

					// if($scan1 < $plan_out1 && $scan2 > $plan_in2){
					// 	$time_in1 = $scan1;
					// 	$time_out1 = $plan_out1;
					// 	$time_in2 = $plan_in2;
					// 	$time_out2 = $scan2;
					// }
					// if($scan2 <= $plan_in2){
					// 	$time_in1 = $scan1;
					// 	$time_out1 = $scan2;
					// 	$time_in2 = $plan_in2;
					// 	$time_out2 = $plan_in2;
					// }
					// if($scan1 >= $plan_out1){
					// 	$time_in1 = $plan_out1;
					// 	$time_out1 = $plan_out1;
					// 	$time_in2 = $scan1;
					// 	$time_out2 = $scan2;
					// }

					// echo '<pre>';
					// print_r($time_in1. '<br>');
					// print_r($time_out1. '<br>');
					// print_r($time_in2. '<br>');
					// print_r($time_out2. '<br>');
					// echo '</pre>';

					


					$calculate = true;
				// }
			}// SCANS PER DAY END //////////////////////////////////////

			// die();
			
//$actual_hrs += (($time_out1-$time_in1) + ($time_out2-$time_in2))/60/60;
if(!empty($v['plan']) && $v['planned_hrs'] > 0){
			if($calculate){


				//if($v['plan'] != 'OFF'){
					if($time_in1 > $plan_in1){
						$late1 = ($time_in1-$plan_in1)/60/60;
		
					}else{
						$before += ($plan_in1-$time_in1)/60/60;
					}
					if($time_in1 < $plan_in1){
						$early1 = ($plan_in1-$time_in1)/60/60;
					}
					if($time_out2 > $plan_out2){
						$late2 = ($time_out2-$plan_out2)/60/60;
					}
					if($time_out2 < $plan_out2){
						$early2 = ($plan_out2-$time_out2)/60/60;
					}else{
						$after += ($time_out2-$plan_out2)/60/60;
					}
				//}


				if($otplan){

					$plan_out2_Second = $plannedTimeU2_second;



					if($ot_from == $plan_out2_Second)
					{ // OT After

						if($time_out2 < $ot_until)
						{

							$ot_hours = ($time_out2/60/60) - ($ot_from/60/60) - $ot_break;
						}
						else
						{
							$ot_hours = ($ot_until/60/60) - ($ot_from/60/60) - $ot_break;
						}						
					}


					if($ot_until == $plan_in1)
					{ // OT Before
						if($time_in1 > $ot_from)
						{
							$ot_hours = ($ot_until/60/60) - ($time_in1/60/60) - $ot_break;
						}
						else
						{
							$ot_hours = ($ot_until/60/60) - ($ot_from/60/60) - $ot_break;
						}					
					}








					if($v['plan'] == 'OFF'){
						if($scan1 <= $ot_from && $scan2 >= $ot_until){
							$ot_hours = ($ot_until/60/60) - ($ot_from/60/60) - $ot_break;
						}elseif($scan2 < $ot_until && $scan1 > $ot_from){
							$ot_hours = ($scan2/60/60) - ($scan1/60/60) - $ot_break;
						}elseif($scan1 <= $ot_from && $scan2 < $ot_until){
							$ot_hours = ($scan2/60/60) - ($ot_from/60/60) - $ot_break;
						}elseif($scan1 > $ot_from && $scan2 >= $ot_until){
							$ot_hours = ($ot_until/60/60) - ($scan1/60/60) - $ot_break;
						}						
					}

					// echo $ot_hours;
				}
	

				// echo $ot_hours;
				// die();



				if($late1 == 0 && $early1 == 0 && $late2 == 0 && $early2 == 0)
				{
					$normal_hrs += $plan;
				}
				else
				{
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

					// $normal_hrs += ($t2-$t1)/60/60;   //NORMAL HOURS 1




					$normal_hrs += ($scan2-$scan1)/60/60;   //NORMAL HOURS 1 - break 


					// check if normal hours is greater than 8 hours if yes then exculde that time 




					$totalBreak = ($totalBrk)/60/60;




					//GET VALUE OF COMPENSATE CHECK IF YES THEN COMPENSATE EARLY LATE WITH OT TIME 



					$userLInkedTeamName = $v['shiftteam'];

					// RUN QUERY TO GET SHIFTPLAN FOR THIS TEAM ON THE BASIS OF TEAM NAME


					$sql5 = "SELECT * FROM ".$cid."_shiftplans_".$cur_year." WHERE id = '".$userLInkedTeamName."'";

					if($res5 = $dbc->query($sql5))
					{
						if($row5 = $res5->fetch_assoc())
						{
							$ss_data = unserialize($row5['ss_data']);

						}
					}

					$compensateTRueOrNot = $ss_data['show_early_late'];

					$shiftplanStartTImeD =  strtotime($v['plannedTimeF1']);
					// echo $decimalshiftplanStartTImeD =  decimalHours($shiftplanStartTImeD)*60*60;
					$latetimevlaue =  (strtotime($v['scan1']) - $shiftplanStartTImeD);

					 $late11 = ($time_in1-$plan_in1)/60/60;

					// if($compensateTRueOrNot == 'yes')
					// {
					// 	// compesnate the late time with OT
					// 	// add late minutes to normal hours and minus late minutes from OT hours 

					// 	// GET LATE MINUTES 
					// 	// shiftplan start time - scan1 in time 


					// 	$ovrtimehours =  dateHours($v['ot_hrs']) ;
					// 	$decimalOvertime =  decimalHours($ovrtimehours)*60*60;
					// 	$finalOVERtime = (($decimalOvertime)/60/60 )- ($late11);


					// 	$normal_hrs = $normal_hrs - $totalBreak - $finalOVERtime ;



					// }
					// else
					// {
					// 	// Do not compensate late time with OT
					// 	// if OT is SET to true then only subtract it from the normal hours otherwise do not calculate OT 
						$ovrtimehours =  dateHours($v['ot_hrs']) ;
						$decimalOvertime =  decimalHours($ovrtimehours)*60*60;
						$finalOVERtime = ($decimalOvertime)/60/60;






						$normal_hrs = $normal_hrs - $totalBreak - $finalOVERtime;
						// $normal_hrs = $normal_hrs - $totalBreak - $finalOVERtime;
					// }


						$normal_planhrstime  =  $plan;

						$normalhrsminusplan =  $normal_hrs - $normal_planhrstime;


						$normal_hrs = $normal_hrs -$normalhrsminusplan;
					


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
					
					// $normal_hrs += ($t2-$t1)/60/60;    // NORMAL HOURS 2  BOTH NORMAL HOURS GOES INTO 1 COLUMN I.E NORMAL_HRS
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


		
				// echo $unpaid_late2;
				// die();


				$late = $late1 ;
				$early =  $early2;
				$paid_late = $paid_late1 + $paid_late2;
				$unpaid_late = $unpaid_late1 ;
				// $unpaid_late = '';
				$paid_early = $paid_early1 + $paid_early2;
				$unpaid_early =  $unpaid_early2;


				
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
				// $actual_hrs += (($time_out1-$time_in1) + ($time_out2-$time_in2))/60/60;


				$actual_hrs += $normal_hrs + $totalBreak + $finalOVERtime -$normalhrsminusplan ;


				// echo $actual_hrs.'--'.$totalBreak. '--' . $normal_hrs.'--'. $finalOVERtime .'--'.$normalhrsminusplan. '<br>';


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
			
			if($shiftplan[$v['plan']]['calc_ot'] == 1){
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
		
				if($otplan){
					if($v['hd'] == 1){ 															// holiday
						$xot[$othd[2]] += $ot_hours;
					}elseif($v['plan'] == 'OFF' && $v['dnr'] == 6){ // saterday
						$xot[$otsa[2]] += $ot_hours;
					}elseif($v['plan'] == 'OFF' && $v['dnr'] == 0){ // sunday
						$xot[$otsu[2]] += $ot_hours;
					}else{
						$xot[$otnd] += $ot_hours;
					}
				}
				else{
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
				}
			}
}else{
	// $actual_hrs += (($time_out1-$time_in1) + ($time_out2-$time_in2))/60/60;
	$actual_hrs += ($time_out2-$time_in1)/60/60;
	$normal_hrs = $actual_hrs;
}	
			// $OThoursval = $v['ot_hrs'];
			if($v['scan1']  == '-' || $v['scan2']  == '-'  )
			{}
			else{

				// get OT FIELD here 


				// echo $otfieldname;

				if($daynumeicvalue == '6' || $daynumeicvalue == '0')
				{

					// if OT is set to 2 after 8 hours insert value in ot2 


					if($daynumeicvalue == '6')
					{
						// calculate overtime for saturday 
						// actual hours is overtime in this case 
						// first OT
						$otsaturdayfirst = $otsArrayvalue['hrs'];
						$totalOT = $actual_hrs;
						$secondsatot= $actual_hrs -$otsaturdayfirst;

						if($otsArrayvalue['1'] == '1')
						{
							$xdata[$k]['ot1'] = $otsaturdayfirst;

							$allSelectedOTarray[] = '1';
						}
						else if($otsArrayvalue['1'] == '1.5')
						{
							$xdata[$k]['ot15'] = $otsaturdayfirst;
							$allSelectedOTarray[] = '1.5';
						}					
						else if($otsArrayvalue['1'] == '2')
						{
							$xdata[$k]['ot2'] = $otsaturdayfirst;
							$allSelectedOTarray[] = '2';

						}						
						else if($otsArrayvalue['1'] == '3')
						{
							$xdata[$k]['ot3'] = $otsaturdayfirst;
							$allSelectedOTarray[] = '3';

						}

						if($otsArrayvalue['2'] == '1')
						{
							$xdata[$k]['ot1'] = $secondsatot;
							$allSelectedOTarray[] = '1';

						}
						else if($otsArrayvalue['2'] == '1.5')
						{
							$xdata[$k]['ot15'] = $secondsatot;
							$allSelectedOTarray[] = '1.5';
						}					
						else if($otsArrayvalue['2'] == '2')
						{
							$xdata[$k]['ot2'] = $secondsatot;
							$allSelectedOTarray[] = '2';
						}						
						else if($otsArrayvalue['2'] == '3')
						{
							$xdata[$k]['ot3'] = $secondsatot;
							$allSelectedOTarray[] = '3';

						}

						$otselectedArray = array( '1', '1.5', '2','3');

						foreach ($allSelectedOTarray as $key2 => $value2) 
						{
							if (in_array($value2, $otselectedArray)) 
							{
						    	$allSelectedOTarray1[] =$value2;
							}
						}

					
						$otarray2 = array_unique($allSelectedOTarray1);

						if (!in_array('1', $otarray2)) 
						{
					    	$xdata[$k]['ot1'] = '';
						}
						if (!in_array('1.5', $otarray2)) 
						{
							$xdata[$k]['ot15'] = '';
						}				
						if (!in_array('2', $otarray2)) 
						{
							$xdata[$k]['ot2'] = '';
						}					

						if (!in_array('3', $otarray2)) 
						{
							$xdata[$k]['ot3'] = '';
						}



					}					


					if($daynumeicvalue == '0')
					{
						// calculate overtime for saturday 
						// actual hours is overtime in this case 
						// first OT
						$otsunfirst = $otsuArrayvalue['hrs'];
						$totalOT = $actual_hrs;
						$secondsunot= $actual_hrs -$otsunfirst;

						if($otsuArrayvalue['1'] == '1')
						{
							$xdata[$k]['ot1'] = $otsunfirst;

							$allSelectedOTarray2[] = '1';
						}
						else if($otsuArrayvalue['1'] == '1.5')
						{
							$xdata[$k]['ot15'] = $otsunfirst;
							$allSelectedOTarray2[] = '1.5';
						}					
						else if($otsuArrayvalue['1'] == '2')
						{
							$xdata[$k]['ot2'] = $otsunfirst;
							$allSelectedOTarray2[] = '2';

						}						
						else if($otsuArrayvalue['1'] == '3')
						{
							$xdata[$k]['ot3'] = $otsunfirst;
							$allSelectedOTarray2[] = '3';

						}

						if($otsuArrayvalue['2'] == '1')
						{
							$xdata[$k]['ot1'] = $secondsunot;
							$allSelectedOTarray2[] = '1';

						}
						else if($otsuArrayvalue['2'] == '1.5')
						{
							$xdata[$k]['ot15'] = $secondsunot;
							$allSelectedOTarray2[] = '1.5';
						}					
						else if($otsuArrayvalue['2'] == '2')
						{
							$xdata[$k]['ot2'] = $secondsunot;
							$allSelectedOTarray2[] = '2';
						}						
						else if($otsuArrayvalue['2'] == '3')
						{
							$xdata[$k]['ot3'] = $secondsunot;
							$allSelectedOTarray2[] = '3';

						}

						$otselectedArrays = array( '1', '1.5', '2','3');

						foreach ($allSelectedOTarray2 as $key3 => $value3) 
						{
							if (in_array($value3, $otselectedArrays)) 
							{
						    	$allSelectedOTarray3[] =$value3;
							}
						}

				
						$otarray23 = array_unique($allSelectedOTarray3);

						if (!in_array('1', $otarray23)) 
						{
					    	$xdata[$k]['ot1'] = '';
						}
						if (!in_array('1.5', $otarray23)) 
						{
							$xdata[$k]['ot15'] = '';
						}				
						if (!in_array('2', $otarray23)) 
						{
							$xdata[$k]['ot2'] = '';
						}					

						if (!in_array('3', $otarray23)) 
						{
							$xdata[$k]['ot3'] = '';
						}



					}
						

		

				
					



					// run for saturaday and sunday 
					$xdata[$k]['ot_hrs'] = '';
					$xdata[$k]['emp_id'] = $v['emp_id'];
					$xdata[$k]['actual_hrs'] = $actual_hrs;
					$xdata[$k]['normal_hrs'] = '';
					$xdata[$k]['paid_hrs'] = $normal_hrs + $xot['1'] + $xot['1.5'] + $xot['2'] + $xot['3'];
					$xdata[$k]['paid_days'] = 0;
					if($actual_hrs > 0 || $normal_hrs > 0){
						$xdata[$k]['paid_days'] = 1;
					}
					$xdata[$k]['paid_late'] = $paid_late;




					$xdata[$k]['unpaid_late'] = '';
		
					$xdata[$k]['paid_early'] = $paid_early;
					$xdata[$k]['unpaid_early'] = $unpaid_early;
					// $xdata[$k]['ot1'] = $xot['1'];
					// $xdata[$k]['ot2'] = $xot['2'];
					// $xdata[$k]['ot3'] = $xot['3'];
					$xdata[$k]['unpaid_leave'] = $unpaid_leave;
					$xdata[$k]['public'] = $public;
					$xdata[$k]['personal'] = $personal;
					$xdata[$k]['leave_hrs'] = $v['leavesum'];
					$xdata[$k]['leave_type'] = $v['leavetype'];
				}
				else
				{

					if($v['plan'] == 'PUB')
					{

						$othdfirst = $othdArrayvalue['hrs'];
						$totalOT = $actual_hrs;
						$secondhdot= $actual_hrs -$othdfirst;

						if($othdArrayvalue['1'] == '1')
						{
							$xdata[$k]['ot1'] = $othdfirst;

							$allSelectedOTarrays[] = '1';
						}
						else if($othdArrayvalue['1'] == '1.5')
						{
							$xdata[$k]['ot15'] = $othdfirst;
							$allSelectedOTarrays[] = '1.5';
						}					
						else if($othdArrayvalue['1'] == '2')
						{
							$xdata[$k]['ot2'] = $othdfirst;
							$allSelectedOTarrays[] = '2';

						}						
						else if($othdArrayvalue['1'] == '3')
						{
							$xdata[$k]['ot3'] = $othdfirst;
							$allSelectedOTarrays[] = '3';

						}

						if($othdArrayvalue['2'] == '1')
						{
							$xdata[$k]['ot1'] = $secondhdot;
							$allSelectedOTarrays[] = '1';

						}
						else if($othdArrayvalue['2'] == '1.5')
						{
							$xdata[$k]['ot15'] = $secondhdot;
							$allSelectedOTarrays[] = '1.5';
						}					
						else if($othdArrayvalue['2'] == '2')
						{
							$xdata[$k]['ot2'] = $secondhdot;
							$allSelectedOTarrays[] = '2';
						}						
						else if($othdArrayvalue['2'] == '3')
						{
							$xdata[$k]['ot3'] = $secondhdot;
							$allSelectedOTarrays[] = '3';

						}

						$otselectedArrays = array( '1', '1.5', '2','3');

						foreach ($allSelectedOTarrays as $key4 => $value4) 
						{
							if (in_array($value4, $otselectedArrays)) 
							{
						    	$allSelectedOTarray4[] =$value4;
							}
						}

						$otarray24 = array_unique($allSelectedOTarray4);

						if (!in_array('1', $otarray24)) 
						{
					    	$xdata[$k]['ot1'] = '';
						}
						if (!in_array('1.5', $otarray24)) 
						{
							$xdata[$k]['ot15'] = '';
						}				
						if (!in_array('2', $otarray24)) 
						{
							$xdata[$k]['ot2'] = '';
						}					

						if (!in_array('3', $otarray24)) 
						{
							$xdata[$k]['ot3'] = '';
						}


						// run for holiday 
						$xdata[$k]['ot_hrs'] = '';
						$xdata[$k]['emp_id'] = $v['emp_id'];
						$xdata[$k]['actual_hrs'] = $actual_hrs;
						$xdata[$k]['normal_hrs'] = '';
						$xdata[$k]['paid_hrs'] = $normal_hrs + $xot['1'] + $xot['1.5'] + $xot['2'] + $xot['3'];
						$xdata[$k]['paid_days'] = 0;
						if($actual_hrs > 0 || $normal_hrs > 0){
							$xdata[$k]['paid_days'] = 1;
						}
						$xdata[$k]['paid_late'] = $paid_late;




						$xdata[$k]['unpaid_late'] = '';
			
						$xdata[$k]['paid_early'] = $paid_early;
						$xdata[$k]['unpaid_early'] = $unpaid_early;
						// $xdata[$k]['ot1'] = $xot['1'];
						// $xdata[$k]['ot2'] = $xot['2'];
						// $xdata[$k]['ot3'] = $xot['3'];
						$xdata[$k]['unpaid_leave'] = $unpaid_leave;
						$xdata[$k]['public'] = $public;
						$xdata[$k]['personal'] = $personal;
						$xdata[$k]['leave_hrs'] = $v['leavesum'];
						$xdata[$k]['leave_type'] = $v['leavetype'];
					}
					else
					{
						//run for working days 
						$xdata[$k]['emp_id'] = $v['emp_id'];
						$xdata[$k]['actual_hrs'] = $actual_hrs;
						$xdata[$k]['normal_hrs'] = $normal_hrs;
						$xdata[$k]['paid_hrs'] = $normal_hrs + $xot['1'] + $xot['1.5'] + $xot['2'] + $xot['3'];
						$xdata[$k]['paid_days'] = 0;
						if($actual_hrs > 0 || $normal_hrs > 0){
							$xdata[$k]['paid_days'] = 1;
						}
						$xdata[$k]['paid_late'] = $paid_late;



						
						// if($compensateTRueOrNot == 'yes' )
						// {
						// 	$newOtHouRs = decimalHours($otHours);
						// 	if($newOtHouRs)
						// 	{
						// 		$latetimevaluevar =  $unpaid_late - $newOtHouRs;
						// 		$xdata[$k]['unpaid_late'] = $unpaid_late ;

						// 		// subtract only when scanin is greater than shift start time 

						// 		$shiftplanstarttimedecimal = decimalHours($shiftplanStartTime);
						// 		$scnaindecimalvalue = decimalHours($v['scan1']);

						// 		// echo $shiftplanstarttimedecimal;
						// 		if($scnaindecimalvalue < $shiftplanstarttimedecimal)
						// 		{
						// 			// check if its early late then minus only early late 

						// 			$xdata[$k]['ot15'] = $newOtHouRs   ;   // check what the selected value is then choose that column 
						// 		}
						// 		// else
						// 		// {
						// 		// 	$xdata[$k]['ot15'] = $newOtHouRs  ;
						// 		// }

						// 	}
						// 	else
						// 	{
						// 		$xdata[$k]['unpaid_late'] = $unpaid_late;
						// 		$xdata[$k]['ot15'] = '';

						// 	}
						// }
						// else
						// {
							// echo $unpaid_late;
						
						$xdata[$k]['unpaid_late'] = $unpaid_late ;
							
				

						// }
						$xdata[$k]['paid_early'] = $paid_early;
						$xdata[$k]['unpaid_early'] = $unpaid_early;
						$xdata[$k]['unpaid_leave'] = $unpaid_leave;
						$xdata[$k]['public'] = $public;
						$xdata[$k]['personal'] = $personal;
						$xdata[$k]['leave_hrs'] = $v['leavesum'];
						$xdata[$k]['leave_type'] = $v['leavetype'];

					}
					


				}



			}

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


		// echo '<pre>';
		// print_r($xdata);
		// echo '</pre>';


		// die();



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




			












