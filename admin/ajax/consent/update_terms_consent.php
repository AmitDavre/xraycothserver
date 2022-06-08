<?
	if(session_id()==''){session_start(); ob_start();}
	include("../../dbconnect/db_connect.php");


	// echo '<pre>';
	// print_r($_SESSION);
	// echo '</pre>';

	// die();



	if($_REQUEST['agreeValue'] == 'yes')
	{
		$termsConsentValue = '1';
	}	
	else if($_REQUEST['agreeValue'] == 'no')
	{
		$termsConsentValue = '0';
	}

	$terms_consent_date = date('d-m-Y H:i:s');


	$username = $_SESSION['RGadmin']['username'];
	$name =$_SESSION['RGadmin']['name'];
	// $email =$_SESSION['RGadmin']['email'];


	$sql = "UPDATE rego_users SET 
			terms_consent = '".$termsConsentValue."', terms_consent_date = '".$terms_consent_date."' , logged_in_status = 'yes' ,  e_login_terms = '".$termsConsentValue."' WHERE username = '".$username."'";


	$insertIntoLog= " INSERT INTO `rego_consent_log`(`name`,`consent_name`, `consent_date`,`user_name`,`terms_consent`,`terms_consent_date`,`consent_status`) VALUES ('".$name."','Terms & Condition', '".$terms_consent_date."','".$username."','".$termsConsentValue."','".$terms_consent_date."','".$termsConsentValue."')" ;

	$dba->query($insertIntoLog);



	if($dba->query($sql)){
		echo 'success';
	}else{
		echo mysqli_error($dba);
	}
	





