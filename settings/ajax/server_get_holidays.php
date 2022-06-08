<?
	if(session_id()==''){session_start();}
	ob_start();
	include('../../dbconnect/db_connect.php');

//$_REQUEST['year'] = '2019';

	$sql_details = array(
		'user' => $my_username,
		'pass' => $my_password,
		'db'   => $my_dbcname,
		'host' => $my_database
	);
	
	$table = $cid.'_holidays';
	$primaryKey = 'id';
	$where = "year = '".$_REQUEST['year']."'"; 
	       
	$nr=0;
	
	$columns[] = array( 'db' => 'date', 'dt' => $nr, 'formatter' => function($d, $row ){
		return date('D d-m-Y', strtotime($d));
	}); $nr++;
	
	$columns[] = array( 'db' => 'cdate', 'dt' => $nr, 'formatter' => function($d, $row ){
		return date('D d-m-Y', strtotime($d));
	}); $nr++;
	
	$columns[] = array( 'db' => 'th', 'dt' => $nr); $nr++;
	
	$columns[] = array( 'db' => 'en', 'dt' => $nr); $nr++;
	
	$columns[] = array( 'db' => 'id', 'dt' => $nr, 'formatter' => function($d, $row ){
		return '<a class="editHoliday" data-id="'.$d.'"><i class="fa fa-edit fa-lg"></i></a>';
	}); $nr++;
	
	$columns[] = array( 'db' => 'id', 'dt' => $nr, 'formatter' => function($d, $row ){
		return '<a class="delHoliday" data-id="'.$d.'"><i class="fa fa-trash fa-lg"></i></a>';
	}); $nr++;

	//var_dump($columns); exit;

	require(DIR.'ajax/ssp.class.php');
	echo json_encode(
		SSP::simple( $_GET, $sql_details, $table, $primaryKey, $columns, $where)
	);
	
?>