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



	$sql = "UPDATE rego_users SET two_factor_authentication = '".$two_factor_authentication."' WHERE username = '".$_SESSION['RGadmin']['username']."' ";

	if($dba->query($sql)){
		echo 'success';
	}else{
		echo mysqli_error($dba);
	}





