<?
	if(session_id()==''){session_start();}
	ob_start();
	include("../../dbconnect/db_connect.php");

	// yes 
	// get the detail from previous year and save for the plan and run below query

	// no 

	if($_POST['shift_schedule1'] != 'select')
	{
		 $shift_schedule1 = $_POST['shift_schedule1'];
	}	
	if($_POST['shift_schedule2'] != 'select')
	{
		 $shift_schedule2 = $_POST['shift_schedule2'];
	}	

	
	$variableOffDaysVal = $_POST['variableOffDaysVal'];


	

	$youroff = strtoupper($_POST['youroff']);
	$yourschedule = $_POST['yourschedule'];

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

	$pubDate = array();
	$sql3 = "SELECT * FROM ".$cid."_holidays  WHERE year= '".$cur_year."'";
	if($res3 = $dbc->query($sql3)){
		while($row3 = $res3->fetch_assoc()){
			$pubDate[] = $row3['cdate'];
		}
	}

	$day = array();
	$months =array();


	$firstDay    = date('Y-m-d', strtotime('first day of january')); // first date


	// get dates between first day and start day of cycle 
	$betweenArray = getDatesFromRange($firstDay, $startdate);
	// echo '<pre>';
	// print_r($betweenArray);
	// echo '</pre>';

	// die();

	foreach ($betweenArray as $key => $value) {
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


	foreach ($test as $key => $value) {
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
				else
				{
					if($_POST['Monday'] == '0')
					{
						$months['January'][$onlyD] = ['date' => $value,'day' => $day,'s1'=> $youroff];
					}
					else if($_POST['Monday'] == '1')
					{
						$months['January'][$onlyD] = ['date' => $value,'day' => $day,'s1'=> $yourschedule];

					}
					else
					{
						$months['January'][$onlyD] = ['date' => $value,'day' => $day,'s1'=> '-'];
					}
				}
				
			}
			else if($day == 'Tuesday')
			{
				if (in_array($value, $pubDate))
				{
					$months['January'][$onlyD] = ['date' => $value,'day' => $day,'s1'=> 'PUB'];
				}
				else
				{
					if($_POST['Tuesday'] == '0')
					{
						$months['January'][$onlyD] = ['date' => $value,'day' => $day,'s1'=> $youroff];
					}
					else if($_POST['Tuesday'] == '1')
					{
						$months['January'][$onlyD] = ['date' => $value,'day' => $day,'s1'=> $yourschedule];

					}
					else
					{
						$months['January'][$onlyD] = ['date' => $value,'day' => $day,'s1'=> '-'];
					}
				}
				
			}			

			else if($day == 'Wednesday')
			{
				if (in_array($value, $pubDate))
				{
					$months['January'][$onlyD] = ['date' => $value,'day' => $day,'s1'=> 'PUB'];
				}
				else
				{
					if($_POST['Wednesday'] == '0')
					{
						$months['January'][$onlyD] = ['date' => $value,'day' => $day,'s1'=> $youroff];
					}
					else if($_POST['Wednesday'] == '1')
					{
						$months['January'][$onlyD] = ['date' => $value,'day' => $day,'s1'=> $yourschedule];

					}
					else
					{
						$months['January'][$onlyD] = ['date' => $value,'day' => $day,'s1'=> '-'];

					}
				}
				
			}			

			else if($day == 'Thursday')
			{
				if (in_array($value, $pubDate))
				{
					$months['January'][$onlyD] = ['date' => $value,'day' => $day,'s1'=> 'PUB'];
				}
				else
				{
					if($_POST['Thursday'] == '0')
					{
						$months['January'][$onlyD] = ['date' => $value,'day' => $day,'s1'=> $youroff];
					}
					else if($_POST['Thursday'] == '1')
					{
						$months['January'][$onlyD] = ['date' => $value,'day' => $day,'s1'=> $yourschedule];

					}
					else
					{
						$months['January'][$onlyD] = ['date' => $value,'day' => $day,'s1'=> '-'];

					}
				}
				
			}			
			else if($day == 'Friday')
			{
				if (in_array($value, $pubDate))
				{
					$months['January'][$onlyD] = ['date' => $value,'day' => $day,'s1'=> 'PUB'];
				}
				else
				{
					if($_POST['Friday'] == '0')
					{
						$months['January'][$onlyD] = ['date' => $value,'day' => $day,'s1'=> $youroff];
					}
					else if($_POST['Friday'] == '1')
					{
						$months['January'][$onlyD] = ['date' => $value,'day' => $day,'s1'=> $yourschedule];

					}
					else
					{
						$months['January'][$onlyD] = ['date' => $value,'day' => $day,'s1'=> '-'];

					}
				}
				
			}			
			else if($day == 'Saturday')
			{
				if (in_array($value, $pubDate))
				{
					$months['January'][$onlyD] = ['date' => $value,'day' => $day,'s1'=> 'PUB'];
				}
				else
				{
					if($_POST['Saturday'] == '0')
					{
						$months['January'][$onlyD] = ['date' => $value,'day' => $day,'s1'=> $youroff];
					}
					else if($_POST['Saturday'] == '1')
					{
						$months['January'][$onlyD] = ['date' => $value,'day' => $day,'s1'=> $yourschedule];

					}
					else
					{
						$months['January'][$onlyD] = ['date' => $value,'day' => $day,'s1'=> '-'];

					}
				}
				
			}			
			else if($day == 'Sunday')
			{
				if (in_array($value, $pubDate))
				{
					$months['January'][$onlyD] = ['date' => $value,'day' => $day,'s1'=> 'PUB'];
				}
				else
				{
					if($_POST['Sunday'] == '0')
					{
						$months['January'][$onlyD] = ['date' => $value,'day' => $day,'s1'=> $youroff];
					}
					else if($_POST['Sunday'] == '1')
					{
						$months['January'][$onlyD] = ['date' => $value,'day' => $day,'s1'=> $yourschedule];

					}
					else
					{
						$months['January'][$onlyD] = ['date' => $value,'day' => $day,'s1'=> '-'];

					}
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
				else
				{
					if($_POST['Monday'] == '0')
					{
						$months['February'][$onlyD] = ['date' => $value,'day' => $day,'s1'=> $youroff];
					}
					else if($_POST['Monday'] == '1')
					{
						$months['February'][$onlyD] = ['date' => $value,'day' => $day,'s1'=> $yourschedule];

					}
					else
					{
						$months['February'][$onlyD] = ['date' => $value,'day' => $day,'s1'=> '-'];

					}
				}
				
			}			
			else if($day == 'Tuesday')
			{
				if (in_array($value, $pubDate))
				{
					$months['February'][$onlyD] = ['date' => $value,'day' => $day,'s1'=> 'PUB'];
				}
				else
				{
					if($_POST['Tuesday'] == '0')
					{
						$months['February'][$onlyD] = ['date' => $value,'day' => $day,'s1'=> $youroff];
					}
					else if($_POST['Tuesday'] == '1')
					{
						$months['February'][$onlyD] = ['date' => $value,'day' => $day,'s1'=> $yourschedule];

					}
					else
					{
						$months['February'][$onlyD] = ['date' => $value,'day' => $day,'s1'=> '-'];
					}
				}
				
			}			
			else if($day == 'Wednesday')
			{
				if (in_array($value, $pubDate))
				{
					$months['February'][$onlyD] = ['date' => $value,'day' => $day,'s1'=> 'PUB'];
				}
				else
				{
					if($_POST['Wednesday'] == '0')
					{
						$months['February'][$onlyD] = ['date' => $value,'day' => $day,'s1'=> $youroff];
					}
					else if($_POST['Wednesday'] == '1')
					{
						$months['February'][$onlyD] = ['date' => $value,'day' => $day,'s1'=> $yourschedule];

					}
					else
					{
						$months['February'][$onlyD] = ['date' => $value,'day' => $day,'s1'=> '-'];
					}
				}
				
			}			
			else if($day == 'Thursday')
			{
				if (in_array($value, $pubDate))
				{
					$months['February'][$onlyD] = ['date' => $value,'day' => $day,'s1'=> 'PUB'];
				}
				else
				{
					if($_POST['Thursday'] == '0')
					{
						$months['February'][$onlyD] = ['date' => $value,'day' => $day,'s1'=> $youroff];
					}
					else if($_POST['Thursday'] == '1')
					{
						$months['February'][$onlyD] = ['date' => $value,'day' => $day,'s1'=> $yourschedule];

					}
					else
					{
						$months['February'][$onlyD] = ['date' => $value,'day' => $day,'s1'=> '-'];

					}
				}
				
			}			

			else if($day == 'Friday')
			{
				if (in_array($value, $pubDate))
				{
					$months['February'][$onlyD] = ['date' => $value,'day' => $day,'s1'=> 'PUB'];
				}
				else
				{
					if($_POST['Friday'] == '0')
					{
						$months['February'][$onlyD] = ['date' => $value,'day' => $day,'s1'=> $youroff];
					}
					else if($_POST['Friday'] == '1')
					{
						$months['February'][$onlyD] = ['date' => $value,'day' => $day,'s1'=> $yourschedule];

					}
					else
					{
						$months['February'][$onlyD] = ['date' => $value,'day' => $day,'s1'=> '-'];

					}
				}
				
			}			

			else if($day == 'Saturday')
			{
				if (in_array($value, $pubDate))
				{
					$months['February'][$onlyD] = ['date' => $value,'day' => $day,'s1'=> 'PUB'];
				}
				else
				{
					if($_POST['Saturday'] == '0')
					{
						$months['February'][$onlyD] = ['date' => $value,'day' => $day,'s1'=> $youroff];
					}
					else if($_POST['Saturday'] == '1')
					{
						$months['February'][$onlyD] = ['date' => $value,'day' => $day,'s1'=> $yourschedule];

					}
					else
					{
						$months['February'][$onlyD] = ['date' => $value,'day' => $day,'s1'=> '-'];

					}
				}
				
			}			
			else if($day == 'Sunday')
			{
				if (in_array($value, $pubDate))
				{
					$months['February'][$onlyD] = ['date' => $value,'day' => $day,'s1'=> 'PUB'];
				}
				else
				{
					if($_POST['Sunday'] == '0')
					{
						$months['February'][$onlyD] = ['date' => $value,'day' => $day,'s1'=> $youroff];
					}
					else if($_POST['Sunday'] == '1')
					{
						$months['February'][$onlyD] = ['date' => $value,'day' => $day,'s1'=> $yourschedule];

					}
					else
					{
						$months['February'][$onlyD] = ['date' => $value,'day' => $day,'s1'=> '-'];

					}
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
				else
				{

					if($_POST['Monday'] == '0')
					{
						$months['March'][$onlyD] = ['date' => $value,'day' => $day,'s1'=> $youroff];
					}
					else if($_POST['Monday'] == '1')
					{
						$months['March'][$onlyD] = ['date' => $value,'day' => $day,'s1'=> $yourschedule];

					}
					else
					{
						$months['March'][$onlyD] = ['date' => $value,'day' => $day,'s1'=> '-'];

					}
				}
				
			}			
			else if($day == 'Tuesday')
			{
				if (in_array($value, $pubDate))
				{
					$months['March'][$onlyD] = ['date' => $value,'day' => $day,'s1'=> 'PUB'];
				}
				else
				{
					if($_POST['Tuesday'] == '0')
					{
						$months['March'][$onlyD] = ['date' => $value,'day' => $day,'s1'=> $youroff];
					}
					else if($_POST['Tuesday'] == '1')
					{
						$months['March'][$onlyD] = ['date' => $value,'day' => $day,'s1'=> $yourschedule];

					}
					else
					{
						$months['March'][$onlyD] = ['date' => $value,'day' => $day,'s1'=> '-'];

					}
				}
				
			}			
			else if($day == 'Wednesday')
			{
				if (in_array($value, $pubDate))
				{
					$months['March'][$onlyD] = ['date' => $value,'day' => $day,'s1'=> 'PUB'];
				}
				else
				{
					if($_POST['Wednesday'] == '0')
					{
						$months['March'][$onlyD] = ['date' => $value,'day' => $day,'s1'=> $youroff];
					}
					else if($_POST['Wednesday'] == '1')
					{
						$months['March'][$onlyD] = ['date' => $value,'day' => $day,'s1'=> $yourschedule];

					}
					else
					{
						$months['March'][$onlyD] = ['date' => $value,'day' => $day,'s1'=> '-'];

					}
				}
				
			}			
			else if($day == 'Thursday')
			{
				if (in_array($value, $pubDate))
				{
					$months['March'][$onlyD] = ['date' => $value,'day' => $day,'s1'=> 'PUB'];
				}
				else
				{
					if($_POST['Thursday'] == '0')
					{
						$months['March'][$onlyD] = ['date' => $value,'day' => $day,'s1'=> $youroff];
					}
					else if($_POST['Thursday'] == '1')
					{
						$months['March'][$onlyD] = ['date' => $value,'day' => $day,'s1'=> $yourschedule];

					}
					else
					{
						$months['March'][$onlyD] = ['date' => $value,'day' => $day,'s1'=> '-'];

					}
				}
				
			}			

			else if($day == 'Friday')
			{
				if (in_array($value, $pubDate))
				{
					$months['March'][$onlyD] = ['date' => $value,'day' => $day,'s1'=> 'PUB'];
				}
				else
				{
					if($_POST['Friday'] == '0')
					{
						$months['March'][$onlyD] = ['date' => $value,'day' => $day,'s1'=> $youroff];
					}
					else if($_POST['Friday'] == '1')
					{
						$months['March'][$onlyD] = ['date' => $value,'day' => $day,'s1'=> $yourschedule];

					}
					else
					{
						$months['March'][$onlyD] = ['date' => $value,'day' => $day,'s1'=> '-'];

					}
				}
				
			}			

			else if($day == 'Saturday')
			{
				if (in_array($value, $pubDate))
				{
					$months['March'][$onlyD] = ['date' => $value,'day' => $day,'s1'=> 'PUB'];
				}
				else
				{
					if($_POST['Saturday'] == '0')
					{
						$months['March'][$onlyD] = ['date' => $value,'day' => $day,'s1'=> $youroff];
					}
					else if($_POST['Saturday'] == '1')
					{
						$months['March'][$onlyD] = ['date' => $value,'day' => $day,'s1'=> $yourschedule];

					}
					else
					{
						$months['March'][$onlyD] = ['date' => $value,'day' => $day,'s1'=> '-'];

					}
				}
				
			}			
			else if($day == 'Sunday')
			{
				if (in_array($value, $pubDate))
				{
					$months['March'][$onlyD] = ['date' => $value,'day' => $day,'s1'=> 'PUB'];
				}
				else
				{
					if($_POST['Sunday'] == '0')
					{
						$months['March'][$onlyD] = ['date' => $value,'day' => $day,'s1'=> $youroff];
					}
					else if($_POST['Sunday'] == '1')
					{
						$months['March'][$onlyD] = ['date' => $value,'day' => $day,'s1'=> $yourschedule];

					}
					else
					{
						$months['March'][$onlyD] = ['date' => $value,'day' => $day,'s1'=> '-'];

					}
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
				else
				{
					if($_POST['Monday'] == '0')
					{
						$months['April'][$onlyD] = ['date' => $value,'day' => $day,'s1'=> $youroff];
					}
					else if($_POST['Monday'] == '1')
					{
						$months['April'][$onlyD] = ['date' => $value,'day' => $day,'s1'=> $yourschedule];

					}
					else
					{
						$months['April'][$onlyD] = ['date' => $value,'day' => $day,'s1'=> '-'];

					}
				}
				
			}			
			else if($day == 'Tuesday')
			{
				if (in_array($value, $pubDate))
				{
					$months['April'][$onlyD] = ['date' => $value,'day' => $day,'s1'=> 'PUB'];
				}
				else
				{
					if($_POST['Tuesday'] == '0')
					{
						$months['April'][$onlyD] = ['date' => $value,'day' => $day,'s1'=> $youroff];
					}
					else if($_POST['Tuesday'] == '1')
					{
						$months['April'][$onlyD] = ['date' => $value,'day' => $day,'s1'=> $yourschedule];

					}
					else
					{
						$months['April'][$onlyD] = ['date' => $value,'day' => $day,'s1'=> '-'];

					}
				}
				
			}			
			else if($day == 'Wednesday')
			{
				if (in_array($value, $pubDate))
				{
					$months['April'][$onlyD] = ['date' => $value,'day' => $day,'s1'=> 'PUB'];
				}
				else
				{
					if($_POST['Wednesday'] == '0')
					{
						$months['April'][$onlyD] = ['date' => $value,'day' => $day,'s1'=> $youroff];
					}
					else if($_POST['Wednesday'] == '1')
					{
						$months['April'][$onlyD] = ['date' => $value,'day' => $day,'s1'=> $yourschedule];

					}
					else
					{
						$months['April'][$onlyD] = ['date' => $value,'day' => $day,'s1'=> '-'];

					}
				}
				
			}			
			else if($day == 'Thursday')
			{
				if (in_array($value, $pubDate))
				{
					$months['April'][$onlyD] = ['date' => $value,'day' => $day,'s1'=> 'PUB'];
				}
				else
				{
					if($_POST['Thursday'] == '0')
					{
						$months['April'][$onlyD] = ['date' => $value,'day' => $day,'s1'=> $youroff];
					}
					else if($_POST['Thursday'] == '1')
					{
						$months['April'][$onlyD] = ['date' => $value,'day' => $day,'s1'=> $yourschedule];

					}
					else
					{
						$months['April'][$onlyD] = ['date' => $value,'day' => $day,'s1'=> '-'];

					}
				}
				
			}			

			else if($day == 'Friday')
			{
				if (in_array($value, $pubDate))
				{
					$months['April'][$onlyD] = ['date' => $value,'day' => $day,'s1'=> 'PUB'];
				}
				else
				{
					if($_POST['Friday'] == '0')
					{
						$months['April'][$onlyD] = ['date' => $value,'day' => $day,'s1'=> $youroff];
					}
					else if($_POST['Friday'] == '1')
					{
						$months['April'][$onlyD] = ['date' => $value,'day' => $day,'s1'=> $yourschedule];

					}
					else
					{
						$months['April'][$onlyD] = ['date' => $value,'day' => $day,'s1'=> '-'];

					}
				}
				
			}			

			else if($day == 'Saturday')
			{
				if (in_array($value, $pubDate))
				{
					$months['April'][$onlyD] = ['date' => $value,'day' => $day,'s1'=> 'PUB'];
				}
				else
				{
					if($_POST['Saturday'] == '0')
					{
						$months['April'][$onlyD] = ['date' => $value,'day' => $day,'s1'=> $youroff];
					}
					else if($_POST['Saturday'] == '1')
					{
						$months['April'][$onlyD] = ['date' => $value,'day' => $day,'s1'=> $yourschedule];

					}
					else
					{
						$months['April'][$onlyD] = ['date' => $value,'day' => $day,'s1'=> '-'];

					}
				}
				
			}			
			else if($day == 'Sunday')
			{
				if (in_array($value, $pubDate))
				{
					$months['April'][$onlyD] = ['date' => $value,'day' => $day,'s1'=> 'PUB'];
				}
				else
				{
					if($_POST['Sunday'] == '0')
					{
						$months['April'][$onlyD] = ['date' => $value,'day' => $day,'s1'=> $youroff];
					}
					else if($_POST['Sunday'] == '1')
					{
						$months['April'][$onlyD] = ['date' => $value,'day' => $day,'s1'=> $yourschedule];

					}
					else
					{
						$months['April'][$onlyD] = ['date' => $value,'day' => $day,'s1'=> '-'];

					}
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
				else
				{
					if($_POST['Monday'] == '0')
					{
						$months['May'][$onlyD] = ['date' => $value,'day' => $day,'s1'=> $youroff];
					}
					else if($_POST['Monday'] == '1')
					{
						$months['May'][$onlyD] = ['date' => $value,'day' => $day,'s1'=> $yourschedule];

					}
					else
					{
						$months['May'][$onlyD] = ['date' => $value,'day' => $day,'s1'=> '-'];

					}
				}
				
			}			
			else if($day == 'Tuesday')
			{
				if (in_array($value, $pubDate))
				{
					$months['May'][$onlyD] = ['date' => $value,'day' => $day,'s1'=> 'PUB'];
				}
				else
				{
					if($_POST['Tuesday'] == '0')
					{
						$months['May'][$onlyD] = ['date' => $value,'day' => $day,'s1'=> $youroff];
					}
					else if($_POST['Tuesday'] == '1')
					{
						$months['May'][$onlyD] = ['date' => $value,'day' => $day,'s1'=> $yourschedule];

					}
					else
					{
						$months['May'][$onlyD] = ['date' => $value,'day' => $day,'s1'=> '-'];

					}
				}
				
			}			
			else if($day == 'Wednesday')
			{
				if (in_array($value, $pubDate))
				{
					$months['May'][$onlyD] = ['date' => $value,'day' => $day,'s1'=> 'PUB'];
				}
				else
				{
					if($_POST['Wednesday'] == '0')
					{
						$months['May'][$onlyD] = ['date' => $value,'day' => $day,'s1'=> $youroff];
					}
					else if($_POST['Wednesday'] == '1')
					{
						$months['May'][$onlyD] = ['date' => $value,'day' => $day,'s1'=> $yourschedule];

					}
					else
					{
						$months['May'][$onlyD] = ['date' => $value,'day' => $day,'s1'=> '-'];

					}
				}
				
			}			
			else if($day == 'Thursday')
			{
				if (in_array($value, $pubDate))
				{
					$months['May'][$onlyD] = ['date' => $value,'day' => $day,'s1'=> 'PUB'];
				}
				else
				{
					if($_POST['Thursday'] == '0')
					{
						$months['May'][$onlyD] = ['date' => $value,'day' => $day,'s1'=> $youroff];
					}
					else if($_POST['Thursday'] == '1')
					{
						$months['May'][$onlyD] = ['date' => $value,'day' => $day,'s1'=> $yourschedule];

					}
					else
					{
						$months['May'][$onlyD] = ['date' => $value,'day' => $day,'s1'=> '-'];

					}
				}
				
			}			

			else if($day == 'Friday')
			{
				if (in_array($value, $pubDate))
				{
					$months['May'][$onlyD] = ['date' => $value,'day' => $day,'s1'=> 'PUB'];
				}
				else
				{
					if($_POST['Friday'] == '0')
					{
						$months['May'][$onlyD] = ['date' => $value,'day' => $day,'s1'=> $youroff];
					}
					else if($_POST['Friday'] == '1')
					{
						$months['May'][$onlyD] = ['date' => $value,'day' => $day,'s1'=> $yourschedule];

					}
					else
					{
						$months['May'][$onlyD] = ['date' => $value,'day' => $day,'s1'=> '-'];

					}
				}
				
			}			

			else if($day == 'Saturday')
			{
				if (in_array($value, $pubDate))
				{
					$months['May'][$onlyD] = ['date' => $value,'day' => $day,'s1'=> 'PUB'];
				}
				else
				{
					if($_POST['Saturday'] == '0')
					{
						$months['May'][$onlyD] = ['date' => $value,'day' => $day,'s1'=> $youroff];
					}
					else if($_POST['Saturday'] == '1')
					{
						$months['May'][$onlyD] = ['date' => $value,'day' => $day,'s1'=> $yourschedule];

					}
					else
					{
						$months['May'][$onlyD] = ['date' => $value,'day' => $day,'s1'=> '-'];

					}
				}
				
			}			
			else if($day == 'Sunday')
			{
				if (in_array($value, $pubDate))
				{
					$months['May'][$onlyD] = ['date' => $value,'day' => $day,'s1'=> 'PUB'];
				}
				else
				{
					if($_POST['Sunday'] == '0')
					{
						$months['May'][$onlyD] = ['date' => $value,'day' => $day,'s1'=> $youroff];
					}
					else if($_POST['Sunday'] == '1')
					{
						$months['May'][$onlyD] = ['date' => $value,'day' => $day,'s1'=> $yourschedule];

					}
					else
					{
						$months['May'][$onlyD] = ['date' => $value,'day' => $day,'s1'=> '-'];

					}
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
				else
				{
					if($_POST['Monday'] == '0')
					{
						$months['June'][$onlyD] = ['date' => $value,'day' => $day,'s1'=> $youroff];
					}
					else if($_POST['Monday'] == '1')
					{
						$months['June'][$onlyD] = ['date' => $value,'day' => $day,'s1'=> $yourschedule];

					}
					else
					{
						$months['June'][$onlyD] = ['date' => $value,'day' => $day,'s1'=> '-'];

					}
				}
				
			}			
			else if($day == 'Tuesday')
			{
				if (in_array($value, $pubDate))
				{
					$months['June'][$onlyD] = ['date' => $value,'day' => $day,'s1'=> 'PUB'];
				}
				else
				{
					if($_POST['Tuesday'] == '0')
					{
						$months['June'][$onlyD] = ['date' => $value,'day' => $day,'s1'=> $youroff];
					}
					else if($_POST['Tuesday'] == '1')
					{
						$months['June'][$onlyD] = ['date' => $value,'day' => $day,'s1'=> $yourschedule];

					}
					else
					{
						$months['June'][$onlyD] = ['date' => $value,'day' => $day,'s1'=> '-'];

					}
				}
				
			}			
			else if($day == 'Wednesday')
			{
				if (in_array($value, $pubDate))
				{
					$months['June'][$onlyD] = ['date' => $value,'day' => $day,'s1'=> 'PUB'];
				}
				else
				{
					if($_POST['Wednesday'] == '0')
					{
						$months['June'][$onlyD] = ['date' => $value,'day' => $day,'s1'=> $youroff];
					}
					else if($_POST['Wednesday'] == '1')
					{
						$months['June'][$onlyD] = ['date' => $value,'day' => $day,'s1'=> $yourschedule];

					}
					else
					{
						$months['June'][$onlyD] = ['date' => $value,'day' => $day,'s1'=> '-'];

					}
				}
				
			}			
			else if($day == 'Thursday')
			{
				if (in_array($value, $pubDate))
				{
					$months['June'][$onlyD] = ['date' => $value,'day' => $day,'s1'=> 'PUB'];
				}
				else
				{
					if($_POST['Thursday'] == '0')
					{
						$months['June'][$onlyD] = ['date' => $value,'day' => $day,'s1'=> $youroff];
					}
					else if($_POST['Thursday'] == '1')
					{
						$months['June'][$onlyD] = ['date' => $value,'day' => $day,'s1'=> $yourschedule];

					}
					else
					{
						$months['June'][$onlyD] = ['date' => $value,'day' => $day,'s1'=> '-'];

					}
				}
				
			}			

			else if($day == 'Friday')
			{
				if (in_array($value, $pubDate))
				{
					$months['June'][$onlyD] = ['date' => $value,'day' => $day,'s1'=> 'PUB'];
				}
				else
				{
					if($_POST['Friday'] == '0')
					{
						$months['June'][$onlyD] = ['date' => $value,'day' => $day,'s1'=> $youroff];
					}
					else if($_POST['Friday'] == '1')
					{
						$months['June'][$onlyD] = ['date' => $value,'day' => $day,'s1'=> $yourschedule];

					}
					else
					{
						$months['June'][$onlyD] = ['date' => $value,'day' => $day,'s1'=> '-'];

					}
				}
				
			}			

			else if($day == 'Saturday')
			{
				if (in_array($value, $pubDate))
				{
					$months['June'][$onlyD] = ['date' => $value,'day' => $day,'s1'=> 'PUB'];
				}
				else
				{
					if($_POST['Saturday'] == '0')
					{
						$months['June'][$onlyD] = ['date' => $value,'day' => $day,'s1'=> $youroff];
					}
					else if($_POST['Saturday'] == '1')
					{
						$months['June'][$onlyD] = ['date' => $value,'day' => $day,'s1'=> $yourschedule];

					}
					else
					{
						$months['June'][$onlyD] = ['date' => $value,'day' => $day,'s1'=> '-'];

					}
				}
				
			}			
			else if($day == 'Sunday')
			{
				if (in_array($value, $pubDate))
				{
					$months['June'][$onlyD] = ['date' => $value,'day' => $day,'s1'=> 'PUB'];
				}
				else
				{
					if($_POST['Sunday'] == '0')
					{
						$months['June'][$onlyD] = ['date' => $value,'day' => $day,'s1'=> $youroff];
					}
					else if($_POST['Sunday'] == '1')
					{
						$months['June'][$onlyD] = ['date' => $value,'day' => $day,'s1'=> $yourschedule];

					}
					else
					{
						$months['June'][$onlyD] = ['date' => $value,'day' => $day,'s1'=> '-'];

					}
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
				else
				{
					if($_POST['Monday'] == '0')
					{
						$months['July'][$onlyD] = ['date' => $value,'day' => $day,'s1'=> $youroff];
					}
					else if($_POST['Monday'] == '1')
					{
						$months['July'][$onlyD] = ['date' => $value,'day' => $day,'s1'=> $yourschedule];

					}
					else
					{
						$months['July'][$onlyD] = ['date' => $value,'day' => $day,'s1'=> '-'];

					}
				}
				
			}			
			else if($day == 'Tuesday')
			{
				if (in_array($value, $pubDate))
				{
					$months['July'][$onlyD] = ['date' => $value,'day' => $day,'s1'=> 'PUB'];
				}
				else
				{
					if($_POST['Tuesday'] == '0')
					{
						$months['July'][$onlyD] = ['date' => $value,'day' => $day,'s1'=> $youroff];
					}
					else if($_POST['Tuesday'] == '1')
					{
						$months['July'][$onlyD] = ['date' => $value,'day' => $day,'s1'=> $yourschedule];

					}
					else
					{
						$months['July'][$onlyD] = ['date' => $value,'day' => $day,'s1'=> '-'];

					}
				}
				
			}			
			else if($day == 'Wednesday')
			{
				if (in_array($value, $pubDate))
				{
					$months['July'][$onlyD] = ['date' => $value,'day' => $day,'s1'=> 'PUB'];
				}
				else
				{
					if($_POST['Wednesday'] == '0')
					{
						$months['July'][$onlyD] = ['date' => $value,'day' => $day,'s1'=> $youroff];
					}
					else if($_POST['Wednesday'] == '1')
					{
						$months['July'][$onlyD] = ['date' => $value,'day' => $day,'s1'=> $yourschedule];

					}
					else
					{
						$months['July'][$onlyD] = ['date' => $value,'day' => $day,'s1'=> '-'];

					}
				}
				
			}			
			else if($day == 'Thursday')
			{
				if (in_array($value, $pubDate))
				{
					$months['July'][$onlyD] = ['date' => $value,'day' => $day,'s1'=> 'PUB'];
				}
				else
				{
					if($_POST['Thursday'] == '0')
					{
						$months['July'][$onlyD] = ['date' => $value,'day' => $day,'s1'=> $youroff];
					}
					else if($_POST['Thursday'] == '1')
					{
						$months['July'][$onlyD] = ['date' => $value,'day' => $day,'s1'=> $yourschedule];

					}
					else
					{
						$months['July'][$onlyD] = ['date' => $value,'day' => $day,'s1'=> '-'];

					}
				}
				
			}			

			else if($day == 'Friday')
			{
				if (in_array($value, $pubDate))
				{
					$months['July'][$onlyD] = ['date' => $value,'day' => $day,'s1'=> 'PUB'];
				}
				else
				{
					if($_POST['Friday'] == '0')
					{
						$months['July'][$onlyD] = ['date' => $value,'day' => $day,'s1'=> $youroff];
					}
					else if($_POST['Friday'] == '1')
					{
						$months['July'][$onlyD] = ['date' => $value,'day' => $day,'s1'=> $yourschedule];

					}
					else
					{
						$months['July'][$onlyD] = ['date' => $value,'day' => $day,'s1'=> '-'];

					}
				}
				
			}			

			else if($day == 'Saturday')
			{
				if (in_array($value, $pubDate))
				{
					$months['July'][$onlyD] = ['date' => $value,'day' => $day,'s1'=> 'PUB'];
				}
				else
				{
					if($_POST['Saturday'] == '0')
					{
						$months['July'][$onlyD] = ['date' => $value,'day' => $day,'s1'=> $youroff];
					}
					else if($_POST['Saturday'] == '1')
					{
						$months['July'][$onlyD] = ['date' => $value,'day' => $day,'s1'=> $yourschedule];

					}
					else
					{
						$months['July'][$onlyD] = ['date' => $value,'day' => $day,'s1'=> '-'];

					}
				}
				
			}			
			else if($day == 'Sunday')
			{
				if (in_array($value, $pubDate))
				{
					$months['July'][$onlyD] = ['date' => $value,'day' => $day,'s1'=> 'PUB'];
				}
				else
				{
					if($_POST['Sunday'] == '0')
					{
						$months['July'][$onlyD] = ['date' => $value,'day' => $day,'s1'=> $youroff];
					}
					else if($_POST['Sunday'] == '1')
					{
						$months['July'][$onlyD] = ['date' => $value,'day' => $day,'s1'=> $yourschedule];

					}
					else
					{
						$months['July'][$onlyD] = ['date' => $value,'day' => $day,'s1'=> '-'];

					}
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
				else
				{
					if($_POST['Monday'] == '0')
					{
						$months['August'][$onlyD] = ['date' => $value,'day' => $day,'s1'=> $youroff];
					}
					else if($_POST['Monday'] == '1')
					{
						$months['August'][$onlyD] = ['date' => $value,'day' => $day,'s1'=> $yourschedule];

					}
					else
					{
						$months['August'][$onlyD] = ['date' => $value,'day' => $day,'s1'=> '-'];

					}
				}
				
			}			
			else if($day == 'Tuesday')
			{
				if (in_array($value, $pubDate))
				{
					$months['August'][$onlyD] = ['date' => $value,'day' => $day,'s1'=> 'PUB'];
				}
				else
				{
					if($_POST['Tuesday'] == '0')
					{
						$months['August'][$onlyD] = ['date' => $value,'day' => $day,'s1'=> $youroff];
					}
					else if($_POST['Tuesday'] == '1')
					{
						$months['August'][$onlyD] = ['date' => $value,'day' => $day,'s1'=> $yourschedule];

					}
					else
					{
						$months['August'][$onlyD] = ['date' => $value,'day' => $day,'s1'=> '-'];

					}
				}
				
			}			
			else if($day == 'Wednesday')
			{
				if (in_array($value, $pubDate))
				{
					$months['August'][$onlyD] = ['date' => $value,'day' => $day,'s1'=> 'PUB'];
				}
				else
				{
					if($_POST['Wednesday'] == '0')
					{
						$months['August'][$onlyD] = ['date' => $value,'day' => $day,'s1'=> $youroff];
					}
					else if($_POST['Wednesday'] == '1')
					{
						$months['August'][$onlyD] = ['date' => $value,'day' => $day,'s1'=> $yourschedule];

					}
					else
					{
						$months['August'][$onlyD] = ['date' => $value,'day' => $day,'s1'=> '-'];

					}
				}
				
			}			
			else if($day == 'Thursday')
			{
				if (in_array($value, $pubDate))
				{
					$months['August'][$onlyD] = ['date' => $value,'day' => $day,'s1'=> 'PUB'];
				}
				else
				{
					if($_POST['Thursday'] == '0')
					{
						$months['August'][$onlyD] = ['date' => $value,'day' => $day,'s1'=> $youroff];
					}
					else if($_POST['Thursday'] == '1')
					{
						$months['August'][$onlyD] = ['date' => $value,'day' => $day,'s1'=> $yourschedule];

					}
					else
					{
						$months['August'][$onlyD] = ['date' => $value,'day' => $day,'s1'=> '-'];

					}
				}
				
			}			

			else if($day == 'Friday')
			{
				if (in_array($value, $pubDate))
				{
					$months['August'][$onlyD] = ['date' => $value,'day' => $day,'s1'=> 'PUB'];
				}
				else
				{
					if($_POST['Friday'] == '0')
					{
						$months['August'][$onlyD] = ['date' => $value,'day' => $day,'s1'=> $youroff];
					}
					else if($_POST['Friday'] == '1')
					{
						$months['August'][$onlyD] = ['date' => $value,'day' => $day,'s1'=> $yourschedule];

					}
					else
					{
						$months['August'][$onlyD] = ['date' => $value,'day' => $day,'s1'=> '-'];

					}
				}
				
			}			

			else if($day == 'Saturday')
			{
				if (in_array($value, $pubDate))
				{
					$months['August'][$onlyD] = ['date' => $value,'day' => $day,'s1'=> 'PUB'];
				}
				else
				{
					if($_POST['Saturday'] == '0')
					{
						$months['August'][$onlyD] = ['date' => $value,'day' => $day,'s1'=> $youroff];
					}
					else if($_POST['Saturday'] == '1')
					{
						$months['August'][$onlyD] = ['date' => $value,'day' => $day,'s1'=> $yourschedule];

					}
					else
					{
						$months['August'][$onlyD] = ['date' => $value,'day' => $day,'s1'=> '-'];

					}
				}
				
			}			
			else if($day == 'Sunday')
			{
				if (in_array($value, $pubDate))
				{
					$months['August'][$onlyD] = ['date' => $value,'day' => $day,'s1'=> 'PUB'];
				}
				else
				{
					if($_POST['Sunday'] == '0')
					{
						$months['August'][$onlyD] = ['date' => $value,'day' => $day,'s1'=> $youroff];
					}
					else if($_POST['Sunday'] == '1')
					{
						$months['August'][$onlyD] = ['date' => $value,'day' => $day,'s1'=> $yourschedule];

					}
					else
					{
						$months['August'][$onlyD] = ['date' => $value,'day' => $day,'s1'=> '-'];

					}
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
				else
				{
					if($_POST['Monday'] == '0')
					{
						$months['September'][$onlyD] = ['date' => $value,'day' => $day,'s1'=> $youroff];
					}
					else if($_POST['Monday'] == '1')
					{
						$months['September'][$onlyD] = ['date' => $value,'day' => $day,'s1'=> $yourschedule];

					}
					else
					{
						$months['September'][$onlyD] = ['date' => $value,'day' => $day,'s1'=> '-'];

					}
				}
				
			}			
			else if($day == 'Tuesday')
			{
				if (in_array($value, $pubDate))
				{
					$months['September'][$onlyD] = ['date' => $value,'day' => $day,'s1'=> 'PUB'];
				}
				else
				{
					if($_POST['Tuesday'] == '0')
					{
						$months['September'][$onlyD] = ['date' => $value,'day' => $day,'s1'=> $youroff];
					}
					else if($_POST['Tuesday'] == '1')
					{
						$months['September'][$onlyD] = ['date' => $value,'day' => $day,'s1'=> $yourschedule];

					}
					else
					{
						$months['September'][$onlyD] = ['date' => $value,'day' => $day,'s1'=> '-'];

					}
				}
				
			}			
			else if($day == 'Wednesday')
			{
				if (in_array($value, $pubDate))
				{
					$months['September'][$onlyD] = ['date' => $value,'day' => $day,'s1'=> 'PUB'];
				}
				else
				{
					if($_POST['Wednesday'] == '0')
					{
						$months['September'][$onlyD] = ['date' => $value,'day' => $day,'s1'=> $youroff];
					}
					else if($_POST['Wednesday'] == '1')
					{
						$months['September'][$onlyD] = ['date' => $value,'day' => $day,'s1'=> $yourschedule];

					}
					else
					{
						$months['September'][$onlyD] = ['date' => $value,'day' => $day,'s1'=> '-'];

					}
				}
				
			}			
			else if($day == 'Thursday')
			{
				if (in_array($value, $pubDate))
				{
					$months['September'][$onlyD] = ['date' => $value,'day' => $day,'s1'=> 'PUB'];
				}
				else
				{
					if($_POST['Thursday'] == '0')
					{
						$months['September'][$onlyD] = ['date' => $value,'day' => $day,'s1'=> $youroff];
					}
					else if($_POST['Thursday'] == '1')
					{
						$months['September'][$onlyD] = ['date' => $value,'day' => $day,'s1'=> $yourschedule];

					}
					else
					{
						$months['September'][$onlyD] = ['date' => $value,'day' => $day,'s1'=> '-'];

					}
				}
				
			}			

			else if($day == 'Friday')
			{
				if (in_array($value, $pubDate))
				{
					$months['September'][$onlyD] = ['date' => $value,'day' => $day,'s1'=> 'PUB'];
				}
				else
				{
					if($_POST['Friday'] == '0')
					{
						$months['September'][$onlyD] = ['date' => $value,'day' => $day,'s1'=> $youroff];
					}
					else if($_POST['Friday'] == '1')
					{
						$months['September'][$onlyD] = ['date' => $value,'day' => $day,'s1'=> $yourschedule];

					}
					else
					{
						$months['September'][$onlyD] = ['date' => $value,'day' => $day,'s1'=> '-'];

					}
				}
				
			}			

			else if($day == 'Saturday')
			{
				if (in_array($value, $pubDate))
				{
					$months['September'][$onlyD] = ['date' => $value,'day' => $day,'s1'=> 'PUB'];
				}
				else
				{
					if($_POST['Saturday'] == '0')
					{
						$months['September'][$onlyD] = ['date' => $value,'day' => $day,'s1'=> $youroff];
					}
					else if($_POST['Saturday'] == '1')
					{
						$months['September'][$onlyD] = ['date' => $value,'day' => $day,'s1'=> $yourschedule];

					}
					else
					{
						$months['September'][$onlyD] = ['date' => $value,'day' => $day,'s1'=> '-'];

					}
				}
				
			}			
			else if($day == 'Sunday')
			{
				if (in_array($value, $pubDate))
				{
					$months['September'][$onlyD] = ['date' => $value,'day' => $day,'s1'=> 'PUB'];
				}
				else
				{
					if($_POST['Sunday'] == '0')
					{
						$months['September'][$onlyD] = ['date' => $value,'day' => $day,'s1'=> $youroff];
					}
					else if($_POST['Sunday'] == '1')
					{
						$months['September'][$onlyD] = ['date' => $value,'day' => $day,'s1'=> $yourschedule];

					}
					else
					{
						$months['September'][$onlyD] = ['date' => $value,'day' => $day,'s1'=> '-'];

					}
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
				else
				{
					if($_POST['Monday'] == '0')
					{
						$months['October'][$onlyD] = ['date' => $value,'day' => $day,'s1'=> $youroff];
					}
					else if($_POST['Monday'] == '1')
					{
						$months['October'][$onlyD] = ['date' => $value,'day' => $day,'s1'=> $yourschedule];

					}
					else
					{
						$months['October'][$onlyD] = ['date' => $value,'day' => $day,'s1'=> '-'];

					}
				}
				
			}			
			else if($day == 'Tuesday')
			{
				if (in_array($value, $pubDate))
				{
					$months['October'][$onlyD] = ['date' => $value,'day' => $day,'s1'=> 'PUB'];
				}
				else
				{
					if($_POST['Tuesday'] == '0')
					{
						$months['October'][$onlyD] = ['date' => $value,'day' => $day,'s1'=> $youroff];
					}
					else if($_POST['Tuesday'] == '1')
					{
						$months['October'][$onlyD] = ['date' => $value,'day' => $day,'s1'=> $yourschedule];

					}
					else
					{
						$months['October'][$onlyD] = ['date' => $value,'day' => $day,'s1'=> '-'];

					}
				}
				
			}			
			else if($day == 'Wednesday')
			{
				if (in_array($value, $pubDate))
				{
					$months['October'][$onlyD] = ['date' => $value,'day' => $day,'s1'=> 'PUB'];
				}
				else
				{
					if($_POST['Wednesday'] == '0')
					{
						$months['October'][$onlyD] = ['date' => $value,'day' => $day,'s1'=> $youroff];
					}
					else if($_POST['Wednesday'] == '1')
					{
						$months['October'][$onlyD] = ['date' => $value,'day' => $day,'s1'=> $yourschedule];

					}
					else
					{
						$months['October'][$onlyD] = ['date' => $value,'day' => $day,'s1'=> '-'];

					}
				}
				
			}			
			else if($day == 'Thursday')
			{
				if (in_array($value, $pubDate))
				{
					$months['October'][$onlyD] = ['date' => $value,'day' => $day,'s1'=> 'PUB'];
				}
				else
				{
					if($_POST['Thursday'] == '0')
					{
						$months['October'][$onlyD] = ['date' => $value,'day' => $day,'s1'=> $youroff];
					}
					else if($_POST['Thursday'] == '1')
					{
						$months['October'][$onlyD] = ['date' => $value,'day' => $day,'s1'=> $yourschedule];

					}
					else
					{
						$months['October'][$onlyD] = ['date' => $value,'day' => $day,'s1'=> '-'];

					}
				}
				
			}			

			else if($day == 'Friday')
			{
				if (in_array($value, $pubDate))
				{
					$months['October'][$onlyD] = ['date' => $value,'day' => $day,'s1'=> 'PUB'];
				}
				else
				{
					if($_POST['Friday'] == '0')
					{
						$months['October'][$onlyD] = ['date' => $value,'day' => $day,'s1'=> $youroff];
					}
					else if($_POST['Friday'] == '1')
					{
						$months['October'][$onlyD] = ['date' => $value,'day' => $day,'s1'=> $yourschedule];

					}
					else
					{
						$months['October'][$onlyD] = ['date' => $value,'day' => $day,'s1'=> '-'];

					}
				}
				
			}			

			else if($day == 'Saturday')
			{
				if (in_array($value, $pubDate))
				{
					$months['October'][$onlyD] = ['date' => $value,'day' => $day,'s1'=> 'PUB'];
				}
				else
				{
					if($_POST['Saturday'] == '0')
					{
						$months['October'][$onlyD] = ['date' => $value,'day' => $day,'s1'=> $youroff];
					}
					else if($_POST['Saturday'] == '1')
					{
						$months['October'][$onlyD] = ['date' => $value,'day' => $day,'s1'=> $yourschedule];

					}
					else
					{
						$months['October'][$onlyD] = ['date' => $value,'day' => $day,'s1'=> '-'];

					}
				}
				
			}			
			else if($day == 'Sunday')
			{
				if (in_array($value, $pubDate))
				{
					$months['October'][$onlyD] = ['date' => $value,'day' => $day,'s1'=> 'PUB'];
				}
				else
				{
					if($_POST['Sunday'] == '0')
					{
						$months['October'][$onlyD] = ['date' => $value,'day' => $day,'s1'=> $youroff];
					}
					else if($_POST['Sunday'] == '1')
					{
						$months['October'][$onlyD] = ['date' => $value,'day' => $day,'s1'=> $yourschedule];

					}
					else
					{
						$months['October'][$onlyD] = ['date' => $value,'day' => $day,'s1'=> '-'];

					}
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
				else
				{
					if($_POST['Monday'] == '0')
					{
						$months['November'][$onlyD] = ['date' => $value,'day' => $day,'s1'=> $youroff];
					}
					else if($_POST['Monday'] == '1')
					{
						$months['November'][$onlyD] = ['date' => $value,'day' => $day,'s1'=> $yourschedule];

					}
					else
					{
						$months['November'][$onlyD] = ['date' => $value,'day' => $day,'s1'=> '-'];

					}
				}
				
			}			
			else if($day == 'Tuesday')
			{
				if (in_array($value, $pubDate))
				{
					$months['November'][$onlyD] = ['date' => $value,'day' => $day,'s1'=> 'PUB'];
				}
				else
				{
					if($_POST['Tuesday'] == '0')
					{
						$months['November'][$onlyD] = ['date' => $value,'day' => $day,'s1'=> $youroff];
					}
					else if($_POST['Tuesday'] == '1')
					{
						$months['November'][$onlyD] = ['date' => $value,'day' => $day,'s1'=> $yourschedule];

					}
					else
					{
						$months['November'][$onlyD] = ['date' => $value,'day' => $day,'s1'=> '-'];

					}
				}
				
			}			
			else if($day == 'Wednesday')
			{
				if (in_array($value, $pubDate))
				{
					$months['November'][$onlyD] = ['date' => $value,'day' => $day,'s1'=> 'PUB'];
				}
				else
				{
					if($_POST['Wednesday'] == '0')
					{
						$months['November'][$onlyD] = ['date' => $value,'day' => $day,'s1'=> $youroff];
					}
					else if($_POST['Wednesday'] == '1')
					{
						$months['November'][$onlyD] = ['date' => $value,'day' => $day,'s1'=> $yourschedule];

					}
					else
					{
						$months['November'][$onlyD] = ['date' => $value,'day' => $day,'s1'=> '-'];

					}
				}
				
			}			
			else if($day == 'Thursday')
			{
				if (in_array($value, $pubDate))
				{
					$months['November'][$onlyD] = ['date' => $value,'day' => $day,'s1'=> 'PUB'];
				}
				else
				{
					if($_POST['Thursday'] == '0')
					{
						$months['November'][$onlyD] = ['date' => $value,'day' => $day,'s1'=> $youroff];
					}
					else if($_POST['Thursday'] == '1')
					{
						$months['November'][$onlyD] = ['date' => $value,'day' => $day,'s1'=> $yourschedule];

					}
					else
					{
						$months['November'][$onlyD] = ['date' => $value,'day' => $day,'s1'=> '-'];

					}
				}
				
			}			

			else if($day == 'Friday')
			{
				if (in_array($value, $pubDate))
				{
					$months['November'][$onlyD] = ['date' => $value,'day' => $day,'s1'=> 'PUB'];
				}
				else
				{
					if($_POST['Friday'] == '0')
					{
						$months['November'][$onlyD] = ['date' => $value,'day' => $day,'s1'=> $youroff];
					}
					else if($_POST['Friday'] == '1')
					{
						$months['November'][$onlyD] = ['date' => $value,'day' => $day,'s1'=> $yourschedule];

					}
					else
					{
						$months['November'][$onlyD] = ['date' => $value,'day' => $day,'s1'=> '-'];

					}
				}
				
			}			

			else if($day == 'Saturday')
			{
				if (in_array($value, $pubDate))
				{
					$months['November'][$onlyD] = ['date' => $value,'day' => $day,'s1'=> 'PUB'];
				}
				else
				{
					if($_POST['Saturday'] == '0')
					{
						$months['November'][$onlyD] = ['date' => $value,'day' => $day,'s1'=> $youroff];
					}
					else if($_POST['Saturday'] == '1')
					{
						$months['November'][$onlyD] = ['date' => $value,'day' => $day,'s1'=> $yourschedule];

					}
					else
					{
						$months['November'][$onlyD] = ['date' => $value,'day' => $day,'s1'=> '-'];

					}
				}
				
			}			
			else if($day == 'Sunday')
			{
				if (in_array($value, $pubDate))
				{
					$months['November'][$onlyD] = ['date' => $value,'day' => $day,'s1'=> 'PUB'];
				}
				else
				{
					if($_POST['Sunday'] == '0')
					{
						$months['November'][$onlyD] = ['date' => $value,'day' => $day,'s1'=> $youroff];
					}
					else if($_POST['Sunday'] == '1')
					{
						$months['November'][$onlyD] = ['date' => $value,'day' => $day,'s1'=> $yourschedule];

					}
					else
					{
						$months['November'][$onlyD] = ['date' => $value,'day' => $day,'s1'=> '-'];

					}
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
				else
				{
					if($_POST['Monday'] == '0')
					{
						$months['December'][$onlyD] = ['date' => $value,'day' => $day,'s1'=> $youroff];
					}
					else if($_POST['Monday'] == '1')
					{
						$months['December'][$onlyD] = ['date' => $value,'day' => $day,'s1'=> $yourschedule];

					}
					else
					{
						$months['December'][$onlyD] = ['date' => $value,'day' => $day,'s1'=> '-'];

					}
				}
				
			}			
			else if($day == 'Tuesday')
			{
				if (in_array($value, $pubDate))
				{
					$months['December'][$onlyD] = ['date' => $value,'day' => $day,'s1'=> 'PUB'];
				}
				else
				{
					if($_POST['Tuesday'] == '0')
					{
						$months['December'][$onlyD] = ['date' => $value,'day' => $day,'s1'=> $youroff];
					}
					else if($_POST['Tuesday'] == '1')
					{
						$months['December'][$onlyD] = ['date' => $value,'day' => $day,'s1'=> $yourschedule];

					}
					else
					{
						$months['December'][$onlyD] = ['date' => $value,'day' => $day,'s1'=> '-'];

					}
				}
				
			}			
			else if($day == 'Wednesday')
			{
				if (in_array($value, $pubDate))
				{
					$months['December'][$onlyD] = ['date' => $value,'day' => $day,'s1'=> 'PUB'];
				}
				else
				{
					if($_POST['Wednesday'] == '0')
					{
						$months['December'][$onlyD] = ['date' => $value,'day' => $day,'s1'=> $youroff];
					}
					else if($_POST['Wednesday'] == '1')
					{
						$months['December'][$onlyD] = ['date' => $value,'day' => $day,'s1'=> $yourschedule];

					}
					else
					{
						$months['December'][$onlyD] = ['date' => $value,'day' => $day,'s1'=> '-'];

					}
				}
				
			}			
			else if($day == 'Thursday')
			{
				if (in_array($value, $pubDate))
				{
					$months['December'][$onlyD] = ['date' => $value,'day' => $day,'s1'=> 'PUB'];
				}
				else
				{
					if($_POST['Thursday'] == '0')
					{
						$months['December'][$onlyD] = ['date' => $value,'day' => $day,'s1'=> $youroff];
					}
					else if($_POST['Thursday'] == '1')
					{
						$months['December'][$onlyD] = ['date' => $value,'day' => $day,'s1'=> $yourschedule];

					}
					else
					{
						$months['December'][$onlyD] = ['date' => $value,'day' => $day,'s1'=> '-'];

					}
				}
				
			}			

			else if($day == 'Friday')
			{
				if (in_array($value, $pubDate))
				{
					$months['December'][$onlyD] = ['date' => $value,'day' => $day,'s1'=> 'PUB'];
				}
				else
				{
					if($_POST['Friday'] == '0')
					{
						$months['December'][$onlyD] = ['date' => $value,'day' => $day,'s1'=> $youroff];
					}
					else if($_POST['Friday'] == '1')
					{
						$months['December'][$onlyD] = ['date' => $value,'day' => $day,'s1'=> $yourschedule];

					}
					else
					{
						$months['December'][$onlyD] = ['date' => $value,'day' => $day,'s1'=> '-'];

					}
				}
				
			}			

			else if($day == 'Saturday')
			{
				if (in_array($value, $pubDate))
				{
					$months['December'][$onlyD] = ['date' => $value,'day' => $day,'s1'=> 'PUB'];
				}
				else
				{
					if($_POST['Saturday'] == '0')
					{
						$months['December'][$onlyD] = ['date' => $value,'day' => $day,'s1'=> $youroff];
					}
					else if($_POST['Saturday'] == '1')
					{
						$months['December'][$onlyD] = ['date' => $value,'day' => $day,'s1'=> $yourschedule];

					}
					else
					{
						$months['December'][$onlyD] = ['date' => $value,'day' => $day,'s1'=> '-'];

					}
				}
				
			}			
			else if($day == 'Sunday')
			{
				if (in_array($value, $pubDate))
				{
					$months['December'][$onlyD] = ['date' => $value,'day' => $day,'s1'=> 'PUB'];
				}
				else
				{
					if($_POST['Sunday'] == '0')
					{
						$months['December'][$onlyD] = ['date' => $value,'day' => $day,'s1'=> $youroff];
					}
					else if($_POST['Sunday'] == '1')
					{
						$months['December'][$onlyD] = ['date' => $value,'day' => $day,'s1'=> $yourschedule];

					}
					else
					{
						$months['December'][$onlyD] = ['date' => $value,'day' => $day,'s1'=> '-'];

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

	

	if($variableOffDaysVal > 0 )
	{
		$workingDaysArray['January']  	 = ['wkd' =>count($totJanDay) , 'pub' =>count($pubJanDay) , 'off' => $variableOffDaysVal, 'noff' => count($offJanDay), 'workonly' => abs(count($totJanDay) - $variableOffDaysVal)];
		$workingDaysArray['February'] 	 = ['wkd' =>count($totFebDay) , 'pub' =>count($pubFebDay) , 'off' => $variableOffDaysVal, 'noff' => count($offFebDay), 'workonly' => abs(count($totFebDay) - $variableOffDaysVal)];
		$workingDaysArray['March']    	 = ['wkd' =>count($totMarDay) , 'pub' =>count($pubMarDay) , 'off' => $variableOffDaysVal, 'noff' => count($offMarDay), 'workonly' => abs(count($totMarDay) - $variableOffDaysVal)];
		$workingDaysArray['April']    	 = ['wkd' =>count($totAprDay) , 'pub' =>count($pubAprDay) , 'off' => $variableOffDaysVal, 'noff' => count($offAprDay), 'workonly' => abs(count($totAprDay) - $variableOffDaysVal)];
		$workingDaysArray['May']      	 = ['wkd' =>count($totMayDay) , 'pub' =>count($pubMayDay) , 'off' => $variableOffDaysVal, 'noff' => count($offMayDay), 'workonly' => abs(count($totMayDay) - $variableOffDaysVal)];
		$workingDaysArray['June']     	 = ['wkd' =>count($totJunDay) , 'pub' =>count($pubJunDay) , 'off' => $variableOffDaysVal, 'noff' => count($offJunDay), 'workonly' => abs(count($totJunDay) - $variableOffDaysVal)];
		$workingDaysArray['July']     	 = ['wkd' =>count($totJulDay) , 'pub' =>count($pubJulDay) , 'off' => $variableOffDaysVal, 'noff' => count($offJulDay), 'workonly' => abs(count($totJulDay) - $variableOffDaysVal)];
		$workingDaysArray['August']   	 = ['wkd' =>count($totAugDay) , 'pub' =>count($pubAugDay) , 'off' => $variableOffDaysVal, 'noff' => count($offAugDay), 'workonly' => abs(count($totAugDay) - $variableOffDaysVal)];
		$workingDaysArray['September']   = ['wkd' =>count($totSepDay) , 'pub' =>count($pubSepDay) , 'off' => $variableOffDaysVal, 'noff' => count($offSepDay), 'workonly' => abs(count($totSepDay) - $variableOffDaysVal)];
		$workingDaysArray['October']     = ['wkd' =>count($totOctDay) , 'pub' =>count($pubOctDay) , 'off' => $variableOffDaysVal, 'noff' => count($offOctDay), 'workonly' => abs(count($totOctDay) - $variableOffDaysVal)];
		$workingDaysArray['November']    = ['wkd' =>count($totNovDay) , 'pub' =>count($pubNovDay) , 'off' => $variableOffDaysVal, 'noff' => count($offNovDay), 'workonly' => abs(count($totNovDay) - $variableOffDaysVal)];
		$workingDaysArray['December']    = ['wkd' =>count($totDecDay) , 'pub' =>count($pubDecDay) , 'off' => $variableOffDaysVal, 'noff' => count($offDecDay), 'workonly' => abs(count($totDecDay) - $variableOffDaysVal)];
	}
	else if($variableOffDaysVal == '0')
	{
		$workingDaysArray['January']  	 = ['wkd' =>count($totJanDay) , 'pub' =>count($pubJanDay) , 'off' => count($offJanDay) , 'noff' => count($offJanDay),'workonly' => count($totJanDay)];
		$workingDaysArray['February'] 	 = ['wkd' =>count($totFebDay) , 'pub' =>count($pubFebDay) , 'off' => count($offFebDay) , 'noff' => count($offFebDay),'workonly' => count($totFebDay)];
		$workingDaysArray['March']    	 = ['wkd' =>count($totMarDay) , 'pub' =>count($pubMarDay) , 'off' => count($offMarDay) , 'noff' => count($offMarDay),'workonly' => count($totMarDay)];
		$workingDaysArray['April']    	 = ['wkd' =>count($totAprDay) , 'pub' =>count($pubAprDay) , 'off' => count($offAprDay) , 'noff' => count($offAprDay),'workonly' => count($totAprDay)];
		$workingDaysArray['May']      	 = ['wkd' =>count($totMayDay) , 'pub' =>count($pubMayDay) , 'off' => count($offMayDay) , 'noff' => count($offMayDay),'workonly' => count($totMayDay)];
		$workingDaysArray['June']     	 = ['wkd' =>count($totJunDay) , 'pub' =>count($pubJunDay) , 'off' => count($offJunDay) , 'noff' => count($offJunDay),'workonly' => count($totJunDay)];
		$workingDaysArray['July']     	 = ['wkd' =>count($totJulDay) , 'pub' =>count($pubJulDay) , 'off' => count($offJulDay) , 'noff' => count($offJulDay),'workonly' => count($totJulDay)];
		$workingDaysArray['August']   	 = ['wkd' =>count($totAugDay) , 'pub' =>count($pubAugDay) , 'off' => count($offAugDay) , 'noff' => count($offAugDay),'workonly' => count($totAugDay)];
		$workingDaysArray['September']   = ['wkd' =>count($totSepDay) , 'pub' =>count($pubSepDay) , 'off' => count($offSepDay) , 'noff' => count($offSepDay),'workonly' => count($totSepDay)];
		$workingDaysArray['October']     = ['wkd' =>count($totOctDay) , 'pub' =>count($pubOctDay) , 'off' => count($offOctDay) , 'noff' => count($offOctDay),'workonly' => count($totOctDay)];
		$workingDaysArray['November']    = ['wkd' =>count($totNovDay) , 'pub' =>count($pubNovDay) , 'off' => count($offNovDay) , 'noff' => count($offNovDay),'workonly' => count($totNovDay)];
		$workingDaysArray['December']    = ['wkd' =>count($totDecDay) , 'pub' =>count($pubDecDay) , 'off' => count($offDecDay) , 'noff' => count($offDecDay),'workonly' => count($totDecDay)];

	}
	else
	{
		$workingDaysArray['January']  	 = ['wkd' =>count($totJanDay) , 'pub' =>count($pubJanDay) , 'off' => count($offJanDay) , 'noff' => count($offJanDay),'workonly' => count($totJanDay)];
		$workingDaysArray['February'] 	 = ['wkd' =>count($totFebDay) , 'pub' =>count($pubFebDay) , 'off' => count($offFebDay) , 'noff' => count($offFebDay),'workonly' => count($totFebDay)];
		$workingDaysArray['March']    	 = ['wkd' =>count($totMarDay) , 'pub' =>count($pubMarDay) , 'off' => count($offMarDay) , 'noff' => count($offMarDay),'workonly' => count($totMarDay)];
		$workingDaysArray['April']    	 = ['wkd' =>count($totAprDay) , 'pub' =>count($pubAprDay) , 'off' => count($offAprDay) , 'noff' => count($offAprDay),'workonly' => count($totAprDay)];
		$workingDaysArray['May']      	 = ['wkd' =>count($totMayDay) , 'pub' =>count($pubMayDay) , 'off' => count($offMayDay) , 'noff' => count($offMayDay),'workonly' => count($totMayDay)];
		$workingDaysArray['June']     	 = ['wkd' =>count($totJunDay) , 'pub' =>count($pubJunDay) , 'off' => count($offJunDay) , 'noff' => count($offJunDay),'workonly' => count($totJunDay)];
		$workingDaysArray['July']     	 = ['wkd' =>count($totJulDay) , 'pub' =>count($pubJulDay) , 'off' => count($offJulDay) , 'noff' => count($offJulDay),'workonly' => count($totJulDay)];
		$workingDaysArray['August']   	 = ['wkd' =>count($totAugDay) , 'pub' =>count($pubAugDay) , 'off' => count($offAugDay) , 'noff' => count($offAugDay),'workonly' => count($totAugDay)];
		$workingDaysArray['September']   = ['wkd' =>count($totSepDay) , 'pub' =>count($pubSepDay) , 'off' => count($offSepDay) , 'noff' => count($offSepDay),'workonly' => count($totSepDay)];
		$workingDaysArray['October']     = ['wkd' =>count($totOctDay) , 'pub' =>count($pubOctDay) , 'off' => count($offOctDay) , 'noff' => count($offOctDay),'workonly' => count($totOctDay)];
		$workingDaysArray['November']    = ['wkd' =>count($totNovDay) , 'pub' =>count($pubNovDay) , 'off' => count($offNovDay) , 'noff' => count($offNovDay),'workonly' => count($totNovDay)];
		$workingDaysArray['December']    = ['wkd' =>count($totDecDay) , 'pub' =>count($pubDecDay) , 'off' => count($offDecDay) , 'noff' => count($offDecDay),'workonly' => count($totDecDay)];

	}
	


	// echo '<pre>';
	// print_r($workingDaysArray);
	// echo '</pre>';

	// die();



	$sql = "UPDATE ".$cid."_shiftplans_".$cur_year." SET cycle_details = '".$dbc->real_escape_string(serialize($months))."', wh_code = '".$_POST['shift_schedule1']."' , wkd = '".$dbc->real_escape_string(serialize($workingDaysArray))."' ,dates = '".$dbc->real_escape_string(serialize($months))."' WHERE id= '".$_POST['hidden_code_id']."'";

	ob_clean();	
	if($dbc->query($sql)){
		echo 'success';
	}else{
		echo mysqli_error($dbc);
	}

	exit;
	
?>














