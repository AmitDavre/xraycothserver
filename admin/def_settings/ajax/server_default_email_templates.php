<?php
	if(session_id()==''){session_start();}
	ob_start();

	include('../../dbconnect/db_connect.php');
	
	$sql_details = array(
		'user' => $my_username,
		'pass' => $my_password,
		'db'   => $my_dbaname,
		'host' => $my_database
	);
	
	$table = 'rego_default_email_templates';
	// Table's primary key
	$primaryKey = 'name';

	$nr=0;
	$columns[] = array( 'db' => 'name',  'dt' => $nr ); $nr++;
	$columns[] = array( 'db' => 'name',    'dt' => $nr, 'formatter' => function($d, $row ){
		return '<a class="editTemplate" data-id="'.$d.'"><i class="fa fa-edit fa-lg"></i></a>';
	}); $nr++;
	//$columns[] = array( 'db' => 'subject',  'dt' => $nr ); $nr++;
	$columns[] = array( 'db' => 'description_'.$lang,  'dt' => $nr ); $nr++;
	

//var_dump($columns); exit;

	require(DIR.'ajax/ssp.class.php' );
	
	//$joinQuery = "FROM $table";
	//var_dump(SSP::simple( $_GET, $sql_details, $table, $primaryKey, $columns, $joinQuery, $extraWhere ));exit;
	$where = '';
	ob_clean();
	echo json_encode(
		SSP::simple($_POST, $sql_details, $table, $primaryKey, $columns, $where)
	);

?>