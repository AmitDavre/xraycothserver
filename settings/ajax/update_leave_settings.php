<?
	if(session_id()==''){session_start();}
	ob_start();
	
	include("../../dbconnect/db_connect.php");
	//var_dump($_REQUEST);



	$ALMAXS = $_REQUEST['type']['AL']['max']['s'];
	$ALMAXM = $_REQUEST['type']['AL']['max']['m'];
	$ALPAYS = $_REQUEST['type']['AL']['pay']['s'];
	$ALPAYM = $_REQUEST['type']['AL']['pay']['m'];


	$_REQUEST['type']['AU']['max']['s'] = $ALMAXS;
	$_REQUEST['type']['AU']['max']['m'] = $ALMAXM;
	$_REQUEST['type']['AU']['pay']['s'] = $ALPAYS;
	$_REQUEST['type']['AU']['pay']['m'] = $ALPAYM;


	$sql = "UPDATE ".$cid."_leave_time_settings SET 
		leave_types = '".$dbc->real_escape_string(serialize($_REQUEST['type']))."', 
		request = '".$dbc->real_escape_string($_REQUEST['request'])."', 
		pr_leave_start = '".$dbc->real_escape_string($_REQUEST['pr_leave_start'])."', 
		pr_leave_end = '".$dbc->real_escape_string($_REQUEST['pr_leave_end'])."', 
		leave_start = '".$dbc->real_escape_string($_REQUEST['leave_start'])."', 
		leave_end = '".$dbc->real_escape_string($_REQUEST['leave_end'])."', 
		workingdays = '".$dbc->real_escape_string($_REQUEST['workingdays'])."', 
		dayhours = '".$dbc->real_escape_string($_REQUEST['dayhours'])."', 
		calc_attendance = '".$dbc->real_escape_string($_REQUEST['calc_attendance'])."', 
		attendance_target = '".$dbc->real_escape_string($_REQUEST['attendance_target'])."'"; 


	if($dbc->query($sql)){
		// if update then insert into leave period table 	
		// before insert first check if data exists in the table then update otherwise insert 

		if($_REQUEST['selectyear'] > 0)
		{
			// startdate conversion
			$convertStartdate    = \DateTime::createFromFormat('d/m/Y', $_REQUEST['startperiod']);
			$convertedSdate 	 = $convertStartdate->format('Y-m-d');
			
			// enddate conversuon 
			$convertEnddate		 = \DateTime::createFromFormat('d/m/Y', $_REQUEST['endperiod']);
			$convertedEdate 	 = $convertEnddate->format('Y-m-d');



			$sql3 = "SELECT * FROM ".$cid."_leave_periods WHERE leave_period_year = '".$_REQUEST['selectyear']."'";
			if($res3 = $dbc->query($sql3))
			{
				if($row3 = $res3->fetch_assoc())
				{
					// update query here
					$sql4 = "UPDATE ".$cid."_leave_periods SET leave_period_year = '".$dbc->real_escape_string($_REQUEST['selectyear'])."', leave_period_start = '".$dbc->real_escape_string($convertedSdate)."', leave_period_end = '".$dbc->real_escape_string($convertedEdate)."' WHERE leave_period_year = '".$_REQUEST['selectyear']."'"; 

					$res4 = $dbc->query($sql4);
				}
				else
				{
					// insert query here 

					$sql2 = "INSERT INTO ".$cid."_leave_periods (leave_period_year, leave_period_start,leave_period_end) VALUES ";
					$sql2 .= "('".$dbc->real_escape_string($_REQUEST['selectyear'])."', ";
					$sql2 .= "'".$dbc->real_escape_string($convertedSdate)."', ";
					$sql2 .= "'".$dbc->real_escape_string($convertedEdate)."')";
					$res2 = $dbc->query($sql2);

				}
			}
		}

		$err_msg = 'success';
	}else{
		$err_msg = mysqli_error($dbc);
	}



	
	ob_clean();
	echo $err_msg;
	exit;
		
	
?>
