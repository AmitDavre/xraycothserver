<?php
	
	if(session_id()==''){session_start();}
	include('db_connect.php');
	//var_dump(hash('sha256', 'AG01001')); exit;
	
	$username = '';
	$password = '';
	$remember = 0;
	if(isset($_COOKIE['aglog'])){
		$log = unserialize($_COOKIE['aglog']);
		if($log['remember'] == 1){
			$username = $log['user'];
			$password = $log['pass'];
			$remember = $log['remember'];
		}
	}
	//$username = 'AG01001';
	//$password = 'AG01001';
?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>REGO HR</title>
	<link rel="shortcut icon" type="image/x-icon" href="favicon.ico">
	<link href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700" rel="stylesheet" type="text/css" />
	<link href="assets/css/bootstrap.min.css" rel="stylesheet" type="text/css">
	<link href="assets/css/font-awesome.min.css" rel="stylesheet" type="text/css">
	<link href="assets/css/style.css?<?=time()?>" rel="stylesheet">
	<link href="assets/css/mobStyle.css?<?=time()?>" rel="stylesheet">
	
</head>
<style>
	.langbutton {
		position:fixed;
		bottom:25px;
		right:25px;
		cursor:pointer;
	}
	form label {
		font-weight:600;
	}
</style>

<body style="">

	<div class="header dashboard_header" style="background: rgba(0,0,0,0.7)">
		<?=$lng['REGO HR Mobile Platform']?>
	</div>			

	<div style="position:absolute; top:0; bottom:0; left:0; right:0; padding-top:80px;background:url(../images/mob_bg.jpg?123) no-repeat center center; background-size:cover;">

		<div style="background:rgba(255,255,255,0.9); min-width:300px; max-width:90%; margin:0 auto; padding:60px 30px 20px; border-radius: 5px; box-shadow: 0 0 20px rgba(0, 0, 0, 0.5); position:relative">
			
			<div style="position:absolute; top:0; left:0; right:0; border-radius:5px 5px 0 0; background:#900; color:#fff; text-align:center; line-height:45px; font-size:18px" id="logTitle"><?=$lng['Login to our secure server']?></div>
			
			<!-- LOG IN -->
			<div id="logForm">	
				<form id="log_form" style="padding:0 0 10px 0">
					
					<label>Agent ID<? //=$lng['Username']?> <i class="man"></i></label>
					<input name="username" type="text" value="<?=$username?>">
					
					<label><?=$lng['Password']?> <i class="man"></i></label>
					<input name="password" type="password" value="<?=$password?>">
					
					<div style="height:5px"></div>
					<label class="control control-solid control-solid-danger control--checkbox"><?=$lng['Remember me']?>
						<input type="checkbox" <? if($remember == 1){echo 'checked';}?> name="remember" value="1" />
						<span class="control__indicator"></span>
					</label>
					
					<button type="submit" class="btn btn-success btn-lg btn-block tac" style="margin-top:10px; font-weight:400; letter-spacing:1px"><?=$lng['Log-in']?> &nbsp;<i class="fa fa-sign-in fa-lg"></i></button>
					
					<button id="forgot" type="button" class="btn btn-info btn-lg btn-block tac" style="margin-top:10px; font-weight:400"><?=$lng['Forgot password']?></button>
					
					<div id="logMsg" style="color:#d00; font-weight:600; text-align:center; display:none; margin-top:10px;"></div>
		
				</form>
			</div>
			<!-- /LOG IN -->
			
			<!-- FIRST VISIT -->
			<div id="firstForm" class="sform" style="display:none">
				<form id="firstPassForm" style="padding:0 0 10px 0">
					 <input name="agent_id" type="hidden" />
					 <label><?=$lng['Password']?> <i class="man"></i></label>
					 <input name="npassword" type="password" autocomplete="new-password" />
					 <label>Repeat <?=$lng['Password']?> <i class="man"></i></label>
					 <input name="rpassword" type="password" autocomplete="new-password" />
					 
					<button type="submit" class="btn btn-success btn-lg btn-block tac" style="margin-top:10px; font-weight:400">Change password<? //=$lng['Request new password']?></button>
					
					<div id="firstMsg" style="color:#d00; font-weight:600; text-align:center; display:none; margin-top:10px;"></div>

				</form>
			</div>
			<!-- /FIRST VISIT -->
			
			<!-- FORGOT PASSWORD -->
			<div id="forgotForm" style="display:none">
				<form id="forgotPassForm" style="padding:0 0 10px 0">
					<label>Agent ID<? //=$lng['Username']?> <i class="man"></i></label>
					<input name="femail" id="femail" type="text" />
					
					<button type="submit" class="btn btn-success btn-lg btn-block tac" style="margin-top:10px; font-weight:400"><?=$lng['Request new password']?></button>
					
					<button id="backLogin" type="button" class="btn btn-info btn-lg btn-block tac" style="margin-top:10px; font-weight:400"><?=$lng['Back to Login']?></button>
					
					<div style="color:#a00; font-size:15px; font-weight:500; display:none; margin-top:10px" id="forMsg"></div>
					
				</form>
			</div>
			<!-- /FORGOT PASSWORD -->
			
			<div id="dump"></div>
		</div>

		<? if($lang=='en'){ ?>
			<a data-lng="th" class="langbutton <? if($lang=='th'){echo 'activ';} ?>"><img height="50px" src="../images/flag_th.png"></a>
		<? }else{ ?>
			<a data-lng="en" class="langbutton <? if($lang=='en'){echo 'activ';} ?>"><img height="50px" src="../images/flag_en.png"></a>
		<? } ?>
	
	</div>
	
	<script type="text/javascript" src="assets/js/jquery.min.js"></script>
	<script type="text/javascript" src="assets/js/popper.min.js"></script>
	<script type="text/javascript" src="assets/js/bootstrap.min.js"></script>
	<script type="text/javascript" src="assets/js/custom.js"></script>
	
	<script type="text/javascript">
		
	$(document).ready(function() {
		
		$('.langbutton').on('click', function(){
			$.ajax({
				url: "ajax/change_lang.php",
				data: {lng: $(this).data('lng')},
				success: function(result){
					//alert(result)
					location.reload();
				}
			});
		});
		
		$("#log_form").submit(function (e) {
			e.preventDefault();

			$('#logMsg').hide();
			if($('input[name="username"]').val() == '' || $('input[name="password"]').val() == ''){
				$('#logMsg').html('<?=$lng['Please fill in required fields']?>').fadeIn(200);
				return false;
			}
			var formData = $(this).serialize();
			$.ajax({
				url: "ajax/ajax_login.php",
				data: formData,
				success: function(response){
					//$("#dump").html(response); return false;
					
					if($.trim(response) == 'first'){
						$('#logMsg').html('<?=$lng['Please change password on first visit']?>').fadeIn(200);
						$("#logForm").slideUp(200);
						$("#firstForm").slideDown(200);
						$('input[name="agent_id"]').val($('#log_form input[name="username"]').val());
					}
					if($.trim(response) == 'wrong'){
						$('#logMsg').html('<?=$lng['Wrong Username or Password']?>').fadeIn(200);
					}
					if($.trim(response) == 'suspended'){
						$('#logMsg').html('<?=$lng['This user is suspended']?>').fadeIn(200);
					}
					if($.trim(response) == 'ok'){
						location.href = 'index.php';
					}
				},
				error:function (xhr, ajaxOptions, thrownError){
					alert('<?=$lng['Error']?> : '+thrownError);
				}
			});
		});
		
		$("#forgotPassForm").submit(function(e){
			e.preventDefault();
			if($('#femail').val()==''){$("#forMsg").html('<i class="fa fa-exclamation-circle"></i>&nbsp; <?=$lng['Please fill in required fields']?>').fadeIn(200); return false;}
			//alert($('#rpass').val())
			var formData = $(this).serialize();
			$.ajax({
				url: "ajax/forgot_agent_password.php",
				dataType: "text",
				data: formData,
				success: function(response){
					response = $.trim(response);
					//alert(response)
					if(response=='success'){
						$("#forMsg").html('<?=$lng['New password send to your email address']?>').fadeIn(200);
					}else{
						$("#forMsg").html(response).fadeIn(200);
					}
				},
				error:function (xhr, ajaxOptions, thrownError){
					$("#forMsg").html('<?=$lng['Error']?> : '+thrownError).fadeIn(200);
				}
			});
		});

		$("#firstPassForm").submit(function(e){
			e.preventDefault();
			$("#firstMsg").html('');
			var formData = $(this).serialize();
			var pass = $('input[name="npassword"]').val();
			$.ajax({
				url: "ajax/change_first_password.php",
				data: formData,
				success: function(response){
					response = $.trim(response);
					//$('#dump').html(response); return false;
					if(response == 'success'){
						$("#firstMsg").html('<?=$lng['Password changed successfuly']?>').fadeIn(200);
						$('input[name="password"]').val(pass);
						$("#log_form").submit();
					}else if(response == 'empty'){
						$("#firstMsg").html('<?=$lng['Please fill in required fields']?>').fadeIn(200);
					}else if(response == 'short'){
						$("#firstMsg").html('<?=$lng['New password to short min 8 characters']?>').fadeIn(200);
					}else if(response == 'same'){
						$("#firstMsg").html('<?=$lng['New passwords are not the same']?>').fadeIn(200);
					}else{
						$("#firstMsg").html(response).fadeIn(200);
					}
				},
				error:function (xhr, ajaxOptions, thrownError){
					$('#firstMsg').html('<?=$lng['Error']?> : '+thrownError).fadeIn(200);
				}
			});
		});
		
		$('#forgot').click(function(){
			$('.logMsg').html('');
			$('#logTitle').html('<?=$lng['Request new password']?>');
			$('#logForm').slideUp(200);
			$('#forgotForm').slideDown(200);
		})
		$('#backLogin').click(function(){
			$('.logMsg').html('');
			$('#forgotForm').slideUp(200);
			$('#logForm').slideDown(200);
			$('#logTitle').html('<?=$lng['Login to our secure server']?>');
		})



	 })
		
	</script>	

</body>

</html>















