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





	$res = $dba->query("SELECT * FROM rego_terms_conditions");
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




	$sql102 = "SELECT * FROM rego_company_settings WHERE id = '1'";

	if($res102 = $dba->query($sql102)){
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
		if($res_updateotp = $dba->query($sql_updateotp)){
			if($row_updateotp = $res_updateotp->fetch_assoc()){

				$authenticated_code = $row_updateotp['authenticated_code'];

				if(!$authenticated_code)
				{
					$sql_upauth = "UPDATE rego_all_users SET 
					authenticated_code = '".$dba->real_escape_string($SixDigitRandomNumber)."'  WHERE username = '".$_SESSION['rego']['username']."' "; 
					$dba->query($sql_upauth);

					// send in email to the user 


					require '../PHPMailer/PHPMailerAutoload.php';
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


	// echo '<pre>';
	// print_r($_SESSION);
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
	
    /*margin-right: 0px!important;*/
    margin-top: 20px!important;
    margin-bottom: 30px!important;
    font-size: 11px!important;
        margin: 0 auto;
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
						<h4 class=""><?=$lng['Two-Factor Authentication (2FA)']?></h4>
					</div>
					<div class="divider-icon">
						<div><i class="fa fa-user-secret fa-lg"></i></div>
					</div>
					
						<fieldset>
							
							<div class="form-group">
								
				                <div id="wrapper" style="text-align: center;">
				                	<div style="margin-top: 10px;">
										<h4>
											<span id="msg" style="color: #0066CC;"> </span>										 
										</h4>
								    </div>

								  <div id="dialog">
								    <h3><?=$lng['Please enter the 6-digit verification code we sent via Email']?>:</h3>
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
			
			$('#msg').html('Please enter the code');
			$('#msg').css('color', 'red');
			setTimeout(function(){
				// location.href = ROOT+'index.php';
				$('#msg').html('');

			},2000);

			return false; 
		}

		$.ajax({
			url: "../ajax/consent/verify_authenticated_code.php",
			data: { box1: box1,box2:box2,box3:box3,box4:box4,box5:box5,box6:box6 },
			success: function(result){
				if(result == 'success'){

					$('#msg').html('Authenticated successfuly');
					$('#msg').css('color', '#0066CC');

					setTimeout(function(){
						// location.href = ROOT+'index.php';
						$('#msg').html('');

					},2000);

					setTimeout(function(){
						// location.href = '';
						window.location.href = 'https://<?php echo $_SERVER['SERVER_NAME'];?>/hr/mob';
					},1000);
				}
				
			},
		});

	});





	})


		function send_auth_code() {  

		$.ajax({
			url: "../ajax/consent/send_authenticated_code.php",
			success: function(result){
				if(result == 'success'){

					$('#msg').html('Authentication code sent successfully');
					$('#msg').css('color', '#0066CC');

					setTimeout(function(){
						// location.href = ROOT+'index.php';
						$('#msg').html('');

					},2000);
				}
			},

		});

	}


	
</script>	
</body>

</html>
