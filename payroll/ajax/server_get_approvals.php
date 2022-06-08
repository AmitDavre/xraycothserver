<?
	if(session_id()==''){session_start();}
	ob_start();
	include('../../dbconnect/db_connect.php');
	include(DIR.'files/functions.php');
	include(DIR.'files/arrays_'.$lang.'.php');

	$nr=0;
	
	$columns[] = array( 'db' => 'on_date', 'dt' => $nr, 'formatter' => function($d, $row ){return date('d-m-Y @ H:i',strtotime($d));}); $nr++;
	$columns[] = array( 'db' => 'month', 'dt' => $nr ); $nr++;
	
	$columns[] = array( 'db' => 'by_name', 'dt' => $nr ); $nr++;
	
	//$columns[] = array( 'db' => 'emp_group', 'dt' => $nr, 'formatter' => function($d, $row )use($emp_group){return $emp_group[$d];}); $nr++;
	$columns[] = array( 'db' => 'id', 'dt' => $nr ); $nr++;

	$columns[] = array( 'db' => 'action', 'dt' => $nr, 'formatter' => function($d, $row )use($payroll_status){return $payroll_status[$d];}); $nr++;
	
	$columns[] = array( 'db' => 'comment', 'dt' => $nr ); $nr++;
	
	$columns[] = array( 'db' => 'attachment', 'dt' => $nr, 'formatter' => function($d, $row ){
		if(!empty($d)){
			return '<a download href="../'.$d.'"><i class="fa fa-download fa-lg"></i></a>';
		}else{
			return '<a class="disabled"><i class="fa fa-download fa-lg"></i></a>';
		}
		
	}); $nr++;
	
	
	//$where = "emp_id IN (".$_REQUEST['empFilter'].")";
	//$where = "emp_id IN (10003,10013,10009)";
	//if(!empty($_REQUEST['statFilter'])){
		//$where .= " AND ".$_REQUEST['statFilter'];
	//}
	//$where = "type = 'payroll' AND emp_group = '".$_SESSION['rego']['emp_group']."'";
	$where = "type = 'payroll'";

	$sql_details = array(
		'user' => $my_username,
		'pass' => $my_password,
		'db'   => $my_dbcname,
		'host' => $my_database
	);
	//$where = "";
	$table = $cid.'_approvals';//$_SESSION['xhr']['emp_dbase'];//'shr0100_employees';
	$primaryKey = 'id';
	
	require('../../ajax/ssp.class.php' );
	
	//$joinQuery = "FROM $table";
	//$where = " status != 'TA' && status != 'CA'";        
	//var_dump(SSP::simple( $_GET, $sql_details, $table, $primaryKey, $columns, $joinQuery, $extraWhere ));exit;
	
	ob_clean();
	echo json_encode(
		SSP::simple( $_GET, $sql_details, $table, $primaryKey, $columns, $where)
	);

?>