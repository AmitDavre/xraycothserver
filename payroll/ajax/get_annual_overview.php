<?php
	if(session_id()==''){session_start();}
	ob_start();
	include('../../dbconnect/db_connect.php');
	include(DIR.'files/payroll_functions.php');
	include(DIR.'files/functions.php');
	include(DIR.'files/arrays_'.$_SESSION['rego']['lang'].'.php');
	
	include(DIR.'payroll/ajax/annual_tax_overview.php');
	//var_dump($empinfo['calc_pvf']); //exit;
	//var_dump($tax_return); exit;
	//var_dump($data); exit;
	
	include('annual_tax_table.php');
	
	ob_clean();
	$array['table'] = $table;
	$array['tax_return'] = round($tax_return,2);
	//echo $table; exit;
	echo json_encode($array);
?>






