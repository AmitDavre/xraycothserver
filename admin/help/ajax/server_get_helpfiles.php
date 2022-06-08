<?
	if(session_id()==''){session_start();}
	include('../../dbconnect/db_connect.php');
	include(DIR.'files/arrays_'.$_SESSION['RGadmin']['lang'].'.php');
	
	$sql_details = array(
		'user' => $my_username,
		'pass' => $my_password,
		'db'   => $my_dbaname,
		'host' => $my_database
	);
	
	// DB table to use
	$table = 'rego_helpfiles';
	
	// Table's primary key
	$primaryKey = 'id';
	
	$where = "";
	/*if($_SESSION['RGadmin']['access']['support']['gen'] == 1){
		$type .= "'gen',";
	}
	if($_SESSION['RGadmin']['access']['support']['con'] == 1){
		$type .= "'con',";
	}
	if($_SESSION['RGadmin']['access']['support']['bug'] == 1){
		$type .= "'bug',";
	}
	$type = substr($type,0,-1);
	if(!empty($type)){
		$where .= "type IN (".$type.")";
	}*/
	
	$nr=0;
	
	$columns[] = array( 'db' => 'id',  'dt' => $nr); $nr++;
	$columns[] = array( 'db' => 'page',  'dt' => $nr); $nr++;
	
/*	$columns[] = array( 'db' => 'id', 'dt' => $nr, 'formatter' => function($d, $row ){return '<input type="hidden" class="id" value="'.$d.'"/><a href="index.php?mn=61&id='.$d.'"><i class="fa fa-edit fa-lg"></i></a>';}); $nr++;

	$columns[] = array( 'db' => 'th_title','dt' => $nr ); $nr++;
	
	$columns[] = array( 'db' => 'en_title','dt' => $nr ); $nr++;
	
	$columns[] = array( 'db' => 'id',  'dt' => $nr, 'formatter' => function( $d, $row ){return '';}); $nr++;
*/
	require(DIR.'ajax/ssp.class.php' );
	
	echo json_encode(
		SSP::simple( $_POST, $sql_details, $table, $primaryKey, $columns, $where)
	);

?>













