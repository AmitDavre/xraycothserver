<?
	if(session_id()==''){session_start();}
	ob_start();
	include('../../dbconnect/db_connect.php');
	include(DIR.'files/arrays_en.php');
	$version['free'] = 'Free mobile';
	$version['mob'] = 'Mobile';
	//var_dump($version);
	//$_REQUEST['agent'] = 'all';
	
	$where = '';
	if($_REQUEST['agent'] != 'all'){
		$where = "agent = '".$_REQUEST['agent']."'";
	}else{
		$where = "agent LIKE '%AG%'";
	}
	
	// REGO HR DATABASE /////////////////////////////////////////////////////////////////////////////////
	$sql_details = array(
		'user' => $my_username,
		'pass' => $my_password,
		'db'   => $my_dbaname,
		'host' => $my_database
	);

	$table = 'rego_customers';
	$primaryKey = 'id';
	
	$nr=0;
	
	$columns[] = array( 'db' => 'clientID', 'dt' => $nr, 'formatter' => function($d, $row ){
		return strtoupper(substr($d, 0, 4).' '.substr($d, 4));
	}); $nr++;
	
	$columns[] = array( 'db' => $_SESSION['RGadmin']['lang'].'_compname', 'dt' => $nr ); $nr++;
	
	$columns[] = array( 'db' => 'name', 'dt' => $nr ); $nr++;
	
	$columns[] = array( 'db' => 'version', 'dt' => $nr, 'formatter' => function($d, $row )use($version){return $version[$d];}); $nr++;
	
	$columns[] = array( 'db' => 'period_start', 'dt' => $nr, 'formatter' => function($d, $row ){return date('d-m-Y', strtotime($d));}); $nr++;
	
	$columns[] = array( 'db' => 'period_end', 'dt' => $nr, 'formatter' => function($d, $row ){return date('d-m-Y', strtotime($d));}); $nr++;
	
	$columns[] = array( 'db' => 'status', 'dt' => $nr, 'formatter' => function($d, $row )use($client_status){return $client_status[$d];}); $nr++;
	
	require(DIR.'ajax/ssp.class.php');
	$data1 = SSP::simple( $_POST, $sql_details, $table, $primaryKey, $columns, $where );//exit;
	//var_dump($data1);//exit;
	
	
	// REGOMOBILE DATABASE /////////////////////////////////////////////////////////////////////////////////
	if(strpos($_SERVER['SERVER_NAME'], 'xray.co.th') !== false){
		$my_database = 'regomobile.com:3306';
		$my_username = 'regomob_admin';
		$my_password = '5cee9e8a871c5ab';
		$prefix = 'regomob_';
	}else{
		$my_database = 'localhost';
		$my_username = 'root';
		$my_password = '';
		$prefix = 'payroll_';
	}
	$my_dbaname = $prefix.'admin';
	
	$sql_details = array(
		'user' => $my_username,
		'pass' => $my_password,
		'db'   => $my_dbaname,
		'host' => $my_database
	);

	$table = 'customers';
	$primaryKey = 'id';
	
	$nr=0;
	
	$columns[] = array( 'db' => 'clientID', 'dt' => $nr, 'formatter' => function($d, $row ){
		return 'RM '.strtoupper(substr($d, 0, 4).' '.substr($d, 4));
	}); $nr++;
	
	$columns[] = array( 'db' => $_SESSION['RGadmin']['lang'].'_compname', 'dt' => $nr ); $nr++;
	
	$columns[] = array( 'db' => 'name', 'dt' => $nr ); $nr++;
	
	$columns[] = array( 'db' => 'version', 'dt' => $nr, 'formatter' => function($d, $row )use($version){return $version[$d];}); $nr++;
	
	$columns[] = array( 'db' => 'period_start', 'dt' => $nr, 'formatter' => function($d, $row ){return date('d-m-Y', strtotime($d));}); $nr++;
	
	$columns[] = array( 'db' => 'period_end', 'dt' => $nr, 'formatter' => function($d, $row ){return date('d-m-Y', strtotime($d));}); $nr++;
	
	$columns[] = array( 'db' => 'status', 'dt' => $nr, 'formatter' => function($d, $row )use($client_status){return $client_status[$d];}); $nr++;
	
	$data2 = SSP::simple( $_POST, $sql_details, $table, $primaryKey, $columns, $where ); //exit;
	if($data2['data']){
		foreach($data2['data'] as $k=>$v){
			$data1['data'][] = $v;
		}
		$data1['recordsTotal'] += $data2['recordsTotal'];
		$data1['recordsFiltered'] += $data2['recordsFiltered'];
	}


	//var_dump($data1);exit;
	echo json_encode($data1); exit;

?>















