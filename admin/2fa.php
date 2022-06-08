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

	}
	else
	{
			header('location: admin_login.php');

	}

	if(empty($compinfo['complogo'])){
		$compinfo['complogo'] = ROOT.'images/rego_logo.png';
	}
	
	// LOGOUT CUSTOMER ///////////////////////////////////////////////////////////////////
	unset($_SESSION['rego']);

	if(!isset($_GET['mn']) && $logger == true){$_GET['mn'] = 2;}
	if(!isset($_GET['mn'])){$_GET['mn'] = 1;}



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
		$sql_updateotp = "SELECT * FROM rego_users WHERE username = '".$_SESSION['RGadmin']['username']."'";
		if($res_updateotp = $dba->query($sql_updateotp)){
			if($row_updateotp = $res_updateotp->fetch_assoc()){

				$authenticated_code = $row_updateotp['authenticated_code'];

				if(!$authenticated_code)
				{
					$sql_upauth = "UPDATE rego_users SET 
					authenticated_code = '".$dba->real_escape_string($SixDigitRandomNumber)."'  WHERE username = '".$_SESSION['RGadmin']['username']."' "; 
					$dba->query($sql_upauth);

					// send in email to the user 


					require '../PHPMailer/PHPMailerAutoload.php';
					$body = "<html>
							<head>
							<meta http-equiv='Content-Type' content='text/html; charset=utf-8' />
							</head>
							<body style='font-size:16px'>
								<p>Dear ".$_SESSION['RGadmin']['name'].",</p>
								<p>Your 6 digit authentication code is <b>".$SixDigitRandomNumber."</b><br>
								</p>
								<p>Kind regards,<br>".strtoupper($client_prefix)." Admin</p>
							</body></html>";

					$mail = new PHPMailer;
					$mail->CharSet = 'UTF-8';
					$mail->From = $admin_mail_value;
					$mail->FromName = strtoupper($client_prefix).' ผู้ดูแลระบบ (Admin)';
					$mail->addAddress($_SESSION['RGadmin']['username']);     // Add a recipient
					$mail->isHTML(true);                                  
					$mail->Subject = '6 Digit Code';
					$mail->Body = $body;
					$mail->WordWrap = 100;                                
					$mail->send();




				}

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

						                <div style="display:  flex;"> <i style="font-size: 33px;" class="fa fa-key" aria-hidden="true" ></i> &nbsp;&nbsp;  <h3><?=$lng['Two-Factor Authentication (2FA)']?></h3> </div>
						                    <div style="float:left;height: 310px;margin-top:80px!important;margin: 0 auto">
						                    	<!-- <h4> A 6 Digit code has been sent to your email address. Please enter the code and submit. </h4> -->

								                <div id="wrapper" style="text-align: center;">
												  <div id="dialog">
												    <h3><?=$lng['Please enter the 6-digit verification code we sent via Email']?> : </h3>
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
														  <a style="color: #0066CC;" onclick="send_auth_code();"><?=$lng['Send code again']?> </a><br />
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


	<!-- data here  -->
	

	
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


	<script type="text/javascript">
		// save script here 

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
						location.href = AROOT+'index.php';
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

	



	</body>
</html>








