<?php

	if(session_id()==''){session_start();} 
	ob_start();
	include('../dbconnect/db_connect.php');
	include(DIR.'files/functions.php');
	include(DIR.'files/payroll_functions.php');
	include(DIR.'files/arrays_'.$lang.'.php');

	if(isset($_GET['y'])){
		$_SESSION['rego']['cur_year'] = $_GET['y'];
	}
	
	$logger = false;
	$logtime = 3600;
	$data = array();
	if(isset($_SESSION['rego']['emp_id']) && !empty($_SESSION['rego']['emp_id'])){
		//$cid = $_SESSION['rego']['cid'];
		//$logtime = getLogtime($cid);
		if(time() - $_SESSION['rego']['timestamp'] > $logtime) {
			$_SESSION['rego']['timestamp'] = 0;
			$logger = false; 
		}else{
			$_SESSION['rego']['timestamp'] = time();
			$_SESSION['rego']['payroll_dbase'] = $_SESSION['rego']['cid'].'_payroll_'.$_SESSION['rego']['cur_year'];
			$_SESSION['rego']['emp_dbase'] = $cid.'_employees';
			$logger = true;
			$res = $dbc->query("SELECT * FROM ".$cid."_employees WHERE emp_id = '".$_SESSION['rego']['emp_id']."'");
			$data = $res->fetch_assoc();
			$years = getYears($cid);
			//var_dump($years);
			$_SESSION['rego']['gov_entity'] = $data['entity'];
			$_SESSION['rego']['emp_group'] = $data['emp_group'];
		}
	}


	if($_SESSION['rego']['customers']){
		$customers = getCustomers($_SESSION['rego']['customers']);
	}
	//$years = array(2019=>2019);//$years[$lang];
	//var_dump($edata);exit;
	
	if(!isset($_GET['mn']) && $logger == true){$_GET['mn'] = 900;}
	//if(!isset($_GET['mn'])){$_GET['mn'] = 1;}



		// ========================RESET CONSENT VALUES ACCORDING TO THE SETTINGS FROM ADMIN PANEL ==============================//



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
				$two_factor_authentication = $row_consentLatest['two_factor_authentication'];
				$authenticated = $row_consentLatest['authenticated'];
				$e_login_cookies = $row_consentLatest['e_login_cookies'];

				$cookie_phpessid = $row_consentLatest['phpsessid'];



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



	
?>

<!DOCTYPE html>
<html lang="en-us">
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">
		<meta name="robots" content="noindex, nofollow">
		<title><?=$www_title?></title>
	
		<link rel="icon" type="image/png" sizes="192x192" href="../assets/images/192x192.png">
		<link rel="icon" type="image/png" sizes="32x32" href="../assets/images/favicon-32x32.png">
		<link rel="icon" type="image/png" sizes="96x96" href="../assets/images/favicon-96x96.png">
		<link rel="icon" type="image/png" sizes="16x16" href="../assets/images/favicon-16x16.png">
		
		<link rel="stylesheet" href="../assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="../assets/css/font-awesome.min.css">
    <link rel="stylesheet" href="../assets/css/line-awesome.min.css">
    <link rel="stylesheet" href="../assets/css/myStyle.css?<?=time()?>">
		<link rel="stylesheet" href="../assets/css/navigation.css?<?=time()?>">
		<link rel="stylesheet" href="../assets/css/bootstrap-datepicker.css?<?=time()?>" />
		<link rel="stylesheet" href="../assets/css/myBootstrap.css?<?=time()?>">
		<link rel="stylesheet" href="../assets/css/basicTable.css?<?=time()?>">
		<link rel="stylesheet" href="../assets/css/myForm.css?<?=time()?>">
		<link rel="stylesheet" href="../assets/css/dataTables.bootstrap4.min.css?<?=time()?>">
		<link rel="stylesheet" href="../assets/css/myDatatables.css?<?=time()?>">
		<link rel="stylesheet" href="../assets/css/overhang.min.css?<?=time()?>">
		<!--<link rel="stylesheet" href="../assets/css/responsive.css?<?=time()?>">-->
		<link rel="stylesheet" href="../assets/css/jquery-clockpicker.min.css">
		
		<script src="../assets/js/jquery-3.2.1.min.js"></script>
		<script src="../assets/js/jquery-ui.min.js"></script>
	
		<script>
			//var headerCount = 2;
			var lang = <?=json_encode($lang)?>;
			var dtable_lang = <?=json_encode($dtable_lang)?>;
			var ROOT = <?=json_encode(ROOT)?>;
			var logtime = <?=json_encode($logtime)*1000?>;
		</script>
		
	</head>

