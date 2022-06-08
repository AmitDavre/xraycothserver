<?
	if(session_id()==''){session_start();}
	ob_start();
	include("../../dbconnect/db_connect.php");
	//var_dump($_REQUEST); exit;

	$sql = "UPDATE rego_default_leave_time_settings SET comments = '".$dba->real_escape_string(serialize($_REQUEST['comments']))."'";
	ob_clean();	
	if($dba->query($sql)){
		echo 'success';
	}else{
		echo mysqli_error($dba);
	}
	
?>