<?
	if(session_id()==''){session_start();}
	ob_start();
	include("../../dbconnect/db_connect.php");
	//var_dump($_REQUEST); exit;
	
	$sql = "SELECT * FROM rego_agents WHERE agent_id = '".$_REQUEST['agent_id']."'";
	
	if($res = $dba->query($sql)){
		if($res->num_rows > 0){
			echo 'exist';
		}else{
			echo 'success';
		}
	}else{
		echo mysqli_error($dba);
	}
	//echo $sql;
	//ob_clean();
	exit;
?>