<?
	if(session_id()==''){session_start(); ob_start();}
	include("../../admin/dbconnect/db_connect.php");

	if($_REQUEST['agreeValue'] == 'yes')
	{
		$cookieConsentValue = '1';
	}	
	else if($_REQUEST['agreeValue'] == 'no')
	{
		$cookieConsentValue = '0';
	}


	$cookie_consent_date = date('d-m-Y H:i:s');



	$email = $_SESSION['rego']['username'];
	$name =$_SESSION['rego']['name'];
	$cidvalue =$_SESSION['rego']['cid'];




	$phpsessid = $_REQUEST['cookie_phpsessid'];
	$lang = $_REQUEST['cookie_lang'];
	$rego_lang = $_REQUEST['cookie_rego_lang'];
	$scanlang = $_REQUEST['cookie_scanlang'];


	// if($_REQUEST['cookie_lang'] != '1')
	// {
	// 	unset($_COOKIE['username']); 
	// 	setcookie("username", "", time() + (3600) , '/', '.xray.co.th' );
	// }	

	// if($_REQUEST['cookie_rego_lang'] != '1')
	// {
	// 	unset($_COOKIE['password']); 
	// 	setcookie("password", "", time() + (3600 ) , '/', '.xray.co.th' );

	// }

	



	$sql103 = "SELECT * FROM rego_all_users WHERE username = '".$email."'";

	if($res103 = $dba->query($sql103)){
		if($res103->num_rows > 0){
			if($row103 = $res103->fetch_assoc())
				{
					$phpsessid1 = $row103['phpsessid'];
					$lang_value1 =  $row103['lang'];
					$rego_lang1 =  $row103['rego_lang'];
				}
		}
	}


	if($_REQUEST['langchecked'] == 'no')
	{
		$usernamecookie= $lang_value1;
	}
	else
	{
		$usernamecookie= $lang;
		
	}	

	if($_REQUEST['regolangchecked'] == 'no')
	{
		$passwordcookie= $rego_lang1;
	}
	else
	{
		$passwordcookie= $rego_lang;
	}


	$sql = "UPDATE rego_all_users SET 
			cookie_consent = '".$cookieConsentValue."' , cookie_consent_date = '".$cookie_consent_date."' , logged_in_status = 'yes' , phpsessid = '".$phpsessid."', lang = '".$usernamecookie."', rego_lang = '".$passwordcookie."', scanlang = '".$scanlang."' , e_login_cookies = '".$cookieConsentValue."' WHERE username = '".$email."'";

	$insertIntoLog= " INSERT INTO `rego_consent_log`(`name`, `cid`,`consent_name`, `consent_date`,`user_name`,`cookie_consent`,`cookie_consent_date`,`consent_status`) VALUES ('".$name."', '".$cidvalue."', 'Cookie Consent', '".$cookie_consent_date."','".$email."','".$cookieConsentValue."','".$cookie_consent_date."','".$cookieConsentValue."')" ;

	$dba->query($insertIntoLog);



	if($dba->query($sql)){
		echo 'success';
	}else{
		echo mysqli_error($dba);
	}





