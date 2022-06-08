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

	$res = $dbadmin->query("SELECT * FROM rego_privacy_policy");
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

	// check in rego all users table for the consent 
		$sql_consent = "SELECT * FROM rego_all_users WHERE username = '".$_SESSION['rego']['username']."'";
		if($res_consent = $dbx->query($sql_consent)){
			if($row_consent = $res_consent->fetch_assoc()){
				$privacyConsent = $row_consent['privacy_consent'];
				$privacy_consent_date = $row_consent['privacy_consent_date'];
				$termsConsent = $row_consent['terms_consent'];
				$terms_consent_date = $row_consent['terms_consent_date'];
				$cookieConsent = $row_consent['cookie_consent'];
				$cookie_consent_date = $row_consent['cookie_consent_date'];
				$showConsent = $row_consent['showConsent'];

				if($privacyConsent == '1'){
					$privacyAgreed = 'Agreed';
				}else{
					$privacyAgreed = 'Not Agreed';
				}

				if($termsConsent == '1'){
					$termsAgreed = 'Agreed';
				}else{
					$termsAgreed = 'Not Agreed';
				}				

				if($cookieConsent == '1'){
					$cookieAgreed = 'Agreed';
				}else{
					$cookieAgreed = 'Not Agreed';
				}


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

					                <div style="display:  flex;"> <i style="font-size: 33px;" class="fa fa-user-secret" aria-hidden="true" ></i> &nbsp;&nbsp;  <h3> Confirmation Text</h3> </div>
					                    <div style="float:left;overflow-y: auto;height: 400px;margin-top: 36px;">
					                    	<h4 style="padding: 36px;line-height: 28px;">
					                    		<!-- <?php echo $confirmationtext;?> -->
				                    						                    
					                    		<p> Hi <?php echo $_SESSION['rego']['name'] ;?>,  </p>
					                    		<p> You have given your consent to :  </p>
					                    		<ul>
					                    			<?php if($termsConsent == '1'){ ?>
		                    							<li>
						                    				Our terms and conditions on
						                    				<span style="margin-left: 76px;">:</span> 

						                    				<?php 
						                    					if($termsConsent == '1'){ ?>
						                    						<button  class="btn btn-primary btn-lg" type="button" ><span style="font-weight: 600;"><span style="font-weight: 600;"><?php echo $terms_consent_date;?></span>
						                    						</button>

						                    				<?php } ?>
							                    		</li>
							                    	<?php } ?>

							                    	<?php if($privacyConsent == '1'){ ?>
					                    			<li style="margin-top: 5px;">
					                    				Our Privacy policy on
					                    				<span style="margin-left: 148px;">:</span>
				                    					<?php 
					                    					if($privacyConsent == '1'){ ?>
					                    						<button  class="btn btn-primary btn-lg" type="button" ><span style="font-weight: 600;"> <span style="font-weight: 600;"><?php echo $privacy_consent_date;?></span>
					                    						</button>

					                    				<?php } ?>

					                    			</li>
					                    			<?php } ?>


					                    			<?php if($cookieConsent == '1'){ ?>
	                    							<li style="margin-top: 5px;">
					                    				Cookie settings confirmation on
					                    				<span style="margin-left: 50px;">:</span> 
					                    				<?php 
					                    					if($cookieConsent == '1'){ ?>
					                    						<button class="btn btn-primary btn-lg" type="button" ><span style="font-weight: 600;">  <span style="font-weight: 600;"><?php echo $cookie_consent_date;?></span>
					                    						</button>

					                    				<?php } ?>
					                    			</li>
					                    			<?php } ?>
					                    		</ul>

					                    		<p> You did not give your consent for : </p>
					                    		<ul>
					                    			<?php if($termsConsent != '1') {?>
	                    							<li>
					                    				Our terms and conditions on
					                    				<span style="margin-left: 76px;">:</span> 

					                    				<?php 

					                    				if($termsConsent != '1') {?>

					                    						<button  class="btn btn-danger btn-lg" type="button" ><span style="font-weight: 600;"> <span style="font-weight: 600;"><?php echo $terms_consent_date;?></span>
					                    						</button>	

					                    				<?php } ?>
						                    		</li>
						                    		<?php } ?>

						                    		<?php if($privacyConsent != '1'){?> 
					                    			<li style="margin-top: 5px;">
					                    				Our Privacy policy on
					                    				<span style="margin-left: 148px;">:</span>
				                    					<?php 
					                    					if($privacyConsent != '1'){?>

					                    						<button  class="btn btn-danger btn-lg" type="button" > <span style="font-weight: 600;"> <span style="font-weight: 600;"><?php echo $privacy_consent_date;?></span>
					                    						</button>	

					                    				<?php } ?>

					                    			</li>
					                    			<?php } ?>



					                    			<?php  if($cookieConsent != '1'){?>
	                    							<li style="margin-top: 5px;">
					                    				Cookie settings confirmation on
					                    				<span style="margin-left: 50px;">:</span> 
					                    				<?php 
					                    					 if($cookieConsent != '1'){?>

					                    						<button  class="btn btn-danger btn-lg" type="button" > <span style="font-weight: 600;"><span style="font-weight: 600;"><?php echo $cookie_consent_date;?></span>
					                    						</button>	

					                    				<?php } ?>
					                    			</li>
					                    			<?php } ?>
					                    		</ul>
					                    	</h4>	
											
											<br>


											<h4 style="padding: 36px;line-height: 28px;padding-top: 0px;">
												<?=$lng['We are sorry, but without the required']?> <b><?=$lng['cookie consent']?></b> <?=$lng['we can not provide you access to the software']?>.
											</h4>
										</div>


                        				<div style="margin-top: 30px;">
                
                        					<button  style="float: right;background: #080;border-color: #080;color: #fff;margin-right: 5px;" class="btn btn-lg " id="sendEmailToAdmin" type="button"><i style="font-size: 15px;" class="fa fa-check" aria-hidden="true"></i> Ok</button>

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

			

	    $('#privacyAgreee').click(function() {
	 		window.location.href = 'privacy_consent.php';
	    });

	    $('#sendEmailToAdmin').click(function() {


	    	$.ajax({
				url: "ajax/consent/sendEmailToAdminAboutConsent.php",
				success: function(result){

					// $("body").overhang({
					// 	type: "success",
					// 	message: '<i class="fa fa-check"></i>&nbsp;&nbsp;<?=$lng['Email sent successfully']?> . . .',
					// 	duration: 1,
					// })

					$.ajax({
						url: ROOT+"ajax/logout.php",
						success: function(result){
					
						setTimeout(function(){

							window.location.href = ROOT+'index.php';
							}, 1000);
						},
						error:function (xhr, ajaxOptions, thrownError){
							//alert(thrownError);
						}
					});

				}
			});


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













