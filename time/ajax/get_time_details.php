<?
	if(session_id()==''){session_start();}
	ob_start();
	include('../../dbconnect/db_connect.php');
	include(DIR.'files/functions.php');
	include(DIR.'time/functions.php');
	//$_REQUEST['id'] = 10007;

	// get plan time 
	function getShiftPlanTime($shiftplan){
		$data = array();	
		
		global $cid;
		global $dbc;
		// echo $cid;
		$res = $dbc->query("SELECT shiftplan FROM ".$cid."_leave_time_settings WHERE id= '1' ");
		if($res->num_rows > 0){
			if($row = $res->fetch_assoc()){
				$data = unserialize($row['shiftplan']);

				$newdata= $data[$shiftplan];
			}
		}
		return $newdata;
	}

	$planf1= getShiftPlanTime($row['plan']['f1']);


	$data = array();
	$data['image'] = '';
	$sql = "SELECT * FROM ".$cid."_attendance WHERE id = '".$_REQUEST['id']."'";
	if($res = $dbc->query($sql)){
		if($row = $res->fetch_assoc()){

			$sql2 = "SELECT * FROM ".$cid."_metascandata WHERE datescan = '".$row['date']."' AND  emp_id = '".$row['emp_id']."'";
			if($res2 = $dbc->query($sql2)){
				while($row2 = $res2->fetch_assoc()){

					$inoutArray[] =$row2;
				}
			}

			if($row['plan'])
			{
				$planf1= getShiftPlanTime($row['plan']);
				$planu2= getShiftPlanTime($row['plan']);

			}
			else{
				$planf1='';
				$planu2='';
			}

			foreach ($inoutArray as $key => $value) {

				$finalarray[$key]  = array(
					'timescan' => $value['timescan'],
					'in_or_out' => $value['in_or_out'],
				);
			}

			$data['finalarray'] = $finalarray;

			$data['image'] = getEmployeeImg($row['emp_id']);
			$data['id'] = $row['id'];
			$data['date'] = $row['date'];
			$data['emp_id'] = $row['emp_id'];
			$data['month'] = $row['month'];
			$data['plan'] = $row['plan'];
			$data['f1'] = $planf1['f1'];
			$data['u1'] = $row['u1'];
			$data['f2'] = $row['f2'];
			$data['u2'] = $planu2['u2'];
			if(!empty($row['all_scans'])){
				$scans = $row['all_scans'];
				$scans = explode('|',$scans);
				$sc=1;
				foreach($scans as $v){
					$data['ascan'.$sc] = $v;
					$sc++;
				}
				while($sc <= 9){
					$data['ascan'.$sc] = '-';
					$sc++;
				}
			}else{
				for($i=1;$i<=9;$i++){
					$data['ascan'.$i] = '-';
				}
			}
			for($i=1;$i<=9;$i++){
				if(!empty($row['img'.$i])){
					$data['img'.$i] = ROOT.$cid.'/time/selfies/'.$row['img'.$i];
				}else{
					$data['img'.$i] = '';
				}
				$data['loc'.$i] = $row['loc'.$i];
			}
			$data['scan1'] = $row['scan1'];
			$data['scan2'] = $row['scan2'];
			$data['scan3'] = $row['scan3'];
			$data['scan4'] = $row['scan4'];
			$data['comp10'] = $row['comp10'];
			
			$data['paid_late'] = dateHoursEmpty($row['paid_late']);
			$data['paid_early'] = dateHoursEmpty($row['paid_early']);
			$data['unpaid_late'] = dateHoursEmpty($row['unpaid_late']);
			$data['unpaid_early'] = dateHoursEmpty($row['unpaid_early']);
			
			$data['planned_hrs'] = dateHours($row['planned_hrs']);
			$data['ot_hrs'] = dateHours($row['ot_hrs']);
			$data['actual_hrs'] = dateHours($row['actual_hrs']);
			$data['break'] = dateHours($row['plan_break']);
			$data['tot_hrs'] = dateHours(($row['planned_hrs'] + $row['ot_hrs']));
			
			$data['unpaid_leave'] = dateHours($row['unpaid_leave']);
			$data['normal_hrs'] = dateHoursEmpty($row['normal_hrs']);
			$data['remarks'] = $row['remarks'];
			$data['comment'] = $row['comment'];
			$data['ot1'] = dateHoursEmpty($row['ot1']);
			$data['ot15'] = dateHoursEmpty($row['ot15']);
			$data['ot2'] = dateHoursEmpty($row['ot2']);
			$data['ot3'] = dateHoursEmpty($row['ot3']);
			$data['tot_ot'] = dateHours(($row['ot1']+$row['ot15']+$row['ot2']+$row['ot3']));
			
			$plan = ($row['planned_hrs'] + $row['ot_hrs']);
			$done = ($row['unpaid_late'] + $row['unpaid_early'] + $row['normal_hrs'] + $row['unpaid_leave'] + $row['ot1'] + $row['ot15'] + $row['ot2'] + $row['ot3']);
			$diff = bcsub($plan,$done,15); //$plan - $done;
			//var_dump($plan); //exit;
			//var_dump($done); //exit;
			//var_dump($diff); exit;

			$data['check'] = '<i style="color:#c00" class="fa fa-times fa-lg"></i>';
			$data['class'] = 'checkred';
			if($diff == 0){
				$data['check'] = '<i style="color:#0a0" class="fa fa-check fa-lg"></i>';
				$data['class'] = 'checkgreen';
			}
			$data['flag'] = dateHoursEmpty($diff);
		}
	}
	//var_dump($plan); //exit;
	//var_dump($done); exit;
	ob_clean();
	echo json_encode($data);


?>
