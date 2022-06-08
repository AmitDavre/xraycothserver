<?
	if(session_id()==''){session_start();}
	ob_start();
	include('../../dbconnect/db_connect.php');
	include(DIR.'files/functions.php');
	include(DIR.'leave/functions.php');
	include(DIR.'files/arrays_'.$lang.'.php');
	
	updateLeaveDatabase($cid);
	
	$leave_types = getSelLeaveTypes($cid);
	$employees = getEmployees($cid);
	//var_dump($leave_types); exit;
	$leave_types[''] = '';

	$nr=0;
	
	$columns[] = array( 'db' => 'emp_id', 'dt' => $nr, 'formatter' => function($d, $row ){return '<span class="emp_id">'.$d.'</span>';}); $nr++;
	
	$columns[] = array( 'db' => 'name', 'dt' => $nr, 'formatter' => function($d, $row ){return $d;}); $nr++;
	
	$columns[] = array( 'db' => 'emp_id', 'dt' => $nr, 'formatter' => function($d, $row )use($employees){
		$img = $employees[$d]['image'];
		if(empty($img)){$img = 'images/profile_image.jpg';}
		$detail = ' title="<img src=../'.$img.' />"  data-toggle="tooltip" data-placement="right"';
		if($img == "images/profile_image.jpg"){$detail = '';}
		return '<img style="height:26px;" src="../'.$img.'"'.$detail.' />';
		}); $nr++;

	$columns[] = array( 'db' => 'leave_type', 'dt' => $nr, 'formatter' => function($d, $row )use($leave_types, $lang){return $leave_types[$d][$lang];}); $nr++;

	$columns[] = array( 'db' => 'start', 'dt' => $nr, 'formatter' => function($d, $row ){return date('D d-m-Y', strtotime($d));}); $nr++;
	
	$columns[] = array( 'db' => 'end', 'dt' => $nr, 'formatter' => function($d, $row ){return date('D d-m-Y', strtotime($d));}); $nr++;
	
	$columns[] = array( 'db' => 'days', 'dt' => $nr, 'formatter' => function($d, $row ){return '<span style="display:block;text-align:right">'.number_format($d,2).'</span>';}); $nr++;
	
	$columns[] = array( 'db' => 'status', 'dt' => $nr, 'formatter' => function($d, $row ){
		if($d != 'RQ' && $d != 'RJ' && $d != 'CA'){
			return '<a class="approve_leave"><i style="color:#ccc;cursor:not-allowed" class="fa fa-thumbs-up fa-lg"></i></a>';
		}else{
			return '<a class="approve_leave" data-id="'.$d.'" data-action="approve"><i style="color:green" class="fa fa-thumbs-up fa-lg"></i></a>';
		}
	}); $nr++;
	
	$columns[] = array( 'db' => 'status', 'dt' => $nr, 'formatter' => function($d, $row ){
		if($d != 'RQ' && $d != 'AP'){
			return '<a class="reject_leave"><i style="color:#ccc;cursor:not-allowed" class="fa fa-thumbs-down fa-lg"></i></a>';
		}else{
			return '<a class="reject_leave" data-toggle="popover" data-id='.$d.' data-action="reject"><i style="color:#c00" class="fa fa-thumbs-down fa-lg"></i></a>';
		}
	}); $nr++;
	
	$columns[] = array( 'db' => 'status', 'dt' => $nr, 'formatter' => function($d, $row ){
		if($d == 'TA' || $d == 'CA'){
			return '<a class="cancel_leave"><i style="color:#ccc;cursor:not-allowed" class="fa fa-times-circle fa-lg"></i></a>';
		}else{
			return '<a class="cancel_leave" data-toggle="popover" data-id='.$d.' data-action="cancel"><i style="color:#f63" class="fa fa-times-circle fa-lg"></i></a>';
		}
	}); $nr++;
	
	$columns[] = array( 'db' => 'status', 'dt' => $nr, 'formatter' => function($d, $row ){
		global $leave_status; 
		return '<span class="col'.$d.'">'.$leave_status[$d].'</span>';
		/*if($d == 'TA'){
			$select = '<span class="col'.$d.'">'.$leave_status[$d].'</span>';
		}else{
			$select = '<select class="selStatus col'.$d.'" style="width:auto">
				 <option '; if($d == 'RQ'){$select .= 'selected ';}
				 $select .= 'value="RQ">Requested</option>
				 <option '; if($d == 'AP'){$select .= 'selected ';}
				 $select .= 'value="AP">Approved</option>
				 <option '; if($d == 'RJ'){$select .= 'selected ';}
				 $select .= 'value="RJ">Rejected</option>
				 <option '; if($d == 'CA'){$select .= 'selected ';}
				 $select .= 'value="CA">Canceled</option>
			</select>';
		}
		return $select;*/
	}); $nr++;

	$columns[] = array( 'db' => 'reason', 'dt' => $nr ); $nr++;

	$columns[] = array( 'db' => 'certificate', 'dt' => $nr, 'formatter' => function($d, $row )use($cert){
		//return $d;
		if($d == 'NA'){
			return '<i style="color:#aaa" class="fa fa-minus"></i>';
		}else if($d == 'N'){
			return '<i style="color:#c00" class="fa fa-times fa-lg"></i>';
		}else{
			return '<i style="color:green" class="fa fa-check fa-lg"></i>';
		}
	}); $nr++;
	
	$columns[] = array( 'db' => 'emp_id', 'dt' => $nr, 'formatter' => function($d, $row ){return '<a class="balance" data-id="'.$d.'"><i class="fa fa-balance-scale fa-lg"></i></a>';}); $nr++;
	
	$columns[] = array( 'db' => 'id', 'dt' => $nr, 'formatter' => function($d, $row ){return '<a class="details" data-id='.$d.' data-id="'.$d.'"><i class="fa fa-info-circle fa-lg"></i></a>';}); $nr++;

	$columns[] = array( 'db' => 'id', 'dt' => $nr, 'formatter' => function($d, $row ){return '<a data-toggle="modal" data-target="#modalAddLeave" class="edit" data-id="'.$d.'" style="cursor:pointer;"><i class="fa fa-edit fa-lg"></i></a>';}); $nr++;
	
	//$columns[] = array( 'db' => 'id', 'dt' => $nr, 'formatter' => function($d, $row ){return '<a class="delLeave" data-id="'.$d.'"><i class="fa fa-trash fa-lg"></i></a>';}); $nr++;
	
	$columns[] = array( 'db' => 'attach', 'dt' => $nr, 'formatter' => function($d, $row )use($cert){
		//$icon = '<i class="fa fa-minus"></i>';
		if(!empty($d)){
			return '<a href="'.$d.'" target="_blank"><i class="fa fa-download fa-lg"></i></a>';
		}else{
			return '<i style="color:#ccc" class="fa fa-download fa-lg"></i>';
		}
	}); $nr++;
	

	$where = "status != 'TA' AND status != 'AP'";
	//$_REQUEST['empFilter'] = '';
	if(!empty($_REQUEST['empFilter'])){
		//$where .= "emp_id = '".$_REQUEST['empFilter']."'";
	}
	if(!empty($_REQUEST['statFilter'])){
		if(!empty($where)){$where .= " AND ";}
		//$where .= $_REQUEST['statFilter'];
	}
	//$where = "";
	$sql_details = array(
		'user' => $my_username,
		'pass' => $my_password,
		'db'   => $my_dbcname,
		'host' => $my_database
	);
	
	$table = $cid.'_leaves';//$_SESSION['payroll']['emp_dbase'];//'shr0100_employees';
	$primaryKey = 'emp_id';
	
	require(DIR.'ajax/ssp.class.php' );
	
	//var_dump(SSP::simple( $_POST, $sql_details, $table, $primaryKey, $columns, $where ));exit;
	
	ob_clean();
	echo json_encode(
		SSP::simple( $_POST, $sql_details, $table, $primaryKey, $columns, $where)
	);

?>