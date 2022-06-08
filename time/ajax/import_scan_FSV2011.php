<?

	if(session_id()==''){session_start();}
	ob_start();
	//$cid = $_SESSION['xhr']['cid'];
	include('../../dbconnect/db_connect.php');
	include(DIR.'files/functions.php');
	include('../functions.php');
	//var_dump($_FILES); exit;




	$period = $_SESSION['rego']['curr_month'].'/'.$_SESSION['rego']['cur_year'];
	$time_settings = getTimeSettings();
	//$fixed_break = $time_settings['fixed_break'];
	$scans = $time_settings['scans'];
	$employees = getEmployeesBySID($cid);
	
	//var_dump($scans); exit;
	$data = array();
	$dir = DIR.$cid.'/uploads/';
	$filename = '?';
	if(!empty($_FILES)) {
		if(strpos($_FILES['timesheet']['type'], 'text/plain') == true ){
			ob_clean();
			echo 'wrong';
			exit;
		}
		$tempFile = $_FILES['timesheet']['tmp_name'];
		$filename = $_FILES['timesheet']['name'];
		$inputFileName =  $dir.$_FILES['timesheet']['name'];

		move_uploaded_file($tempFile,$inputFileName);
	}

		$readfilename = $inputFileName;
		$fp = fopen($readfilename, "r");

		$content = fread($fp, filesize($readfilename));
		$lines = explode("\n", $content);
		fclose($fp);

		unset($lines[0]);




		$resultss = array_filter($lines, function($value){
	       return trim($value);
	    });


		$linesnew = array_values($resultss);

		$iOne = array_combine(range(1, count($linesnew)), array_values($linesnew));


		

		


		foreach ($iOne as $key_1 => $value_1) {
			# code...
			$linesArray = explode(' ', $value_1);


			$result2 = array_filter($linesArray, function($value){
		       return trim($value);
		    });


			$linesnew2 = array_values($result2);

			$iOne2 = array_combine(range(1, count($linesnew2)), array_values($linesnew2));




			// make 3 cases 
			// for first name - middle name - last name  - count 10
			// for firstname - last name - count 9
			// for no name - count -8 	

			$linesCount  = count($iOne2);

			if($linesCount == '8')
			{
				$employeeId = $iOne2[4];
				$scanInDate = $iOne2[5];
				// $scanInTime = $iOne2[6];

				if($iOne2[6])
				{
					$explodeScaninTime  = explode(':', $iOne2[6]);
					$scanInTime = $explodeScaninTime[0].':'.$explodeScaninTime[1];

				}
				else
				{
					$scanInTime = '';
				}

			}
			elseif ($linesCount == '9')
			{

				$employeeId = $iOne2[5];
				$scanInDate = $iOne2[6];

				if($iOne2[7])
				{
					$explodeScaninTime  = explode(':', $iOne2[7]);
					$scanInTime = $explodeScaninTime[0].':'.$explodeScaninTime[1];

				}
				else
				{
					$scanInTime = '';
				}

			}
			elseif ($linesCount == '10') {

				$employeeId = $iOne2[6];
				$scanInDate = $iOne2[7];
				// $scanInTime = $iOne2[8];

				if($iOne2[8])
				{
					$explodeScaninTime  = explode(':', $iOne2[8]);
					$scanInTime = $explodeScaninTime[0].':'.$explodeScaninTime[1];

				}
				else
				{
					$scanInTime = '';
				}


			}


	


			

			$scanArrayVal[] = array( 'employee_id' =>$employeeId , 'scan_in_date' =>$scanInDate , 'scan_in_time' => $scanInTime);

		}


		// 	echo '<pre>';
		// 	print_r($scanArrayVal);
		// 	echo '</pre>';


		// die();


		foreach ($scanArrayVal as $key => $value) {
			
			$newarray[$value['employee_id']][$value['scan_in_date']][] = $value['scan_in_time'];
		}


		// echo '<pre>';
		// print_r($newarray);
		// echo '</pre>';

		// die();


		$count = 1 ;

		foreach ($newarray as $key_2 => $value_2) {
				$k = $count++;

			foreach ($value_2 as $key_3 => $value_3) {
			// IF EMPLOYEE EXISTS GET EMPLOYEE DETAILS ON THE BAIS OF SCAN ID 



			$sql_get_employee = "SELECT * FROM ".$cid."_employees WHERE sid= '".$key_2."' ";
						
			if($res_get_employee = $dbc->query($sql_get_employee))
			{
				if($row_get_employee = $res_get_employee->fetch_assoc())
				{
					$employeeIdVal  = $row_get_employee['emp_id'];
					$employee_name  = $row_get_employee['en_name'];
					$teamName 		= $row_get_employee['teams'];
					$empNameTh      = $row_get_employee['th_name'];

				}
				else
				{
					$employeeIdVal = '';
					$employee_name = '';
					$empNameTh 	   = '';
					$empNameTh     = '';


				}
			}


			$sql9 = "SELECT * FROM ".$cid."_shiftplans_".$cur_year. " WHERE id= '".$teamName."'";

			if($res9 = $dbc->query($sql9))
			{
				if($row9 = $res9->fetch_assoc())
				{
					if($row9['ss_data'] != '')
					{
						$ss_data = unserialize($row9['ss_data']);
					}
					else
					{
						$ss_data = '';
					}

					if($row9['cycle_details'] != '')
					{
						$cyc_data = unserialize($row9['cycle_details']);
					}
					else
					{
						$cyc_data = '';
					}
				}
			}

			$sql10 = "SELECT * FROM ".$cid."_leave_time_settings WHERE id= '1'";

			if($res10 = $dbc->query($sql10))
			{
				if($row10 = $res10->fetch_assoc())
				{
					$workingHrs = unserialize($row10['shiftplan']);
				}
			}	


			// FETCH SCAN START DATE 

			// 23-03-2021 4:00 PM - 23-03-2021 11:00 PM 
			// if scan out is on different date
			// scan in  23-03-2021 4:00 PM
			// scan out 24-04-2021 12:10 AM 
			// scan out should go with scan in shiftplan as it is closer to the the shiftplan then scan out 
			// find difference between scan in end date time and scan out start date and time  named diffenece 1
			// find differnece between scan out date and scan out date time and scan out plan date and time named differnece 2
			// differnece 1 > differnece 2 then diff 2 is used else if differnece 2 > difference 1 then diff 1 is used to fetch the shiftplan of that date
	


			$var = $key_3;
			$date = str_replace('/', '-', $var);
			$datein = date('Y-m-d', strtotime($date));


			$dateScanVal =$datein;
			$emp_Name = $employee_name;
			$scanIn = $value_3[0];
			$scanOut = $value_3[1];
			$filenamess = $filename;
			$scanID = $key_2;
			$empId = $employeeIdVal;
			$dateScanValOut = $datein;


			// if($scanIn == '' || $scanOut == '' || $scanID == '' || $empId == '' )
			if(($scanOut == '' && $scanIn == '') || $scanID == '' )
			{
				$statusVal = '0';
			}
			else
			{
				$statusVal = '1';
			}			

			// if($scanID == '' ||  $empId == '' || ($scanOut == '' && $scanIn == '') )
			if($scanID == ''  || ($scanOut == '' && $scanIn == '') )
			{
				$statusVals = '0';
			}
			else
			{
				$statusVals = '1';
			}


			$month = date('F', strtotime($datein));
			$date = date('d', strtotime($datein));	


			// FETCH SHIFT PLAN BASED ON START DATE OF SHIFT PLAN
			if($cyc_data != '')
			{
				$plannedShift = $cyc_data[$month][$date]['s1'];
			}
			else
			{
				$plannedShift = '';
			}


			if($plannedShift != '')
			{

				// check if shiftplan exists for that month in table otherwise insert blank  

				$plannedHrs =  $workingHrs[$plannedShift]['hours'];
			}
			else
			{
				$plannedHrs = '';	
			}


				// CALCULATE DIFFERNECE OF TIME BETWEEN SCANOUT DATES 

			$scanEndDate = date('Y-m-d', strtotime($datein)); 
			$scanEndTime = date('H:i', strtotime($scanOut));

			$explodeScanEndTime = explode(':', $scanEndTime);

			
			if($explodeScanEndTime[0] <=11 ||  $explodeScanEndTime[0] == 00)
			{
				$scanEndDT = date('Y-m-d h:i a', strtotime("$scanEndDate $scanEndTime"));
			}
			else
			{
				$scanEndDT = date('Y-m-d H:i', strtotime("$scanEndDate $scanEndTime"));
			}

			$startDiffTime = '00:00';
			$diff1StartDate  = date('Y-m-d H:i', strtotime("$scanEndDate $startDiffTime"));


			$monthAEnd = date('F', strtotime($scanEndDate));
			$dateAEnd  = date('d', strtotime($scanEndDate));


			if($cyc_data != '')
			{
				$AplanShift = $cyc_data[$monthAEnd][$dateAEnd]['s1'];
				$actualSDateTime = $workingHrs[$AplanShift]['u2'];
				$actualSDateTimeOFnewDate = $workingHrs[$AplanShift]['f1'];
			}
			else
			{
				$AplanShift = '';
				$actualSDateTime = '';
				$actualSDateTimeOFnewDate = '';
			}

			
			$explodeTIme = explode(':', $actualSDateTimeOFnewDate);
			if($explodeTIme[0] <=12)
			{
				if($scanEndDate != '' && $actualSDateTimeOFnewDate != '')
				{
					$ActualscanEndDT = date('Y-m-d H:i a', strtotime("$scanEndDate $actualSDateTimeOFnewDate"));
				}
				else
				{
					$ActualscanEndDT = '';
				}
			}
			else
			{
				if($scanEndDate != '' && $actualSDateTimeOFnewDate != '')
				{
					$ActualscanEndDT = date('Y-m-d H:i ', strtotime("$scanEndDate $actualSDateTimeOFnewDate"));
				}
				else
				{
					$ActualscanEndDT = '';
				}
			}
			


			$date1 = $diff1StartDate;
			$date2 = $scanEndDT;
			$date3 = $scanEndDT;
			$date4 = $ActualscanEndDT;

			if(($datein) != ($datein))
			{
				$first_date1 = new DateTime($date1);
				$second_date2 = new DateTime($date2);
				$difference1 = $first_date1->diff($second_date2);

				$first_date3 = new DateTime($date3);
				$second_date4 = new DateTime($date4);
				$difference2 = $first_date3->diff($second_date4);


				$diff1Time = $difference1->h.':'.$difference1->i;
				$diff2Time = $difference2->h.':'.$difference2->i;

				$dTime1 = strtotime($diff1Time);
				$dTime2 = strtotime($diff2Time);

				if($dTime1 < $dTime2)
				{
					// set v[0] as shift plan date 
					$shiftPlanDate = $datein;
				}
				else if($dTime2 < $dTime1)
				{
					// set v[1] as shift plan date 
					$shiftPlanDate = $datein;
				}
			}
			else
			{
				$shiftPlanDate = $datein;
			}



			// check if the monthly plan for scan date is present in monthly shiftplan table on the basis of month and emp id 
			if($datein)
			{
				$monthArray  = explode('-',$datein); // get month from hgere  
				$monthvalue = $monthArray[1];

				$scanmonth = ltrim($monthvalue, "0"); 
			}




			$data[$k]['shiftteam'] = $teamName;
			$data[$k]['en_name'] = $emp_Name;
			$data[$k]['th_name'] = $empNameTh;
			$data[$k]['filename'] = $filename;
			$data[$k]['id'] = $empId;
			$data[$k]['name'] = '';
			$date = date('d-m-Y', strtotime(str_replace('/','-',$shiftPlanDate)));


			$allscans = $value_3 ;

			unset($allscans[0]);



			if(!isset($data[$k]['time'][$date]) && !empty($scanIn))
			{
				$data[$k]['time'][$date] = $scanIn;
				if(!empty($scanOut))
				{	
					foreach ($allscans as $key14 => $value14) {
						$data[$k]['time'][$date] .= '|'.$value14;
					}
				}
			}


			$data[$k]['dateScanVal'] = $datein;
			$data[$k]['dateScanValOut'] = $datein;
			$data[$k]['scanIn'] = $scanIn;
			$data[$k]['scanOut'] = $scanOut;
			$data[$k]['scanID'] = $scanID;
			$data[$k]['empId'] = $empId;
			$data[$k]['emp_Name'] = $emp_Name;
			$data[$k]['statusVal'] = $statusVals;




			$sql9 = "SELECT * FROM ".$cid."_monthly_shiftplans_".$cur_year." WHERE month = '".$scanmonth."' AND emp_id = '".$empId."'";
			if($res9 = $dbc->query($sql9))
			{
				if($row9 = $res9->fetch_assoc())
				{
					// $finalplannedShift = $plannedShift  ;
					$finalplannedShift = ''  ;
					// $data[$k]['planned_hrs'] = $plannedHrs;
					$data[$k]['planned_hrs'] = '';

					// $data[$k]['plan'] = $cyc_data[$shiftmonth][$shiftdate]['s1'];
					$data[$k]['plan'] = '';
				}
				else
				{
					$finalplannedShift = ''  ;
					$data[$k]['planned_hrs'] = '';
					$data[$k]['plan'] = '';
				}
			}



				unset($value_3[0]);
				unset($value_3[1]);
				$implodeScans= implode('|', $value_3);

				$sql1 = "INSERT INTO ".$cid."_scandata (datescan, emp_name,scan_in, scan_out,filename,status,scan_id,emp_id,linkedPlan,timescan,datescanout) VALUES ";
				$sql1 .= "('".$dbc->real_escape_string($datein)."', ";
				$sql1 .= "'".$dbc->real_escape_string($emp_Name)."', ";
				$sql1 .= "'".$dbc->real_escape_string($scanIn)."', ";
				$sql1 .= "'".$dbc->real_escape_string($scanOut)."', ";
				$sql1 .= "'".$dbc->real_escape_string($filenamess)."', ";
				$sql1 .= "'".$dbc->real_escape_string($statusVal)."', ";
				$sql1 .= "'".$dbc->real_escape_string($scanID)."', ";
				$sql1 .= "'".$dbc->real_escape_string($empId)."', ";
				$sql1 .= "'".$dbc->real_escape_string($finalplannedShift)."', ";
				$sql1 .= "'".$dbc->real_escape_string($implodeScans)."', ";
				$sql1 .= "'".$dbc->real_escape_string($datein)."')";

				$res1 = $dbc->query($sql1);



		}



		}



		$scan_system = $_POST['scan_system'];

		$in_out = 'Yes';


		// echo '<pre>';
		// print_r($data);
		// echo '</pre>';

		// die();

		$sql = "INSERT INTO ".$cid."_scanfiles (date, period, content, filename, import, status,in_out,scansystem) VALUES ";
			$sql .= "('".$dbc->real_escape_string(date('Y-m-d'))."', ";
			$sql .= "'".$dbc->real_escape_string($period)."', ";
			$sql .= "'".$dbc->real_escape_string(serialize($data))."', ";
			$sql .= "'".$dbc->real_escape_string($filename)."', ";
			$sql .= "'".$dbc->real_escape_string(1)."', ";
			$sql .= "'".$dbc->real_escape_string(0)."', ";
			$sql .= "'".$dbc->real_escape_string($in_out)."', ";
			$sql .= "'".$dbc->real_escape_string($scan_system)."')";

		
		ob_clean();
		if($res = $dbc->query($sql))
		{
			echo 'success';
		}
		else
		{
			echo mysqli_error($dbc);
		}
		exit;
	
?>
