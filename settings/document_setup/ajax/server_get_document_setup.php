<?
	if(session_id()==''){session_start();}
	ob_start();
	include('../../dbconnect/db_connect.php');
	//include('../../inc/arrays_'.$_SESSION['erp']['lang'].'.php');
	//$categories[''] = '';
	
	$sql_details = array(
		'user' => $my_username,
		'pass' => $my_password,
		'db'   => $my_dbcname,
		'host' => $my_database
	);
		
	$table = 'erp_document_setup';
	
	$primaryKey = 'id';

	$nr=0;
	$columns[] = array( 'db' => 'id', 'dt' => $nr, 'formatter' => function($d, $row ){return '<span class="id">'.$d.'</span>';}); $nr++;
	$columns[] = array( 'db' => 'name', 'dt' => $nr); $nr++;

require('../../ajax/ssp.class.php' );
$where = "";//status = '0'"; 
//$joinQuery = "FROM $table";
//$where = "emp_id = '".$_REQUEST['emp_id']."'";        
//var_dump(SSP::simple( $_GET, $sql_details, $table, $primaryKey, $columns, $joinQuery, $extraWhere ));exit;
ob_clean();
echo json_encode(
	SSP::simple( $_GET, $sql_details, $table, $primaryKey, $columns, $where)
);

?>