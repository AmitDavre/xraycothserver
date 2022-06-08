<?php

	if(session_id()==''){session_start();}
	include('../../dbconnect/db_connect.php');
	include(DIR.'files/arrays_'.$_SESSION['rego']['lang'].'.php');
	
	$sql_details = array(
		'user' => $my_username,
		'pass' => $my_password,
		'db'   => $my_dbxname,
		'host' => $my_database
	);
	
	$table = 'rego_support_desk';
	$primaryKey = 'id';
	
	$where = "cid = '".$_SESSION['rego']['cid']."'";
	if($_REQUEST['status'] != ''){
		$where .= " AND status = '".$_REQUEST['status']."'";        
	}
	if($_REQUEST['priority'] != ''){
		if(!empty($where)){$where .= ' AND ';}
		$where .= "priority = ".$_REQUEST['priority']; 
	}

	$nr=0;
	
	$columns[] = array( 'db' => 'ticket',  'dt' => $nr, 'formatter' => function( $d, $row ){
		return '<a class="ticket" data-id="'.$d.'">'.$d.'</a>';
	}); $nr++;
	
	$columns[] = array( 'db' => 'created','dt' => $nr, 'formatter' => function( $d, $row ){return date('D d-m-Y @ H:i', strtotime($d));}); $nr++;
	
	$columns[] = array( 'db' => 'subject','dt' => $nr ); $nr++;
	
	//$columns[] = array( 'db' => 'company','dt' => $nr ); $nr++;
	
	$columns[] = array( 'db' => 'applicant','dt' => $nr ); $nr++;
	
	$columns[] = array( 'db' => 'type','dt' => $nr, 'formatter' => function( $d, $row )use($sd_type){return $sd_type[$d];}); $nr++;
	
	$columns[] = array( 'db' => 'priority','dt' => $nr, 'formatter' => function( $d, $row )use($sd_prior){return '<small style="display:block; background:'.$sd_prior[$d]['1'].'; color:#fff; padding:1px 10px; text-align:center; border-radius:2px; width:100%; font-size:12px">'.$sd_prior[$d]['0'].'</small>';}); $nr++;
	
	$columns[] = array( 'db' => 'status','dt' => $nr, 'formatter' => function( $d, $row )use($sd_status){return '<small style="display:block; background:'.$sd_status[$d][1].'; color:#fff; padding:1px 10px; text-align:center; border-radius:2px; width:100%; font-size:12px">'.$sd_status[$d][0].'</small>';}); $nr++;
	
	$columns[] = array( 'db' => 'id',  'dt' => $nr, 'formatter' => function( $d, $row ){return '';}); $nr++;

	require(DIR.'ajax/ssp.class.php' );
	
	echo json_encode(
		SSP::simple( $_POST, $sql_details, $table, $primaryKey, $columns, $where)
	);

