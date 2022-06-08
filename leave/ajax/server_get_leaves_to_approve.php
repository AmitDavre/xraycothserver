<?
	if(session_id()==''){session_start();}
	ob_start();
	include('../../dbconnect/db_connect.php');
	include(DIR.'files/functions.php');
	include(DIR.'leave/functions.php');
	include(DIR.'files/arrays_'.$lang.'.php');
	//var_dump($leave_types); exit;
	//OF100-085
	
	updateLeaveDatabase($cid);
	
	$leave_types = getSelLeaveTypes($cid);
	$leave_settings = getLeaveTimeSettings();
	$period_start = $leave_settings['pr_leave_start'];
	$leave_periods = getMonthlyPeriod($period_start);
	$dayhours = $leave_settings['dayhours'];
	
	$where = "status = 'TA'";
	if(!empty($_REQUEST['empFilter'])){
		$where .= " AND emp_id = '".$_REQUEST['empFilter']."'";
	}
	//$where .= " date >= '".$leave_periods['start']."' AND date < '".$leave_periods['end']."' ";
	//$where = '';
	$employees = getEmployees($cid,0);
	//var_dump($employees); exit;

	$nr=0;
	
	$columns[] = array( 'db' => 'name', 'dt' => $nr, 'formatter' => function($d, $row ){return '<b>'.$d.'</b>';}); $nr++;
	
	$columns[] = array( 'db' => 'emp_id', 'dt' => $nr, 'formatter' => function($d, $row )use($employees){
		$img = $employees[$d]['image'];
		if(empty($img)){$img = 'images/profile_image.jpg';}
		$detail = ' title="<img src=../'.$img.' />"  data-toggle="tooltip" data-placement="right"';
		if($img == "images/profile_image.jpg"){$detail = '';}
		return '<img style="height:26px; display:block" src="../'.$img.'"'.$detail.' />';
		}); $nr++;
	
	$columns[] = array( 'db' => 'leave_id', 'dt' => $nr, 'formatter' => function($d, $row ){return '<a class="edit" data-id="'.$d.'" style="cursor:pointer;"><i class="fa fa-pencil-square-o fa-lg"></i></a>';}); $nr++;

	$columns[] = array( 'db' => 'leave_id', 'dt' => $nr, 'formatter' => function($d, $row ){return '<a class="details" data-id='.$d.' data-id="'.$d.'"><i class="fa fa-info-circle fa-lg"></i></a>';}); $nr++;
	
	$columns[] = array( 'db' => 'emp_id', 'dt' => $nr, 'formatter' => function($d, $row ){return '<a class="balance" data-id="'.$d.'"><i class="fa fa-balance-scale fa-lg"></i></a>';}); $nr++;
	
	$columns[] = array( 'db' => 'leave_type', 'dt' => $nr, 'formatter' => function($d, $row )use($leave_types){return '<b><span class="leaveid">'.$d.'</span></b> '.$leave_types[$d][$_SESSION['rego']['lang']];}); $nr++;
	
	$columns[] = array( 'db' => 'date', 'dt' => $nr, 'formatter' => function($d, $row ){return date('d-m-Y', strtotime($d));}); $nr++;
	
	$columns[] = array( 'db' => 'days', 'dt' => $nr, 'formatter' => function($d, $row ){return number_format($d,2);}); $nr++;
	
	$columns[] = array( 'db' => 'hours', 'dt' => $nr, 'formatter' => function($d, $row ){return number_format($d,2);}); $nr++;
	
	$columns[] = array( 'db' => 'status', 'dt' => $nr, 'formatter' => function($d, $row ){global $leave_status; return '<span class="col'.$d.'">'.$leave_status[$d].'</span>';}); $nr++;
	
	$columns[] = array( 'db' => 'certificate', 'dt' => $nr, 'formatter' => function($d, $row ){
		$icon = ' <i style="color:#c00" class="fa fa-times"></i>';
		if($d == 1){$icon = ' <i style="color:green" class="fa fa-check"></i>';}
		//return $cert[$d].$icon;
		return $icon;
	}); $nr++;

	$columns[] = array( 'db' => 'reason', 'dt' => $nr, 'formatter' => function($d, $row ){return $d;}); $nr++;
	
	$columns[] = array( 'db' => 'lock', 'dt' => $nr, 'formatter' => function($d, $row ){
		$icon = '<i style="color:#c00" class="fa fa-times-circle fa-lg"></i>';
		if($d == 1){$icon = '<i style="color:green" class="fa fa-check-square-o fa-lg"></i>';}
		//return $cert[$d].$icon;
		return $icon;
	}); $nr++;
	
	$sql_details = array(
		'user' => $my_username,
		'pass' => $my_password,
		'db'   => $my_dbcname,
		'host' => $my_database
	);
	$table = $cid.'_leaves_data';
	$primaryKey = 'emp_id';

	require(DIR.'ajax/ssp.class.php' );
	
	//var_dump(SSP::simple( $_GET, $sql_details, $table, $primaryKey, $columns, $joinQuery, $extraWhere ));exit;
	
	ob_clean();
	echo json_encode(
		SSP::simple( $_GET, $sql_details, $table, $primaryKey, $columns, $where)
	);

?>