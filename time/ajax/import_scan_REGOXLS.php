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

	$dir = DIR.$cid.'/uploads/';
	$filename = '?';
	if(!empty($_FILES)) {
		if(strpos($_FILES['timesheet']['type'], 'ms-excel') == false && strpos($_FILES['timesheet']['type'], 'spreadsheetml') == false){
			ob_clean();
			echo 'wrong';
			exit;
		}
		$tempFile = $_FILES['timesheet']['tmp_name'];
		$filename = $_FILES['timesheet']['name'];
		$inputFileName =  $dir.$_FILES['timesheet']['name'];
		move_uploaded_file($tempFile,$inputFileName);
	}
	//$inputFileName = '../timesheet/weladee/attendance-07-2020.xls';
	//$inputFileName = '../timesheet/september.xlsx';

	$sheetData = array();
	//$inputFileName = $targetFile; 


	require_once '../../PhpSpreadsheet/vendor/autoload.php';
	use PhpOffice\PhpSpreadsheet\IOFactory;
	
	$inputFileType = IOFactory::identify($inputFileName);
	$reader = IOFactory::createReader($inputFileType);
	$reader->setReadDataOnly(true); 
	$reader->setReadEmptyCells(false);
	$spreadsheet = $reader->load($inputFileName);
	
	$sheetData = $spreadsheet->getActiveSheet()->toArray('', false, false, false);
	//$objPHPExcel = PHPExcel_IOFactory::load($inputFileName);
	// 1. Value returned in the array entry if a cell doesn't exist
	// 2. Should formulas be calculated ?
	// 3. Should formatting be applied to cell values ?
	// 4. False - Return a simple array of rows and columns indexed by number counting from zero 
	// 4. True - Return rows and columns indexed by their actual row and column IDs

	//var_dump($sheetData[1]);
	unset($sheetData[0]);
	//exit;
	//var_dump($sheetData); exit;


	// $sheetData = array();



	foreach ($sheetData as $key2 => $value2) {


	
		$timeIn = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($value2[2]);
		$timeOut = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($value2[3]);


		$hyphenDate1   = $value2[0];
		$hyphenDate2   = $value2[1];
		// check if date contains hyphen if contains hyphen then convert else pass to to date object 

		$findme1  = '-';
		$pos1 = strpos($hyphenDate1, $findme1);	


		$findme2  = '-';
		$pos2 = strpos($hyphenDate2, $findme2);	

		if($pos1 != '')
		{
	
			$date_time =  $hyphenDate1.' '."00:00:00";
			$date_time_plus_one = strtotime($date_time . ' +1 day');
			$str_date = strtotime(date('Y-m-d', $date_time_plus_one));
			$date000 = intval(25569 + $str_date / 86400);

			$excelDate = $date000;
			$miliseconds = ($excelDate - (25567 + 2)) * 86400 * 1000;
			$seconds = $miliseconds / 1000;
			$date0 = date("d-m-Y", $seconds);

		}
		else
		{
			$excelDate = $hyphenDate1;
			$miliseconds = ($excelDate - (25567 + 2)) * 86400 * 1000;
			$seconds = $miliseconds / 1000;
			$date0 = date("d-m-Y", $seconds);
		}


		if($pos2 != '')
		{

			$date_time1 =  $hyphenDate2.' '."00:00:00";
			$date_time_plus_one1 = strtotime($date_time1 . ' +1 day');
			$str_date1 = strtotime(date('Y-m-d', $date_time_plus_one1));
			$date111 = intval(25569 + $str_date1 / 86400);

			$excelDate1 = $date111;
			$miliseconds1 = ($excelDate1 - (25567 + 2)) * 86400 * 1000;
			$seconds1 = $miliseconds1 / 1000;
			$date1 = date("d-m-Y", $seconds1);


		}
		else
		{
			$excelDate1 = $hyphenDate2;
			$miliseconds1 = ($excelDate1 - (25567 + 2)) * 86400 * 1000;
			$seconds1 = $miliseconds1 / 1000;
			$date1 = date("d-m-Y", $seconds1);
		}





	

		// $timeIn1 =  $timeIn->format('Y-m-d H:i:s');
		// $onlyTimeIn1 = explode(' ', $timeIn1);

		// $timeOut2 =  $timeOut->format('Y-m-d H:i:s');
		// $onlyTimeOut2 = explode(' ', $timeOut2);


		// $sheetData[$key2][2] = $onlyTimeIn1[1];
		// $sheetData[$key2][3] = $onlyTimeOut2[1];
		// $sheetData[$key2][0] = $date0;
		// $sheetData[$key2][1] = $date1;


		
		$timeIn1 =  $timeIn->format('Y-m-d H:i:s');
		$onlyTimeIn1 = explode(' ', $timeIn1);

		// echo $countscanin = count($onlyTimeIn1);
		$onlyTimeIn11 = explode(':', $onlyTimeIn1[1]);

		$onlyTimeIn111 = $onlyTimeIn11[0].':'.$onlyTimeIn11[1];


		$timeOut2 =  $timeOut->format('Y-m-d H:i:s');
		$onlyTimeOut2 = explode(' ', $timeOut2);

		$onlyTimeOut22 = explode(':', $onlyTimeOut2[1]);
		$onlyTimeIn222 = $onlyTimeOut22[0].':'.$onlyTimeOut22[1];


		$sheetData[$key2][2] = $onlyTimeIn111;
		$sheetData[$key2][3] = $onlyTimeIn222;
		$sheetData[$key2][0] = $date0;
		$sheetData[$key2][1] = $date1;



	}


	// echo '<pre>';
	// print_r($sheetData);
	// echo '</pre>';
	
	// die();


	$sql2 = "SELECT filename from  ".$cid."_scanfiles WHERE filename= '".$filename."'";
	$res2 = $dbc->query($sql2);
	if ($res2->num_rows > 0) 
	{
		// delete old entries and then enter new entries 
		if($_POST['hidden1Value'] == '1')
		{
			// delete old entries 
			$deleteSql1 = "DELETE FROM ".$cid."_scanfiles WHERE filename = '".$filename."'";
			$deleteSql2 = "DELETE FROM ".$cid."_scandata WHERE filename = '".$filename."'";
			$deleteSql3 = "DELETE FROM ".$cid."_metascandata WHERE filename = '".$filename."'";
			$deleteSql4 = "DELETE FROM ".$cid."_attendance WHERE filename = '".$filename."'";
			$res3 = $dbc->query($deleteSql1);
			$res4 = $dbc->query($deleteSql2);
			$res5 = $dbc->query($deleteSql3);
			$res6 = $dbc->query($deleteSql4);


			// newinsert 
			$data = array();
			foreach($sheetData as $k=>$v){

				// FETCH TEAM NAME OF EMPLOYEE USING EMPLOYEE ID 
				$sql8 = "SELECT * FROM ".$cid."_employees  WHERE emp_id= '".$v[7]."'";
				if($res8 = $dbc->query($sql8))
				{
					if($row8 = $res8->fetch_assoc())
					{
						$teamName = $row8['teams'];
						$empNameTh =$row8['th_name'];
						$empNameEn =$row8['en_name'];
					}
					else
					{
						$teamName = '';
						$empNameTh ='';
						$empNameEn ='';
					}
				}

				// FETCH TEAM SHIFT SCHEDULE USING TEAM NAME AS ID 

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
							$ss_data ='';
						}

						if($row9['cycle_details'] != '')
						{
							// fetch only if data is in montlhy shiftplan 
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

				$scanStartDate = date('Y-m-d', strtotime($v[0])); 

				$month = date('F', strtotime($scanStartDate));
				$date = date('d', strtotime($scanStartDate));
				// FETCH SHIFT PLAN BASED ON START DATE OF SHIFT PLAN
				
				// if scan out is on different date

				// scan in  -23-03-2021 8:00 PM
				// scan out -24-04-2021 12:10 AM 
				// scan out should go with scan in shiftplan as it is closer to the the shiftplan then scan out 
				// find difference between scan in end date time and scan out start date and time  named diffenece 1
				// find differnece between scan out date and scan out date time and scan out plan date and time named differnece 2
				// differnece 1 > differnece 2 then diff 2 is used else if differnece 2 > difference 1 then diff 1 is used to fetch the shiftplan of that date

				if($cyc_data != '') 
				{
					$data[$k]['plan'] = $cyc_data[$month][$date]['s1'];
				}
				else
				{
					$data[$k]['plan'] = '';
				}

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
					$plannedHrs =  '';
				}

				// CALCULATE DIFFERNECE OF TIME BETWEEN SCANOUT DATES 

			$scanEndDate = date('Y-m-d', strtotime($v[1])); 
			$scanEndTime = date('H:i', strtotime($v[3]));

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
				if($scanEndDate != ''  && $actualSDateTimeOFnewDate !='')
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

			if(($v[0]) != ($v[1]))
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
					$shiftPlanDate = $v[0];
				}
				else if($dTime2 < $dTime1)
				{
					// set v[1] as shift plan date 
					$shiftPlanDate = $v[1];

				}

			}
			else
			{
				$shiftPlanDate = $v[0];
			}

			
				// $data[$k]['planned_hrs'] = $plannedHrs;
				$data[$k]['filename'] = $filename;

				$data[$k]['shiftteam'] = $teamName;
				$data[$k]['en_name'] = $empNameEn;
				$data[$k]['th_name'] = $empNameTh;



				
				$data[$k]['id'] = $v[7];
				$data[$k]['name'] = $employees[$v[5]][$lang.'_name'];
				// $date = date('d-m-Y', strtotime(str_replace('/','-',$v[0])));

				$date = date('d-m-Y', strtotime(str_replace('/','-',$shiftPlanDate)));

				$shiftmonth = date('F', strtotime($date));
				$shiftdate = date('d', strtotime($date));
				// $data[$k]['plan'] = $cyc_data[$shiftmonth][$shiftdate]['s1'];


				if(!isset($data[$k]['time'][$date]) && !empty($v[1]))
				{
					$data[$k]['time'][$date] = $v[2];
					if(!empty($v[2]))
					{
						$data[$k]['time'][$date] .= '|'.$v[3];
					}
				}

				$newDate = date('Y-m-d',strtotime($date));

				$scan_in 	= $v[1];
				$scan_out   = $v[2];
				$status = '1';

				$dateScanVal = date('Y-m-d', strtotime($v[0])); // start date 
				$dateScanValOut = date('Y-m-d', strtotime($v[1])); // end date 
				$scanIn = $v[2];  
				$scanOut = $v[3];  
				$scanID = $v[6];  
				$empId = $v[7];  
				$emp_Name = $v[8];

				if($scanIn == '' || $scanOut == '' || $scanID == '' || $empId == '' )
				{
					$statusVal = '0';
				}
				else
				{
					$statusVal = '1';
				}

				$data[$k]['dateScanVal'] = $dateScanVal;
				$data[$k]['dateScanValOut'] = $dateScanValOut;
				$data[$k]['scanIn'] = $scanIn;
				$data[$k]['scanOut'] = $scanOut;
				$data[$k]['scanID'] = $scanID;
				$data[$k]['empId'] = $empId;
				$data[$k]['emp_Name'] = $emp_Name;
				$data[$k]['statusVal'] = $statusVal;



							// check if the monthly plan for scan date is present in monthly shiftplan table on the basis of month and emp id 
			if($dateScanVal)
			{
				$monthArray  = explode('-',$dateScanVal); // get month from hgere  
				$monthvalue = $monthArray[1];

				$scanmonth = ltrim($monthvalue, "0"); 
			}

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




				

				// $sql1 = "INSERT INTO ".$cid."_scandata (datescan,scan_in, scan_out, status) VALUES ";
				$sql1 = "INSERT INTO ".$cid."_scandata (datescan, emp_name,scan_in, scan_out,filename,status,scan_id,emp_id,linkedPlan,datescanout) VALUES ";
				$sql1 .= "('".$dbc->real_escape_string($dateScanVal)."', ";
				$sql1 .= "'".$dbc->real_escape_string($emp_Name)."', ";
				$sql1 .= "'".$dbc->real_escape_string($scanIn)."', ";
				$sql1 .= "'".$dbc->real_escape_string($scanOut)."', ";
				$sql1 .= "'".$dbc->real_escape_string($filename)."', ";
				$sql1 .= "'".$dbc->real_escape_string($statusVal)."', ";
				$sql1 .= "'".$dbc->real_escape_string($scanID)."', ";
				$sql1 .= "'".$dbc->real_escape_string($empId)."', ";
				$sql1 .= "'".$dbc->real_escape_string($finalplannedShift)."', ";
				$sql1 .= "'".$dbc->real_escape_string($dateScanValOut)."')";

				$res1 = $dbc->query($sql1);

			}

			$scan_system = $_POST['scan_system'];
			$in_out = 'Yes';

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
				echo 'newInsert';
			}
			else
			{
				echo mysqli_error($dbc);
			}
			exit;


		}
		else
		{
			//duplicate
			echo 'duplicate';

		}
	}
	else
	{
		// insert entries 
		

		$data = array();

		foreach($sheetData as $k=>$v){

			// FETCH TEAM NAME OF EMPLOYEE USING EMPLOYEE ID 
			$sql8 = "SELECT * FROM ".$cid."_employees  WHERE emp_id= '".$v[7]."'";
			if($res8 = $dbc->query($sql8))
			{
				if($row8 = $res8->fetch_assoc())
				{
					$teamName = $row8['teams'];
					$empNameTh =$row8['th_name'];
					$empNameEn =$row8['en_name'];
				}
				else
				{
					$teamName = '';
					$empNameTh ='';
					$empNameEn ='';
				}
			}

			// FETCH TEAM SHIFT SCHEDULE USING TEAM NAME AS ID 

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

			// echo '<pre>';
			// print_r($cyc_data[$month][$date]);
			// echo '</pre>';


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
	
			$scanStartDate = date('Y-m-d', strtotime($v[0])); 

			// $dateArray = explode('-', $scanStartDate);

			// $ltrimDate = ltrim($dateArray[1], '0');
			// $ltrimDay = ltrim($dateArray[2], '0');






			// $sql11 = "SELECT * FROM ".$cid."_monthly_shiftplans_".$cur_year." WHERE emp_id = '".$v[7]."' AND shiftteam_name = '".$teamName."' AND month = '".$ltrimDate."'";

			// if($res11 = $dbc->query($sql11))
			// {
			// 	if($row11 = $res11->fetch_assoc())
			// 	{

			// 		$rowValue = 'D'.$ltrimDay;
			// 		$newplannedShift = $row11[$rowValue];
			// 	}
			// }

			// echo $newplannedShift;





			$month = date('F', strtotime($scanStartDate));
			$date = date('d', strtotime($scanStartDate));

			// echo '<pre>';
			// print_r($cyc_data[$month][$date]);
			// echo '</pre>';


			// FETCH SHIFT PLAN BASED ON START DATE OF SHIFT PLAN
			if($cyc_data != '')
			{
				$plannedShift = $cyc_data[$month][$date]['s1'];
			}
			else
			{
				$plannedShift = '';
			}
			// $data[$k]['plan'] = $cyc_data[$month][$date]['s1'];

			// echo $plannedShift;


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

			$scanEndDate = date('Y-m-d', strtotime($v[1])); 
			$scanEndTime = date('H:i', strtotime($v[3]));

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

			if(($v[0]) != ($v[1]))
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
					$shiftPlanDate = $v[0];
				}
				else if($dTime2 < $dTime1)
				{
					// set v[1] as shift plan date 
					$shiftPlanDate = $v[1];
				}
			}
			else
			{
				$shiftPlanDate = $v[0];
			}

			// if exists in monthly shiftplan 
		
			

			$data[$k]['shiftteam'] = $teamName;
			$data[$k]['en_name'] = $empNameEn;
			$data[$k]['th_name'] = $empNameTh;
			$data[$k]['filename'] = $filename;

			$data[$k]['id'] = $v[7];
			// $data[$v[5]]['id'] = $employees[$v[5]]['emp_id'];
			$data[$k]['name'] = $employees[$v[5]][$lang.'_name'];
			$date = date('d-m-Y', strtotime(str_replace('/','-',$shiftPlanDate)));

			$shiftmonth = date('F', strtotime($date));
			$shiftdate = date('d', strtotime($date));


			// $data[$k]['plan'] = $cyc_data[$shiftmonth][$shiftdate]['s1'];

			// $date = date('d-m-Y', strtotime(str_replace('/','-',$v[0])));

			if(!isset($data[$k]['time'][$date]) && !empty($v[1]))
			{
				$data[$k]['time'][$date] = $v[2];
				if(!empty($v[2]))
				{
					$data[$k]['time'][$date] .= '|'.$v[3];
				}
			}

			$newDate = date('Y-m-d',strtotime($date));

			$scan_in 	= $v[1];
			$scan_out   = $v[2];
			$status = '1';

			$dateScanVal = date('Y-m-d', strtotime($v[0])); // start date 
			$dateScanValOut = date('Y-m-d', strtotime($v[1])); // end date 
			$scanIn = $v[2];  
			$scanOut = $v[3];  
			$scanID = $v[6];  
			$empId = $v[7];  
			$emp_Name = $v[8];

			if($scanIn == '' || $scanOut == '' || $scanID == '' || $empId == '' )
			{
				$statusVal = '0';
			}
			else
			{
				$statusVal = '1';
			}

			$data[$k]['dateScanVal'] = $dateScanVal;
			$data[$k]['dateScanValOut'] = $dateScanValOut;
			$data[$k]['scanIn'] = $scanIn;
			$data[$k]['scanOut'] = $scanOut;
			$data[$k]['scanID'] = $scanID;
			$data[$k]['empId'] = $empId;
			$data[$k]['emp_Name'] = $emp_Name;
			$data[$k]['statusVal'] = $statusVal;

			// insert linked plan name into scandata 



			// check if the monthly plan for scan date is present in monthly shiftplan table on the basis of month and emp id 
			if($dateScanVal)
			{
				$monthArray  = explode('-',$dateScanVal); // get month from hgere  
				$monthvalue = $monthArray[1];

				$scanmonth = ltrim($monthvalue, "0"); 
			}

			$sql9 = "SELECT * FROM ".$cid."_monthly_shiftplans_".$cur_year." WHERE month = '".$scanmonth."' AND emp_id = '".$empId."'";
			if($res9 = $dbc->query($sql9))
			{
				if($row9 = $res9->fetch_assoc())
				{
					// $finalplannedShift = $plannedShift  ;
					$finalplannedShift = '' ;
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
			
			// echo '<pre>';
			// print_r($data);
			// echo '</pre>';

			// die();


			// $sql1 = "INSERT INTO ".$cid."_scandata (datescan,scan_in, scan_out, status) VALUES ";
			$sql1 = "INSERT INTO ".$cid."_scandata (datescan, emp_name,scan_in, scan_out,filename,status,scan_id,emp_id,linkedPlan,datescanout) VALUES ";
			$sql1 .= "('".$dbc->real_escape_string($dateScanVal)."', ";
			$sql1 .= "'".$dbc->real_escape_string($emp_Name)."', ";
			$sql1 .= "'".$dbc->real_escape_string($scanIn)."', ";
			$sql1 .= "'".$dbc->real_escape_string($scanOut)."', ";
			$sql1 .= "'".$dbc->real_escape_string($filename)."', ";
			$sql1 .= "'".$dbc->real_escape_string($statusVal)."', ";
			$sql1 .= "'".$dbc->real_escape_string($scanID)."', ";
			$sql1 .= "'".$dbc->real_escape_string($empId)."', ";
			$sql1 .= "'".$dbc->real_escape_string($finalplannedShift)."', ";
			$sql1 .= "'".$dbc->real_escape_string($dateScanValOut)."')";

			$res1 = $dbc->query($sql1);

		}

		// echo '<pre>';
		// print_r($data);
		// echo '</pre>';
		// die();

		$scan_system = $_POST['scan_system'];
		$in_out = 'Yes';

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
	}
		
