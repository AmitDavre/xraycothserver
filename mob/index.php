<?php
	if(session_id()==''){session_start();}
	
	$logtime = 7200;
	if(isset($_SESSION['rego']['emp_id']) && !empty($_SESSION['rego']['emp_id'])){
		if(time() - $_SESSION['rego']['timestamp'] > $logtime) {
			$_SESSION['rego']['timestamp'] = 0;
			header('location: login.php');
		}else{
			$_SESSION['rego']['timestamp'] = time();
		}
	}else{
		header('location: login.php');
	}	
	
	// print_r($_SESSION);
	include('../dbconnect/db_connect.php');
	include(DIR.'files/arrays_'.$lang.'.php');
	include(DIR.'files/functions.php');
	include(DIR.'files/payroll_functions.php');
	include(DIR.'leave/functions.php');
	
	if(isset($_GET['y'])){
		$_SESSION['rego']['mob_year'] = $_GET['y'];
	}
	
	$cid = $_SESSION['rego']['cid'];
	$scan = false;
	if($res = $dbc->query("SELECT scan_system FROM ".$cid."_leave_time_settings")){
		$xrow = $res->fetch_assoc();
		if($xrow['scan_system'] == 'REGO'){$scan = true;}
	}
	
	if(!isset($_SESSION['rego']['mob_year'])){$_SESSION['rego']['mob_year'] = date('Y');}
	//$_SESSION['rego']['mob_year'] = date('Y');
	$_SESSION['rego']['year_en'] = $_SESSION['rego']['mob_year'];
	$_SESSION['rego']['year_th'] = ((int)$_SESSION['rego']['mob_year'])+543;
	$_SESSION['rego']['curr_month'] = date('m');
	$_SESSION['rego']['cur_month'] = date('n');
	$data = array();
	if($res = $dbc->query("SELECT * FROM ".$cid."_employees WHERE emp_id = '".$_SESSION['rego']['emp_id']."'")){
		$data = $res->fetch_assoc();
	}
	if(empty($data['image'])){$data['image'] = 'images/profile_image.jpg';}
	$_SESSION['rego']['payroll_dbase'] = $cid.'_payroll_'.$_SESSION['rego']['mob_year'];
	$_SESSION['rego']['emp_dbase'] = $cid.'_employees';
	$years = getYears($cid);
			
	//$leave_settings = getLeaveTimeSettings();
	$leave_types = getEmpLeaveTypes($cid);
	unset($leave_types['EL']);
	//var_dump($leave_types); //exit;
	
	$request = array();
	$confirmed = array();
	$assigned = array();
	/*$sql = "SELECT * FROM ".$cid."_ot_employees WHERE emp_id = '".$_SESSION['rego']['emp_id']."' AND ot_invited = 1 ORDER BY date DESC";
	if($res = $dbc->query($sql)){
		while($row = $res->fetch_assoc()){
			if($row['ot_confirmed']){
				$confirmed[$row['id']]['date'] = $row['date'];
				$confirmed[$row['id']]['from'] = $row['ot_from'];
				$confirmed[$row['id']]['until'] = $row['ot_until'];
				$confirmed[$row['id']]['type'] = $row['ot_type'];
			}
			if($row['ot_assigned']){
				$assigned[$row['id']]['date'] = $row['date'];
				$assigned[$row['id']]['from'] = $row['ot_from'];
				$assigned[$row['id']]['until'] = $row['ot_until'];
				$assigned[$row['id']]['type'] = $row['ot_type'];
			}
			if(!$row['ot_assigned'] && !$row['ot_confirmed']){
				$request[$row['id']]['date'] = $row['date'];
				$request[$row['id']]['from'] = $row['ot_from'];
				$request[$row['id']]['until'] = $row['ot_until'];
				$request[$row['id']]['type'] = $row['ot_type'];
			}
		}
	}*/
	//var_dump($assigned); exit;
	//var_dump($positions); 
	//var_dump($data); exit;
	if(!isset($_GET['mn'])){$_GET['mn'] = 2;}
	//if(!isset($_GET['mn'])){$_GET['mn'] = 1;}
	//var_dump($logger); //exit;

