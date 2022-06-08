<?php

	if(session_id()==''){session_start();} 
	ob_start();

	
	unset($_SESSION['RGadmin']['showConsentPage']);

	
	if(isset($_SESSION['RGadmin']['username'])){

		include('../dbconnect/db_connect.php');
		include(DIR.'files/functions.php');
		include(DIR.'files/arrays_'.$lang.'.php');


		// GET ADMIN EMAIL
		$my_dbaname = $prefix.'admin';
		$dbadmin = new mysqli($my_database,$my_username,$my_password,$my_dbaname);
		mysqli_set_charset($dbadmin,"utf8");
		if($dbadmin->connect_error) {
			echo'<p style="width:900px; margin:0 auto; margin-top:20px;" class="box_err">Error: ('.$dbadmin->connect_errno.') '.$dbadmin->connect_error.'<br>Please try again later or report this error to <a href="mailto:admin@regohr.com">admin@regohr.com</a></p>';
		}

		$sql102 = "SELECT * FROM rego_company_settings WHERE id = '1'";

		if($res102 = $dbadmin->query($sql102)){
			if($res102->num_rows > 0){
				if($row102 = $res102->fetch_assoc())
					{
						$admin_mail_value = $row102['admin_mail'];  // SELECTED TEAMS STORED IN SESSION 
							
					}
			}
		}


		// check in rego all users table for the consent 
			$sql_consent = "SELECT * FROM rego_users WHERE username = '".$_SESSION['RGadmin']['username']."'";
			if($res_consent = $dbadmin->query($sql_consent)){
				if($row_consent = $res_consent->fetch_assoc()){
					$privacyConsent = $row_consent['privacy_consent'];
					$termsConsent = $row_consent['terms_consent'];
					$cookieConsent = $row_consent['cookie_consent'];


				}
			}


		$sql_get_consent_setting = "SELECT * FROM rego_default_settings";
			if($res_get_consent_setting = $dbadmin->query($sql_get_consent_setting)){
				if($row_get_consent_setting = $res_get_consent_setting->fetch_assoc()){

				$show_confirmation_text = $row_get_consent_setting['show_confirmation_text'];

				}
		}

		if($cookieConsent != '1' )
		{
			header('location: '.AROOT.'consent/message.php');
		}
		else
		{
			if($show_confirmation_text == '0')
			{
				header('location: '.AROOT.'index.php');
			}
			else
			{
				header('location: '.AROOT.'consent/consent.php');
			}
		}


}


?>













