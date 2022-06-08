<?php

	if(session_id()==''){session_start();}
	ob_start();
	include('../../dbconnect/db_connect.php');
	//var_dump($_REQUEST['month']);
	$nr=0;
	
	$columns[] = array( 'db' => 'type', 'dt' => $nr, 'formatter' => function($d, $row ){
		switch($d){
			case 'pdf': 
				$fa = '<i class="fa fa-file-pdf-o"></i>'; break;
			case 'xlsx': 
				$fa = '<i class="fa fa-file-excel-o"></i>'; break;
			case 'xls': 
				$fa = '<i class="fa fa-file-excel-o"></i>'; break;
			case 'txt': 
			default: 
				$fa = '<i class="fa fa-file-text-o"></i>'; break;
		}
		return $fa;
	}); $nr++;
	
	//$columns[] = array( 'db' => 'name', 'dt' => $nr ); $nr++;
	
	$columns[] = array( 'db' => 'name', 'dt' => $nr ); $nr++;
	
	$columns[] = array( 'db' => 'size', 'dt' => $nr, 'formatter' => function($d, $row ){
		if($d > 1024){
			return number_format(($d/1000), 2).' MB';
		}else{
			return number_format($d, 2).' KB';
		}
	}); $nr++;
	
	$columns[] = array( 'db' => 'date', 'dt' => $nr ); $nr++;
	
	$columns[] = array( 'db' => 'user_name', 'dt' => $nr ); $nr++;
	
	$columns[] = array( 'db' => 'link', 'dt' => $nr, 'formatter' => function($d, $row ){
		if(!empty($d)){
			return '<a target="_blank" href="'.$d.'" download><i class="fa fa-download fa-lg"></i></a>';
		}else{
			return '<a class="disabled"><i class="fa fa-download fa-lg"></i></a>';
		}
	}); $nr++;
	$columns[] = array( 'db' => 'id', 'dt' => $nr, 'formatter' => function($d, $row ){
		return '<a data-toggle="confirmation" class="delete" data-id="'.$d.'"><i class="fa fa-trash fa-lg"></i></a>';
	}); $nr++;
	
	$where = '';
	if(!empty($_REQUEST['month'])){
		$where = "month = '".$_REQUEST['month']."' AND year = '".$_SESSION['rego']['cur_year']."'";
	}
	
	$sql_details = array(
		'user' => $my_username,
		'pass' => $my_password,
		'db'   => $my_dbcname,
		'host' => $my_database
	);
	//$where = "";
	$table = $cid.'_documents';
	$primaryKey = 'filename';
	
	require(DIR.'ajax/ssp.class.php' );
	//var_dump(SSP::simple( $_POST, $sql_details, $table, $primaryKey, $columns, $where)); exit;
	ob_clean();
	echo json_encode(
		SSP::simple( $_POST, $sql_details, $table, $primaryKey, $columns, $where)
	);

