<?
	if(session_id()==''){session_start();}
	ob_start();
	//$cid = $_SESSION['rego']['cid'];
	include('../../dbconnect/db_connect.php');
	include(DIR.'files/functions.php');
	include(DIR.'time/functions.php');
	include(DIR.'leave/functions.php');
	
	//$departments = unserialize($compinfo['departments']);
	//$departments = $departments[$_SESSION['rego']['lang']];

	//$leave_status = array('RQ'=>$lng['Pending'],'CA'=>$lng['Cancelled'],'AP'=>$lng['Approved'],'RJ'=>$lng['Rejected']);
	$leave_types = getLeaveTypes($cid);
	//var_dump($leave_types); exit;
	
	$time_settings = getTimeSettings();
	$var_allow = unserialize($time_settings['var_allow']);

	$employees = getEmployeeNameId($cid);
	$empArray = '';
	foreach($employees as $k=>$v){
		$empArray .= $k."','";
	}
	$empArray = substr($empArray, 0,-3);
	//var_dump($empArray); //exit;
	//$sql = "SELECT * FROM ".$cid."_leaves_data WHERE date = '".date('Y-m-d', strtotime($_REQUEST['sdate']))."' AND emp_id IN ('".$empArray."')";
	
	/*$date_start = '2020-01-01';//date('Y-m-d', strtotime($_REQUEST['sdate']));
	$date_end = '2020-01-31';//date('Y-m-d', strtotime($_REQUEST['edate']));
	$where = " WHERE (date BETWEEN '".$date_start."' AND '".$date_end."')";// AND plan != 'OFF' "; // 
	
	$leaves = array();
	$sql = "SELECT * FROM ".$cid."_leaves_data ".$where;
	//echo $sql;
	if($res = $dbc->query($sql)){
		while($row = $res->fetch_assoc()){
			$leaves[$row['emp_id']][] = array($row['leave_type']=>$row['days']);
		}
	}*/
	/*foreach($leaves as $k=>$v){
		$sql = "UPDATE `".$cid."_attendance` SET 
			`leave_type` = '".$dba->real_escape_string($v['type'])."',  
			`leave_days` = '".$dba->real_escape_string($v['days'])."'   
			WHERE `emp_id` = '".$k."' AND date = '".$_REQUEST['sdate']."'";
		$dbc->query($sql);
		//echo $sql; //exit;
	}*/
	//var_dump($leaves); exit;
	
	$sql_details = array(
		'user' => $my_username,
		'pass' => $my_password,
		'db'   => $my_dbcname,
		'host' => $my_database
	);
	
$table = $cid."_attendance";//$_SESSION['payroll']['emp_dbase'];//'shr0100_employees';

// Table's primary key
$primaryKey = 'emp_id';
	
	//$date = date('Y-m-d', strtotime($_REQUEST['sdate']));
	$where = "";
	//$where .= " date = '".$date."' AND plan != 'OFF' ";
	
	$date_start = date('Y-m-d', strtotime($_REQUEST['sdate']));
	$date_end = date('Y-m-d', strtotime($_REQUEST['edate']));

