<?
	if(session_id()==''){session_start(); ob_start();}
	include("../../dbconnect/db_connect.php");
	include(DIR.'files/functions.php');
	include(DIR."time/functions.php");
	$holidays = getHolidaysDates();
	
	//var_dump($holidays); exit;
	
	$data = array();
	$sql = "SELECT * FROM ".$cid."_monthly_shiftplans_".$cur_year." WHERE month = ".$_REQUEST['month'];
	if($res = $dbc->query($sql)){
		while($row = $res->fetch_assoc()){
			$end = date('t', strtotime('01-'.$row['month'].'-'.$_SESSION['rego']['cur_year']));
			//var_dump($end); exit;
			for($i=1; $i<=$end; $i++){
				$splan = $row['D'.$i];
				if($splan != 'HD'){
					$date = date('Y-m-d', strtotime($i.'-'.$row['month'].'-'.$cur_year));
					$day = date('D', strtotime($date));
					$dnr = date('w', strtotime($date));
					$id = $row['emp_id'].'_'.strtotime($date);
					//$splan = $row['D'.$i];
					$hours = getShiftHoursValidate($cid, $splan, strtotime($date));
	
					$data[$id]['id'] = $id;
					$data[$id]['emp_id'] = $row['emp_id'];
					$data[$id]['en_name'] = $row['en_name'];
					$data[$id]['th_name'] = $row['th_name'];
					$data[$id]['date'] = $date;
					$data[$id]['day'] = $day;
					$data[$id]['dnr'] = $dnr;
					$data[$id]['plan'] = $splan;
					$data[$id]['shiftteam'] = $row['shiftteam'];
					$data[$id]['month'] = $row['month'];
	
					$data[$id]['f1'] = '';
					$data[$id]['u1'] = '';
					$data[$id]['f2'] = '';
					$data[$id]['u2'] = '';
					$data[$id]['plan_hrs'] = '';
					$data[$id]['planned_hrs'] = 0;
					$data[$id]['planned_ot'] = 0;
					$data[$id]['plan_break'] = 0;
	
					$data[$id]['hd'] = 0;
					$data[$id]['planned_days'] = 1;
					$data[$id]['planned_hrs'] = 0;
					if(isset($holidays[strtotime($date)])){
						$data[$id]['hd'] = 1;
						$data[$id]['planned_days'] = 0;
					}elseif($splan == 'OFF'){
						$data[$id]['planned_days'] = 0;
					}else{
						if($hours){
							$data[$id]['f1'] = $hours['f1'];
							$data[$id]['u1'] = $hours['u1'];
							$data[$id]['f2'] = $hours['f2'];
							$data[$id]['u2'] = $hours['u2'];
							$data[$id]['plan_hrs'] = $splan.'|'.$hours['f1'].'|'.$hours['u1'].'|'.$hours['f2'].'|'.$hours['u2'];
							$data[$id]['planned_hrs'] = decimalHours($hours['hours']);
							$data[$id]['planned_ot'] = decimalHours($hours['ot']);
							$data[$id]['plan_break'] = decimalHours($hours['break']);
						}
					}
				}
			}
			//$data[$row['id']] = $row;
		}
	}
	//var_dump($data); exit;
	
	$sql = "INSERT INTO ".$cid."_attendance (id, emp_id, en_name, th_name, date, day, dnr, plan, hd, plan_hrs, f1, u1, f2, u2, planned_days, planned_hrs, planned_ot, plan_break, shiftteam, month) VALUES ";
	foreach($data as $k=>$v){
		if($v['plan'] != 'OFF'){
			$sql .= "('".$dbc->real_escape_string($v['id'])."', ";
			$sql .= "'".$dbc->real_escape_string($v['emp_id'])."', ";
			$sql .= "'".$dbc->real_escape_string($v['en_name'])."', ";
			$sql .= "'".$dbc->real_escape_string($v['th_name'])."', ";
			$sql .= "'".$dbc->real_escape_string($v['date'])."', ";
			$sql .= "'".$dbc->real_escape_string($v['day'])."', ";
			$sql .= "'".$dbc->real_escape_string($v['dnr'])."', ";
			$sql .= "'".$dbc->real_escape_string($v['plan'])."', ";
			$sql .= "'".$dbc->real_escape_string($v['hd'])."', ";
			$sql .= "'".$dbc->real_escape_string($v['plan_hrs'])."', ";
			$sql .= "'".$dbc->real_escape_string($v['f1'])."', ";
			$sql .= "'".$dbc->real_escape_string($v['u1'])."', ";
			$sql .= "'".$dbc->real_escape_string($v['f2'])."', ";
			$sql .= "'".$dbc->real_escape_string($v['u2'])."', ";
			$sql .= "'".$dbc->real_escape_string($v['planned_days'])."', ";
			$sql .= "'".$dbc->real_escape_string($v['planned_hrs'])."', ";
			$sql .= "'".$dbc->real_escape_string($v['planned_ot'])."', ";
			$sql .= "'".$dbc->real_escape_string($v['plan_break'])."', ";
			$sql .= "'".$dbc->real_escape_string($v['shiftteam'])."', ";
			$sql .= "'".$dbc->real_escape_string($v['month'])."'), ";
		}
	}
	$sql = substr($sql,0,-2);
	$sql .= " ON DUPLICATE KEY UPDATE 
		en_name = VALUES(en_name), 
		th_name = VALUES(th_name), 
		plan = VALUES(plan), 
		plan_hrs = VALUES(plan_hrs),
		f1 = VALUES(f1),
		u1 = VALUES(u1),
		f2 = VALUES(f2),
		u2 = VALUES(u2),
		planned_days = VALUES(planned_days),
		planned_hrs = VALUES(planned_hrs),
		planned_ot = VALUES(planned_ot),
		plan_break = VALUES(plan_break),
		shiftteam = VALUES(shiftteam)";
	//echo $sql; exit;
		
	if($dbc->query($sql)){
		ob_clean();	echo 'success'; //exit;
	}else{
		ob_clean();	echo mysqli_error($dbc); exit;
	}

	foreach($data as $k=>$v){
		if($v['plan'] == 'OFF' || $v['plan'] == 'HD'){
			$sql = "DELETE FROM ".$cid."_attendance WHERE id = '".$k."' AND (scan1 = '-' OR scan1 = '')";
			//echo '<br>'.$sql;
			if($dbc->query($sql)){
				ob_clean();	
				echo 'success'; //exit;
			}else{
				ob_clean();	
				echo mysqli_error($dbc);
			}
		}
	}





























	
?>