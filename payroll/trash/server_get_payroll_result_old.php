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
	
	$fix_allow = getUsedFixAllow($_SESSION['rego']['lang']);
	$var_allow = getUsedVarAllow($_SESSION['rego']['lang']);
	
	$data = array();
	$fallow = array();
	$vallow = array();
	
	$sql = "SELECT * FROM ".$_SESSION['rego']['payroll_dbase']." WHERE month = ".$_SESSION['rego']['cur_month'];
	if($res = $dbc->query($sql)){
		while($row = $res->fetch_assoc()){
			$data[$row['emp_id']] = array($row['emp_id'], $row['emp_name_'.$_SESSION['rego']['lang']]);
			foreach($fix_allow as $k=>$v){
				$fallow[$row['emp_id']][$k] = $row['fix_allow_'.$k];
			}
			foreach($var_allow as $k=>$v){
				$vallow[$row['emp_id']][$k] = $row['var_allow_'.$k];
			}
		}
	}

	//var_dump($allow); exit;

	$table = $_SESSION['rego']['payroll_dbase'];//'shr0100_employees';
	$primaryKey = 'emp_id';

	$nr=0;
	
	$columns[] = array( 'db' => 'emp_id', 'dt' => $nr, 'formatter' => function($d, $row ){return '<span id="emp_id">'.$d.'</span>';}); $nr++;
	
	$columns[] = array( 'db' => 'emp_name_'.$_SESSION['rego']['lang'], 'dt' => $nr, 'formatter' => function($d, $row ){return '<span class="emp_name">'.$d.'</span>';}); $nr++;
	
	$columns[] = array( 'db' => 'emp_id', 'dt' => $nr, 'formatter' => function($d, $row )use($data){return '<a data-id="'.$d.'" class="showtaxcalc"><i class="fa fa-calculator" style="font-size:14px"></i></a>';}); $nr++;
	
	$columns[] = array( 'db' => 'emp_id', 'dt' => $nr, 'formatter' => function($d, $row )use($data){return '<a data-id="'.$d.'" class="showpayslip"><i class="fa fa-product-hunt" style="font-size:14px"></i></a>';}); $nr++;
	
	$columns[] = array( 'db' => 'emp_id', 'dt' => $nr, 'formatter' => function($d, $row )use($data){return '<a data-id="'.$d.'" class="showreport"><i class="fa fa-list" style="font-size:14px"></i></a>';}); $nr++;
	
	$columns[] = array( 'db' => 'id', 'dt' => $nr, 'formatter' => function($d, $row ){return '<a data-id="'.$d.'" class="editOTpayroll"><i class="fa fa-edit" style="font-size:15px"></i></a>';}); $nr++;

	$columns[] = array( 'db' => 'salary', 'dt' => $nr, 'formatter' => function($d, $row ){return number_format($d,2);}); $nr++;
	
	$columns[] = array( 'db' => 'bonus', 'dt' => $nr, 'formatter' => function($d, $row ){return number_format($d,2);}); $nr++;
	
	$columns[] = array( 'db' => 'ot1b', 'dt' => $nr, 'formatter' => function($d, $row ){return number_format($d,2);}); $nr++;
	
	$columns[] = array( 'db' => 'ot15b', 'dt' => $nr, 'formatter' => function($d, $row ){return number_format($d,2);}); $nr++;
	
	$columns[] = array( 'db' => 'ot2b', 'dt' => $nr, 'formatter' => function($d, $row ){return number_format($d,2);}); $nr++;
	
	$columns[] = array( 'db' => 'ot3b', 'dt' => $nr, 'formatter' => function($d, $row ){return number_format($d,2);}); $nr++;
	
	$columns[] = array( 'db' => 'ootb', 'dt' => $nr, 'formatter' => function($d, $row ){return number_format($d,2);}); $nr++;
	
	$columns[] = array( 'db' => 'total_otb', 'dt' => $nr, 'formatter' => function($d, $row ){return number_format($d,2);}); $nr++;
	
	$columns[] = array( 'db' => 'absence_b', 'dt' => $nr, 'formatter' => function($d, $row ){return number_format($d,2);}); $nr++;
	
	$columns[] = array( 'db' => 'leave_wop_b', 'dt' => $nr, 'formatter' => function($d, $row ){return number_format($d,2);}); $nr++;
	
	$columns[] = array( 'db' => 'late_early_b', 'dt' => $nr, 'formatter' => function($d, $row ){return number_format($d,2);}); $nr++;
	
	foreach($fix_allow as $k=>$v){
		$columns[] = array( 'db' => 'emp_id', 'dt' => $nr, 'formatter' => function($d, $row )use($fallow, $k){return number_format($fallow[$d][$k],2);}); $nr++;
	}
	foreach($var_allow as $k=>$v){
		$columns[] = array( 'db' => 'emp_id', 'dt' => $nr, 'formatter' => function($d, $row )use($vallow, $k){return number_format($vallow[$d][$k],2);}); $nr++;
	}

	$columns[] = array( 'db' => 'total_tax_allow', 'dt' => $nr, 'formatter' => function($d, $row ){return number_format($d,2);}); $nr++;
	
	$columns[] = array( 'db' => 'other_income', 'dt' => $nr, 'formatter' => function($d, $row ){return number_format($d,2);}); $nr++;
	
	$columns[] = array( 'db' => 'severance', 'dt' => $nr, 'formatter' => function($d, $row ){return number_format($d,2);}); $nr++;
	
	$columns[] = array( 'db' => 'remaining_salary', 'dt' => $nr, 'formatter' => function($d, $row ){return number_format($d,2);}); $nr++;
	
	$columns[] = array( 'db' => 'notice_payment', 'dt' => $nr, 'formatter' => function($d, $row ){return number_format($d,2);}); $nr++;
	
	$columns[] = array( 'db' => 'paid_leave', 'dt' => $nr, 'formatter' => function($d, $row ){return number_format($d,2);}); $nr++;
	
	$columns[] = array( 'db' => 'uniform', 'dt' => $nr, 'formatter' => function($d, $row ){return number_format($d,2);}); $nr++;
	
	$columns[] = array( 'db' => 'deduct_3', 'dt' => $nr, 'formatter' => function($d, $row ){return number_format($d,2);}); $nr++;
	
	$columns[] = array( 'db' => 'pvf_employee', 'dt' => $nr, 'formatter' => function($d, $row ){return number_format($d,2);}); $nr++;
	
	$columns[] = array( 'db' => 'social', 'dt' => $nr, 'formatter' => function($d, $row ){return number_format($d,2);}); $nr++;
	
	$columns[] = array( 'db' => 'tax', 'dt' => $nr, 'formatter' => function($d, $row ){return number_format($d,2);}); $nr++;
	
	$columns[] = array( 'db' => 'tot_absence', 'dt' => $nr, 'formatter' => function($d, $row ){return number_format($d,2);}); $nr++;
	
	$columns[] = array( 'db' => 'tot_deductions', 'dt' => $nr, 'formatter' => function($d, $row ){return number_format($d,2);}); $nr++;
	
	$columns[] = array( 'db' => 'gross_income', 'dt' => $nr, 'formatter' => function($d, $row ){return number_format($d,2);}); $nr++;
	
	$columns[] = array( 'db' => 'advance', 'dt' => $nr, 'formatter' => function($d, $row ){return number_format($d,2);}); $nr++;
	
	$columns[] = array( 'db' => 'legal_deductions', 'dt' => $nr, 'formatter' => function($d, $row ){return number_format($d,2);}); $nr++;
	
	$columns[] = array( 'db' => 'net_income', 'dt' => $nr, 'formatter' => function($d, $row ){return number_format($d,2);}); $nr++;

	require(DIR.'ajax/ssp.class.php' );
	$where =  "month = '".$_SESSION['rego']['cur_month']."' AND paid != 'H'";        
	//$where =  "month = '".$_SESSION['rego']['cur_month']."'";        
	//var_dump(SSP::simple( $_GET, $sql_details, $table, $primaryKey, $columns, $joinQuery, $extraWhere ));exit;
	echo json_encode(
		SSP::complex( $_POST, $sql_details, $table, $primaryKey, $columns, '', $where)
	);






