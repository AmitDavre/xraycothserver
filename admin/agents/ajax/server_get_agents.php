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
	$where = '';
	if($_REQUEST['status'] != ''){
		$where = "status = ".$_REQUEST['status'];
	}
	// DB table to use
	$table = 'rego_agents';
	
	// Table's primary key
	$primaryKey = 'id';
	
	$nr=0;
	
	$columns[] = array( 'db' => 'img', 'dt' => $nr, 'formatter' => function($d, $row ){
		if(empty($d)){$d = '../images/profile_image.jpg';}
		return '<img style="height:28px; width:28px; margin:2px; cursor:default" class="img-tooltip" data-id="'.$d.'" title="<img src='.AROOT.$d.'?'.time().'/>" data-toggle="tooltip" data-placement="right" src="'.AROOT.$d.'?'.time().'" />';
	}); $nr++;
	
	$columns[] = array( 'db' => 'agent_id', 'dt' => $nr, 'formatter' => function($d, $row ){
		return '<span class="emp_id">'.$d.'</span>';
	}); $nr++;
	
	$columns[] = array( 'db' => $_SESSION['RGadmin']['lang'].'_name', 'dt' => $nr ); $nr++;
	
	$columns[] = array( 'db' => 'phone', 'dt' => $nr ); $nr++;
	
	$columns[] = array( 'db' => 'region', 'dt' => $nr ); $nr++;
	
	$columns[] = array( 'db' => 'status', 'dt' => $nr, 'formatter' => function($d, $row )use($agent_status){
		$str = '<select class="statusAgent" style="width:auto; background:transparent; padding:0px 6px">';
		foreach($agent_status as $k => $v){ 
			$str .= '<option ';
			if($d == $k){$str .= "selected ";}
			$str .= 'value="'.$k.'">'.$v.'</option>';
		}
		$str .= '</select>';
		return $str;
	}); $nr++;

	$columns[] = array( 'db' => 'id', 'dt' => $nr, 'formatter' => function($d, $row ){
		return '<a data-id="'.$d.'" class="editAgent"><i class="fa fa-edit fa-lg"></i></a>';
	}); $nr++;
	


	require(DIR.'ajax/ssp.class.php');
	//var_dump(SSP::simple( $_GET, $sql_details, $table, $primaryKey, $columns, $joinQuery, $extraWhere ));exit;
	echo json_encode(
		SSP::simple( $_POST, $sql_details, $table, $primaryKey, $columns, $where)
	);

?>