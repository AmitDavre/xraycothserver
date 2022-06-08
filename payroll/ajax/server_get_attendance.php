<?php
	
	if(session_id()==''){session_start();} 
	ob_start();
	include('../../dbconnect/db_connect.php');
	include(DIR.'files/functions.php');
	include(DIR.'files/payroll_functions.php');
	
	$sql_details = array(
		'user' => $my_username,
		'pass' => $my_password,
		'db'   => $my_dbcname,
		'host' => $my_database
	);
	
	
	$data = array();
	$sql = "SELECT emp_id, emp_name_en, emp_name_th FROM ".$_SESSION['rego']['payroll_dbase']." WHERE month = ".$_SESSION['rego']['cur_month'];
	if($res = $dbc->query($sql)){
		while($row = $res->fetch_object()){
			if($_SESSION['rego']['lang'] == 'en'){
				$data[$row->emp_id] = array($row->emp_id, $row->emp_name_en);
			}else{
				$data[$row->emp_id] = array($row->emp_id, $row->emp_name_th);
			}
		}
	}
	
	$var_allow = getUsedVarAllow($lang);
	//$allow_names = $allowances[$lang];
	//$var_allow = $allowances['var'];
	
	//$btax_deduct = getUsedBTdeduct($lang);
	$var_deduct = getUsedVarDeduct($lang);

	$table = $_SESSION['rego']['payroll_dbase'];//'rego0100_employees';
	$primaryKey = 'emp_id';

	//if($data){
	$nr=0;
	
	$columns[] = array( 'db' => 'emp_id', 'dt' => $nr, 'formatter' => function($d, $row ){return $d.'<input name="empid[]" type="hidden" value="'.$d.'" />';}); $nr++;
	
	$columns[] = array( 'db' => 'emp_name_'.$_SESSION['rego']['lang'], 'dt' => $nr, 'formatter' => function($d, $row ){return '<span id="empname">'.$d.'</span>';}); $nr++;
	
	$columns[] = array( 'db' => 'emp_id', 'dt' => $nr, 'formatter' => function($d, $row )use($data){return '<a style="padding:0 5px" tabIndex="-1" data-id="'.$d.'" data-name="'.$data[$d][1].'" class="showEmpinfo"><i class="fa fa-info-circle fa-lg"></i></a>';}); $nr++;
	
	$columns[] = array( 'db' => 'paid_days', 'dt' => $nr, 'formatter' => function($d, $row ){return '<input class="sel float312" name="paid_days[]" type="text" value="'.number_format($d,2).'" />';}); $nr++;
	
	$columns[] = array( 'db' => 'ot1h', 'dt' => $nr, 'formatter' => function($d, $row ){return '<input class="sel hourFormat" name="ot1h[]" type="text" value="'.$d.'" />';}); $nr++;
	
	$columns[] = array( 'db' => 'ot15h', 'dt' => $nr, 'formatter' => function($d, $row ){return '<input class="sel hourFormat" name="ot15h[]" type="text" value="'.$d.'" />';}); $nr++;
	
	$columns[] = array( 'db' => 'ot2h', 'dt' => $nr, 'formatter' => function($d, $row ){return '<input class="sel hourFormat" name="ot2h[]" type="text" value="'.$d.'" />';}); $nr++;
	
	$columns[] = array( 'db' => 'ot3h', 'dt' => $nr, 'formatter' => function($d, $row ){return '<input class="sel hourFormat" name="ot3h[]" type="text" value="'.$d.'" />';}); $nr++;
	
	$columns[] = array( 'db' => 'ootb', 'dt' => $nr, 'formatter' => function($d, $row ){return '<input class="sel neg_numeric" name="ootb[]" type="text" value="'.$d.'" />';}); $nr++;
	
	$columns[] = array( 'db' => 'absence', 'dt' => $nr, 'formatter' => function($d, $row ){return '<input class="sel hourFormat" name="absence[]" type="text" value="'.$d.'" />';}); $nr++;
	
	$columns[] = array( 'db' => 'late_early', 'dt' => $nr, 'formatter' => function($d, $row ){return '<input class="sel hourFormat" name="late_early[]" type="text" value="'.$d.'" />';}); $nr++;
	
	$columns[] = array( 'db' => 'leave_wop', 'dt' => $nr, 'formatter' => function($d, $row ){return '<input class="sel hourFormat" name="leave_wop[]" type="text" value="'.$d.'" />';}); $nr++;
	
	foreach($var_allow as $k=>$v){
		$columns[] = array( 'db' => 'var_allow_'.$k, 'dt' => $nr, 'formatter' => function($d, $row )use($k){return '<input class="sel float72" name="var_allow_'.$k.'[]" type="text" value="'.$d.'" />';}); $nr++;
	}
	
	$columns[] = array( 'db' => 'other_income', 'dt' => $nr, 'formatter' => function($d, $row ){return '<input class="sel float72" name="other_income[]" type="text" value="'.$d.'" />';}); $nr++;
	
	$columns[] = array( 'db' => 'severance', 'dt' => $nr, 'formatter' => function($d, $row ){return '<input class="sel float72" name="severance[]" type="text" value="'.$d.'" />';}); $nr++;
	
	$columns[] = array( 'db' => 'remaining_salary', 'dt' => $nr, 'formatter' => function($d, $row ){return '<input class="sel float72" name="remaining_salary[]" type="text" value="'.$d.'" />';}); $nr++;
	
	$columns[] = array( 'db' => 'notice_payment', 'dt' => $nr, 'formatter' => function($d, $row ){return '<input class="sel float72" name="notice_payment[]" type="text" value="'.$d.'" />';}); $nr++;
	
	$columns[] = array( 'db' => 'paid_leave', 'dt' => $nr, 'formatter' => function($d, $row ){return '<input class="sel float72" name="paid_leave[]" type="text" value="'.$d.'" />';}); $nr++;
	
	foreach($var_deduct as $k=>$v){
		$columns[] = array( 'db' => 'var_deduct_'.$k, 'dt' => $nr, 'formatter' => function($d, $row )use($k){return '<input class="sel float72" name="var_deduct_'.$k.'[]" type="text" value="'.$d.'" />';}); $nr++;
	}
	
	$columns[] = array( 'db' => 'advance', 'dt' => $nr, 'formatter' => function($d, $row ){return '<input class="sel float72" name="advance[]" type="text" value="'.$d.'" />';}); $nr++;
	
	//$columns[] = array( 'db' => 'id', 'dt' => $nr, 'formatter' => function($d, $row ){return '<a style="padding:0 5px" tabIndex="-1" data-id="'.$d.'" class="editEmployee"><i class="fa fa-edit fa-lg"></i></a>';}); $nr++;
	
	$columns[] = array( 'db' => 'emp_id', 'dt' => $nr, 'formatter' => function($d, $row ){return '<a style="padding:0 5px" tabIndex="-1" data-id="'.$d.'" class="delEmployee"><i class="fa fa-trash fa-lg"></i></a>';}); $nr++;

