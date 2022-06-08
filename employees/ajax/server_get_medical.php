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
	$table = $cid.'_employee_medical';
	$primaryKey = 'id';

	$nr=0;
	
	$columns[] = array( 'db' => 'date_from','dt' => $nr, 'formatter' => function( $d, $row ){return date('D d-m-Y', strtotime($d));}); $nr++;
	
	$columns[] = array( 'db' => 'emp_condition','dt' => $nr); $nr++;
	
	$columns[] = array( 'db' => 'certificate','dt' => $nr, 'formatter' => function( $d, $row )use($cid){
		if(!empty($d)){
			return '<a target="_blank" href="'.ROOT.$cid.'/medical/'.$d.'">'.$d.'</a>';
		}else{
			return 'NO certificate';
		}
	}); $nr++;
	
	$columns[] = array( 'db' => 'id','dt' => $nr); $nr++;
	
	require('../../ajax/ssp.class.php' );
	
	echo json_encode(
		SSP::simple( $_POST, $sql_details, $table, $primaryKey, $columns, $where)
	);