<?php

	if(session_id()==''){session_start(); ob_start();}
	include('../../dbconnect/db_connect.php');
	include(DIR.'files/functions.php');
	include(DIR.'time/functions.php');

	$shiftplan = getFullShiftplan();

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






	$sdate = date('Y-m-d', strtotime($_REQUEST['sdate']));
	$edate = date('Y-m-d', strtotime($_REQUEST['edate']));
	$dates = dateRange($sdate, $edate, '+1 day', 'Y-m-d');
	
	
	//$date = $_REQUEST['date'];
	$time_settings = getTimeSettings();
	//$compensations = unserialize($time_settings['compensations']);
	//var_dump($compensations); //exit;


	$data = array();


	// echo '<pre>';
	// print_r($dates);
	// echo '</pre>';
	// die();

	foreach($dates as $d){
	
		$holiday = getHolidayFromDate($cid, $d);
		$day = date('D', strtotime($d));
		
		
		$data = array();
		if(isset($_REQUEST['id'])){
			$sql = "SELECT * FROM ".$cid."_attendance WHERE id = '".$_REQUEST['id']."'";
		}else{
			$sql = "SELECT * FROM ".$cid."_attendance WHERE date = '$d' AND approved = 0";
		}


		if($res = $dbc->query($sql)){
			while($row = $res->fetch_assoc()){

				// GET SHIFT PLAN START TIME 

				$sql3 = "SELECT * FROM ".$cid."_leave_time_settings WHERE id= '1'";

				if($res3 = $dbc->query($sql3))
				{
					if($row3 = $res3->fetch_assoc())
					{
						$shiftplanDetails = unserialize($row3['shiftplan']);
					}
				}

				$plannedhours = $shiftplanDetails[$row['plan']]['hours'];  // PLANNED TIME WITHOUT BREAK 
				$plannedTimeF1 = $shiftplanDetails[$row['plan']]['f1'];  // PLANNED TIME WITHOUT BREAK 
				$plannedTimeU2 = $shiftplanDetails[$row['plan']]['u2'];  // PLANNED TIME WITHOUT BREAK 


				$data[$row['id']]['plannedTime'] = $plannedhours;
				$data[$row['id']]['plannedTimeF1'] = $plannedTimeF1;
				$data[$row['id']]['plannedTimeU2'] = $plannedTimeU2;
				$data[$row['id']]['emp_id'] = $row['emp_id'];
				$data[$row['id']]['f1'] = $row['f1'];
				$data[$row['id']]['u1'] = $row['u1'];
				$data[$row['id']]['f2'] = $row['f2'];
				$data[$row['id']]['u2'] = $row['u2'];
				$data[$row['id']]['time_in'] = $row['f1'];
				$data[$row['id']]['time_out'] = $row['u2'];
				$data[$row['id']]['plan'] = $row['plan'];
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
		


					
				}


			}

	

			foreach($data as $k1=>$v1){

				if(($v1['scan1'] != '' && $v1['scan1'] != '-') && ($v1['scan2'] != '' && $v1['scan2'] != '-')  && ($v1['scan3'] != '' && $v1['scan3'] != '-') && ($v1['scan4'] != '' && $v1['scan4'] != '-' ))
				{
					$countValue = '4';
				}
				else if($v1['scan1'] != '' && $v1['scan2'] != '')
				{
					$countValue = '2';
				}
				else
				{
					$countValue = 'error';
				}

			

				// if count value  = 2 or 4 or error run this 

				$scans = $shiftplan[$v1['plan']]['scans'];
				$first = decimalHours($shiftplan[$v1['plan']]['first'])*60*60;
				$second = decimalHours($shiftplan[$v1['plan']]['second'])*60*60;
				$breaktime = decimalHours($shiftplan[$v1['plan']]['break']);
				$addbreak = decimalHours($shiftplan[$v1['plan']]['addbreak'])*60*60;
				$shiftplanStart = decimalHours($shiftplan[$v1['plan']]['f1'])*60*60;
				$shiftplanEnd = decimalHours($shiftplan[$v1['plan']]['u2'])*60*60;

				$totalBrk =  $breaktime + $addbreak;


				if($v1['plan'] == 'NSG')
				{
					$scanValue1  		=  $v1['scan2']; // scan 1 value 

				}
				else
				{
				$scanValue1  		=  $v1['scan1']; // scan 1 value 

				}
				if($countValue == '2' && $v1['scan2'] != '-')
				{
					if($v1['plan'] == 'NSG')
					{
					$scanValue2  		=  $v1['scan1'];	// scan 2 value 

					}
					else
					{
					$scanValue2  		=  $v1['scan2'];	// scan 2 value 

					}
				}
				else if($countValue == '4' && $v1['scan4'] != '-')
				{
					$scanValue2  		=  $v1['scan4'];	// scan 2 value 
				}
				else
				{
					$scanValue1 = 'nocal';
					$scanValue2 = 'nocal';
				}



				// get value of late and early from the shiftplan 

				if(($v1['shiftteam'] != '') && ($v1['plan'] != ''))
				{

					
						if($scanValue2 != 'nocal' && $scanValue1 != 'nocal')
						{

							$sql5 = "SELECT * FROM ".$cid."_shiftplans_".$cur_year." WHERE id = '".$v1['shiftteam']."'";

							if($res5 = $dbc->query($sql5))
							{
								if($row5 = $res5->fetch_assoc())
								{
									$ss_data = unserialize($row5['ss_data']);

								}
							}


							$normal_hrs = $ss_data['normalhours'] ;// get normal hours from shiftplan 
							$earlyLatecheck = $ss_data['early_late'] ;// earlylate check from shiftplan 
							$accept_early = $ss_data['accept_early'] ;// early check from shiftplan 
							$accept_late = $ss_data['accept_late'] ;// late check from shiftplan 
							$planTimeIN = $v1['plannedTimeF1'] ; 
							$planTimeOUT = $v1['plannedTimeU2'] ; 






							$normal_hrsDecimal = decimalHours($ss_data['normalhours']) ;//normal hrs db
					
							if(strtotime($scanValue2) >= strtotime($scanValue1) )
							{
								$acutalworktime = (strtotime($scanValue2)- strtotime($scanValue1) )/60/60; // actual hrs db

							}
							else
							{

								$acutalworktime = (strtotime($scanValue1)- strtotime($scanValue2) )/60/60; // actual hrs db
							}
							$shiftplangivenbreak = $acutalworktime -$breaktime;
							$acutalworktimewithoutbreak = $shiftplangivenbreak; // actual hrs without break db

							// accept early late part here 

							// if yes then allow for the time after that calacualte early late 
							// if no then calcualte from and till scan time 

							if($earlyLatecheck == 'yes')
							{
								// check if they are 10 mins early or late then don't count 
								// plan time in - scan in = late 
								// calcualte when scan is greater than plan time in 

								if(strtotime($scanValue1) >= strtotime($planTimeIN))
								{ 
									$latetimestrt = (decimalHours($scanValue1)- decimalHours($planTimeIN) );

									$accept_latestrt =decimalHours($accept_late);

									if($latetimestrt > $accept_latestrt)
									{
										$latetime = (strtotime($scanValue1)- strtotime($planTimeIN) )/60/60;
									}
									else
									{
										$latetime = 0;
									}


									$checkecho = 'if';
								}								

								else if(strtotime($scanValue1) < strtotime($planTimeIN))
								{
									
									$latetimestrt = (decimalHours($planTimeIN)- decimalHours($scanValue1) );

									$accept_latestrt =decimalHours($accept_late);

									if($latetimestrt > $accept_latestrt)
									{
										// $latetime = (strtotime($planTimeIN)- strtotime($scanValue1) )/60/60;
										$latetime = 0;

									}
									else
									{
										$latetime = 0;
									}

									$checkecho = 'else2 ';

									
								}
								
								else
								{
									$latetime = 0;
									$checkecho = 'else3 ';

								}

								if(strtotime($planTimeOUT) >= strtotime($scanValue2))
								{
									$earlytimestrt = (decimalHours($planTimeOUT)- decimalHours($scanValue2) );

									$accept_earlystrt =decimalHours($accept_early);

									if($earlytimestrt > $accept_earlystrt)
									{
										$earlytime = (strtotime($planTimeOUT)- strtotime($scanValue2) )/60/60;
									}
									else
									{
										$earlytime = 0;
									}

								}
								else
								{
									$earlytime = 0;
								}


									// echo $earlytimestrt .'<br>';
									// echo $accept_earlystrt .'<br>';

								// check if late early passes the default values 


							}


							// echo $checkecho.'--'.$latetime . '--'. $earlytime . '--'.$planTimeIN.'--'.$scanValue1.'--'.$accept_latestrt .'<br>';

							// CALCULATE OT HOURS


							// if OT is YES 

							// OT starts after 30 mins
							// OT periods  30 mins
							// OT on working day OT 1.5

							// OT on Saturday  first 8 hours OT1  then OT3
							// OT on Sunday    first 8 hours OT2  then OT3
							// OT on holidays  first 8 hours OT2 then OT3
							
							$overtimeYesNo = $ss_data['overtime'];
							// if sat / sun then calucalte the plan if exists otherwise calculate default setting ot 



							if($overtimeYesNo == 'yes')
							{	

								
								$ot_Starts = '00:30'; // after or equal to 30 mins
								$ot_working_day = '1.5';

								if($v1['plan'] )
								{
									$ot_on_saturday = '1.5';   // first 8 hours ot 1 then ot 3
									$ot_on_sunday = '1.5'; 	// first 8 hours ot 2 then ot 3
									$ot_on_holidays = '2';  // first 8 hours ot 2 then ot 3
								}
								else
								{
									$ot_on_saturday = '1';   // first 8 hours ot 1 then ot 3
									$ot_on_sunday = '2'; 	// first 8 hours ot 2 then ot 3
									$ot_on_holidays = '2';  // first 8 hours ot 2 then ot 3
								}


								
					
								$accept_late_plan = $ss_dataTeam['accept_late']; 
								$accept_early_plan = $ss_dataTeam['accept_early']; 
								$ot_start_after_value = '00:30'; 
								$otwd_value = '1.5';	




								$sql13= "SELECT * FROM ".$cid."_leave_time_settings WHERE id ='1' ";	
								if($res13 = $dbc->query($sql13))
								{
									if($row13 = $res13->fetch_assoc())
									{
										$shiftplanValue = unserialize($row13['shiftplan']);
										$accept_lateD = $accept_late_plan; 
										$accept_earlyD = $accept_early_plan; 
										$otwd = $otwd_value; 
										$ot_start_afterr = $ot_start_after_value; 
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
								else if ($v1['dnr'] == '6')
								{
									if($v1['plan'] )
									{
									 	$otvalue = '1.5';
									}
									else
									{
									 	$otvalue = '1';
									}
								}	
								else if ($v1['dnr'] == '0')
								{
									if($v1['plan'] )
									{
									 	$otvalue = '1.5';
									}
									else
									{
									 	$otvalue = '2';
									}

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

								$acceptEarlyTime = $accept_earlyD ;		// accepted early minutes  convert them to compare 
								$acceptedLateTime = $accept_lateD;	//accepted late minutes convert them to compare 




								// compare plan time with OT start after ,Acceptable late, Acceptable early	

								// get the final ot hours , ot starts from , ot untill 
								$strtofacceptedlate = strtotime($acceptedLateTime); // strtotime of accepted late 


								$strtofshiftendtime = strtotime($shiftplanEndTime); // shiftendtime strtotime
								$strtofshiftstarttime = strtotime($shiftplanStartTime); // shiftstarttime strtotime

								$strtofscanout = strtotime($scanValue2); // strtofscanout strtotime
								$strtofscanin = strtotime($scanValue1); // strtofscanin strtotime

								// start OT when strtofshiftendtime is > ot starts after + shift end time 

								$ot_start_afterrr = $ot_start_afterr;
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
										$otHours  = (strtotime($scanValue2)- strtotime($shiftplanEndTime) )/60/60;

										$otfrom = $shiftplanEndTime;
										$otuntill = $scanValue2 ;

										$checkifelse = 'if';

										// if(($v1['plan'] == 'NSG') && (strtotime($scanValue1) >= strtotime($shiftplanEndTime)))
										// {
										// 	$sql11= "UPDATE ".$cid."_attendance SET  ot_hrs = '0', ot1 ='0', ot15 ='0'  WHERE id = '".$v1['id']."'";
										// 	$dbc->query($sql11);
										// }


									}
									else if($strtofscanin <= $newstrtotimedatetimein  )
									{

										// if scanvalue1 is > than shiftplan start time 

										$scaninstrtotime = strtotime($scanValue1);
										$planstrtotime  = strtotime($shiftplanStartTime);

										$otHours  = (strtotime($shiftplanStartTime)- strtotime($scanValue1) )/60/60;
										

										$otfrom = $scanValue1;
										$otuntill = $shiftplanStartTime ;
										$checkifelse = 'else';

										



									}
									else
									{
										$otHours = '';

										$otfrom = '';
										$otuntill = '' ;
										$checkifelse = 'else2';

									}



									
									// N/A - 0
									// OT1 - 1
									// OT 1.5 - 1.5
									// OT 2 - 2
									// OT 3 - 3	

									// echo $otHours.'--'.$otfrom.'--'.$otuntill.'--'.$shiftplanStartTime.'--'.$shiftplanEndTime.'--'.$scanValue1.'--'.$scanValue2.'--'.$checkifelse.'--'. '<br>';

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
										if(($v1['plan'] == 'PUB') || ($v1['plan'] == 'OFF') || ($v1['plan'] == 'NSG'))
										{
											$sql11= "UPDATE ".$cid."_attendance SET  ot_hrs = '0', ot1 ='0' , ot15 ='0', ot2 ='0'  WHERE id = '".$v1['id']."'";
											$dbc->query($sql11);
										}

										// else if(($v1['plan'] == 'NSG') && ($nsgcheck == '0'))
										// {
										// 	$sql11= "UPDATE ".$cid."_attendance SET  ot_hrs = '0', ot1 ='0', ot15 ='0'  WHERE id = '".$v1['id']."'";
										// 	$dbc->query($sql11);
										// }
										else
										{
											$sql11= "UPDATE ".$cid."_attendance SET  ot_hrs = '".decimalHours($otHours)."', ".$otfieldname." ='".decimalHours($otHours)."'  WHERE id = '".$v1['id']."'";
											$dbc->query($sql11);

										}



									}

								}
								else
								{


								}
							}
							else
							{
								$xdata[$v1['id']]['ot_hrs'] = 0;
								$xdata[$v1['id']]['ot1'] = 0;
								$xdata[$v1['id']]['ot15'] = 0;
								$xdata[$v1['id']]['ot2'] = 0;
								$xdata[$v1['id']]['ot3'] = 0;
							}




							// echo '<pre>';
							// print_r($ss_data);
							// echo '</pre>';

							// die();

							if(($v1['plan'] == 'PUB') || ($v1['plan'] == 'OFF'))
							{
								$xdata[$v1['id']]['actual_hrs'] = 0;
								$xdata[$v1['id']]['normal_hrs'] = 0;
								$xdata[$v1['id']]['unpaid_late'] = 0;
								$xdata[$v1['id']]['unpaid_early'] = 0;
							}
							else {

								$xdata[$v1['id']]['actual_hrs'] = $acutalworktimewithoutbreak;
								$xdata[$v1['id']]['normal_hrs'] = $normal_hrsDecimal;
								$xdata[$v1['id']]['unpaid_late'] = $latetime;
								$xdata[$v1['id']]['unpaid_early'] = $earlytime;
							}


							
							// $actual_hrs =  // scan in - scan out + break time +  extra work time 
						}

					

				}
				
	

			}


		}


		// echo '<pre>';
		// print_r($xdata);
		// echo '</pre>';

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


		

?>







