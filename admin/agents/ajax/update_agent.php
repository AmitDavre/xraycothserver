<?
	if(session_id()==''){session_start();}
	ob_start();
	include("../../dbconnect/db_connect.php");
	//var_dump($_REQUEST); exit;

	$sql = "UPDATE rego_agents SET ".$_REQUEST['field']." = '".$_REQUEST['value']."' WHERE agent_id = '".$_REQUEST['id']."'";
	if($dba->query($sql)){
		echo 'success';
	}else{
		echo mysqli_error($dba);
	}
