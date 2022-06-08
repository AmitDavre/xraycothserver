<?
	if(session_id()==''){session_start(); ob_start();}
	include("../../dbconnect/db_connect.php");
	
	$employeeId = $_POST['emp_id'].'-'.$_POST['monthFilter'];

	$sql = "UPDATE ".$cid."_leaves_data SET status = '".$dbc->real_escape_string($_POST['action'])."' WHERE date = '".$_POST['date']."' AND emp_id='".$_POST['emp_id']."' ";	


	$sql5 = "SELECT * FROM ".$cid."_monthly_shiftplans_".$cur_year." WHERE id = '".$employeeId."'";
	if($res5 = $dbc->query($sql5))
	{
		if($row5 = $res5->fetch_assoc())
		{
			$variableOff = $row5['vod'];			
			$offdayused = $row5['off_day_used'];			
			$dummy_vod = $row5['dummy_vod'];			
			$dummy_used = $row5['dummy_used'];			
		}
	}

 



	if($dbc->query($sql)){

		// add 1 off day to off day used

		ob_clean();	echo 'success'; exit;
	}else{
		ob_clean();	echo 'Error : '.mysqli_error($dbc);
	}
	
?>