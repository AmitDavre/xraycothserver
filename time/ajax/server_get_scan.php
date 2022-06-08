<?
	if(session_id()==''){session_start();}
	ob_start();
	include('../../dbconnect/db_connect.php');
	$dir = ROOT.$cid.'/uploads/';
	
	$sql_details = array(
		'user' => $my_username,
		'pass' => $my_password,
		'db'   => $my_dbcname,
		'host' => $my_database
	);
	
	$table = $cid."_scanfiles";
	$primaryKey = 'id';
	
	$where = "";
	if($_REQUEST['valid'] != ''){
		$where .= "status = '".$_REQUEST['valid']."'";
	}
	if(!empty($_REQUEST['period'])){
		if($where != ''){$where .= ' AND ';}
		$where .= "period = '".$_REQUEST['period']."'";
	}

// 	echo '<pre>';
// print_r($_SESSION);
// echo '</pre>';

// die();


	$currentyear = '%'.$cur_year.'%';
	if(!empty($where))
	{
		$where .= " AND date LIKE '".$currentyear."'  )";
	}
	else
	{
		$where .= "  date LIKE '".$currentyear."'    ";
	}

	





	$nr=0;
	
	$columns[] = array( 'db' => 'date', 'dt' => $nr, 'formatter' => function($d, $row ){return date('d-m-Y', strtotime($d));}); $nr++;
	
	$columns[] = array( 'db' => 'period', 'dt' => $nr); $nr++;
	
	$columns[] = array( 'db' => 'filename', 'dt' => $nr); $nr++;
	$columns[] = array( 'db' => 'scansystem', 'dt' => $nr); $nr++;
	$columns[] = array( 'db' => 'in_out', 'dt' => $nr); $nr++;
	
	$columns[] = array( 'db' => 'import', 'dt' => $nr, 'formatter' => function($d, $row ){
		if($d == 0){
			return '<span style="color:#a00; font-weight:normal"><i class="fa fa-minus fa-lg"></i></span>';
		}else{
			return '<span style="color:green; font-weight:normal"><i class="fa fa-check fa-lg"></i></span>';
		}
	}); $nr++;
	
	$columns[] = array( 'db' => 'status', 'dt' => $nr, 'formatter' => function($d, $row ){
		if($d == 0){
			return '<span style="color:#a00; font-weight:normal"><i class="fa fa-minus fa-lg"></i></span>';
		}else{
			return '<span style="color:green; font-weight:normal"><i class="fa fa-check fa-lg"></i></span>';
		}
	}); $nr++;
	
	$columns[] = array( 'db' => 'id', 'dt' => $nr, 'formatter' => function($d, $row ){
		return '<a data-id="'.$d.'" class="viewContent"><i class="fa fa-eye fa-lg"></i></a>';
	}); $nr++;
	
	$columns[] = array( 'db' => 'id', 'dt' => $nr, 'formatter' => function($d, $row ){return '';}); $nr++;
	
	$columns[] = array( 'db' => 'filename', 'dt' => $nr, 'formatter' => function($d, $row )use($dir){
		return '<a download href="'.$dir.$d.'" class="downloadFile"><i class="fa fa-download fa-lg"></i></a>';
	}); $nr++;

	$columns[] = array( 'db' => 'id', 'dt' => $nr, 'formatter' => function($d, $row )use($dir){

			if($_SESSION['rego']['time_import']['edit']) {

				return '<a data-id="'.$d.'" class="deleteFile"><i class="fa fa-trash fa-lg"></i></a>';
			}
			else
			{
				return '<a data-id="" class="deleteFile"><i class="fa fa-trash fa-lg"></i></a>';
			}
	}); $nr++;

	require(DIR.'ajax/ssp.class.php' );
	
	//var_dump(SSP::simple( $_POST, $sql_details, $table, $primaryKey, $columns, $where)); exit;
	
	ob_clean();
	echo json_encode(
		SSP::simple( $_POST, $sql_details, $table, $primaryKey, $columns, $where)
	);

?>