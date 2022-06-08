<?
	if(session_id()==''){session_start();}
	ob_start();
	include("../../dbconnect/db_connect.php");
	//var_dump($_REQUEST); exit;
	
	$sql = "UPDATE rego_customers SET 
		firstname = '".$dbx->real_escape_string($_REQUEST['firstname'])."',
		lastname = '".$dbx->real_escape_string($_REQUEST['lastname'])."',
		name = '".$dbx->real_escape_string($_REQUEST['firstname'].' '.$_REQUEST['lastname'])."',
		email = '".$dbx->real_escape_string($_REQUEST['email'])."',
		phone = '".$dbx->real_escape_string($_REQUEST['phone'])."',
		line_id = '".$dbx->real_escape_string($_REQUEST['line_id'])."' 
		WHERE clientID = '".$cid."'";
	//var_dump($sql);
	//echo($sql);
	//exit;
	ob_clean();	
	if($dbx->query($sql)){
		echo 'success';
	}else{
		echo mysqli_error($dbx);
	}
