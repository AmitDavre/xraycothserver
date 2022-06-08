<?
	if(session_id()==''){session_start(); ob_start();}
	include("../../dbconnect/db_connect.php");
	//var_dump($_REQUEST); exit;
	
	$sql = "DELETE FROM rego_default_holidays WHERE id = '".$_REQUEST['id']."'";
	//echo $sql;
	//exit;
	ob_clean();	
	if($dba->query($sql)){
		echo 'ok';
	}else{
		echo mysqli_error($dba);
	}
	exit;
?>