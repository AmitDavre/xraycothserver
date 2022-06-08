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
		}
		//var_dump($logtime);
		if($logtime < 60){
			$logtime = 900; // 15 min
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


	$res = $dbadmin->query("SELECT * FROM rego_cookie_consent");
	if($row = $res->fetch_assoc()){
		$th_content = $row['th_content'];
		$en_content = $row['en_content'];
		
		if($lang == 'en')
		{
			$contentValue = $row['en_content'];
		}
		else
		{
			$contentValue = $row['th_content'];
		}

	}	


	// check here if privacy policy is agreed then go to next policy which is not agreed 


	$sql103 = "SELECT * FROM rego_all_users WHERE username = '".$_SESSION['rego']['username']."'";

	if($res103 = $dbadmin->query($sql103)){
		if($res103->num_rows > 0){
			if($row103 = $res103->fetch_assoc())
				{
					$cookie_consentValue = $row103['cookie_consent'];  
					$phpsessid = $row103['phpsessid'];
					$lang_value =  $row103['lang'];
					$rego_lang =  $row103['rego_lang'];
					$scanlang =  $row103['scanlang'];
						
				}
		}
	}

	if($cookie_consentValue == '1')
	{
		header('location: '.ROOT.'consent_detail.php');
	}


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

					                <div style="display:  flex;"> <i style="font-size: 33px;" class="fa fa-user-secret" aria-hidden="true" ></i> &nbsp;&nbsp;  <h3><?=$lng['Cookie Consent']?></h3> </div>
					                    <div id="cookie_consent_data" style="float:left;overflow-y: auto;height: 387px;margin-top: 36px;">
					                    	<p > <?php echo $contentValue;?></p> </div>

					                	<div style="margin-top: 24px;">

					                		<label>
					                          <input id="cookie-remember" type="checkbox" name="remember" value="1"> <?=$lng['By checking this box, I state that I have read and understood the cookie consent.']?> 
					                        </label>

                        				</div>
                        				<div style="margin-top: 30px;">
                        					<!-- <button style="float: left;background: #000;border-color: #000;color: #fff;" class="btn goBackterms" type="button"><i style="font-size: 15px;" class="fa fa-arrow-circle-o-left" aria-hidden="true"></i> Go Back</button> -->
                        					<button style="float: right;background: #c00;border-color: #c00;color: #fff;" class="btn disagreeCookie" type="button"><i style="font-size: 15px;" class="fa fa-exclamation-circle" aria-hidden="true"></i> <?=$lng['I do not agree']?></button>
                        					<button disabled="disabled" style="float: right;background: #080;border-color: #080;color: #fff;margin-right: 5px;" class="btn saveCookie " id="cookieAgree" type="button"><i style="font-size: 15px;" class="fa fa-check-circle" aria-hidden="true"></i> <?=$lng['I agree']?></button>
                        					
                        				</div>
					            </div>
					        </div>
					    </div>
					</div>

	                <!-- <span class="display-1 d-block" style="margin-top: 200px;">There is a problem with the account.</span> -->
	                <!-- <div class="mb-4 lead" style="margin-top: 70px;font-size: 20px;">Please contact us at <?php echo $admin_mail_value ;?> for further assistance.</div> -->
	            </div>
	        </div>
	    </div>
	</div>


	<div class="modal fade" id="modalConsent" tabindex="-1" role="dialog" data-backdrop="static" data-keyboard="false">
		<div class="modal-dialog" role="document">
			<div class="modal-content" style="width: 550px;">
				<div class="modal-header">
					<h5 class="modal-title"><?=$lng['Change Cookie Consent Access']?></h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">
					<form method="post" id="requestForm" class="sform">

						<div style="padding: 5px 2px;">
							<label><input style="height: 14px;width: 14px;" type="checkbox" checked="checked" name="cookie_phpsessid" id= "cookie_phpsessid" value="1" > <span style="margin-left: 22px"> Auto generated cookies for the functioning of our website:   <i class="man"></i></span> <p style="font-weight: 100;margin-left: 38px" > Cookies like (but not limited to) : PHPSESSID, rego_lang, scanlang  are auto generated cookies during your session and are required for the functioning of our website.</p></label>
						</div>
						<div style="padding: 5px 2px;">
							<label><input style="height: 14px;width: 14px;" type="checkbox"  name="cookie_lang"  id="cookie_lang" value="1" ><span style="margin-left: 22px"> Cookie Remember username : </span><p style="font-weight: 100;margin-left: 38px" > This is not a required Cookie for our website. It tells the browser to save a cookie so that you will not have to type the username at the sign-in process.</p></label>
						</div>
						<div style="padding: 5px 2px;">
							<label><input style="height: 14px;width: 14px;" type="checkbox"  name="cookie_rego_lang" id="cookie_rego_lang" value="1" ><span style="margin-left: 22px"> Cookie remember password :  </span><p style="font-weight: 100;margin-left: 38px" > This is not a required Cookie for our website. It tells the browser to save a cookie so that you will not have to type the password at the sign-in process.</p></label>
						</div>						

						<div style="height:15px"></div>
						<button id="modalCancelConsentBtn" type="button" class="btn btn-primary btn-fr" data-dismiss="modal"></i>&nbsp; <?=$lng['OK']?></button>
						<div class="clear"></div>
					</div>
			  </div>
		 </div>
	</div>
	
	
	<? include('include/modal_relog.php')?>

	<script type="text/javascript">
		// save script here 



		// var url      = window.location.href; 

		// var result = url.split('cookie_consent.php?');

		// if(result[1] == 'open')
		// {
		// 	$('#modalConsent').modal('show');
		// }
	



		var hideElm = 'clicking here',
	    regex = new RegExp(hideElm, 'g');

		$('#cookie_consent_data').html(function(i, html){
		  return html.replace(regex, '<button id="choose_cookie" style="background: none;border: none;color: #0066CC;padding: 0px;">' + hideElm + '</button>');
		});

		$(document).on('click', '#choose_cookie', function(e) {

			$('#modalConsent').modal('show');

		})

		
		// I AGREE
		$(document).on('click', '.saveCookie', function(e) {

			// if agree then check all the cookies as well 

			if($('#cookie_phpsessid').is(":checked")){
				var cookie_phpsessid = '1';
			}else{
				var cookie_phpsessid = '0';
			}			

			if($('#cookie_lang').is(":checked")){
				var cookie_lang = '1';
				var langchecked = 'yes';

			}else{
				var cookie_lang = '0';
				var langchecked = 'no';

			}

			if($('#cookie_rego_lang').is(":checked")){
				var cookie_rego_lang = '1';
				var regolangchecked = 'yes';

			}else{
				var cookie_rego_lang = '0';
				var regolangchecked = 'no';

			}

			if($('#cookie_scanlang').is(":checked")){
				var cookie_scanlang = '1';
			}else{
				var cookie_scanlang = '0';
			}




			var agreeValue = 'yes';
			$.ajax({
				url: "ajax/consent/update_cookie_consent.php",
				data: {agreeValue:agreeValue,cookie_phpsessid: cookie_phpsessid,cookie_lang:cookie_lang,cookie_rego_lang:cookie_rego_lang,cookie_scanlang:cookie_scanlang,langchecked:langchecked,regolangchecked:regolangchecked },

				success: function(result){

					$("body").overhang({
						type: "success",
						message: '<i class="fa fa-check"></i>&nbsp;&nbsp;<?=$lng['Cookies consent saved successfully']?> . . .',
						duration: 1,
					})
					setTimeout(function(){
						window.location.href = 'consent_detail.php';
					}, 1000);


				}
			});
		})		

		// I DONT AGREE
		$(document).on('click', '.disagreeCookie', function(e) {

			// if don't agree then remvoe all the checked cookies 


			if($('#cookie_phpsessid').is(":checked")){
				var cookie_phpsessid = '1';
				var cookiechcked = 'yes';
			}else{
				var cookie_phpsessid = '0';
				var cookiechcked = 'no';

			}			

			if($('#cookie_lang').is(":checked")){
				var cookie_lang = '1';
				var langchecked = 'yes';
			}else{
				var cookie_lang = '0';
				var langchecked = 'no';

			}

			if($('#cookie_rego_lang').is(":checked")){
				var cookie_rego_lang = '1';
				var regolangchecked = 'yes';
			}else{
				var cookie_rego_lang = '0';
				var regolangchecked = 'no';

			}

			if($('#cookie_scanlang').is(":checked")){
				var cookie_scanlang = '1';
			}else{
				var cookie_scanlang = '0';
			}


			var agreeValue = 'no';
			$.ajax({
				url: "ajax/consent/update_cookie_consent.php",
				data: {agreeValue:agreeValue,cookie_phpsessid: cookie_phpsessid,cookie_lang:cookie_lang,cookie_rego_lang:cookie_rego_lang,cookie_scanlang:cookie_scanlang,cookiechcked:cookiechcked,langchecked:langchecked,regolangchecked:regolangchecked },

				success: function(result){

					$("body").overhang({
						type: "success",
						message: '<i class="fa fa-check"></i>&nbsp;&nbsp;<?=$lng['Cookies consent saved successfully']?> . . .',
						duration: 1,
					})
					setTimeout(function(){
						window.location.href = 'consent_detail.php';
					}, 1000);


				}
			});
		})


		// DISABLE ON CLICK CHECKBOX

		$('#cookie-remember').click(function() {
	        if ($(this).is(':checked')) {
	            $('#cookieAgree').removeAttr('disabled');
	            // $('#cookie_phpsessid').prop('checked',true);
				// $('#cookie_lang').prop('checked',true);
				// $('#cookie_rego_lang').prop('checked',true);
				// $('#cookie_scanlang').prop('checked',true);

	        } else {
	            $('#cookieAgree').attr('disabled', 'disabled');
	            // $('#cookie_phpsessid').prop('checked',false);
				// $('#cookie_lang').prop('checked',false);
				// $('#cookie_rego_lang').prop('checked',false);
				// $('#cookie_scanlang').prop('checked',false);


	        }
	    });
		
		$('#cookie_phpsessid').click(function() {
	        if ($(this).is(':checked')) {
	            // $('#cookieAgree').removeAttr('disabled');
	            // $('#cookie-remember').prop('checked',true);

	        } else {
	            // $('#cookieAgree').attr('disabled', 'disabled');
	            // $('#cookie-remember').prop('checked',false);



	        }
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













