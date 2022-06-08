<?
	if(session_id()==''){session_start();}
	ob_start();
	include('../../dbconnect/db_connect.php');
	include(DIR.'files/functions.php');
	include(DIR.'leave/functions.php');
	include(DIR.'files/arrays_'.$lang.'.php');


	$sbranches = str_replace(',', "','", $_SESSION['rego']['sel_branches']);
	$sdivisions = str_replace(',', "','", $_SESSION['rego']['sel_divisions']);
	$sdepartments = str_replace(',', "','", $_SESSION['rego']['sel_departments']);
	$steams = str_replace(',', "','", $_SESSION['rego']['sel_teams']);


	
	updateLeaveDatabase($cid);
	
	$leave_types = getSelLeaveTypes($cid);
	$employees = getEmployees($cid,0);
	//var_dump($leave_types); exit;
	$leave_types[''] = '';

	$nr=0;
	
	$columns[] = array( 'db' => 'emp_id', 'dt' => $nr, 'formatter' => function($d, $row ){return '<span class="emp_id">'.$d.'</span>';}); $nr++;
	
	$columns[] = array( 'db' => 'name', 'dt' => $nr, 'formatter' => function($d, $row ){return '<a class="selEmp">'.$d.'</a>';}); $nr++;
	
	$columns[] = array( 'db' => 'emp_id', 'dt' => $nr, 'formatter' => function($d, $row )use($employees){
		$img = $employees[$d]['image'];
		if(empty($img)){$img = 'images/profile_image.jpg';}
		$detail = ' title="<img src=../'.$img.' />"  data-toggle="tooltip" data-placement="right"';
		if($img == "images/profile_image.jpg"){$detail = '';}
		return '<img style="height:28px;" src="../'.$img.'"'.$detail.' />';
		}); $nr++;

	$columns[] = array( 'db' => 'leave_type', 'dt' => $nr, 'formatter' => function($d, $row )use($leave_types, $lang){return '<b>'.$d.'</b>&nbsp; '.$leave_types[$d][$lang];}); $nr++;

	$columns[] = array( 'db' => 'start', 'dt' => $nr, 'formatter' => function($d, $row ){return '<span class="daterow" style="display:none;">'.date('Y-m-d', strtotime($d)).'</span>'.date('D d-m-Y', strtotime($d));}); $nr++;
	
	$columns[] = array( 'db' => 'end', 'dt' => $nr, 'formatter' => function($d, $row ){return date('D d-m-Y', strtotime($d));}); $nr++;
	
	$columns[] = array( 'db' => 'days', 'dt' => $nr, 'formatter' => function($d, $row ){return '<span style="display:block;text-align:right">'.number_format($d,2).'</span>';}); $nr++;
	
	$columns[] = array( 'db' => 'status', 'dt' => $nr, 'formatter' => function($d, $row ){

		if($_SESSION['rego']['leave_application']['view'] == '1' && $_SESSION['rego']['leave_application']['approve'] == '1')
		{
			if($d != 'RQ' && $d != 'RJ' && $d != 'CA'){
				return '<a class="approve_leave"><i style="color:#ccc;cursor:not-allowed" class="fa fa-thumbs-up fa-lg"></i></a>';
			}else{
				return '<a class="approve_leave" data-id="'.$d.'" data-action="approve"><i style="color:green" class="fa fa-thumbs-up fa-lg"></i></a>';
			}
		}
		else if($_SESSION['rego']['leave_application']['view'] == '1')
		{
			return '';
		}



	}); $nr++;
	
	$columns[] = array( 'db' => 'status', 'dt' => $nr, 'formatter' => function($d, $row ){

		if($_SESSION['rego']['leave_application']['view'] == '1' && $_SESSION['rego']['leave_application']['approve'] == '1')
		{
			if($d != 'RQ' && $d != 'AP'){
				return '<a class="reject_leave"><i style="color:#ccc;cursor:not-allowed" class="fa fa-thumbs-down fa-lg"></i></a>';
			}else{
				return '<a class="reject_leave" data-toggle="popOver" data-id='.$d.' data-action="reject"><i style="color:#c00" class="fa fa-thumbs-down fa-lg"></i></a>';
			}
		}
		else if($_SESSION['rego']['leave_application']['view'] == '1')
		{
			return '';
		}



	}); $nr++;
	
	$columns[] = array( 'db' => 'status', 'dt' => $nr, 'formatter' => function($d, $row ){

		if($_SESSION['rego']['leave_application']['view'] == '1' && $_SESSION['rego']['leave_application']['edit'] == '1')
		{
			if($d == 'TA' || $d == 'CA'){
			return '<a class="cancel_leave"><i style="color:#ccc;cursor:not-allowed" class="fa fa-times-circle fa-lg"></i></a>';
			}else{
				return '<a class="cancel_leave" data-toggle="popOver" data-id='.$d.' data-action="cancel"><i style="color:#f63" class="fa fa-times-circle fa-lg"></i></a>';
			}
			
		}
		else if($_SESSION['rego']['leave_application']['view'] == '1')
		{
		}




	}); $nr++;
	
	$columns[] = array( 'db' => 'status', 'dt' => $nr, 'formatter' => function($d, $row ){
		global $leave_status; 
		return '<span class="col'.$d.'">'.$leave_status[$d].'</span>';
		if($d == 'TA'){
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
		return $select;
	}); $nr++;

	$columns[] = array( 'db' => 'reason', 'dt' => $nr ); $nr++;

	$columns[] = array( 'db' => 'attach', 'dt' => $nr, 'formatter' => function($d, $row )use($cert){
		$icon = '<i class="fa fa-minus"></i>';
		if(!empty($d)){
			return '<a href="'.$d.'" target="_blank"><i class="fa fa-download fa-lg"></i></a>';
		}else{
			return '<i style="color:#ccc" class="fa fa-download fa-lg"></i>';
		}
	}); $nr++;
	
	$columns[] = array( 'db' => 'emp_id', 'dt' => $nr, 'formatter' => function($d, $row ){return '<a class="balance" data-id="'.$d.'"><i class="fa fa-balance-scale fa-lg"></i></a>';}); $nr++;
	
	$columns[] = array( 'db' => 'id', 'dt' => $nr, 'formatter' => function($d, $row ){return '<a class="details" data-id='.$d.' data-id="'.$d.'"><i class="fa fa-info-circle fa-lg"></i></a>';}); $nr++;

	$columns[] = array( 'db' => 'id', 'dt' => $nr, 'formatter' => function($d, $row ){

		if($_SESSION['rego']['leave_application']['view'] == '1' && $_SESSION['rego']['leave_application']['edit'] == '1')
		{
			return '<a data-toggle="modal" class="edit" data-id="'.$d.'" style="cursor:pointer;"><i class="fa fa-edit fa-lg"></i></a>';
		}
		else if($_SESSION['rego']['leave_application']['view'] == '1')
		{
			return '';
		}

	}); $nr++;
	
	$columns[] = array( 'db' => 'id', 'dt' => $nr, 'formatter' => function($d, $row ){

		if($_SESSION['rego']['leave_application']['view'] == '1' && $_SESSION['rego']['leave_application']['del'] == '1')
		{
			return '<a class="delLeave" data-id="'.$d.'"><i class="fa fa-trash fa-lg"></i></a>';
		}
		else if($_SESSION['rego']['leave_application']['view'] == '1')
		{
			return '';
		}


		

	}); $nr++;
	
	$where = '';
	//$_REQUEST['empFilter'] = '';
	if(!empty($_REQUEST['empFilter']))
	{
		$where .= "emp_id = '".$_REQUEST['empFilter']."'";
	}


	if(!empty($_REQUEST['statFilter']))
	{
		if(!empty($where))
		{
			$where .= " AND ";

		}

		$where .= $_REQUEST['statFilter'];

		$where .= " AND emp_group = '".$_SESSION['rego']['emp_group']."'";
		$where .= " AND branch IN ('".$sbranches."')";
		$where .= " AND division IN ('".$sdivisions."')";
		$where .= " AND department IN ('".$sdepartments."')";
		$where .= " AND team IN ('".$steams."')";


	}
	else
	{
		$where = "  emp_group = '".$_SESSION['rego']['emp_group']."'";
		$where .= " AND branch IN ('".$sbranches."')";
		$where .= " AND division IN ('".$sdivisions."')";
		$where .= " AND department IN ('".$sdepartments."')";
		$where .= " AND team IN ('".$steams."')";

	}


	//$where = "";

	// echo $where;

	

	// get leave period for the selected year 
	$getCurrentDateForQuery = date('Y-m-d');

	$res20008 = $dbc->query("SELECT * FROM ".$cid."_leave_periods WHERE DATE('".$getCurrentDateForQuery."') BETWEEN DATE(leave_period_start) AND DATE(leave_period_end)"); 
	if($row20008 = $res20008->fetch_assoc()){

		$periodStartDate8 = $row20008['leave_period_start'];
		$periodEndDate8   = $row20008['leave_period_end'];

	}


	$currentyear = '%'.$cur_year.'%';
	if(!empty($where))
	{
		// $where .= " AND (start LIKE '".$currentyear."'  OR end LIKE '".$currentyear."'  )";
		$where .= " AND (DATE(start) BETWEEN '".$periodStartDate8."' AND '".$periodEndDate8."' OR DATE(end) BETWEEN '".$periodStartDate8."' AND '".$periodEndDate8."')";
	}
	else
	{
		// $where .= "  start LIKE '".$currentyear."'  OR end LIKE '".$currentyear."'  ";
		$where .= "  DATE(start) BETWEEN '".$periodStartDate8."' AND '".$periodEndDate8."' OR DATE(end) BETWEEN '".$periodStartDate8."' AND '".$periodEndDate8."'  ";
	}

	






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