// echo '<pre>';
// print_r($_SESSION);
// echo '</pre>';
// die();


		// GET REGO STANDARD VAUES 

	$my_dbaname = $prefix.'admin';


	$dba = new mysqli($my_database,$my_username,$my_password,$my_dbaname);
	mysqli_set_charset($dba,"utf8");
	if($dba->connect_error) {
		echo'<p style="width:900px; margin:0 auto; margin-top:20px;" class="box_err">Error: ('.$dba->connect_errno.') '.$dba->connect_error.'<br>Please try again later or report this error to <a href="mailto:admin@regohr.com">admin@regohr.com</a></p>';
	}

	$sql102 = "SELECT * FROM rego_customers WHERE clientID = '".$_SESSION['rego']['cid']."'";

	if($res102 = $dba->query($sql102)){
		if($res102->num_rows > 0){
			if($row102 = $res102->fetch_assoc())
				{
					$versionValue = $row102['version'];  // SELECTED TEAMS STORED IN SESSION 

					$expire_date = $row102['period_end'];
					$diff = strtotime($row102['period_end']) - strtotime(date('d-m-Y'));
					$days_left = floor($diff / (60*60*24));
					if($days_left < 0){$days_left = 0;}
					$_SESSION['rego']['expire'] = $days_left;
					$_SESSION['rego']['status_val'] = $row102['status'];


						
				}
		}
	}


	$standardArray = $_SESSION['rego']['standard'];

	$leaveCheck = $standardArray[$versionValue]['leave'];
	$timeCheck = $standardArray[$versionValue]['time'];

	if(($days_left <= 0) || ($_SESSION['rego']['status_val'] =='0') || ($_SESSION['rego']['status_val'] =='2') || ($_SESSION['rego']['status_val'] =='3'))
	{
		header('location: '.ROOT.'mob/expired.php');
	}


	$sql111 = "SELECT * FROM ".$cid."_users WHERE username = '".$_SESSION['rego']['username']."'";
	if($res111 = $dbc->query($sql111)){
		while($row111 = $res111->fetch_assoc()){ 

			$permissionsVar = unserialize($row111['permissions']);
		}
	}
	

	// OT REQUEST  COUNT 


	$sql = "SELECT * FROM ".$cid."_ot_employees WHERE emp_id = '".$_SESSION['rego']['emp_id']."' AND ot_invited = 1 ORDER BY date DESC";
			if($res = $dbc->query($sql)){
				while($row = $res->fetch_assoc()){
					
					if($row['ot_confirmed'] == '1'){
						$confirmed[$row['id']]['date'] = $row['date'];
						$confirmed[$row['id']]['from'] = $row['ot_from'];
						$confirmed[$row['id']]['until'] = $row['ot_until'];
					}
					if($row['ot_assigned'] == '1'){
						$assigned[$row['id']]['date'] = $row['date'];
						$assigned[$row['id']]['from'] = $row['ot_from'];
						$assigned[$row['id']]['until'] = $row['ot_until'];
					}
					if($row['ot_assigned']  == '0' && $row['ot_confirmed'] == '0'){
						$request[$row['id']]['date'] = $row['date'];
						$request[$row['id']]['from'] = $row['ot_from'];
						$request[$row['id']]['until'] = $row['ot_until'];
					}
				}
			}


			$count_request = count($request);
			$count_assigned = count($assigned);
			$count_confirmed = count($confirmed);



		// check in rego all users table for the consent 
		$sql_consent = "SELECT * FROM rego_all_users WHERE username = '".$_SESSION['rego']['username']."'";
		if($res_consent = $dba->query($sql_consent)){
			if($row_consent = $res_consent->fetch_assoc()){
				$privacyConsent = $row_consent['privacy_consent']; // 0
				$privacy_consent_date = $row_consent['privacy_consent_date']; // 0
				$privacy_consent_change = $row_consent['privacy_consent_change']; // 0

				$termsConsent = $row_consent['terms_consent']; // 0
				$terms_consent_date = $row_consent['terms_consent_date']; // 0
				$terms_consent_change = $row_consent['terms_consent_change']; // 0

				$cookieConsent = $row_consent['cookie_consent']; // 0
				$cookie_consent_date = $row_consent['cookie_consent_date']; // 0
				$cookie_consent_change = $row_consent['cookie_consent_change']; // 0

				$showConsent = $row_consent['showConsent'];
				$logged_in_status = $row_consent['logged_in_status'];


			}
		}		


	// ========================RESET CONSENT VALUES ACCORDING TO THE SETTINGS FROM ADMIN PANEL ==============================//




	$sql_get_consent_setting = "SELECT * FROM rego_default_settings";
	if($res_get_consent_setting = $dba->query($sql_get_consent_setting)){
		if($row_get_consent_setting = $res_get_consent_setting->fetch_assoc()){

			$terms_renewal = $row_get_consent_setting['terms_renewal'];
			$privacy_renewal = $row_get_consent_setting['privacy_renewal'];
			$cookie_renewal = $row_get_consent_setting['cookie_renewal'];
			$consent_process = $row_get_consent_setting['consent_process'];
			$show_confirmation_text = $row_get_consent_setting['show_confirmation_text'];
		
		}
	}



	// ======================= TERMS & CONDITION ============================//
	if($terms_renewal == '1')
	{
		// everyday 
		// check here if the last saved time is greater than 24 hours 
		if($termsConsent == '1') // check if already agreed then ask again because if didn't agreed yet it will ask automatically 
		{
			everdayConsentCheckTerms($terms_consent_date,'24',$_SESSION['rego']['username'],$terms_consent_change,'1','1'); // checking for terms for everday reset 
		}

	}
	else if($terms_renewal == '2')
	{
		// 1 month with change 
		if($termsConsent == '1') // check if already agreed then ask again because if didn't agreed yet it will ask automatically 
		{
			everdayConsentCheckTerms($terms_consent_date,'720',$_SESSION['rego']['username'],$terms_consent_change,'30','2'); // checking for terms for everday reset 
		}
	}	
	else if($terms_renewal == '3')
	{
		// 1 month with change 
		if($termsConsent == '1') // check if already agreed then ask again because if didn't agreed yet it will ask automatically 
		{
			everdayConsentCheckTerms($terms_consent_date,'2160',$_SESSION['rego']['username'],$terms_consent_change,'90','3'); // checking for terms for everday reset 
		}
	}	
	else if($terms_renewal == '4')
	{
		// 1 month with change 
		if($termsConsent == '1') // check if already agreed then ask again because if didn't agreed yet it will ask automatically 
		{
			everdayConsentCheckTerms($terms_consent_date,'4320',$_SESSION['rego']['username'],$terms_consent_change,'180','4'); // checking for terms for everday reset 
		}
	}	
	else if($terms_renewal == '5')
	{
		// 1 month with change 
		if($termsConsent == '1') // check if already agreed then ask again because if didn't agreed yet it will ask automatically 
		{
			everdayConsentCheckTerms($terms_consent_date,'0',$_SESSION['rego']['username'],$terms_consent_change,'0','5'); // checking for terms for everday reset 
		}
	}
	// ======================= TERMS & CONDITION ============================//	

	// ======================= Privacy Policy Renewal ============================//
	if($privacy_renewal == '1')
	{
		// everyday 
		// check here if the last saved time is greater than 24 hours 
		if($privacyConsent == '1') // check if already agreed then ask again because if didn't agreed yet it will ask automatically 
		{
			everdayConsentCheckPrivacy($privacy_consent_date,'24',$_SESSION['rego']['username'],$privacy_consent_change,'1','1'); // checking for terms for everday reset 
		}

	}
	else if($privacy_renewal == '2')
	{
		// 1 month with change 
		if($privacyConsent == '1') // check if already agreed then ask again because if didn't agreed yet it will ask automatically 
		{
			everdayConsentCheckPrivacy($privacy_consent_date,'720',$_SESSION['rego']['username'],$privacy_consent_change,'30','2'); // checking for terms for everday reset 
		}
	}	
	else if($privacy_renewal == '3')
	{
		// 1 month with change 
		if($privacyConsent == '1') // check if already agreed then ask again because if didn't agreed yet it will ask automatically 
		{
			everdayConsentCheckPrivacy($privacy_consent_date,'2160',$_SESSION['rego']['username'],$privacy_consent_change,'90','3'); // checking for terms for everday reset 
		}
	}	
	else if($privacy_renewal == '4')
	{
		// 1 month with change 
		if($privacyConsent == '1') // check if already agreed then ask again because if didn't agreed yet it will ask automatically 
		{
			everdayConsentCheckPrivacy($privacy_consent_date,'4320',$_SESSION['rego']['username'],$privacy_consent_change,'180','4'); // checking for terms for everday reset 
		}
	}	
	else if($privacy_renewal == '5')
	{
		// 1 month with change 
		if($privacyConsent == '1') // check if already agreed then ask again because if didn't agreed yet it will ask automatically 
		{
			everdayConsentCheckPrivacy($privacy_consent_date,'0',$_SESSION['rego']['username'],$privacy_consent_change,'0','5'); // checking for terms for everday reset 
		}
	}
	// ======================= Privacy Policy  renewal============================//

	// ======================= Cookie Renewal ============================//
	if($cookie_renewal == '1')
	{
		// everyday 
		// check here if the last saved time is greater than 24 hours 
		if($cookieConsent == '1') // check if already agreed then ask again because if didn't agreed yet it will ask automatically 
		{
			everdayConsentCheckCookie($cookie_consent_date,'24',$_SESSION['rego']['username'],$cookie_consent_change,'1','1'); // checking for terms for everday reset 
		}

	}
	else if($cookie_renewal == '2')
	{
		// 1 month with change 
		if($cookieConsent == '1') // check if already agreed then ask again because if didn't agreed yet it will ask automatically 
		{
			everdayConsentCheckCookie($cookie_consent_date,'720',$_SESSION['rego']['username'],$cookie_consent_change,'30','2'); // checking for terms for everday reset 
		}
	}	
	else if($cookie_renewal == '3')
	{
		// 1 month with change 
		if($cookieConsent == '1') // check if already agreed then ask again because if didn't agreed yet it will ask automatically 
		{
			everdayConsentCheckCookie($cookie_consent_date,'2160',$_SESSION['rego']['username'],$cookie_consent_change,'90','3'); // checking for terms for everday reset 
		}
	}	
	else if($cookie_renewal == '4')
	{
		// 1 month with change 
		if($cookieConsent == '1') // check if already agreed then ask again because if didn't agreed yet it will ask automatically 
		{
			everdayConsentCheckCookie($cookie_consent_date,'4320',$_SESSION['rego']['username'],$cookie_consent_change,'180','4'); // checking for terms for everday reset 
		}
	}	
	else if($cookie_renewal == '5')
	{
		// 1 month with change 
		if($cookieConsent == '1') // check if already agreed then ask again because if didn't agreed yet it will ask automatically 
		{
			everdayConsentCheckCookie($cookie_consent_date,'0',$_SESSION['rego']['username'],$cookie_consent_change,'0','5'); // checking for terms for everday reset 
		}
	}
	// ======================= Cookie renewal   ============================//





	// ========================RESET CONSENT VALUES ACCORDING TO THE SETTINGS FROM ADMIN PANEL ==============================//


	// ======================= UPDATED CONSENT DATA ============================// 
	
		// check in rego all users table for the consent 
		$sql_consentLatest = "SELECT * FROM rego_all_users WHERE username = '".$_SESSION['rego']['username']."'";
		if($res_consentLatest = $dba->query($sql_consentLatest)){
			if($row_consentLatest = $res_consentLatest->fetch_assoc()){
				$privacyConsentLatest = $row_consentLatest['privacy_consent']; // 0
				$termsConsentLatest = $row_consentLatest['terms_consent']; // 0
				$terms_consent_dateLatest = $row_consentLatest['terms_consent_date']; // 0
				$terms_consent_changeLatest = $row_consentLatest['terms_consent_change']; // 0
				$cookieConsentLatest = $row_consentLatest['cookie_consent']; // 0
				$cookieConsentChangeLatest = $row_consentLatest['cookie_consent_change']; // 0
				$showConsentLatest = $row_consentLatest['showConsent'];
				$logged_in_statusLatest = $row_consentLatest['logged_in_status'];

				$privacy_consent_date = $row_consentLatest['privacy_consent_date'];
				$terms_consent_date = $row_consentLatest['terms_consent_date'];
				$cookie_consent_date = $row_consentLatest['cookie_consent_date'];
				$two_factor_authentication = $row_consentLatest['two_factor_authentication'];
				$authenticated = $row_consentLatest['authenticated'];

				$cookie_phpessid = $row_consentLatest['phpsessid'];
				$userlang = $row_consentLatest['lang'];
				$passlang = $row_consentLatest['rego_lang'];
				$two_factor_authenticationnnn = $row_consentLatest['two_factor_authentication'];
				$mob_show = $row_consentLatest['mob_show'];
				$e_login_cookies = $row_consentLatest['e_login_cookies'];



				if($privacyConsentLatest == '1'){
					$privacyAgreed = 'Agreed';
				}else{
					$privacyAgreed = 'Not Agreed';
				}

				if($termsConsentLatest == '1'){
					$termsAgreed = 'Agreed';
				}else{
					$termsAgreed = 'Not Agreed';
				}				

				if($cookieConsentLatest == '1'){
					$cookieAgreed = 'Agreed';
				}else{
					$cookieAgreed = 'Not Agreed';
				}



			}
		}	

	// ======================= UPDATED CONSENT DATA ============================// 



	// echo '<pre>';
	// print_r($permissionsVar);
	// echo '</pre>';
	// die();



