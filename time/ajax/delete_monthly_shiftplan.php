<?php

	if(session_id()==''){session_start();}
	ob_start();
	include('../../dbconnect/db_connect.php');
	


	$sql = "DELETE FROM ".$cid."_monthly_shiftplans_".$cur_year." WHERE emp_id = '".$_POST['empId']."' AND month = '".$_POST['monthVal']."'";
	$res3 = $dbc->query($sql);


	ob_clean();	
	if($dbc->query($sql)){
		echo 'success';
	}else{
		echo mysqli_error($dbc);
	}
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	