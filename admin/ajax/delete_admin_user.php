<?
	if(session_id()==''){session_start();}
	ob_start();
	include("../dbconnect/db_connect.php");
	//var_dump($_REQUEST); exit;
	
	$sql = "DELETE FROM rego_users WHERE user_id = '".$_REQUEST['id']."'";
	if($dba->query($sql)){
		ob_clean();
		echo 'success';
		//writeToLogfile($_SESSION['xhr']['cid'], 'action', 'Update / Add '.$types[$_REQUEST['user_type']].' user : '.$_REQUEST['name'].' ('.$_REQUEST['user_id'].')');
	}else{
		echo mysqli_error($dba);
	}