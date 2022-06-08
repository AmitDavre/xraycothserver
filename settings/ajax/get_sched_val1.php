<?
	if(session_id()==''){session_start();}
	ob_start();
	include("../../dbconnect/db_connect.php");
	
	$cid = $_POST['cid'];
	$cur_year = $_POST['cur_year'];
	$schedule_code = $_POST['schedule_code'];
	$sqlFteams = "SELECT * FROM ".$cid."_leave_time_settings WHERE id = '1'";



	if($resFteams = $dbc->query($sqlFteams)){

		if($row = $resFteams->fetch_assoc()){

			$desc =  unserialize($row['shiftplan']);
	
		}
		else
		{	
			$desc = '';
		}
	}

	foreach ($desc as $key => $value) {
		# code...

		if($value['code'] == $schedule_code)
		{
			$totalHours = $value['hours'];
			$break = $value['break']; // 1:00
			$addbreak = $value['addbreak']; //0:15

		}
	}

	$breakA = explode(':',$break);
	$addbreakA = explode(':',$addbreak);
	$sum1 = $breakA[0] + $addbreakA[0];
	$sum2 = $breakA[1] + $addbreakA[1];
	// $test = $break +$addbreak;

	$totalHour = $sum1.':'.$sum2; 

	$data = array(

		'totalhours' => $totalHours,
		'totalbreak' =>$totalHour,
			);
	ob_clean();
	echo json_encode($data);
	// exit;
	
?>
