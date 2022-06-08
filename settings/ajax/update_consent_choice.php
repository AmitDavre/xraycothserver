<?
	if(session_id()==''){session_start();}
	ob_start();
	include("../../dbconnect/db_connect.php");

	if($_REQUEST['cookie_phpsessid'] == '0')
	{
		$cookie_consent = '0' ;
		$logged_in_status = NULL;
		$showConsent = '0';
	}
	else
	{
		$cookie_consent = '1' ;
		$logged_in_status = 'yes';
		$showConsent = '1';


	}


	if($_REQUEST['cookie_lang'] != '1')
	{
		unset($_COOKIE['username']); 
		setcookie("username", "", time() + (3600) , '/', '.xray.co.th' );
	}	

	if($_REQUEST['cookie_rego_lang'] != '1')
	{
		unset($_COOKIE['password']); 
		setcookie("password", "", time() + (3600 ) , '/', '.xray.co.th' );

	}

	$sql = "UPDATE rego_all_users SET phpsessid = '".$_REQUEST['cookie_phpsessid']."', lang = '".$_REQUEST['cookie_lang']."', rego_lang = '".$_REQUEST['cookie_rego_lang']."', scanlang = '".$_REQUEST['cookie_scanlang']."' , cookie_consent = '".$cookie_consent."' , logged_in_status = '".$logged_in_status."' , showConsent = '".$showConsent."' WHERE username = '".$_SESSION['rego']['username']."'";

	ob_clean();	
	if($dbx->query($sql)){
		echo 'success';
	}else{
		echo mysqli_error($dbx);
	}
	exit;
	
?>














