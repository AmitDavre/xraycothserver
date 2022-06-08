<?
	if(session_id()==''){session_start();}
	ob_start();
	include("../../dbconnect/db_connect.php");

	$sql = "SELECT personal_email ,work_email FROM ".$cid."_employees WHERE emp_id = '".$_REQUEST['regoID']."' ";
	if($res = $dbc->query($sql)){
		if($row = $res->fetch_assoc()){

				echo json_encode($row);
		}
	}
	
?>