/*$date_start = '2020-01-31';
$date_end = '2020-01-31';
$_REQUEST['filter'] = 'itime';
*/	
	
	
	$where .= " (date BETWEEN '".$date_start."' AND '".$date_end."')";// AND plan != 'OFF' "; // 
	//$_REQUEST['filter'] = 'leave';
	if(isset($_REQUEST['filter'])){
		$filter = $_REQUEST['filter'];
		if($filter == 'leave'){
			$where .= " AND leave_type != ''";
		}
		if($filter == 'late'){
			$where .= " AND (late != '-' OR early != '-')";
		}
		if($filter == 'ot'){
			$where .= "  AND (ot1 != '-' OR ot15 != '-' OR ot2 != '-' OR ot3 != '-')";
		}
		/*if($filter == 'ok'){
			$where .= " AND ot1 = '0' AND ot15 = '0' AND ot2 = '0' AND ot3 = '0' AND late = '0' AND early = '0'";
		}*/
		
		
		if($filter == 'scan'){
			$where .= " AND (scan1 = '-' OR scan2 = '-' OR scan3 = '-' OR scan4 = '-')";
		}
		if($filter == 'itime'){
			$where .= " AND ((TIME(scan1) > TIME(f1)) OR (TIME(scan2) < TIME(u1)) OR (TIME(scan3) > TIME(f2)) OR (TIME(scan4) < TIME(u2)))";
			//$where = " TIME(scan1) > TIME(f1) OR TIME(scan2) < TIME(u1)";
		}
		if($filter == 'ctime'){
			$where .= " AND ((TIME(scan1) < TIME(f1)) AND (TIME(scan2) > TIME(u1)) AND (TIME(scan3) < TIME(f2)) AND (TIME(scan4) > TIME(u2))) AND status = 0";
		}
		
		
		if($filter == 'nappr'){
			$where .= " AND status = '0'";
		}
		if($filter == 'appr'){
			$where .= " AND status = '1'";
		}
		if($filter == 'all'){
			$where .= " AND status = '0'";
		}
		if($filter == 'off'){
			$where .= " AND plan = 'OFF'";
		}
		if($filter == 'wday'){
			$where .= " AND plan != 'OFF'";
		}
	}
	if(!empty($_REQUEST['emp_id'])){
		$where .= " AND (
			LOWER(emp_id) LIKE '%".strtolower($_REQUEST['emp_id'])."%' OR 
			LOWER(en_name) LIKE '%".strtolower($_REQUEST['emp_id'])."%' OR 
			LOWER(th_name) LIKE '%".strtolower($_REQUEST['emp_id'])."%')";
	}

	/*$sql = "SELECT * FROM ".$cid."_attendance_".$_SESSION['rego']['cur_year']." WHERE ".$where;	
	if($res = $dbc->query($sql)){
		while($row = $res->fetch_assoc()){
			$data[$row['id']] = $row['f1'].' | '.$row['scan1'];
		}
	}
	
	echo $sql;
	var_dump($data); 
	exit;*/



	//$where = "leave_type != ''";
	//var_dump($where);
	//$where .= " AND (ot1 != '-' OR ot15 != '-' OR ot2 != '-' OR ot3 != '-')";
	
	/*if(!empty($_REQUEST['team'])){
		$where .= " AND shiftteam = '".$_REQUEST['team']."'";
	}*/
	//$where = " (date BETWEEN '".$date_start."' AND '".$date_end."')";// AND plan != 'OFF' "; // 
	
	$nr=0;
	
	$columns[] = array( 'db' => 'emp_id', 'dt' => $nr, 'formatter' => function($d, $row ){return '<span class="emp_id">'.$d.'</span>';}); $nr++;
	
	$columns[] = array( 'db' => $_SESSION['rego']['lang'].'_name', 'dt' => $nr, 'formatter' => function($d, $row ){return '<span class="emp_name">'.$d.'</span>';}); $nr++;
	
	$columns[] = array( 'db' => 'id', 'dt' => $nr, 'formatter' => function($d, $row ){return '<a class="edit" data-id="'.$d.'"><i class="fa fa-edit fa-lg"></i></a>';}); $nr++;
	
	$columns[] = array( 'db' => 'status', 'dt' => $nr, 'formatter' => function($d, $row ){
		$ch = ''; 
		if($d == 1){$ch = 'checked disabled';}; 
		return '<label><input '.$ch.' type="checkbox" class="dbox checkbox notxt"><span style="z-index:0"></span></label>';
	}); $nr++;
	
	$columns[] = array( 'db' => 'date', 'dt' => $nr, 'formatter' => function($d, $row ){return '<span class="row_date">'.date('D d-m-Y', strtotime($d)).'</span>';}); $nr++;
	
	$columns[] = array( 'db' => 'plan_hrs', 'dt' => $nr, 'formatter' => function($d, $row ){
		$str = explode('|', $d);
		if($str[0] == 'HD'){ $str[0] = '<i style="color:#99f" class="fa fa-rebel fa-lg"></i>';}
		
		$tooltip = '';
		foreach($str as $k=>$v){
			if($k > 0){$tooltip .= $v.' - ';}
		}
		$tooltip = substr($tooltip, 0, -2);
		return '<div data-toggle="tooltip" data-placement="left" title="'.$tooltip.'" style="text-align:center">'.$str[0].'</div>';
	}); $nr++;
	
	$columns[] = array( 'db' => 'plan_rh', 'dt' => $nr, 'formatter' => function($d, $row ){
		if($d == 'HD'){ return '<i style="color:#99f" class="fa fa-rebel fa-lg"></i>';}
		return $d;
	}); $nr++;
	
	$columns[] = array( 'db' => 'ot_hrs', 'dt' => $nr); $nr++;
	
	$columns[] = array( 'db' => 'all_scans', 'dt' => $nr, 'formatter' => function($d, $row ){
		return '<img data-toggle="tooltip" data-placement="left" title="'.str_replace('|',' - ',$d).'" style="height:16px; display:block" src="../../images/fingerprint.png" />';
	}); $nr++;
	
	$columns[] = array( 'db' => 'scan1', 'dt' => $nr); $nr++;
	$columns[] = array( 'db' => 'scan2', 'dt' => $nr); $nr++;
	$columns[] = array( 'db' => 'scan3', 'dt' => $nr); $nr++;
	$columns[] = array( 'db' => 'scan4', 'dt' => $nr); $nr++;
	
	$columns[] = array( 'db' => 'late', 'dt' => $nr); $nr++;
	
	$columns[] = array( 'db' => 'early', 'dt' => $nr); $nr++;
	
	$columns[] = array( 'db' => 'actual_hrs', 'dt' => $nr); $nr++;
	
	$columns[] = array( 'db' => 'paid_hrs', 'dt' => $nr); $nr++;
	
	$columns[] = array( 'db' => 'ot1', 'dt' => $nr, 'formatter' => function($d, $row ){
		if(!empty($d)){
			return '<span class="emp_id">'.$d.'</span>';
		}else{
			return '-';
		}
	}); $nr++;
	
	$columns[] = array( 'db' => 'ot15', 'dt' => $nr, 'formatter' => function($d, $row ){
		if(!empty($d)){
			return '<span class="emp_id">'.$d.'</span>';
		}else{
			return '-';
		}
	}); $nr++;
	
	$columns[] = array( 'db' => 'ot2', 'dt' => $nr, 'formatter' => function($d, $row ){
		if(!empty($d)){
			return '<span class="emp_id">'.$d.'</span>';
		}else{
			return '-';
		}
	}); $nr++;
	
	$columns[] = array( 'db' => 'ot3', 'dt' => $nr, 'formatter' => function($d, $row ){
		if(!empty($d)){
			return '<span class="emp_id">'.$d.'</span>';
		}else{
			return '-';
		}
	}); $nr++;
	
	$columns[] = array( 'db' => 'leave_type', 'dt' => $nr, 'formatter' => function($d, $row )use($leave_types, $lang){
		if(!empty($d)){
			$tmp = explode('|', $d);
			//$lid = $tmp[0];
			//unset($tmp[0]);
			$str = '';
			foreach($tmp as $k=>$v){
				//$str .= '<span class="lid" data-lid="'.$k.'" data-toggle="tooltip" title="'.$leave_types[$v]['en'].'"><a class="leaveType">'.$v.'</a></span>|';
				$str .= '<a href="../leave/index.php?mn=201" data-toggle="tooltip" title="'.$leave_types[$v][$lang].'">'.$v.'</a>|';
				//$str = '<span data-toggle="tooltip" title="'.$leave_types[$d]['en'].'">'.$d.'</span>';
			}
			$str = substr($str,0,-1);
			
		}else{
			$str = '<a href="../leave/index.php?mn=201" class="xxxleaveType"><i class="fa fa-plane fa-lg"></i></a>';
		}
		return $str;
	}); $nr++;
	
	$columns[] = array( 'db' => 'leave_days', 'dt' => $nr, 'formatter' => function($d, $row ){
		if(!empty($d)){return $d;}else{return '-';}
	}); $nr++;

	$columns[] = array( 'db' => 'dilligence', 'dt' => $nr, 'formatter' => function($d, $row ){
		if($d > 0){$chk = 'checked';}else{$chk = '';}
		return '<label><input data-fld="dilligence" '.$chk.' type="checkbox" class="abox checkbox notxt" value="'.$d.'"><span></span></label>';
	}); $nr++;

	$columns[] = array( 'db' => 'shift', 'dt' => $nr, 'formatter' => function($d, $row ){
		if($d > 0){$chk = 'checked';}else{$chk = '';}
		return '<label><input data-fld="shift" '.$chk.' type="checkbox" class="abox checkbox notxt" value="'.$d.'"><span></span></label>';
	}); $nr++;

	$columns[] = array( 'db' => 'transport', 'dt' => $nr, 'formatter' => function($d, $row ){
		if($d > 0){$chk = 'checked';}else{$chk = '';}
		return '<label><input data-fld="transport" '.$chk.' type="checkbox" class="abox checkbox notxt" value="'.$d.'"><span></span></label>';
	}); $nr++;

	$columns[] = array( 'db' => 'meal', 'dt' => $nr, 'formatter' => function($d, $row ){
		if($d > 0){$chk = 'checked';}else{$chk = '';}
		return '<label><input data-fld="meal" '.$chk.' type="checkbox" class="abox checkbox notxt" value="'.$d.'"><span></span></label>';
	}); $nr++;

	$columns[] = array( 'db' => 'phone', 'dt' => $nr, 'formatter' => function($d, $row ){
		if($d > 0){$chk = 'checked';}else{$chk = '';}
		return '<label><input data-fld="phone" '.$chk.' type="checkbox" class="abox checkbox notxt" value="'.$d.'"><span></span></label>';
	}); $nr++;

	
	$columns[] = array( 'db' => 'comment', 'dt' => $nr, 'formatter' => function($d, $row ){
		$size = strlen($d);
		return '<input class="comment" size="'.$size.'" type="text" value="'.$d.'">';
	}); $nr++;
	
require(DIR.'ajax/ssp.class.php' );

//$joinQuery = "FROM $table";
//ob_clean();
echo json_encode(
	SSP::simple( $_POST, $sql_details, $table, $primaryKey, $columns, $where)
);

?>