<?php
	if(session_id()==''){session_start();}
	ob_start();
	//$cid = $_SESSION['xhr']['cid'];
	include('../dbconnect/db_connect.php');
	//include(DIR.'files/functions.php');
	
	$sql_details = array(
		'user' => $my_username,
		'pass' => $my_password,
		'db'   => $my_dbaname,
		'host' => $my_database
	);
	//var_dump($_REQUEST); exit;
	//$_REQUEST['from'] = '2019-01-01';
	//$_REQUEST['until'] = '2019-12-30';
	//$_REQUEST['action'] = 'log';
	$root = ROOT;
	$from = date('Y-m-d', strtotime($_REQUEST['from']));
	$until = date('Y-m-d', strtotime($_REQUEST['until']));
	
	$where = '';
	$field = 'log';
	if($_REQUEST['action'] != ''){
		$field = $_REQUEST['action'];
		$where = " (timestamp BETWEEN '$from' AND '$until') AND ".$_REQUEST['action']." != '' ";
	}
	//var_dump($where);
	//exit;
	
	$table = 'rego_logdata';
	$primaryKey = 'id';

	$nr=0;
	/*$columns[] = array( 'db' => 'user_id',    'dt' => $nr, 'formatter' => function($d, $row ){ 
		if($d == '5a6effb9c34ab'){
			return 'XRAY Admin';
		}else{
			return $d;
		}
	}); $nr++;*/
	
	$columns[] = array( 'db' => 'user_name',  'dt' => $nr ); $nr++;
	
	$columns[] = array( 'db' => 'user_img', 'dt' => $nr, 'formatter' => function($d, $row )use($root){
		if(empty($d)){$d = "images/profile_image.jpg";}
		$detail = '';
		if($d != "images/profile_image.jpg"){$detail = ' title="<img src='.$root.$d.'?'.time().' />"  data-toggle="tooltip" data-placement="right"';}
		return '<img style="height:26px; width:26px" '.$detail.' src="'.$root.$d.'" />';
	}); $nr++;
	
	$columns[] = array( 'db' => 'cid',     'dt' => $nr ); $nr++;

	$columns[] = array( 'db' => 'timestamp',  'dt' => $nr, 'formatter' => function($d, $row ){ return date('d-m-Y @ H:i:s', strtotime($d));}); $nr++;
	
	$columns[] = array( 'db' => 'user_ip','dt' => $nr ); $nr++;
	
	$columns[] = array( 'db' => 'server_ip','dt' => $nr ); $nr++;
	
	$columns[] = array( 'db' => 'platform',  'dt' => $nr ); $nr++;
	
	$columns[] = array( 'db' => 'browser',     'dt' => $nr ); $nr++;
	
	$columns[] = array( 'db' => 'uri',     'dt' => $nr ); $nr++;
	
	$columns[] = array( 'db' => $field, 'dt' => $nr ); $nr++;
	
	require(DIR.'ajax/ssp.class.php' );
	
	//$joinQuery = "FROM $table";
	//var_dump(SSP::simple( $_GET, $sql_details, $table, $primaryKey, $columns, $joinQuery, $extraWhere ));exit;
	//$where = '';
	ob_clean();
	echo json_encode(
		SSP::simple($_POST, $sql_details, $table, $primaryKey, $columns, $where)
	);

?>