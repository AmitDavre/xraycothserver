<?
	if(session_id()==''){session_start(); ob_start();}
	include("../dbconnect/db_connect.php");
	



	if($_REQUEST['consent_process'])
	{
		$consent_process = '1';
	}
	else
	{
		$consent_process = '0';
	}	

	if($_REQUEST['show_confirmation_text'])
	{
		$show_confirmation_text = '1';
	}
	else
	{
		$show_confirmation_text = '0';
	}




	if($_REQUEST['two_factor_authentication'])
	{
		$two_factor_authentication = '1';
	}
	else
	{
		$two_factor_authentication = '0';
	}
	
	// update in rego all user all the columns 
	// if privacy is 1 then set privacy change =0 
	// when there is a change in this drop down 


// $sql1 = "UPDATE rego_all_users SET privacy_consent_change = '0'  WHERE privacy_consent_change = '1' ";
// $dba->query($sql1);


		if($_REQUEST['privacy_renewal'] != '1')
		{

			$sql_consent = "SELECT * FROM rego_all_users";
			if($res_consent = $dba->query($sql_consent)){
				while($row_consent = $res_consent->fetch_assoc()){
				
		
					$sql1 = "UPDATE rego_all_users SET privacy_consent = '".$row_consent['e_login_privacy']."' ,privacy_consent_change = '0' WHERE id = '".$row_consent['id']."' ";
					$dba->query($sql1);


				}
			}				


			$sql_consent2 = "SELECT * FROM rego_users";
			if($res_consent2 = $dba->query($sql_consent2)){
				while($row_consent2 = $res_consent2->fetch_assoc()){
				
		
					$sql12 = "UPDATE rego_users SET privacy_consent = '".$row_consent2['e_login_privacy']."' ,privacy_consent_change = '0' WHERE user_id = '".$row_consent2['user_id']."' ";
					$dba->query($sql12);


				}
			}	
		}		

		if($_REQUEST['terms_renewal'] != '1')
		{

			$sql_consent = "SELECT * FROM rego_all_users";
			if($res_consent = $dba->query($sql_consent)){
				while($row_consent = $res_consent->fetch_assoc()){
				
		
					$sql1 = "UPDATE rego_all_users SET terms_consent = '".$row_consent['e_login_terms']."' ,terms_consent_change = '0' WHERE id = '".$row_consent['id']."' ";
					$dba->query($sql1);


				}
			}				


			$sql_consent3 = "SELECT * FROM rego_users";
			if($res_consent3 = $dba->query($sql_consent3)){
				while($row_consent3 = $res_consent3->fetch_assoc()){
				
		
					$sql13 = "UPDATE rego_users SET terms_consent = '".$row_consent3['e_login_terms']."' ,terms_consent_change = '0' WHERE user_id = '".$row_consent3['user_id']."' ";
					$dba->query($sql13);


				}
			}	
		}		

		if($_REQUEST['cookie_renewal'] != '1')
		{

			$sql_consent = "SELECT * FROM rego_all_users";
			if($res_consent = $dba->query($sql_consent)){
				while($row_consent = $res_consent->fetch_assoc()){
				
		
					$sql1 = "UPDATE rego_all_users SET cookie_consent = '".$row_consent['e_login_cookies']."' ,cookie_consent_change = '0' WHERE id = '".$row_consent['id']."' ";
					$dba->query($sql1);


				}
			}				


			$sql_consent4 = "SELECT * FROM rego_users";
			if($res_consent4 = $dba->query($sql_consent4)){
				while($row_consent4 = $res_consent4->fetch_assoc()){
				
		
					$sql14 = "UPDATE rego_users SET cookie_consent = '".$row_consent4['e_login_cookies']."' ,cookie_consent_change = '0' WHERE user_id = '".$row_consent4['user_id']."' ";
					$dba->query($sql14);


				}
			}	
		}



	$sql = "UPDATE rego_default_settings SET 
			terms_renewal = '".$dba->real_escape_string($_REQUEST['terms_renewal'])."', 
			privacy_renewal = '".$dba->real_escape_string($_REQUEST['privacy_renewal'])."', 
			cookie_renewal = '".$dba->real_escape_string($_REQUEST['cookie_renewal'])."',
			consent_process = '".$dba->real_escape_string($consent_process)."',
			show_confirmation_text = '".$dba->real_escape_string($show_confirmation_text)."' ";
	//ob_clean();	
	if($dba->query($sql)){
		echo 'success';
	}else{
		echo mysqli_error($dba);
	}





