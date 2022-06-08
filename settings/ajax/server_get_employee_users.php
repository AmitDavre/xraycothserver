<?php
	if(session_id()==''){session_start();}
	ob_start();
	include('../../dbconnect/db_connect.php');
	include(DIR.'files/arrays_'.$_SESSION['rego']['lang'].'.php');
	
	$sql_details = array(
		'user' => $my_username,
		'pass' => $my_password,
		'db'   => $my_dbcname,
		'host' => $my_database
	);
	
	$where = '';
	$positions[''] = '';
	$emp_status[''] = '';

	$table = $cid.'_employees';
	$primaryKey = 'emp_id';

	$nr=0;
	$columns[] = array( 'db' => 'emp_id',  'dt' => $nr, 'formatter' => function($d, $row )use($emp_status){
		return '<span class="emp_id">'.$d.'</span>';
	}); $nr++;
	
	$columns[] = array( 'db' => 'image', 'dt' => $nr, 'formatter' => function($d, $row ){
		if(empty($d)){$d = "images/profile_image.jpg";}
		$detail = '';
		if($d != "../images/profile_image.jpg"){$detail = ' title="<img src=../'.$d.'?'.time().' />"  data-toggle="tooltip" data-placement="right"';}
		return '<center><img style="height:28px; width:28px" src="../'.$d.'?'.time().'"'.$detail.' /></center>';
		}); $nr++;

	$columns[] = array( 'db' => $_SESSION['rego']['lang'].'_name',  'dt' => $nr ); $nr++;
	
	$columns[] = array( 'db' => 'personal_email',  'dt' => $nr, 'formatter' => function($d, $row ){
		return '<span class="username">'.$d.'</span>';
	}); $nr++;
	
	$columns[] = array( 'db' => 'emp_status','dt' => $nr, 'formatter' => function($d, $row )use($emp_status){
		return '<span style="display:none">'.$d.'</span>'.$emp_status[$d];
	}); $nr++;
	
	$columns[] = array( 'db' => 'allow_login','dt' => $nr, 'formatter' => function($d, $row )use($lng){
		$select = '<select class="allow_login" style="min-width:100%;width:auto; background:transparent"><option';
		if($d == 0){$select .= ' selected';}
		$select .= ' value="0">'.$lng['No'].'</option><option';
		if($d == 1){$select .= ' selected';}
		$select .= ' value="1">'.$lng['Yes'].'</option></select>';
		return $select;
	}); $nr++;

	$columns[] = array( 'db' => 'personal_email','dt' => $nr, 'formatter' => function($d, $row )use($emp_status){
		return '<button type="button" class="btn btn-sm btn-primary" id="'.$d.'" onclick="changePassword(this.id)">Change password</button>';
	}); $nr++;
	
	$columns[] = array( 'db' => 'emp_id','dt' => $nr, 'formatter' => function($d, $row ){
		return '';
	}); $nr++;
	
	//var_dump($columns); exit;
	require(DIR.'ajax/ssp.class.php' );
	ob_clean();
	echo json_encode(
		SSP::simple($_GET, $sql_details, $table, $primaryKey, $columns, $where)
	);

?>