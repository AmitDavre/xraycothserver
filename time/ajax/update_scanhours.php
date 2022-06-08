<?

	if(session_id()==''){session_start();}
	ob_start();
	include("../../dbconnect/db_connect.php");
	include("../functions.php");
	//var_dump($_REQUEST); exit;
	
	/*function isValidDate($date, $format = 'H:i') {
		 $d = DateTime::createFromFormat($format, $date);
		 return $d && $d->format($format) == $date;
	}*/
	
	/*if(!isValidDate($_REQUEST['scan1'])){
		echo 'Scan 1 - Bad time format'; exit;
	}
	if(!isValidDate($_REQUEST['scan2'])){
		echo 'Scan 2 - Bad time format'; exit;
	}
	if(!isValidDate($_REQUEST['scan3'])){
		echo 'Scan 3 - Bad time format'; exit;
	}
	if(!isValidDate($_REQUEST['scan4'])){
		echo 'Scan 4 - Bad time format'; exit;
	}*/
	


	// echo '<pre>';
	// print_r($_REQUEST);
	// echo '</pre>';

	// die();
	$sql13= "SELECT * FROM ".$cid."_attendance WHERE id ='".$_REQUEST['id']."' ";	
	if($res13 = $dbc->query($sql13))
	{
		if($row13 = $res13->fetch_assoc())
		{
			$attendanceData[] = $row13;
		}
	}

	foreach ($attendanceData as $key => $value) {

		if(($value['scan1'] != '' && $value['scan1'] != '-') && ($value['scan2'] != '' && $value['scan2'] != '-')  && ($value['scan3'] != '' && $value['scan3'] != '-') && ($value['scan4'] != '' && $value['scan4'] != '-' ))
		{
			$countValue1 = '4';
		}
		else if($value['scan1'] != '' && $value['scan2'] != '')
		{
			$countValue1 = '2';
		}





	}


	// if 2 scans then update in scan2 if 4 scans  then update in scan4

	if($countValue1 == '2')
	{
		$sql = "UPDATE ".$cid."_attendance SET 
		scan1 = '".$dbc->real_escape_string($_REQUEST['scan1'])."', 
		scan2 = '".$dbc->real_escape_string($_REQUEST['scan4'])."', comp10 = '".$dbc->real_escape_string('1')."' 
		WHERE id = '".$_REQUEST['id']."'";
	}
	else if($countValue1 == '4')
	{
		$sql = "UPDATE ".$cid."_attendance SET 
		scan1 = '".$dbc->real_escape_string($_REQUEST['scan1'])."', 
		scan4 = '".$dbc->real_escape_string($_REQUEST['scan4'])."' , comp10 = '".$dbc->real_escape_string('1')."' 
		WHERE id = '".$_REQUEST['id']."'";
	}
	
		
	if($dbc->query($sql)){
		ob_clean();	echo 'success'; exit;
	}else{
		ob_clean();	echo 'Error : '.mysqli_error($dbc);
	}
	
