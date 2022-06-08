<?
	if(session_id()==''){session_start();}
	ob_start();
	include('../../dbconnect/db_connect.php');

	$empIds = $_REQUEST['emparr'];
	$implodeids = implode(',', $empIds);

	if($_REQUEST['ccid'] > 0){

		//get pre data for this id...
		$getinfo = $dbc->query("SELECT * FROM ".$cid."_comm_centers WHERE id = '".$_REQUEST['ccid']."'");
		$rowinfo = $getinfo->fetch_assoc();

		//update logs...
		$dbc->query("INSERT INTO ".$cid."_commCenters_logs (cc_id, field, prev, new, user) VALUES ('".$_REQUEST['ccid']."','Send to','".$rowinfo['sent_to']."','".implode(',', $_REQUEST['selTeams'])."','".$_SESSION['rego']['name']."' ) ");

		$sql = "UPDATE ".$cid."_comm_centers SET `sent_to`='".implode(',', $_REQUEST['selTeams'])."', `sel_emp_ids`='".$implodeids."' WHERE id='".$_REQUEST['ccid']."'";
		ob_clean();
		if($dbc->query($sql)){
			echo 'success';
		}else{
			echo mysqli_error($dbc);
		}
	}



?>