<?php

	if(session_id()==''){session_start();} 
	ob_start();

	// echo '<pre>';
	// print_r($_SESSION);
	// echo '</pre>';

	// die();
	
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
		}
		//var_dump($logtime);
		if($logtime < 60){
			$logtime = 900; // 15 min
		}
		//var_dump($logtime);
		if(time() - $_SESSION['rego']['timestamp'] > $logtime) {
			$_SESSION['rego']['timestamp'] = 0;
			$logger = false; 
		}else{
			$_SESSION['rego']['timestamp'] = time();
			$logger = true;
			$years = getYears(); // Get payroll Years
			$_SESSION['rego']['payroll_dbase'] = $_SESSION['rego']['cid'].'_payroll_'.$_SESSION['rego']['cur_year'];
			$_SESSION['rego']['emp_dbase'] = $cid.'_employees';
			//$_SESSION['rego']['paydate'] = getPaydate($cid);
			if(!isset($_SESSION['rego']['period'])){$_SESSION['rego']['period'] = $lng['Select period'];}
			//$_SESSION['rego']['period'] = $months[$_SESSION['rego']['cur_month']].' '.$_SESSION['rego']['year_'.$lang];
			if($_SESSION['rego']['customers']){
				$customers = getCustomers($_SESSION['rego']['customers']);
			}

			$checkSetup = checkSetupData($cid);
			$periods = getPayrollPeriods($lang);
			$period = $periods['period'];
		}
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
		}else{
			$typecchk = '';
		}
	}else{
		$typecchk ='';
	}

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


	$SixDigitRandomNumber = mt_rand(100000,999999);

	if($SixDigitRandomNumber)
	{
		// save six digit number to user row 
		$sql_updateotp = "SELECT * FROM rego_all_users WHERE username = '".$_SESSION['rego']['username']."'";
		if($res_updateotp = $dbx->query($sql_updateotp)){
			if($row_updateotp = $res_updateotp->fetch_assoc()){

				$authenticated_code = $row_updateotp['authenticated_code'];

				if(!$authenticated_code)
				{
					$sql_upauth = "UPDATE rego_all_users SET 
					authenticated_code = '".$dbx->real_escape_string($SixDigitRandomNumber)."'  WHERE username = '".$_SESSION['rego']['username']."' "; 
					$dbx->query($sql_upauth);

					// send in email to the user 


					require 'PHPMailer/PHPMailerAutoload.php';
					$body = "<html>
							<head>
							<meta http-equiv='Content-Type' content='text/html; charset=utf-8' />
							</head>
							<body style='font-size:16px'>
								<p>Dear ".$_SESSION['rego']['name'].",</p>
								<p>Your 6 digit authentication code is <b>".$SixDigitRandomNumber."</b><br>
								</p>
								<p>Kind regards,<br>".strtoupper($client_prefix)." Admin</p>
							</body></html>";

					$mail = new PHPMailer;
					$mail->CharSet = 'UTF-8';
					$mail->From = $admin_mail_value;
					$mail->FromName = strtoupper($client_prefix).' ผู้ดูแลระบบ (Admin)';
					$mail->addAddress($_SESSION['rego']['username']);     // Add a recipient
					$mail->isHTML(true);                                  
					$mail->Subject = '6 Digit Code';
					$mail->Body = $body;
					$mail->WordWrap = 100;                                
					$mail->send();




				}

			}
		}
	}

	


	// // check here if terms is agreed then go to next policy which is not agreed 


	// $sql103 = "SELECT * FROM rego_all_users WHERE username = '".$_SESSION['rego']['username']."'";

	// if($res103 = $dbadmin->query($sql103)){
	// 	if($res103->num_rows > 0){
	// 		if($row103 = $res103->fetch_assoc())
	// 			{
	// 				$terms_consentValue = $row103['terms_consent'];  // SELECTED TEAMS STORED IN SESSION 
						
	// 			}
	// 	}
	// }



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
<style type="text/css">
	.card {
    flex-direction: column;
    min-width: 0;
    color: #000;
    word-wrap: break-word;
    background-color: #fff;
    background-clip: border-box;
    border: 1px solid #fff;
    border-radius: 6px;
    -webkit-box-shadow: 0px 0px 5px 0px rgb(249, 249, 250);
    -moz-box-shadow: 0px 0px 5px 0px rgba(212, 182, 212, 1);
    box-shadow: 0px 0px 5px 0px rgb(161, 163, 164)
}

.learn-more {
    text-decoration: none;
    color: #000;
    margin-top: 8px
}

.learn-more:hover {
    text-decoration: none;
    color: blue;
    margin-top: 8px
}

.digitclass
{
	margin: 0 auto!important;
    margin-right: 0px!important;
    margin-top: 20px!important;
    margin-bottom: 30px!important;
    font-size: 22px!important;
}

