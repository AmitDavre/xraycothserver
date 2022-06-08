<?
	if(session_id()==''){session_start();}
	ob_start();
	include('dbconnect/db_connect.php');
	//include('../files/functions.php');
	//var_dump(hash('sha256', 'Berne123')); exit;
	//var_dump('1de5d3be55f595c1dd4412c5d3264b6b8b71190e69142adb1fda2d2b12c39d82');exit;
	$err_msg = "";
	if(isset($_SESSION['RGadmin']['timestamp']) && $_SESSION['RGadmin']['timestamp'] == 0){
		$err_msg = '<div class="msg_alert nomargin">'.$lng['Logtime expired'].'</div>';
		$sql103 = "UPDATE rego_users SET logged_in_status = NULL , authenticated = NULL WHERE username = '".$_SESSION['RGadmin']['username']."'";
		$res103 = $dba->query($sql103);

		unset($_SESSION['RGadmin']);
	}




		// unset($_COOKIE['admin']);
	
	$adminLoginCheck = $_SESSION['adminLoginCheck'];
	$userLogincheck = $_SESSION['userLogincheck'];
	// echo '<pre>';
	// print_r($userLogincheck);
	// echo '</pre>';
	// die();

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
<html lang="en-us">
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, maximum-scale=1">
		<meta name="robots" content="noindex, nofollow">
		<title><?=$www_title?></title>
	
		<link rel="shortcut icon" href="../images/favicon.ico" type="image/x-icon">
		<link rel="icon" href="../images/favicon.ico" type="image/x-icon">
		
		<link rel="stylesheet" href="../assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="../assets/css/font-awesome.min.css">
    <link rel="stylesheet" href="../assets/css/login_<?=$brand?>.css?<?=time()?>">
		<link rel="stylesheet" href="../assets/css/myBootstrap.css?<?=time()?>">

		<script src="../assets/js/jquery-3.2.1.min.js"></script>
		<script src="../assets/js/popper.min.js"></script>
		<script src="../assets/js/bootstrap.min.js"></script>

	</head>
	
	<body class="xbody_logo">
		
		<div id="brand_logo">
			<? if($lang=='en'){ ?>
				<a style="margin:0px 0 0 0" data-lng="th" class="langbutton <? if($lang=='th'){echo 'activ';} ?>"><img src="../images/flag_th.png"></a>
			<? }else{ ?>
				<a style="margin:0px 0 0 0" data-lng="en" class="langbutton <? if($lang=='en'){echo 'activ';} ?>"><img src="../images/flag_en.png"></a>
			</td>
			<? } ?>
		</div>
		
		<div class="header">
			<table width="100%" border="0"><tr>
				<td>
					<img style="margin:0 0 3px 15px; height:40px;" src="../<?=$default_logo?>?<?=time()?>">
				</td>
				<td style="width:95%"></td>
				<td style="padding-right:20px">
				<? if($lang=='en'){ ?>
					<a style="margin:0px 0 0 0" data-lng="th" class="langbutton <? if($lang=='th'){echo 'activ';} ?>"><img src="../images/flag_th.png"></a>
				<? }else{ ?>
					<a style="margin:0px 0 0 0" data-lng="en" class="langbutton <? if($lang=='en'){echo 'activ';} ?>"><img src="../images/flag_en.png"></a>
				</td>
				<? } ?>
			</tr></table>
		</div>
		
		<!--<br><br><br><div id="dump"></div>-->
		<div style="padding-top:12vh; xborder:1px solid red">
			<div class="brand">
				<img src="../images/pkf_people.png">
				<p>Collaborate, share and grow</p>
			</div>
			
			
		<div class="logform">
			<h2 style="background:#007700; border-radius:3px 3px 0 0"><i class="fa fa-lock"></i> &nbsp;<?=$lng['Login to our secure server']?></h2>
			<div class="logform-inner">
				<div id="logMsg" style="color:#b00; font-weight:600; font-size:14px; display:none"></div>
				
				<div id="login">
					<form id="logForm">
						<label><?=$lng['Username']?> <i class="man"></i></label>
						<input name="username" type="text" autocomplete="username" value="<?php if(isset($_COOKIE["username"])) { echo encrypt_decrypt($_COOKIE["username"], 'decrypt'); } ?>" />
						<label><?=$lng['Password']?> <i class="man"></i></label>
						<input name="password" type="password" autocomplete="current-password" value="<?php if(isset($_COOKIE["password"])) { echo encrypt_decrypt($_COOKIE["password"], 'decrypt') ; } ?>" />
			<!-- 			<label>
                          <input id="login-remember" type="checkbox" name="remember" value="1"> Remember me
                        </label> -->
						<div style="height:15px"></div>
						<button type="submit" class="btn btn-primary"><i class="fa fa-sign-in"></i>&nbsp; <?=$lng['Log-in']?></button>
						<? if($brand == 'rego'){ ?>
						<button id="togglediv" style="float:right; margin-top:7px" type="button" class="btn btn-outline-secondary btn-sm"><?=$lng['Forgot password']?></button>
						<? }else{ ?>
						<button id="togglediv" style="float:right;" type="button" class="btn btn-primary"><?=$lng['Forgot password']?></button>
						<? } ?>
					</form>
					<div><h3 style="font-size: 14px;padding: 21px;padding-bottom: 0px;font-weight: 600;">You can activate 2FA in your account settings</h3></div>

				</div>
				
				<div id="forgot" style="display:none">
					<form id="forgotForm">
						 <label><?=$lng['Username']?> <i class="man"></i></label>
						 <input name="forgot_username" type="text" autocomplete="username" value="" />
						 <div style="height:15px"></div>
						 <button class="btn btn-primary" type="submit"><i class="fa fa-paper-plane"></i>&nbsp; <?=$lng['Request new password']?></button>
						<? if($brand == 'rego'){ ?>
						 <button style="float:right; margin-top:7px" id="togglediv2" class="btn btn-outline-secondary btn-sm" type="button"><?=$lng['Log-in']?></button>
						<? }else{ ?>
						 <button style="float:right;" id="togglediv2" class="btn btn-primary" type="button"><?=$lng['Log-in']?></button>
						<? } ?>
					</form>
					<div style="clear:both"></div>
				</div>

			</div>
		</div>
		</div>

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

		
	
