<?
	if(session_id()==''){session_start(); ob_start();}
	include("../../dbconnect/db_connect.php");
	include(DIR.'files/functions.php');
	//var_dump($_REQUEST); exit;
	//$_REQUEST['id'] = '1';

	$leave_status = array('RQ'=>'Pending','CA'=>'Cancelled','AP'=>'Approved','RJ'=>'Rejected','PE'=>'Pending');
	//$leave_types = getLeaveTypes($cid);
	//ob_clean();
	//var_dump($leave_types); exit;
	//$_REQUEST['id'] = '10093_LW';
	
	$res = $dbc->query("SELECT * FROM ".$cid."_leaves WHERE id = '".$_REQUEST['id']."'"); 
	if($row = $res->fetch_assoc()){
		$data['emp_id'] = $row['emp_id'];
		$data['name'] = $row[$_SESSION['rego']['lang'].'_name'];
		$data['department'] = $departments[$row['dept_code']];
		$data['position'] = $positions[$row['position_code']];
		//$data['emp_name'] = $row[$_SESSION['rego']['lang'].'_name'];
		$data['type'] = $row['type'];
		$data['days'] = $row['days'];
		//$data['awarded'] = $row['awarded'];
		$data['start'] = date('d-m-Y', strtotime($row['start']));
		$data['end'] = date('d-m-Y', strtotime($row['end']));
		$data['nstart'] = $row['start'];
		$data['nend'] = $row['end'];
		$data['certificate'] = $row['certificate'];
		$data['attach'] = $row['attach'];
		$data['status'] = $row['status'];
		$data['date_range'] = unserialize($row['date_range']);
		$data['comment'] = $row['comment'];
		//var_dump($data);
	}else{
		$data = array();
		echo 'Error';
	}
	//var_dump($data); exit;
	ob_clean();
	echo json_encode($data);
	exit;
	
	
	
	
	
	$emp_id = $data['emp_id'].'_'.$data['type'];
	//var_dump($emp_id); exit;
	
	$res = $dbc->query("SELECT * FROM ".$cid."_leave_balance_".$_SESSION['rego']['cur_year']." WHERE leave_id = '".$emp_id."'"); 
	if($row = $res->fetch_assoc()){
		$days[0] = $row['days_available'];
		$days[1] = $row['days_used'];
		$days[2] = $row['days_pending'];
		$days[3] = $row['days_max']; // days available
		$days[4] = $row['days_paid'];
	}else{
		$days = array();
	}
	var_dump($days); exit;
	
	
	$table = '<table class="basicTable" border="0">
				<tbody>
				<tr>
					<th valign="top" style="padding-top:4px">
						<label><i class="man"></i>Employee :</label>
					</th>
					<td>
						<input placeholder="Type for hints ..." type="text" name="emp_name" id="autocomplete-name" value="'.$data['emp_name'].'" />
						<input type="hidden" name="emp_id" id="emp_id" value="" />
					</td>
				</tr>
				<tr>
					<th valign="top" style="padding-top:4px">
						<label><i class="man"></i>Leave type :</label>
					</th>
					<td>
						<select name="leavetype" id="leavetype">
							<option disabled selected value="0">Select</option>';
					foreach($leave_types as $k=>$v){
						$table .= '<option value="'.$k.'">'.$v['en'].'</option>';
					}
	$table .= ' 	</select>
					</td>
				</tr>
				</body>
			</table>';
	
	
	ob_clean();
	echo $table;
	exit;
	
	
	
	
	
	
	
	
	
	
	
	
	
	$table = '<b>Under construction</b></br><table class="modalTable" width:100% border="0">';
	$table .= '<tr><th>Employee :</th><td>'.$data['emp_id'].' - '.$data['emp_name'].'</td></tr>';
	$table .= '<tr><th>Leave type :</th><td>'.$leave_types[$data['type']]['en'].'</td></tr>';
	
	$table .= '<tr><th>Leave balance :</th><td><b>Available :</b> '.$days[0].' &nbsp;-&nbsp; <b>Used :</b> '.$days[1].' &nbsp;-&nbsp; <b>Pending :</b> '.$days[2].' &nbsp;-&nbsp; <b>Max. days :</b> '.$days[3].'<b> &nbsp;-&nbsp; Max. paid :</b> '.$days[4].'</td></tr>';
	
	
	
	$table .= '<tr><th>Details :</th><td style="padding-right:0"><div style="max-height:85px;overflow-y:auto;">';
	foreach($data['date_range'] as $v){
		$table .= $v.'<br>';
	}
	$table .= '</div></td></tr>';
	$table .= '<tr><th>Requested by :</th><td>'.$data['created_by_name'].' on '.$data['created'].'</td></tr>';
	if(!empty($action)){$table .= $action;	}
	if(!empty($reason)){$table .= $reason;	}
	$table .= '<tr><th>Comment :</th><td style="white-space:normal">'.$data['comment'].'</td></tr>';
	$table .= '</table>';
	ob_clean();
	echo $table;
?>


















