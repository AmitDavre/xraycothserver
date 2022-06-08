	<?
	if(session_id()==''){session_start();}
	ob_start();
	include('../files/admin_functions.php');
	include('../dbconnect/db_connect.php');
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

	}else
	{
			header('location: ../admin_login.php');

	}

	if(empty($compinfo['complogo'])){
		$compinfo['complogo'] = ROOT.'images/rego_logo.png';
	}
	
	// LOGOUT CUSTOMER ///////////////////////////////////////////////////////////////////
	unset($_SESSION['rego']);

	if(!isset($_GET['mn']) && $logger == true){$_GET['mn'] = 2;}
	if(!isset($_GET['mn'])){$_GET['mn'] = 1;}


	// check in rego all users table for the consent 
		$sql_consent = "SELECT * FROM rego_users WHERE username = '".$_SESSION['RGadmin']['username']."'";
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




?>

<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">
		<meta name="robots" content="noindex, nofollow">
		<title><?=$www_title?></title>
	
		<link rel="icon" type="image/png" sizes="192x192" href="../../assets/images/192x192.png">
		<link rel="icon" type="image/png" sizes="32x32" href="../assets/images/favicon-32x32.png">
		<link rel="icon" type="image/png" sizes="96x96" href="../assets/images/favicon-96x96.png">
		<link rel="icon" type="image/png" sizes="16x16" href="../assets/images/favicon-16x16.png">
		
		<link rel="stylesheet" href="../../assets/css/bootstrap.min.css">
	    <link rel="stylesheet" href="../../assets/css/font-awesome.min.css">
	    <link rel="stylesheet" href="../../assets/css/line-awesome.min.css">
		<link rel="stylesheet" href="../../assets/css/myStyle.css?<?=time()?>">
		<link rel="stylesheet" href="../../assets/css/dataTables.bootstrap4.min.css?<?=time()?>">
		<link rel="stylesheet" href="../../assets/css/myDatatables.css?<?=time()?>">
		<link rel="stylesheet" href="../../assets/css/navigation.css?<?=time()?>">
		<link rel="stylesheet" href="../../assets/css/bootstrap-datepicker.css?<?=time()?>" />
		<link rel="stylesheet" href="../../assets/css/myBootstrap.css?<?=time()?>">
		<link rel="stylesheet" href="../../assets/css/basicTable.css?<?=time()?>">
		<link rel="stylesheet" href="../../assets/css/myForm.css?<?=time()?>">
		<link rel="stylesheet" href="../../assets/css/overhang.min.css?<?=time()?>">
		<link rel="stylesheet" href="../../assets/css/sumoselect.css?<?=time()?>">
		<link rel="stylesheet" href="../../assets/css/responsive.css?<?=time()?>">
		<link rel="stylesheet" href="../../assets/css/autocomplete.css?<?=time()?>">



		<link rel="stylesheet" href="../../assets/css/sumoselect.css?<?=time()?>">
		<link rel="stylesheet" href="../../assets/css/autocomplete.css?<?=time()?>">
		<link rel="stylesheet" href="../../assets/css/main.css?<?=time()?>">




		<script src="../../assets/js/jquery-3.2.1.min.js"></script>
		<script src="../../assets/js/jquery-ui.min.js"></script>
		<script src="../../assets/js/moment.min.js"></script>
		<script src='../../assets/js/moment-duration-format.min.js'></script>




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
					<img style="margin:5px 7px 5px 0; height:40px;" src="../../<?=$default_logo?>?<?=time()?>" />
				</td>
				<td style="white-space:nowrap; vertical-align:middle; padding-left:5px">
					<b style="font-family:'Roboto Condensed'; font-weight:400; font-size:24px; color:#333;"><?=$lng['Admin Platform']?></b>
				</td>
				<td style="width:95%"></td>
				<td>
				<? if($lang=='en'){ ?>
					<a data-lng="th" class="langbutton admin_lang <? if($lang=='th'){echo 'activ';} ?>"><img src="../../images/flag_th.png"></a>
				<? }else{ ?>
					<a data-lng="en" class="langbutton admin_lang <? if($lang=='en'){echo 'activ';} ?>"><img src="../../images/flag_en.png"></a>
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
			
		</div>
	<? } ?>
	<div id="dump"></div>


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
					          					<p> Hi <?php echo $_SESSION['RGadmin']['name'] ;?>,  </p>
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
                
                        					<button  style="float: right;background: #080;border-color: #080;color: #fff;margin-right: 5px;" class="btn btn-lg " id="sendEmailToAdmina" type="button"><i style="font-size: 15px;" class="fa fa-check" aria-hidden="true"></i> <?=$lng['OK']?></button>

                        				</div>
					            </div>
					        </div>
					    </div>
					</div>

	     
	            </div>
	        </div>
	    </div>
	</div>

	<!-- data here  -->
	

	
	<script src="../../assets/js/popper.min.js"></script>
	<script src="../../assets/js/bootstrap.min.js"></script>
	<script src="../../assets/js/jquery.dataTables.min.js"></script>
	<script src="../../assets/js/dataTables.bootstrap4.min.js"></script>
	<script src="../../assets/js/bootstrap-datepicker.min.js"></script>
	<script src="../../assets/js/bootstrap-datepicker.th.js"></script>
	<script src="../../assets/js/bootstrap-confirmation.js"></script>
	<script src="../../assets/js/jquery.numberfield.js"></script>	
	<script src="../../assets/js/jquery.mask.js"></script>	
	<script src="../../assets/js/overhang.min.js?<?=time()?>"></script>
	<script src="../../assets/js/rego.js"></script>
	<script src="../../assets/js/jquery.sumoselect.min.js"></script>

	<script src="../../assets/js/jquery.flicker.js?<?=time()?>"></script>
	<script src="../../assets/js/jquery.autocomplete.js"></script>

	<script src='../../assets/js/fullcalendar.js'></script>
	<script src='../../assets/js/main.js'></script>



	<script type="text/javascript">
		// save script here 

		$('.admin_lang').on('click', function(){
			//alert()
			$.ajax({
				url: "../ajax/change_lang.php",
				data: {lng: $(this).data('lng')},
				success: function(ajaxresult){
					//alert(ajaxresult);
					location.reload();
				}
			});
		});
		
		$(".alogout").click(function(){ 
			$.ajax({
				url: "../ajax/logout.php",
				success: function(result){
					//alert(result)
					location.reload();
				},
				error:function (xhr, ajaxOptions, thrownError){
					alert(thrownError);
				}
			});
		});

		$("#sendEmailToAdmina").click(function(){ 


				 $.ajax({
					url: "../ajax/consent/sendEmailToAdminAboutConsent.php",
					success: function(result){

						$('.alogout').click();

				}
			});


		});


		// I AGREE
		$(document).on('click', '.saveCookie', function(e) {

			var agreeValue = 'yes';
			$.ajax({
				url: "../ajax/consent/update_cookie_consent.php",
				data: {agreeValue:agreeValue},
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

			var agreeValue = 'no';
			$.ajax({
				url: "../ajax/consent/update_cookie_consent.php",
				data: {agreeValue:agreeValue},
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
	        } else {
	            $('#cookieAgree').attr('disabled', 'disabled');

	        }
	    });

	</script>
	</body>
</html>








