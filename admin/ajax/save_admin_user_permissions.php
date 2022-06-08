<?
	if(session_id()==''){session_start();} 
	ob_start();
	include("../dbconnect/db_connect.php");
	//var_dump($_REQUEST); exit;
	
	$user_id = $_REQUEST['user_id'];
	unset($_REQUEST['user_id']);

	$sql = "UPDATE rego_users SET 
		access = '".$dba->real_escape_string(serialize($_REQUEST))."'  
		WHERE user_id = '".$user_id."'";
		
	if($dba->query($sql)){
		ob_clean();
		echo 'ok';
		//writeToLogfile($_SESSION['xhr']['cid'], 'action', 'Update user permissions for : '.$user_name.' ('.$user_id.')');
	}else{
		ob_clean();
		echo mysqli_error($dba);
	}
	exit;
	
?>














