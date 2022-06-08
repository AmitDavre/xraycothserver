<?php

	if(session_id()==''){session_start();}
	ob_start();
	include('../../dbconnect/db_connect.php');
	include(DIR.'files/functions.php');
	include(DIR.'time/functions.php');
	$time_settings = getTimeSettings();
	$shiftplans = unserialize($time_settings['shiftplan']);
	$holidays = getHolidaysDates();
	//var_dump($holidays); exit;
		


	//var_dump($_REQUEST); //exit;
	//$_REQUEST['team'] = 'DT';
	//$_REQUEST['date'] = '10-09-2020';
	$day = 'D'.date('j', strtotime($_REQUEST['date']));
	$month = date('m', strtotime($_REQUEST['date']));
	$year = date('Y', strtotime($_REQUEST['date']));

	$dayplan = array();
	$sql = "SELECT $day FROM ".$cid."_monthly_shiftplans_".$year." WHERE month = $month AND shiftteam_name = '".$_REQUEST['team']."'";

	if($res = $dbc->query($sql)){
		if($row = $res->fetch_assoc()){
			$dayplan['plan'] = $row[$day];
			if(isset($holidays[strtotime($_REQUEST['date'])])){
				$dayplan['plan'] = 'HD';
			}
			if($dayplan['plan'] != 'OFF' && $dayplan['plan'] != 'HD'){
				$dayplan['plan_f1'] = $shiftplans[$row[$day]]['f1'];
				$dayplan['plan_u2'] = $shiftplans[$row[$day]]['u2'];
			}else{
				$dayplan['plan_f1'] = '-';
				$dayplan['plan_u2'] = '-';
			}
		}
	}

	//var_dump(unserialize($time_settings['shiftplan'])); //exit;
	//var_dump($dayplan);
	ob_clean(); 
	echo json_encode($dayplan);
	
	exit;
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	$cols = date('t', strtotime($_SESSION['rego']['cur_year'].'-'.$_REQUEST['month'].'-01'));
	//var_dump($cols);
	$emps = getActiveEmployees();
	//$holidays = getHolidaysDates();
	//var_dump($emps); exit;
	
	$nr = 0;
	foreach($emps as $k=>$v) {
		//var_dump($v['shiftplan']);
		$dates = array();
		$res = $dbc->query("SELECT dates FROM ".$cid."_shiftplans_".$cur_year." WHERE id = '".$v['shiftplan']."'");
		if(!mysqli_error($dbc)){
			if($row = $res->fetch_assoc()){
				$dates = unserialize($row['dates']);
			}
		}
		//var_dump($dates);
		$data[$nr]['id'] = $k.'-'.$_REQUEST['month'];
		$data[$nr]['sid'] = $v['sid'];
		$data[$nr]['month'] = $_REQUEST['month'];
		$data[$nr]['emp_id'] = $k;
		$data[$nr]['en_name'] = $v['en_name'];
		$data[$nr]['th_name'] = $v['th_name'];
		if(!empty($v['shiftplan'])){
			$data[$nr]['shiftteam'] = $v['shiftplan'];
		}else{
			$data[$nr]['shiftteam'] = 'DT';
		}
		for($i=1;$i<=$cols;$i++){
			$str = strtotime($_SESSION['rego']['cur_year'].'-'.$_REQUEST['month'].'-'.$i);
			$data[$nr]['D'.$i] = $dates[$str]['plan'];
			//if(isset($holidays[$str])){
				//$data[$nr]['D'.$i] = 'HD';
			//}
		}
		$nr ++;
	}
	//var_dump($dates);
	var_dump($data); exit;
	
	$sql = "INSERT INTO ".$cid."_monthly_shiftplans_".$_SESSION['rego']['cur_year']." (";
	foreach($data[0] as $k=>$v){
		$sql .= $k.',';
	}
	$sql = substr($sql,0,-1);
	$sql .= ") VALUES ("; 
	foreach($data as $key=>$val){
		foreach($val as $k=>$v){
			$sql .= "'".mysqli_real_escape_string($dbc,$v)."',";
		}
		$sql = substr($sql,0,-1);
		$sql .= '),(';
	}
	$sql = substr($sql,0,-2);
	$sql .= " ON DUPLICATE KEY UPDATE ";
	foreach($data[0] as $k=>$v){
		$sql .= $k.' = VALUES('.$k.'),';
		//var_dump($v.' = VALUES('.$v.')');
	}
	$sql = substr($sql,0,-1);
	//echo $sql;
	//exit;
	ob_clean();	
	if($dbc->query($sql)){
		echo 'success';
	}else{
		echo mysqli_error($dbc);
	}