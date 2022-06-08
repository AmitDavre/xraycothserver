<?php
	
	if(session_id()==''){session_start();}
	ob_start();
	//$cid = $_SESSION['rego']['cid'];
	include('../../dbconnect/db_connect.php');
	include(DIR.'files/functions.php');
	include(DIR.'files/arrays_'.$lang.'.php');
	
	$var_allow = getUsedVarAllow($lang);
	$fix_allow = getUsedFixAllow($lang);
	//$fix_deduct = unserialize($pr_settings['fix_deduct']);
	//$var_deduct = unserialize($pr_settings['var_deduct']);
	//$months[0] = '';

	$table = $cid.'_historic_data';
	
	$sql_details = array(
		'user' => $my_username,
		'pass' => $my_password,
		'db'   => $my_dbcname,
		'host' => $my_database
	);
	
	$primaryKey = 'emp_id';
	
	$nr=0;
	$columns[] = array( 'db' => 'emp_id', 'dt' => $nr, 'formatter' => function($d, $row ){return '<input class="emp_id" name="emp_id[]" type="hidden" value="'.$d.'" />'.$d;}); $nr++;
	
	$columns[] = array( 'db' => 'emp_name_'.$_SESSION['rego']['lang'],  'dt' => $nr ); $nr++;
	
	$columns[] = array( 'db' => 'emp_id', 'dt' => $nr, 'formatter' => function($d, $row ){return '<a style="padding:0 5px" tabIndex="-1" data-id="'.$d.'" class="showEmpinfo"><i class="fa fa-info-circle fa-lg"></i></a>';}); $nr++;

	$columns[] = array( 'db' => 'month', 'dt' => $nr, 'formatter' => function($d, $row )use($months){return '<input name="month[]" type="hidden" value="'.$d.'" />'.$months[$d];}); $nr++;
	
	$columns[] = array( 'db' => 'gross_income', 'dt' => $nr, 'formatter' => function($d, $row ){return '<input readonly class="nofocus grossIncome" type="text" style="color:#b00" value="'.number_format($d,2).'" />';}); $nr++;
	
	$columns[] = array( 'db' => 'net_income', 'dt' => $nr, 'formatter' => function($d, $row ){return '<input readonly class="nofocus netIncome" type="text" style="color:#b00" value="'.number_format($d,2).'" />';}); $nr++;
	
	//$columns[] = array( 'db' => 'tax', 'dt' => $nr, 'formatter' => function($d, $row ){return '<input readonly class="nofocus" name="tax[]" type="text" value="'.$d.'" />';}); $nr++;
	
	$columns[] = array( 'db' => 'salary', 'dt' => $nr, 'formatter' => function($d, $row ){return '<input class="sel float72" name="salary[]" type="text" value="'.round($d,2).'" />';}); $nr++;
	
	$columns[] = array( 'db' => 'ot1b', 'dt' => $nr, 'formatter' => function($d, $row ){return '<input class="sel float72" name="ot1b[]" type="text" value="'.round($d,2).'" />';}); $nr++;
	
	$columns[] = array( 'db' => 'ot15b', 'dt' => $nr, 'formatter' => function($d, $row ){return '<input class="sel float72" name="ot15b[]" type="text" value="'.round($d,2).'" />';}); $nr++;
	
	$columns[] = array( 'db' => 'ot2b', 'dt' => $nr, 'formatter' => function($d, $row ){return '<input class="sel float72" name="ot2b[]" type="text" value="'.round($d,2).'" />';}); $nr++;
	
	$columns[] = array( 'db' => 'ot3b', 'dt' => $nr, 'formatter' => function($d, $row ){return '<input class="sel float72" name="ot3b[]" type="text" value="'.round($d,2).'" />';}); $nr++;
	
	$columns[] = array( 'db' => 'ootb', 'dt' => $nr, 'formatter' => function($d, $row ){return '<input class="sel float72" name="ootb[]" type="text" value="'.round($d,2).'" />';}); $nr++;

	foreach($fix_allow as $k=>$v){ 
		$columns[] = array( 'db' => 'fix_allow_'.$k, 'dt' => $nr, 'formatter' => function($d, $row )use($k){return '<input class="sel float72" name="fix_allow['.$k.'][]" type="text" value="'.round($d,2).'" />';}); $nr++;
	}
	
	foreach($var_allow as $k=>$v){ 
		$columns[] = array( 'db' => 'var_allow_'.$k, 'dt' => $nr, 'formatter' => function($d, $row )use($k){return '<input class="sel float72" name="var_allow['.$k.'][]" type="text" value="'.round($d,2).'" />';}); $nr++;
	}

	$columns[] = array( 'db' => 'tax_by_company', 'dt' => $nr, 'formatter' => function($d, $row ){return '<input class="sel float72" name="tax_by_company[]" type="text" value="'.round($d,2).'" />';}); $nr++;

	$columns[] = array( 'db' => 'sso_by_company', 'dt' => $nr, 'formatter' => function($d, $row ){return '<input class="sel float72" name="sso_by_company[]" type="text" value="'.round($d,2).'" />';}); $nr++;
	
	$columns[] = array( 'db' => 'other_income', 'dt' => $nr, 'formatter' => function($d, $row ){return '<input class="sel float72" name="other_income[]" type="text" value="'.round($d,2).'" />';}); $nr++;

	$columns[] = array( 'db' => 'tot_deduct_before', 'dt' => $nr, 'formatter' => function($d, $row )use($k){return '<input class="sel float72" name="tot_deduct_before[]" type="text" value="'.round($d,2).'" />';}); $nr++;
	
	$columns[] = array( 'db' => 'tot_deduct_after', 'dt' => $nr, 'formatter' => function($d, $row )use($k){return '<input class="sel float72" name="tot_deduct_after[]" type="text" value="'.round($d,2).'" />';}); $nr++;
	
	//$columns[] = array( 'db' => 'tot_deduct', 'dt' => $nr, 'formatter' => function($d, $row ){return '<input class="sel float72" name="tot_deduct[]" type="text" value="'.round($d,2).'" />';}); $nr++;
	
	$columns[] = array( 'db' => 'social', 'dt' => $nr, 'formatter' => function($d, $row ){return '<input class="sel float72" name="social[]" type="text" value="'.round($d,2).'" />';}); $nr++;
	
	$columns[] = array( 'db' => 'social_com', 'dt' => $nr, 'formatter' => function($d, $row ){return '<input class="sel float72" name="social_com[]" type="text" value="'.round($d,2).'" />';}); $nr++;
	
	$columns[] = array( 'db' => 'pvf_employee', 'dt' => $nr, 'formatter' => function($d, $row ){return '<input class="sel float72" name="pvf_employee[]" type="text" value="'.round($d,2).'" />';}); $nr++;
	
	$columns[] = array( 'db' => 'pvf_employer', 'dt' => $nr, 'formatter' => function($d, $row ){return '<input class="sel float72" name="pvf_employer[]" type="text" value="'.round($d,2).'" />';}); $nr++;
	
	$columns[] = array( 'db' => 'psf_employee', 'dt' => $nr, 'formatter' => function($d, $row ){return '<input class="sel float72" name="psf_employee[]" type="text" value="'.round($d,2).'" />';}); $nr++;
	
	$columns[] = array( 'db' => 'tax', 'dt' => $nr, 'formatter' => function($d, $row ){return '<input class="sel float72" name="tax[]" type="text" value="'.round($d,2).'" />';}); $nr++;
	
	$columns[] = array( 'db' => 'id', 'dt' => $nr, 'formatter' => function($d, $row ){return '<a tabIndex="-1" data-id="'.$d.'" class="delLine"><i class="fa fa-trash fa-lg"></i></a>';}); $nr++;

	
	require(DIR.'ajax/ssp.class.php' );
	
	//$joinQuery = "FROM $table";
	//var_dump(SSP::simple( $_GET, $sql_details, $table, $primaryKey, $columns, $joinQuery, $extraWhere ));exit;
	$where = "entity = '".$_SESSION['rego']['gov_entity']."' AND emp_group = '".$_SESSION['rego']['emp_group']."'";
	ob_clean();
	echo json_encode(
		SSP::simple($_POST, $sql_details, $table, $primaryKey, $columns, $where)
	);

?>