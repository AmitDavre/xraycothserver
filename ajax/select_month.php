<?php
	if(session_id()==''){session_start();}
	ob_start();
	include('../dbconnect/db_connect.php');
	include(DIR.'files/arrays_'.$lang.'.php');
	
	$_SESSION['rego']['cur_month'] = $_REQUEST['month'];
	$_SESSION['rego']['gov_month'] = $_REQUEST['month'];
	$_SESSION['rego']['curr_month'] = sprintf('%02d', $_REQUEST['month']);
	$_SESSION['rego']['period'] = $months[$_REQUEST['month']].' '.$_SESSION['rego']['year_'.$lang];
	$dbc->query("UPDATE ".$cid."_sys_settings SET cur_month = '".$_REQUEST['month']."'");
	//ob_clean();
	//echo mysqli_error($dbc);
	ob_clean();
	echo $_REQUEST['month'];
?>