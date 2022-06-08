<?
	if(session_id()==''){session_start();}
	ob_start();
	include('files/admin_functions.php');
	include('dbconnect/db_connect.php');
	include(DIR.'files/arrays_'.$lang.'.php');

	$logtime = 84600;
	$logger = false;
	$comp_count = 0;
	$emp_count = 0;

	$active_emps = 0;
    $inactive_emps = 0;
	$exceeded = 0;

	if(isset($_SESSION['RGadmin']['id']) && !empty($_SESSION['RGadmin']['id'])){
		if(time() - $_SESSION['RGadmin']['timestamp'] > $logtime) {
			$_SESSION['RGadmin']['timestamp'] = 0;
			$logger = false; 
		}else{
			$_SESSION['RGadmin']['timestamp'] = time();
			$logger = true;
		}
		$customers = getCustomers();
		$cid = '';
		//if(isset($_SESSION['SDadmin'])){$cid = strtolower($_SESSION['RGadmin']);}
		if(!isset($_SESSION['RGadmin']['cur_year'])){$_SESSION['RGadmin']['cur_year'] = date('Y');}
		//if(!isset($_SESSION['RGadmin']['cur_month'])){$_SESSION['RGadmin']['cur_month'] = date('m');}
		if(!isset($_SESSION['RGadmin']['year_en'])){$_SESSION['RGadmin']['year_en'] = date('Y');}
		if(!isset($_SESSION['RGadmin']['year_th'])){$_SESSION['RGadmin']['year_th'] = (date('Y')+543);}
		$_SESSION['RGadmin']['cur_date'] = date('l d F ').$_SESSION['RGadmin']['year_'.$lang];
	
		$all_customers = array();
		$sql = "SELECT * FROM rego_customers";
		if($res = $dba->query($sql)){
			while($row = $res->fetch_assoc()){
				$all_customers[$row['clientID']] = $row[$lang.'_compname'];
			}
		}
		/*$comp_count = count($all_customers);
		foreach($all_customers as $k=>$v){
			$dbc = @new mysqli($my_database,$my_username,$my_password);
			$dbc = @new mysqli($my_database,$my_username,$my_password,$prefix.$k);
			mysqli_set_charset($dbc,"utf8");
			if($res = $dbc->query("SELECT emp_id FROM ".$k."_employees")){
				$emp_count += $res->num_rows;
			}else{
				echo mysqli_error($dbc);
			}
		}*/
		//var_dump($emp_count);
		//var_dump($all_customers);
		//exit;	


		//new code...
		$sql11 = "SELECT max_employees FROM rego_company_settings";
        if($res11 = $dba->query($sql11)){
            $row11 = $res11->fetch_assoc();
            $max_employees = $row11['max_employees'];
        }

        $comp_count = count($all_customers);
        foreach($all_customers as $k=>$v){

            $dbc = @new mysqli($my_database,$my_username,$my_password);
            $dbc = @new mysqli($my_database,$my_username,$my_password,$prefix.$k);
            mysqli_set_charset($dbc,"utf8");
            if($res = $dbc->query("SELECT emp_status FROM ".$k."_employees")){

            	$emp_count += $res->num_rows;
                while($row = $res->fetch_assoc()){

                    if($row['emp_status'] == 1){
                        $active_emps ++;
                    }else{
                        $inactive_emps ++;
                    }
                }
            }else{
                echo mysqli_error($dbc);
            }
        }

        if($active_emps >= $max_employees){
            $exceeded = 1;
        }
	}
	if(empty($compinfo['complogo'])){
		$compinfo['complogo'] = ROOT.'images/rego_logo.png';
	}
	
	// LOGOUT CUSTOMER ///////////////////////////////////////////////////////////////////
	unset($_SESSION['rego']);

	if(!isset($_GET['mn']) && $logger == true){$_GET['mn'] = 2;}
	if(!isset($_GET['mn'])){$_GET['mn'] = 1;}




		$sql_get_consent_setting1 = "SELECT * FROM rego_default_settings";
		if($res_get_consent_setting1 = $dba->query($sql_get_consent_setting1)){
			if($row_get_consent_setting1 = $res_get_consent_setting1->fetch_assoc()){

			$consent_process = $row_get_consent_setting1['consent_process'];
			

			}
		}





			// check in rego all users table for the consent 
		$sql_consent = "SELECT * FROM rego_users WHERE username = '".$_SESSION['RGadmin']['username']."'";
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


		// echo '<pre>';
		// print_r($row_consent);
		// echo '</pre>';

		// die();

			$sql_get_consent_setting = "SELECT * FROM rego_default_settings";
			if($res_get_consent_setting = $dba->query($sql_get_consent_setting)){
				if($row_get_consent_setting = $res_get_consent_setting->fetch_assoc()){

				$terms_renewal = $row_get_consent_setting['terms_renewal'];
				$privacy_renewal = $row_get_consent_setting['privacy_renewal'];
				$cookie_renewal = $row_get_consent_setting['cookie_renewal'];
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
				everdayConsentCheckTerms($terms_consent_date,'24',$_SESSION['RGadmin']['username'],$terms_consent_change,'1','1'); // checking for terms for everday reset 
			}

		}
		else if($terms_renewal == '2')
		{
			// 1 month with change 
			if($termsConsent == '1') // check if already agreed then ask again because if didn't agreed yet it will ask automatically 
			{
				everdayConsentCheckTerms($terms_consent_date,'720',$_SESSION['RGadmin']['username'],$terms_consent_change,'30','2'); // checking for terms for everday reset 
			}
		}	
		else if($terms_renewal == '3')
		{
			// 1 month with change 
			if($termsConsent == '1') // check if already agreed then ask again because if didn't agreed yet it will ask automatically 
			{
				everdayConsentCheckTerms($terms_consent_date,'2160',$_SESSION['RGadmin']['username'],$terms_consent_change,'90','3'); // checking for terms for everday reset 
			}
		}	
		else if($terms_renewal == '4')
		{
			// 1 month with change 
			if($termsConsent == '1') // check if already agreed then ask again because if didn't agreed yet it will ask automatically 
			{
				everdayConsentCheckTerms($terms_consent_date,'4320',$_SESSION['RGadmin']['username'],$terms_consent_change,'180','4'); // checking for terms for everday reset 
			}
		}	
		else if($terms_renewal == '5')
		{
			// 1 month with change 
			if($termsConsent == '1') // check if already agreed then ask again because if didn't agreed yet it will ask automatically 
			{
				everdayConsentCheckTerms($terms_consent_date,'0',$_SESSION['RGadmin']['username'],$terms_consent_change,'0','5'); // checking for terms for everday reset 
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
				everdayConsentCheckPrivacy($privacy_consent_date,'24',$_SESSION['RGadmin']['username'],$privacy_consent_change,'1','1'); // checking for terms for everday reset 
			}

		}
		else if($privacy_renewal == '2')
		{
			// 1 month with change 
			if($privacyConsent == '1') // check if already agreed then ask again because if didn't agreed yet it will ask automatically 
			{
				everdayConsentCheckPrivacy($privacy_consent_date,'720',$_SESSION['RGadmin']['username'],$privacy_consent_change,'30','2'); // checking for terms for everday reset 
			}
		}	
		else if($privacy_renewal == '3')
		{
			// 1 month with change 
			if($privacyConsent == '1') // check if already agreed then ask again because if didn't agreed yet it will ask automatically 
			{
				everdayConsentCheckPrivacy($privacy_consent_date,'2160',$_SESSION['RGadmin']['username'],$privacy_consent_change,'90','3'); // checking for terms for everday reset 
			}
		}	
		else if($privacy_renewal == '4')
		{
			// 1 month with change 
			if($privacyConsent == '1') // check if already agreed then ask again because if didn't agreed yet it will ask automatically 
			{
				everdayConsentCheckPrivacy($privacy_consent_date,'4320',$_SESSION['RGadmin']['username'],$privacy_consent_change,'180','4'); // checking for terms for everday reset 
			}
		}	
		else if($privacy_renewal == '5')
		{
			// 1 month with change 
			if($privacyConsent == '1') // check if already agreed then ask again because if didn't agreed yet it will ask automatically 
			{
				everdayConsentCheckPrivacy($privacy_consent_date,'0',$_SESSION['RGadmin']['username'],$privacy_consent_change,'0','5'); // checking for terms for everday reset 
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
				everdayConsentCheckCookie($cookie_consent_date,'24',$_SESSION['RGadmin']['username'],$cookie_consent_change,'1','1'); // checking for terms for everday reset 
			}

		}
		else if($cookie_renewal == '2')
		{
			// 1 month with change 
			if($cookieConsent == '1') // check if already agreed then ask again because if didn't agreed yet it will ask automatically 
			{
				everdayConsentCheckCookie($cookie_consent_date,'720',$_SESSION['RGadmin']['username'],$cookie_consent_change,'30','2'); // checking for terms for everday reset 
			}
		}	
		else if($cookie_renewal == '3')
		{
			// 1 month with change 
			if($cookieConsent == '1') // check if already agreed then ask again because if didn't agreed yet it will ask automatically 
			{
				everdayConsentCheckCookie($cookie_consent_date,'2160',$_SESSION['RGadmin']['username'],$cookie_consent_change,'90','3'); // checking for terms for everday reset 
			}
		}	
		else if($cookie_renewal == '4')
		{
			// 1 month with change 
			if($cookieConsent == '1') // check if already agreed then ask again because if didn't agreed yet it will ask automatically 
			{
				everdayConsentCheckCookie($cookie_consent_date,'4320',$_SESSION['RGadmin']['username'],$cookie_consent_change,'180','4'); // checking for terms for everday reset 
			}
		}	
		else if($cookie_renewal == '5')
		{
			// 1 month with change 
			if($cookieConsent == '1') // check if already agreed then ask again because if didn't agreed yet it will ask automatically 
			{
				everdayConsentCheckCookie($cookie_consent_date,'0',$_SESSION['RGadmin']['username'],$cookie_consent_change,'0','5'); // checking for terms for everday reset 
			}
		}
		// ======================= Cookie renewal   ============================//

 

	// ======================= UPDATED CONSENT DATA ============================// 
	
		// check in rego all users table for the consent 
		$sql_consentLatest = "SELECT * FROM rego_users WHERE username = '".$_SESSION['RGadmin']['username']."'";
		if($res_consentLatest = $dba->query($sql_consentLatest)){
			if($row_consentLatest = $res_consentLatest->fetch_assoc()){
				$privacyConsentLatest = $row_consentLatest['privacy_consent']; // 0
				$privacyConsentChangeLatest = $row_consentLatest['privacy_consent_change']; // 0
				$termsConsentLatest = $row_consentLatest['terms_consent']; // 0
				$terms_consent_dateLatest = $row_consentLatest['terms_consent_date']; // 0
				$terms_consent_changeLatest = $row_consentLatest['terms_consent_change']; // 0
				$cookieConsentLatest = $row_consentLatest['cookie_consent']; // 0
				$cookieConsentChangeLatest = $row_consentLatest['cookie_consent_change']; // 0
				$logged_in_statusLatest = $row_consentLatest['logged_in_status'];
				$showConsentLatest = $row_consentLatest['showConsent'];
				$two_factor_authentication = $row_consentLatest['two_factor_authentication'];
				$authenticated = $row_consentLatest['authenticated'];
				$e_login_cookies = $row_consentLatest['e_login_cookies'];


			}
		}	

	// ======================= UPDATED CONSENT DATA ============================// 


	// echo '<pre>';
	// print_r($terms_consent_changeLatest);
	// echo '</pre>';
	// die();



