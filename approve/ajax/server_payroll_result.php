<?php

	if(session_id()==''){session_start();}
	ob_start();
	include('../../dbconnect/db_connect.php');
	include(DIR.'files/functions.php');
	//include(DIR.'files/payroll_functions.php');
	
	$sql_details = array(
		'user' => $my_username,
		'pass' => $my_password,
		'db'   => $my_dbcname,
		'host' => $my_database
	);
	
	$data = array();
	
	$sql = "SELECT * FROM ".$_SESSION['rego']['payroll_dbase']." WHERE month = ".$_SESSION['rego']['cur_month'];
	if($res = $dbc->query($sql)){
		while($row = $res->fetch_assoc()){
			$data[$row['id']]['other_income'] = $row['other_income'] + $row['notice_payment'] + $row['remaining_salary'] + $row['paid_leave'] + $row['severance'];
			$data[$row['id']]['deductions'] = $row['tot_deduct_before'] + $row['tot_deduct_after'];
			$data[$row['id']]['pension'] = $row['pvf_employee'] + $row['psf_employee'];
		}
	}

	//var_dump($data); //exit;

	$table = $_SESSION['rego']['payroll_dbase'];//'shr0100_employees';
	$primaryKey = 'emp_id';

	$nr=0;
	
	$columns[] = array( 'db' => 'emp_id', 'dt' => $nr, 'formatter' => function($d, $row ){return '<span id="emp_id">'.$d.'</span>';}); $nr++;
	
	$columns[] = array( 'db' => 'emp_name_'.$_SESSION['rego']['lang'], 'dt' => $nr, 'formatter' => function($d, $row ){return '<span class="emp_name">'.$d.'</span>';}); $nr++;
	
	$columns[] = array( 'db' => 'emp_id', 'dt' => $nr, 'formatter' => function($d, $row )use($data){return '<a data-id="'.$d.'" class="showtaxcalc"><i class="fa fa-calculator" style="font-size:14px"></i></a>';}); $nr++;
	
	$columns[] = array( 'db' => 'paid_days', 'dt' => $nr, 'formatter' => function($d, $row ){return number_format($d,2);}); $nr++;
	
	$columns[] = array( 'db' => 'salary', 'dt' => $nr, 'formatter' => function($d, $row ){return number_format($d,2);}); $nr++;
	
	$columns[] = array( 'db' => 'total_fix_allow', 'dt' => $nr, 'formatter' => function($d, $row ){return number_format($d,2);}); $nr++;
	
	$columns[] = array( 'db' => 'total_otb', 'dt' => $nr, 'formatter' => function($d, $row ){return number_format($d,2);}); $nr++;
	
	$columns[] = array( 'db' => 'total_var_allow', 'dt' => $nr, 'formatter' => function($d, $row ){return number_format($d,2);}); $nr++;
	
	$columns[] = array( 'db' => 'tot_absence', 'dt' => $nr, 'formatter' => function($d, $row ){return number_format($d,2);}); $nr++;
	
$columns[] = array( 'db' => 'id', 'dt' => $nr, 'formatter' => function($d, $row )use($data){return number_format($data[$d]['other_income'],2);}); $nr++;
	
$columns[] = array( 'db' => 'id', 'dt' => $nr, 'formatter' => function($d, $row )use($data){return number_format($data[$d]['deductions'],2);}); $nr++;

$columns[] = array( 'db' => 'id', 'dt' => $nr, 'formatter' => function($d, $row )use($data){return number_format($data[$d]['pension'],2);}); $nr++;

	$columns[] = array( 'db' => 'social', 'dt' => $nr, 'formatter' => function($d, $row ){return number_format($d,2);}); $nr++;
	
	$columns[] = array( 'db' => 'tax', 'dt' => $nr, 'formatter' => function($d, $row ){return number_format($d,2);}); $nr++;
	
	//$columns[] = array( 'db' => 'tot_deductions', 'dt' => $nr, 'formatter' => function($d, $row ){return number_format($d,2);}); $nr++;
	
	$columns[] = array( 'db' => 'gross_income', 'dt' => $nr, 'formatter' => function($d, $row ){return number_format($d,2);}); $nr++;
	
	$columns[] = array( 'db' => 'advance', 'dt' => $nr, 'formatter' => function($d, $row ){return number_format($d,2);}); $nr++;
	
	$columns[] = array( 'db' => 'legal_deductions', 'dt' => $nr, 'formatter' => function($d, $row ){return number_format($d,2);}); $nr++;
	
	$columns[] = array( 'db' => 'net_income', 'dt' => $nr, 'formatter' => function($d, $row ){return number_format($d,2);}); $nr++;

	require(DIR.'ajax/ssp.class.php' );
	
	$where =  "month = '".$_SESSION['rego']['cur_month']."' AND paid != 'H'";        

	//var_dump(SSP::simple( $_GET, $sql_details, $table, $primaryKey, $columns, $where ));exit;
	
	ob_clean();
	echo json_encode(
		SSP::complex( $_POST, $sql_details, $table, $primaryKey, $columns, '', $where)
	);






