<?
	if(session_id()==''){session_start(); ob_start();}
	include('../../dbconnect/db_connect.php');
	include(DIR.'files/arrays_en.php');
	
	$sql_details = array(
		'user' => $my_username,
		'pass' => $my_password,
		'db'   => $my_dbaname,
		'host' => $my_database
	);
	
	// DB table to use
	$table = 'rego_invoices';
	
	// Table's primary key
	$primaryKey = 'id';
	
	$nr=0;
	
	$columns[] = array( 'db' => 'inv', 'dt' => $nr, 'formatter' => function($d, $row ){
		return '<a class="edit_invoice">'.$d.'</a>';
	}); $nr++;
	
	$columns[] = array( 'db' => 'customer', 'dt' => $nr); $nr++;
	
	$columns[] = array( 'db' => 'inv_date', 'dt' => $nr); $nr++;
	
	$columns[] = array( 'db' => 'inv_due', 'dt' => $nr ); $nr++;
	
	$columns[] = array( 'db' => 'subtotal', 'dt' => $nr, 'formatter' => function($d, $row ){return number_format($d,2);}); $nr++;
	
	$columns[] = array( 'db' => 'total', 'dt' => $nr, 'formatter' => function($d, $row ){return number_format($d,2);}); $nr++;
	
	$columns[] = array( 'db' => 'inv_date', 'dt' => $nr, 'formatter' => function($d, $row ){
		return date("01-m-Y", strtotime(date("Y-m-d", strtotime($d)) . " + 1 year"));
	}); $nr++;
	
	$columns[] = array( 'db' => 'rec_id', 'dt' => $nr, 'formatter' => function($d, $row ){
		if(empty($d)){
			return '<a class="create_receipt"><i class="fa fa-file-text-o fa-lg"></i></a>';
		}else{
			return '<a class="edit_receipt">'.$d.'</a>';
		}
	
	}); $nr++;
	
	$columns[] = array( 'db' => 'rec_date', 'dt' => $nr, 'formatter' => function($d, $row ){return '<span style="display:xnone">'.$d.'</span>';}); $nr++;
	
	$columns[] = array( 'db' => 'id', 'dt' => $nr, 'formatter' => function($d, $row ){return '<span data-id="'.$d.'" class="id"></span>';}); $nr++;
	
	/*$columns[] = array( 'db' => 'id', 'dt' => $nr, 'formatter' => function($d, $row ){
		return '<a href="index.php?mn=21&id='.$d.'"><i class="fa fa-pencil-square-o fa-lg"></i></a>';
	}); $nr++;*/
	
	$columns[] = array( 'db' => 'pdf_invoice', 'dt' => $nr, 'formatter' => function($d, $row ){
		if(empty($d)){
			return '<a style="cursor:default !important"><i style="color:#ccc" class="fa fa-download fa-lg"></i></a>';
		}else{
			return '<a href="'.$d.'" target="_blank"><i class="fa fa-download fa-lg"></i></a>';
		}
	}); $nr++;
	
	/*$columns[] = array( 'db' => 'id', 'dt' => $nr, 'formatter' => function($d, $row ){
		return '<a href="index.php?mn=23&vid='.$d.'"><i class="fa fa-pencil-square-o fa-lg"></i></a>';
	}); $nr++;*/
	
	$columns[] = array( 'db' => 'pdf_receipt', 'dt' => $nr, 'formatter' => function($d, $row ){
		if(empty($d)){
			return '<a style="cursor:default !important"><i style="color:#ccc" class="fa fa-download fa-lg"></i></a>';
		}else{
			return '<a href="'.$d.'" target="_blank"><i class="fa fa-download fa-lg"></i></a>';
		}
	}); $nr++;
	
	$columns[] = array( 'db' => 'pdf_certificate', 'dt' => $nr, 'formatter' => function($d, $row ){
		if(empty($d)){
			return '<a style="cursor:default !important"><i style="color:#ccc" class="fa fa-download fa-lg"></i></a>';
		}else{
			return '<a href="'.$d.'" target="_blank"><i class="fa fa-download fa-lg"></i></a>';
		}
	}); $nr++;
	


	require(DIR.'ajax/ssp.class.php');
	$where =  "status = 1";//"month = '".$_SESSION['payroll']['cur_month']."'";        
	//var_dump(SSP::simple( $_GET, $sql_details, $table, $primaryKey, $columns, $joinQuery, $extraWhere ));exit;
	echo json_encode(
		SSP::simple( $_POST, $sql_details, $table, $primaryKey, $columns, $where)
	);

?>