<?php
	if(session_id()==''){session_start();}
	ob_start();
	include('../../dbconnect/db_connect.php');
	include(DIR.'files/functions.php');
	include(DIR.'files/arrays_'.$lang.'.php');
	//$_REQUEST['status'] = 7;
	
	$sql_details = array(
		'user' => $my_username,
		'pass' => $my_password,
		'db'   => $my_dbcname,
		'host' => $my_database
	);
	
	$employees = getEmployees($cid,0);
	$pat_bank = '%%%-%-%%%%%-%';
	$pat_idcard = '%-%%%%-%%%%%-%%-%';
	
	$sbranches = str_replace(',', "','", $_SESSION['rego']['sel_branches']);
	$sdivisions = str_replace(',', "','", $_SESSION['rego']['sel_divisions']);
	$sdepartments = str_replace(',', "','", $_SESSION['rego']['sel_departments']);
	$steams = str_replace(',', "','", $_SESSION['rego']['sel_teams']);
	
	// $where = "emp_group = '".$_SESSION['rego']['emp_group']."'";
	$where = " branch IN ('".$sbranches."')";
	$where .= " AND division IN ('".$sdivisions."')";
	$where .= " AND department IN ('".$sdepartments."')";
	$where .= " AND team IN ('".$steams."')";
	//$where .= " AND emp_status = ".$_REQUEST['status'];
	if($_REQUEST['status'] != ''){
		if($_REQUEST['status'] == 7){
			$where .= " AND title = '' OR joining_date = '' OR firstname = '' OR lastname = '' OR base_salary = 0 OR team = ''";
		}else{
			$where .= " AND emp_status = ".$_REQUEST['status'];
		}
	}
	//var_dump($where);
	//exit;

	// echo $where ;

	// exit;
	
	$emp_status[0] = '';
	$emp_status[''] = '';
	//if(!isset($positions['0'])){$positions['0'] = 'no Position';}
	//$positions['000'] = 'no Position';
	//var_dump($positions);
	//exit;

	$table = $cid.'_employees';
	$primaryKey = 'emp_id';

	$nr=0;
	$columns[] = array( 'db' => 'emp_id', 'dt' => $nr, 'formatter' => function($d, $row ){
		return '<span class="id">'.$d.'</span>';
	}); $nr++;
	
	$columns[] = array( 'db' => 'image', 'dt' => $nr, 'formatter' => function($d, $row ){
		if(empty($d)){$d = "images/profile_image.jpg";}
		$detail = '';
		if($d != "images/profile_image.jpg"){$detail = ' title="<img src=../'.$d.'?'.time().' />"  data-toggle="tooltip" data-placement="right"';}
		return '<img class="hover-bold" style="height:26px; width:26px" src="../'.$d.'?'.time().'"'.$detail.' />';
		}); $nr++;

	$columns[] = array( 'db' => $_SESSION['rego']['lang'].'_name',  'dt' => $nr ); $nr++;
	
	$columns[] = array( 'db' => 'entity', 'dt' => $nr, 'formatter' => function($d, $row )use($entities){
		if(!empty($d)){
			return $entities[$d][$_SESSION['rego']['lang']];
		}
	}); $nr++;

	$columns[] = array( 'db' => 'branch', 'dt' => $nr, 'formatter' => function($d, $row )use($branches){
		if(!empty($d)){
			return $branches[$d][$_SESSION['rego']['lang']];
		}
	}); $nr++;

	$columns[] = array( 'db' => 'division',  'dt' => $nr, 'formatter' => function($d, $row )use($divisions){
		if(!empty($d)){
			return $divisions[$d][$_SESSION['rego']['lang']];
		}
	}); $nr++;
	
	$columns[] = array( 'db' => 'department',  'dt' => $nr, 'formatter' => function($d, $row )use($departments){
		if(!empty($d)){
			return $departments[$d][$_SESSION['rego']['lang']];
		}
	}); $nr++;
	
	$columns[] = array( 'db' => 'team',  'dt' => $nr, 'formatter' => function($d, $row )use($teams){
		if(!empty($d)){
			return $teams[$d][$_SESSION['rego']['lang']];
		}
	}); $nr++;
	
	$columns[] = array( 'db' => 'position',  'dt' => $nr, 'formatter' => function($d, $row )use($positions){
		return $positions[$d][$_SESSION['rego']['lang']];
	}); $nr++;
	
	$columns[] = array( 'db' => 'joining_date',  'dt' => $nr, 'formatter' => function($d, $row )use($positions){
		return date('d-m-Y', strtotime($d));
	}); $nr++;
	
	$columns[] = array( 'db' => 'personal_phone', 'dt' => $nr ); $nr++;
	
	$columns[] = array( 'db' => 'personal_email', 'dt' => $nr, 'formatter' => function($d, $row ){
		return '<a href="mailto:'.$d.'">'.$d.'</a>';
	}); $nr++;
	
	
	$columns[] = array( 'db' => 'emp_status','dt' => $nr, 'formatter' => function($d, $row )use($emp_status){
		return '<span style="display:none">'.$d.'</span>'.$emp_status[$d];
	}); $nr++;
//var_dump($columns); exit;

	require(DIR.'ajax/ssp.class.php' );
	
	//var_dump(SSP::simple( $_POST, $sql_details, $table, $primaryKey, $columns, $where ));exit;
	ob_clean();


	echo json_encode(
		SSP::employeesimple($_POST, $sql_details, $table, $primaryKey, $columns, $where,$steams)
	);

?>
