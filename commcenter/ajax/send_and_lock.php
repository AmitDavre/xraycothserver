<?
	if(session_id()==''){session_start();}
	ob_start();
	include('../../dbconnect/db_connect.php');


	$publish_on = $_REQUEST['publishdate']; 

	if($publish_on !=''){
		$pubdate = date('Y-m-d H:i', strtotime($publish_on));
	}else{
		$pubdate = date('Y-m-d H:i');
	}

	//update logs...
	$dbc->query("INSERT INTO ".$cid."_commCenters_logs (cc_id, field, prev, new, user) VALUES ('".$_REQUEST['ccid']."','Send & lock','','Send & lock','".$_SESSION['rego']['name']."' ) ");

	$sql = "UPDATE ".$cid."_comm_centers SET `publish_on`='".$pubdate."', status='3' WHERE id='".$_REQUEST['ccid']."'";
	ob_clean();
	if($dbc->query($sql)){ 
		echo 'success';
	}else{
		echo mysqli_error($dbc);
	}


?>