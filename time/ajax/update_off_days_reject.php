<?
	if(session_id()==''){session_start(); ob_start();}
	include("../../dbconnect/db_connect.php");
	

	$sql = "UPDATE ".$cid."_leaves_data SET status = '".$dbc->real_escape_string($_POST['action'])."' WHERE date = '".$_POST['date']."' AND emp_id='".$_POST['emp_id']."' ";


	if($dbc->query($sql)){
		ob_clean();	echo 'success'; exit;
	}else{
		ob_clean();	echo 'Error : '.mysqli_error($dbc);
	}
	
?>