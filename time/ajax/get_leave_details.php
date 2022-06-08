<?
	if(session_id()==''){session_start(); ob_start();}
	include("../../dbconnect/db_connect.php");
	include(DIR.'time/functions.php');
	
	//var_dump($_REQUEST); exit;

	if(!isset($_REQUEST['lid'])){$_REQUEST['lid'] = 0;}
	$date = date('Y-m-d', strtotime($_REQUEST['date']));
	//$date = '2019-01-29';
	//$_REQUEST['type'] = 'FL';
		
	$img = '../../'.$cid."/employees/img/".$_REQUEST['id'].'.jpg';
	if(file_exists($img)){   
		$data['img'] = '../'.$cid."/employees/img/".$_REQUEST['id'].'.jpg';                       
	}else{
		$data['img'] = '../images/profile_image.jpg';                        
	}	

	if($_REQUEST['lid'] == 0){
		$employee = getEmployeeInfo($cid, $_REQUEST['id']);
		$data['leave_id'] = 0;
		$data['emp_id'] = $employee['emp_id'];
		$data['en_name'] = $employee['emp_id'].' - '.$employee['en_name'];
		$data['th_name'] = $employee['emp_id'].' - '.$employee['th_name'];
		$data['name'] = $employee['emp_id'].' - '.$employee[$_SESSION['xhr']['lang'].'_name'];
		$data['department'] = $departments[$employee['dept_code']];
		$data['position'] = $positions[$employee['position_code']];
		$data['dept_code'] = $employee['dept_code'];
		$data['position_code'] = $employee['position_code'];
		$data['group_code'] = $employee['group_code'];
		$data['branch_code'] = $employee['branch_code'];
		$data['planned'] = 0;
		$data['paid'] = 0;
		$data['leaveperiod'] = 0;
		$data['type'] = '';
		$data['days'] = '';
		$data['start'] = '';
		$data['end'] = '';
		$data['certificate'] = '';
		$data['attach'] = '';
		$data['status'] = '';
		//$data['details'] = array();
		//$data['date_range'] = array();
		$data['comment'] = '';
	
		$data['table'] = '';
		//var_dump($data); exit;
		
		ob_clean();
		echo json_encode($data);
		exit;
		
	}
	
	
	//var_dump($employee); //exit;
	//var_dump($_REQUEST); exit;
	
	//$_REQUEST['id'] = '1';

	//$leave_status = array('RQ'=>'Pending','CA'=>'Cancelled','AP'=>'Approved','RJ'=>'Rejected','PE'=>'Pending');
	$leave_types = getLeaveTypes($cid);
	foreach($leave_types as $k=>$v){
		if($v['min_request'] == 'hrs'){$leave_type_hrs[$k] = $v;}
		if($v['min_request'] == 'half'){$leave_type_half[$k] = $v;}
	}
	
	//ob_clean();
	//var_dump($leave_types); exit;
	//$_REQUEST['id'] = '10093_LW';
	
	$leave_id = $_REQUEST['lid'];
	/*$res = $dbc->query("SELECT * FROM ".$cid."_leaves_data_".$_SESSION['xhr']['cur_year']." WHERE emp_id = '".$_REQUEST['id']."' AND date = '".$date."' AND leave_type = '".$_REQUEST['type']."'"); 
	if($row = $res->fetch_assoc()){
		$leave_id = $row['leave_id'];
	}*/
	//var_dump($leave_id); //exit;
	
	$res = $dbc->query("SELECT * FROM ".$cid."_leaves WHERE id = '".$leave_id."'"); 
	if($row = $res->fetch_assoc()){
		$data['leave_id'] = $leave_id;
		$data['emp_id'] = $row['emp_id'];
		$data['en_name'] = $row['en_name'];
		$data['th_name'] = $row['th_name'];
		$data['name'] = $row[$_SESSION['xhr']['lang'].'_name'];
		$data['department'] = $departments[$row['dept_code']];
		$data['position'] = $positions[$row['position_code']];
		$data['dept_code'] = $row['dept_code'];
		$data['position_code'] = $row['position_code'];
		$data['group_code'] = $row['group_code'];
		$data['branch_code'] = $row['branch_code'];
		$data['planned'] = $row['planned'];
		$data['paid'] = $row['paid'];
		$data['leaveperiod'] = $row['leaveperiod'];
		$data['type'] = $row['type'];
		$data['days'] = $row['days'];
		//$data['awarded'] = $row['awarded'];
		$data['start'] = date('d-m-Y', strtotime($row['start']));
		$data['end'] = date('d-m-Y', strtotime($row['end']));
		//$data['nstart'] = $row['start'];
		//$data['nend'] = $row['end'];
		$data['certificate'] = $row['certificate'];
		$data['attach'] = $row['attach'];
		$data['status'] = $row['status'];
		$data['details'] = unserialize($row['details']);
		$data['date_range'] = unserialize($row['date_range']);
		$data['comment'] = $row['comment'];
		//var_dump($data);
	}else{
		$data = array();
		echo json_encode('error');;
	}
	
	//var_dump($data); exit;

	if($data['leaveperiod'] == 'multi'){
		
		$table = '<table class="detailTable" border="0" style="width:100%">';
		foreach($data['date_range'] as $k => $v){
			$style = 'style="border-bottom:1px solid #eee"';
			if($k == count($data['date_range'])){$style = '';}
			$table .= '<tr><td>
			<tr '.$style.'>
				<td style="vertical-align:top; border:0">
					<input style="width:120px" class="nofocus" readonly name="details['.$k.'][date]" type="text" value="'.$v['date'].'">
				</td>
				<td style="vertical-align:top">
					<select style="width:auto;" data-id="'.$k.'" class="multiFullDay" name="details['.$k.'][day]">
						<option ';
						if($v['day'] == 'full'){$table .=  'selected ';}
						$table .= 'value="full">Full day</option>
						<option ';
						if($v['day'] == 'half'){$table .=  'selected ';}
						$table .= 'value="half">Half day</option>
					</select>
				</td>
				<td class="multiHalf'.$k.'" style="vertical-align:top; display:';
					if($v['day'] == 'full'){$table .=  'none';}
					$table .= '">
					<select style="width:auto;" data-id="'.$k.'" name="details['.$k.'][half]">
						<option ';
						if($v['half'] == 1){$table .=  'selected ';}
						$table .= 'value="1">First half</option>
						<option ';
						if($v['half'] == 2){$table .=  'selected ';}
						$table .= 'value="2">Second half</option>
					</select>
				</td>
				<td style="width:100%; border:0;"></td>
			</tr>';
		};
		$table .= '</table>';

	}else{
		$details = $data['details'][0];
		//var_dump($details);
		$table = '<table class="detailTable" border="0" style="width:auto">
			<tr>
				<td style="vertical-align:top; border:0">
					<input style="width:120px" class="nofocus" readonly name="details[0][date]" type="text" value="'.$details['date'].'">
				</td>
				<td style="vertical-align:top">
					<select style="width:auto;" id="oneFullDay" name="details[0][day]">
						<option ';
						if($details['day'] == 'full'){$table .= 'selected';}
						$table .= ' value="full">Full day</option>
						<option ';
						if($details['day'] == 'half'){$table .= 'selected';}
						$table .= ' value="half">Half day</option>
						<option ';
						if($details['day'] == 'hrs'){$table .= 'selected';}
						$table .= ' value="hrs">Hours</option>
					</select>
				</td>
				<td id="oneHalf" style="vertical-align:top; display:';
				if($details['day'] == 'full'){$table .= 'none';}
				$table .= '">
					<div class="xsubinfo">
						First half :<br>
						Second half :
					</div>
					<div style="float:right">
					<input type="hidden" name="details[0][half][1]" value="0">
					<select id="halfdayLeave1" name="details[0][half][1]" style="width:100%">
						<option selected value="0">No leave</option>';
						foreach($leave_type_half as $k=>$v){
							$table .= '<option ';
							if($details['half'][1] == $k){$table .= 'selected';}
							$table .= ' value="'.$k.'">'.$v['en'].'</option>';
						}
					$table .= '</select><br>
					<input type="hidden" name="details[0][half][2]" value="0">
					<select id="halfdayLeave2" name="details[0][half][2]" style="width:100%">
						<option selected value="0">No leave</option>';
						foreach($leave_type_half as $k=>$v){
							$table .= '<option ';
							if($details['half'][2] == $k){$table .= 'selected';}
							$table .= ' value="'.$k.'">'.$v['en'].'</option>';
						}
					$table .= '</select>
					</div>
				</td>
				<td id="oneHours" style="display:none; vertical-align:top; border:0">
					<div class="xsubinfo">
						Hours :<br>
						Hours :
					</div>
					<div style="float:right">
					
					<select id="dayHours1" style="width:auto; margin-right:2px" name="details[0][hours][1]">';
						for($i=0; $i<=12; $i++){
							$table .= '<option ';
							if($details['hours']['hrs'][1] == $k){$table .= 'selected';}
							$table .= ' value="'.$i.'">'.$i.'</option>';
						}
					$table .= '</select>
					<input type="hidden" name="details[0][leave][1]" value="0">
					<select id="hoursLeave1" style="width:auto" name="details[0][leave][1]">
						<option disabled selected value="0">No leave</option>';
						foreach($leave_type_hrs as $k=>$v){
							$table .= '<option ';
							if($details['hours']['leave'][1] == $k){$table .= 'selected';}
							$table .= ' value="'.$k.'">'.$v['en'].'</option>';
						}
					$table .= '</select><br>
					<select id="dayHours2" style="width:auto; margin-right:2px" name="details[0][hours][2]">';
						for($i=0; $i<=12; $i++){
							$table .= '<option ';
							if($details['hours']['hrs'][2] == $k){$table .= 'selected';}
							$table .= ' value="'.$i.'">'.$i.'</option>';
						}
					$table .= '</select>
					<input type="hidden" name="details[0][leave][2]" value="0">
					<select id="hoursLeave2" name="details[0][leave][2]" style="width:auto">
						<option disabled selected value="0">No leave</option>';
						foreach($leave_type_hrs as $k=>$v){
							$table .= '<option ';
							if($details['hours']['leave'][2] == $k){$table .= 'selected';}
							$table .= ' value="'.$k.'">'.$v['en'].'</option>';
						}
					$table .= '</select>
					</div>
				</td>
				
				
				<td id="oneFull" style="display:';
				if($details['day'] != 'full'){$table .= 'none';}
				$table .= '; vertical-align:top; border:0">
					<input type="hidden" name="details[0][full]" value="0">
					<select id="fullDayLeave" name="details[0][full]" style="width:auto">
						<option disabled selected value="0">Select leave type</option>';
						foreach($leave_types as $k=>$v){
							$table .= '<option ';
							if($details['full'] == $k){$table .= 'selected';}
							$table .= ' value="'.$k.'">'.$v['en'].'</option>';
						}
					$table .= '</select>
				</td>
			</tr>';
		$table .= '</table>';
		
	}
	
	
	$data['table'] = $table;

	//var_dump($data); exit;

	ob_clean();
	echo json_encode($data);
	exit;
	
	
	
	
	

















