<?
	if(session_id()==''){session_start();}
	ob_start();
	include('../../dbconnect/db_connect.php');
	include(DIR.'time/functions.php');



	$dateArray = explode(' ', $_REQUEST['dateSelect']);
	$convertDate = date('Y-m-d',strtotime($dateArray[1]));



	if($_REQUEST['scanValue'] == 'all')
	{
		$sql = "SELECT * FROM ".$cid."_attendance WHERE date = '".$convertDate."' ";
	}	
	else if($_REQUEST['scanValue'] == 'scan')
	{
		$sql = "SELECT * FROM ".$cid."_attendance WHERE date = '".$convertDate."' AND (scan1= '-' OR scan2= '-')";
	}	
	else if($_REQUEST['scanValue'] == 'ctime')
	{
		$sql = "SELECT * FROM ".$cid."_attendance WHERE date = '".$convertDate."' AND plan != '' ";
	}	
	else if($_REQUEST['scanValue'] == 'itime')
	{
		$sql = "SELECT * FROM ".$cid."_attendance WHERE date = '".$convertDate."' AND plan = '' ";
	}
	else if($_REQUEST['scanValue'] == 'ot')
	{
		$sql = "SELECT * FROM ".$cid."_attendance WHERE date = '".$convertDate."' AND (ot1 =! '0' OR ot15 =! '0' OR ot2 =! '0'OR ot3 =! '0' )";
	}	
	else if($_REQUEST['scanValue'] == 'leave')
	{
		$sql = "SELECT * FROM ".$cid."_attendance WHERE date = '".$convertDate."' AND leave_type  != '' ";
	}	
	else if($_REQUEST['scanValue'] == 'late')
	{
		$sql = "SELECT * FROM ".$cid."_attendance WHERE date = '".$convertDate."' AND (unpaid_late != '0' OR unpaid_early != '0' ) ";
	}	
	else if($_REQUEST['scanValue'] == 'plan')
	{
		$sql = "SELECT * FROM ".$cid."_attendance WHERE date = '".$convertDate."' AND plan = '' ";
	}

	if($res = $dbc->query($sql)){
		while($row = $res->fetch_assoc())
		{
			$data[] = $row;	
		}
	}

	// echo '<pre>';
	// print_r($data);
	// echo '</pre>';
	// die();
	
	// //var_dump($table); exit;
	echo json_encode($data);


?>


















