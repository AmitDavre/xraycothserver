<?
	if(session_id()==''){session_start();}
	ob_start();
	include('../../dbconnect/db_connect.php');
	//include(DIR.'files/functions.php');
	//include(DIR.'files/arrays_'.$_SESSION['xhr']['lang'].'.php');
	
	$sql_details = array(
		'user' => $my_username,
		'pass' => $my_password,
		'db'   => $my_dbcname,
		'host' => $my_database
	);
	
	$where = "emp_id = '".$_REQUEST['emp_id']."'";
	$table = $cid.'_employee_log';
	$primaryKey = 'emp_id';

	$nr=0;
	
	$columns[] = array( 'db' => 'field',  'dt' => $nr ); $nr++;
	
	$columns[] = array( 'db' => 'prev','dt' => $nr ); $nr++;
	
	$columns[] = array( 'db' => 'new','dt' => $nr ); $nr++;
	
	$columns[] = array( 'db' => 'date','dt' => $nr, 'formatter' => function( $d, $row ){return date('d-m-Y @ H:i', strtotime($d));}); $nr++;
	
	$columns[] = array( 'db' => 'user',  'dt' => $nr ); $nr++;
	
	//$columns[] = array( 'db' => 'emp_role',  'dt' => $nr, 'formatter' => function( $d, $row )use($roles){return $roles[$d];}); $nr++;
	

	require(DIR.'ajax/ssp.class.php' );
	
	ob_clean();
	echo json_encode(
		SSP::simple( $_POST, $sql_details, $table, $primaryKey, $columns, $where)
	);

?>