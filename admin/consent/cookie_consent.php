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



	$res = $dba->query("SELECT * FROM rego_cookie_consent");
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


	$sql103 = "SELECT * FROM rego_users WHERE username = '".$_SESSION['RGadmin']['username']."'";

	if($res103 = $dba->query($sql103)){
		if($res103->num_rows > 0){
			if($row103 = $res103->fetch_assoc())
				{
					$cookie_consentValue = $row103['cookie_consent'];  
					$cookie_consent_change = $row103['cookie_consent_change'];  
						
				}
		}
	}

	if($cookie_consentValue == '1')
	{
		if($cookie_consent_change != '1')
		{
			header('location: '.AROOT.'consent/consent_detail.php');
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

					                <div style="display:  flex;"> <i style="font-size: 33px;" class="fa fa-user-secret" aria-hidden="true" ></i> &nbsp;&nbsp;  <h3><?=$lng['Cookie Consent']?></h3> </div>
					                    <div id="cookie_consent_data" style="float:left;overflow-y: auto;height: 387px;margin-top: 36px;">
					                    	<p> <?php echo $contentValue;?></p> </div>

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
						<button id="modalCancelConsentBtn" type="button" class="btn btn-primary btn-fr" data-dismiss="modal"><i class="fa fa-times"></i>&nbsp; <?=$lng['OK']?></button>
						<div class="clear"></div>
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

		// var url      = window.location.href; 

		// var result = url.split('cookie_consent.php?');

		// if(result[1] == 'open')
		// {
		// 	$('#modalConsent').modal('show');
		// }
	


		// var url      = window.location.href; 

		// var str1 = url;
		// var str2 = "admin";

		// if(str1.indexOf(str2) != -1){

		// 	window.location.href = 'cookie_consent.php';

		// }


		// var result = url.split('cookie_consent.php?');

		// if(result[1] == 'open')
		// {
		// 	$('#modalConsent').modal('show');
		// }
	


		// console.log(url);
		var hideElm = 'clicking here',
	    regex = new RegExp(hideElm, 'g');

		$('#cookie_consent_data').html(function(i, html){
		  return html.replace(regex, '<button id="choose_cookie" style="background: none;border: none;color: #0066CC;padding: 0px;">' + hideElm + '</button>');
		});


		$(document).on('click', '#choose_cookie', function(e) {

			$('#modalConsent').modal('show');

		})


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


		// I AGREE
		$(document).on('click', '.saveCookie', function(e) {

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
				url: "../ajax/consent/update_cookie_consent.php",
				data: {agreeValue:agreeValue,cookie_phpsessid: cookie_phpsessid,cookie_lang:cookie_lang,cookie_rego_lang:cookie_rego_lang,cookie_scanlang:cookie_scanlang,langchecked:langchecked,regolangchecked:regolangchecked  },

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

	



	</body>
</html>








