<?
	if(session_id()==''){session_start(); ob_start();}
	//$cid = $_SESSION['xhr']['cid'];
	include('../../dbconnect/db_connect.php');

	$ot_type = array('paid'=>'OT paid','comp'=>'Compensation leave');
	
	$sql_details = array(
		'user' => $my_username,
		'pass' => $my_password,
		'db'   => $my_dbcname,
		'host' => $my_database
	);
	
	$table = $cid.'_ot_requests';//$_SESSION['payroll']['emp_dbase'];//'shr0100_employees';
	
	$primaryKey = 'emp_id';

	$nr=0;
	
	$columns[] = array( 'db' => 'type', 'dt' => $nr, 'formatter' => function($d, $row )use($ot_type){
		return $ot_type[$d];
	}); $nr++;
	
	$columns[] = array( 'db' => 'start', 'dt' => $nr, 'formatter' => function($d, $row ){return date('d-m-Y', strtotime($d));}); $nr++;
	$columns[] = array( 'db' => 'end', 'dt' => $nr, 'formatter' => function($d, $row ){return date('d-m-Y', strtotime($d));}); $nr++;
	$columns[] = array( 'db' => 'hours', 'dt' => $nr); $nr++;
	$columns[] = array( 'db' => 'status', 'dt' => $nr, 'formatter' => function($d, $row ){global $leave_status; return '<span class="col'.$d.'">'.$leave_status[$d].'</span>';}); $nr++;
	$columns[] = array( 'db' => 'created', 'dt' => $nr); $nr++;
	$columns[] = array( 'db' => 'updated', 'dt' => $nr); $nr++;
	$columns[] = array( 'db' => 'updated_by_name', 'dt' => $nr); $nr++;
	$columns[] = array( 'db' => 'remarks', 'dt' => $nr); $nr++;
	

	require('../../ajax/ssp.class.php' );
	
	//$joinQuery = "FROM $table";
	$where = "emp_id = '".$_REQUEST['emp_id']."'";        
	//var_dump(SSP::simple( $_GET, $sql_details, $table, $primaryKey, $columns, $joinQuery, $extraWhere ));exit;
	ob_clean();
	echo json_encode(
		SSP::simple( $_GET, $sql_details, $table, $primaryKey, $columns, $where)
	);

?>