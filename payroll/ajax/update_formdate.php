<?php
	if(session_id()==''){session_start();}
	ob_start();
	include('../../dbconnect/db_connect.php');
	
	$enm = array(1=>"January", 2=>"February", 3=>"March", 4=>"April", 5=>"May", 6=>"June", 7=>"July", 8=>"August", 9=>"September", 10=>"October", 11=>"November", 12=>"December");
	$thm = array(1=>"มกราคม", 2=>"กุมภาพันธ์", 3=>"มีนาคม", 4=>"เมษายน", 5=>"พฤษภาคม", 6=>"กรกฏาคม", 7=>"มิถุนายน", 8=>"สิงหาคม", 9=>"กันยายน", 10=>"ตุลาคม", 11=>"พฤษจิกายน", 12=>"ธันวาคม");
	//$thm = array(1=>"มกราคม", 2=>"กุมภาพันธ์", 3=>"มีนาคม", 4=>"เมษายน", 5=>"พฤษภาคม", 6=>"มิถุนายน", 7=>"กรกฏาคม", 8=>"สิงหาคม", 9=>"กันยายน", 10=>"ตุลาคม", 11=>"พฤษจิกายน", 12=>"ธันวาคม");
	$date = date('d-m-Y', strtotime($_REQUEST['date']));
	$dat['d'] = date('d', strtotime($date));
	$dat['m'] = $enm[date('n', strtotime($date))];
	$dat['thm'] = $thm[date('n', strtotime($date))];
	$dat['eny'] = date('Y', strtotime($date));
	$dat['thy'] = date('Y', strtotime($date))+543;
	$dat['endate'] = $date;
	$dat['thdate'] = date('d-m-', strtotime($date)).$dat['thy'];
	
	$_SESSION['rego']['formdate']['endate'] = $date;
	$_SESSION['rego']['formdate']['thdate'] = $dat['thdate'];
	$_SESSION['rego']['formdate']['d'] = $dat['d'];
	$_SESSION['rego']['formdate']['m'] = $dat['m'];
	$_SESSION['rego']['formdate']['thm'] = $dat['thm'];
	$_SESSION['rego']['formdate']['eny'] = $dat['eny'];
	$_SESSION['rego']['formdate']['thy'] = $dat['thy'];
	
	$month = $_SESSION['rego']['cur_year'].'_'.$_SESSION['rego']['cur_month'];
	$sql = "INSERT INTO ".$_SESSION['rego']['cid']."_payroll_months (month, formdate) 
		VALUES ('".$month."', '".$_REQUEST['date']."') 
		ON DUPLICATE KEY UPDATE
		formdate = VALUES(formdate)";
	if(!$dbc->query($sql)){
		echo mysqli_error($dbc);
	}
	
	/*$sql = "UPDATE ".$_SESSION['rego']['cid']."_payroll_months SET formdate = '".$date."' WHERE month = '".$_SESSION['rego']['cur_month'].$_SESSION['rego']['cur_year']."'";
	$dbc->query($sql);*/
	
	//echo $sql;
	//echo '<br>xxx'.mysqli_error($dbc);
	//var_dump($dat); exit;
	ob_clean();
	echo json_encode($dat);
	
	
	
?>