<?php
	if(session_id()==''){session_start();}
	ob_start();
	include('../../dbconnect/db_connect.php');
	include('../../../files/arrays_'.$lang.'.php');
	//var_dump($yesno); exit;
	
	$sql_details = array(
		'user' => $my_username,
		'pass' => $my_password,
		'db'   => $my_dbaname,
		'host' => $my_database
	);
	
	$where = '';
	$table = 'demo_employees';
	$primaryKey = 'emp_id';

	$nr=0;
	$columns[] = array( 'db' => 'emp_id', 'dt' => $nr ); $nr++;
	
	$columns[] = array( 'db' => 'image', 'dt' => $nr, 'formatter' => function($d, $row ){
		if(empty($d)){$d = "../images/profile_image.jpg";}
		$detail = '';
		if($d != "../images/profile_image.jpg"){$detail = ' title="<img src=../'.$d.'?'.time().' />"  data-toggle="tooltip" data-placement="right"';}
		return '<img style="height:26px; width:26px" src="../'.$d.'?'.time().'"'.$detail.' />';
		}); $nr++;

	$columns[] = array( 'db' => 'emp_id', 'dt' => $nr, 'formatter' => function($d, $row ){
		return '<a class="icon" href="index.php?mn=59&id='.$d.'"><i class="fa fa-pencil-square-o fa-lg"></i></a>';
	}); $nr++;

	$columns[] = array( 'db' => $_SESSION['RGadmin']['lang'].'_name',  'dt' => $nr ); $nr++;
	
	$columns[] = array( 'db' => 'base_salary',  'dt' => $nr, 'formatter' => function($d, $row ){return number_format($d,2);}); $nr++;
	
	$columns[] = array( 'db' => 'calc_sso',  'dt' => $nr, 'formatter' => function($d, $row )use($noyes01){return $noyes01[$d];}); $nr++;
	
	$columns[] = array( 'db' => 'calc_pvf',  'dt' => $nr, 'formatter' => function($d, $row )use($noyes01){return $noyes01[$d];}); $nr++;
	
	$columns[] = array( 'db' => 'calc_tax',  'dt' => $nr, 'formatter' => function($d, $row )use($calcTax){return $calcTax[$d];}); $nr++;
	
	$columns[] = array( 'db' => 'calc_method',  'dt' => $nr, 'formatter' => function($d, $row ){return strtoupper($d);}); $nr++;
	
	$columns[] = array( 'db' => 'emp_tax_deductions',  'dt' => $nr, 'formatter' => function($d, $row ){return number_format($d,2);}); $nr++;
	
	$columns[] = array( 'db' => 'emp_status',  'dt' => $nr, 'formatter' => function($d, $row )use($emp_status){return $emp_status[$d];}); $nr++;
	
//var_dump($columns); exit;

	require(DIR.'ajax/ssp.class.php' );
	//var_dump(SSP::simple( $_GET, $sql_details, $table, $primaryKey, $columns, $joinQuery, $extraWhere ));exit;
	//ob_clean();
	echo json_encode(
		SSP::simple($_POST, $sql_details, $table, $primaryKey, $columns, $where)
	);

?>