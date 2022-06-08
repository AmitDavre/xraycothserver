<?
	if(session_id()==''){session_start(); ob_start();}
	//$cid = $_SESSION['rego']['cid'];
	include("../../dbconnect/db_connect.php");
	include(DIR.'files/functions.php');
	include(DIR.'leave/functions.php');
	//include(DIR.'files/arrays_'.$_SESSION['rego']['lang'].'.php');
	$leave_types = getLeaveTypes($cid);
	//$lng = getLangVariables($_SESSION['rego']['lang']);
	//var_dump($leave_types); exit;
	$day_array = array('full'=>$lng['Full day'], 'first'=>$lng['First half'], 'second'=>$lng['Second half']);
	$leave_status = array('RQ'=>$lng['Requested'],'CA'=>$lng['Cancelled'],'AP'=>$lng['Approved'],'RJ'=>$lng['Rejected'],'PE'=>$lng['Pending'],'TA'=>'Taken');

	$res = $dbc->query("SELECT * FROM ".$cid."_leaves WHERE id = '".$_REQUEST['id']."'"); 
	//var_dump($cid."_leaves_'".$_SESSION['rego']['cur_year']);
	if($row = $res->fetch_assoc()){
		$data['emp_id'] = $row['emp_id'];
		$data['name'] = $row['name'];
		$data['phone'] = $row['phone'];
		$data['leave_type'] = $row['leave_type'];
		$data['days'] = $row['days'];
		$data['start'] = date('D d-m-Y', strtotime($row['start']));
		$data['end'] = date('D d-m-Y', strtotime($row['end']));
		$data['status'] = $row['status'];
		$data['certificate'] = $row['certificate'];
		$data['attach'] = $row['attach'];
		$data['details'] = unserialize($row['details']);
		$data['reason'] = $row['reason'];
		$data['created'] = $row['created'];
		$data['created_by'] = $row['created_by'];
		$data['approved'] = $row['approved'];
		$data['approved_by'] = $row['approved_by'];
		$data['rejected'] = $row['rejected'];
		$data['rejected_by'] = $row['rejected_by'];
		$data['canceled'] = $row['canceled'];
		$data['canceled_by'] = $row['canceled_by'];
		$data['updated'] = $row['updated'];
		$data['updated_by'] = $row['updated_by'];
		$data['comment'] = $row['comment'];
		//var_dump($data);
	}else{
		$data = array();
		//echo 'Error';
	}
	//var_dump($data); exit;
	
	$action = '';
	$comment = '';
	if($data['status'] == 'AP'){
		$action = '<tr><th>'.$lng['Approved by'].'</th><td>'.$data['approved_by'].' '.$lng['on'].' '.$data['approved'].'</td></tr>';
	}
	if($data['status'] == 'RJ'){
		$action = '<tr><th>'.$lng['Rejected by'].'</th><td>'.$data['rejected_by'].' '.$lng['on'].' '.$data['rejected'].'</td></tr>';
		$comment = '<tr><th>'.$lng['Comment'].'</th><td style="white-space:normal">'.$data['comment'].'</td></tr>';
	}
	if($data['status'] == 'CA'){
		$action = '<tr><th>'.$lng['Canceled by'].'</th><td>'.$data['canceled_by'].' '.$lng['on'].' '.$data['canceled'].'</td></tr>';
		$comment = '<tr><th>'.$lng['Comment'].'</th><td style="white-space:normal">'.$data['comment'].'</td></tr>';
	}
	
	$table = '<table class="basicTable compact" width:100% border="0">';
	$table .= '<tr><th>'.$lng['Employee'].'</th><td>'.$data['name'].'</td></tr>';
	$table .= '<tr><th>'.$lng['Phone'].'</th><td>'.$data['phone'].'</td></tr>';
	$table .= '<tr><th>'.$lng['Leave type'].'</th><td>'.$leave_types[$data['leave_type']][$lang].'</td></tr>';
	$table .= '<tr><th>'.$lng['Reason'].'</th><td>'.$data['reason'].'</td></tr>';
	$table .= '<tr><th>'.$lng['Status'].'</th><td>'.$leave_status[$data['status']].'</td></tr>';
	
	$table .= '<tr><th>'.$lng['Date range'].'</th><td> '.$lng['Fromm'].' '.$data['start'].'&nbsp;&nbsp; '.$lng['Until'].' '.$data['end'].'</td></tr>';
	
	$table .= '<tr><th>'.$lng['Details'].'</th><td style="padding:0"><div style="max-height:150px;overflow-y:auto;">';
	$table .= '<table class="noStyle" width="100%">';
	foreach($data['details'] as $v){
		$tmp = $v['day'];
		if($v['day'] == 'full'){$tmp = $lng['Full day'];}
		if($v['day'] == 'first'){$tmp = $lng['First half'];}
		if($v['day'] == 'second'){$tmp = $lng['Second half'];}
		$table .= '<tr><td>'.date('D d-m-Y', strtotime($v['date'])).'</td><td style="border-right:0 !important">'.$tmp.'</td><td width="50%"></td></tr>';
	}
	$table .= '</table>';
	
	$table .= '</div></td></tr>';
	$table .= '<tr><th>'.$lng['Requested by'].'</th><td>'.$data['created_by'].' '.$lng['on'].' '.$data['created'].'</td></tr>';
	if(!empty($action)){$table .= $action;	}
	if(!empty($comment)){$table .= $comment;	}
	if($data['certificate'] == 'Y'){
		$table .= '<tr><th>'.$lng['Certificate'].'</th><td>'.$lng['Yes'].'</td></tr>'; // ????????????????????????????????????????????????????????????
	}
	if(!empty($data['attach'])){
		$table .= '<tr><th>'.$lng['Attachment'].'</th><td><a target="_blank" href="'.$data['attach'].'">'.$lng['View'].'/'.$lng['Download'].'</a></td></tr>';
	}
	$table .= '</table>';
	
	ob_clean();
	echo $table
?>


















