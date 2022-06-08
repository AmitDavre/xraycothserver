<?
	if(session_id()==''){session_start();} ob_start();
	include("../../dbconnect/db_connect.php");
	//var_dump($_REQUEST); //exit;
	
	$sql = "UPDATE xhr_clients SET status = '".$_REQUEST['status']."' WHERE id = '".$_REQUEST['id']."'";
	$dba->query($sql); 
	if($dba->query($sql)){
		//echo 'ok';
	}else{
		//echo mysqli_error($dba);
	}
?>
