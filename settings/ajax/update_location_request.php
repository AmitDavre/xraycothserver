<?
	
	if(session_id()==''){session_start();}
	ob_start();

	include('../../dbconnect/db_connect.php');
	include(DIR.'files/arrays_'.$lang.'.php');


	$checkboxValue1 = $_REQUEST['confirmationcheckIn'];
	if($checkboxValue1)
	{
		$checkboxValue = $_REQUEST['confirmationcheckIn'];
	}
	else
	{
		$checkboxValue = 0;
	}



	$sql2 = "SELECT default_scan_perimeter FROM ".$cid."_leave_time_settings WHERE id = '1'";

	if($res2 = $dbc->query($sql2)){
		if($row2 = $res2->fetch_assoc())
		{
			$DefaultperimeterVlaue = $row2['default_scan_perimeter'];
		}
	
	}


	if(!$_REQUEST['perimeter'])
	{
		$perimeterVlaue = $DefaultperimeterVlaue;
	}
	else
	{
		$perimeterVlaue = $_REQUEST['perimeter'] ;
	}


$sql = "UPDATE ".$cid."_location SET loc_name = '".$dbc->real_escape_string($_REQUEST['name'])."',scan_in_confirmation = '".$dbc->real_escape_string($checkboxValue)."',code = '".$dbc->real_escape_string($_REQUEST['code'])."',qr = '".$dbc->real_escape_string($_REQUEST['qr'])."',latitude = '".$dbc->real_escape_string($_REQUEST['latitude'])."',longitude = '".$dbc->real_escape_string($_REQUEST['longitude'])."',perimeter = '".$dbc->real_escape_string($perimeterVlaue)."',contact_name = '".$dbc->real_escape_string($_REQUEST['contact_name'])."',address = '".$dbc->real_escape_string($_REQUEST['locations_address'])."',contact_email = '".$dbc->real_escape_string($_REQUEST['contact_email'])."' WHERE loc_id = '".$_REQUEST['ref_id']."'";
	
	ob_clean();	
	
	if($dbc->query($sql)){
		echo 'success';
	}else{
		echo mysqli_error($dbc);
	}





	
	exit;



















	
	
	
	
	
	
	
