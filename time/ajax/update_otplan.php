<?
	if(session_id()==''){session_start();}
	ob_start();
	include("../../dbconnect/db_connect.php");
	include(DIR.'time/functions.php');
	//var_dump($_REQUEST['otplan']); //exit;
	



	foreach($_REQUEST['otplan'] as $k=>$v){
		if(empty($v['date']) || empty($v['ot_from']) || empty($v['ot_until']) || empty($v['shiftteam'])){
			ob_clean();	echo 'empty'; exit;
		}
		if($v['plan'] != 'OFF' && $v['plan'] != 'HD'){
			if($v['ot_from'] != $v['plan_u2'] && $v['ot_until'] != $v['plan_f1']){
				ob_clean();	echo 'from_until'; exit;
			}
		}
	}

	$sql = "INSERT INTO ".$cid."_ot_plans (id, date, plan, plan_f1, plan_u2, ot_from, ot_until, ot_break, hours, shiftteam, compensations) VALUES ";
	foreach($_REQUEST['otplan'] as $k=>$v){
		$sql .= "(
			'".$dbc->real_escape_string($k)."', 
			'".$dbc->real_escape_string(date('Y-m-d', strtotime($v['date'])))."', 
			'".$dbc->real_escape_string($v['plan'])."', 
			'".$dbc->real_escape_string($v['plan_f1'])."', 
			'".$dbc->real_escape_string($v['plan_u2'])."', 
			'".$dbc->real_escape_string($v['ot_from'])."', 
			'".$dbc->real_escape_string($v['ot_until'])."', 
			'".$dbc->real_escape_string($v['ot_break'])."', 
			'".$dbc->real_escape_string($v['hours'])."', 
			'".$dbc->real_escape_string($v['shiftteam'])."', 
			'".$dbc->real_escape_string($v['compensations'])."'),";
	}
	$sql = substr($sql,0,-1);
	$sql .= " ON DUPLICATE KEY UPDATE 
		date = VALUES(date),
		plan = VALUES(plan),
		plan_f1 = VALUES(plan_f1),
		plan_u2 = VALUES(plan_u2),
		ot_from = VALUES(ot_from),
		ot_until = VALUES(ot_until),
		ot_break = VALUES(ot_break),
		hours = VALUES(hours),
		shiftteam = VALUES(shiftteam),
		compensations = VALUES(compensations)";
	//echo $sql; //exit;
	
	if($dbc->query($sql)){
		
		foreach($_REQUEST['otplan'] as $key=>$val){
			
			$exist = array();
			$sql = "SELECT * FROM ".$cid."_ot_employees WHERE ot_plan = '".$key."'";
			if($res = $dbc->query($sql)){
				while($row = $res->fetch_assoc()){
					$exist[$row['id']] = $key;
				}
			}
			//var_dump($exist); 
			//var_dump($val['shiftteam']); 
			
			/*$shiftteams = explode(',',$val['shiftteam']);
			$array = '';
			foreach($shiftteams as $v){$array .= "'".$v."',";}
			$array = substr($array,0,-1);*/
			$employees = array();
			$sql = "SELECT * FROM ".$cid."_employees WHERE teams = '".$val['shiftteam']."' AND emp_status = 1";
			if($res = $dbc->query($sql)){
				while($row = $res->fetch_assoc()){
					$id = $row['emp_id'].'_'.strtotime($val['date']);
					$employees[$id]['ot_plan'] = $key;
					$employees[$id]['emp_id'] = $row['emp_id'];
					$employees[$id]['month'] = date('n', strtotime($val['date']));
					$employees[$id]['date'] = $val['date'];
					$employees[$id]['en_name'] = $row['en_name'];
					$employees[$id]['th_name'] = $row['th_name'];
					$employees[$id]['position'] = $row['position'];
					$employees[$id]['team'] = $row['teams'];
					$employees[$id]['ot_from'] = $val['ot_from'];
					$employees[$id]['ot_until'] = $val['ot_until'];
					$employees[$id]['ot_break'] = $val['ot_break'];
					$employees[$id]['ot_hours'] = $val['hours'];
					//$employees[$id]['ot_type'] = $val['type'];
					$employees[$id]['ot_compensations'] = $val['compensations'];
				}
			}
			//var_dump($employees); //exit;
			
			foreach($exist as $k=>$v){
				if(!isset($employees[$k])){
					//var_dump($k); //exit;
					$dbc->query("DELETE FROM ".$cid."_ot_employees WHERE id = '".$k."'");
				}
			}


			// echo '<pre>';
			// print_r($employees);
			// echo '</pre>';

			// die();
			
			if($employees){
				$sql = "INSERT INTO ".$cid."_ot_employees (id, ot_plan, month, date, emp_id, en_name, th_name, ot_from, ot_until, ot_hours, ot_break, shiftteam, position, ot_compensations) VALUES ";
				foreach($employees as $k=>$v){
					$sql .= "(
						'".$dbc->real_escape_string($k)."', 
						'".$dbc->real_escape_string($v['ot_plan'])."', 
						'".$dbc->real_escape_string($v['month'])."', 
						'".$dbc->real_escape_string($v['date'])."', 
						'".$dbc->real_escape_string($v['emp_id'])."', 
						'".$dbc->real_escape_string($v['en_name'])."', 
						'".$dbc->real_escape_string($v['th_name'])."', 
						'".$dbc->real_escape_string($v['ot_from'])."', 
						'".$dbc->real_escape_string($v['ot_until'])."', 
						'".$dbc->real_escape_string($v['ot_hours'])."', 
						'".$dbc->real_escape_string($v['ot_break'])."', 
						'".$dbc->real_escape_string($v['team'])."', 
						'".$dbc->real_escape_string($v['position'])."', 
						'".$dbc->real_escape_string($v['ot_compensations'])."'),";
				}
				$sql = substr($sql,0,-1);
				$sql .= " ON DUPLICATE KEY UPDATE 
					ot_plan = VALUES(ot_plan),
					month = VALUES(month),
					date = VALUES(date),
					emp_id = VALUES(emp_id),
					en_name = VALUES(en_name),
					th_name = VALUES(th_name),
					ot_from = VALUES(ot_from),
					ot_until = VALUES(ot_until),
					ot_hours = VALUES(ot_hours),
					ot_break = VALUES(ot_break),
					shiftteam = VALUES(shiftteam),
					position = VALUES(position),
					ot_compensations = VALUES(ot_compensations)";
				//echo $sql; //exit;
				
				if($dbc->query($sql)){
					ob_clean();	
					echo 'success';
				}else{
					ob_clean();	
					echo mysqli_error($dbc);
				}
			}
		}
		exit;
		
		
		ob_clean();	
		echo 'success';
	}else{
		ob_clean();	
		echo mysqli_error($dbc);
	}
	
?>