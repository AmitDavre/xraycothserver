<?php
	
	if(session_id()==''){session_start();}

	
	ob_start();
	include('../dbconnect/db_connect.php');

	// if(isset($_SESSION['rego']['timestamp']) && $_SESSION['rego']['timestamp'] == 0){

	// $sql103 = "UPDATE rego_all_users SET logged_in_status = NULL , authenticated = NULL WHERE username = '".$_SESSION['rego']['username']."'";

	// $res103 = $dbx->query($sql103);
	
	// 	unset($_SESSION['rego']);
	// }

	
	$username = '';
	$password = '';
	$remember = 0;
	if(isset($_COOKIE['log'])){
		$log = unserialize($_COOKIE['log']);
		if($log['remember'] == 1){
			$username = $log['user'];
			$password = $log['pass'];
			$remember = $log['remember'];
		}
	}
	//var_dump($log);
	//$brand = 'pkf';
	//$default_logo = 'images/pkf_people.png';
	//var_dump( hash('sha256', 'guest')); 


	// echo '<pre>';
	// print_r($_SESSION);
	// echo '</pre>';
	// die();
	$servername =  $_SERVER['SERVER_NAME'];


			function encrypt_decrypt($string, $action = 'encrypt')
	{
	    $encrypt_method = "AES-256-CBC";
	    $secret_key = 'AA74CDCC2BBRT935136HH7B63C27'; // user define private key
	    $secret_iv = '5fgf5HJ5g27'; // user define secret key
	    $key = hash('sha256', $secret_key);
	    $iv = substr(hash('sha256', $secret_iv), 0, 16); // sha256 is hash_hmac_algo
	    if ($action == 'encrypt') {
	        $output = openssl_encrypt($string, $encrypt_method, $key, 0, $iv);
	        $output = base64_encode($output);
	    } else if ($action == 'decrypt') {
	        $output = openssl_decrypt(base64_decode($string), $encrypt_method, $key, 0, $iv);
	    }
	    return $output;
	}
	





?>

<!DOCTYPE html>
<html lang="en">

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1, viewport-fit=cover" />
	<meta name="apple-mobile-web-app-capable" content="yes" />
	<meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
	<meta name="theme-color" content="#000000">
	<title><?=$www_title?></title>
	<link rel="icon" type="image/png" href="assets/img/favicon.png" sizes="32x32">
	<link rel="apple-touch-icon" sizes="180x180" href="assets/img/icon/192x192.png">
	<link rel="stylesheet" href="../assets/css/font-awesome.min.css">
	<link rel="stylesheet" href="../assets/css/line-awesome.min.css">
	<link rel="stylesheet" href="assets/css/style.css?<?=time()?>">
	<link rel="manifest" href="__manifest.json">
	<? if($brand == 'pkf'){ ?>
	<style>
		body {
			background: url(../images/login-final-client-small.png) no-repeat top +450px center;
			background-size: contain;
		}
	</style>
	<? } ?>
	
</head>

<body>

    <!-- loader -->
    <!--<div id="loader">
        <div class="spinner-border text-primary" role="status"></div>
    </div>-->
    <!-- * loader -->

    <!-- App Header -->
    <div class="appHeader bg-dark text-light">
        <div class="pageTitle"><?=$lng['Login to our secure server']?></div>
    </div>
    <!-- * App Header -->

    <!-- App Content -->
		<div class="container-fluid" style="xborder:1px solid red">
			<div class="row" style="xborder:1px solid green; padding:25px">
				<div class="col-12">
					<img style="height:40px" src="../<?=$default_logo?>">
					<? if($brand == 'pkf'){
						echo '<p style="color:#999">Collaborate, share and grow</p>';
						} ?>
					
					<div class="divider-icon">
						<div><i class="fa fa-lock fa-lg"></i></div>
					</div>
					
					<div id="logForm">	
						<form id="log_form">
							<div class="form-group">
								<label for="username"><?=$lng['Username']?></label>
								<input class="form-control" type="text" name="username" value="<?php if(isset($_COOKIE["username"])) { echo encrypt_decrypt($_COOKIE["username"], 'decrypt'); } ?>" id="username"/>
							</div>
							<div class="form-group">
								<label for="password"><?=$lng['Password']?></label>
								<input class="form-control" type="password" name="password" value="<?php if(isset($_COOKIE["password"])) { echo encrypt_decrypt($_COOKIE["password"], 'decrypt') ; } ?>" id="password"/>
							</div>
							<table border="0" style="margin-bottom:10px">
								<tr>
