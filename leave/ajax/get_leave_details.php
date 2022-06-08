<?

	if(session_id()==''){session_start(); ob_start();}
	include("../../dbconnect/db_connect.php");
	include(DIR.'/leave/functions.php');
	include(DIR."/files/functions.php");
	//var_dump($_REQUEST); exit;

	$leave_status = array('RQ'=>'Pending','CA'=>'Cancelled','AP'=>'Approved','RJ'=>'Rejected','PE'=>'Pending');
	$leave_types = getLeaveTypes($cid);
	
	$res = $dbc->query("SELECT * FROM ".$cid."_leaves WHERE id = '".$_REQUEST['lid']."'"); 
	if($row = $res->fetch_assoc()){
		$data['leave_id'] = $_REQUEST['lid'];
		$data['emp_id'] = $row['emp_id'];
		$data['name'] = $row['name'];
		$data['phone'] = $row['phone'];
		$data['leave_type'] = $row['leave_type'];
		$data['days'] = $row['days'];
		$data['start'] = date('d-m-Y', strtotime($row['start']));
		$data['end'] = date('d-m-Y', strtotime($row['end']));
		$data['certificate'] = $row['certificate'];
		$data['attach'] = $row['attach'];
		$data['status'] = $row['status'];
		$data['details'] = unserialize($row['details']);
		$data['reason'] = $row['reason'];
	}else{
		$data = array();
		echo 'Error';
	}
	$img = DIR.$cid."/employees/img/".$data['emp_id'].'.jpg';
	if(file_exists($img)){   
		$data['img'] = ROOT.$cid."/employees/img/".$data['emp_id'].'.jpg';                       
	}else{
		$data['img'] = ROOT.'images/profile_image.jpg';                        
	}	
	//var_dump($data); exit;

	$table = '<table class="basicTable" border="0"><tbody>';
	foreach($data['details'] as $k=>$v){
		$tmp = $v['day'];
		if($v['day'] == 'full'){$tmp = 'Full day';}
		if($v['day'] == 'first'){$tmp = 'First half';}
		if($v['day'] == 'second'){$tmp = 'Second half';}
		$table .= '
			<tr>
				<td class="tar">
					'.date('D d-m-Y', strtotime($v['date'])).'
					<input name="date['.$k.']" type="hidden" value="'.date('Y-m-d', strtotime($v['date'])).'" />
				</td>
				<td class="pad1">
					<button data-id="'.$k.'" class="popRequest dayType tal btn btn-default btn-xxs" type="button"><span class="day'.$k.'">'.$tmp.'</span></button>
					<input name="day['.$k.']" id="mday'.$k.'" type="hidden" value="full" />
				</td>
				<td style="width:80%">&nbsp;</td>
			</tr>';
	}	
	$table .= '</tbody></table>';
		
	$data['table'] = $table;
	//var_dump($data); exit;
	
	ob_clean();
	echo json_encode($data);
	exit;
	
	
	
	
	

