?>

<!doctype html>
<html lang="en">

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="viewport"	content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1, viewport-fit=cover" />
	
	<meta name="apple-mobile-web-app-capable" content="yes" />
	<meta name="apple-mobile-web-app-status-bar-style" content="white-translucent">
	<meta name="theme-color" content="#ffffff">
	<title><?=$www_title?></title>

	<link rel="icon" type="image/png" href="assets/img/favicon.png" sizes="32x32">
	<link rel="apple-touch-icon" sizes="180x180" href="assets/img/icon/192x192.png">
	
	<link rel="apple-touch-icon" sizes="57x57" href="assets/img/icon/57x57.png">
	<link rel="apple-touch-icon" sizes="60x60" href="assets/img/icon/60x60.png">
	<link rel="apple-touch-icon" sizes="72x72" href="assets/img/icon/72x72.png">
	<link rel="apple-touch-icon" sizes="76x76" href="assets/img/icon/76x76.png">
	<link rel="apple-touch-icon" sizes="114x114" href="assets/img/icon/114x114.png">
	<link rel="apple-touch-icon" sizes="120x120" href="assets/img/icon/120x120.png">
	<link rel="apple-touch-icon" sizes="144x144" href="assets/img/icon/144x144.png">
	<link rel="apple-touch-icon" sizes="152x152" href="assets/img/icon/152x152.png">
	<link rel="apple-touch-icon" sizes="180x180" href="assets/img/icon/180x180.png">
	
	<link rel="icon" type="image/png" sizes="192x192" href="assets/img/icon/192x192.png">
	<link rel="icon" type="image/png" sizes="32x32" href="assets/img/icon/favicon-32x32.png">
	<link rel="icon" type="image/png" sizes="96x96" href="assets/img/icon/favicon-96x96.png">
	<link rel="icon" type="image/png" sizes="16x16" href="assets/img/icon/favicon-16x16.png"> 
	
	<link rel="manifest" href="__manifest.json">

	<meta name="msapplication-TileColor" content="#ffffff">
	<meta name="msapplication-TileImage" content="assets/img/icon/144x144.png">
	<meta name="theme-color" content="#ffffff">	
	
	<link rel="stylesheet" href="../assets/css/font-awesome.min.css">
	<link rel="stylesheet" href="../assets/css/line-awesome.min.css">
	<!--<link href="assets/css/mobStyle.css?<?=time()?>" rel="stylesheet">-->
	<link href="../assets/css/bootstrap-datepicker.css?<?=time()?>" rel="stylesheet">
	<link href="assets/css/jquery-clockpicker.css?<?=time()?>" rel="stylesheet">
	<link href="assets/css/bootstrap-datepicker.css?<?=time()?>" rel="stylesheet">
	<link rel="stylesheet" href="assets/css/style.css?<?=time()?>">
    <!-- Jquery -->
    <script src="assets/js/lib/jquery-3.4.1.min.js"></script>

