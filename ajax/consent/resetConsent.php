<?
	if(session_id()==''){session_start(); ob_start();}
	include("../../admin/dbconnect/db_connect.php");

	$email = $_SESSION['rego']['username'];

	// get terms / privacy / cookie renewal choice 

	// $renewalOptions   	= array(1=>'Every day', 2=>'Every month and if a change happens', 3=>'Every 3 months and if a change happens', 4=>'Every 6 months and if a change happens', 5=> 'Only if a change happens');

	$sql_get_consent_setting = "SELECT * FROM rego_default_settings";
	if($res_get_consent_setting = $dba->query($sql_get_consent_setting)){
		if($row_get_consent_setting = $res_get_consent_setting->fetch_assoc()){

			$terms_renewal = $row_get_consent_setting['terms_renewal'];
			$privacy_renewal = $row_get_consent_setting['privacy_renewal'];
			$cookie_renewal = $row_get_consent_setting['cookie_renewal'];
		
		}
	}


	// everyday after 24 hours reset 
	// 


	// $sql = "UPDATE rego_all_users SET 
	// 		privacy_consent = '".$privacyConsentValue."', privacy_consent_date = '".$privacy_consent_date."' , logged_in_status = 'yes' WHERE username = '".$email."'";

	
	// $insertIntoLog= " INSERT INTO `rego_consent_log`(`name`, `cid`,`consent_name`, `consent_date`,`user_name`,`privacy_consent`,`privacy_consent_date`,`consent_status`) VALUES ('".$name."', '".$cidvalue."', 'Privacy Policy', '".$privacy_consent_date."','".$email."','".$privacyConsentValue."','".$privacy_consent_date."','".$privacyConsentValue."')" ;

	// $dba->query($insertIntoLog);



	// if($dba->query($sql)){
	// 	echo 'success';
	// }else{
	// 	echo mysqli_error($dba);
	// }





