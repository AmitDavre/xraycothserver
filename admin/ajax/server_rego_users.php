<?
	if(session_id()==''){session_start(); ob_start();}
	include('../dbconnect/db_connect.php');
	include(DIR.'files/arrays_en.php');
	
	$sql_details = array(
		'user' => $my_username,
		'pass' => $my_password,
		'db'   => $my_dbaname,
		'host' => $my_database
	);
	$where = '';
	/*if($_REQUEST['status'] != ''){
		$where = "status = ".$_REQUEST['status'];
	}*/
	// DB table to use
	$table = 'rego_all_users';
	
	// Table's primary key
	$primaryKey = 'id';
	
	$nr=0;
	
	/*$columns[] = array( 'db' => 'img', 'dt' => $nr, 'formatter' => function($d, $row ){
		if(empty($d)){$d = ROOT.'images/profile_image.jpg';}
		return '<img style="height:28px; width:28px; margin:2px; cursor:default" class="img-tooltip" data-id="'.$d.'" title="<img src='.ROOT.$d.'?'.time().'/>" data-toggle="tooltip" data-placement="right" src="'.ROOT.$d.'?'.time().'" />';
	}); $nr++;*/
	
	//$columns[] = array( 'db' => 'cid', 'dt' => $nr, 'formatter' => function($d, $row ){return strtoupper($d);}); $nr++;

	//$columns[] = array( 'db' => 'name', 'dt' => $nr ); $nr++;
	
	//$columns[] = array( 'db' => 'position', 'dt' => $nr ); $nr++;
	
	//$columns[] = array( 'db' => 'phone', 'dt' => $nr ); $nr++;
	
	$columns[] = array( 'db' => 'username', 'dt' => $nr, 'formatter' => function($d, $row ){return '<a href="mailto:'.$d.'">'.$d.'</a>';}); $nr++;
	
	$columns[] = array( 'db' => 'type', 'dt' => $nr, 'formatter' => function($d, $row )use($user_type){return $user_type[$d];}); $nr++;
	
	$columns[] = array( 'db' => 'access', 'dt' => $nr, 'formatter' => function($d, $row ){
		$tmp = explode(',', $d);
		if(count($tmp) > 1){
			$access = '<select style="width:auto; background:transparent; padding:0px 6px">';
			foreach($tmp as $v){$access .= '<option>'.strtoupper($v).'</option>';}
			$access .= '</select>';
		}else{
			$access = '<span style="padding-left:10px">'.strtoupper($tmp[0]).'</span>';
		}
		return $access;
	}); $nr++;
	
	/*$columns[] = array( 'db' => 'status', 'dt' => $nr, 'formatter' => function($d, $row )use($def_status){
		return '<span style="padding:0 10px">'.$def_status[$d].'</span>';
	}); $nr++;*/
	
	/*$columns[] = array( 'db' => 'status', 'dt' => $nr, 'formatter' => function($d, $row )use($def_status){
		$str = '<select data-id="'.$d.'" class="clstatus" style="width:auto; background:transparent; padding:0px 6px">';
		foreach($def_status as $k => $v){ 
			$str .= '<option ';
			if($d == $k){$str .= "selected ";}
			$str .= 'value="'.$k.'">'.$v.'</option>';
		}
		$str .= '</select>';
		return $str;
	}); $nr++;*/
	
	$columns[] = array( 'db' => 'id', 'dt' => $nr, 'formatter' => function($d, $row ){return '';}); $nr++;
	
	$columns[] = array( 'db' => 'id', 'dt' => $nr, 'formatter' => function($d, $row ){return '<a class="editUser" data-id="'.$d.'"><i class="fa fa-edit fa-lg"></i></a>';}); $nr++;
	
	//$columns[] = array( 'db' => 'id', 'dt' => $nr, 'formatter' => function($d, $row ){return '<a class="delUser" data-id="'.$d.'"><i class="fa fa-trash fa-lg"></i></a>';}); $nr++;


	require(DIR.'ajax/ssp.class.php');
	//var_dump(SSP::simple( $_GET, $sql_details, $table, $primaryKey, $columns, $joinQuery, $extraWhere ));exit;
	echo json_encode(
		SSP::simple( $_POST, $sql_details, $table, $primaryKey, $columns, $where)
	);

?>