<body>
	
	<? if($comp_settings['txt_color'] == 'blue'){ ?>
	<link rel="stylesheet" href="<?=ROOT?>assets/css/pkf.css?<?=time()?>">
	<? } ?>
	<div class="header">
		<table border="0">
			<tr>
				<td class="header-logo" style="padding-right:15px">
					<img src="<?=ROOT.$compinfo['logofile'].'?'.time()?>" />
				</td>
				<td class="header-client" style="width:80%">
					<? if(isset($_SESSION['rego']['cid'])){ ?>
							<?=$compinfo[$lang.'_compname']?>&nbsp;-&nbsp;<?=$rego?> 
					<? } ?>
				</td>
				<td class="header-date">
					<?=$_SESSION['rego']['cur_date']?>
				</td>
				<? if($lang=='en'){ ?>
				<td>
					<a data-lng="th" class="langbutton <? if($lang=='th'){echo 'activ';} ?>"><img src="<?=ROOT?>images/flag_th.png"></a>
				</td>
				<? }else{ ?>
				<td>
					<a data-lng="en" class="langbutton <? if($lang=='en'){echo 'activ';} ?>"><img src="<?=ROOT?>images/flag_en.png"></a>
				</td>
				<? } ?>
				<td style="padding:0 10px">
					<button class="btn btn-logout logout"><i class="fa fa-power-off"></i></button>
				</td>
			</tr>
		</table>
	</div>
	
	<div class="topnav-custom">
		<? if($logger){ ?>
			<? if($_GET['mn'] >= 900){ ?>
				<div class="btn-group <? if($_GET['mn']==900){echo 'active';}?>">
					<a href="index.php?mn=900" class="home"><i class="fa fa-home"></i></a>
				</div>
			<? } ?>

			<? if(count($customers) > 1){ ?>
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
			<? } ?>
				

			<div style="float:right" class="btn-group">
				<a class="dropdown-item" href="index.php?mn=920"><i class="fa fa-user"></i>&nbsp; <?=$lng['My account']?></a>
			</div>
			<?php if($_GET['mn'] != '920'){?>
			<? if($_GET['mn'] > 900 && $_GET['mn'] < 950){ ?>
				<div class="btn-group <? if($_GET['mn']==910){echo 'active';}?>">
					<a href="index.php?mn=910"><?=$lng['Personal data']?></a>
				</div>
				<div class="btn-group <? if($_GET['mn']==911){echo 'active';}?>">
					<a href="index.php?mn=911"><?=$lng['Payslips']?></a>
				</div>
				<div class="btn-group <? if($_GET['mn']==912){echo 'active';}?>">
					<a href="index.php?mn=912"><?=$lng['Year overview']?></a>
				</div>
				<div class="btn-group <? if($_GET['mn']==913){echo 'active';}?>">
					<a href="index.php?mn=913"><?=$lng['Leave application']?></a>
				</div>

			<? } if(count($years) > 1){ ?>	
				<div style="float:right" class="btn-group">
					<button data-toggle="dropdown">
						<?=$lng['Year'].' '.$_SESSION['rego']['year_'.$lang]?> <span class="caret"></span>
					</button>
					<ul class="dropdown-menu">
						<? foreach($years[$lang] as $k=>$v){ ?>
						<li><a href="index.php?mn=<?=$_GET['mn']?>&y=<?=$k?>"><?=$v?></a></li>
						<? } ?>
					</ul>
				</div>
			<? }else{ ?>
				<div style="float:right" class="btn-group">
					<a><?=$lng['Year'].' '.$_SESSION['rego']['year_'.$lang]?></a>

				</div>
			<? } ?>
			<? } ?>

		<? } ?>
		
	</div>
		
	<? if($logger){

			if(!isset($_SESSION['RGadmin']['username']))
			{
				// if process on 
					
				if($two_factor_authentication == '1' && $authenticated != '1')
				{
					// goto authentication page  if its true in my account setting
					header('location: '.ROOT.'2fa.php');
				}
				else
				{

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
						

			switch($_GET['mn']){
				case 900: 
					include('emp_dashboard.php'); break;
				case 910: 
					include('emp_personal_data.php'); break;
				case 911: 
					include('emp_payslips.php'); break;
				case 912: 
					include('emp_year_overview.php'); break;
				case 913: 
					include('emp_leave_application.php'); break;
				/*case 914: 
					include('emp_ot_application.php'); break;
				case 916: 
					include('emp_ot_application2.php'); break;
				case 915: 
					include('emp_work_schedule.php'); break;*/	

				case 920: 
					include('consent_settings.php'); break;
			}
		}else{
			header('location: ../login.php');
		}
	?>
	
	<!-- Modal Contactform -->
	<div class="modal fade" id="modalContactForm" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
		 <div class="modal-dialog" style="width:600px">
			  <div class="modal-content">
					<div class="modal-header">
						 <h4 class="modal-title" id="myModalLabel"><i class="fa fa-list-ul"></i>&nbsp; <?=$lng['Contact']?></h4>
					</div>
					<div class="modal-body" style="padding:10px 40px 30px 40px">
					<span style="font-weight:600; color:#cc0000;" id="conMess"></span>
					<form style="padding-top:5px" id="contactForm" class="sform" enctype="multipart/form-data">
						<input name="name" type="hidden" value="<?=$_SESSION['rego']['name']?>" />
						<input name="emp_id" type="hidden" value="<?=$_SESSION['rego']['emp_id']?>" />
						<input name="email" type="hidden" value="<?=$_SESSION['rego']['username']?>" />
						<input name="phone" type="hidden" value="<?=$_SESSION['rego']['phone']?>" />
						<input style="visibility:hidden; height:0;" id="contactAttach" type="file" name="contactAttach" />

						<label><?=$lng['Subject']?> <i class="man"></i></label>
						<input name="subject" id="subject" type="text" />
						
						<label><?=$lng['Message Question']?> <i class="man"></i></label>
						<textarea name="comment" id="comment" rows="4"></textarea>
						
						<div style="height:3px"></div>
						<div id="contactMsg" style="color:#c00; padding:2px 0; font-weight:600; display:none"></div>
						<div style="height:7px"></div>
						
						<table><tr><td>
						<button onClick="$('#contactAttach').click()" class="btn btn-default btn-xs" style="display:block; margin:0" type="button"><?=$lng['Attachement']?></button></td><td style="padding-left:10px"><span id="attachMsg"><?=$lng['No file selected']?></span></td></tr></table>

						<div style="height:15px"></div>
						
						<button id="contactBtn" style="float:left" type="submit" class="btn btn-primary btn-sm"><i class="fa fa-paper-plane-o"></i>&nbsp; <?=$lng['Send']?></button>
						
						<button style="float:right" type="button" class="btn btn-primary btn-sm" data-dismiss="modal"><i class="fa fa-times"></i>&nbsp; <?=$lng['Cancel']?></button>
						
						<div class="clear"></div>
					</form>
					</div>
			  </div>
		 </div>
	</div>

	<!-- Modal Change Password -->
	<div class="modal fade" id="passModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
		 <div class="modal-dialog" style="width:450px">
			  <div class="modal-content">
					<div class="modal-header">
						 <h4 class="modal-title" id="myModalLabel"><i class="fa fa-lock"></i>&nbsp; <?=$lng['Change password']?></h4>
					</div>
					<div class="modal-body" style="padding:10px 25px 25px 25px">
						<form id="changePassForm" class="sform" style="padding-top:10px;">
							 
							 <label><?=$lng['Old password']?> <i class="man"></i></label>
							 <input name="opass" id="opass" type="text" />
							 
							 <label><?=$lng['New password']?> <i class="man"></i></label>
							 <input name="npass" id="npass" type="password" />
							 
							 <label><?=$lng['Repeat new password']?> <i class="man"></i></label>
							 <input name="rpass" id="rpass" type="password" />
							
							<div style="height:2px"></div>
							<div id="passMsg" style="color:#c00; padding:2px 0; font-weight:600; display:none"></div>
							<div style="height:8px"></div>
							 
							<button class="btn btn-primary btn-sm" type="submit"><i class="fa fa-save"></i> <?=$lng['Change password']?></button>
							
							<button style="float:right" type="button" class="btn btn-primary btn-sm" data-dismiss="modal"><i class="fa fa-times"></i>&nbsp; <?=$lng['Cancel']?></button>
							<div class="clear"></div>
							
						</form>
					</div>
					<div class="clear"></div>
			  </div>
		 </div>
	</div>

	<? include('../include/modal_relog.php')?>

	<script src="../assets/js/popper.min.js"></script>
	<script src="../assets/js/bootstrap.min.js"></script>
	<script src="../assets/js/jquery.dataTables.min.js"></script>
	<script src="../assets/js/dataTables.bootstrap4.min.js"></script>
	<script src="../assets/js/bootstrap-datepicker.min.js"></script>
	<script src="../assets/js/bootstrap-datepicker.th.js"></script>
	<script src="../assets/js/bootstrap-confirmation.js"></script>
	<script src="../assets/js/jquery.numberfield.js"></script>	
	<script src="../assets/js/jquery.mask.js"></script>	
	<script src="../assets/js/overhang.min.js?<?=time()?>"></script>
	<script src="../assets/js/rego.js?<?=time()?>"></script>
	
	<? include('../include/common_script.php')?>
	
	
<script type="text/javascript">
	
	$(document).ready(function() {
		
		function readAttURL(input) {
		  if (input.files && input.files[0]) {
				var reader = new FileReader();
				reader.onload = function (e) {
					var fileExtension = ['pdf', 'doc', 'docx', 'txt', 'jpg', 'jpeg', 'png'];
					var ext = input.files[0].name.split('.').pop();
					if ($.inArray(ext.toLowerCase(), fileExtension) == -1) {
						alert('Use only '+fileExtension+' files')
						$('#attachMsg').html('<?=$lng['No file selected']?>');
					}else{				
						$('#attachMsg').html(input.files[0].name);
					}
				}
				reader.readAsDataURL(input.files[0]);
		  }
		};
		/*setTimeout(function(){
			$('#modalExpired').modal('toggle');
		},logtime);	*/
		$('#contactAttach').on('change', function(){ 
			readAttURL(this);
		});
		$("#contactForm").submit(function(e){
			e.preventDefault();
			$("#contactBtn").prop('disabled', true);
			$("#contactBtn i").removeClass('fa-paper-plane').addClass('fa-refresh fa-spin');
			var formData = new FormData($(this)[0]);
			$.ajax({
				url: ROOT+"ajax/send_contact_mail.php",
				data: formData,
				type: "POST", 
				cache: false,
				processData:false,
				contentType: false,
				success: function(response){
					//$('#dump').html(response)
					if(response=='success'){
						$('#contactMsg').html('<?=$lng['Mail send successfully']?>').fadeIn(400);
						setTimeout(function(){
							$('#modalContactForm').modal('toggle');
						},2000);
					}else if(response=='empty'){
						$('#contactMsg').html('<?=$lng['Please fill in required fields']?>').fadeIn(400);
						$("#contactBtn").prop('disabled', false);
					}else{
						$('#contactMsg').html('<?=$lng['Error']?> : ' + response).fadeIn(400);
						$("#contactBtn").prop('disabled', false);
					}
					$("#contactBtn i").removeClass('fa-refresh fa-spin').addClass('fa-paper-plane');
				},
				error:function (xhr, ajaxOptions, thrownError){
					$('#contactMsg').html('<?=$lng['Error']?> : ' + thrownError).fadeIn(400);
					$("#contactBtn i").removeClass('fa-refresh fa-spin').addClass('fa-paper-plane');
				}
			});
		});
		$('#modalContactForm').on('hidden.bs.modal', function () {
			$(this).find('form').trigger('reset');
			$("#contactMsg").html('');
			$("#contactAttach").val('');
		});
		$("#changePassForm").submit(function(e){
			e.preventDefault();
			var formData = $(this).serialize();
			//alert(formData)
			$.ajax({
				url: ROOT+"ajax/change_employee_password.php",
				data: formData,
				success: function(response){
					//$('#dump3').html(response)
					if(response=='success'){
						$('#passMsg').html('<?=$lng['Password changed successfuly']?>').fadeIn(400);
						setTimeout(function(){
							$('#passModal').modal('toggle');
						},2000);
					}else if(response=='empty'){
						$('#passMsg').html('<?=$lng['Please fill in required fields']?>').fadeIn(400);
					}else if(response=='short'){
						$('#passMsg').html('<?=$lng['New password to short min 8 characters']?>').fadeIn(400);
					}else if(response=='same'){
						$('#passMsg').html('<?=$lng['New passwords are not the same']?>').fadeIn(400);
					}else if(response=='old'){
						$('#passMsg').html('<?=$lng['Old Password is wrong']?>').fadeIn(400);
					}else{
						$('#passMsg').html('<?=$lng['Error']?> : '+response).fadeIn(400);
					}
				},
				error:function (xhr, ajaxOptions, thrownError){
					$('#passMsg').html('<?=$lng['Error']?> : ' + thrownError).fadeIn(400);
				}
			});
		});
		
	});
		
</script>
	
</body>
</html>








