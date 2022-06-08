<?
	if(session_id()==''){session_start();}
	ob_start();
	
	include("../../dbconnect/db_connect.php");
	//include(DIR."../../files/admin_functions.php");
	//$lng = getLangVariables($_SESSION['admin']['lang']);
	
	$sql = "UPDATE rego_default_leave_time_settings SET 
		leave_types = '".$dba->real_escape_string(serialize($_REQUEST['type']))."', 
		request = '".$dba->real_escape_string($_REQUEST['request'])."', 
		pr_leave_start = '".$dba->real_escape_string($_REQUEST['pr_leave_start'])."', 
		pr_leave_end = '".$dba->real_escape_string($_REQUEST['pr_leave_end'])."', 
		leave_start = '".$dba->real_escape_string($_REQUEST['leave_start'])."', 
		leave_end = '".$dba->real_escape_string($_REQUEST['leave_end'])."', 
		workingdays = '".$dba->real_escape_string($_REQUEST['workingdays'])."', 
		calc_attendance = '".$dba->real_escape_string($_REQUEST['calc_attendance'])."', 
		attendance_target = '".$dba->real_escape_string($_REQUEST['attendance_target'])."'"; 
		//echo $sql; //exit;
	if($dba->query($sql)){
		$err_msg = 'success';
	}else{
		$err_msg = mysqli_error($dba);
	}
	
	ob_clean();
	echo $err_msg;
	exit;
		
	
?>