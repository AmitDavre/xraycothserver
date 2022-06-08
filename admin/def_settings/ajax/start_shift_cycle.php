<?
	if(session_id()==''){session_start();}
	ob_start();
	include("../../dbconnect/db_connect.php");


	// echo '<pre>';
	// print_r($_SESSION);
	// echo '</pre>';

	// die();
	$cur_year = $_SESSION['RGadmin']['cur_year'] ;
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
	$sql3 = "SELECT * FROM rego_default_holidays  WHERE year= '".$cur_year."'";
	// echo $sql3;
	// die();
	if($res3 = $dba->query($sql3)){
		while($row3 = $res3->fetch_assoc()){
			$pubDate[] = $row3['cdate'];
		}
	}

	$day = array();
	$months =array();


	$firstDay    = date('Y-m-d', strtotime('first day of january')); // first date


	// get dates between first day and start day of cycle 
	$betweenArray = getDatesFromRange($firstDay, $startdate);


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
	// print_r($months);
	// echo '</pre>';
	// die();

	$sql = "UPDATE rego_default_shiftplans SET cycle_details = '".$dba->real_escape_string(serialize($months))."', wh_code = '".$_POST['shift_schedule1']."' , wkd = '".$dba->real_escape_string(serialize($workingDaysArray))."' ,dates = '".$dba->real_escape_string(serialize($months))."' WHERE id= '".$_POST['hidden_code_id']."'";

	ob_clean();	
	if($dba->query($sql)){
		echo 'success';
	}else{
		echo mysqli_error($dba);
	}

	exit;
	
?>














