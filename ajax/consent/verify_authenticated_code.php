<?
	if(session_id()==''){session_start(); ob_start();}
	include("../../admin/dbconnect/db_connect.php");


	$sql_updateotp = "SELECT * FROM rego_all_users WHERE username = '".$_SESSION['rego']['username']."'";
		if($res_updateotp = $dba->query($sql_updateotp)){
			if($row_updateotp = $res_updateotp->fetch_assoc()){

				$authenticated_code = $row_updateotp['authenticated_code'];
			}
	}


	$entered_authenticated_code = $_REQUEST['box1'].''.$_REQUEST['box2'].''.$_REQUEST['box3'].''.$_REQUEST['box4'].''.$_REQUEST['box5'].''.$_REQUEST['box6'];



	if($authenticated_code == $entered_authenticated_code)
	{
		// reset otp 

		$sql_reset = "UPDATE rego_all_users SET 
			authenticated_code = NULL , authenticated = '1' WHERE username = '".$_SESSION['rego']['username']."'";
		$dba->query($sql_reset);

		echo 'success';

	}
	else
	{
		echo 'error';
	}


	


