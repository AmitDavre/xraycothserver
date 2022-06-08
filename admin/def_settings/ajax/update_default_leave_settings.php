<?
	if(session_id()==''){session_start();}
	ob_start();
	include("../../dbconnect/db_connect.php");
	
	$types = $_REQUEST['type'];
	$leave_types = array();
	foreach($types as $k=>$v){
		$leave_types[$v['code']] = $v;	
	}
	//var_dump($leave_types); exit;
	
	$sql = "UPDATE rego_default_leave_time_settings SET 
		leave_types = '".$dba->real_escape_string(serialize($leave_types))."', 
		request = '".$dba->real_escape_string($_REQUEST['request'])."', 
		pr_leave_start = '".$dba->real_escape_string($_REQUEST['pr_leave_start'])."', 
		leave_start = '".$dba->real_escape_string($_REQUEST['leave_start'])."', 
		workingdays = '".$dba->real_escape_string($_REQUEST['workingdays'])."', 
		dayhours = '".$dba->real_escape_string($_REQUEST['dayhours'])."', 
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