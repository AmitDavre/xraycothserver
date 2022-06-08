<?
	if(session_id()==''){session_start();}
	ob_start();
	include("../../dbconnect/db_connect.php");
	$checkboxArray = $_REQUEST['checkboxArray'];

	$searlizezArray = serialize($checkboxArray);

	// if no checkbox is selected set value to zero 
	$scanSystemVal = $_REQUEST['scanSystemVal'];

	if($scanSystemVal)
	{
		$sql = "UPDATE ".$cid."_leave_time_settings SET compensations = '".$searlizezArray."' , scan_system = '0' WHERE id = '1'";
	}
	else
	{
		// compensation field is used for saving checkbox value of scan systems
		$sql = "UPDATE ".$cid."_leave_time_settings SET compensations = '".$searlizezArray."' WHERE id = '1'";
	}

	
	if($dbc->query($sql)){
		ob_clean();	
		echo 'success';
	}else{
		ob_clean();	
		echo mysqli_error($dbc);
	}
	
