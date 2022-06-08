<?
	if(session_id()==''){session_start();}
	ob_start();
	include("../../dbconnect/db_connect.php");
	
	$data = array();
	$sql = "SELECT * FROM ".$cid."_workpermit WHERE emp_id = '".$_REQUEST['emp_id']."'";
	if($res = $dbc->query($sql)){
		if($row = $res->fetch_assoc()){
			$data = $row;
		}
	}else{
		//echo mysqli_error($dbc);
	}
	
	//var_dump($data); exit;
	ob_clean();
	echo json_encode($data);
	
?>
