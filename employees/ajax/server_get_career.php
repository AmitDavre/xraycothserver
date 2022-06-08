<?
	if(session_id()==''){session_start();}
	ob_start();
	
	include('../../dbconnect/db_connect.php');

	$sql_details = array(
		'user' => $my_username,
		'pass' => $my_password,
		'db'   => $my_dbcname,
		'host' => $my_database
	);
	
	$where = "emp_id = '".$_REQUEST['emp_id']."'";
	$table = $cid.'_employee_career';
	$primaryKey = 'id';

	$nr=0;
	
	$columns[] = array( 'db' => 'start_date','dt' => $nr); $nr++;

	$columns[] = array( 'db' => 'position','dt' => $nr); $nr++;
	
	$columns[] = array( 'db' => 'department','dt' => $nr); $nr++;
	
	$columns[] = array( 'db' => 'salary','dt' => $nr); $nr++;
	
	$columns[] = array( 'db' => 'id','dt' => $nr); $nr++;

	require(DIR.'ajax/ssp.class.php' );

	echo json_encode(
		SSP::simple( $_GET, $sql_details, $table, $primaryKey, $columns, $where)
	);