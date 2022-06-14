<?php

	if(session_id()==''){session_start();} 
	ob_start();


	
	$isMob = is_numeric(strpos(strtolower($_SERVER['HTTP_USER_AGENT']), "mobile"));
	$isTab = is_numeric(strpos(strtolower($_SERVER['HTTP_USER_AGENT']), "tablet"));
	$isDesktop = !$isMob && !$isTab;
	if($isMob || $isTab){
		header('location: mob/login.php'); exit;
	}

	include('dbconnect/db_connect.php');
	include(DIR.'files/functions.php');
	include(DIR.'files/arrays_'.$lang.'.php');

	$logger = false;
	$years = '';
	$checkSetup = '';
	$periods = array();
	//$_SESSION['rego']['locked'] = true;
	$locked = true;
	$program = 20;
	$customers = array();
	$history_lock = 1;
	$suspended = 0;
	$expire_date = '';
	$days_left = 365;
	$price_table = array();
	$buyrego = false;
	$BuyRego = $lng['Buy REGO'];
	//var_dump($_SESSION['rego']['cid']); //exit;
	
	if(isset($_SESSION['rego']['cid']) && !empty($_SESSION['rego']['cid'])){
		
		// CHECK IF CUSTOMER IS NOT SUSPENDED //////////////////////////////////////////////////////////////
		$sql = "SELECT * FROM rego_customers WHERE clientID = '".$cid."'";
		if($res = $dbx->query($sql)){
			if($row = $res->fetch_assoc()){
				if($row['status'] == 0){ $suspended = 1;}
				$_SESSION['rego']['version'] = $row['version'];
				$standard = $row['version'];
				$_SESSION['rego']['max'] = $row['employees'];
				$_SESSION['rego']['emp_platform'] = $row['emp_platform'];
				$_SESSION['rego']['phone'] = $row['phone'];
				$_SESSION['rego']['email'] = $row['email'];
				$expire_date = $row['period_end'];
				$diff = strtotime($row['period_end']) - strtotime(date('d-m-Y'));
				$days_left = floor($diff / (60*60*24));
				if($days_left < 0){$days_left = 0;}
				if($row['version'] > 0 && $days_left < 30){$buyrego = true; $BuyRego = $lng['Extend REGO'];}
				if($row['version'] == 0){$buyrego = true;}
				if($row['status'] == 2){$buyrego = false;}
				if($days_left > 35){$buyrego = false;}
				$_SESSION['rego']['expire'] = $days_left;
				$_SESSION['rego']['status_val'] = $row['status'];
			}
		}
		
		if(isset($_SESSION['RGadmin'])){
			$logtime = 86000;
		}else{
			$logtime = (int)$comp_settings['logtime'];
			// $logtime = (int)$comp_settings['logtime'];
		}
		//var_dump($logtime);
		if($logtime < 60){
			$logtime = 900; // 15 min
		}

		// $logtime= '1';
		//var_dump($logtime);
		if(time() - $_SESSION['rego']['timestamp'] > $logtime) {
			$_SESSION['rego']['timestamp'] = 0;
			$logger = false; 
		}else{
			$_SESSION['rego']['timestamp'] = time();
			$logger = true;
			$years = getYears(); // Get payroll Years
			//var_dump($years);
			//$years = array(2019=>2019);//$years[$lang];
			$_SESSION['rego']['payroll_dbase'] = $_SESSION['rego']['cid'].'_payroll_'.$_SESSION['rego']['cur_year'];
			$_SESSION['rego']['emp_dbase'] = $cid.'_employees';
			//$_SESSION['rego']['paydate'] = getPaydate($cid);
			if(!isset($_SESSION['rego']['period'])){$_SESSION['rego']['period'] = $lng['Select period'];}
			//$_SESSION['rego']['period'] = $months[$_SESSION['rego']['cur_month']].' '.$_SESSION['rego']['year_'.$lang];
			if($_SESSION['rego']['customers']){
				$customers = getCustomers($_SESSION['rego']['customers']);
			}
			//var_dump($customers);
			//$periods = getPayrollPeriods($lang);
			//$to_lock = $periods['to_lock'];
			//$to_unlock = $periods['unlock'];
			//$period = $periods['period'];
			//var_dump($teams);
			//$locked = getLockedMonth($_SESSION['rego']['cur_month']);
			//$_SESSION['rego']['locked'] = $locked;
			//$history_lock = getHistoryLock($cid);
			$checkSetup = checkSetupData($cid);
			$periods = getPayrollPeriods($lang);
			$period = $periods['period'];
			//getFormdate($cid);
			//echo($checkSetup);
		}


		// check in rego all users table for the consent 
		$sql_consent = "SELECT * FROM rego_all_users WHERE username = '".$_SESSION['rego']['username']."'";
		if($res_consent = $dbx->query($sql_consent)){
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

		$sql_consent_confirmation = "SELECT * FROM rego_cookie_confirmation WHERE id = '1'";
		if($res_consent_confirmation = $dbx->query($sql_consent_confirmation)){
			if($row_consent_confirmation = $res_consent_confirmation->fetch_assoc()){

				if($lang == 'th')
				{
					$confirmationtext  = $row_consent_confirmation['th_content'];
				}
				else
				{
					$confirmationtext  = $row_consent_confirmation['en_content'];
				}
				
				
			}
		}

		// if( $cookieConsent == '0')
		// {
		// 	//block
		// 	$groupConsent = '1';
		// }
		// else if($termsConsent == '1' || $privacyConsent == '1' )
		// {
		// 	// access
		// 	$groupConsent = '2';
		// }
		// else
		// {
		// 	$groupConsent = '3';
		// }

	}
	//var_dump($logtime); //exit;
	if(!isset($_GET['mn']) && $logger == true){$_GET['mn'] = 2;}
	if(!isset($_GET['mn'])){$_GET['mn'] = 1;}
	if($sys_settings['demo'] == 0){$_GET['mn'] = 3;}
	//var_dump($days_left); exit;

	//check login user in each company...
	if($_SESSION['rego']['cid'] != ''){
		$checksqlss = "SELECT * FROM ".$_SESSION['rego']['cid']."_users WHERE username = '".$_SESSION['rego']['username']."'";
		$resdds = $dbc->query($checksqlss);
		if($resdds->num_rows > 0){
			$rowxz = $resdds->fetch_assoc();

			$typecchk = $rowxz['type'];
			if($rowxz['emp_id'] !=''){
				$typecchkemp = 'emp';
			}

			// echo '<pre>';
			// print_r($rowxz);
			// echo '</pre>';


			
		}else{
			$typecchk = '';
			$typecchkemp = '';
		}
	}else{
		$typecchk ='';
		$typecchkemp =='';
	}

	

	// ========================RESET CONSENT VALUES ACCORDING TO THE SETTINGS FROM ADMIN PANEL ==============================//




	$sql_get_consent_setting = "SELECT * FROM rego_default_settings";
	if($res_get_consent_setting = $dbx->query($sql_get_consent_setting)){
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
		if($res_consentLatest = $dbx->query($sql_consentLatest)){
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

				$cookie_phpessid = $row_consentLatest['phpsessid'];
				$two_factor_authentication = $row_consentLatest['two_factor_authentication'];
				$authenticated = $row_consentLatest['authenticated'];
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



	// $years_valuesss = getYears();



		// echo '<pre>';
		// print_r($years_valuesss);
		// echo '</pre>';

		// die();


?>

<!DOCTYPE html>
<html lang="en-us">
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, maximum-scale=1">
		<meta name="robots" content="noindex, nofollow">
		<title><?=$www_title?></title>
	
		<link rel="icon" type="image/png" sizes="192x192" href="assets/images/192x192.png">
		<link rel="icon" type="image/png" sizes="32x32" href="assets/images/favicon-32x32.png">
		<link rel="icon" type="image/png" sizes="96x96" href="assets/images/favicon-96x96.png">
		<link rel="icon" type="image/png" sizes="16x16" href="assets/images/favicon-16x16.png">
    
		<link rel="stylesheet" href="assets/css/bootstrap.min.css?<?=time()?>">
    <link rel="stylesheet" href="assets/css/font-awesome.min.css">
    <link rel="stylesheet" href="assets/css/line-awesome.min.css">
		<link rel="stylesheet" href="assets/css/myStyle.css?<?=time()?>">
		<link rel="stylesheet" href="assets/css/navigation.css?<?=time()?>">
		<link rel="stylesheet" href="assets/css/bootstrap-datepicker.css">
		<link rel="stylesheet" href="assets/css/myBootstrap.css?<?=time()?>">
		<link rel="stylesheet" href="assets/css/basicTable.css?<?=time()?>">
		<link rel="stylesheet" href="assets/css/myForm.css?<?=time()?>">
		<link rel="stylesheet" href="assets/css/overhang.min.css?<?=time()?>">
		<link rel="stylesheet" href="assets/css/responsive.css?<?=time()?>">
		<link rel="stylesheet" href="assets/css/sumoselect-menu.css?<?=time()?>">
		
		<script src="assets/js/jquery-3.2.1.min.js"></script>
		<script src="assets/js/jquery-ui.min.js"></script>

		<script>
			//var headerCount = 2;
			var lang = <?=json_encode($lang)?>;
			//var mn = <? //=json_encode($_GET['mn'])?>;
			//var setup = <? //=json_encode($checkSetup)?>;
			var dtable_lang = <?=json_encode($dtable_lang)?>;
			var ROOT = <?=json_encode(ROOT)?>;
			//var locked = <? //=json_encode($_SESSION['rego']['locked'])?>;
			var logtime = <?=json_encode($logtime)*1000?>;
		</script>

	</head>

	<body>
	
	<? include('include/main_header.php');?>
	
	<div class="topnav-custom">
		<!-- BACK TO XRAY ADMIN /////////////////////////////////////////////////////////////////////-->
		<? if(isset($_SESSION['RGadmin']['id'])){ ?>
		<div class="btn-group"> 
			<a href="admin/index.php?mn=2" class="nav-link">&nbsp;<i class="fa fa-font fa-lg"></i>&nbsp;</a>
		</div>
		<? } ?>
		
		<div class="btn-group <? if($_GET['mn'] == 2){echo 'active';}?>"> 
			<a href="index.php?mn=2" class="home"><i class="fa fa-home"></i></a>


		</div>


		<? if(count($customers) > 1){
			//if((!isset($_SESSION['RGadmin']['id']) && $_GET['mn'] == 2) || $days_left <= 0){ 
			if((!isset($_SESSION['RGadmin']['id']) && ($_GET['mn'] == 2) || $_GET['mn'] == 3)){ ?>
			<div class="btn-group">
				<button type="button" class="dropdown-toggle" data-toggle="dropdown">
					Companies<? //=$customers[$cid]?>
				</button>
				<div class="dropdown-menu">
					<? foreach($customers as $k=>$v){ ?>
					<a class="dropdown-item changeCustomer" data-cid="<?=$k?>"><?=strtoupper($k)?> - <?=$v?></a>
					<? } ?>
				</div>
			</div>
		<? }} ?>
		
		<? include('include/main_menu_selection.php'); ?>
		
		<!--Move year to settings ???--> 
		<!--<div class="btn-group" style="float:right"> 
			<button class="dropdown-toggle" data-toggle="dropdown">
				Year <span class="caret"></span>
			</button>
				<div class="dropdown-menu">
					<a class="dropdown-item" href="#">2020</a>
					<a class="dropdown-item" href="#">2019</a>
				</div>
		</div>-->	

		<!-- USER ///////////////////////////////////////////////////////////////////////////////////////-->
		<? if($_GET['mn'] == 2){ ?>

		<div class="btn-group hide-xs" style="float:right; background:#000 !important">
			
			<button class="dropdown-toggle" data-toggle="dropdown" style="padding:0 10px 0 0">
				 <img style="height:35px; width:35px; display:inline-block; border-radius:0px; margin:-3px 10px 0 10px; border:0px solid #666" src="<?=$_SESSION['rego']['img']?>"><b><?=$_SESSION['rego']['name']?></b>
			</button>

				<? if(!isset($_SESSION['RGadmin']['id'])){?>
				<div class="dropdown-menu dropdown-menu-right" style="max-width:180px">
					<img style="width:100%; padding-bottom:2px" src="<?=$_SESSION['rego']['img']?>">
					<!--<a class="dropdown-item" xhref="myrego/index.php?mn=5" style="color:#ccc"><i class="fa fa-user"></i>&nbsp; My REGO</a>-->



					<? if(!empty($_SESSION['rego']['emp_id'])){ ?>
					<a class="dropdown-item" href="settings/index.php?mn=313"><i class="fa fa-user"></i>&nbsp; <?=$lng['My account']?></a>
					<? } ?>
					<!-- <a class="dropdown-item" data-toggle="modal" data-target="#passModal"><i class="fa fa-key"></i>&nbsp; <?=$lng['Change password']?></a> -->
					<a class="dropdown-item logout"><i class="fa fa-sign-out"></i>&nbsp; <?=$lng['Sign out']?></a>
				</div>


				<? } ?>

		</div>
			<? include('include/common_select_year.php');?>
		
		<? } ?>
		
		<? if($_GET['mn'] == 2){ ?>
			<div class="btn-group" style="float:right;"> 
				<button class="dropdown-toggle" data-toggle="dropdown">
					<? if(isset($period[$_SESSION['rego']['cur_month']])){
						echo $period[$_SESSION['rego']['cur_month']];
					}else{
						echo $lng['Select period'];
					}?>
				</button>
				<div class="dropdown-menu">
					<? foreach($period as $k=>$v){ ?>
						<a class="dropdown-item selectMonth" data-id="<?=$k?>"><?=$v?></a>
					<? } ?>
				</div>
			</div>
			
			
			
		
		<? } if($_GET['mn'] == 2 || $_GET['mn'] == 3 || $_GET['mn'] == 4){ ?>
			<? //if($days_left > 0){ ?>
			<div class="btn-group hide-xs <? if($_GET['mn'] == 3){echo 'active';}?>" style="float:right">
				<a href="index.php?mn=3"><?=$lng['Welcome']?></a>
			</div>
			<? //} ?>
			<? if($buyrego){ ?>
			<div class="btn-group hide-xs <? if($_GET['mn'] == 5){echo 'active';}?>" style="float:right">
				<a href="myrego/index.php?mn=5"><?=$BuyRego?> : <?=$days_left?> <?=$lng['days remaining']?></a>
			</div>
			<? } ?>
		<? } ?>


	</div>



	<? 
	
	if($logger){

			// check consent here if all checked then redirect to dashboard else dont allow login 

			// echo $groupConsent;

			// die();

			if(!isset($_SESSION['RGadmin']['username']))
			{
			
				if($two_factor_authentication == '1' && $authenticated != '1')
				{
					// goto authentication page  if its true in my account setting
					header('location: '.ROOT.'2fa.php');
				}
				else
				{
					// go to consent page  if authetication is fasle in my account settings 


					if($consent_process == '1')
					{
						if( $termsConsentLatest != '1' && $logged_in_statusLatest != 'yes')
						{
							header('location: '.ROOT.'terms_consent.php');
						
						}
						else if($privacyConsentLatest != '1' && $logged_in_statusLatest != 'yes')
						{
							header('location: '.ROOT.'privacy_consent.php');
						}			

						else if($cookieConsentLatest != '1' && $logged_in_statusLatest != 'yes' && $cookie_phpessid !='1')
						{
							header('location: '.ROOT.'cookie_consent.php');
						}		
						else if($cookieConsentLatest == '1' && $logged_in_statusLatest != 'yes' && $cookie_phpessid !='1' && $cookieConsentChangeLatest == '1')
						{
							header('location: '.ROOT.'cookie_consent.php');
						}		
						else if($cookieConsentLatest != '1' && $logged_in_statusLatest != 'yes' && $cookie_phpessid =='1' && $cookieConsentChangeLatest == '1')
						{
							header('location: '.ROOT.'cookie_consent.php');
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
								header('location: '.ROOT.'consent_detail.php');
							}
						}

					}



				}



			}
					


			if(!isset($_SESSION['RGadmin']['username']))
			{
				if(($days_left <= 0) || ($_SESSION['rego']['status_val'] =='0') || ($_SESSION['rego']['status_val'] =='2')  || ($_SESSION['rego']['status_val'] =='3') )
				{
					header('location: '.ROOT.'expired.php');
				}
			}


			// if($days_left <= 0){
			// 	//header('location: myrego/index.php?mn=5');
			// }
			switch($_GET['mn']){
				case 2: 
					include('dashboard.php'); 
					break;
				case 3: 
					include('welcome.php'); 
					break;
				case 4: 
					include('terms_conditions.php'); 
					break;
				case 5: 
					include('privacy_policy.php'); 
					break;
				case 460: 
					include('archive/archive_center.php'); 
					break;				
							
				case 461: 
					include('myaccount.php'); 
					break;				
			
			}
		}else{
			//unset($_SESSION['rego']['report_id']);
			header('location: login.php');
		} ?>
		
	<!-- Modal Change Password -->
	<div class="modal fade" id="passModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
		 <div class="modal-dialog" style="max-widt:450px">
			  <div class="modal-content">
					<div class="modal-header">
						 <h4 class="modal-title" id="myModalLabel"><i class="fa fa-lock"></i>&nbsp; <?=$lng['Change password']?></h4>
					</div>
					<div class="modal-body" style="padding:10px 25px 25px 25px">
					<span style="font-weight:600; color:#cc0000;" id="pass_msg"></span>
					<form id="changeUserPassword" class="sform" style="padding-top:10px;">
						 <label><?=$lng['Old password']?> <i class="man"></i></label>
						 <input name="opass" id="opass" type="password" />
						 <label><?=$lng['New password']?> <i class="man"></i></label>
						 <input name="npass" id="npass" type="password" />
						 <label><?=$lng['Repeat new password']?> <i class="man"></i></label>
						 <input name="rpass" id="rpass" type="password" />
						 <button class="btn btn-primary" style="margin-top:15px" type="submit"><i class="fa fa-save"></i> <?=$lng['Change password']?></button>
						<button style="float:right;margin-top:15px" type="button" class="btn btn-primary" data-dismiss="modal"><i class="fa fa-times"></i>&nbsp; <?=$lng['Cancel']?></button>
						<div class="clear"></div>
					</form>
					</div>
					<div class="clear"></div>
			  </div>
		 </div>
	</div>	

	<!-- Modal Consent Information -->
	<div class="modal fade" id="consentModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
		 <div class="modal-dialog" style="max-width:1795px">
			  <div class="modal-content" style="min-height: 800px;">
					<div class="modal-header">
						 <h4 class="modal-title" id="myModalLabel"><i class="fa fa-lock"></i>&nbsp; <?=$lng['Consent Information']?></h4>
					</div>
					<div class="modal-body" style="padding:25px 25px 25px 25px">
					<div class="page-wrap d-flex flex-row ">
					    <div class="container">
					        <div class="row ">
					            	<div class="d-flex  container mt-5">
									    <div class="row" style="width: 100%;">

									        
									                    <div style="float:left;overflow-y: auto;height: 387px;margin: 0 auto;">
									                    	<h3 style="padding: 36px;line-height: 28px;">
									                    		<!-- <?php echo $confirmationtext;?> -->
									                    		<p> You have submitted your consent for the followings:- </p>
									                    		<ul>
									                    			<li>
										                    			
										                    				Terms & Condition
										                    				<span style="margin-left: 17px;">:</span> 

										                    				<?php 
										                    					if($termsConsentLatest == '1'){ ?>
										                    						<button  style="width: 300px;" class="btn btn-primary btn-lg" type="button" ><span style="font-weight: 600;"> <?php echo $termsAgreed ?></span> on <span style="font-weight: 600;"><?php echo $terms_consent_date;?></span>
										                    						</button>

										                    					<?php }else{?>

										                    						<button  class="btn btn-danger btn-lg" type="button" ><span style="font-weight: 600;"> <?php echo $termsAgreed ?></span> on <span style="font-weight: 600;"><?php echo $terms_consent_date;?></span>
										                    						</button>	

										                    				<?php } ?>
										                    				
										                    		</li>

									                    			<li style="margin-top: 5px;">
									                    				Privacy Policy 
									                    				<span style="margin-left: 73px;">:</span>
								                    					<?php 
									                    					if($privacyConsentLatest == '1'){ ?>
									                    						<button  style="width: 300px;" class="btn btn-primary btn-lg" type="button" ><span style="font-weight: 600;"> <?php echo $privacyAgreed ?></span> on <span style="font-weight: 600;"><?php echo $privacy_consent_date;?></span>
									                    						</button>

									                    					<?php }else{?>

									                    						<button  class="btn btn-danger btn-lg" type="button" > <span style="font-weight: 600;"> <?php echo $privacyAgreed ?></span> on <span style="font-weight: 600;"><?php echo $privacy_consent_date;?></span>
									                    						</button>	

									                    				<?php } ?>

									                    			</li>

									                    			<li style="margin-top: 5px;">
									                    				Cookie Consent 
									                    				<span style="margin-left: 50px;">:</span> 
									                    				<?php 
									                    					if($cookieConsentLatest == '1'){ ?>
									                    						<button style="width: 300px;" class="btn btn-primary btn-lg" type="button" ><span style="font-weight: 600;"> <?php echo $cookieAgreed ?></span> on <span style="font-weight: 600;"><?php echo $cookie_consent_date;?></span>
									                    						</button>

									                    					<?php }else{?>

									                    						<button  class="btn btn-danger btn-lg" type="button" > <span style="font-weight: 600;"> <?php echo $cookieAgreed ?></span> on <span style="font-weight: 600;"><?php echo $cookie_consent_date;?></span>
									                    						</button>	

									                    				<?php } ?>
									                    			</li>
									                    		</ul>
									                    	</h3>	
															
															<br></div>


				                        				<div style="margin:0 auto;">
											                <h3 style="padding: 36px;line-height: 28px;text-align: center;">

											                You will be redirected to dashboard in <span id="time_count">3</span><span> seconds</span>
											                </h3>

				                        				</div>
									    </div>
									</div>

					     
					        </div>
					    </div>
					</div>
					</div>
					<div class="clear"></div>
			  </div>
		 </div>
	</div>

	
	<? include('include/modal_relog.php')?>
	<script type="text/javascript">

		// php variables to show the consent chekbox values 

		var showConsent = '<?php echo $showConsent?>';
		$( document ).ready(function() {


		var is_modal_show = sessionStorage.getItem('alreadyShow');



			if(showConsent == '1')
			{

				if(is_modal_show != 'alredy shown'){
					// $('#consentModal').modal('show');
					// window.location.href = 'consent_detail.php';
				  	sessionStorage.setItem('alreadyShow','alredy shown');
				}


			}
			var counter = 3;
			var interval = setInterval(function() {
			    counter--;
			    // Display 'counter' wherever you want to display it.
			    if (counter <= 0) 
			    {
			     	clearInterval(interval);
					// $('#consentModal').modal('hide');
			        return;
			    }
			    else
			    {
			    	$('#time_count').text(counter);
			    }
			}, 1000);



		});
	</script>
	<script src="assets/js/popper.min.js"></script>
	<script src="assets/js/bootstrap.min.js"></script>
	<script src="assets/js/bootstrap-datepicker.min.js"></script>
	<script src="assets/js/bootstrap-confirmation.js"></script>
	<script src="assets/js/jquery.mask.js"></script>	
	<script src="assets/js/overhang.min.js"></script>
	<script src="assets/js/jquery.sumoselect-menu.js"></script>
	<script src="assets/js/rego.js?<?=time()?>"></script>
	
	<? include('include/common_script.php')?>
	<? include('include/main_menu_script.php')?>
	
	
	
	
	
	
	
	
	</body>
</html>













