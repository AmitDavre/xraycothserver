<?

	if(session_id()==''){session_start();}
	ob_start();
	include('../../dbconnect/db_connect.php');
	//include(DIR.'files/functions.php');
	include(DIR.'time/functions.php');
	include(DIR.'leave/functions.php');

	// update scan 2 to 1 if shiftplan is night 



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





		
	//$leave_status = array('RQ'=>$lng['Pending'],'CA'=>$lng['Cancelled'],'AP'=>$lng['Approved'],'RJ'=>$lng['Rejected']);
	$leave_types = getLeaveTypes($cid);
	// var_dump($leave_types); exit;
	
	$time_settings = getTimeSettings();
	$var_allow = unserialize($time_settings['var_allow']);
	$tmp = unserialize($time_settings['shiftplan']);
	foreach($tmp as $k=>$v){
		$shiftplans[$k] = $v['scans'];
	}
	//var_dump($_REQUEST); exit;
	
	$compensations = getCompensations();

	// echo  '<pre>';
	// print_r($_REQUEST['sdate']);
	// echo  '</pre>';
	// die();

	/*$employees = getEmployeeNameId($cid);
	$empArray = '';
	foreach($employees as $k=>$v){
		$empArray .= $k."','";
	}
	$empArray = substr($empArray, 0,-3);*/
	//var_dump($empArray); //exit;
	
	// Get attendance table data  
	// Get team name 
	// Get shift schedule of that team 
	// insert shiftplan closest to the scan date 




	if($_REQUEST['sdate'] != '' && $_REQUEST['edate'] != '') 
	{

		$sql1 = "SELECT * FROM ".$cid."_attendance WHERE plan = ''";
		if($res1 = $dbc->query($sql1))
		{
			while($row1 = $res1->fetch_assoc())
			{

				$month_value  =  $row1['month'];
				$emp_id_value  =  $row1['emp_id'];

				// check if monthly plan is created for the month if month and id both exists then create 

				$sql9 = "SELECT * FROM ".$cid."_monthly_shiftplans_".$cur_year." WHERE month = '".$month_value."' AND emp_id = '".$emp_id_value."'";
				if($res9 = $dbc->query($sql9))
				{
					if($row9 = $res9->fetch_assoc())
					{

						$teamName = $row1['shiftteam'];
						$rowId = $row1['id']; // id of row which needs to be updated 
						$startdate = $row1['date'];
						$plan_hrs = $row1['plan_hrs'];


						$sql2 = "SELECT * FROM ".$cid."_shiftplans_".$cur_year. " WHERE id= '".$teamName."'";

						if($res2 = $dbc->query($sql2))
						{
							if($row2 = $res2->fetch_assoc())
							{

								$ss_dataTeam = unserialize($row2['ss_data']);


								if($row2['cycle_details'] != '')
								{
									$cyc_data = unserialize($row2['cycle_details']);
								}
								else
								{
									$cyc_data = '';
								}
							}
						}


						$scanStartDate = date('Y-m-d', strtotime($startdate)); 

						$month = date('F', strtotime($scanStartDate));
						$date = date('d', strtotime($scanStartDate));

						$plannedShift = $cyc_data[$month][$date]['s1'];

						// echo '<pre>';
						// print_r($cyc_data);
						// echo '</pre>';

						$newPlanhrs = $plannedShift.$plan_hrs;

						// GET PLAN HOURS 

						$sql3 = "SELECT * FROM ".$cid."_leave_time_settings WHERE id= '1'";

						if($res3 = $dbc->query($sql3))
						{
							if($row3 = $res3->fetch_assoc())
							{
								$workingHrs3 = unserialize($row3['shiftplan']);
							}
						}

						// GET PLAN VALUE AND UPDATE IT IN THE METASCANDATA ROW 



						$plannedHrs =  $workingHrs3[$plannedShift]['hours'];

						$sql4 = "UPDATE ".$cid."_attendance SET plan = '".$plannedShift."' , plan_hrs  = '".$newPlanhrs."' , planned_hrs = '".$plannedHrs."' WHERE id = '".$rowId."'";

						$dbc->query($sql4);

					}
				}
		
			}

		}

		// $sql11111 = "SELECT * FROM ".$cid."_attendance where plan ='NSG'";
		// if($res2221 = $dbc->query($sql11111))
		// {
		// 	while($row1111 = $res2221->fetch_assoc())
		// 	{


		// 		$sql4111 = "UPDATE ".$cid."_attendance SET scan2 = '".$row1111['scan1']."' , scan1  = '".$row1111['scan2']."'  WHERE id = '".$row1111['id']."'";

		// 				$dbc->query($sql4111);


			
		// 	}
		// }


		// Update leave type and leave hours for plan is blank and plan is filled 

		$sql15 = "SELECT * FROM ".$cid."_attendance WHERE leave_type = ''";
		if($res15 = $dbc->query($sql15))
		{
			while($row15 = $res15->fetch_assoc())
			{

				$month_value15  =  $row15['month'];
				$emp_id_value15  =  $row15['emp_id'];
				$date15  =  $row15['date'];

				$sql16 = "SELECT * FROM ".$cid."_leaves_data WHERE emp_id = '".$emp_id_value15."' and date= '".$date15."'";
				if($res16 = $dbc->query($sql16))
				{
					if($row16 = $res16->fetch_assoc())
					{
						$leavedataarray[$row15['id']]=$row16;

					}
				}
				// UPDATE HERE in attendance 
			}


			foreach ($leavedataarray as $key11 => $value11) 
			{
				$sql7 = "UPDATE ".$cid."_attendance SET leave_type = '".$value11['leave_type']."' , leave_hrs  = '".$value11['hours']."' WHERE id = '".$key11."'";

				$dbc->query($sql7);

			}

		}		


		// if leave type exists then update 

		$sql18 = "SELECT * FROM ".$cid."_attendance WHERE leave_type != ''";
		if($res18 = $dbc->query($sql18))
		{
			while($row18 = $res18->fetch_assoc())
			{

				$month_value18  =  $row18['month'];
				$emp_id_value18  =  $row18['emp_id'];
				$date18  =  $row18['date'];

				$sql19 = "SELECT * FROM ".$cid."_leaves_data WHERE emp_id = '".$emp_id_value18."' and date= '".$date18."'";
				if($res19 = $dbc->query($sql19))
				{
					if($row19 = $res16->fetch_assoc())
					{
						
		
						$leavedataarray2[$row18['id']]=$row19;

					}
				}
				// UPDATE HERE in attendance 
			}


			foreach ($leavedataarray2 as $key12 => $value12) 
			{
				$sq20 = "UPDATE ".$cid."_attendance SET leave_type = '".$value12['leave_type']."' , leave_hrs  = '".$value12['hours']."' WHERE id = '".$key12."'";

				$dbc->query($sq20);

			}

		}


		// echo '<pre>';
		// print_r($leavedataarray2);
		// echo '</pre>';

		
		



	}



	


	$sql6 = "SELECT * FROM ".$cid."_attendance WHERE plan != ''";
	if($res6 = $dbc->query($sql6))
	{
		while($row6 = $res6->fetch_assoc())
		{
			


			$sql5 = "UPDATE ".$cid."_metascandata SET linkedPlan = '".$row6['plan']."' WHERE emp_id= '".$row6['emp_id']."' AND datescan = '".$row6['date']."' AND LEFT(scan_in , 5) = '".$row6['scan1']."'";
			// $sql7 = "UPDATE ".$cid."_metascandata SET linkedPlan = '".$row6['plan']."' WHERE emp_id= '".$row6['emp_id']."' AND datescan = '".$row6['date']."' AND scan_in = '".$row6['scan2']."'";
			$dbc->query($sql5);	

			$sq22l5 = "UPDATE ".$cid."_metascandata SET linkedPlan = '".$row6['plan']."' WHERE emp_id= '".$row6['emp_id']."' AND datescan = '".$row6['date']."' AND LEFT(scan_out , 5) = '".$row6['scan2']."'";
			// $sql7 = "UPDATE ".$cid."_metascandata SET linkedPlan = '".$row6['plan']."' WHERE emp_id= '".$row6['emp_id']."' AND datescan = '".$row6['date']."' AND scan_in = '".$row6['scan2']."'";
			$dbc->query($sq22l5);
			// $dbc->query($sql7);
	


		}
	}



	// die();
	$date_start = date('Y-m-d', strtotime($_REQUEST['sdate']));
	$date_end = date('Y-m-d', strtotime($_REQUEST['edate']));
	//$date_start = date('Y-m-d', strtotime('2020-09-21'));
	//$date_end = date('Y-m-d', strtotime('2020-09-26'));
	//$_REQUEST['team'] = 'all';
	//$filter = '';
	
	$where = " (date BETWEEN '".$date_start."' AND '".$date_end."')";// AND plan != 'OFF' "; // 
	if(isset($_REQUEST['filter'])){
		$filter = $_REQUEST['filter'];
		if($filter == 'leave'){
			$where .= " AND leave_type != ''";
		}
		if($filter == 'late'){
			$where .= " AND (paid_late > 0 OR paid_early > 0 OR unpaid_late > 0 OR unpaid_early > 0)";
		}
		if($filter == 'ot'){
			$where .= "  AND (ot1 != '-' OR ot15 != '-' OR ot2 != '-' OR ot3 != '-')";
		}
		if($filter == 'plan'){
			$where .= " AND plan = ''";
		}
		/*if($filter == 'wday'){
			$where .= " AND plan != 'OFF'";
		}*/
	}
	if(isset($_REQUEST['apprfilter'])){
		$apprfilter = $_REQUEST['apprfilter'];
		if($apprfilter == 'appr'){
			$where .= " AND (approved = '1')";
		}else if($apprfilter == 'nappr'){
			$where .= " AND (approved = '0' OR approved IS NULL)";
		}
	}
	if(!empty($_REQUEST['emp_id'])){
		$where .= " AND (
			LOWER(emp_id) LIKE '%".strtolower($_REQUEST['emp_id'])."%' OR 
			LOWER(en_name) LIKE '%".strtolower($_REQUEST['emp_id'])."%' OR 
			LOWER(th_name) LIKE '%".strtolower($_REQUEST['emp_id'])."%')";
	}
	
	$teams = '';
	$tmp = getShifTeams();
	if($_SESSION['rego']['teams'] != 'all'){ // NOT ADMIN //////////////////////
		$team = explode(',',$_SESSION['rego']['teams']);
		foreach($team as $k=>$v){
			$teams .= "'$v',";
		}
		$teams = substr($teams,0,-1);
	}
	if($_REQUEST['team'] != 'all'){
		$teams = "'".$_REQUEST['team']."'";
		//$teams .= "' '";
	}
	//var_dump($team); 
	if(!empty($teams)){
		// $where .= " AND shiftteam in(".$teams.")";
	}
	//var_dump($teams); exit;


	$selectedteams = $_REQUEST['team_filter'] ;


	// if($selectedteams)
	// {
	// 	$explodeteams=  explode(',', $selectedteams);

	// 	$teamsselected = "'" . implode ( "', '", $explodeteams) . "'";

	// }





	// $where .= " AND  LOWER(shiftteam) in (".strtolower($teamsselected).")";


	


	$sql12 = "SELECT * FROM ".$cid."_attendance WHERE ".$where." ORDER BY emp_id ASC, date ASC";	

	if($res12 = $dbc->query($sql12)){
		while($row12 = $res12->fetch_assoc()){



			if(($row12['scan1'] != '' && $row12['scan1'] != '-') && ($row12['scan2'] != '' && $row12['scan2'] != '-')  && ($row12['scan3'] != '' && $row12['scan3'] != '-') && ($row12['scan4'] != '' && $row12['scan4'] != '-' ))
			{
				$countValue1 = '4';
			}
			else if($row12['scan1'] != '' && $row12['scan2'] != '')
			{
				$countValue1 = '2';
			}



			// GET PLAN DEFAULT SETTINGS FROM SHIFTPLAN TABLE OF THE SHIFTPLAN 


			$sql2_getshiftteam = "SELECT * FROM ".$cid."_shiftplans_".$cur_year. " WHERE id= '".$row12['shiftteam']."'";

			if($res2_getshifteam = $dbc->query($sql2_getshiftteam))
			{
				if($row2_get_shiftteam = $res2_getshifteam->fetch_assoc())
				{

					$ss_dataTeam = unserialize($row2_get_shiftteam['ss_data']);

				}
			}


			$accept_late_plan = $ss_dataTeam['accept_late']; 
			$accept_early_plan = $ss_dataTeam['accept_early']; 
			$ot_start_after_value = '00:30'; 
			$otwd_value = '1.5';	



			// GET THE PLAN DEFAULT HOURS FROM LEAVE TIME SETTING TABLE USING PLAN NAME EXAMPLE DWD 

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
					if($key13 == $row12['plan'])
					{
						$startTime11 = $value13['f1'];
						$endTime11 = $value13['u2'];
					}
				}





				$daysArray = array('1','2','3','4','5');
				// calculate OT hours using shift plan time and default OT time

				// Get shiftplan start time and end time 

				// Get which OT is set for the date 
				if (in_array($row12['dnr'], $daysArray)) {
				    $otvalue = $otwd; // value here 1,1.5,2,3
				}
				else if ($row12['dnr'] == '6')
				{
					 $otvalue = '1';
				}	
				else if ($row12['dnr'] == '0')
				{
					 $otvalue = '2';
				}
				else
				{
					 $otvalue = '';
				}



				$shiftplanStartTime =  $startTime11;   // shiftplan start time 
				$shiftplanEndTime 	=  $endTime11;	// shiftplan end time 

				$scanValue1  		=  $row12['scan1']; // scan 1 value 

				if($countValue1 == '2')
				{
					$scanValue2  		=  $row12['scan2'];	// scan 2 value 
				}
				else if($countValue1 == '4')
				{
					$scanValue2  		=  $row12['scan4'];	// scan 2 value 
				}
				

				$acceptEarlyTime = $accept_earlyD ;		// accepted early minutes  convert them to compare 
				$acceptedLateTime = $accept_lateD;	//accepted late minutes convert them to compare 




				// compare plan time with OT start after ,Acceptable late, Acceptable early	

				// get the final ot hours , ot starts from , ot untill 
				$strtofacceptedlate = strtotime($acceptedLateTime); // strtotime of accepted late 


				$strtofshiftendtime = strtotime($shiftplanEndTime); // shiftendtime strtotime
				$strtofscanout = strtotime($scanValue2); // strtofscanout strtotime
				$strtofscanin = strtotime($scanValue1); // strtofscanin strtotime



				$ot_start_afterrr = $ot_start_afterr;

				$decimalacceptlate = decimalHours($ot_start_afterrr);


				$decimalshiftentime = decimalHours($shiftplanEndTime); // end 
				$decimalshiftstarttime = decimalHours($shiftplanStartTime); // start 


				$bewtest= $decimalacceptlate+$decimalshiftentime; // 17.5 // end 
				$bewteststart= $decimalshiftstarttime -$decimalacceptlate; // 17.5 // end 



				$decimalshiftend =  decimalHours($shiftplanEndTime); // end 

				$decimalshiftstart =  decimalHours($shiftplanStartTime); // start






				if($bewtest > $decimalshiftend) 
				{

					$newendttimeshift = dateHours($bewtest);
					$newendttimeshiftstart = dateHours($bewteststart);

					$newstrtotimedatetime = strtotime($newendttimeshift); // end

					$newstrtotimedatetimein = strtotime($newendttimeshiftstart); // start

					
					// add late/early 

					if($strtofscanout >= $newstrtotimedatetime)
					{
						// $otHours  = getTimeDiff($shiftplanEndTime,$scanValue2);

						$otHours  = (strtotime($scanValue2)- strtotime($shiftplanEndTime) )/60/60;


						$otfrom = $shiftplanEndTime;
						$otuntill = $scanValue2 ;



					}
					else if($strtofscanin <= $newstrtotimedatetimein  )
					{

						// echo $scanValue1 ;

						// $otHours  = getTimeDiff($shiftplanStartTime,$scanValue1);

						$scaninstrtotime = strtotime($scanValue1);
						$planstrtotime  = strtotime($shiftplanStartTime);

						$otHours  = (strtotime($shiftplanStartTime)- strtotime($scanValue1) )/60/60;


						$otfrom = $scanValue1;
						$otuntill = $shiftplanStartTime ;


					}
					else
					{
						$otHours = '';

						$otfrom = '';
						$otuntill = '' ;
					}


					// echo $otfrom;



					if($otvalue != '0')
					{
						$sql11= "UPDATE ".$cid."_attendance SET ot_from = '".$otfrom."' , ot_until  = '".$otuntill."' , ot_type  = '".$otvalue."'  WHERE id = '".$row12['id']."'";
						// $dbc->query($sql11);

					}
				


				}

	


		}
	}


				// die();


	$nr = 0;
	$data = array();
	$sql = "SELECT * FROM ".$cid."_attendance WHERE ".$where." ORDER BY emp_id ASC, date ASC";	

	// echo $sql;
	// die();
	if($res = $dbc->query($sql)){
		while($row = $res->fetch_assoc()){


		
			$plan = $row['planned_hrs'] + $row['ot_hrs'];
			$done = $row['unpaid_late'] + $row['unpaid_early'] + $row['normal_hrs'] + $row['unpaid_leave'] + $row['ot1'] + $row['ot15'] + $row['ot2'] + $row['ot3'];

			// echo $plan.'_test' ;

			// echo $done.'_value';


			$diff = bcsub($plan,$done,10);
			$check = '';



			if($diff != 0){
				$check = 'non';
			}

			// echo $row['ot_hrs'].'--'.$row['planned_hrs'] .'--'.$plan .' -- '. $done.'--'.$row['scan1'].'--'.$diff.'--'.$check.'<br>';



			$locked = '';
			if($row['locked']){$locked = 'locked';}
			$approved = '';
			if($row['approved']){$approved = 'approved';}
			
			$proceed = true;	
			$spd = $shiftplans[$row['plan']];
			if($filter == 'scan'){
				if($spd == 4){
					if($row['scan1'] != '-' && $row['scan2'] != '-' && $row['scan3'] != '-' && $row['scan4'] != '-'){$proceed = false;}
				}else{
					if($row['scan1'] != '-' && $row['scan2'] != '-'){$proceed = false;}
				}		
			}
			if($filter == 'itime'){
				if($check == ''){$proceed = false;}
				/*if($spd == 4){
					if(strtotime($row['scan1']) <= strtotime($row['f1']) && strtotime($row['scan2']) >= strtotime($row['u1']) && strtotime($row['scan3']) <= strtotime($row['f2']) && strtotime($row['scan4']) >= strtotime($row['u2'])){
						$proceed = false;
					}
					if($row['scan1'] == '-' || $row['scan2'] == '-' || $row['scan3'] == '-' || $row['scan4'] == '-'){$proceed = false;}
				}else{
					if(strtotime($row['scan1']) <= strtotime($row['f1']) && strtotime($row['scan2']) >= strtotime($row['u2'])){
						$proceed = false;
					}
					if($row['scan1'] == '-' || $row['scan2'] == '-'){$proceed = false;}
				}*/		
			}
			if($filter == 'ctime'){
				if($check == 'non'){$proceed = false;}
				/*if($spd == 4){
					if(strtotime($row['scan1']) > strtotime($row['f1']) || strtotime($row['scan2']) < strtotime($row['u1']) || strtotime($row['scan3']) > strtotime($row['f2']) || strtotime($row['scan4']) < strtotime($row['u2'])){
						$proceed = false;
					}
					if($row['scan1'] == '-' || $row['scan2'] == '-' || $row['scan3'] == '-' || $row['scan4'] == '-'){$proceed = false;}
				}else{
					if(strtotime($row['scan1']) > strtotime($row['f1']) || strtotime($row['scan2']) < strtotime($row['u2'])){
						$proceed = false;
					}
					if($row['scan1'] == '-' || $row['scan2'] == '-'){$proceed = false;}
				}*/		
			}
			
			if($proceed){
				$data[$nr][] = '<span class="emp_id '.$check.' '.$locked.' '.$approved.'">'.$row['emp_id'].'</span>';
				$data[$nr][] = '<span class="emp_name">'.$row[$_SESSION['rego']['lang'].'_name'].'</span>';
				$data[$nr][] = '<a class="rowedit edit" data-id="'.$row['id'].'"><i class="fa fa-edit fa-lg"></i></a>';
				
				$ch = ''; 
				if($diff != 0){$ch = 'disabled ';}; 
				if($row['approved'] == 1){$ch = 'checked disabled ';}; 

				$data[$nr][] = '<label><input '.$ch.'type="checkbox" class="dbox checkbox notxt"><span style="z-index:0"></span></label>';
				$data[$nr][] = '<span class="row_date">'.date('D d-m-Y', strtotime($row['date'])).'</span>';
				
				$str = explode('|', $row['plan_hrs']);
				if($row['hd'] == 1){ $row['plan'] .= '&nbsp; <i style="color:#99f" class="fa fa-rebel"></i>';}
				
				$tooltip = '';
				foreach($str as $k=>$v){
					if($k > 0){$tooltip .= $v.' - ';}
				}
				$tooltip = substr($tooltip, 0, -2);


				// GET THE PLAN DEFAULT HOURS FROM LEAVE TIME SETTING TABLE USING PLAN NAME EXAMPLE DWD 

				$sql10= "SELECT * FROM ".$cid."_leave_time_settings WHERE id ='1' ";	
				if($res10 = $dbc->query($sql10))
				{
					if($row10 = $res10->fetch_assoc())
					{
						$shiftplanValue = unserialize($row10['shiftplan']);
					}
				}

				foreach ($shiftplanValue as $key10 => $value10) 
				{
					if($key10 == $row['plan'])
					{
						$startTime = $value10['f1'];
						$endTime = $value10['u2'];

						$tooltip2 = $startTime.'-'.$endTime;
					}

					// echo $row['plan'];

					if($row['plan'] == 'OFF')
					{
						$tooltip3 = '';
					}
					else
					{
						$tooltip3 = $tooltip2;
					}
				}


				// echo '<pre>';
				// print_r($shiftplanValue);
				// echo '</pre>';



				$data[$nr][] = '<div data-toggle="tooltip" data-placement="left" title="'.$tooltip3.'" style="text-align:center">'.$row['plan'].'</div>';
				//if($row['hd'] == 1){ $data[$nr][] = '<i style="color:#99f" class="fa fa-rebel fa-lg"></i>';}
				$data[$nr][] = dateHours($row['planned_hrs']);





				$data[$nr][] = dateHours($row['ot_hrs']); 
				// $data[$nr][] = '-'; 



				
				$data[$nr][] = $row['planned_ot'];
				

				// if shiftschedule setting is scan 2 only show 2 scan if its 4 show 4 scans 


				$data[$nr][] = '<center><img data-toggle="tooltip" data-placement="left" title="'.str_replace('|',' - ',$row['all_scans']).'" style="height:16px; display:block" src="../../hr/images/fingerprint.png" /></center>';



			$sql2_getshiftteam1 = "SELECT * FROM ".$cid."_shiftplans_".$cur_year. " WHERE id= '".$row['shiftteam']."'";

			if($res2_getshifteam1= $dbc->query($sql2_getshiftteam1))
			{
				if($row2_get_shiftteam1 = $res2_getshifteam1->fetch_assoc())
				{

					$ss_dataTeam1 = unserialize($row2_get_shiftteam1['ss_data']);

				}
			}


			

				if($ss_dataTeam1['scan2'] )
				{
					if($row['plan'] == 'NSG')
					{
						if(!empty($row['scan1']) && $row['scan1'] != '-'){$data[$nr][] = date('H:i', strtotime($row['scan2']));}else{$data[$nr][] = '-';}
						if(!empty($row['scan2']) && $row['scan2'] != '-'){$data[$nr][] = date('H:i', strtotime($row['scan1']));}else{$data[$nr][] = '-';}
						if(!empty($row['scan3']) && $row['scan3'] != '-'){$data[$nr][] = $data[$nr][] = '-';}else{$data[$nr][] = '-';}
						if(!empty($row['scan4']) && $row['scan4'] != '-'){$data[$nr][] = $data[$nr][] = '-';}else{$data[$nr][] = '-';}
						
						
					}
					else
					{
						if(!empty($row['scan1']) && $row['scan1'] != '-'){$data[$nr][] = date('H:i', strtotime($row['scan1']));}else{$data[$nr][] = '-';}
						if(!empty($row['scan2']) && $row['scan2'] != '-'){$data[$nr][] = date('H:i', strtotime($row['scan2']));}else{$data[$nr][] = '-';}
						if(!empty($row['scan3']) && $row['scan3'] != '-'){$data[$nr][] = $data[$nr][] = '-';}else{$data[$nr][] = '-';}
						if(!empty($row['scan4']) && $row['scan4'] != '-'){$data[$nr][] = $data[$nr][] = '-';}else{$data[$nr][] = '-';}
						
					}


				}
				else if($ss_dataTeam1['scan4'] )
				{
					if($row['plan'] == 'NSG')
					{
						if(!empty($row['scan1']) && $row['scan1'] != '-'){$data[$nr][] = date('H:i', strtotime($row['scan2']));}else{$data[$nr][] = '-';}
						if(!empty($row['scan2']) && $row['scan2'] != '-'){$data[$nr][] = date('H:i', strtotime($row['scan1']));}else{$data[$nr][] = '-';}
						if(!empty($row['scan3']) && $row['scan3'] != '-'){$data[$nr][] = date('H:i', strtotime($row['scan3']));}else{$data[$nr][] = '-';}
						if(!empty($row['scan4']) && $row['scan4'] != '-'){$data[$nr][] = date('H:i', strtotime($row['scan4']));}else{$data[$nr][] = '-';}
						
						
					}
					else
					{
						if(!empty($row['scan1']) && $row['scan1'] != '-'){$data[$nr][] = date('H:i', strtotime($row['scan1']));}else{$data[$nr][] = '-';}
						if(!empty($row['scan2']) && $row['scan2'] != '-'){$data[$nr][] = date('H:i', strtotime($row['scan2']));}else{$data[$nr][] = '-';}
						if(!empty($row['scan3']) && $row['scan3'] != '-'){$data[$nr][] = date('H:i', strtotime($row['scan3']));}else{$data[$nr][] = '-';}
						if(!empty($row['scan4']) && $row['scan4'] != '-'){$data[$nr][] = date('H:i', strtotime($row['scan4']));}else{$data[$nr][] = '-';}
						
					}

				}
				else
				{
					if(!empty($row['scan1']) && $row['scan1'] != '-'){$data[$nr][] = date('H:i', strtotime($row['scan1']));}else{$data[$nr][] = '-';}
					if(!empty($row['scan2']) && $row['scan2'] != '-'){$data[$nr][] = date('H:i', strtotime($row['scan2']));}else{$data[$nr][] = '-';}
					if(!empty($row['scan3']) && $row['scan3'] != '-'){$data[$nr][] = $data[$nr][] = '-';}else{$data[$nr][] = '-';}
					if(!empty($row['scan4']) && $row['scan4'] != '-'){$data[$nr][] = $data[$nr][] = '-';}else{$data[$nr][] = '-';}
				}


				
			
				/*$data[$nr][] = $row['scan2'];
				$data[$nr][] = $row['scan3'];
				$data[$nr][] = $row['scan4'];*/
				
				$data[$nr][] = dateHours($row['unpaid_late']);
				$data[$nr][] = dateHours($row['unpaid_early']);
				$data[$nr][] = dateHours($row['actual_hrs']);
				$data[$nr][] = dateHours($row['normal_hrs']);
				
				if(!empty($row['ot1'])){
					$data[$nr][] = '<span class="emp_id">'.dateHours($row['ot1']).'</span>';
				}else{
					$data[$nr][] = '-';
				}
				if(!empty($row['ot15'])){
					$data[$nr][] = '<span class="emp_id">'.dateHours($row['ot15']).'</span>';
				}else{
					$data[$nr][] = '-';
				}
				if(!empty($row['ot2'])){
					$data[$nr][] = '<span class="emp_id">'.dateHours($row['ot2']).'</span>';
				}else{
					$data[$nr][] = '-';
				}
				if(!empty($row['ot3'])){
					$data[$nr][] = '<span class="emp_id">'.dateHours($row['ot3']).'</span>';
				}else{
					$data[$nr][] = '-';
				}
				
				if(!empty($row['leave_type'])){
					$tmp = explode('|', $row['leave_type']);
					$str = '';
					foreach($tmp as $k=>$v){
						$str .= '<a href="../leave/index.php?mn=201&id='.$row['emp_id'].'&date='.$row['date'].'" data-toggle="tooltip" title="'.$leave_types[$v][$lang].'">'.$v.'</a>|';
					}
					$str = substr($str,0,-1);
				}else{
					$str = '<a href="../leave/index.php?mn=201&id='.$row['emp_id'].'&add=true""><i class="fa fa-plane fa-lg"></i></a>';
				}
				$data[$nr][] = $str;
				
				if(!empty($row['personal'])){$data[$nr][] = dateHours($row['personal']);}else{$data[$nr][] = '-';}
				
				if(!empty($row['unpaid_leave'])){$data[$nr][] = dateHours($row['unpaid_leave']);}else{$data[$nr][] = '-';}
	
				foreach($compensations as $k=>$v){
					$chk = '';
					if($row['comp'.$k] > 0){$chk = 'checked';}else{$chk = '';}
					$data[$nr][] = '<label><input data-fld="comp'.$k.'" '.$chk.' type="checkbox" class="combox checkbox notxt"><span></span></label>';
				}
				
				$size = strlen($row['comment']);
				$data[$nr][] = '<input class="comment" size="'.$size.'" type="text" value="'.$row['comment'].'">';
				
				$nr ++;
			}
		}
	}
	$xdata['draw'] = 0;
	$xdata['recordsTotal'] = 0;
	$xdata['recordsFiltered'] = 0;
	$xdata['data'] = $data;
	
	// echo $sql;
	//echo $sql;
	//var_dump($xdata); exit;

	// echo '<pre>';
	// print_r($xdata);
	// echo '</pre>';
	// die();







	ob_clean();
	echo json_encode($xdata);
	exit;