</style>
	<body>
	
	<? include('include/main_header.php');?>
	
	<div class="topnav-custom">
	
	</div>

	<div class="page-wrap d-flex flex-row ">
	    <div class="container">
	        <div class="row ">
	            <div class="col-md-12 ">
	            	<div class="d-flex  container mt-5">
					    <div class="row" style="width: 100%;">
					        <div class="col-md-12">
					            <div class="card cookie p-3">

					                <div style="display:  flex;"> <i style="font-size: 33px;" class="fa fa-key" aria-hidden="true" ></i> &nbsp;&nbsp;  <h3><?=$lng['Two-Factor Authentication (2FA)']?></h3> </div>
					                    <div style="float:left;height: 310px;margin-top:80px!important;margin: 0 auto">
					                    	<!-- <h4> A 6 Digit code has been sent to your email address. Please enter the code and submit. </h4> -->

							                <div id="wrapper" style="text-align: center;">
											  <div id="dialog">
											    <h3><?=$lng['Please enter the 6-digit verification code we sent via Email']?> :</h3>
											    <span>(<?=$lng['we want to make sure its you']?>)</span>
											    <div id="form" style="display: flex;">
											      <input id="box1" class="digitclass" type="text" maxLength="1" size="1" min="0" max="9" pattern="[0-9]{1}" />
											      <input id="box2" class="digitclass" type="text" maxLength="1" size="1" min="0" max="9" pattern="[0-9]{1}" />
											      <input id="box3" class="digitclass" type="text" maxLength="1" size="1" min="0" max="9" pattern="[0-9]{1}" />
											      <input id="box4" class="digitclass" type="text" maxLength="1" size="1" min="0" max="9" pattern="[0-9]{1}" />
											      <input id="box5" class="digitclass" type="text" maxLength="1" size="1" min="0" max="9" pattern="[0-9]{1}" />
											      <input id="box6" class="digitclass" type="text" maxLength="1" size="1" min="0" max="9" pattern="[0-9]{1}" />
											    </div>
											    <div>
											      <button id="verifyotp" class="btn btn-primary btn-embossed btn-lg" style="margin-top: 10px;"><?=$lng['Verify']?></button>
											    </div>
											    <div style="margin-top: 30px;">
													<h4>
													  <?=$lng['Did not receive the code']?>?<br />
													  <a style="color: #0066CC;" onclick="send_auth_code();"><?=$lng['Send code again']?></a><br />
													</h4>
											    </div>
											  </div>
											</div>


											
										</div>
			
                        			
					            </div>
					        </div>
					    </div>
					</div>
	            </div>
	        </div>
	    </div>
	</div>

	
	
	<? include('include/modal_relog.php')?>

	<script type="text/javascript">
		// save script here 

		$(function() {
		  'use strict';

		  var body = $('body');

		  function goToNextInput(e) {
		    var key = e.which,
		      t = $(e.target),
		      sib = t.next('input');

		    if (key != 9 && (key < 48 || key > 57)) {
		      // e.preventDefault();
		      // return false;
		    }

		    if (key === 9) {
		      return true;
		    }

		    if (!sib || !sib.length) {
		      sib = body.find('input').eq(0);
		    }
		    sib.select().focus();
		  }

		  function onKeyDown(e) {
		    var key = e.which;

		    if (key === 9 || (key >= 48 && key <= 57)) {
		      return true;
		    }

		    // e.preventDefault();
		    // return false;
		  }
		  
		  function onFocus(e) {
		    $(e.target).select();
		  }

		  body.on('keyup', 'input', goToNextInput);
		  body.on('keydown', 'input', onKeyDown);
		  body.on('click', 'input', onFocus);

		})




// OTP VERIFY CODE HERE 

	$("#verifyotp").on('click', function(e){  

		var box1 = $('#box1').val();
		var box2 = $('#box2').val();
		var box3 = $('#box3').val();
		var box4 = $('#box4').val();
		var box5 = $('#box5').val();
		var box6 = $('#box6').val();

		if(box1 == '' || box2 == '' || box3 == '' || box4 == '' || box5 == '' || box6 == '' )
		{
			$("body").overhang({
				type: "error",
				message: '<i class="fa fa-exclamation-triangle"></i>&nbsp;&nbsp;<?=$lng['Error']?> : Please enter the code',
				duration: 4,
			})
			return false; 
		}

		$.ajax({
			url: "ajax/consent/verify_authenticated_code.php",
			data: { box1: box1,box2:box2,box3:box3,box4:box4,box5:box5,box6:box6 },
			success: function(result){
				if(result == 'success'){
					$("body").overhang({
						type: "success",
						message: '<i class="fa fa-check"></i>&nbsp;&nbsp;Authenticated successfuly',
						duration: 2,
					})
					setTimeout(function(){
						location.href = ROOT+'index.php';
					},1000);
				}else{
					$("body").overhang({
						type: "error",
						message: '<i class="fa fa-exclamation-triangle"></i>&nbsp;&nbsp;<?=$lng['Error']?> : Please enter the correct 6 digit code',
						duration: 4,
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


	function send_auth_code() {  

		$.ajax({
			url: "ajax/consent/send_authenticated_code.php",
			success: function(result){
				if(result == 'success'){
					$("body").overhang({
						type: "success",
						message: '<i class="fa fa-check"></i>&nbsp;&nbsp;Authentication code sent successfuly',
						duration: 2,
					})
					setTimeout(function(){
						// location.href = ROOT+'index.php';
					},1000);
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

	}

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













