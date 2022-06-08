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

	if(!isset($_GET['mn'])){$_GET['mn'] = 2;}

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





	// check in rego all users table for the consent 
		$sql_consent = "SELECT * FROM rego_all_users WHERE username = '".$_SESSION['rego']['username']."'";
		if($res_consent = $dba->query($sql_consent)){
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
		if($res_consent_confirmation = $dba->query($sql_consent_confirmation)){
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


    <!-- App Header -->
    <div class="appHeader text-light">
        <div class="pageTitle"><?=$compinfo[$lang.'_compname']?></div>
    </div>
    <!-- * App Header -->
		<div class="container-fluid" style="xborder:1px solid red">
			<div class="row" style="xborder:1px solid green; padding:20px 25px">
				<div class="col-12">
					<div class="page-header">
						<h4 class=""><?=$lng['Confirmation Text']?></h4>
					</div>
					<div class="divider-icon">
						<div><i class="fa fa-user-secret fa-lg"></i></div>
					</div>
					
						<fieldset>
							
							<div class="form-group">
								<p> Hi <?php echo $_SESSION['rego']['name'] ;?>,  </p>
	                    		<p> You have given your consent to :  </p>
	                    		<ul>
	                    			<?php if($termsConsent == '1'){ ?>
            							<li>
		                    				Our terms and conditions on
		                    				<span style="margin-left: ;">:</span> 

		                    				<?php 
		                    					if($termsConsent == '1'){ ?>
		                    						<button  class="btn btn-primary btn-sm" type="button" ><span style="font-weight: 600;"><span style="font-weight: 600;"><?php echo $terms_consent_date;?></span>
		                    						</button>

		                    				<?php } ?>
			                    		</li>
			                    	<?php } ?>

			                    	<?php if($privacyConsent == '1'){ ?>
	                    			<li style="margin-top: 5px;">
	                    				Our Privacy policy on
	                    				<span style="margin-left: ;">:</span>
                    					<?php 
	                    					if($privacyConsent == '1'){ ?>
	                    						<button  class="btn btn-primary btn-sm" type="button" ><span style="font-weight: 600;"> <span style="font-weight: 600;"><?php echo $privacy_consent_date;?></span>
	                    						</button>

	                    				<?php } ?>

	                    			</li>
	                    			<?php } ?>


	                    			<?php if($cookieConsent == '1'){ ?>
        							<li style="margin-top: 5px;">
	                    				Cookie settings confirmation on
	                    				<span style="margin-left: ;">:</span> 
	                    				<?php 
	                    					if($cookieConsent == '1'){ ?>
	                    						<button class="btn btn-primary btn-sm" type="button" ><span style="font-weight: 600;">  <span style="font-weight: 600;"><?php echo $cookie_consent_date;?></span>
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
	                    				<span style="margin-left: ;">:</span> 

	                    				<?php 

	                    				if($termsConsent != '1') {?>

	                    						<button  class="btn btn-danger btn-sm" type="button" ><span style="font-weight: 600;"> <span style="font-weight: 600;"><?php echo $terms_consent_date;?></span>
	                    						</button>	

	                    				<?php } ?>
		                    		</li>
		                    		<?php } ?>

		                    		<?php if($privacyConsent != '1'){?> 
	                    			<li style="margin-top: 5px;">
	                    				Our Privacy policy on
	                    				<span style="margin-left: ;">:</span>
                    					<?php 
	                    					if($privacyConsent != '1'){?>

	                    						<button  class="btn btn-danger btn-sm" type="button" > <span style="font-weight: 600;"> <span style="font-weight: 600;"><?php echo $privacy_consent_date;?></span>
	                    						</button>	

	                    				<?php } ?>

	                    			</li>
	                    			<?php } ?>



	                    			<?php  if($cookieConsent != '1'){?>
        							<li style="margin-top: 5px;">
	                    				Cookie settings confirmation on
	                    				<span style="margin-left: ;">:</span> 
	                    				<?php 
	                    					 if($cookieConsent != '1'){?>

	                    						<button  class="btn btn-danger btn-sm" type="button" > <span style="font-weight: 600;"><span style="font-weight: 600;"><?php echo $cookie_consent_date;?></span>
	                    						</button>	

	                    				<?php } ?>
	                    			</li>
	                    			<?php } ?>
	                    		</ul>

							</div>		

							<div class="form-group">
					            <label>
		                   			<?=$lng['We are sorry, but without the required']?> <b><?=$lng['cookie consent']?></b> <?=$lng['we can not provide you access to the software']?>.
		                        </label>

							</div>						

							<div class="form-group">
					            <label>
		                   			<button  id="sendEmailToAdmin" style="font-size:16px;margin-bottom: 40px;" type="button" class="disagreeTerms btn btn-default	 btn-block"><?=$lng['OK']?></button>

		                        </label>

							</div>
					
							
						</fieldset>
						
				</div>
			</div>
		</div>	

    <!-- App Bottom Menu -->
    <div class="appBottomMenu text-light">
        <a href="#" class="item logout">
            <div class="col"><i class="fa fa-sign-out fa-lg"></i></div>
        </a>
				<a href="#" class="item"  style="border-left:1px solid #666">
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
	
	$(document).ready(function() {


		
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


		$('#sendEmailToAdmin').click(function() {


	    	$.ajax({
				url: "../ajax/consent/sendEmailToAdminAboutConsent.php",
				success: function(result){

					$.ajax({
						url: "../ajax/logout.php",
						success: function(result){
					
						setTimeout(function(){

							window.location.href = 'index.php';
							}, 1000);
						},
						error:function (xhr, ajaxOptions, thrownError){
							//alert(thrownError);
						}
					});

				}
			});


	    });




	})

	
</script>	
</body>

</html>
