<?
	
	if(session_id()==''){session_start();}
	ob_start();
	include('../../dbconnect/db_connect.php');
	include(DIR.'files/arrays_'.$lang.'.php');
	
	$sql_details = array(
		'user' => $my_username,
		'pass' => $my_password,
		'db'   => $my_dbcname,
		'host' => $my_database
	);
	
	$where = "emp_id = '".$_REQUEST['emp_id']."'";
	$table = $cid.'_employee_discipline';
	$primaryKey = 'id';

	$nr=0;
	
	$columns[] = array( 'db' => 'date','dt' => $nr, 'formatter' => function( $d, $row ){return date('d-m-Y', strtotime($d));}); $nr++;
	
	$columns[] = array( 'db' => 'warning','dt' => $nr, 'formatter' => function( $d, $row )use($warnings){return $warnings[$d];}); $nr++;
	
	$columns[] = array( 'db' => 'violation','dt' => $nr, 'formatter' => function( $d, $row )use($violations){return $violations[$d];}); $nr++;
	
	$columns[] = array( 'db' => 'status','dt' => $nr, 'formatter' => function( $d, $row )use($oc_status){return $oc_status[$d];}); $nr++;
	
	$columns[] = array( 'db' => 'id','dt' => $nr); $nr++;
	
	require(DIR.'ajax/ssp.class.php' );

	echo json_encode(
		SSP::simple( $_POST, $sql_details, $table, $primaryKey, $columns, $where)
	);