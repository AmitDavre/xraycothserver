<?
	if(session_id()==''){session_start();}
	ob_start();
	include('../../../../dbconnect/db_connect.php');
	include(DIR.'files/functions.php');

	$sql_details = array(
		'user' => $my_username,
		'pass' => $my_password,
		'db'   => $my_dbcname,
		'host' => $my_database
	);
	

	$sbranches = str_replace(',', "','", $_SESSION['rego']['sel_branches']);
	$sdivisions = str_replace(',', "','", $_SESSION['rego']['sel_divisions']);
	$sdepartments = str_replace(',', "','", $_SESSION['rego']['sel_departments']);
	$steams = str_replace(',', "','", $_SESSION['rego']['sel_teams']);
	
	// $where = "emp_group = '".$_SESSION['rego']['emp_group']."'";
	$where = " branch IN ('".$sbranches."')";
	$where .= " AND division IN ('".$sdivisions."')";
	$where .= " AND department IN ('".$sdepartments."')";
	$where .= " AND team IN ('".$steams."')";



	
	$table = $cid.'_consent_letter';
	$primaryKey = 'id';

	$nr=0;
	
	$columns[] = array( 'db' => 'emp_id','dt' => $nr ); $nr++;
	
	$columns[] = array( 'db' => 'en_name','dt' => $nr ); $nr++;
	
	$columns[] = array( 'db' => 'department', 'dt' => $nr, 'formatter' => function($d, $row )use($departments){return $departments[$d][$_SESSION['rego']['lang']];}); $nr++;

	$columns[] = array( 'db' => 'position', 'dt' => $nr, 'formatter' => function($d, $row )use($positions){return $positions[$d][$_SESSION['rego']['lang']];}); $nr++;

	$columns[] = array( 'db' => 'emp_id', 'dt' => $nr, 'formatter' => function($d, $row ){return '<a class="empPrint" data-id="'.$d.'"><i class="fa fa-print fa-lg"></i></a>';}); $nr++;
	// $columns[] = array( 'db' => 'emp_id', 'dt' => $nr, 'formatter' => function($d, $row ){return '<a class="empView" data-id="'.$d.'"><i class="fa fa-eye fa-lg"></i></a>';}); $nr++;
	$columns[] = array( 'db' => 'id', 'dt' => $nr, 'formatter' => function($d, $row ){return '<a class="emptrash" data-id="'.$d.'"><i class="fa fa-trash fa-lg"></i></a>';}); $nr++;

	
	require(DIR.'ajax/ssp.class.php' );
	
	ob_clean();
	echo json_encode(
		// SSP::simple( $_GET, $sql_details, $table, $primaryKey, $columns, $where)
		SSP::complex( $_POST, $sql_details, $table, $primaryKey, $columns, '', $where)

	);