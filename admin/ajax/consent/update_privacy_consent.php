<?
	if(session_id()==''){session_start(); ob_start();}
	include("../../dbconnect/db_connect.php");



	if($_REQUEST['agreeValue'] == 'yes')
	{
		$privacyConsentValue = '1';
	}	
	else if($_REQUEST['agreeValue'] == 'no')
	{
		$privacyConsentValue = '0';
	}

	$privacy_consent_date = date('d-m-Y H:i:s');


	$email = $_SESSION['RGadmin']['username'];
	$name = $_SESSION['RGadmin']['name'];
	// $cidvalue =$_SESSION['RGadmin']['cid'];



	$sql = "UPDATE rego_users SET 
			privacy_consent = '".$privacyConsentValue."', privacy_consent_date = '".$privacy_consent_date."' , logged_in_status = 'yes' , e_login_privacy = '".$privacyConsentValue."'  WHERE username = '".$email."'";

	
	$insertIntoLog= " INSERT INTO `rego_consent_log`(`name`,`consent_name`, `consent_date`,`user_name`,`privacy_consent`,`privacy_consent_date`,`consent_status`) VALUES ('".$name."','Privacy Policy', '".$privacy_consent_date."','".$email."','".$privacyConsentValue."','".$privacy_consent_date."','".$privacyConsentValue."')" ;

	$dba->query($insertIntoLog);



	if($dba->query($sql)){
		echo 'success';
	}else{
		echo mysqli_error($dba);
	}





