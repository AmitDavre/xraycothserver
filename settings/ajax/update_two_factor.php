<?
	if(session_id()==''){session_start(); ob_start();}
	include("../../dbconnect/db_connect.php");



	if($_REQUEST['two_factor_authentication'])
	{
		$two_factor_authentication = '1';
	}
	else
	{
		$two_factor_authentication = '0';
	}	


	$sql = "UPDATE rego_all_users SET 
			two_factor_authentication = '".$dbc->real_escape_string($two_factor_authentication)."' WHERE username = '".$_SESSION['rego']['username']."' ";
	ob_clean();	
	if($dbx->query($sql)){
		echo 'success';
	}else{
		echo mysqli_error($dbx);
	}





