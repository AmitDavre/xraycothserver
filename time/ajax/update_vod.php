<?
	if(session_id()==''){session_start(); ob_start();}
	include("../../dbconnect/db_connect.php");
	
	$employeeId = $_POST['hiddenEmpID'].'-'.$_POST['monthFilter'];

	$totalApproved  = $_POST['hiddenCount'];

	$sql5 = "SELECT * FROM ".$cid."_monthly_shiftplans_".$cur_year." WHERE id = '".$employeeId."'";
	if($res5 = $dbc->query($sql5))
	{
		if($row5 = $res5->fetch_assoc())
		{
			$variableOff = $row5['vod'];			
			$offdayused = $row5['off_day_used'];			
			$wkd = $row5['wkd'];			
			$baloff = unserialize($row5['bal_off']);			
		}
	}




	$vod  =  $variableOff - $totalApproved; 
	$odu  =  $offdayused + $totalApproved;

	// if already approved selected do not subtract only subtract if new approved entry 


	// $wkd2  = $wkd -  $totalApproved;

	$bal_off = array(
		'off_day_pending' => $vod,
		'off_day_used' => $odu,

	);

	$sql2 = "UPDATE ".$cid."_monthly_shiftplans_".$cur_year." SET bal_off = '".$dbc->real_escape_string(serialize($bal_off))."'   WHERE  id='".$employeeId."' ";


	if($dbc->query($sql2)){

		ob_clean();	echo 'success'; exit;
	}else{
		ob_clean();	echo 'Error : '.mysqli_error($dbc);
	}
	
?>