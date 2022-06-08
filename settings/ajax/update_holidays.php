<?
	if(session_id()==''){session_start();}
	ob_start();
	include("../../dbconnect/db_connect.php");
	//var_dump($_REQUEST); exit;


	$sql1 = "SELECT * FROM ".$cid."_shiftplans_".$cur_year ;

	// echo $sql1;
	// die();
	if($res1 = $dbc->query($sql1))
	{
		while($row1 = $res1->fetch_assoc())
		{
			$cyc_data[$row1['id']] = unserialize($row1['cycle_details']);
			$ss_data[$row1['id']] = unserialize($row1['ss_data']);
		}
	}







	$companyDate = date('Y-m-d', strtotime($_REQUEST['cdate']));
	$pcompanyDate = date('Y-m-d', strtotime($_REQUEST['hiddencdate']));



	$day=  date('l', strtotime($companyDate));
	$year = date('Y', strtotime($companyDate));
	$month = date('F', strtotime($companyDate));
	$onlyD = date('d', strtotime($companyDate));

	$zeroRemOnlyD =  ltrim($onlyD, '0');

	$pday=  date('l', strtotime($pcompanyDate));
	$pyear = date('Y', strtotime($pcompanyDate));
	$pmonth = date('F', strtotime($pcompanyDate));
	$ponlyD = date('d', strtotime($pcompanyDate));


	$zeroRempOnlyD =  ltrim($ponlyD, '0');



	function getDatesFromRange($start, $end, $format = 'Y-m-d') 
	{
	    $array = array();
	    $interval = new DateInterval('P1D');

	    $realEnd = new DateTime($end);
	    $realEnd->add($interval);

	    $period = new DatePeriod(new DateTime($start), $interval, $realEnd);

	    foreach($period as $date) { 
	        $array[] = $date->format($format); 
	    }

	    return $array;
	}

	$firstDay    = date('Y-m-d', strtotime('first day of january')); // first date
	

	foreach ($ss_data as $key2s => $value2s) 
	{
		if($key2s == 'main'){
			$startDate[$key2s] = '';
		}else{
			//$startDate[$key2s] = $value2s['startdate'];
			$startDate[$key2s] = date('Y-d-m', strtotime($value2s['startdate']));
		
		}
	}

	foreach ($startDate as $key22 => $value22) 
	{
		$pmonthss = date('F', strtotime($companyDate));
		$ponlyDss = date('d', strtotime($companyDate));

		if($value22 !=''){
			$betweenArray11[$key22] = getDatesFromRange($firstDay, $value22);
			$betweenArray[$key22][$pmonthss][$ponlyDss] = array(
																	'date' => $companyDate,
																	'day'  => $day,
																	's1'   => 'PUB',
																);

		}else{
			$betweenArray[$key22][$pmonthss][$ponlyDss] = array();
			$betweenArray11[] = '';
		}
	}


	// echo '<pre>';
	// print_r($betweenArray11['team1']);
	// echo '</pre>';



	// die();
	
	

	// starts from start date 
	foreach ($cyc_data as $key => $value) 
	{

		if(in_array($companyDate, $betweenArray11[$key])) 
		{
			if($key != 'main')
			{
				$cyc_data[$key][$month][$onlyD] = ['date' => $companyDate, 'day' => $day,'s1' => 'PUB'];
			}
		}

		if($value[$month][$onlyD]['s1'] != '')
		{
			if($value[$month][$onlyD]['s1'] !='PUB')
			{
				$cyc_data[$key][$month][$onlyD]['s1'] = 'PUB';
				$cyc_data[$key][$pmonth][$ponlyD]['s1'] = '';

				$sql2 = "UPDATE ".$cid."_shiftplans_".$cur_year." SET cycle_details = '".$dbc->real_escape_string(serialize($cyc_data[$key]))."' WHERE id= '".$key."'";
				$sql2Res =$dbc->query($sql2);
			}
		}
	}	

	// echo '<pre>';
	// print_r($cyc_data);
	// echo '</pre>';
	// die();


	// Update in monthly shift plan 

	// get data according to months from monthly shiftplan table 

	if($month == 'January')
	{
		$monthCount = '1';
	}
	else if($month == 'February')
	{
		$monthCount = '2';
	}
	else if($month == 'March')
	{
		$monthCount = '3';
	}
	else if($month == 'April')
	{
		$monthCount = '4';
	}
	else if($month == 'May')
	{
		$monthCount = '5';
	}	
	else if($month == 'June')
	{
		$monthCount = '6';
	}
	else if($month == 'July')
	{
		$monthCount = '7';
	}	
	else if($month == 'August')
	{
		$monthCount = '8';
	}	
	else if($month == 'September')
	{
		$monthCount = '9';
	}	
	else if($month == 'October')
	{
		$monthCount = '10';
	}	
	else if($month == 'November')
	{
		$monthCount = '11';
	}	
	else if($month == 'December')
	{
		$monthCount = '12';
	}


	$sql3 = "SELECT * FROM ".$cid."_monthly_shiftplans_".$cur_year." WHERE month  = '".$monthCount."'" ;


	if($res3 = $dbc->query($sql3))
	{
		while($row3 = $res3->fetch_assoc())
		{
			 $employes[] = $row3;
		}
	}

	 $colName= 'D'.$zeroRemOnlyD;
	 $pcolName= 'D'.$zeroRempOnlyD;


		
	$sql4 = "UPDATE ".$cid."_monthly_shiftplans_".$cur_year." SET ".$colName." = '".$dbc->real_escape_string('PUB')."' , ".$pcolName." = '".$dbc->real_escape_string('')."' WHERE month= '".$monthCount."'";
	$sql4Res =$dbc->query($sql4);





	
	$sql = "INSERT INTO ".$cid."_holidays (id, year, date, cdate, th, en) VALUES (
		'".$dbc->real_escape_string($_REQUEST['id'])."', 
		'".$dbc->real_escape_string(date('Y', strtotime($_REQUEST['date'])))."', 
		'".$dbc->real_escape_string(date('Y-m-d', strtotime($_REQUEST['date'])))."', 
		'".$dbc->real_escape_string(date('Y-m-d', strtotime($_REQUEST['cdate'])))."', 
		'".$dbc->real_escape_string($_REQUEST['th'])."', 
		'".$dbc->real_escape_string($_REQUEST['en'])."') 
		ON DUPLICATE KEY UPDATE 
		year = VALUES(year),
		date = VALUES(date),
		cdate = VALUES(cdate),
		th = VALUES(th),
		en = VALUES(en)";
	//echo $sql;
	//exit;
	
	// get cycle details 




	ob_clean();	
	if($dbc->query($sql)){
		echo 'success';
	}else{
		echo mysqli_error($dbc);
	}
	exit;
?>