<script type="text/javascript">
		
	$(document).ready(function() {

			var adminLoginCheck = '<?php echo $_SESSION['adminLoginCheck']?>';
			var userLoginCheck = "<?php echo $_SESSION['userLogincheck']?>";





		
		$("#forgotForm").submit(function (e) {
			e.preventDefault();
			$('#logMsg').hide();
			if($('input[name="forgot_username"]').val() == ''){
				$('#logMsg').html('Please fill in required fields').fadeIn(200);
				return false;
			}
			var formData = $(this).serialize();
			$.ajax({
				url: "ajax/admin_forgot_password.php",
				data: formData,
				success: function(response){
					//$("#dump").html(response); return false;
					if($.trim(response) == 'success'){
						$('#logMsg').html('Please check your email for new password').fadeIn(200);
						$('input[name="password"]').val('')
						setTimeout(function(){
							$('#logMsg').hide();
							$("#forgot").slideUp(200);
							$("#login").slideDown(200);
						}, 5000);
					}
				},
				error:function (xhr, ajaxOptions, thrownError){
					alert('Error : '+thrownError);
				}
			});
		});
		
		$("#logForm").submit(function (e) {
			e.preventDefault();


			if(adminLoginCheck == 'check' || userLoginCheck == 'check')
			{
				// set admin as logged in cookie for mobile 

				document.cookie = "mobloggedcheck=check"; 

			}



			$('#logMsg').hide();
			if($('input[name="username"]').val() == '' || $('input[name="password"]').val() == ''){
				$('#logMsg').html('Please fill in required fields').fadeIn(200);
				return false;
			}
			var formData = $(this).serialize();
			$.ajax({
				url: "ajax/ajax_admin_login.php",
				data: formData,
				success: function(response){
					//$("#dump").html(response); //return false;
					//alert(response)
					if($.trim(response) == 'wrong'){
						$('#logMsg').html('Wrong username or password').fadeIn(200);
					}else if($.trim(response) == 'suspended'){
						$('#logMsg').html('This user is suspended').fadeIn(200);
					}else if($.trim(response) == 'session'){
						 $('#modalErroPopup').modal('show');

					}else{
						// add cookies by jquery for mobile login check 

						
						window.location.href = response;
					}
				},
				error:function (xhr, ajaxOptions, thrownError){
					$('#logMsg').html('Error : ' + thrownError).fadeIn(200);
				}
			});
		});
		
		$('.langbutton').on('click', function(){
			$.ajax({
				url: "ajax/change_lang.php",
				data: {lng: $(this).data('lng')},
				success: function(ajaxresult){
					location.reload();
				}
			});
		});
		
		$('#togglediv').click(function(){
			$('#logMsg').hide();
			$('#login').slideUp(200);
			$('#forgot').slideDown(200);
		})
		$('#togglediv2').click(function(){
			$('#logMsg').hide();
			$('#forgot').slideUp(200);
			$('#login').slideDown(200);
		})


		// var x = document.cookie; 

		// console.log(x[adminlogCheck]);
		
	})
		

</script>

	</body>
</html>










