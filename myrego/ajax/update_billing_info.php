<?
	if(session_id()==''){session_start();}
	ob_start();
	include("../../dbconnect/db_connect.php");
	//var_dump($_REQUEST); //exit;
	
	$sql = "UPDATE rego_customers SET 
		".$lang."_compname = '".$dbx->real_escape_string($_REQUEST['compname'])."',
		".$lang."_address = '".$dbx->real_escape_string($_REQUEST['address'])."',
		".$lang."_billing = '".$dbx->real_escape_string($_REQUEST['billing'])."',
		tax_id = '".$dbx->real_escape_string($_REQUEST['tax_id'])."',
		certificate = '".$_REQUEST['certificate']."'  
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
