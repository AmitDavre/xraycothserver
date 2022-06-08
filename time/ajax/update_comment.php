<?
	if(session_id()==''){session_start(); ob_start();}
	include("../../dbconnect/db_connect.php");
	//var_dump($_REQUEST); exit;
	
	$sql = "UPDATE ".$cid."_attendance SET 
		comment = '".$dbc->real_escape_string($_REQUEST['text'])."' 
		WHERE `id` = '".$_REQUEST['id']."'";
	//echo $sql; exit;
		
	if($dbc->query($sql)){
		ob_clean();	echo 'success';
	}else{
		ob_clean();	echo mysqli_error($dbc);
	}
	
?>