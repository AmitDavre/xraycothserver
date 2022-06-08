<?
	if(session_id()==''){session_start();}
	ob_start();
	include("../../dbconnect/db_connect.php");

	
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


	$label = $_POST['sArray'];

	$datetest = explode('/',$_POST['startdate']);
	$newDateVar =  $datetest[2].'-'.$datetest[1].'-'.$datetest[0];
	// $startdate = date('Y-m-d',strtotime($datetest)); // start date 
	$startdate = $newDateVar; // start date 

	$yearEnd = date('Y-m-d', strtotime('last day of december')); // end date


	$test = getDatesFromRange($startdate, $yearEnd);

	$arryCount = count($test);

	
	 $label2= array_merge(...array_fill(0, 150, $label));

	$pubDate = array();
	$sql3 = "SELECT * FROM ".$cid."_holidays  WHERE year= '".$cur_year."'";


	if($res3 = $dbc->query($sql3)){
		while($row3 = $res3->fetch_assoc()){
			$pubDate[] = $row3['cdate'];
		}
	}
	

	$months =array();

	$firstDay    = date('Y-m-d', strtotime('first day of january')); // first date
	$betweenArray = getDatesFromRange($firstDay, $startdate);


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

	// echo '<pre>';
	// print_r($months);
	// echo '</pre>';
	// die();

	$day = array();

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
					$months['January'][$onlyD] = ['date' => $value,'day' => $day,'s1'=> strtoupper($label2[$key])];
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
					$months['January'][$onlyD] = ['date' => $value,'day' => $day,'s1'=> strtoupper($label2[$key])];
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
					$months['January'][$onlyD] = ['date' => $value,'day' => $day,'s1'=> strtoupper($label2[$key])];
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
					$months['January'][$onlyD] = ['date' => $value,'day' => $day,'s1'=> strtoupper($label2[$key])];
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
					$months['January'][$onlyD] = ['date' => $value,'day' => $day,'s1'=> strtoupper($label2[$key])];
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
					$months['January'][$onlyD] = ['date' => $value,'day' => $day,'s1'=> strtoupper($label2[$key])];
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
					$months['January'][$onlyD] = ['date' => $value,'day' => $day,'s1'=> strtoupper($label2[$key])];
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
					$months['February'][$onlyD] = ['date' => $value,'day' => $day,'s1'=> strtoupper($label2[$key])];
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
					$months['February'][$onlyD] = ['date' => $value,'day' => $day,'s1'=> strtoupper($label2[$key])];
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
					$months['February'][$onlyD] = ['date' => $value,'day' => $day,'s1'=> strtoupper($label2[$key])];
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
					$months['February'][$onlyD] = ['date' => $value,'day' => $day,'s1'=> strtoupper($label2[$key])];
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
					$months['February'][$onlyD] = ['date' => $value,'day' => $day,'s1'=> strtoupper($label2[$key])];
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
					$months['February'][$onlyD] = ['date' => $value,'day' => $day,'s1'=> strtoupper($label2[$key])];
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
					$months['February'][$onlyD] = ['date' => $value,'day' => $day,'s1'=> strtoupper($label2[$key])];
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
					$months['March'][$onlyD] = ['date' => $value,'day' => $day,'s1'=> strtoupper($label2[$key])];
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
					$months['March'][$onlyD] = ['date' => $value,'day' => $day,'s1'=> strtoupper($label2[$key])];
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
					$months['March'][$onlyD] = ['date' => $value,'day' => $day,'s1'=> strtoupper($label2[$key])];
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
					$months['March'][$onlyD] = ['date' => $value,'day' => $day,'s1'=> strtoupper($label2[$key])];
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
					$months['March'][$onlyD] = ['date' => $value,'day' => $day,'s1'=> strtoupper($label2[$key])];
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
					$months['March'][$onlyD] = ['date' => $value,'day' => $day,'s1'=> strtoupper($label2[$key])];
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
					$months['March'][$onlyD] = ['date' => $value,'day' => $day,'s1'=> strtoupper($label2[$key])];
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
					$months['April'][$onlyD] = ['date' => $value,'day' => $day,'s1'=> strtoupper($label2[$key])];
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
					$months['April'][$onlyD] = ['date' => $value,'day' => $day,'s1'=> strtoupper($label2[$key])];
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
					$months['April'][$onlyD] = ['date' => $value,'day' => $day,'s1'=> strtoupper($label2[$key])];
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
					$months['April'][$onlyD] = ['date' => $value,'day' => $day,'s1'=> strtoupper($label2[$key])];
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
					$months['April'][$onlyD] = ['date' => $value,'day' => $day,'s1'=> strtoupper($label2[$key])];
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
					$months['April'][$onlyD] = ['date' => $value,'day' => $day,'s1'=> strtoupper($label2[$key])];
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
					$months['April'][$onlyD] = ['date' => $value,'day' => $day,'s1'=> strtoupper($label2[$key])];
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
					$months['May'][$onlyD] = ['date' => $value,'day' => $day,'s1'=> strtoupper($label2[$key])];
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
					$months['May'][$onlyD] = ['date' => $value,'day' => $day,'s1'=> strtoupper($label2[$key])];
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
					$months['May'][$onlyD] = ['date' => $value,'day' => $day,'s1'=> strtoupper($label2[$key])];
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
					$months['May'][$onlyD] = ['date' => $value,'day' => $day,'s1'=> strtoupper($label2[$key])];
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
					$months['May'][$onlyD] = ['date' => $value,'day' => $day,'s1'=> strtoupper($label2[$key])];
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
					$months['May'][$onlyD] = ['date' => $value,'day' => $day,'s1'=> strtoupper($label2[$key])];
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
					$months['May'][$onlyD] = ['date' => $value,'day' => $day,'s1'=> strtoupper($label2[$key])];
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
					$months['June'][$onlyD] = ['date' => $value,'day' => $day,'s1'=> strtoupper($label2[$key])];
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
					$months['June'][$onlyD] = ['date' => $value,'day' => $day,'s1'=> strtoupper($label2[$key])];
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
					$months['June'][$onlyD] = ['date' => $value,'day' => $day,'s1'=> strtoupper($label2[$key])];
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
					$months['June'][$onlyD] = ['date' => $value,'day' => $day,'s1'=> strtoupper($label2[$key])];
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
					$months['June'][$onlyD] = ['date' => $value,'day' => $day,'s1'=> strtoupper($label2[$key])];
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
					$months['June'][$onlyD] = ['date' => $value,'day' => $day,'s1'=> strtoupper($label2[$key])];
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
					$months['June'][$onlyD] = ['date' => $value,'day' => $day,'s1'=> strtoupper($label2[$key])];
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
					$months['July'][$onlyD] = ['date' => $value,'day' => $day,'s1'=> strtoupper($label2[$key])];
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
					$months['July'][$onlyD] = ['date' => $value,'day' => $day,'s1'=> strtoupper($label2[$key])];
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
					$months['July'][$onlyD] = ['date' => $value,'day' => $day,'s1'=> strtoupper($label2[$key])];
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
					$months['July'][$onlyD] = ['date' => $value,'day' => $day,'s1'=> strtoupper($label2[$key])];
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
					$months['July'][$onlyD] = ['date' => $value,'day' => $day,'s1'=> strtoupper($label2[$key])];
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
					$months['July'][$onlyD] = ['date' => $value,'day' => $day,'s1'=> strtoupper($label2[$key])];
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
					$months['July'][$onlyD] = ['date' => $value,'day' => $day,'s1'=> strtoupper($label2[$key])];
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
					$months['August'][$onlyD] = ['date' => $value,'day' => $day,'s1'=> strtoupper($label2[$key])];
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
					$months['August'][$onlyD] = ['date' => $value,'day' => $day,'s1'=> strtoupper($label2[$key])];
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
					$months['August'][$onlyD] = ['date' => $value,'day' => $day,'s1'=> strtoupper($label2[$key])];
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
					$months['August'][$onlyD] = ['date' => $value,'day' => $day,'s1'=> strtoupper($label2[$key])];
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
					$months['August'][$onlyD] = ['date' => $value,'day' => $day,'s1'=> strtoupper($label2[$key])];
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
					$months['August'][$onlyD] = ['date' => $value,'day' => $day,'s1'=> strtoupper($label2[$key])];
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
					$months['August'][$onlyD] = ['date' => $value,'day' => $day,'s1'=> strtoupper($label2[$key])];
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
					$months['September'][$onlyD] = ['date' => $value,'day' => $day,'s1'=> strtoupper($label2[$key])];
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
					$months['September'][$onlyD] = ['date' => $value,'day' => $day,'s1'=> strtoupper($label2[$key])];
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
					$months['September'][$onlyD] = ['date' => $value,'day' => $day,'s1'=> strtoupper($label2[$key])];
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
					$months['September'][$onlyD] = ['date' => $value,'day' => $day,'s1'=> strtoupper($label2[$key])];
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
					$months['September'][$onlyD] = ['date' => $value,'day' => $day,'s1'=> strtoupper($label2[$key])];
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
					$months['September'][$onlyD] = ['date' => $value,'day' => $day,'s1'=> strtoupper($label2[$key])];
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
					$months['September'][$onlyD] = ['date' => $value,'day' => $day,'s1'=> strtoupper($label2[$key])];
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
					$months['October'][$onlyD] = ['date' => $value,'day' => $day,'s1'=> strtoupper($label2[$key])];
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
					$months['October'][$onlyD] = ['date' => $value,'day' => $day,'s1'=> strtoupper($label2[$key])];
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
					$months['October'][$onlyD] = ['date' => $value,'day' => $day,'s1'=> strtoupper($label2[$key])];
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
					$months['October'][$onlyD] = ['date' => $value,'day' => $day,'s1'=> strtoupper($label2[$key])];
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
					$months['October'][$onlyD] = ['date' => $value,'day' => $day,'s1'=> strtoupper($label2[$key])];
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
					$months['October'][$onlyD] = ['date' => $value,'day' => $day,'s1'=> strtoupper($label2[$key])];
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
					$months['October'][$onlyD] = ['date' => $value,'day' => $day,'s1'=> strtoupper($label2[$key])];
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
					$months['November'][$onlyD] = ['date' => $value,'day' => $day,'s1'=> strtoupper($label2[$key])];
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
					$months['November'][$onlyD] = ['date' => $value,'day' => $day,'s1'=> strtoupper($label2[$key])];
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
					$months['November'][$onlyD] = ['date' => $value,'day' => $day,'s1'=> strtoupper($label2[$key])];
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
					$months['November'][$onlyD] = ['date' => $value,'day' => $day,'s1'=> strtoupper($label2[$key])];
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
					$months['November'][$onlyD] = ['date' => $value,'day' => $day,'s1'=> strtoupper($label2[$key])];
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
					$months['November'][$onlyD] = ['date' => $value,'day' => $day,'s1'=> strtoupper($label2[$key])];
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
					$months['November'][$onlyD] = ['date' => $value,'day' => $day,'s1'=> strtoupper($label2[$key])];
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
					$months['December'][$onlyD] = ['date' => $value,'day' => $day,'s1'=> strtoupper($label2[$key])];
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
					$months['December'][$onlyD] = ['date' => $value,'day' => $day,'s1'=> strtoupper($label2[$key])];
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
					$months['December'][$onlyD] = ['date' => $value,'day' => $day,'s1'=> strtoupper($label2[$key])];
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
					$months['December'][$onlyD] = ['date' => $value,'day' => $day,'s1'=> strtoupper($label2[$key])];
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
					$months['December'][$onlyD] = ['date' => $value,'day' => $day,'s1'=> strtoupper($label2[$key])];
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
					$months['December'][$onlyD] = ['date' => $value,'day' => $day,'s1'=> strtoupper($label2[$key])];
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
					$months['December'][$onlyD] = ['date' => $value,'day' => $day,'s1'=> strtoupper($label2[$key])];
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
	// // print_r($months);
	// echo '</pre>';
	


	// die();


		// $sql = "UPDATE ".$cid."_shiftplans_".$cur_year." SET cycle_details = '".$dbc->real_escape_string(serialize($months))."'  WHERE id= '".$_POST['hidden_code_id']."'";


	$sql = "UPDATE ".$cid."_shiftplans_".$cur_year." SET cycle_details = '".$dbc->real_escape_string(serialize($months))."',dates = '".$dbc->real_escape_string(serialize($months))."', wkd = '".$dbc->real_escape_string(serialize($workingDaysArray))."' WHERE id= '".$_POST['hidden_code_id']."'";

		// echo $sql;
		// die();

	ob_clean();	
	if($dbc->query($sql)){
		echo 'success';
	}else{
		echo mysqli_error($dbc);
	}

	exit;


	
?>














