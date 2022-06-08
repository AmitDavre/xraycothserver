<?
	if(session_id()==''){session_start();}
	ob_start();
	include("../../dbconnect/db_connect.php");
	include(DIR.'files/functions.php');
	include(DIR.'files/arrays_'.$lang.'.php');
	include(DIR.'leave/functions.php');
	//var_dump($_REQUEST); exit;

	$leave_types = getLeaveTypes($cid);
	foreach($leave_types as $k=>$v){
		$balance[$k] = array('type'=>$v[$lang], 'pending'=>0, 'used'=>0);
	}
	$res = $dbc->query("SELECT * FROM ".$cid."_leaves_data WHERE emp_id = '".$_REQUEST['emp_id']."' AND date >= '".$_SESSION['rego']['mob_year'].'-01-01'."'");
	while($row = $res->fetch_assoc()){
		if($row['status'] == 'RQ' || $row['status'] == 'AP'){
			$balance[$row['leave_type']]['pending'] += $row['days'];
		}elseif($row['status'] == 'TA'){
			$balance[$row['leave_type']]['used'] += $row['days'];
		}
	}
	foreach($balance as $k=>$v){
		if($v['pending'] == 0 && $v['used'] == 0){unset($balance[$k]);}
	}

	$res = $dbc->query("SELECT * FROM ".$cid."_leaves WHERE id = '".$_REQUEST['id']."'"); 
	if($row = $res->fetch_assoc()){
		//$ALemp = getALemployee($cid, $row['emp_id']) - $balance['AL']['used'];
		$data['emp_id'] = $row['emp_id'];
		$data['name'] = $row['name'];
		$data['phone'] = $row['phone'];
		$data['leave_type'] = $row['leave_type'];
		$data['days'] = $row['days'];
		$data['start'] = date('D d-m-Y', strtotime($row['start']));
		$data['end'] = date('D d-m-Y', strtotime($row['end']));
		$data['attach'] = $row['attach'];
		$data['details'] = unserialize($row['details']);
		$data['reason'] = $row['reason'];
		$data['created'] = $row['created'];
		$data['created_by'] = $row['created_by'];
		$data['comment'] = $row['comment'];
	}else{
		$data = array();
	}
	//var_dump($data); exit;
	
	$table = '
		<table class="table table-bordered table-sm table-striped" style="width:100%; margin:0">
			<tr>
				<th>'.$lng['Employee'].'</th><td>'.$data['name'].'</td>
			</tr>
			<tr>
				<th>'.$lng['Phone'].'</th>
				<td style="padding:3px 3px !important"><a style="background:#09f; padding:1px 10px 0px; border-radius:20px; color:#fff; display:inline-block" href="tel:'.$data['phone'].'">'.$data['phone'].'</a>&nbsp; Tab to call</td>
			</tr>
			<tr>
				<th>'.$lng['Leave type'].'</th>
				<td>'.$leave_types[$data['leave_type']][$lang].'</td>
			</tr>
			<tr>
				<th>'.$lng['Date range'].'</th>
				<td style="padding:0 !important">
					<table class="noStyle" width="100%">';
					foreach($data['details'] as $v){
						$tmp = $v['day'];
						if($v['day'] == 'full'){$tmp = $lng['Full day'];}
						if($v['day'] == 'first'){$tmp = $lng['First half'];}
						if($v['day'] == 'second'){$tmp = $lng['Second half'];}
						$d = date('N', strtotime($v['date']));
						$table .= '
						<tr>
							<td style="white-space:normal">'.$xsdays[$d].date(' d-m-Y', strtotime($v['date'])).'</td>
							<td style="border-right:0 !important">'.$tmp.'</td>
						</tr>';
					}
	$table .=  '</table>
				</td>
			</tr>
			<tr>
				<th>'.$lng['Reason'].'</th>
				<td>'.$data['reason'].'</td>
			</tr>';
			if(!empty($data['attach'])){
				$table .= '
					<tr>
						<th>'.$lng['Attachment'].'</th><td><a target="_blank" href="'.$data['attach'].'">'.$lng['Uploaded document'].'</a></td>
					</tr>';
			}else{
				$table .= '
					<tr>
						<th>'.$lng['Attachment'].'</th><td>No attachment</td>
					</tr>';
			}
	$table .=  '</table>';
	
	if($balance){
		$table .= '<table class="table table-bordered table-sm xtable-striped" style="width:100%; margin-top:10px">
						<thead>
							<tr style="background:#eee">
								<th>'.$lng['Leave balance'].'</th><th class="tac">'.$lng['Pending'].'</th><th class="tac">'.$lng['Used'].'</th>
							</tr>
						</thead>
						<tbody>';
		
		foreach($balance as $v){
			$table .=  '<tr>
								<th>'.$v['type'].'</th>
								<td class="tac">'.$v['pending'].'</td>
								<td class="tac">'.$v['used'].'</td>
							</tr>';
		}
		/*}else{
			$table .=  '<tr><td colspan="3" class="tac">No data available</td></tr>';
		}*/
		$table .= '</tbody></table>';
	}
	ob_clean();
	echo $table
?>


