//var_dump($columns); exit;

	require(DIR.'ajax/ssp.class.php' );
	
	$sentities = str_replace(',', "','", $_SESSION['rego']['selpr_entities']);
	$sbranches = str_replace(',', "','", $_SESSION['rego']['selpr_branches']);
	$sdivisions = str_replace(',', "','", $_SESSION['rego']['selpr_divisions']);
	$sdepartments = str_replace(',', "','", $_SESSION['rego']['selpr_departments']);
	$steams = str_replace(',', "','", $_SESSION['rego']['selpr_teams']);
	
	$where =  "month = '".$_SESSION['rego']['cur_month']."' AND paid != 'H'";    
	$where .= " AND emp_group = '".$_SESSION['rego']['emp_group']."'";
	if($_REQUEST['filter'] != 'all'){
		$where .= " AND calc_tax = '".$_REQUEST['filter']."'";
	}
	
	$where .= " AND entity IN ('".$sentities."')";
	$where .= " AND branch IN ('".$sbranches."')";
	$where .= " AND division IN ('".$sdivisions."')";
	$where .= " AND department IN ('".$sdepartments."')";
	$where .= " AND team IN ('".$steams."')";
	

	echo json_encode(
		SSP::complex( $_POST, $sql_details, $table, $primaryKey, $columns, '', $where)
	);
	
	
	
	
	