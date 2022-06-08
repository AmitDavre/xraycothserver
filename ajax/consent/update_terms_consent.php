<?
	if(session_id()==''){session_start(); ob_start();}
	include("../../admin/dbconnect/db_connect.php");


	if($_REQUEST['agreeValue'] == 'yes')
	{
		$termsConsentValue = '1';
	}	
	else if($_REQUEST['agreeValue'] == 'no')
	{
		$termsConsentValue = '0';
	}

	$terms_consent_date = date('d-m-Y H:i:s');


	$email = $_SESSION['rego']['username'];
	$name =$_SESSION['rego']['name'];
	$cidvalue =$_SESSION['rego']['cid'];

	$sql = "UPDATE rego_all_users SET 
			terms_consent = '".$termsConsentValue."', terms_consent_date = '".$terms_consent_date."' , logged_in_status = 'yes' , e_login_terms = '".$termsConsentValue."' WHERE username = '".$email."'";


	$insertIntoLog= " INSERT INTO `rego_consent_log`(`name`, `cid`,`consent_name`, `consent_date`,`user_name`,`terms_consent`,`terms_consent_date`,`consent_status`) VALUES ('".$name."', '".$cidvalue."', 'Terms & Condition', '".$terms_consent_date."','".$email."','".$termsConsentValue."','".$terms_consent_date."','".$termsConsentValue."')" ;

	$dba->query($insertIntoLog);



	if($dba->query($sql)){
		echo 'success';
	}else{
		echo mysqli_error($dba);
	}





