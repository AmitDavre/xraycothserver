<?
	if(session_id()==''){session_start(); ob_start();}
	include("../../dbconnect/db_connect.php");
	include(DIR."files/functions.php");
	include(DIR."leave/functions.php");
	//$_REQUEST['emp_id'] = 'DEMO-001';

	$leave_time_settings = getLeaveTimeSettings();
	$leave_types = unserialize($leave_time_settings['leave_types']);
	foreach($leave_types as $k=>$v){
		$balance[$k] = array('th'=>$v['th'], 'en'=>$v['en'], 'maxdays'=>$v['max'][$_SESSION['rego']['emp_group']], 'maxpaid'=>$v['pay'][$_SESSION['rego']['emp_group']], 'pending'=>0, 'used'=>0);
	}
	$ALemp = getALemployee($cid, $_REQUEST['emp_id']);
	// $ALemp = getALemployeeOther($cid, $_REQUEST['emp_id'],$_SESSION['rego']['mob_year']);


	$currentyear = '%'.$_SESSION['rego']['mob_year'].'%';

	// $ALemp = getALemployee($cid, $_REQUEST['emp_id']);

	$previousYear = $_SESSION['rego']['mob_year'] -1 ;


		// if annual leave carry forward is yes then add AL condition here 

	// $sqlGetCarryForawrdAL= "SELECT * FROM ".$cid."_employee_per_year_records WHERE emp_id = '".$_REQUEST['emp_id']."'  AND year = '".$previousYear."'";
	// if($resSqlGetCarryForawrdAL = $dbc->query($sqlGetCarryForawrdAL)){
	// 	if($rowSqlGetCarryForawrdAL = $resSqlGetCarryForawrdAL->fetch_assoc()){
	// 		$carryForward = unserialize($rowSqlGetCarryForawrdAL['other_fields']);
	// 	}
	// }

	// if($carryForward['leaveForwardorNot'] == '1')
	// {
	// 	// carry forward annual leave balance 

	// 	// get annual leave balance 

	// 	$sqlGetAlBAl= "SELECT * FROM ".$cid."_employee_per_year_records WHERE emp_id = '".$_REQUEST['emp_id']."' AND year = '".$previousYear."'";



	// 	// die();
	// 	if($resSqlGetALBal = $dbc->query($sqlGetAlBAl)){
	// 		if($rowSQlgEtBal = $resSqlGetALBal->fetch_assoc()){
	// 			$annualLeaveBal  = $rowSQlgEtBal['annual_leave'];
	// 		}
	// 	}






	// 	if($annualLeaveBal)
	// 	{
	// 		$balance['AL']['maxdays'] = $annualLeaveBal;
	// 	}
	// 	else
	// 	{
	// 		$balance['AL']['maxdays'] = $ALemp;

	// 	}


	// }
	// else
	// {
		// do not carry forward 
		$balance['AL']['maxdays'] = $ALemp;

	// }
	


	$balance = getUsedLeaveEmployeeWithBal($cid, $_REQUEST['emp_id'], $balance, $currentyear, $_SESSION['rego']['mob_year']);



	$maxdaysAL= $balance['AL']['maxdays'];
	$maxpaidAL= $balance['AL']['maxpaid'];
	$pendingAL= $balance['AL']['pending'];
	$usedAL	  = $balance['AL']['used'];

	$maxdaysAU= $balance['AU']['maxdays'];
	$maxpaidAU= $balance['AU']['maxpaid'];
	$pendingAU= $balance['AU']['pending'];
	$usedAU	  = $balance['AU']['used'];




	$balance['AU']['maxdays']=  $maxdaysAL;
	$balance['AU']['maxpaid']= $maxpaidAL;
	$balance['AU']['pending']= $pendingAU + $pendingAL;
	$balance['AU']['used']  =  $usedAU + $usedAL;	
	$balance['AL']['maxdays']= $maxdaysAL;
	$balance['AL']['maxpaid']= $maxpaidAL;
	$balance['AL']['pending']= $pendingAU + $pendingAL;
	$balance['AL']['used']  =  $usedAU + $usedAL;	





	$maxdaysSL= $balance['SL']['maxdays'];
	$maxpaidSL= $balance['SL']['maxpaid'];
	$pendingSL= $balance['SL']['pending'];
	$usedSL	  = $balance['SL']['used'];

	$maxdaysSN= $balance['SN']['maxdays'];
	$maxpaidSN= $balance['SN']['maxpaid'];
	$pendingSN= $balance['SN']['pending'];
	$usedSN	  = $balance['SN']['used'];	

	$totSickpending		= $pendingSL + $pendingSN;
	$totSickused	 	= $usedSL    + $usedSN;


	$balance['SN']['maxdays']= $maxdaysSL;
	$balance['SN']['maxpaid']= $maxpaidSL;
	$balance['SN']['pending']= $totSickpending;
	$balance['SN']['used']  =  $totSickused;

	$balance['SL']['maxdays']= $maxdaysSL;
	$balance['SL']['maxpaid']= $maxpaidSL;
	$balance['SL']['pending']= $totSickpending;
	$balance['SL']['used']  =  $totSickused;




	ob_clean();	
	echo json_encode($balance);		
?>
