<?
	if(session_id()==''){session_start();}
	ob_start();
	include('../../dbconnect/db_connect.php');

//$_REQUEST['year'] = '2019';

	$sql_details = array(
		'user' => $my_username,
		'pass' => $my_password,
		'db'   => $my_dbaname,
		'host' => $my_database
	);
	
	$table = 'rego_default_holidays';
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
		if($_SESSION['RGadmin']['access']['def_settings']['add'] == 1 || $_SESSION['RGadmin']['access']['def_settings']['edit'] == 1){
			return '<a class="editHoliday" data-id="'.$d.'"><i class="fa fa-edit fa-lg"></i></a>';
		}else{
			return '<a style="cursor:not-allowed !important"><i style="color:#ccc" class="fa fa-edit fa-lg"></i></a>';
		}
	}); $nr++;
	
	$columns[] = array( 'db' => 'id', 'dt' => $nr, 'formatter' => function($d, $row ){
		if($_SESSION['RGadmin']['access']['def_settings']['delete'] == 1){
			return '<a class="delHoliday" data-id="'.$d.'"><i class="fa fa-trash fa-lg"></i></a>';
		}else{
			return '<a style="cursor:not-allowed !important"><i style="color:#ccc" class="fa fa-times-circle fa-lg"></i></a>';
		}
	}); $nr++;

	//var_dump($columns); exit;

	require(DIR.'ajax/ssp.class.php');
	echo json_encode(
		SSP::simple( $_GET, $sql_details, $table, $primaryKey, $columns, $where)
	);
	
?>