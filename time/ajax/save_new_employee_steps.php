<?
	if(session_id()==''){session_start(); ob_start();}
	include("../../dbconnect/db_connect.php");
	//var_dump($_REQUEST); //exit;
	
	if($_REQUEST['status'] == 'true'){$status = 1;}else{$status = 0;}
	
	$sql = "UPDATE ".$cid."_ot_employees SET 
		".$_REQUEST['field']." = '".$dbc->real_escape_string($status)."' 
		WHERE `id` = '".$_REQUEST['id']."'";
	//echo $sql; exit;
		
	if($dbc->query($sql)){
		ob_clean();	echo 'success';
	}else{
		ob_clean();	echo mysqli_error($dbc);
	}
	
?>