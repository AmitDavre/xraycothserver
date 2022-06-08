<?
	if(session_id()==''){session_start();}
	ob_start();
	include('../../dbconnect/db_connect.php');
	include(DIR.'time/functions.php');
	//var_dump($_REQUEST); //exit;
	$teams = getShifTeams();
	$positions[0] = '-';
	
	$data = array();
	$sql = "SELECT * FROM ".$cid."_ot_employees WHERE ot_plan = '".$_REQUEST['id']."' ORDER BY emp_id ASC";
	if($res = $dbc->query($sql)){
		while($row = $res->fetch_assoc()){
			$data[$row['emp_id']]['id'] = $row['id'];
			$data[$row['emp_id']]['name'] = $row[$lang.'_name'];
			$data[$row['emp_id']]['position'] = $positions[$row['position']];
			$data[$row['emp_id']]['team'] = $row['shiftteam'];
			$data[$row['emp_id']]['invited'] = $row['ot_invited'];
			$data[$row['emp_id']]['confirmed'] = $row['ot_confirmed'];
			$data[$row['emp_id']]['assigned'] = $row['ot_assigned'];
		}
	}
	//var_dump($data); exit;


	// echo '<pre>';
	// print_r($data);
	// echo '</pre>';

	// die();
	
	$table = '';
	foreach($data as $k=>$v){
		$table .= '
			<tr>
				<td>'.$k.'</td>
				<td>'.$v['name'].'</td>
				<td>'.$v['position']['en'].'</td>
				<td>'.$v['team'].'</td>
				<td class="tac">
					<label><input ';
					if($v['invited']){$table .= 'checked ';}
					$table .= 'type="checkbox" data-id="'.$v['id'].'" data-field="ot_invited" class="inviteBox checkbox notxt"><span style="margin:0"></span></label>									
				</td>
				<td class="tac">
					<label><input ';
					if($v['confirmed']){$table .= 'checked ';}
					$table .= 'type="checkbox" data-id="'.$v['id'].'" data-field="ot_confirmed" class="confirmBox checkbox notxt"><span style="margin:0"></span></label>									
				</td>
				<td class="tac">
					<label><input ';
					if($v['assigned']){$table .= 'checked ';}
					$table .= 'type="checkbox" data-id="'.$v['id'].'" data-field="ot_assigned" class="assignBox checkbox notxt"><span style="margin:0"></span></label>									
				</td>
			</tr>';
	}
	
	echo($table); exit;
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
