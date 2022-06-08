<?
	if(session_id()==''){session_start();}
	ob_start();
	include("../../dbconnect/db_connect.php");


	if($_POST['shift_schedule1'] != 'select')
	{
		 $shift_schedule1 = $_POST['shift_schedule1'];
	}	
	if($_POST['shift_schedule2'] != 'select')
	{
		 $shift_schedule2 = $_POST['shift_schedule2'];
	}	

	$youroff = $_POST['youroff'];
	$yourschedule = $_POST['yourschedule'];
	$finalarray = $_POST['finalarray'];
	$sArray = $_POST['sArray'];
	$schedulerange4 = $_POST['schedulerange4'];
	$dateResult = $_POST['dateResult'];

 	$days = array();
    for ($i = 0; $i < 7; $i++) {
        $days[$i] = jddayofweek($i,1);
    }
   

	$daystest = array();

	$label2= array_merge(...array_fill(0, 150, $finalarray));
	$days2 = array_merge(...array_fill(0, 150, $days));
	// $sArray2 = array_merge(...array_fill(0, 150, $sArray));
	$sArray2 = array_merge(...array_fill(0, 150, $schedulerange4));

	$array1 = array_slice($label2, 0, 365);
	$array2 = array_slice($days2, 0, 365);
	$array3 = array_slice($sArray2, 0, 365);

	
	// echo '<pre>';
	// print_r($sArray2);
	// echo '</pre>';

	// die();
	


	function getDatesFromRange($start, $end, $format = 'Y-m-d') {
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



	$datetest = explode('/',$_POST['startdate']);
	$newDateVar =  $datetest[2].'-'.$datetest[1].'-'.$datetest[0];
	// $startdate = date('Y-m-d',strtotime($datetest)); // start date 
	$startdate = $newDateVar; // start date 
	$yearEnd   = date('Y-m-d', strtotime('last day of december')); // end date

	
	$test = getDatesFromRange($startdate, $yearEnd);

	$yearStart   = date('Y-m-d', strtotime('first day of January'));


	foreach ( $test as $idx => $val ) {

		$newday = date('l', strtotime($val));

	    // $all_array[] = [ $val, $array1[$idx], $array2[$idx], $array3[$idx] ];
	    $all_array[] = [ $val, $array1[$idx], $newday, $array3[$idx] ];
	}

	// echo '<pre>';
	// print_r($all_array);
	// echo '</pre>';
	// die();


	$pubDate = array();
	$sql3 = "SELECT * FROM rego_default_holidays  WHERE year= '".$cur_year."'";
	if($res3 = $dba->query($sql3)){
		while($row3 = $res3->fetch_assoc()){
			$pubDate[] = $row3['cdate'];
		}
	}


	$firstDay    = date('Y-m-d', strtotime('first day of january')); // first date
	$betweenArray = getDatesFromRange($firstDay, $startdate);






	$months =array();
	foreach ($betweenArray as $key => $value) 
	{
		# code...
		$day=  date('l', strtotime($value));



		$dateArray[$value] =$day;


		$year = date('Y', strtotime($value));

		$month = date('F', strtotime($value));
		$onlyD = date('d', strtotime($value));

		if($month == 'January')
		{	
			if($day == 'Monday')
			{
				if (in_array($value, $pubDate))
				{	

					$months['January'][$onlyD] = ['date' => $value,'day' => $day,'s1'=> 'PUB'];
				}
			}
			else if($day == 'Tuesday')
			{
				if (in_array($value, $pubDate))
				{
					$months['January'][$onlyD] = ['date' => $value,'day' => $day,'s1'=> 'PUB'];
				}
			}			

			else if($day == 'Wednesday')
			{
				if (in_array($value, $pubDate))
				{
					$months['January'][$onlyD] = ['date' => $value,'day' => $day,'s1'=> 'PUB'];
				}
			}			

			else if($day == 'Thursday')
			{
				if (in_array($value, $pubDate))
				{
					$months['January'][$onlyD] = ['date' => $value,'day' => $day,'s1'=> 'PUB'];
				}
			}			
			else if($day == 'Friday')
			{
				if (in_array($value, $pubDate))
				{
					$months['January'][$onlyD] = ['date' => $value,'day' => $day,'s1'=> 'PUB'];
				}
			}			
			else if($day == 'Saturday')
			{
				if (in_array($value, $pubDate))
				{
					$months['January'][$onlyD] = ['date' => $value,'day' => $day,'s1'=> 'PUB'];
				}
			}			
			else if($day == 'Sunday')
			{
				if (in_array($value, $pubDate))
				{
					$months['January'][$onlyD] = ['date' => $value,'day' => $day,'s1'=> 'PUB'];
				}
			}

		}			
		else if($month == 'February')
		{	
			if($day == 'Monday')
			{
				if (in_array($value, $pubDate))
				{	

					$months['February'][$onlyD] = ['date' => $value,'day' => $day,'s1'=> 'PUB'];
				}
			}
			else if($day == 'Tuesday')
			{
				if (in_array($value, $pubDate))
				{
					$months['February'][$onlyD] = ['date' => $value,'day' => $day,'s1'=> 'PUB'];
				}
			}			

			else if($day == 'Wednesday')
			{
				if (in_array($value, $pubDate))
				{
					$months['February'][$onlyD] = ['date' => $value,'day' => $day,'s1'=> 'PUB'];
				}
			}			

			else if($day == 'Thursday')
			{
				if (in_array($value, $pubDate))
				{
					$months['February'][$onlyD] = ['date' => $value,'day' => $day,'s1'=> 'PUB'];
				}
			}			
			else if($day == 'Friday')
			{
				if (in_array($value, $pubDate))
				{
					$months['February'][$onlyD] = ['date' => $value,'day' => $day,'s1'=> 'PUB'];
				}
			}			
			else if($day == 'Saturday')
			{
				if (in_array($value, $pubDate))
				{
					$months['February'][$onlyD] = ['date' => $value,'day' => $day,'s1'=> 'PUB'];
				}
			}			
			else if($day == 'Sunday')
			{
				if (in_array($value, $pubDate))
				{
					$months['February'][$onlyD] = ['date' => $value,'day' => $day,'s1'=> 'PUB'];
				}
			}

		}			

		else if($month == 'March')
		{	
			if($day == 'Monday')
			{
				if (in_array($value, $pubDate))
				{	

					$months['March'][$onlyD] = ['date' => $value,'day' => $day,'s1'=> 'PUB'];
				}
			}
			else if($day == 'Tuesday')
			{
				if (in_array($value, $pubDate))
				{
					$months['March'][$onlyD] = ['date' => $value,'day' => $day,'s1'=> 'PUB'];
				}
			}			

			else if($day == 'Wednesday')
			{
				if (in_array($value, $pubDate))
				{
					$months['March'][$onlyD] = ['date' => $value,'day' => $day,'s1'=> 'PUB'];
				}
			}			

			else if($day == 'Thursday')
			{
				if (in_array($value, $pubDate))
				{
					$months['March'][$onlyD] = ['date' => $value,'day' => $day,'s1'=> 'PUB'];
				}
			}			
			else if($day == 'Friday')
			{
				if (in_array($value, $pubDate))
				{
					$months['March'][$onlyD] = ['date' => $value,'day' => $day,'s1'=> 'PUB'];
				}
			}			
			else if($day == 'Saturday')
			{
				if (in_array($value, $pubDate))
				{
					$months['March'][$onlyD] = ['date' => $value,'day' => $day,'s1'=> 'PUB'];
				}
			}			
			else if($day == 'Sunday')
			{
				if (in_array($value, $pubDate))
				{
					$months['March'][$onlyD] = ['date' => $value,'day' => $day,'s1'=> 'PUB'];
				}
			}

		}			

		else if($month == 'April')
		{	
			if($day == 'Monday')
			{
				if (in_array($value, $pubDate))
				{	

					$months['April'][$onlyD] = ['date' => $value,'day' => $day,'s1'=> 'PUB'];
				}
			}
			else if($day == 'Tuesday')
			{
				if (in_array($value, $pubDate))
				{
					$months['April'][$onlyD] = ['date' => $value,'day' => $day,'s1'=> 'PUB'];
				}
			}			

			else if($day == 'Wednesday')
			{
				if (in_array($value, $pubDate))
				{
					$months['April'][$onlyD] = ['date' => $value,'day' => $day,'s1'=> 'PUB'];
				}
			}			

			else if($day == 'Thursday')
			{
				if (in_array($value, $pubDate))
				{
					$months['April'][$onlyD] = ['date' => $value,'day' => $day,'s1'=> 'PUB'];
				}
			}			
			else if($day == 'Friday')
			{
				if (in_array($value, $pubDate))
				{
					$months['April'][$onlyD] = ['date' => $value,'day' => $day,'s1'=> 'PUB'];
				}
			}			
			else if($day == 'Saturday')
			{
				if (in_array($value, $pubDate))
				{
					$months['April'][$onlyD] = ['date' => $value,'day' => $day,'s1'=> 'PUB'];
				}
			}			
			else if($day == 'Sunday')
			{
				if (in_array($value, $pubDate))
				{
					$months['April'][$onlyD] = ['date' => $value,'day' => $day,'s1'=> 'PUB'];
				}
			}

		}			
		else if($month == 'May')
		{	
			if($day == 'Monday')
			{
				if (in_array($value, $pubDate))
				{	

					$months['May'][$onlyD] = ['date' => $value,'day' => $day,'s1'=> 'PUB'];
				}
			}
			else if($day == 'Tuesday')
			{
				if (in_array($value, $pubDate))
				{
					$months['May'][$onlyD] = ['date' => $value,'day' => $day,'s1'=> 'PUB'];
				}
			}			

			else if($day == 'Wednesday')
			{
				if (in_array($value, $pubDate))
				{
					$months['May'][$onlyD] = ['date' => $value,'day' => $day,'s1'=> 'PUB'];
				}
			}			

			else if($day == 'Thursday')
			{
				if (in_array($value, $pubDate))
				{
					$months['May'][$onlyD] = ['date' => $value,'day' => $day,'s1'=> 'PUB'];
				}
			}			
			else if($day == 'Friday')
			{
				if (in_array($value, $pubDate))
				{
					$months['May'][$onlyD] = ['date' => $value,'day' => $day,'s1'=> 'PUB'];
				}
			}			
			else if($day == 'Saturday')
			{
				if (in_array($value, $pubDate))
				{
					$months['May'][$onlyD] = ['date' => $value,'day' => $day,'s1'=> 'PUB'];
				}
			}			
			else if($day == 'Sunday')
			{
				if (in_array($value, $pubDate))
				{
					$months['May'][$onlyD] = ['date' => $value,'day' => $day,'s1'=> 'PUB'];
				}
			}

		}			
		else if($month == 'June')
		{	
			if($day == 'Monday')
			{
				if (in_array($value, $pubDate))
				{	

					$months['June'][$onlyD] = ['date' => $value,'day' => $day,'s1'=> 'PUB'];
				}
			}
			else if($day == 'Tuesday')
			{
				if (in_array($value, $pubDate))
				{
					$months['June'][$onlyD] = ['date' => $value,'day' => $day,'s1'=> 'PUB'];
				}
			}			

			else if($day == 'Wednesday')
			{
				if (in_array($value, $pubDate))
				{
					$months['June'][$onlyD] = ['date' => $value,'day' => $day,'s1'=> 'PUB'];
				}
			}			

			else if($day == 'Thursday')
			{
				if (in_array($value, $pubDate))
				{
					$months['June'][$onlyD] = ['date' => $value,'day' => $day,'s1'=> 'PUB'];
				}
			}			
			else if($day == 'Friday')
			{
				if (in_array($value, $pubDate))
				{
					$months['June'][$onlyD] = ['date' => $value,'day' => $day,'s1'=> 'PUB'];
				}
			}			
			else if($day == 'Saturday')
			{
				if (in_array($value, $pubDate))
				{
					$months['June'][$onlyD] = ['date' => $value,'day' => $day,'s1'=> 'PUB'];
				}
			}			
			else if($day == 'Sunday')
			{
				if (in_array($value, $pubDate))
				{
					$months['June'][$onlyD] = ['date' => $value,'day' => $day,'s1'=> 'PUB'];
				}
			}

		}			
		else if($month == 'July')
		{	
			if($day == 'Monday')
			{
				if (in_array($value, $pubDate))
				{	

					$months['July'][$onlyD] = ['date' => $value,'day' => $day,'s1'=> 'PUB'];
				}
			}
			else if($day == 'Tuesday')
			{
				if (in_array($value, $pubDate))
				{
					$months['July'][$onlyD] = ['date' => $value,'day' => $day,'s1'=> 'PUB'];
				}
			}			

			else if($day == 'Wednesday')
			{
				if (in_array($value, $pubDate))
				{
					$months['July'][$onlyD] = ['date' => $value,'day' => $day,'s1'=> 'PUB'];
				}
			}			

			else if($day == 'Thursday')
			{
				if (in_array($value, $pubDate))
				{
					$months['July'][$onlyD] = ['date' => $value,'day' => $day,'s1'=> 'PUB'];
				}
			}			
			else if($day == 'Friday')
			{
				if (in_array($value, $pubDate))
				{
					$months['July'][$onlyD] = ['date' => $value,'day' => $day,'s1'=> 'PUB'];
				}
			}			
			else if($day == 'Saturday')
			{
				if (in_array($value, $pubDate))
				{
					$months['July'][$onlyD] = ['date' => $value,'day' => $day,'s1'=> 'PUB'];
				}
			}			
			else if($day == 'Sunday')
			{
				if (in_array($value, $pubDate))
				{
					$months['July'][$onlyD] = ['date' => $value,'day' => $day,'s1'=> 'PUB'];
				}
			}

		}		
		else if($month == 'August')
		{	
			if($day == 'Monday')
			{
				if (in_array($value, $pubDate))
				{	

					$months['August'][$onlyD] = ['date' => $value,'day' => $day,'s1'=> 'PUB'];
				}
			}
			else if($day == 'Tuesday')
			{
				if (in_array($value, $pubDate))
				{
					$months['August'][$onlyD] = ['date' => $value,'day' => $day,'s1'=> 'PUB'];
				}
			}			

			else if($day == 'Wednesday')
			{
				if (in_array($value, $pubDate))
				{
					$months['August'][$onlyD] = ['date' => $value,'day' => $day,'s1'=> 'PUB'];
				}
			}			

			else if($day == 'Thursday')
			{
				if (in_array($value, $pubDate))
				{
					$months['August'][$onlyD] = ['date' => $value,'day' => $day,'s1'=> 'PUB'];
				}
			}			
			else if($day == 'Friday')
			{
				if (in_array($value, $pubDate))
				{
					$months['August'][$onlyD] = ['date' => $value,'day' => $day,'s1'=> 'PUB'];
				}
			}			
			else if($day == 'Saturday')
			{
				if (in_array($value, $pubDate))
				{
					$months['August'][$onlyD] = ['date' => $value,'day' => $day,'s1'=> 'PUB'];
				}
			}			
			else if($day == 'Sunday')
			{
				if (in_array($value, $pubDate))
				{
					$months['August'][$onlyD] = ['date' => $value,'day' => $day,'s1'=> 'PUB'];
				}
			}

		}			
		else if($month == 'September')
		{	
			if($day == 'Monday')
			{
				if (in_array($value, $pubDate))
				{	

					$months['September'][$onlyD] = ['date' => $value,'day' => $day,'s1'=> 'PUB'];
				}
			}
			else if($day == 'Tuesday')
			{
				if (in_array($value, $pubDate))
				{
					$months['September'][$onlyD] = ['date' => $value,'day' => $day,'s1'=> 'PUB'];
				}
			}			

			else if($day == 'Wednesday')
			{
				if (in_array($value, $pubDate))
				{
					$months['September'][$onlyD] = ['date' => $value,'day' => $day,'s1'=> 'PUB'];
				}
			}			

			else if($day == 'Thursday')
			{
				if (in_array($value, $pubDate))
				{
					$months['September'][$onlyD] = ['date' => $value,'day' => $day,'s1'=> 'PUB'];
				}
			}			
			else if($day == 'Friday')
			{
				if (in_array($value, $pubDate))
				{
					$months['September'][$onlyD] = ['date' => $value,'day' => $day,'s1'=> 'PUB'];
				}
			}			
			else if($day == 'Saturday')
			{
				if (in_array($value, $pubDate))
				{
					$months['September'][$onlyD] = ['date' => $value,'day' => $day,'s1'=> 'PUB'];
				}
			}			
			else if($day == 'Sunday')
			{
				if (in_array($value, $pubDate))
				{
					$months['September'][$onlyD] = ['date' => $value,'day' => $day,'s1'=> 'PUB'];
				}
			}

		}			
		else if($month == 'October')
		{	
			if($day == 'Monday')
			{
				if (in_array($value, $pubDate))
				{	

					$months['October'][$onlyD] = ['date' => $value,'day' => $day,'s1'=> 'PUB'];
				}
			}
			else if($day == 'Tuesday')
			{
				if (in_array($value, $pubDate))
				{
					$months['October'][$onlyD] = ['date' => $value,'day' => $day,'s1'=> 'PUB'];
				}
			}			

			else if($day == 'Wednesday')
			{
				if (in_array($value, $pubDate))
				{
					$months['October'][$onlyD] = ['date' => $value,'day' => $day,'s1'=> 'PUB'];
				}
			}			

			else if($day == 'Thursday')
			{
				if (in_array($value, $pubDate))
				{
					$months['October'][$onlyD] = ['date' => $value,'day' => $day,'s1'=> 'PUB'];
				}
			}			
			else if($day == 'Friday')
			{
				if (in_array($value, $pubDate))
				{
					$months['October'][$onlyD] = ['date' => $value,'day' => $day,'s1'=> 'PUB'];
				}
			}			
			else if($day == 'Saturday')
			{
				if (in_array($value, $pubDate))
				{
					$months['October'][$onlyD] = ['date' => $value,'day' => $day,'s1'=> 'PUB'];
				}
			}			
			else if($day == 'Sunday')
			{
				if (in_array($value, $pubDate))
				{
					$months['October'][$onlyD] = ['date' => $value,'day' => $day,'s1'=> 'PUB'];
				}
			}

		}		
		else if($month == 'November')
		{	
			if($day == 'Monday')
			{
				if (in_array($value, $pubDate))
				{	

					$months['November'][$onlyD] = ['date' => $value,'day' => $day,'s1'=> 'PUB'];
				}
			}
			else if($day == 'Tuesday')
			{
				if (in_array($value, $pubDate))
				{
					$months['November'][$onlyD] = ['date' => $value,'day' => $day,'s1'=> 'PUB'];
				}
			}			

			else if($day == 'Wednesday')
			{
				if (in_array($value, $pubDate))
				{
					$months['November'][$onlyD] = ['date' => $value,'day' => $day,'s1'=> 'PUB'];
				}
			}			

			else if($day == 'Thursday')
			{
				if (in_array($value, $pubDate))
				{
					$months['November'][$onlyD] = ['date' => $value,'day' => $day,'s1'=> 'PUB'];
				}
			}			
			else if($day == 'Friday')
			{
				if (in_array($value, $pubDate))
				{
					$months['November'][$onlyD] = ['date' => $value,'day' => $day,'s1'=> 'PUB'];
				}
			}			
			else if($day == 'Saturday')
			{
				if (in_array($value, $pubDate))
				{
					$months['November'][$onlyD] = ['date' => $value,'day' => $day,'s1'=> 'PUB'];
				}
			}			
			else if($day == 'Sunday')
			{
				if (in_array($value, $pubDate))
				{
					$months['November'][$onlyD] = ['date' => $value,'day' => $day,'s1'=> 'PUB'];
				}
			}

		}		
		else if($month == 'December')
		{	
			if($day == 'Monday')
			{
				if (in_array($value, $pubDate))
				{	

					$months['December'][$onlyD] = ['date' => $value,'day' => $day,'s1'=> 'PUB'];
				}
			}
			else if($day == 'Tuesday')
			{
				if (in_array($value, $pubDate))
				{
					$months['December'][$onlyD] = ['date' => $value,'day' => $day,'s1'=> 'PUB'];
				}
			}			

			else if($day == 'Wednesday')
			{
				if (in_array($value, $pubDate))
				{
					$months['December'][$onlyD] = ['date' => $value,'day' => $day,'s1'=> 'PUB'];
				}
			}			

			else if($day == 'Thursday')
			{
				if (in_array($value, $pubDate))
				{
					$months['December'][$onlyD] = ['date' => $value,'day' => $day,'s1'=> 'PUB'];
				}
			}			
			else if($day == 'Friday')
			{
				if (in_array($value, $pubDate))
				{
					$months['December'][$onlyD] = ['date' => $value,'day' => $day,'s1'=> 'PUB'];
				}
			}			
			else if($day == 'Saturday')
			{
				if (in_array($value, $pubDate))
				{
					$months['December'][$onlyD] = ['date' => $value,'day' => $day,'s1'=> 'PUB'];
				}
			}			
			else if($day == 'Sunday')
			{
				if (in_array($value, $pubDate))
				{
					$months['December'][$onlyD] = ['date' => $value,'day' => $day,'s1'=> 'PUB'];
				}
			}

		}	


	}







	$day = array();

	foreach ($all_array as $key => $value) 
	{

		
		$day=  date('l', strtotime($value[0]));

		$dateArray[$value[0]] =$day;

		$year = date('Y', strtotime($value[0]));
		$month = date('F', strtotime($value[0]));
		$onlyD = date('d', strtotime($value[0]));


			if($month == 'January')
			{	
				if($day == 'Monday')
				{	

					if (in_array($value[0], $pubDate))
					{
						$months['January'][$onlyD] = ['date' => $value[0],'day' => $day,'s1'=> 'PUB'];
					}
					else
					{
						if($value[1] == '0' && $value[2] == 'Monday')
						{
							$months['January'][$onlyD] = ['date' => $value[0],'day' => $day,'s1'=> 'OFF'];
						}
						else
						{
							$months['January'][$onlyD] = ['date' => $value[0],'day' => $day,'s1'=> $value[3]];
						}	
					}				
				
				}				
				else if($day == 'Tuesday')
				{	
					if (in_array($value[0], $pubDate))
					{
						$months['January'][$onlyD] = ['date' => $value[0],'day' => $day,'s1'=> 'PUB'];
					}
					else
					{
						if($value[1] == '0' && $value[2] == 'Tuesday')
						{
							$months['January'][$onlyD] = ['date' => $value[0],'day' => $day,'s1'=> 'OFF'];
						}
						else
						{
							$months['January'][$onlyD] = ['date' => $value[0],'day' => $day,'s1'=> $value[3]];
						}
					}
				}				
				else if($day == 'Wednesday')
				{	

					if (in_array($value[0], $pubDate))
					{
						$months['January'][$onlyD] = ['date' => $value[0],'day' => $day,'s1'=> 'PUB'];
					}
					else
					{
						if($value[1] == '0' && $value[2] == 'Wednesday')
						{
							$months['January'][$onlyD] = ['date' => $value[0],'day' => $day,'s1'=> 'OFF'];
						}
						else
						{
							$months['January'][$onlyD] = ['date' => $value[0],'day' => $day,'s1'=> $value[3]];
						}
					}
				}				
				else if($day == 'Thursday')
				{	
					if (in_array($value[0], $pubDate))
					{
						$months['January'][$onlyD] = ['date' => $value[0],'day' => $day,'s1'=> 'PUB'];
					}
					else
					{
						if($value[1] == '0' && $value[2] == 'Thursday')
						{
							$months['January'][$onlyD] = ['date' => $value[0],'day' => $day,'s1'=> 'OFF'];
						}
						else
						{
							$months['January'][$onlyD] = ['date' => $value[0],'day' => $day,'s1'=> $value[3]];
						}
					}
				}			
				else if($day == 'Friday')
				{	
					if (in_array($value[0], $pubDate))
					{
						$months['January'][$onlyD] = ['date' => $value[0],'day' => $day,'s1'=> 'PUB'];

					}
					else
					{
						if($value[1] == '0' && $value[2] == 'Friday')
						{
							$months['January'][$onlyD] = ['date' => $value[0],'day' => $day,'s1'=> 'OFF'];
						}
						else
						{
							$months['January'][$onlyD] = ['date' => $value[0],'day' => $day,'s1'=> $value[3]];
						}
					}
				}
				else if($day == 'Saturday')
				{	
					if (in_array($value[0], $pubDate))
					{
						$months['January'][$onlyD] = ['date' => $value[0],'day' => $day,'s1'=> 'PUB'];
					}
					else
					{
						if($value[1] == '0' && $value[2] == 'Saturday')
						{
							$months['January'][$onlyD] = ['date' => $value[0],'day' => $day,'s1'=> 'OFF'];
						}
						else
						{
							$months['January'][$onlyD] = ['date' => $value[0],'day' => $day,'s1'=> $value[3]];
						}
					}
				}
				else if($day == 'Sunday')
				{	
					if (in_array($value[0], $pubDate))
					{
						$months['January'][$onlyD] = ['date' => $value[0],'day' => $day,'s1'=> 'PUB'];
					}
					else
					{
						if($value[1] == '0' && $value[2] == 'Sunday')
						{
							$months['January'][$onlyD] = ['date' => $value[0],'day' => $day,'s1'=> 'OFF'];
						}
						else
						{
							$months['January'][$onlyD] = ['date' => $value[0],'day' => $day,'s1'=> $value[3]];
						}
					}
				}
			}		
			else if($month == 'February')
			{	
				if($day == 'Monday')
				{	
					if (in_array($value[0], $pubDate))
					{
						$months['February'][$onlyD] = ['date' => $value[0],'day' => $day,'s1'=> 'PUB'];
					}
					else
					{
						if($value[1] == '0' && $value[2] == 'Monday')
						{
							$months['February'][$onlyD] = ['date' => $value[0],'day' => $day,'s1'=> 'OFF'];
						}
						else
						{
							$months['February'][$onlyD] = ['date' => $value[0],'day' => $day,'s1'=> $value[3]];
						}
					}					
				
				}				
				else if($day == 'Tuesday')
				{
					if (in_array($value[0], $pubDate))
					{
						$months['February'][$onlyD] = ['date' => $value[0],'day' => $day,'s1'=> 'PUB'];
					}
					else
					{
						if($value[1] == '0' && $value[2] == 'Tuesday')
						{
							$months['February'][$onlyD] = ['date' => $value[0],'day' => $day,'s1'=> 'OFF'];
						}
						else
						{
							$months['February'][$onlyD] = ['date' => $value[0],'day' => $day,'s1'=> $value[3]];
						}
					}
				}				
				else if($day == 'Wednesday')
				{	
					if (in_array($value[0], $pubDate))
					{
						$months['February'][$onlyD] = ['date' => $value[0],'day' => $day,'s1'=> 'PUB'];
					}
					else
					{
						if($value[1] == '0' && $value[2] == 'Wednesday')
						{
							$months['February'][$onlyD] = ['date' => $value[0],'day' => $day,'s1'=> 'OFF'];
						}
						else
						{
							$months['February'][$onlyD] = ['date' => $value[0],'day' => $day,'s1'=> $value[3]];
						}
					}
				}				
				else if($day == 'Thursday')
				{	
					if (in_array($value[0], $pubDate))
					{
						$months['February'][$onlyD] = ['date' => $value[0],'day' => $day,'s1'=> 'PUB'];
					}
					else
					{
						if($value[1] == '0' && $value[2] == 'Thursday')
						{
							$months['February'][$onlyD] = ['date' => $value[0],'day' => $day,'s1'=> 'OFF'];
						}
						else
						{
							$months['February'][$onlyD] = ['date' => $value[0],'day' => $day,'s1'=> $value[3]];
						}
					}
				}			
				else if($day == 'Friday')
				{	
					if (in_array($value[0], $pubDate))
					{
						$months['February'][$onlyD] = ['date' => $value[0],'day' => $day,'s1'=> 'PUB'];
					}
					else
					{
						if($value[1] == '0' && $value[2] == 'Friday')
						{
							$months['February'][$onlyD] = ['date' => $value[0],'day' => $day,'s1'=> 'OFF'];
						}
						else
						{
							$months['February'][$onlyD] = ['date' => $value[0],'day' => $day,'s1'=> $value[3]];
						}
					}
				}
				else if($day == 'Saturday')
				{	
					if (in_array($value[0], $pubDate))
					{
						$months['February'][$onlyD] = ['date' => $value[0],'day' => $day,'s1'=> 'PUB'];
					}
					else
					{
						if($value[1] == '0' && $value[2] == 'Saturday')
						{
							$months['February'][$onlyD] = ['date' => $value[0],'day' => $day,'s1'=> 'OFF'];
						}
						else
						{
							$months['February'][$onlyD] = ['date' => $value[0],'day' => $day,'s1'=> $value[3]];
						}
					}
				}
				else if($day == 'Sunday')
				{	
					if (in_array($value[0], $pubDate))
					{
						$months['February'][$onlyD] = ['date' => $value[0],'day' => $day,'s1'=> 'PUB'];
					}
					else
					{
						if($value[1] == '0' && $value[2] == 'Sunday')
						{
							$months['February'][$onlyD] = ['date' => $value[0],'day' => $day,'s1'=> 'OFF'];
						}
						else
						{
							$months['February'][$onlyD] = ['date' => $value[0],'day' => $day,'s1'=> $value[3]];
						}
					}
				}
			}			
			else if($month == 'March')
			{	
				if($day == 'Monday')
				{	
					if (in_array($value[0], $pubDate))
					{
						$months['March'][$onlyD] = ['date' => $value[0],'day' => $day,'s1'=> 'PUB'];
					}
					else
					{
						if($value[1] == '0' && $value[2] == 'Monday')
						{
							$months['March'][$onlyD] = ['date' => $value[0],'day' => $day,'s1'=> 'OFF'];
						}
						else
						{
							$months['March'][$onlyD] = ['date' => $value[0],'day' => $day,'s1'=> $value[3]];
						}					
					}
				
				}				
				else if($day == 'Tuesday')
				{	
					if (in_array($value[0], $pubDate))
					{
						$months['March'][$onlyD] = ['date' => $value[0],'day' => $day,'s1'=> 'PUB'];
					}
					else
					{
						if($value[1] == '0' && $value[2] == 'Tuesday')
						{
							$months['March'][$onlyD] = ['date' => $value[0],'day' => $day,'s1'=> 'OFF'];
						}
						else
						{
							$months['March'][$onlyD] = ['date' => $value[0],'day' => $day,'s1'=> $value[3]];
						}
					}
				}				
				else if($day == 'Wednesday')
				{	
					if (in_array($value[0], $pubDate))
					{
						$months['March'][$onlyD] = ['date' => $value[0],'day' => $day,'s1'=> 'PUB'];
					}
					else
					{
						if($value[1] == '0' && $value[2] == 'Wednesday')
						{
							$months['March'][$onlyD] = ['date' => $value[0],'day' => $day,'s1'=> 'OFF'];
						}
						else
						{
							$months['March'][$onlyD] = ['date' => $value[0],'day' => $day,'s1'=> $value[3]];
						}
					}
				}				
				else if($day == 'Thursday')
				{	
					if (in_array($value[0], $pubDate))
					{
						$months['March'][$onlyD] = ['date' => $value[0],'day' => $day,'s1'=> 'PUB'];
					}
					else
					{
						if($value[1] == '0' && $value[2] == 'Thursday')
						{
							$months['March'][$onlyD] = ['date' => $value[0],'day' => $day,'s1'=> 'OFF'];
						}
						else
						{
							$months['March'][$onlyD] = ['date' => $value[0],'day' => $day,'s1'=> $value[3]];
						}
					}
				}			
				else if($day == 'Friday')
				{	
					if (in_array($value[0], $pubDate))
					{
						$months['March'][$onlyD] = ['date' => $value[0],'day' => $day,'s1'=> 'PUB'];
					}
					else
					{
						if($value[1] == '0' && $value[2] == 'Friday')
						{
							$months['March'][$onlyD] = ['date' => $value[0],'day' => $day,'s1'=> 'OFF'];
						}
						else
						{
							$months['March'][$onlyD] = ['date' => $value[0],'day' => $day,'s1'=> $value[3]];
						}
					}
				}
				else if($day == 'Saturday')
				{	
					if (in_array($value[0], $pubDate))
					{
						$months['March'][$onlyD] = ['date' => $value[0],'day' => $day,'s1'=> 'PUB'];
					}
					else
					{
						if($value[1] == '0' && $value[2] == 'Saturday')
						{
							$months['March'][$onlyD] = ['date' => $value[0],'day' => $day,'s1'=> 'OFF'];
						}
						else
						{
							$months['March'][$onlyD] = ['date' => $value[0],'day' => $day,'s1'=> $value[3]];
						}
					}
				}
				else if($day == 'Sunday')
				{	
					if (in_array($value[0], $pubDate))
					{
						$months['March'][$onlyD] = ['date' => $value[0],'day' => $day,'s1'=> 'PUB'];
					}
					else
					{
						if($value[1] == '0' && $value[2] == 'Sunday')
						{
							$months['March'][$onlyD] = ['date' => $value[0],'day' => $day,'s1'=> 'OFF'];
						}
						else
						{
							$months['March'][$onlyD] = ['date' => $value[0],'day' => $day,'s1'=> $value[3]];
						}
					}
				}
			}			
			else if($month == 'April')
			{	
				if($day == 'Monday')
				{	
					if (in_array($value[0], $pubDate))
					{
						$months['April'][$onlyD] = ['date' => $value[0],'day' => $day,'s1'=> 'PUB'];
					}
					else
					{
						if($value[1] == '0' && $value[2] == 'Monday')
						{
							$months['April'][$onlyD] = ['date' => $value[0],'day' => $day,'s1'=> 'OFF'];
						}
						else
						{
							$months['April'][$onlyD] = ['date' => $value[0],'day' => $day,'s1'=> $value[3]];
						}
					}					
				
				}				
				else if($day == 'Tuesday')
				{	
					if (in_array($value[0], $pubDate))
					{
						$months['April'][$onlyD] = ['date' => $value[0],'day' => $day,'s1'=> 'PUB'];
					}
					else
					{
						if($value[1] == '0' && $value[2] == 'Tuesday')
						{
							$months['April'][$onlyD] = ['date' => $value[0],'day' => $day,'s1'=> 'OFF'];
						}
						else
						{
							$months['April'][$onlyD] = ['date' => $value[0],'day' => $day,'s1'=> $value[3]];
						}
					}
				}				
				else if($day == 'Wednesday')
				{	
					if (in_array($value[0], $pubDate))
					{
						$months['April'][$onlyD] = ['date' => $value[0],'day' => $day,'s1'=> 'PUB'];
					}
					else
					{
						if($value[1] == '0' && $value[2] == 'Wednesday')
						{
							$months['April'][$onlyD] = ['date' => $value[0],'day' => $day,'s1'=> 'OFF'];
						}
						else
						{
							$months['April'][$onlyD] = ['date' => $value[0],'day' => $day,'s1'=> $value[3]];
						}
					}
				}				
				else if($day == 'Thursday')
				{	
					if (in_array($value[0], $pubDate))
					{
						$months['April'][$onlyD] = ['date' => $value[0],'day' => $day,'s1'=> 'PUB'];
					}
					else
					{
						if($value[1] == '0' && $value[2] == 'Thursday')
						{
							$months['April'][$onlyD] = ['date' => $value[0],'day' => $day,'s1'=> 'OFF'];
						}
						else
						{
							$months['April'][$onlyD] = ['date' => $value[0],'day' => $day,'s1'=> $value[3]];
						}
					}
				}			
				else if($day == 'Friday')
				{	
					if (in_array($value[0], $pubDate))
					{
						$months['April'][$onlyD] = ['date' => $value[0],'day' => $day,'s1'=> 'PUB'];
					}
					else
					{
						if($value[1] == '0' && $value[2] == 'Friday')
						{
							$months['April'][$onlyD] = ['date' => $value[0],'day' => $day,'s1'=> 'OFF'];
						}
						else
						{
							$months['April'][$onlyD] = ['date' => $value[0],'day' => $day,'s1'=> $value[3]];
						}
					}
				}
				else if($day == 'Saturday')
				{	
					if (in_array($value[0], $pubDate))
					{
						$months['April'][$onlyD] = ['date' => $value[0],'day' => $day,'s1'=> 'PUB'];
					}
					else
					{
						if($value[1] == '0' && $value[2] == 'Saturday')
						{
							$months['April'][$onlyD] = ['date' => $value[0],'day' => $day,'s1'=> 'OFF'];
						}
						else
						{
							$months['April'][$onlyD] = ['date' => $value[0],'day' => $day,'s1'=> $value[3]];
						}
					}
				}
				else if($day == 'Sunday')
				{	
					if (in_array($value[0], $pubDate))
					{
						$months['April'][$onlyD] = ['date' => $value[0],'day' => $day,'s1'=> 'PUB'];
					}
					else
					{
						if($value[1] == '0' && $value[2] == 'Sunday')
						{
							$months['April'][$onlyD] = ['date' => $value[0],'day' => $day,'s1'=> 'OFF'];
						}
						else
						{
							$months['April'][$onlyD] = ['date' => $value[0],'day' => $day,'s1'=> $value[3]];
						}
					}
				}
			}			
			else if($month == 'May')
			{	
				if($day == 'Monday')
				{	
					if (in_array($value[0], $pubDate))
					{
						$months['May'][$onlyD] = ['date' => $value[0],'day' => $day,'s1'=> 'PUB'];
					}
					else
					{
						if($value[1] == '0' && $value[2] == 'Monday')
						{
							$months['May'][$onlyD] = ['date' => $value[0],'day' => $day,'s1'=> 'OFF'];
						}
						else
						{
							$months['May'][$onlyD] = ['date' => $value[0],'day' => $day,'s1'=> $value[3]];
						}	
					}				
				
				}				
				else if($day == 'Tuesday')
				{	
					if (in_array($value[0], $pubDate))
					{
						$months['May'][$onlyD] = ['date' => $value[0],'day' => $day,'s1'=> 'PUB'];
					}
					else
					{
						if($value[1] == '0' && $value[2] == 'Tuesday')
						{
							$months['May'][$onlyD] = ['date' => $value[0],'day' => $day,'s1'=> 'OFF'];
						}
						else
						{
							$months['May'][$onlyD] = ['date' => $value[0],'day' => $day,'s1'=> $value[3]];
						}
					}
				}				
				else if($day == 'Wednesday')
				{	
					if (in_array($value[0], $pubDate))
					{
						$months['May'][$onlyD] = ['date' => $value[0],'day' => $day,'s1'=> 'PUB'];
					}
					else
					{
						if($value[1] == '0' && $value[2] == 'Wednesday')
						{
							$months['May'][$onlyD] = ['date' => $value[0],'day' => $day,'s1'=> 'OFF'];
						}
						else
						{
							$months['May'][$onlyD] = ['date' => $value[0],'day' => $day,'s1'=> $value[3]];
						}
					}
				}				
				else if($day == 'Thursday')
				{	
					if (in_array($value[0], $pubDate))
					{
						$months['May'][$onlyD] = ['date' => $value[0],'day' => $day,'s1'=> 'PUB'];
					}
					else
					{
						if($value[1] == '0' && $value[2] == 'Thursday')
						{
							$months['May'][$onlyD] = ['date' => $value[0],'day' => $day,'s1'=> 'OFF'];
						}
						else
						{
							$months['May'][$onlyD] = ['date' => $value[0],'day' => $day,'s1'=> $value[3]];
						}
					}
				}			
				else if($day == 'Friday')
				{	
					if (in_array($value[0], $pubDate))
					{
						$months['May'][$onlyD] = ['date' => $value[0],'day' => $day,'s1'=> 'PUB'];
					}
					else
					{
						if($value[1] == '0' && $value[2] == 'Friday')
						{
							$months['May'][$onlyD] = ['date' => $value[0],'day' => $day,'s1'=> 'OFF'];
						}
						else
						{
							$months['May'][$onlyD] = ['date' => $value[0],'day' => $day,'s1'=> $value[3]];
						}
					}
				}
				else if($day == 'Saturday')
				{	
					if (in_array($value[0], $pubDate))
					{
						$months['May'][$onlyD] = ['date' => $value[0],'day' => $day,'s1'=> 'PUB'];
					}
					else
					{
						if($value[1] == '0' && $value[2] == 'Saturday')
						{
							$months['May'][$onlyD] = ['date' => $value[0],'day' => $day,'s1'=> 'OFF'];
						}
						else
						{
							$months['May'][$onlyD] = ['date' => $value[0],'day' => $day,'s1'=> $value[3]];
						}
					}
				}
				else if($day == 'Sunday')
				{	
					if (in_array($value[0], $pubDate))
					{
						$months['May'][$onlyD] = ['date' => $value[0],'day' => $day,'s1'=> 'PUB'];
					}
					else
					{
						if($value[1] == '0' && $value[2] == 'Sunday')
						{
							$months['May'][$onlyD] = ['date' => $value[0],'day' => $day,'s1'=> 'OFF'];
						}
						else
						{
							$months['May'][$onlyD] = ['date' => $value[0],'day' => $day,'s1'=> $value[3]];
						}
					}
				}
			}		
			else if($month == 'June')
			{	
				if($day == 'Monday')
				{	
					if (in_array($value[0], $pubDate))
					{
						$months['June'][$onlyD] = ['date' => $value[0],'day' => $day,'s1'=> 'PUB'];
					}
					else
					{
						if($value[1] == '0' && $value[2] == 'Monday')
						{
							$months['June'][$onlyD] = ['date' => $value[0],'day' => $day,'s1'=> 'OFF'];
						}
						else
						{
							$months['June'][$onlyD] = ['date' => $value[0],'day' => $day,'s1'=> $value[3]];
						}	

					}				
				
				}				
				else if($day == 'Tuesday')
				{	
					if (in_array($value[0], $pubDate))
					{
						$months['June'][$onlyD] = ['date' => $value[0],'day' => $day,'s1'=> 'PUB'];
					}
					else
					{
						if($value[1] == '0' && $value[2] == 'Tuesday')
						{
							$months['June'][$onlyD] = ['date' => $value[0],'day' => $day,'s1'=> 'OFF'];
						}
						else
						{
							$months['June'][$onlyD] = ['date' => $value[0],'day' => $day,'s1'=> $value[3]];
						}
					}
				}				
				else if($day == 'Wednesday')
				{	
					if (in_array($value[0], $pubDate))
					{
						$months['June'][$onlyD] = ['date' => $value[0],'day' => $day,'s1'=> 'PUB'];
					}
					else
					{
						if($value[1] == '0' && $value[2] == 'Wednesday')
						{
							$months['June'][$onlyD] = ['date' => $value[0],'day' => $day,'s1'=> 'OFF'];
						}
						else
						{
							$months['June'][$onlyD] = ['date' => $value[0],'day' => $day,'s1'=> $value[3]];
						}
					}
				}				
				else if($day == 'Thursday')
				{	
					if (in_array($value[0], $pubDate))
					{
						$months['June'][$onlyD] = ['date' => $value[0],'day' => $day,'s1'=> 'PUB'];
					}
					else
					{
						if($value[1] == '0' && $value[2] == 'Thursday')
						{
							$months['June'][$onlyD] = ['date' => $value[0],'day' => $day,'s1'=> 'OFF'];
						}
						else
						{
							$months['June'][$onlyD] = ['date' => $value[0],'day' => $day,'s1'=> $value[3]];
						}
					}
				}			
				else if($day == 'Friday')
				{	
					if (in_array($value[0], $pubDate))
					{
						$months['June'][$onlyD] = ['date' => $value[0],'day' => $day,'s1'=> 'PUB'];
					}
					else
					{
						if($value[1] == '0' && $value[2] == 'Friday')
						{
							$months['June'][$onlyD] = ['date' => $value[0],'day' => $day,'s1'=> 'OFF'];
						}
						else
						{
							$months['June'][$onlyD] = ['date' => $value[0],'day' => $day,'s1'=> $value[3]];
						}
					}
				}
				else if($day == 'Saturday')
				{	
					if (in_array($value[0], $pubDate))
					{
						$months['June'][$onlyD] = ['date' => $value[0],'day' => $day,'s1'=> 'PUB'];
					}
					else
					{
						if($value[1] == '0' && $value[2] == 'Saturday')
						{
							$months['June'][$onlyD] = ['date' => $value[0],'day' => $day,'s1'=> 'OFF'];
						}
						else
						{
							$months['June'][$onlyD] = ['date' => $value[0],'day' => $day,'s1'=> $value[3]];
						}
					}
				}
				else if($day == 'Sunday')
				{	
					if (in_array($value[0], $pubDate))
					{
						$months['June'][$onlyD] = ['date' => $value[0],'day' => $day,'s1'=> 'PUB'];
					}
					else
					{
						if($value[1] == '0' && $value[2] == 'Sunday')
						{
							$months['June'][$onlyD] = ['date' => $value[0],'day' => $day,'s1'=> 'OFF'];
						}
						else
						{
							$months['June'][$onlyD] = ['date' => $value[0],'day' => $day,'s1'=> $value[3]];
						}
					}
				}
			}			
			else if($month == 'July')
			{	
				if($day == 'Monday')
				{	
					if (in_array($value[0], $pubDate))
					{
						$months['July'][$onlyD] = ['date' => $value[0],'day' => $day,'s1'=> 'PUB'];
					}
					else
					{
						if($value[1] == '0' && $value[2] == 'Monday')
						{
							$months['July'][$onlyD] = ['date' => $value[0],'day' => $day,'s1'=> 'OFF'];
						}
						else
						{
							$months['July'][$onlyD] = ['date' => $value[0],'day' => $day,'s1'=> $value[3]];
						}	
					}				
				
				}				
				else if($day == 'Tuesday')
				{	
					if (in_array($value[0], $pubDate))
					{
						$months['July'][$onlyD] = ['date' => $value[0],'day' => $day,'s1'=> 'PUB'];
					}
					else
					{
						if($value[1] == '0' && $value[2] == 'Tuesday')
						{
							$months['July'][$onlyD] = ['date' => $value[0],'day' => $day,'s1'=> 'OFF'];
						}
						else
						{
							$months['July'][$onlyD] = ['date' => $value[0],'day' => $day,'s1'=> $value[3]];
						}
					}
				}				
				else if($day == 'Wednesday')
				{	
					if (in_array($value[0], $pubDate))
					{
						$months['July'][$onlyD] = ['date' => $value[0],'day' => $day,'s1'=> 'PUB'];
					}
					else
					{
						if($value[1] == '0' && $value[2] == 'Wednesday')
						{
							$months['July'][$onlyD] = ['date' => $value[0],'day' => $day,'s1'=> 'OFF'];
						}
						else
						{
							$months['July'][$onlyD] = ['date' => $value[0],'day' => $day,'s1'=> $value[3]];
						}
					}
				}				
				else if($day == 'Thursday')
				{	
					if (in_array($value[0], $pubDate))
					{
						$months['July'][$onlyD] = ['date' => $value[0],'day' => $day,'s1'=> 'PUB'];
					}
					else
					{
						if($value[1] == '0' && $value[2] == 'Thursday')
						{
							$months['July'][$onlyD] = ['date' => $value[0],'day' => $day,'s1'=> 'OFF'];
						}
						else
						{
							$months['July'][$onlyD] = ['date' => $value[0],'day' => $day,'s1'=> $value[3]];
						}
					}
				}			
				else if($day == 'Friday')
				{	
					if (in_array($value[0], $pubDate))
					{
						$months['July'][$onlyD] = ['date' => $value[0],'day' => $day,'s1'=> 'PUB'];
					}
					else
					{
						if($value[1] == '0' && $value[2] == 'Friday')
						{
							$months['July'][$onlyD] = ['date' => $value[0],'day' => $day,'s1'=> 'OFF'];
						}
						else
						{
							$months['July'][$onlyD] = ['date' => $value[0],'day' => $day,'s1'=> $value[3]];
						}
					}
				}
				else if($day == 'Saturday')
				{	
					if (in_array($value[0], $pubDate))
					{
						$months['July'][$onlyD] = ['date' => $value[0],'day' => $day,'s1'=> 'PUB'];
					}
					else
					{
						if($value[1] == '0' && $value[2] == 'Saturday')
						{
							$months['July'][$onlyD] = ['date' => $value[0],'day' => $day,'s1'=> 'OFF'];
						}
						else
						{
							$months['July'][$onlyD] = ['date' => $value[0],'day' => $day,'s1'=> $value[3]];
						}
					}
				}
				else if($day == 'Sunday')
				{	
					if (in_array($value[0], $pubDate))
					{
						$months['July'][$onlyD] = ['date' => $value[0],'day' => $day,'s1'=> 'PUB'];
					}
					else
					{
						if($value[1] == '0' && $value[2] == 'Sunday')
						{
							$months['July'][$onlyD] = ['date' => $value[0],'day' => $day,'s1'=> 'OFF'];
						}
						else
						{
							$months['July'][$onlyD] = ['date' => $value[0],'day' => $day,'s1'=> $value[3]];
						}
					}
				}
			}			
			else if($month == 'August')
			{	
				if($day == 'Monday')
				{	
					if (in_array($value[0], $pubDate))
					{
						$months['August'][$onlyD] = ['date' => $value[0],'day' => $day,'s1'=> 'PUB'];
					}
					else
					{
						if($value[1] == '0' && $value[2] == 'Monday')
						{
							$months['August'][$onlyD] = ['date' => $value[0],'day' => $day,'s1'=> 'OFF'];
						}
						else
						{
							$months['August'][$onlyD] = ['date' => $value[0],'day' => $day,'s1'=> $value[3]];
						}		
					}			
				
				}				
				else if($day == 'Tuesday')
				{	
					if (in_array($value[0], $pubDate))
					{
						$months['August'][$onlyD] = ['date' => $value[0],'day' => $day,'s1'=> 'PUB'];
					}
					else
					{
						if($value[1] == '0' && $value[2] == 'Tuesday')
						{
							$months['August'][$onlyD] = ['date' => $value[0],'day' => $day,'s1'=> 'OFF'];
						}
						else
						{
							$months['August'][$onlyD] = ['date' => $value[0],'day' => $day,'s1'=> $value[3]];
						}
					}
				}				
				else if($day == 'Wednesday')
				{	
					if (in_array($value[0], $pubDate))
					{
						$months['August'][$onlyD] = ['date' => $value[0],'day' => $day,'s1'=> 'PUB'];
					}
					else
					{
						if($value[1] == '0' && $value[2] == 'Wednesday')
						{
							$months['August'][$onlyD] = ['date' => $value[0],'day' => $day,'s1'=> 'OFF'];
						}
						else
						{
							$months['August'][$onlyD] = ['date' => $value[0],'day' => $day,'s1'=> $value[3]];
						}
					}
				}				
				else if($day == 'Thursday')
				{	
					if (in_array($value[0], $pubDate))
					{
						$months['August'][$onlyD] = ['date' => $value[0],'day' => $day,'s1'=> 'PUB'];
					}
					else
					{
						if($value[1] == '0' && $value[2] == 'Thursday')
						{
							$months['August'][$onlyD] = ['date' => $value[0],'day' => $day,'s1'=> 'OFF'];
						}
						else
						{
							$months['August'][$onlyD] = ['date' => $value[0],'day' => $day,'s1'=> $value[3]];
						}
					}
				}			
				else if($day == 'Friday')
				{	
					if (in_array($value[0], $pubDate))
					{
						$months['August'][$onlyD] = ['date' => $value[0],'day' => $day,'s1'=> 'PUB'];
					}
					else
					{
						if($value[1] == '0' && $value[2] == 'Friday')
						{
							$months['August'][$onlyD] = ['date' => $value[0],'day' => $day,'s1'=> 'OFF'];
						}
						else
						{
							$months['August'][$onlyD] = ['date' => $value[0],'day' => $day,'s1'=> $value[3]];
						}
					}
				}
				else if($day == 'Saturday')
				{	
					if (in_array($value[0], $pubDate))
					{
						$months['August'][$onlyD] = ['date' => $value[0],'day' => $day,'s1'=> 'PUB'];
					}
					else
					{
						if($value[1] == '0' && $value[2] == 'Saturday')
						{
							$months['August'][$onlyD] = ['date' => $value[0],'day' => $day,'s1'=> 'OFF'];
						}
						else
						{
							$months['August'][$onlyD] = ['date' => $value[0],'day' => $day,'s1'=> $value[3]];
						}
					}
				}
				else if($day == 'Sunday')
				{	
					if (in_array($value[0], $pubDate))
					{
						$months['August'][$onlyD] = ['date' => $value[0],'day' => $day,'s1'=> 'PUB'];
					}
					else
					{
						if($value[1] == '0' && $value[2] == 'Sunday')
						{
							$months['August'][$onlyD] = ['date' => $value[0],'day' => $day,'s1'=> 'OFF'];
						}
						else
						{
							$months['August'][$onlyD] = ['date' => $value[0],'day' => $day,'s1'=> $value[3]];
						}
					}
				}
			}			
			else if($month == 'September')
			{	
				if($day == 'Monday')
				{	
					if (in_array($value[0], $pubDate))
					{
						$months['September'][$onlyD] = ['date' => $value[0],'day' => $day,'s1'=> 'PUB'];
					}
					else
					{
						if($value[1] == '0' && $value[2] == 'Monday')
						{
							$months['September'][$onlyD] = ['date' => $value[0],'day' => $day,'s1'=> 'OFF'];
						}
						else
						{
							$months['September'][$onlyD] = ['date' => $value[0],'day' => $day,'s1'=> $value[3]];
						}	
					}				
				
				}				
				else if($day == 'Tuesday')
				{	
					if (in_array($value[0], $pubDate))
					{
						$months['September'][$onlyD] = ['date' => $value[0],'day' => $day,'s1'=> 'PUB'];
					}
					else
					{
						if($value[1] == '0' && $value[2] == 'Tuesday')
						{
							$months['September'][$onlyD] = ['date' => $value[0],'day' => $day,'s1'=> 'OFF'];
						}
						else
						{
							$months['September'][$onlyD] = ['date' => $value[0],'day' => $day,'s1'=> $value[3]];
						}
					}
				}				
				else if($day == 'Wednesday')
				{	
					if (in_array($value[0], $pubDate))
					{
						$months['September'][$onlyD] = ['date' => $value[0],'day' => $day,'s1'=> 'PUB'];
					}
					else
					{
						if($value[1] == '0' && $value[2] == 'Wednesday')
						{
							$months['September'][$onlyD] = ['date' => $value[0],'day' => $day,'s1'=> 'OFF'];
						}
						else
						{
							$months['September'][$onlyD] = ['date' => $value[0],'day' => $day,'s1'=> $value[3]];
						}
					}
				}				
				else if($day == 'Thursday')
				{	
					if (in_array($value[0], $pubDate))
					{
						$months['September'][$onlyD] = ['date' => $value[0],'day' => $day,'s1'=> 'PUB'];
					}
					else
					{
						if($value[1] == '0' && $value[2] == 'Thursday')
						{
							$months['September'][$onlyD] = ['date' => $value[0],'day' => $day,'s1'=> 'OFF'];
						}
						else
						{
							$months['September'][$onlyD] = ['date' => $value[0],'day' => $day,'s1'=> $value[3]];
						}
					}
				}			
				else if($day == 'Friday')
				{	
					if (in_array($value[0], $pubDate))
					{
						$months['September'][$onlyD] = ['date' => $value[0],'day' => $day,'s1'=> 'PUB'];
					}
					else
					{
						if($value[1] == '0' && $value[2] == 'Friday')
						{
							$months['September'][$onlyD] = ['date' => $value[0],'day' => $day,'s1'=> 'OFF'];
						}
						else
						{
							$months['September'][$onlyD] = ['date' => $value[0],'day' => $day,'s1'=> $value[3]];
						}
					}
				}
				else if($day == 'Saturday')
				{	
					if (in_array($value[0], $pubDate))
					{
						$months['September'][$onlyD] = ['date' => $value[0],'day' => $day,'s1'=> 'PUB'];
					}
					else
					{
						if($value[1] == '0' && $value[2] == 'Saturday')
						{
							$months['September'][$onlyD] = ['date' => $value[0],'day' => $day,'s1'=> 'OFF'];
						}
						else
						{
							$months['September'][$onlyD] = ['date' => $value[0],'day' => $day,'s1'=> $value[3]];
						}
					}
				}
				else if($day == 'Sunday')
				{	
					if (in_array($value[0], $pubDate))
					{
						$months['September'][$onlyD] = ['date' => $value[0],'day' => $day,'s1'=> 'PUB'];
					}
					else
					{
						if($value[1] == '0' && $value[2] == 'Sunday')
						{
							$months['September'][$onlyD] = ['date' => $value[0],'day' => $day,'s1'=> 'OFF'];
						}
						else
						{
							$months['September'][$onlyD] = ['date' => $value[0],'day' => $day,'s1'=> $value[3]];
						}
					}
				}
			}			
			else if($month == 'October')
			{	
				if($day == 'Monday')
				{	
					if (in_array($value[0], $pubDate))
					{
						$months['October'][$onlyD] = ['date' => $value[0],'day' => $day,'s1'=> 'PUB'];
					}
					else
					{
						if($value[1] == '0' && $value[2] == 'Monday')
						{
							$months['October'][$onlyD] = ['date' => $value[0],'day' => $day,'s1'=> 'OFF'];
						}
						else
						{
							$months['October'][$onlyD] = ['date' => $value[0],'day' => $day,'s1'=> $value[3]];
						}	
					}				
				
				}				
				else if($day == 'Tuesday')
				{	
					if (in_array($value[0], $pubDate))
					{
						$months['October'][$onlyD] = ['date' => $value[0],'day' => $day,'s1'=> 'PUB'];
					}
					else
					{
						if($value[1] == '0' && $value[2] == 'Tuesday')
						{
							$months['October'][$onlyD] = ['date' => $value[0],'day' => $day,'s1'=> 'OFF'];
						}
						else
						{
							$months['October'][$onlyD] = ['date' => $value[0],'day' => $day,'s1'=> $value[3]];
						}
					}
				}				
				else if($day == 'Wednesday')
				{	
					if (in_array($value[0], $pubDate))
					{
						$months['October'][$onlyD] = ['date' => $value[0],'day' => $day,'s1'=> 'PUB'];
					}
					else
					{
						if($value[1] == '0' && $value[2] == 'Wednesday')
						{
							$months['October'][$onlyD] = ['date' => $value[0],'day' => $day,'s1'=> 'OFF'];
						}
						else
						{
							$months['October'][$onlyD] = ['date' => $value[0],'day' => $day,'s1'=> $value[3]];
						}
					}
				}				
				else if($day == 'Thursday')
				{	
					if (in_array($value[0], $pubDate))
					{
						$months['October'][$onlyD] = ['date' => $value[0],'day' => $day,'s1'=> 'PUB'];
					}
					else
					{
						if($value[1] == '0' && $value[2] == 'Thursday')
						{
							$months['October'][$onlyD] = ['date' => $value[0],'day' => $day,'s1'=> 'OFF'];
						}
						else
						{
							$months['October'][$onlyD] = ['date' => $value[0],'day' => $day,'s1'=> $value[3]];
						}
					}
				}			
				else if($day == 'Friday')
				{	
					if (in_array($value[0], $pubDate))
					{
						$months['October'][$onlyD] = ['date' => $value[0],'day' => $day,'s1'=> 'PUB'];
					}
					else
					{
						if($value[1] == '0' && $value[2] == 'Friday')
						{
							$months['October'][$onlyD] = ['date' => $value[0],'day' => $day,'s1'=> 'OFF'];
						}
						else
						{
							$months['October'][$onlyD] = ['date' => $value[0],'day' => $day,'s1'=> $value[3]];
						}
					}
				}
				else if($day == 'Saturday')
				{	
					if (in_array($value[0], $pubDate))
					{
						$months['October'][$onlyD] = ['date' => $value[0],'day' => $day,'s1'=> 'PUB'];
					}
					else
					{
						if($value[1] == '0' && $value[2] == 'Saturday')
						{
							$months['October'][$onlyD] = ['date' => $value[0],'day' => $day,'s1'=> 'OFF'];
						}
						else
						{
							$months['October'][$onlyD] = ['date' => $value[0],'day' => $day,'s1'=> $value[3]];
						}
					}
				}
				else if($day == 'Sunday')
				{	
					if (in_array($value[0], $pubDate))
					{
						$months['October'][$onlyD] = ['date' => $value[0],'day' => $day,'s1'=> 'PUB'];
					}
					else
					{
						if($value[1] == '0' && $value[2] == 'Sunday')
						{
							$months['October'][$onlyD] = ['date' => $value[0],'day' => $day,'s1'=> 'OFF'];
						}
						else
						{
							$months['October'][$onlyD] = ['date' => $value[0],'day' => $day,'s1'=> $value[3]];
						}
					}
				}
			}			
			else if($month == 'November')
			{	
				if($day == 'Monday')
				{	
					if (in_array($value[0], $pubDate))
					{
						$months['November'][$onlyD] = ['date' => $value[0],'day' => $day,'s1'=> 'PUB'];
					}
					else
					{
						if($value[1] == '0' && $value[2] == 'Monday')
						{
							$months['November'][$onlyD] = ['date' => $value[0],'day' => $day,'s1'=> 'OFF'];
						}
						else
						{
							$months['November'][$onlyD] = ['date' => $value[0],'day' => $day,'s1'=> $value[3]];
						}
					}					
				
				}				
				else if($day == 'Tuesday')
				{	
					if (in_array($value[0], $pubDate))
					{
						$months['November'][$onlyD] = ['date' => $value[0],'day' => $day,'s1'=> 'PUB'];
					}
					else
					{
						if($value[1] == '0' && $value[2] == 'Tuesday')
						{
							$months['November'][$onlyD] = ['date' => $value[0],'day' => $day,'s1'=> 'OFF'];
						}
						else
						{
							$months['November'][$onlyD] = ['date' => $value[0],'day' => $day,'s1'=> $value[3]];
						}
					}
				}				
				else if($day == 'Wednesday')
				{	
					if (in_array($value[0], $pubDate))
					{
						$months['November'][$onlyD] = ['date' => $value[0],'day' => $day,'s1'=> 'PUB'];
					}
					else
					{
						if($value[1] == '0' && $value[2] == 'Wednesday')
						{
							$months['November'][$onlyD] = ['date' => $value[0],'day' => $day,'s1'=> 'OFF'];
						}
						else
						{
							$months['November'][$onlyD] = ['date' => $value[0],'day' => $day,'s1'=> $value[3]];
						}
					}
				}				
				else if($day == 'Thursday')
				{	
					if (in_array($value[0], $pubDate))
					{
						$months['November'][$onlyD] = ['date' => $value[0],'day' => $day,'s1'=> 'PUB'];
					}
					else
					{
						if($value[1] == '0' && $value[2] == 'Thursday')
						{
							$months['November'][$onlyD] = ['date' => $value[0],'day' => $day,'s1'=> 'OFF'];
						}
						else
						{
							$months['November'][$onlyD] = ['date' => $value[0],'day' => $day,'s1'=> $value[3]];
						}
					}
				}			
				else if($day == 'Friday')
				{	
					if (in_array($value[0], $pubDate))
					{
						$months['November'][$onlyD] = ['date' => $value[0],'day' => $day,'s1'=> 'PUB'];
					}
					else
					{
						if($value[1] == '0' && $value[2] == 'Friday')
						{
							$months['November'][$onlyD] = ['date' => $value[0],'day' => $day,'s1'=> 'OFF'];
						}
						else
						{
							$months['November'][$onlyD] = ['date' => $value[0],'day' => $day,'s1'=> $value[3]];
						}
					}
				}
				else if($day == 'Saturday')
				{	
					if (in_array($value[0], $pubDate))
					{
						$months['November'][$onlyD] = ['date' => $value[0],'day' => $day,'s1'=> 'PUB'];
					}
					else
					{
						if($value[1] == '0' && $value[2] == 'Saturday')
						{
							$months['November'][$onlyD] = ['date' => $value[0],'day' => $day,'s1'=> 'OFF'];
						}
						else
						{
							$months['November'][$onlyD] = ['date' => $value[0],'day' => $day,'s1'=> $value[3]];
						}
					}
				}
				else if($day == 'Sunday')
				{	
					if (in_array($value[0], $pubDate))
					{
						$months['November'][$onlyD] = ['date' => $value[0],'day' => $day,'s1'=> 'PUB'];
					}
					else
					{
						if($value[1] == '0' && $value[2] == 'Sunday')
						{
							$months['November'][$onlyD] = ['date' => $value[0],'day' => $day,'s1'=> 'OFF'];
						}
						else
						{
							$months['November'][$onlyD] = ['date' => $value[0],'day' => $day,'s1'=> $value[3]];
						}
					}
				}
			}			
			else if($month == 'December')
			{	
				if($day == 'Monday')
				{	
					if (in_array($value[0], $pubDate))
					{
						$months['December'][$onlyD] = ['date' => $value[0],'day' => $day,'s1'=> 'PUB'];
					}
					else
					{
						if($value[1] == '0' && $value[2] == 'Monday')
						{
							$months['December'][$onlyD] = ['date' => $value[0],'day' => $day,'s1'=> 'OFF'];
						}
						else
						{
							$months['December'][$onlyD] = ['date' => $value[0],'day' => $day,'s1'=> $value[3]];
						}
					}					
				
				}				
				else if($day == 'Tuesday')
				{	
					if (in_array($value[0], $pubDate))
					{
						$months['December'][$onlyD] = ['date' => $value[0],'day' => $day,'s1'=> 'PUB'];
					}
					else
					{
						if($value[1] == '0' && $value[2] == 'Tuesday')
						{
							$months['December'][$onlyD] = ['date' => $value[0],'day' => $day,'s1'=> 'OFF'];
						}
						else
						{
							$months['December'][$onlyD] = ['date' => $value[0],'day' => $day,'s1'=> $value[3]];
						}
					}
				}				
				else if($day == 'Wednesday')
				{	
					if (in_array($value[0], $pubDate))
					{
						$months['December'][$onlyD] = ['date' => $value[0],'day' => $day,'s1'=> 'PUB'];
					}
					else
					{
						if($value[1] == '0' && $value[2] == 'Wednesday')
						{
							$months['December'][$onlyD] = ['date' => $value[0],'day' => $day,'s1'=> 'OFF'];
						}
						else
						{
							$months['December'][$onlyD] = ['date' => $value[0],'day' => $day,'s1'=> $value[3]];
						}
					}
				}				
				else if($day == 'Thursday')
				{	
					if (in_array($value[0], $pubDate))
					{
						$months['December'][$onlyD] = ['date' => $value[0],'day' => $day,'s1'=> 'PUB'];
					}
					else
					{
						if($value[1] == '0' && $value[2] == 'Thursday')
						{
							$months['December'][$onlyD] = ['date' => $value[0],'day' => $day,'s1'=> 'OFF'];
						}
						else
						{
							$months['December'][$onlyD] = ['date' => $value[0],'day' => $day,'s1'=> $value[3]];
						}
					}
				}			
				else if($day == 'Friday')
				{	
					if (in_array($value[0], $pubDate))
					{
						$months['December'][$onlyD] = ['date' => $value[0],'day' => $day,'s1'=> 'PUB'];
					}
					else
					{
						if($value[1] == '0' && $value[2] == 'Friday')
						{
							$months['December'][$onlyD] = ['date' => $value[0],'day' => $day,'s1'=> 'OFF'];
						}
						else
						{
							$months['December'][$onlyD] = ['date' => $value[0],'day' => $day,'s1'=> $value[3]];
						}
					}
				}
				else if($day == 'Saturday')
				{	
					if (in_array($value[0], $pubDate))
					{
						$months['December'][$onlyD] = ['date' => $value[0],'day' => $day,'s1'=> 'PUB'];
					}
					else
					{
						if($value[1] == '0' && $value[2] == 'Saturday')
						{
							$months['December'][$onlyD] = ['date' => $value[0],'day' => $day,'s1'=> 'OFF'];
						}
						else
						{
							$months['December'][$onlyD] = ['date' => $value[0],'day' => $day,'s1'=> $value[3]];
						}
					}
				}
				else if($day == 'Sunday')
				{	
					if (in_array($value[0], $pubDate))
					{
						$months['December'][$onlyD] = ['date' => $value[0],'day' => $day,'s1'=> 'PUB'];
					}
					else
					{
						if($value[1] == '0' && $value[2] == 'Sunday')
						{
							$months['December'][$onlyD] = ['date' => $value[0],'day' => $day,'s1'=> 'OFF'];
						}
						else
						{
							$months['December'][$onlyD] = ['date' => $value[0],'day' => $day,'s1'=> $value[3]];
						}
					}
				}
			}

	}		




	foreach ($months as $key2 => $value2) {

		if($key2 == 'January')
		{
			foreach ($value2 as $key3 => $value3) 
			{
				if($value3['s1'] == 'PUB')
				{
					$pubJanDay[] = $value3['s1'];
				}
				else if( $value3['s1'] == 'OFF')
				{
					$offJanDay[] = $value3['s1'];
				}
				else
				{
					$totJanDay[] = $value3['s1'];
				}
			}
		}		
		else if($key2 == 'February')
		{
			foreach ($value2 as $key3 => $value3) 
			{
				if($value3['s1'] == 'PUB')
				{
					$pubFebDay[] = $value3['s1'];
				}
				else if( $value3['s1'] == 'OFF')
				{
					$offFebDay[] = $value3['s1'];
				}
				else
				{
					$totFebDay[] = $value3['s1'];
				}
			}
		}		
		else if($key2 == 'March')
		{
			foreach ($value2 as $key3 => $value3) 
			{
				if($value3['s1'] == 'PUB')
				{
					$pubMarDay[] = $value3['s1'];
				}
				else if( $value3['s1'] == 'OFF')
				{
					$offMarDay[] = $value3['s1'];
				}
				else
				{
					$totMarDay[] = $value3['s1'];
				}
			}
		}				
		else if($key2 == 'April')
		{
			foreach ($value2 as $key3 => $value3) 
			{
				if($value3['s1'] == 'PUB')
				{
					$pubAprDay[] = $value3['s1'];
				}
				else if( $value3['s1'] == 'OFF')
				{
					$offAprDay[] = $value3['s1'];
				}
				else
				{
					$totAprDay[] = $value3['s1'];
				}
			}
		}		
		else if($key2 == 'May')
		{
			foreach ($value2 as $key3 => $value3) 
			{
				if($value3['s1'] == 'PUB')
				{
					$pubMayDay[] = $value3['s1'];
				}
				else if( $value3['s1'] == 'OFF')
				{
					$offMayDay[] = $value3['s1'];
				}
				else
				{
					$totMayDay[] = $value3['s1'];
				}
			}
		}		
		else if($key2 == 'June')
		{
			foreach ($value2 as $key3 => $value3) 
			{
				if($value3['s1'] == 'PUB')
				{
					$pubJunDay[] = $value3['s1'];
				}
				else if( $value3['s1'] == 'OFF')
				{
					$offJunDay[] = $value3['s1'];
				}
				else
				{
					$totJunDay[] = $value3['s1'];
				}
			}
		}				
		else if($key2 == 'July')
		{
			foreach ($value2 as $key3 => $value3) 
			{
				if($value3['s1'] == 'PUB')
				{
					$pubJulDay[] = $value3['s1'];
				}
				else if( $value3['s1'] == 'OFF')
				{
					$offJulDay[] = $value3['s1'];
				}
				else
				{
					$totJulDay[] = $value3['s1'];
				}
			}
		}		
		else if($key2 == 'August')
		{
			foreach ($value2 as $key3 => $value3) 
			{
				if($value3['s1'] == 'PUB')
				{
					$pubAugDay[] = $value3['s1'];
				}
				else if( $value3['s1'] == 'OFF')
				{
					$offAugDay[] = $value3['s1'];
				}
				else
				{
					$totAugDay[] = $value3['s1'];
				}
			}
		}		
		else if($key2 == 'September')
		{
			foreach ($value2 as $key3 => $value3) 
			{
				if($value3['s1'] == 'PUB')
				{
					$pubSepDay[] = $value3['s1'];
				}
				else if( $value3['s1'] == 'OFF')
				{
					$offSepDay[] = $value3['s1'];
				}
				else
				{
					$totSepDay[] = $value3['s1'];
				}
			}
		}		
		else if($key2 == 'October')
		{
			foreach ($value2 as $key3 => $value3) 
			{
				if($value3['s1'] == 'PUB')
				{
					$pubOctDay[] = $value3['s1'];
				}
				else if( $value3['s1'] == 'OFF')
				{
					$offOctDay[] = $value3['s1'];
				}
				else
				{
					$totOctDay[] = $value3['s1'];
				}
			}
		}		
		else if($key2 == 'November')
		{
			foreach ($value2 as $key3 => $value3) 
			{
				if($value3['s1'] == 'PUB')
				{
					$pubNovDay[] = $value3['s1'];
				}
				else if( $value3['s1'] == 'OFF')
				{
					$offNovDay[] = $value3['s1'];
				}
				else
				{
					$totNovDay[] = $value3['s1'];
				}
			}
		}		
		else if($key2 == 'December')
		{
			foreach ($value2 as $key3 => $value3) 
			{
				if($value3['s1'] == 'PUB')
				{
					$pubDecDay[] = $value3['s1'];
				}
				else if( $value3['s1'] == 'OFF')
				{
					$offDecDay[] = $value3['s1'];
				}
				else
				{
					$totDecDay[] = $value3['s1'];
				}
			}
		}
	}

	
	$workingDaysArray['January']  	 = ['wkd' =>count($totJanDay) , 'pub' =>count($pubJanDay) , 'off' => count($offJanDay)];
	$workingDaysArray['February'] 	 = ['wkd' =>count($totFebDay) , 'pub' =>count($pubFebDay) , 'off' => count($offFebDay)];
	$workingDaysArray['March']    	 = ['wkd' =>count($totMarDay) , 'pub' =>count($pubMarDay) , 'off' => count($offMarDay)];
	$workingDaysArray['April']    	 = ['wkd' =>count($totAprDay) , 'pub' =>count($pubAprDay) , 'off' => count($offAprDay)];
	$workingDaysArray['May']      	 = ['wkd' =>count($totMayDay) , 'pub' =>count($pubMayDay) , 'off' => count($offMayDay)];
	$workingDaysArray['June']     	 = ['wkd' =>count($totJunDay) , 'pub' =>count($pubJunDay) , 'off' => count($offJunDay)];
	$workingDaysArray['July']     	 = ['wkd' =>count($totJulDay) , 'pub' =>count($pubJulDay) , 'off' => count($offJulDay)];
	$workingDaysArray['August']   	 = ['wkd' =>count($totAugDay) , 'pub' =>count($pubAugDay) , 'off' => count($offAugDay)];
	$workingDaysArray['September']   = ['wkd' =>count($totSepDay) , 'pub' =>count($pubSepDay) , 'off' => count($offSepDay)];
	$workingDaysArray['October']     = ['wkd' =>count($totOctDay) , 'pub' =>count($pubOctDay) , 'off' => count($offOctDay)];
	$workingDaysArray['November']    = ['wkd' =>count($totNovDay) , 'pub' =>count($pubNovDay) , 'off' => count($offNovDay)];
	$workingDaysArray['December']    = ['wkd' =>count($totDecDay) , 'pub' =>count($pubDecDay) , 'off' => count($offDecDay)];





	// echo '<pre>';
	// print_r($workingDaysArray);
	// echo '</pre>';


	// die();

	// echo '<pre>';
	// print_r($months);
	// echo '</pre>';

	// die();





	$sql = "UPDATE rego_default_shiftplans SET cycle_details = '".$dba->real_escape_string(serialize($months))."', wh_code = '".$_POST['shift_schedule1']."' ,dates = '".$dba->real_escape_string(serialize($months))."', wkd = '".$dba->real_escape_string(serialize($workingDaysArray))."' WHERE id= '".$_POST['hidden_code_id']."'";

	ob_clean();	
	if($dba->query($sql)){
		echo 'success';
	}else{
		echo mysqli_error($dba);
	}

	exit;
	
?>