</head>
<style type="text/css">
	.opacitycheck
	{
		opacity: 0.3;
	}



</style>




<body>

    <!-- loader -->
    <!--<div id="loader">
        <div class="spinner-border text-primary" role="status"></div>
    </div>-->
    <!-- * loader -->

    <!-- App Header -->
    <div class="appHeader text-light">
			<? if($_GET['mn'] > 2){?>
				<div class="left">
					<a href="#" class="headerButton goBack">
						 <i class="fa fa-angle-double-left fa-lg"></i>
					</a>
       			</div>
			<? } else {?>

				<div class="left">
					<a href="index.php?mn=1706" class="spanClass headerButton ">
						
						 <i class="fa fa-bell fa-lg"></i>

						 <?php if($count_request != '0') { ?>

						 	<span class='badge badge-warning' id='lblCartCount'> <?php echo $count_request; ?> </span>
						<?php } ?>
					</a>
       			</div>


			<?php } ?>
        <div class="pageTitle"><?=$compinfo[$lang.'_compname']?></div>
        <div class="right">
					<a href="#" class="headerButton" data-toggle="modal" data-target="#sidebarPanel">
						 <i class="fa fa-bars fa-lg"></i>
					</a>
				</div>
    </div>
    <!-- * App Header -->

	<? 

			if(!isset($_SESSION['RGadmin']['username']))
			{
				// if process on 

				// die('1');

				// echo '<pre>';
				// print_r($_SESSION);
				// echo '</pre>';
				// die();
				if($two_factor_authentication == '1' && $authenticated != '1')
				{
					// goto authentication page  if its true in my account setting
					header('location: '.ROOT.'mob/2fa.php');
				}
				else
				{

					if($consent_process == '1')
					{
						if( $termsConsentLatest != '1' && $logged_in_statusLatest != 'yes')
						{
							header('location: '.ROOT.'mob/terms_consent.php');
						
						}
						else if($privacyConsentLatest != '1' && $logged_in_statusLatest != 'yes')
						{
							header('location: '.ROOT.'mob/privacy_consent.php');
						}			

						else if($cookieConsentLatest != '1' && $logged_in_statusLatest != 'yes' && $cookie_phpessid !='1')
						{
							header('location: '.ROOT.'mob/cookie_consent.php');
						}		
						else if($cookieConsentLatest == '1' && $logged_in_statusLatest != 'yes' && $cookie_phpessid !='1' && $cookieConsentChangeLatest == '1')
						{
							header('location: '.ROOT.'mob/cookie_consent.php');
						}		
						else if($cookieConsentLatest != '1' && $logged_in_statusLatest != 'yes' && $cookie_phpessid =='1' && $cookieConsentChangeLatest == '1')
						{
							header('location: '.ROOT.'mob/cookie_consent.php');
						}
						else if( $cookieConsentLatest != '1' && $logged_in_statusLatest = 'yes' && $e_login_cookies != '1')
						{
							header('location: '.ROOT.'consent_detail.php');
						}
						else if( $termsConsentLatest != '1' && $logged_in_statusLatest = 'yes')
						{
						
						}
						else if( $privacyConsentLatest != '1' && $logged_in_statusLatest = 'yes')
						{
						
						}			
						else if( $cookieConsentLatest != '1' && $logged_in_statusLatest = 'yes')
						{
							// header('location: '.ROOT.'message.php');
						}
						else if( $termsConsentLatest== '1' && $privacyConsentLatest== '1' && $cookieConsentLatest == '1' && $showConsentLatest != '1')
						{

							$emailUsername = $_SESSION['rego']['username'];

							$sql_update_consent = "UPDATE rego_all_users SET showConsent = '1'  WHERE username = '".$emailUsername."'";
							$dbx->query($sql_update_consent);


						}
						else if($_SESSION['rego']['showConsentPage'] == '1')
						{
							if($show_confirmation_text == '1')
							{
								if($mob_show == '1')
								{

								}
								else
								{
									header('location: '.ROOT.'mob/consent_detail.php');
								}
							}
						}

					}
				}
			}




	switch($_GET['mn']){
			case 1: 
				//header('location: login.php'); break;
			case 2: 
				include('dashboard.php');	break;
			case 10: 
				include('personal.php'); break;
			case 11: 
				include('payslips.php'); break;
			case 12: 
				include('year_overview.php'); break;
			case 13: 
				//include('leave_calendar.php'); break;
				include('calendar.php'); break;
			case 14: 
				include('contact.php'); break;
			case 15: 
				include('password.php'); break;
			case 16: 
				include('time.php'); break;
			case 17: 
				include('leave.php'); break;
			case 1701: 
				include('app_leave.php'); break;
			case 1702: 
				include('app_time.php'); break;
			case 1703: 
				include('app_payroll.php'); break;
			case 1704: 
				include('app_time_register.php'); break;
			case 1705: 
				include('time_home.php'); break;
			case 1706: 
				include('ot_requests.php'); break;
			case 202: 
				include('privacy_policy_content.php'); break;
			
		}	?>

    <!-- App Bottom Menu -->
    <div class="appBottomMenu text-light">
        <a href="#" class="item logout">
            <div class="col"><i class="fa fa-sign-out fa-lg"></i></div>
        </a>
				<a href="#" class="item" data-toggle="modal" data-target="#selectyear" style="border-left:1px solid #666">
            <div class="col"><?=$_SESSION['rego']['year_'.$lang]?></div>
        </a>
				<? if($lang=='en'){ ?>
					<a href="#" style="border-left:1px solid #666" data-lng="th" class="item langbutton <? if($lang=='th'){echo 'activ';} ?>"><div class="col"><img src="../images/flag_th.png"></div></a>
				<? }else{ ?>
					<a href="#" style="border-left:1px solid #666" data-lng="en" class="item langbutton <? if($lang=='en'){echo 'activ';} ?>"><div class="col"><img src="../images/flag_en.png"></div></a>
				<? } ?>
        <a href="https://<?php echo $_SERVER['SERVER_NAME'];?>/hr/mob" class="item" style="border-left:1px solid #666">
            <div class="col"><i class="fa fa-home fa-lg"></i></div>
        </a>
    </div>
    <!-- * App Bottom Menu -->

    <!-- App Sidebar -->
    <div class="modal fade panelbox panelbox-right" id="sidebarPanel" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-body">

                    <!-- profile box -->
                    <div class="profileBox">
                        <div class="image-wrapper">
                            <!-- <img src="../<?=$default_logo?>"> -->
                        </div>
                        <a href="#" class="close-sidebar-button" data-dismiss="modal">
                            <i class="fa fa-times"></i>
                        </a>
                    </div>
                    <!-- * profile box -->

                    <ul class="custom-list" style=" border-bottom:1px solid #333; padding-bottom:10px;">
											<li>
												<a href="https://<?php echo $_SERVER['SERVER_NAME'];?>/hr/mob" class="item">
													<div class="icon-box" style="background: #eee; color:#333"><i class="fa fa-home fa-lg"></i></div>
													<em><?=$lng['Dashboard']?></em>
												</a>
											</li>
											<li>
												<a href="index.php?mn=10" class="item">
													<div class="icon-box bg-red-dark"><i class="fa fa-user"></i></div>
													<em><?=$lng['Personal data']?></em>
												</a>
											</li>
											<li>
												<a href="index.php?mn=11" class="item">
													<div class="icon-box bg-green-dark"><i class="fa fa-files-o"></i></div>
													<em><?=$lng['Payslips']?></em>
												</a>
											</li>
											<li>
												<a href="index.php?mn=12" class="item">
													<div class="icon-box bg-blue-dark"><i class="fa fa-list-ul"></i></div>
													<em><?=$lng['Year overview']?></em>
												</a>
											</li>
											<li>
												<a href="index.php?mn=13" class="item">
													<div class="icon-box bg-magenta-dark"><i class="fa fa-calendar"></i></div>
													<em><?=$lng['Calendar']?></em>
												</a>
											</li>
		<!-- 									<li>
												<a href="index.php?mn=17" class="item">
													<div class="icon-box bg-yellow-dark"><i class="fa fa-plane"></i></div>
													<em><?=$lng['Leave']?></em>
												</a>
											</li> -->
											<li>
												<a <?php if($leaveCheck == '1'){ 
														echo 'href="index.php?mn=17"';

													}else {

														echo 'href="#"';
													} ?> class="item">
													<div class="icon-box bg-yellow-dark"><i class="fa fa-plane"></i></div>
													<em><?=$lng['Leave']?></em>
												</a>
											</li>
											<?php if($_SESSION['rego']['type'] == 'sys' || $_SESSION['rego']['type'] == 'appr' || $_SESSION['rego']['type'] == 'app') { ?> 
											<?php if($permissionsVar['leave_application']['view'] == '1' ){?>		
											<li>
												<a href="index.php?mn=1701" class="item">
													<div class="icon-box bg-night-dark" style="background-color:#163e67!important;"><i class="fa fa-check"></i></div>
													<em><?=$lng['Approve leave']?></em>
												</a>
											</li>	
											<?php } ?>		

											<?php if($permissionsVar['payroll_result']['view'] == '1' ){?>								
											<li>
												<a href="index.php?mn=1703" class="item">
													<div class="icon-box bg-night-dark" style="background-color:#716512!important;"><i class="fa fa-money"></i></div>
													<em><?=$lng['Approve payroll']?></em>
												</a>
											</li>	
											<?php } ?>	
											<?php if($permissionsVar['time_attendance']['view'] == '1' ){?>								
											<li>
												<a href="index.php?mn=1702" class="item">
													<div class="icon-box bg-night-dark" style="background-color:#711212!important;"><i class="fa fa-clock-o"></i></div>
													<em><?=$lng['Approve time']?></em>
												</a>
											</li>
										<?php } ?>
											<?php } ?>
											<li>
												<a href="index.php?mn=14" class="item">
													<div class="icon-box bg-night-dark"><i class="fa fa-comments-o"></i></div>
													<em><?=$lng['Contact']?></em>
												</a>
											</li>
											<li>
												<a href="index.php?mn=15" class="item">
													<div class="icon-box bg-blue-light"><i class="fa fa-lock"></i></div>
													<em><?=$lng['My account']?></em>
												</a>
											</li>
											<li>
												<a href="#" class="item logout">
													<div class="icon-box bg-red-dark"><i class="fa fa-sign-out"></i></div>
													<em><?=$lng['Log out']?></em>
												</a>
											</li>
											<li >
												<a href="index.php?mn=202" class="item">
													<div class="icon-box bg-blue-light"><i class="fa fa-files-o"></i></div>
													<em><?=$lng['Privacy policy']?></em>
												</a>
											</li>
										</ul>

                    <ul class="custom-list" style="margin-top:10px !important">
											<li>
												<a href="tel:<?=$edata['comp_phone']?>" class="item">
													<div class="icon-box bg-green-dark"><i class="fa fa-phone"></i></div>
													<em><?=$lng['Call us']?></em>
												</a>
											</li>
											<li>
												<a href="sms:<?=$edata['comp_phone']?>" class="item">
													<div class="icon-box bg-red-dark"><i class="fa fa-comment-o"></i></div>
													<em><?=$lng['Text us']?></em>
												</a>
											</li>
											<li>
												<a href="mailto:<?=$edata['comp_email']?>?subject=Message from PKF Mobile (<?=$data[$lang.'_name']?>)" class="item">
													<div class="icon-box bg-blue-dark"><i class="fa fa-envelope-o"></i></div>
													<em><?=$lng['Mail us']?></em>
												</a>
											</li>
										</ul>
                </div>

            </div>
        </div>
    </div>
    <!-- * App Sidebar -->
    
		<!-- iOS Add to Home Action Sheet -->
    <div class="modal inset fade action-sheet ios-add-to-home" id="ios-add-to-home-screen" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add to Home Screen</h5>
                    <a href="javascript:;" class="close-button" data-dismiss="modal">
                        <ion-icon name="close"></ion-icon>
                    </a>
                </div>
                <div class="modal-body">
                    <div class="action-sheet-content text-center">
                        <div class="mb-1"><img style="height:30px" src="../<?=$default_logo?>"></div>
                        <h4><?=$www_title?></h4>
                        <div>
                            Install <?=$www_title?> on your iPhone's home screen.
                        </div>
                        <div>
                            Tap <ion-icon name="share-outline"></ion-icon> and Add to homescreen.
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- * iOS Add to Home Action Sheet -->
    
		<!-- Android Add to Home Action Sheet -->
    <div class="modal inset fade action-sheet android-add-to-home" id="android-add-to-home-screen" tabindex="-1"
        role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add to Home Screen</h5>
                    <a href="javascript:;" class="close-button" data-dismiss="modal">
                        <ion-icon name="close"></ion-icon>
                    </a>
                </div>
                <div class="modal-body">
                    <div class="action-sheet-content text-center">
                        <div class="mb-1"><img style="height:30px" src="../<?=$default_logo?>"></div>
                        <h4><?=$www_title?></h4>
                        <div>
                            Install <?=$www_title?> on your Android's home screen.
                        </div>
                        <div>
                            Tap <ion-icon name="ellipsis-vertical"></ion-icon> and Add to homescreen.
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- * Android Add to Home Action Sheet -->
        
		<!-- Select year -->
		<div class="modal fade action-sheet" id="selectyear" tabindex="-1" role="dialog">
			<div class="modal-dialog" role="document">
				<div class="modal-content">
					<div class="modal-header" style="background:#f6f6f6">
							<h5 class="modal-title" style="font-size:18px; font-weight:500">Select year</h5>
					</div>
					<div class="modal-body">
						<div style="padding:15px">
						 <? foreach($years as $k=>$v){
									$yr = $k;
									if($lang == 'th'){$yr = $k + 543;} ?>
									<button class="btn btn-info selYear mr-1 mb-1" data-year="<?=$k?>"><?=$yr?></button>
								<? } ?>
								<div class="clear"></div>
								<!--<button type="button" class="btn btn-danger btn-block" data-dismiss="modal">Close</button>-->
						</div>
					</div>
				</div>
			</div>
		</div>


	<div style="top: 82px!important;" class="modal fade" id="modalConsent1" tabindex="-1" role="dialog" data-backdrop="static" data-keyboard="false">
			<div class="modal-dialog" role="document">
				<div class="modal-content" >
					<div class="modal-header">
						<h5 class="modal-title"><?=$lng['Change password']?></h5>
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
					</div>
					<div class="modal-body">
						<form class="contactForm" id="changePassForm">
						<fieldset>
							<div class="form-group">
								<label for="opass"><?=$lng['Old password']?></label>
								<input type="password" name="opass" class="form-control" />
							</div>
							<div class="form-group">
								<label for="npass"><?=$lng['New password']?></label>
								<input type="password" name="npass" class="form-control" />
							</div>
							<div class="form-group">
								<label for="rpass"><?=$lng['Repeat new password']?></label>
								<input type="password" name="rpass" class="form-control"  />
							</div>
							
							<div id="passMsg" style="color:#b00; font-size:15px; text-align:center; margin-top:-10px; padding-bottom:5px; display:none"></div>
							<div class="contactFormButton">
								<button id="passBtn" style="font-size:16px" type="submit" class="btn btn-default btn-block"><?=$lng['Change password']?></button>
							</div>
							<div id="dump"></div>
						</fieldset>
					</form>  
				  </div>
			 </div>
		</div>
	</div>


	<div class="modal fade" id="modalConsent3" tabindex="-1" role="dialog" data-backdrop="static" data-keyboard="false">
		<div class="modal-dialog" role="document">
			<div class="modal-content" >
				<div class="modal-header">
					<h5 class="modal-title"><?=$lng['Change password']?></h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">

					<div style="padding: 5px 2px;">
						<label><input style="height: 14px;width: 14px;" type="checkbox" checked="checked" name="cookie_phpsessid" id= "cookie_phpsessid" value="1" > <span style="margin-left: 22px;font-weight: 600;"> Auto generated cookies for the functioning of our website:   <i class="man"></i></span> <p style="font-weight: 100;margin-left: 38px" > Cookies like (but not limited to) : PHPSESSID, rego_lang, scanlang  are auto generated cookies during your session and are required for the functioning of our website.</p></label>
					</div>
					<div style="padding: 5px 2px;">
						<label><input style="height: 14px;width: 14px;" type="checkbox"  name="cookie_lang"  <?php if($userlang == '1'){echo 'checked="checked"';}?> id="cookie_lang" value="1" ><span style="margin-left: 22px;font-weight: 600;"> Cookie Remember username : </span><p style="font-weight: 100;margin-left: 38px" > This is not a required Cookie for our website. It tells the browser to save a cookie so that you will not have to type the username at the sign-in process.</p></label>
					</div>
					<div style="padding: 5px 2px;">
						<label><input style="height: 14px;width: 14px;" type="checkbox" <?php if($passlang == '1'){echo 'checked="checked"';}?>  name="cookie_rego_lang" id="cookie_rego_lang" value="1" ><span style="margin-left: 22px;font-weight: 600;"> Cookie remember password :  </span><p style="font-weight: 100;margin-left: 38px" > This is not a required Cookie for our website. It tells the browser to save a cookie so that you will not have to type the password at the sign-in process.</p></label>
					</div>						

					<div style="height:15px"></div>
					<button id="modalConsentBtn" type="button" class="btn btn-primary btn-fl"><i class="fa fa-paper-plane-o"></i>&nbsp; <?=$lng['Submit']?></button>

						<button id="modalCancelConsentBtn" type="button" class="btn btn-primary btn-fr" data-dismiss="modal"><i class="fa fa-times"></i>&nbsp; <?=$lng['Cancel']?></button>
					<div class="clear"></div>
				</div>
		  	</div>
	 	</div>
	</div>


    <script src="assets/js/lib/popper.min.js"></script>
    <script src="assets/js/lib/bootstrap.min.js"></script>
    <script type="module" src="https://unpkg.com/ionicons@5.2.3/dist/ionicons/ionicons.js"></script>
    <script src="assets/js/plugins/owl-carousel/owl.carousel.min.js"></script>
    <script src="assets/js/plugins/jquery-circle-progress/circle-progress.min.js"></script>
		<script src="../assets/js/bootstrap-datepicker.min.js"></script>
		<script src="assets/js/jquery-clockpicker.js?<?=time()?>"></script>
    <script src="assets/js/base.js"></script>

