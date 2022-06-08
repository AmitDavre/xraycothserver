<?
	if(session_id()==''){session_start();}
	ob_start();
	include('../../dbconnect/db_connect.php');
	include('../../inc/arrays_'.$_SESSION['erp']['lang'].'.php');

	$sql_details = array(
		'user' => $my_username,
		'pass' => $my_password,
		'db'   => $my_dbcname,
		'host' => $my_database
	);

	$table = 'erp_document_textblocks';//$_SESSION['payroll']['emp_dbase'];//'shr0100_employees';
	
	$primaryKey = 'id';

	$nr=0;
	$columns[] = array( 'db' => 'id', 'dt' => $nr, 'formatter' => function($d, $row ){return '<span class="id">'.$d.'</span>';}); $nr++;
	$columns[] = array( 'db' => 'name', 'dt' => $nr); $nr++;
	$columns[] = array( 'db' => 'status', 'dt' => $nr, 'formatter' => function($d, $row )use($status){return $status[$d];}); $nr++;

require('../../ajax/ssp.class.php' );
$where = ""; 
$joinQuery = "FROM $table";
$where = "";        
//var_dump(SSP::simple( $_GET, $sql_details, $table, $primaryKey, $columns, $joinQuery, $extraWhere ));exit;
ob_clean();
echo json_encode(
	SSP::simple( $_GET, $sql_details, $table, $primaryKey, $columns, $where)
);

?>