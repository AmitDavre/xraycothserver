<?
	
	if(session_id()==''){session_start();}
	ob_start();

	include('../../dbconnect/db_connect.php');
	include(DIR.'files/arrays_'.$lang.'.php');
	

	// get default_scan_perimeter value 

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

$sql = "INSERT INTO ".$cid."_location (loc_name, code, qr, latitude, longitude ,perimeter,contact_name,address,contact_email) VALUES (
			'".$dbc->real_escape_string($_REQUEST['location_name'])."', 
			'".$dbc->real_escape_string($_REQUEST['code'])."', 
			'".$dbc->real_escape_string($_REQUEST['qr'])."', 
			'".$dbc->real_escape_string($_REQUEST['latitude'])."', 
			'".$dbc->real_escape_string($_REQUEST['longitude'])."', 
			'".$dbc->real_escape_string($perimeterVlaue)."', 
			'".$dbc->real_escape_string($_REQUEST['contact_name'])."', 
			'".$dbc->real_escape_string($_REQUEST['locations_address'])."', 
			'".$dbc->real_escape_string($_REQUEST['contact_email'])."'	)"; 
		
	
	
	ob_clean();
	if(!$dbc->query($sql)){
		echo 'error';
	}else{
		echo $dbc->insert_id;
	}


	
	exit;



















	
	
	
	
	
	
	
