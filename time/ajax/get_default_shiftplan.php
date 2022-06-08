<?php

	if(session_id()==''){session_start();}
	ob_start();
	include('../../dbconnect/db_connect.php');
	include(DIR.'files/functions.php');



	//var_dump($_REQUEST); //exit;
	
	$cols = date('t', strtotime($_SESSION['rego']['cur_year'].'-'.$_REQUEST['month'].'-01'));
	//var_dump($cols);
	$emps = getTimeEmployeesCOpy();
	//$holidays = getHolidaysDates();
	//var_dump($emps); exit;
	// echo ' <pre>';
	// print_r($emps);
	// echo ' </pre>';
	// die();


	$nr = 0;

	// die();
	//var_dump($v['shiftplan']);
	foreach($emps as $k=>$v) {
		// var_dump($emps);
		$dates = array();
		$res = $dbc->query("SELECT dates,cycle_details FROM ".$cid."_shiftplans_".$cur_year." WHERE id = '".$v['teams']."'");
		if(!mysqli_error($dbc)){
			if($row = $res->fetch_assoc()){
				$dates = unserialize($row['dates']);
				$cycle_details = $row['cycle_details'];
			}
		}


		



		//var_dump($dates);
		$data[$nr]['id'] = $k.'-'.$_REQUEST['month'];
		$data[$nr]['sid'] = $v['sid'];
		$data[$nr]['month'] = $_REQUEST['month'];
		$data[$nr]['emp_id'] = $k;
		$data[$nr]['en_name'] = $v['en_name'];
		$data[$nr]['th_name'] = $v['th_name'];
		$data[$nr]['time_reg'] = $v['time_reg'];
		$data[$nr]['shiftteam_name'] = $v['teams'];
		$data[$nr]['cycle_details'] = $cycle_details;

		




		if(!empty($v['shiftplan'])){
			$data[$nr]['shiftteam'] = $v['shiftplan'];
		}else{
			$data[$nr]['shiftteam'] = 'DT';
		}
		for($i=1;$i<=$cols;$i++){
			$str = strtotime($_SESSION['rego']['cur_year'].'-'.$_REQUEST['month'].'-'.$i);

			if($_REQUEST['month'] == '1')
			{
				$currentMonth  = 'January';
			}
			else if($_REQUEST['month'] == '2')
			{
				$currentMonth  = 'February';
			}
			else if($_REQUEST['month'] == '3')
			{
				$currentMonth  = 'March';
			}			
			else if($_REQUEST['month'] == '4')
			{
				$currentMonth  = 'April';
			}			
			else if($_REQUEST['month'] == '5')
			{
				$currentMonth  = 'May';
			}			
			else if($_REQUEST['month'] == '6')
			{
				$currentMonth  = 'June';
			}			
			else if($_REQUEST['month'] == '7')
			{
				$currentMonth  = 'July';
			}			
			else if($_REQUEST['month'] == '8')
			{
				$currentMonth  = 'August';
			}			
			else if($_REQUEST['month'] == '9')
			{
				$currentMonth  = 'September';
			}			
			else if($_REQUEST['month'] == '10')
			{
				$currentMonth  = 'October';
			}			
			else if($_REQUEST['month'] == '11')
			{
				$currentMonth  = 'November';
			}			
			else if($_REQUEST['month'] == '12')
			{
				$currentMonth  = 'December';
			}





			$dayLength = strlen($i);
			if($dayLength == '1')
			{
				$data[$nr]['D'.$i] = $dates[$currentMonth]['0'.$i]['s1'];
			}
			else
			{
				$data[$nr]['D'.$i] = $dates[$currentMonth][$i]['s1'];
			}
			//if(isset($holidays[$str])){
				//$data[$nr]['D'.$i] = 'HD';
			//}
		}
		$nr ++;


			// echo '<pre>';
			// print_r($dates);
			// echo '</pre>';
	}


	// 	echo '<pre>';
	// print_r($data);
	// echo '</pre>';

	// die();

	// exit();
	//var_dump($dates);
	// var_dump($data); exit;


	// die();
	
	// $sql = "INSERT INTO ".$cid."_monthly_shiftplans_".$_SESSION['rego']['cur_year']." (";
	// foreach($data[0] as $k=>$v){
	// 	$sql .= $k.',';
	// }

	// $sql = substr($sql,0,-1);
	// $sql .= ") VALUES ("; 


	// foreach($data as $key=>$val)
	// {


	// 	foreach($val as $k=>$v)
	// 	{

		
	// 		$sql .= "'".mysqli_real_escape_string($dbc,$v)."',";
	// 	}

	// 	$sql = substr($sql,0,-1);
	// 	$sql .= '),(';
	// }


	




	// $sql = substr($sql,0,-2);
	// $sql .= " ON DUPLICATE KEY UPDATE ";
	// foreach($data[0] as $k=>$v)
	// {
	// 	$sql .= $k.' = VALUES('.$k.'),';
	// 	//var_dump($v.' = VALUES('.$v.')');

	// }
	// $sql = substr($sql,0,-1);


	// die();



	
	foreach ($data as $key1 => $value1) 
	{
		if($value1['shiftteam_name'])
		{
			
				if($value1['time_reg'] == '1')
				// Insert into monthly shift plan 
				{


					$sql = "INSERT INTO ".$cid."_monthly_shiftplans_".$_SESSION['rego']['cur_year']." (id,sid,month,emp_id,en_name,th_name,shiftteam_name,shiftteam,D1,D2,D3,D4,D5,D6,D7,D8,D9,D10,D11,D12,D13,D14,D15,D16,D17,D18,D19,D20,D21,D22,D23,D24,D25,D26,D27,D28,D29,D30,D31) VALUES ";
					$sql .= "('".$dbc->real_escape_string($value1['id'])."', ";
					$sql .= "'".$dbc->real_escape_string($value1['sid'])."', ";
					$sql .= "'".$dbc->real_escape_string($value1['month'])."', ";
					$sql .= "'".$dbc->real_escape_string($value1['emp_id'])."', ";
					$sql .= "'".$dbc->real_escape_string($value1['en_name'])."', ";
					$sql .= "'".$dbc->real_escape_string($value1['th_name'])."', ";
					$sql .= "'".$dbc->real_escape_string($value1['shiftteam_name'])."', ";
					$sql .= "'".$dbc->real_escape_string($value1['shiftteam'])."', ";
					$sql .= "'".$dbc->real_escape_string($value1['D1'])."', ";
					$sql .= "'".$dbc->real_escape_string($value1['D2'])."', ";
					$sql .= "'".$dbc->real_escape_string($value1['D3'])."', ";
					$sql .= "'".$dbc->real_escape_string($value1['D4'])."', ";
					$sql .= "'".$dbc->real_escape_string($value1['D5'])."', ";
					$sql .= "'".$dbc->real_escape_string($value1['D6'])."', ";
					$sql .= "'".$dbc->real_escape_string($value1['D7'])."', ";
					$sql .= "'".$dbc->real_escape_string($value1['D8'])."', ";
					$sql .= "'".$dbc->real_escape_string($value1['D9'])."', ";
					$sql .= "'".$dbc->real_escape_string($value1['D10'])."', ";
					$sql .= "'".$dbc->real_escape_string($value1['D11'])."', ";
					$sql .= "'".$dbc->real_escape_string($value1['D12'])."', ";
					$sql .= "'".$dbc->real_escape_string($value1['D13'])."', ";
					$sql .= "'".$dbc->real_escape_string($value1['D14'])."', ";
					$sql .= "'".$dbc->real_escape_string($value1['D15'])."', ";
					$sql .= "'".$dbc->real_escape_string($value1['D16'])."', ";
					$sql .= "'".$dbc->real_escape_string($value1['D17'])."', ";
					$sql .= "'".$dbc->real_escape_string($value1['D18'])."', ";
					$sql .= "'".$dbc->real_escape_string($value1['D19'])."', ";
					$sql .= "'".$dbc->real_escape_string($value1['D20'])."', ";
					$sql .= "'".$dbc->real_escape_string($value1['D21'])."', ";
					$sql .= "'".$dbc->real_escape_string($value1['D22'])."', ";
					$sql .= "'".$dbc->real_escape_string($value1['D23'])."', ";
					$sql .= "'".$dbc->real_escape_string($value1['D24'])."', ";
					$sql .= "'".$dbc->real_escape_string($value1['D25'])."', ";
					$sql .= "'".$dbc->real_escape_string($value1['D26'])."', ";
					$sql .= "'".$dbc->real_escape_string($value1['D27'])."', ";
					$sql .= "'".$dbc->real_escape_string($value1['D28'])."', ";
					$sql .= "'".$dbc->real_escape_string($value1['D29'])."', ";
					$sql .= "'".$dbc->real_escape_string($value1['D30'])."', ";
					$sql .= "'".$dbc->real_escape_string($value1['D31'])."') ;";

			// echo $sql ;

					$result = $dbc->query($sql);
				}
			


		}

	}
// die();
			// ob_clean();	

	foreach ($data as $key => $value) {
		if($value['time_reg'] == '1')
		{


			$shifteamname = $value['shiftteam_name'];
			// Fetch Working Days according to month 

			$sql3 = "SELECT * FROM ".$cid."_shiftplans_".$cur_year." WHERE id = '".$shifteamname."'";
			if($res3 = $dbc->query($sql3)){
				if($row3 = $res3->fetch_assoc()){
					$workDays = unserialize($row3['wkd']);
					$variableoffdayssss = unserialize($row3['ss_data']);
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
				}
			}


			
			$empVOD = $variableOFFDays['variableOffDaysVal'];

			// Update working days of the employee

			$employeeID = $value['id'];

			$bal_off = array(
				'off_day_pending' => $empVOD,
				'off_day_used' => '0',

			);



			// pen = total off - variable off 
			// ob_clean();	
			// if($dbc->query($sql))
			// {

			if($value['shiftteam_name'])
			{

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

				// echo $sql4;
				echo 'success';
			}
			// }
			else
			{
				echo mysqli_error($dbc);
			}
		}




	}

	