<script type="text/javascript">
	
	// Toggle Add to Home Button with 3 seconds delay.
	// Toggle only once
	AddtoHome('3000', 'once');
	
	$(document).ready(function() {
		
		$(document).on("click", ".selYear", function(e){
			$.ajax({
				url: "ajax/change_year.php",
				data: {year: $(this).data('year')},
				success:function(result){
					//alert(result);
					window.location.reload();
				},
				error:function (xhr, ajaxOptions, thrownError){
					alert('<?=$lng['Error']?> ' + thrownError);
				}
			});
		});
		
		$('.langbutton').on('click', function(){
			$.ajax({
				url: "ajax/change_lang.php",
				data: {lng: $(this).data('lng')},
				success: function(ajaxresult){
					location.reload();
				}
			});
		});
		
		$(".logout").on('click', function(){ 
			$.ajax({
				url: "../ajax/logout.php",
				success: function(result){
					//alert(ROOT+SUBDIR+'/index.php')
					$.ajax({
						url: "ajax/logout.php",
						success: function(ajaxresult){
							window.location.href = 'login.php';

						}
					});

				},
				error:function (xhr, ajaxOptions, thrownError){
					//alert(thrownError);
				}
			});
		});


	})


	$(document).ready(function() {
		
		$('#regDate').datepicker({
			autoclose: true,
			format: 'D dd-mm-yyyy',
			language: '<?=$lang?>',
			startDate: new Date(),
			// startDate: addDays(leave_type),
		}).on('changeDate', function(e){
			startDate = e.format();
			$('#startModal').modal('toggle');
			$('#leavestart').html(e.format());
		});	
		
		
	})
	
</script>	
</body>

</html>
