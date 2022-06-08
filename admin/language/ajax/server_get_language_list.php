<?
	if(session_id()==''){session_start();}
	ob_start();
	include('../../dbconnect/db_connect.php');
	
	$sql_details = array(
		'user' => $my_username,
		'pass' => $my_password,
		'db'   => $my_dbaname,
		'host' => $my_database
	);
	
	$table = 'rego_application_language';
	$primaryKey = 'id';
	
	$nr=0;
	$columns[] = array( 'db' => 'code', 'dt' => $nr, 'formatter' => function($d, $row ){return '<span class="code">'.$d.'</span>';}); $nr++;
	$columns[] = array( 'db' => 'en', 'dt' => $nr, 'formatter' => function($d, $row ){return '<span class="en">'.$d.'</span>';}); $nr++;
	$columns[] = array( 'db' => 'th', 'dt' => $nr, 'formatter' => function($d, $row ){return '<span class="th">'.$d.'</span>';}); $nr++;
	$columns[] = array( 'db' => 'id', 'dt' => $nr, 'formatter' => function($d, $row ){return '<input type="hidden" class="id" value="'.$d.'"/><a data-id="'.$d.'" class="icon editLang" href="javascript:void()"><i class="fa fa-edit fa-lg"></i></a>';}); $nr++;

	//var_dump($columns); exit;

	require(DIR.'ajax/ssp.class.php');
	$where =  "";
	ob_clean();
	echo json_encode(
		SSP::simple( $_GET, $sql_details, $table, $primaryKey, $columns, $where)
	);

?>