<!-- 									<td>
										<div class="custom-control custom-switch" style="xbackground:red; padding:0">
												<input <? if($remember == 1){echo 'checked';}?> type="checkbox" class="custom-control-input" name="remember" id="remember" value="1">
												<label class="custom-control-label" for="remember"></label>
										</div>
									</td>
									<td style="padding-left:8px; font-weight:500"><?=$lng['Remember me']?></td> -->
								</tr>
							</table>
							<button style="margin-bottom:6px" type="submit" class="btn btn-default btn-block"><?=$lng['Log-in']?> &nbsp;<i class="fa fa-sign-in fa-lg"></i></button>
							<button type="button" class="btn btn-info btn-block" id="forgot"><?=$lng['Forgot password']?></button>
							<div style="color:#a00; font-size:15px; display:none; margin-top:10px" id="logMsg"></div>
						</form>
						<h3 style="font-size: 11px;padding: 21px;padding-bottom: 0px;">You can activate 2FA in your account settings</h3>

					</div>
					
					<div id="forgotForm" style="display:none">
						<form id="forgotPassForm">
							<div class="form-group">
								<label for="username"><?=$lng['Username']?></label>
								<input class="form-control"type="text" name="femail" id="femail"/>
							</div>
							<button style="margin-bottom:6px" type="submit" class="btn btn-default btn-block"><?=$lng['Request new password']?></button>
							<button id="backLogin" type="button" class="btn btn-info btn-block"><?=$lng['Back to Login']?></button>
							<div style="color:#a00; line-height:140%; font-size:15px; display:none; margin-top:10px" id="forMsg"></div>
						</form>
					</div>
					
					<div style="height:250px"></div>
					
				</div>
			</div>
		</div>	
    <!-- * App Content -->
    
		<!-- App Bottom Menu -->
    <div class="fixBottomMenu" style="border:0">
				<? if($lang=='en'){ ?>
					<a href="#" data-lng="th" class="langbutton <? if($lang=='th'){echo 'activ';} ?>"><img src="../images/flag_th.png"></a>
				<? }else{ ?>
					<a href="#" data-lng="en" class="langbutton <? if($lang=='en'){echo 'activ';} ?>"><img src="../images/flag_en.png"></a>
				<? } ?>
    </div>
    <!-- * App Bottom Menu -->

    	<!-- Modal Error Popup -->
	<div class="modal fade" id="modalErroPopup" tabindex="-1" role="dialog" data-backdrop="static" data-keyboard="false">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header" style="background:#b00; color:#fff">
					<h5 class="modal-title"><i class="fa fa-exclamation-triangle"></i>&nbsp; <?=$lng['Error Message']?></h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body" style="padding:20px 25px 25px 25px">
					<p>You are currently logged in as a user on this platform already. Please close the current session first before logging into a new session or as a different user</p>
		
					</div>
			  </div>
		 </div>
	</div>



    <!-- ///////////// Js Files ////////////////////  -->
    <!-- Jquery -->
    <script src="assets/js/lib/jquery-3.4.1.min.js"></script>
    <!-- Bootstrap-->
    <script src="assets/js/lib/popper.min.js"></script>
    <script src="assets/js/lib/bootstrap.min.js"></script>
    <!-- Owl Carousel -->
    <!--<script src="assets/js/plugins/owl-carousel/owl.carousel.min.js"></script>-->
    <!-- jQuery Circle Progress -->
    <!--<script src="assets/js/plugins/jquery-circle-progress/circle-progress.min.js"></script>-->
    <!-- Base Js File -->
    <script src="assets/js/base.js?<?=time()?>"></script>

	<script type="text/javascript">
		
	$(document).ready(function() {

		var serverNameVal =  '<?php echo $servername ?>';
		console.log(serverNameVal);
		
		$("#log_form").submit(function (e) {
			e.preventDefault();

			$('#logMsg').hide();
			$('#forMsg').hide();
			if($('input[name="username"]').val() == '' || $('input[name="password"]').val() == ''){
				$('#logMsg').html('<i class="fa fa-exclamation-circle"></i>&nbsp; <?=$lng['Please fill in required fields']?></div>').fadeIn(200);
				return false;
			}
			var formData = $(this).serialize();
			$.ajax({
				url: "ajax/ajax_login.php",
				data: formData,
				success: function(response){
					//$("#dump").html(response); return false;
					//alert(response)
					if($.trim(response) == 'wrong'){
						$('#logMsg').html('<i class="fa fa-exclamation-circle"></i>&nbsp; <?=$lng['Wrong Username or Password']?></div>').fadeIn(200);
					}
					if($.trim(response) == 'suspended'){
						$('#logMsg').html('<i class="fa fa-exclamation-circle"></i>&nbsp; <?=$lng['This user is suspended']?></div>').fadeIn(200);
					}
					if($.trim(response) == 'emp'){
						$('#logMsg').html('<i class="fa fa-exclamation-circle"></i>&nbsp; <?=$lng['You have no access to this platform']?></div>').fadeIn(200);
					}	
		
					if($.trim(response) == 'success'){
						// location.href = 'index.php';
						window.location ='https://'+serverNameVal+'/hr/mob';
					}
					// if($.trim(response) == 'session'){
					// 	$('#modalErroPopup').modal('show');
					// }
				},
				error:function (xhr, ajaxOptions, thrownError){
					alert('<?=$lng['Error']?> : '+thrownError);
				}
			});
		});
		
		$("#forgotPassForm").submit(function(e){
			e.preventDefault();
			if($('#femail').val()==''){$("#forMsg").html('<i class="fa fa-exclamation-circle"></i>&nbsp; <?=$lng['Please fill in required fields']?>').fadeIn(200); return false;}
			$("#forMsg").html('<i class="fa fa-refresh fa-spin"></i>&nbsp; One moment please ...').fadeIn(200);
			//return false;

			//alert($('#rpass').val())
			var formData = $(this).serialize();
			
			$.ajax({
				url: "ajax/forgot_password.php",
				dataType: "text",
				data: formData,
				success: function(response){
					//alert(response)
					setTimeout(function(){
						if(response == "suspended"){
							$('#forMsg').html("<i class='fa fa-exclamation-circle'></i>&nbsp; We have not found your email address in our database<? //=$lng['We have not found your email address in our database']?></div>").fadeIn(200);
						}else if(response == 'success'){
							$("#forMsg").html('<i class="fa fa-exclamation-circle"></i>&nbsp; <?=$lng['New password send to your email address']?>').fadeIn(200);
						}else{
							$("#forMsg").html('<i class="fa fa-exclamation-circle"></i>&nbsp; '+response).fadeIn(200);
						}
					},1000);
				},
				error:function (xhr, ajaxOptions, thrownError){
					$("#forMsg").html('<i class="fa fa-exclamation-circle"></i>&nbsp; <?=$lng['Error']?> : '+thrownError).fadeIn(200);
				}
			});
		});

		$('#forgot').click(function(){
			$('#logMsg').hide();
			$('#forMsg').hide();
			$('#logTitle').html('<?=$lng['Request new password']?>');
			$('#logForm').slideUp(200);
			$('#forgotForm').slideDown(200);
		})
		$('#backLogin').click(function(){
			$('#logMsg').hide();
			$('#forMsg').hide();
			$('#forgotForm').slideUp(200);
			$('#logForm').slideDown(200);
			$('#logTitle').html('<?=$lng['Login to our secure server']?>');
		})
		
		$('.langbutton').on('click', function(){
			$.ajax({
				url: "ajax/change_lang.php",
				data: {lng: $(this).data('lng')},
				success: function(ajaxresult){
					location.reload();
				}
			});
		});

	})
		
	</script>	

</body>

</html>
