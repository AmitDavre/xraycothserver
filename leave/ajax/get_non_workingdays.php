<?
	if(session_id()==''){session_start();}; ob_start();
	include('../../dbconnect/db_connect.php');
	include(DIR.'files/functions.php');
	$_REQUEST['emp_id'] = '10003';
	
	//$sd = getShiftDates($dbc, $cid, 'DWW');
	//var_dump($sd['days']);
	
	
	
	$non_workingdays = getNonWorkingDays($cid, $compinfo, $_REQUEST['emp_id']);
	$nwd = array();
	foreach($non_workingdays as $k=>$v){
		$nwd[] = $v;
	}
	//$non_workingdays['03-02-2018'] = strtotime('03-02-2018');
	//$non_workingdays['04-02-2018'] = strtotime('04-02-2018');
	//$non_workingdays[strtotime('31-01-2018')] = '31-01-2018';
	//$non_workingdays  = array(strtotime('13-01-2018'),strtotime('14-01-2018'),strtotime('31-03-2018'));
	
	
	var_dump($non_workingdays);
	//var_dump(date('d-m-Y',1516554000));
	ob_clean();
	echo json_encode($nwd);
?>
