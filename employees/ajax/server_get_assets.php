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
	$table = $cid.'_employee_assets';
	$primaryKey = 'id';

	$nr=0;
	
	$columns[] = array( 'db' => 'asset','dt' => $nr ); $nr++;
	
	$columns[] = array( 'db' => 'description','dt' => $nr ); $nr++;
	
	$columns[] = array( 'db' => 'assign_date','dt' => $nr ); $nr++;
	
	$columns[] = array( 'db' => 'return_date','dt' => $nr ); $nr++;
	
	$columns[] = array( 'db' => 'id','dt' => $nr); $nr++;
	
	require(DIR.'ajax/ssp.class.php' );
	
	ob_clean();
	echo json_encode(
		SSP::simple( $_GET, $sql_details, $table, $primaryKey, $columns, $where)
	);