?>

<!DOCTYPE html>
<html lang="en">
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
		<link rel="stylesheet" href="../assets/css/dataTables.bootstrap4.min.css?<?=time()?>">
		<link rel="stylesheet" href="../assets/css/myDatatables.css?<?=time()?>">
		<link rel="stylesheet" href="../assets/css/navigation.css?<?=time()?>">
		<link rel="stylesheet" href="../assets/css/bootstrap-datepicker.css?<?=time()?>" />
		<link rel="stylesheet" href="../assets/css/myBootstrap.css?<?=time()?>">
		<link rel="stylesheet" href="../assets/css/basicTable.css?<?=time()?>">
		<link rel="stylesheet" href="../assets/css/myForm.css?<?=time()?>">
		<link rel="stylesheet" href="../assets/css/overhang.min.css?<?=time()?>">
		<link rel="stylesheet" href="../assets/css/sumoselect.css?<?=time()?>">
		<link rel="stylesheet" href="../assets/css/responsive.css?<?=time()?>">
		<link rel="stylesheet" href="../assets/css/autocomplete.css?<?=time()?>">



		<link rel="stylesheet" href="../assets/css/sumoselect.css?<?=time()?>">
		<link rel="stylesheet" href="../assets/css/autocomplete.css?<?=time()?>">
		<link rel="stylesheet" href="../assets/css/main.css?<?=time()?>">




		<script src="../assets/js/jquery-3.2.1.min.js"></script>
		<script src="../assets/js/jquery-ui.min.js"></script>
		<script src="../assets/js/moment.min.js"></script>
		<script src='../assets/js/moment-duration-format.min.js'></script>




		<script>
			//var headerCount = 2;
			var lang = <?=json_encode($lang)?>;
			var dtable_lang = <?=json_encode($dtable_lang)?>;
			var ROOT = <?=json_encode(ROOT)?>;
			var AROOT = <?=json_encode(AROOT)?>;
			var DIR = <?=json_encode(DIR)?>;
		</script>
		
	<style>
		.exelbox {
			width:200px;
			float:left;
			padding:15px;
		}
		.exelbox .inner {
			width:100%;
			border:0px red dotted;
			padding:10px;
			background:#fff;
			box-shadow:0px 0px 5px rgba(0,0,0,0.2);
			cursor:default;
		}
		.exelbox .inner:hover {
			xbox-shadow: 1px 1px 3px rgba(0,0,0,0.2);
		}
		.exelbox .inner p {
			padding:5px 0 0 0;
			font-size:13px;
			font-weight:bold;
			text-align:right;
		}
		.exelbox .inner img {
			width:100%;
		}
		.aTable {  
			width:100%;
			table-layout:auto;
			border-collapse:collapse;
			border:none;
			font-size:13px;
			color:#000;
			white-space:nowrap;
		}
		.aTable thead tr {
			border-bottom: 1px #ccc solid;
			background:#eee;
		}
		.aTable tfoot tr {
			border-bottom: 1px #eee solid;
			background:#fff;
		}
		.aTable thead tr th {
			text-align:left;
			width:5%;
			color:#005588;
			font-weight:600;
			vertical-align:middle;
			padding:4px 10px;
			border-right:1px #fff solid;
		}
		.aTable thead tr th:last-child {
			border-right:0;
		}
		.aTable tfoot tr td:last-child {
			border-right:0;
		}
		.aTable thead tr.tac th {
			text-align:center;
		}
		.aTable thead tr.tar th {
			text-align:right;
		}
		.aTable tbody tr {
			border-bottom: 1px #eee solid;
		}
		.aTable tbody.nopadding td,
		.aTable tfoot.nopadding td {
			padding:0;
		}
		.aTable tbody td, 
		.aTable tfoot td { 
			text-align:left;
			vertical-align: middle;
			padding:4px 10px;
			font-weight:400;
			border-right:1px #eee solid;
		}
		.aTable tfoot td.hl { 
			background: #ffd;
		}
		.aTable tbody td.pad410, 
		.aTable tfoot td.pad410 { 
			padding:4px 10px;
		}
		.aTable tbody.bold td, 
		.aTable tfoot.bold td {
			font-weight:600;
		}
		.aTable tbody td:last-child {
			border-right:0;
		}
		.aTable tbody td input[type=text], 
		.aTable tbody td input[type=password], 
		.aTable tbody td select,
		.aTable tfoot td input[type=text], 
		.aTable tfoot td input[type=password], 
		.aTable tfoot td select {
			width:100%;
			padding:4px 10px;
			border:0;
			border-bottom:0px #fff solid;
			margin:0;
			line-height:normal;
			box-sizing: border-box;
			display:inline-block;
			text-align:right;
			background:transparent;
		}
		.aTable tbody td select {
			padding:3px 6px;
			width:auto;
		}
		.tal {
			text-align:left;
		}
		.tac {
			text-align:center;
		}
		.tar {
			text-align:right;
		}
		.xtopnav .btn-group a.disable {
			pointer-events: none;
			cursor: default;
			xbackground:#333;
			color:#ccc;
		}
		.xtopnav btn-group a.disable:hover {
			cursor: not-allowed !important;
			background: #fff !important;
		}
	
	</style>		
	
	</head>
	<body>
		
	<div class="header">
		<table width="100%" border="0">
			<tr>
				<td style="padding-left:15px; width:1px">
					<img style="margin:5px 7px 5px 0; height:40px;" src="../<?=$default_logo?>?<?=time()?>" />
				</td>
				<td style="white-space:nowrap; vertical-align:middle; padding-left:5px">
					<b style="font-family:'Roboto Condensed'; font-weight:400; font-size:24px; color:#333;"><?=$lng['Admin Platform']?></b>
				</td>
				<td style="width:95%"></td>
				<td>
				<? if($lang=='en'){ ?>
					<a data-lng="th" class="langbutton admin_lang <? if($lang=='th'){echo 'activ';} ?>"><img src="../images/flag_th.png"></a>
				<? }else{ ?>
					<a data-lng="en" class="langbutton admin_lang <? if($lang=='en'){echo 'activ';} ?>"><img src="../images/flag_en.png"></a>
				</td>
				<? } ?>
				<td style="padding:0 10px">
				<? if($logger){ ?>
					<button type="button" class="alogout btn btn-logout"><i class="fa fa-power-off"></i></button>
				<? } ?>
				</td>
			</tr>
		</table>
	</div>

	<? if($logger){ ?>
		<div class="topnav-custom">
		
			<div class="btn-group <? if($_GET['mn']==2){echo 'active';}?>">
				<a href="index.php?mn=2" class="home"><i class="fa fa-home"></i></a>

			</div>	

			<div class="btn-group <? if($_GET['mn']==2){echo 'active';}?>">
				<?php 
					if($_GET['mn'] == 75 || $_GET['mn'] == 76 || $_GET['mn'] == 102 || $_GET['mn'] == 103 || $_GET['mn'] == 104 || $_GET['mn'] == 105 || $_GET['mn'] == 108 || $_GET['mn'] == 109 || $_GET['mn'] == 110 || $_GET['mn'] == 111)
					{ ?>

					<a href="index.php?mn=101" class="home"><i class="fa fa-dashboard"></i></a>

				<?php } ?>

			</div>

			<? if($_GET['mn'] == 100){ ?>
				<div class="btn-group <? if($_GET['mn']==100){echo 'active';}?>">
					<a href="index.php?mn=57" class="home"><i class="fa fa-angle-double-left"></i></a>
				</div>
			<? } ?>


			
			<? if($_GET['mn'] >= 10 && $_GET['mn'] < 20){ ?> <!-- CUSTOMERS SETUP ----------------------------- -->
				<div class="btn-group <? if($_GET['mn']==11){echo 'active';}?>">
					<a href="index.php?mn=11"><?=$lng['Customers register']?></a>
				</div>
				<? if($_SESSION['RGadmin']['access']['customer']['add'] == 1){ ?>

					<? if($exceeded == 0){ ?>
						<div class="btn-group <? if($_GET['mn']==12){echo 'active';}?>">
							<a href="index.php?mn=12"><?=$lng['Add new Customer']?></a>
						</div>
					<? } ?>

				<? } ?>
			<? } ?>
			
			<? if($_GET['mn'] >= 50 && $_GET['mn'] < 60){ ?>
				<div class="btn-group <? if($_GET['mn']==51){echo 'active';}?>">
					<a href="index.php?mn=51"><?=$lng['Payroll settings']?></a>
				</div>
				<div class="btn-group <? if($_GET['mn']==52){echo 'active';}?>">
					<a href="index.php?mn=52"><?=$lng['Leave settings']?></a>
				</div>
				<div class="btn-group <? if($_GET['mn']==53){echo 'active';}?>">
					<a href="index.php?mn=53"><?=$lng['Time settings']?></a>
				</div>
				<div class="btn-group <? if($_GET['mn']==58){echo 'active';}?>">
					<a href="index.php?mn=58"><?=$lng['Other settings']?></a>
				</div>
				<div class="btn-group <? if($_GET['mn']==54){echo 'active';}?>">
					<a href="index.php?mn=54"><?=$lng['Holidays']?></a>
				</div>
				<div class="btn-group <? if($_GET['mn']==55){echo 'active';}?>">
					<a href="index.php?mn=55"><?=$lng['Calendar']?></a>
				</div>
				<div class="btn-group <? if($_GET['mn']==56){echo 'active';}?>">
					<a href="index.php?mn=56"><?=$lng['Demo employees']?></a>
				</div>
				<div class="btn-group <? if($_GET['mn']==57){echo 'active';}?>">
					<a href="index.php?mn=57"><?=$lng['eMail templates']?></a>
				</div>
			<? } ?>
			
			<? if($_GET['mn'] >= 30 && $_GET['mn'] < 35){ ?>
				<div class="btn-group <? if($_GET['mn']==30){echo 'active';}?>">
					<a href="index.php?mn=30"><?=$lng['Admin users']?></a>
				</div>
				<div class="btn-group <? if($_GET['mn']==31){echo 'active';}?>">
					<a href="index.php?mn=31"><?=$lng['Client users']?></a>
				</div>
			<? } ?>
			
			<? if($_GET['mn'] >= 60 && $_GET['mn'] < 65){ ?>
				<div class="btn-group <? if($_GET['mn']==60){echo 'active';}?>">
					<a href="index.php?mn=60"><?=$lng['Help files']?></a>
				</div>
				<div class="btn-group <? if($_GET['mn']==61){echo 'active';}?>">
					<a href="index.php?mn=61"><?=$lng['Welcome files']?></a>
				</div>
				<div class="btn-group <? if($_GET['mn']==62){echo 'active';}?>">
					<a href="index.php?mn=62"><?=$lng['Log-in promo file']?></a>
				</div>
			<? } ?>
			
			<? if($_GET['mn'] >= 90 && $_GET['mn'] < 95){ ?> <!-- SUPPORT DESK ------------------------ -->
				<div class="btn-group <? if($_GET['mn']==90){echo 'active';}?>">
					<a href="index.php?mn=90"><?=$lng['Support desk']?></a>
				</div>
			<? } ?>
			
			<? if($_GET['mn'] >= 20 && $_GET['mn'] < 30){ ?>
				<div class="btn-group <? if($_GET['mn']==20){echo 'active';}?>">
					<a href="index.php?mn=20"><?=$lng['Overview']?></a>
				</div>
				<div class="btn-group <? if($_GET['mn']==21){echo 'active';}?>">
					<a href="index.php?mn=21"><?=$lng['New invoice']?></a>
				</div>
				<!--<div class="btn-group <? if($_GET['mn']==23){echo 'active';}?>">
					<a href="index.php?mn=23">New Receipt<? //=$lng['New invoice']?></a>
				</div>-->
				
				<div class="btn-group <? if($_GET['mn']==22){echo 'active';}?>">
					<button data-toggle="dropdown">
						 <?=$lng['Document setup']?> <span class="caret"></span>
					</button>
					<ul class="dropdown-menu">
						<li><a href="index.php?mn=22&inv">Invoice</a></li>
						<li><a href="index.php?mn=22&rec">Receipt / Tax invoice</a></li>
					</ul>
				</div>
			<? } ?>
			
			<!-- USER ---------------------------------------------------------------------------------------------------------->
			
			<div class="btn-group" style="float:right; background:#000 !important">
				<button data-toggle="dropdown" style="padding:0 10px 0 0">
					 <img style="height:36px; width:36px; display:inline-block; border-radius:0px; margin:-4px 10px 0 10px; border:0px solid #666" src="<?=ROOT.$_SESSION['RGadmin']['img']?>?<?=time()?>"><b><?=$_SESSION['RGadmin']['name']?></b>&nbsp; <span class="caret"></span>
				</button>
					<? if($_SESSION['RGadmin']['id'] != '5a6effb9c34ab'){ ?>
					<ul class="dropdown-menu pull-right">
						<li><img style="display:block; width:100%; padding-bottom:2px" src="<?=ROOT.$_SESSION['RGadmin']['img']?>?<?=time()?>"></li>
						<li><a href="index.php?mn=107" style="width:100%" class=""><i class="fa fa-user"></i>&nbsp; <?=$lng['My account']?></a></li>
						<li><a style="width:100%" class="alogout"><i class="fa fa-sign-out"></i>&nbsp; Sign out</a></li>
						<!-- <li><a style="width:100%" data-toggle="modal" data-target="#passModal"><i class="fa fa-lock"></i>&nbsp; <?=$lng['Change password']?></a></li> -->
					</ul>
					<? } ?>
			</div>
			
			<div class="btn-group" style="float:right; background:#000 !important"><a style="font-size:16px; color:#fd0"><b><?=$emp_count?></b> <?=$lng['Employees']?> <?=$lng['in']?> <b><?=$comp_count?></b> <?=$lng['Companies']?></a></div>

			<div class="btn-group" style="float:right; display:none">
				<button data-toggle="dropdown">
					<i class="fa fa-user"></i>&nbsp; <?=$lng['Admin']?> <span class="caret"></span>
				</button>
				<ul class="dropdown-menu xpull-right">
					<li><a <? if($_GET['mn']==31){echo 'class="activ"';} ?> href="index.php?mn=31"><?=$lng['Add new Client']?></a></li>
					<li><a <? if($_GET['mn']==32){echo 'class="activ"';} ?> href="index.php?mn=32"><?=$lng['Client list']?></a></li>
				</ul>
			</div>
			
		</div>
	<? } ?>
	<div id="dump"></div>
	
		<? //var_dump($logger);
		if($logger){


			if($two_factor_authentication == '1' && $authenticated != '1')
				{
					// goto authentication page  if its true in my account setting
					header('location: '.AROOT.'2fa.php');
				}
				else
				{
					// go to consent page  if authetication is fasle in my account settings 

					// check for consent approval 
					if($consent_process == '1')
					{

						if( $termsConsentLatest != '1' && $logged_in_statusLatest != 'yes')
						{
							header('location: '.AROOT.'consent/terms_consent.php');
						
						}		
						else if( $termsConsentLatest == '1' && $logged_in_statusLatest != 'yes' && $terms_consent_changeLatest == '1')
						{
							header('location: '.AROOT.'consent/terms_consent.php');
						
						}
						else if($privacyConsentLatest != '1' && $logged_in_statusLatest != 'yes')
						{
							header('location: '.AROOT.'consent/privacy_consent.php');
						}	
						else if($privacyConsentLatest == '1' && $logged_in_statusLatest != 'yes' && $privacyConsentChangeLatest == '1')
						{
							header('location: '.AROOT.'consent/privacy_consent.php');
						}			

						else if($cookieConsentLatest != '1' && $logged_in_statusLatest != 'yes')
						{
							header('location: '.AROOT.'consent/cookie_consent.php');
						}		

						else if($cookieConsentLatest == '1' && $logged_in_statusLatest != 'yes' && $cookieConsentChangeLatest == '1')
						{
							header('location: '.AROOT.'consent/cookie_consent.php');
						}
						else if( $cookieConsentLatest != '1' && $logged_in_statusLatest = 'yes' && $e_login_cookies != '1')
						{
							header('location: '.AROOT.'consent/consent_detail.php');
						}
						else if( $termsConsentLatest != '1' && $logged_in_statusLatest = 'yes')
						{
						
						}
						else if( $privacyConsentLatest != '1' && $logged_in_statusLatest = 'yes')
						{
						
						}			
						else if( $cookieConsentLatest != '1' && $logged_in_statusLatest = 'yes')
						{
							// header('location: '.AROOT.'consent/message.php');
						}
						else if( $termsConsentLatest== '1' && $privacyConsentLatest== '1' && $cookieConsentLatest == '1' && $showConsentLatest != '1')
						{
							$emailUsername = $_SESSION['RGadmin']['username'];

							$sql_update_consent = "UPDATE rego_users SET showConsent = '1'  WHERE username = '".$emailUsername."'";
							$dba->query($sql_update_consent);
						} 
						else if($_SESSION['RGadmin']['showConsentPage'] == '1')
						{
							if($show_confirmation_text == '1')
							{
								header('location: '.AROOT.'consent/consent_detail.php');
							}
						}
						else
						{
							if($privacyConsentChangeLatest == '1')
							{
								header('location: '.AROOT.'consent/privacy_consent.php');
							}
						}
			
					}
				}


			switch($_GET['mn']){
				
				case 2:  
					include('admin_dashboard.php'); 
					break;
					
				case 11:  
					if($_SESSION['RGadmin']['access']['customer']['access'] == 1){
						include('admin_customers_list.php'); 
					}else{
						include('no_access.php'); 
					}
					break;
				case 12:  
					if($_SESSION['RGadmin']['access']['customer']['access'] == 1){
						include('admin_new_customer.php'); 
					}else{
						include('no_access.php'); 
					}
					break;
				
				case 20: 
					if($_SESSION['RGadmin']['access']['billing']['access'] == 1){
						include('billing/billing_list.php'); 
					}else{
						include('no_access.php'); 
					}
					break;
				case 21: 
					if($_SESSION['RGadmin']['access']['billing']['access'] == 1){
						include('billing/new_invoice.php'); 
					}else{
						include('no_access.php'); 
					}
					break;
				case 22: 
					if($_SESSION['RGadmin']['access']['billing']['access'] == 1){
						include('billing/document_setup.php'); 
					}else{
						include('no_access.php'); 
					}
					break;
				case 23: 
					if($_SESSION['RGadmin']['access']['billing']['access'] == 1){
						include('billing/new_receipt.php'); 
					}else{
						include('no_access.php'); 
					}
					break;
				
				case 30: 
					if($_SESSION['RGadmin']['access']['users']['access'] == 1){
						include('admin_system_users.php'); 
					}else{
						include('no_access.php'); 
					}
					break;
				case 31: 
					if($_SESSION['RGadmin']['access']['users']['access'] == 1){
						include('rego_users.php'); 
					}else{
						include('no_access.php'); 
					}
					break;
				
				case 40: 
					if($_SESSION['RGadmin']['access']['price']['access'] == 1){
						include('admin_standards.php'); 
						//include('admin_pricetables.php'); 
					}else{
						include('no_access.php'); 
					}
					break;
				
				case 50: break;
				case 51: 
					if($_SESSION['RGadmin']['access']['def_settings']['access'] == 1){
						include('def_settings/default_payroll_settings.php'); 
					}else{
						include('no_access.php'); 
					}
					break;
				case 52: 
					if($_SESSION['RGadmin']['access']['def_settings']['access'] == 1){
						include('def_settings/default_leave_settings.php'); 
					}else{
						include('no_access.php'); 
					}
					break;
				case 53: 
					if($_SESSION['RGadmin']['access']['def_settings']['access'] == 1){
						include('def_settings/default_time_settings.php'); 
					}else{
						include('no_access.php'); 
					}
					break;				
				case 7002: 
					if($_SESSION['RGadmin']['access']['def_settings']['access'] == 1){
						include('def_settings/edit_admin_shift_schedule.php'); 
					}else{
						include('no_access.php'); 
					}
					break;
				case 54: 
					if($_SESSION['RGadmin']['access']['def_settings']['access'] == 1){
						include('def_settings/default_holidays.php'); 
					}else{
						include('no_access.php'); 
					}
					break;
				case 55: 
					if($_SESSION['RGadmin']['access']['def_settings']['access'] == 1){
						include('def_settings/default_calendar.php'); 
					}else{
						include('no_access.php'); 
					}
					break;
				case 56: 
					if($_SESSION['RGadmin']['access']['def_settings']['access'] == 1){
						include('def_settings/demo_employees.php'); 
					}else{
						include('no_access.php'); 
					}
					break;
				case 59: 
					if($_SESSION['RGadmin']['access']['def_settings']['access'] == 1){
						include('def_settings/demo_employees_edit.php'); 
					}else{
						include('no_access.php'); 
					}
					break;
				case 57: 
					if($_SESSION['RGadmin']['access']['def_settings']['access'] == 1){
						include('def_settings/default_email_templates.php'); 
					}else{
						include('no_access.php'); 
					}
					break;
				case 58: 
					if($_SESSION['RGadmin']['access']['def_settings']['access'] == 1){
						include('def_settings/default_other_settings.php'); 
					}else{
						include('no_access.php'); 
					}
					break;
				case 580: 
					include('def_settings/default_settings.php'); 
					break;
				
				case 60: 
					if($_SESSION['RGadmin']['access']['help']['access'] == 1){
						include('help/help_files.php'); 
					}else{
						include('no_access.php'); 
					}
					break;				
				case 61: 
					if($_SESSION['RGadmin']['access']['help']['access'] == 1){
						include('help/welcome_files.php'); 
					}else{
						include('no_access.php'); 
					}
					break;				
				case 62: 
					if($_SESSION['RGadmin']['access']['help']['access'] == 1){
						include('help/promo_file.php'); 
					}else{
						include('no_access.php'); 
					}
					break;				

				case 70: 
					if($_SESSION['RGadmin']['access']['privacy']['access'] == 1){
						include('admin_logdata.php'); 
					}else{
						include('no_access.php'); 
					}
					break;				
				
				case 75: 
					if($_SESSION['RGadmin']['access']['privacy']['access'] == 1){
						include('help/terms_conditions.php'); 
					}else{
						include('no_access.php'); 
					}
					break;				
				
				case 76: 
					if($_SESSION['RGadmin']['access']['privacy']['access'] == 1){
						include('help/privacy_policy.php'); 
					}else{
						include('no_access.php'); 
					}
					break;				
				
				case 80: 
					if($_SESSION['RGadmin']['access']['comp_settings']['access'] == 1){
						include('admin_company_settings.php'); 
					}else{
						include('no_access.php'); 
					}
					break;				
				
				case 90: 
					if($_SESSION['RGadmin']['access']['support']['access'] == 1){
						include('support/support_desk.php'); 
					}else{
						include('no_access.php'); 
					}
					break;
				case 91: 
					if($_SESSION['RGadmin']['access']['support']['access'] == 1){
						include('support/support_ticket.php'); 
					}else{
						include('no_access.php'); 
					}
					break;
				case 92: 
					if($_SESSION['RGadmin']['access']['support']['access'] == 1){
						include('support/new_support_ticket.php'); 
					}else{
						include('no_access.php'); 
					}
					break;
				
				case 95: 
					if($_SESSION['RGadmin']['access']['agents']['access'] == 1){
						include('agents/agents_list.php'); 
					}else{
						include('no_access.php'); 
					}
					break;
				
				case 99: 
					if($_SESSION['RGadmin']['access']['language']['access'] == 1){
						include('language/language_list.php'); 
					}else{
						include('no_access.php'); 
					}
					break;
				case 100: 
					if($_SESSION['RGadmin']['access']['def_settings']['access'] == 1){
						include('def_settings/default_new_email_templates.php'); 
					}else{
						include('no_access.php'); 
					}
					break;				

				case 101: 
					if($_SESSION['RGadmin']['access']['privacy']['access'] == 1){
						include('help/legal_conditions.php'); 
					}else{
						include('no_access.php'); 
					}
					break;		

				case 102: 
					if($_SESSION['RGadmin']['access']['privacy']['access'] == 1){
						include('help/cookie_consent.php'); 
					}else{
						include('no_access.php'); 
					}
					break;		
				case 103: 
					if($_SESSION['RGadmin']['access']['privacy']['access'] == 1){
						include('help/cookie_confirmation.php'); 
					}else{
						include('no_access.php'); 
					}
					break;				
				case 104: 
					if($_SESSION['RGadmin']['access']['privacy']['access'] == 1){
						include('help/confirmation_log.php'); 
					}else{
						include('no_access.php'); 
					}
					break;						
				case 105: 
					if($_SESSION['RGadmin']['access']['privacy']['access'] == 1){
						include('help/consent_settings.php'); 
					}else{
						include('no_access.php'); 
					}
					break;						
				case 106: 
					if($_SESSION['RGadmin']['access']['privacy']['access'] == 1){
						include('help/myaccount.php'); 
					}else{
						include('no_access.php'); 
					}
					break;					
				case 107: 
					if($_SESSION['RGadmin']['access']['privacy']['access'] == 1){
						include('help/cookie_consent_settings.php'); 
					}else{
						include('no_access.php'); 
					}
					break;					
				case 108: 
					if($_SESSION['RGadmin']['access']['privacy']['access'] == 1){
						include('help/consent_letter/consent_letters.php'); 
					}else{
						include('no_access.php'); 
					}
					break;				

				case 109: 
					if($_SESSION['RGadmin']['access']['privacy']['access'] == 1){
						include('help/consent_letter/identification.php'); 
					}else{
						include('no_access.php'); 
					}
					break;					

				case 110: 
					if($_SESSION['RGadmin']['access']['privacy']['access'] == 1){
						include('help/consent_letter/body_text.php'); 
					}else{
						include('no_access.php'); 
					}
					break;			
								
				case 111: 
					if($_SESSION['RGadmin']['access']['privacy']['access'] == 1){
						include('help/consent_letter/reference.php'); 
					}else{
						include('no_access.php'); 
					}
					break;			
				
			}
		}else{
			//include('admin_login.php');
			header('location: admin_login.php');
		}
		?>
	
	<!-- Modal Change Password -->
	<div class="modal fade" id="passModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
		 <div class="modal-dialog" style="max-widt:450px">
			  <div class="modal-content">
					<div class="modal-header">
						 <h4 class="modal-title" id="myModalLabel"><i class="fa fa-lock"></i>&nbsp; <?=$lng['Change password']?></h4>
					</div>
					<div class="modal-body" style="padding:10px 25px 25px 25px">
					<span style="font-weight:600; color:#cc0000;" id="pass_msg"></span>
					<form id="userPassword" class="sform" style="padding-top:10px;">
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
	<div class="modal fade" id="consentModal1" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
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
	<script src="../assets/js/rego.js"></script>
	<script src="../assets/js/jquery.sumoselect.min.js"></script>

	<script src="../assets/js/jquery.flicker.js?<?=time()?>"></script>
	<script src="../assets/js/jquery.autocomplete.js"></script>

			<script src='../assets/js/fullcalendar.js'></script>
		<script src='../assets/js/main.js'></script>
		<? if($lang == 'th'){ ?>
		<script src="../assets/js/fullcalendar-th.js?<?=time()?>"></script>
		<? } ?>
	
	<script type="text/javascript">
		
	$(document).ready(function() {


				// php variables to show the consent chekbox values 

		var showConsent = '<?php echo $showConsent?>';
		$( document ).ready(function() {

		var is_modal_showAdmin = sessionStorage.getItem('alreadyShowAdmin');


			if(showConsent == '1')
			{
				if(is_modal_showAdmin != 'alredy shown'){
					// $('#consentModal1').modal('show');
				  	sessionStorage.setItem('alreadyShowAdmin','alredy shown');
				}
			}
			var counter = 3;
			var interval = setInterval(function() {
			    counter--;
			    // Display 'counter' wherever you want to display it.
			    if (counter <= 0) 
			    {
			     	clearInterval(interval);
					// $('#consentModal1').modal('hide');
			        return;
			    }
			    else
			    {
			    	$('#time_count').text(counter);
			    }
			}, 1000);



		});


		
		$(document).on('click', ".dataTable.selectable tbody tr", function(){
			$(".dataTable tbody tr").removeClass('selected');
			$(this).addClass('selected');
		});

		$('.date_year').datepicker({
			format: "dd-mm-yyyy",
			autoclose: true,
			inline: true,
			language: '<?=$lang?>',//lang+'-th',
			//viewMode: 'years',
			startView: 'decade',
			todayHighlight: true,
			//startDate : startYear,
    		//endDate   : endYear
		})
		
		$('.date_month').datepicker({
			format: "dd-mm-yyyy",
			autoclose: true,
			inline: true,
			language: '<?=$lang?>',//lang+'-th',
			//viewMode: 'years',
			startView: 'year',
			todayHighlight: true,
			//startDate : startYear,
    		//endDate   : endYear
		})
		
		$('.datepick').datepicker({
			format: "dd-mm-yyyy",
			autoclose: true,
			inline: true,
			language: '<?=$lang?>',//lang+'-th',
			//viewMode: 'years',
			todayHighlight: true,
			//startDate : startYear,
    		//endDate   : endYear
		})
		
		var innerheight = window.innerHeight;
		$('.widget.autoheight').css('min-height', innerheight-150);
		
		$(document).on('click', '.selectCustomer', function(){
			$.ajax({
				url: "ajax/select_customer.php",
				data: {id: $(this).data('id')},
				success: function(result){
					$('#dump').html(result);
					//alert(ROOT+result+'/index.php')
					//window.open(ROOT+result+'/index.php', '_blank');
					location.href = ROOT+'index.php';
				}
			});
		});
		
		$('.admin_lang').on('click', function(){
			//alert()
			$.ajax({
				url: "ajax/change_lang.php",
				data: {lng: $(this).data('lng')},
				success: function(ajaxresult){
					//alert(ajaxresult);
					location.reload();
				}
			});
		});
		
		$(".alogout").click(function(){ 


			sessionStorage.removeItem('alreadyShowAdmin');

			$.ajax({
				url: "ajax/logout.php",
				success: function(result){
					//alert(result)
					location.reload();
				},
				error:function (xhr, ajaxOptions, thrownError){
					alert(thrownError);
				}
			});
		});
		
		$(window).on('resize', function(){
			var innerheight = window.innerHeight;
			$('.widget.autoheight').css('min-height', innerheight-150)
		});	
		
		$("#userPassword").submit(function (e) {
			e.preventDefault();
			if($('#opass').val()=='' || $('#npass').val()=='' || $('#rpass').val()==''){
				$("body").overhang({
					type: "error",
					message: '<i class="fa fa-exclamation-triangle"></i>&nbsp;&nbsp;<?=$lng['Please fill in required fields']?>',
					duration: 3,
				})
				return false;
			}
			if($('#npass').val().length < 8){
				$("body").overhang({
					type: "error",
					message: '<i class="fa fa-exclamation-triangle"></i>&nbsp;&nbsp;<?=$lng['New password to short min 8 characters']?>',
					duration: 3,
				})
				return false;
			}
			if($('#npass').val() !== $('#rpass').val()){
				$("body").overhang({
					type: "error",
					message: '<i class="fa fa-exclamation-triangle"></i>&nbsp;&nbsp;<?=$lng['New passwords are not the same']?>',
					duration: 3,
				})
				return false;
			}
			var formData = $(this).serialize();
			//alert(formData);
			$.ajax({
				url: "ajax/change_admin_password.php",
				dataType: "text",
				data: formData,
				success: function(response){
					response = $.trim(response);
					//$('#dump').html(response); return false;
					if(response=='success'){
						$("body").overhang({
							type: "success",
							message: '<i class="fa fa-check"></i>&nbsp;&nbsp;<?=$lng['Password changed successfuly']?>',
							duration: 3,
						})
						setTimeout(function(){
							$('#passModal').modal('toggle');
						}, 2000);
					}else if(response=='old'){
						$("body").overhang({
							type: "error",
							message: '<i class="fa fa-exclamation-triangle"></i>&nbsp;&nbsp;<?=$lng['Old Password is wrong']?>',
							duration: 3,
						})
					}else{
						$("body").overhang({
							type: "error",
							message: '<i class="fa fa-exclamation-triangle"></i>&nbsp;&nbsp;' + response,
							duration: 3,
						})
					}
				},
				error:function (xhr, ajaxOptions, thrownError){
					$("body").overhang({
						type: "error",
						message: '<i class="fa fa-exclamation-triangle"></i>&nbsp;&nbsp;<?=$lng['Sorry but someting went wrong']?> <b><?=$lng['Error']?></b> : '+thrownError,
						duration: 8,
						closeConfirm: "true",
					})
				}
			});
		});
		$('#passModal').on('hidden.bs.modal', function () {
			$(this).find('form').trigger('reset');
			$("#pass_msg").html('');
		});
			
		<? if($logger == 'x'){ ?>
			setTimeout(function(){
				$.ajax({
					url: "ajax/logtime_expired.php",
					success: function(result){
						window.location.reload();
					}
				});
			}, <?=$logtime*1000?>);
		<? } ?>
		
	});

	</script>

	<script>

		$('.ssdatepick').datepicker({
			format: "dd/mm/yyyy",
			autoclose: true,
			inline: true,
			language: '<?=$lang?>',
			todayHighlight: true,
		});

	</script>

	</body>
</html>








