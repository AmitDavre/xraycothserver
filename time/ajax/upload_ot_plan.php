<?
	if(session_id()==''){session_start();}
	ob_start();
	include("../../dbconnect/db_connect.php");
	include(DIR.'time/functions.php');
	//$teams = getShifTeams();
	//var_dump($_REQUEST); exit;



	
	$data = array();
	$sql = "SELECT * FROM ".$cid."_ot_employees WHERE ot_plan = '".$_REQUEST['id']."' AND ot_assigned = 1";
	if($res = $dbc->query($sql)){
		while($row = $res->fetch_assoc()){
			$date = date('Y-m-d', strtotime($row['date']));
			$month = date('n', strtotime($row['date']));
			$day = date('D', strtotime($row['date']));
			$dnr = date('w', strtotime($row['date']));
			$data[$row['id']]['id'] = $row['id'];
			$data[$row['id']]['emp_id'] = $row['emp_id'];
			$data[$row['id']]['en_name'] = $row['en_name'];
			$data[$row['id']]['th_name'] = $row['th_name'];
			$data[$row['id']]['shiftteam'] = $row['shiftteam'];
			$data[$row['id']]['date'] = $date;
			$data[$row['id']]['month'] = $month;
			$data[$row['id']]['day'] = $day;
			$data[$row['id']]['dnr'] = $dnr;
			$data[$row['id']]['ot_plan'] = $row['ot_plan'];
			$data[$row['id']]['ot_from'] = $row['ot_from'];
			$data[$row['id']]['ot_until'] = $row['ot_until'];
			$data[$row['id']]['ot_hrs'] = decimalHours($row['ot_hours']);
			$data[$row['id']]['ot_break'] = decimalHours($row['ot_break']);
			//$data[$row['id']]['ot_type'] = $row['ot_type'];
			$data[$row['id']]['ot_compensations'] = $row['ot_compensations'];
		}
	}
	//var_dump($data); exit;


	// echo '<pre>';
	// print_r($data);
	// echo '</pre>';

	// die();

	if($data){
		$sql = "INSERT INTO ".$cid."_attendance (id, emp_id, en_name, th_name, shiftteam, date, month, day, dnr, ot_plan, ot_from, ot_until, ot_hrs, ot_break, ot_compensations) VALUES (";
		foreach($data as $key=>$val){
			foreach($val as $k=>$v){
				$sql .= "'".$dbc->real_escape_string($v)."',";
			}
			$sql = substr($sql,0,-1);
			$sql .= '),(';
		}
		$sql = substr($sql,0,-2);
		$sql .= " ON DUPLICATE KEY UPDATE 
			ot_plan = VALUES(ot_plan),
			ot_from = VALUES(ot_from),
			ot_until = VALUES(ot_until),
			ot_hrs = VALUES(ot_hrs),
			ot_break = VALUES(ot_break),
			ot_compensations = VALUES(ot_compensations)";
		//echo $sql;
		//exit;
		
		if($res = $dbc->query($sql)){
			ob_clean();
			echo 'success';
		}else{
			ob_clean();
			echo mysqli_error($dbc);
		}	
	}else{
		ob_clean();
		echo 'assign';
	}
