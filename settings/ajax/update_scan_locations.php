<?
	if(session_id()==''){session_start();}
	ob_start();
	include("../../dbconnect/db_connect.php");
	//var_dump($_REQUEST); exit;

	$loc1 = json_encode($_REQUEST['location']['1']);
	$loc2 = json_encode($_REQUEST['location']['2']);
	$loc3 = json_encode($_REQUEST['location']['3']);
	$loc4 = json_encode($_REQUEST['location']['4']);
	$loc5 = json_encode($_REQUEST['location']['5']);


	$ref_id = $_REQUEST['location']['ref_id'];

	// fetch branches data  
	$sql2 = "SELECT * FROM ".$cid."_branches_data WHERE ref = '".$ref_id."'";
	if($res2 = $dbc->query($sql2)){
		if($row2 = $res2->fetch_assoc())
		{
			// update 

			$sql = "UPDATE ".$cid."_branches_data SET loc1 = '".$loc1."', loc2 = '".$loc2."', loc3 = '".$loc3."', loc4 = '".$loc4."', loc5 = '".$loc5."' WHERE ref = '".$ref_id."'";
		}
		else
		{
			//insert 
			$sql = "INSERT INTO ".$cid."_branches_data ( ref,loc1, loc2, loc3, loc4,loc5) VALUES (";
			$sql .= "'".$ref_id."',";
			$sql .= "'".$loc1."',";
			$sql .= "'".$loc2."',";
			$sql .= "'".$loc3."',";
			$sql .= "'".$loc4."',";
			$sql .= "'".$loc5."')";
		}
	
	}	


	// $sql = "UPDATE ".$cid."_leave_time_settings SET scan_locations = '".$dbc->real_escape_string(serialize($_REQUEST['location']))."'";

	
	if($dbc->query($sql)){
		ob_clean();	
		echo 'success';
		//writeToLogfile($_SESSION['xhr']['cid'], 'action', 'Update time settings');
	}else{
		ob_clean();	
		echo mysqli_error($dbc);
	}
	
