<?
	if(session_id()==''){session_start();}
	ob_start();
	include("../../dbconnect/db_connect.php");
		

	$sqlgetdata = "SELECT * FROM ".$cid."_leave_periods WHERE leave_period_year = '".$_REQUEST['year']."'";

	if($res = $dbc->query($sqlgetdata)){

		if($row = $res->fetch_assoc()){

			echo json_encode($row);
	
		}
	
	}

	exit;
?>
