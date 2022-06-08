<?php
	
	if(session_id()==''){session_start();}
	ob_start();
	include('../../../dbconnect/db_connect.php');
	include(DIR.'files/functions.php');
	//include(DIR.'files/payroll_functions.php');
	
	$sql_details = array(
		'user' => $my_username,
		'pass' => $my_password,
		'db'   => $my_dbcname,
		'host' => $my_database
	);
	
	$table = $_SESSION['rego']['payroll_dbase'];//'shr0100_employees';
	$primaryKey = 'emp_id';

	$nr=0;
	
	$columns[] = array( 'db' => 'emp_id', 'dt' => $nr); $nr++;
	
	$columns[] = array( 'db' => 'emp_name_'.$_SESSION['rego']['lang'], 'dt' => $nr); $nr++;
	
	//$columns[] = array( 'db' => 'entity', 'dt' => $nr); $nr++;

	//$columns[] = array( 'db' => 'branch', 'dt' => $nr); $nr++;

	$columns[] = array( 'db' => 'emp_group', 'dt' => $nr); $nr++;

	$columns[] = array( 'db' => 'department', 'dt' => $nr); $nr++;
	
	$columns[] = array( 'db' => 'department', 'dt' => $nr, 'formatter' => function($d, $row )use($departments){return $departments[$d][$_SESSION['rego']['lang']];}); $nr++;

	$columns[] = array( 'db' => 'position', 'dt' => $nr, 'formatter' => function($d, $row )use($positions){return $positions[$d][$_SESSION['rego']['lang']];}); $nr++;

	$columns[] = array( 'db' => 'month', 'dt' => $nr); $nr++;

	$columns[] = array( 'db' => 'gross_income', 'dt' => $nr, 'formatter' => function($d, $row ){return number_format($d,2);}); $nr++;
	
	$columns[] = array( 'db' => 'tot_deductions', 'dt' => $nr, 'formatter' => function($d, $row ){return number_format($d,2);}); $nr++;
	
	$columns[] = array( 'db' => 'net_income', 'dt' => $nr, 'formatter' => function($d, $row ){return number_format($d,2);}); $nr++;
	
	$columns[] = array( 'db' => 'id', 'dt' => $nr, 'formatter' => function($d, $row ){return '<a class="empPrint" data-id="'.$d.'"><i class="fa fa-print fa-lg"></i></a>';}); $nr++;
	
	

	require(DIR.'ajax/ssp.class.php' );
	
	/*$sbranches = str_replace(',', "','", $_SESSION['rego']['selpr_branches']);
	$sdivisions = str_replace(',', "','", $_SESSION['rego']['selpr_divisions']);
	$sdepartments = str_replace(',', "','", $_SESSION['rego']['selpr_departments']);
	$steams = str_replace(',', "','", $_SESSION['rego']['selpr_teams']);
	
	$where =  "month = '".$_SESSION['rego']['cur_month']."' AND paid != 'H'";    
	$where .= " AND emp_group = '".$_SESSION['rego']['emp_group']."'";
	if($_REQUEST['filter'] != 'all'){
		$where .= " AND calc_tax = '".$_REQUEST['filter']."'";
	}
	$where .= " AND entity = '".$_SESSION['rego']['selpr_entities']."'";
	$where .= " AND branch IN ('".$sbranches."')";
	$where .= " AND division IN ('".$sdivisions."')";
	$where .= " AND department IN ('".$sdepartments."')";
	$where .= " AND team IN ('".$steams."')";*/
	$where = '';

	//$where .=  "month = '".$_SESSION['rego']['cur_month']."' AND paid != 'H'"; 
	
	//var_dump(SSP::simple( $_GET, $sql_details, $table, $primaryKey, $columns, $where ));exit;
	echo json_encode(
		SSP::complex( $_POST, $sql_details, $table, $primaryKey, $columns, '', $where)
	);






