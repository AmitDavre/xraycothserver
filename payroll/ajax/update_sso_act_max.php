<?php
	if(session_id()==''){session_start();}
	ob_start();
	include('../../dbconnect/db_connect.php');
	
	$month = $_SESSION['rego']['cur_year'].'_'.$_SESSION['rego']['cur_month'];
	
	$sql = "INSERT INTO ".$_SESSION['rego']['cid']."_payroll_months (month, sso_act_max) 
		VALUES ('".$month."', '".$_REQUEST['val']."') 
		ON DUPLICATE KEY UPDATE
		sso_act_max = VALUES(sso_act_max)";
	if(!$dbc->query($sql)){
		//echo mysqli_error($dbc);
	}
	//echo $sql;
	//echo '<br>xxx'.mysqli_error($dbc);
	//echo json_encode($dat);
	
	
	
?>