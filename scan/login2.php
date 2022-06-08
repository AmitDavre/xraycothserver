<?php
	
	if(session_id()==''){session_start();}
	include('db_connect.php');
	
	$clientID = '';
	$password = '';
	if(isset($_COOKIE['scan'])) {
		$tmp = unserialize($_COOKIE['scan']);
		$clientID = $tmp['cid'];
		$password = $tmp['pass'];
	}
	if(isset($_COOKIE['scanlang'])) {
		$lang = $_COOKIE['scanlang'];
		$_SESSION['scan']['lang'] = $lang;
	}else{
		$lang = 'th';
		$_SESSION['scan']['lang'] = $lang;
	}
	
?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>REGO HR</title>
	
	<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700">
	<link rel="stylesheet" href="../assets/css/bootstrap.min.css">
	<link rel="stylesheet" href="../assets/css/font-awesome.min.css">
	<link rel="stylesheet" href="css/style.css?<?=time()?>">

	<script type="text/javascript" src="js/jquery.min.js"></script>
	<script type="text/javascript" src="../assets/js/bootstrap.min.js"></script>
	
</head>
<style>
	.langbutton {
		position:fixed;
		bottom:25px;
		right:25px;
		cursor:pointer;
	}
	* {
		outline:none;
	}
		
	form label {
		display:block;
		padding:0 0 0 2px;
		margin:0;
		font-weight:600;
		font-size:15px;
		color:#000;
	}
	form input[type=text], 
	form input[type=password], 
	form td select, 
	form textarea {
		width:100%;
		padding:8px 12px;
		border:1px #ddd solid;
		margin:3px 0 10px;
		line-height:normal;
		box-sizing: border-box;
		font-size:15px;
	}
	form input[type=text]:hover, 
	form input[type=password]:hover, 
	form textarea:hover {
		background:#fff !important;
	}
	form input[type=text]:focus, 
	form input[type=password]:focus, 
	form textarea:focus, 
	form select:hover {
		background:#fff !important;
	}
	
	
</style>

<body style="">

	<div class="header" style="background: rgba(0,0,0,0.7); text-align:center">
		<? //=$lng['REGO HR Mobile Platform']?>REGO HR Time registration
	</div>			

	<div style="position:absolute; top:0; bottom:0; left:0; right:0; padding-top:80px;background:url(../images/mob_bg.jpg?123) no-repeat center center; background-size:cover;">

		<div style="background:rgba(255,255,255,0.9); min-width:300px; max-width:90%; margin:0 auto; padding:60px 30px 20px; border-radius: 5px; box-shadow: 0 0 20px rgba(0, 0, 0, 0.5); position:relative">
			
			<div style="position:absolute; top:0; left:0; right:0; border-radius:5px 5px 0 0; background:#900; color:#fff; text-align:center; line-height:45px; font-size:18px" id="logTitle"><?=$lng['Login to our secure server']?></div>

			<div id="logForm">	
				<form id="log_form" style="padding:0 0 10px 0">
					
					<label><?=$lng['Client ID']?></label>
					<input name="clientID" type="text" value="<?=$clientID?>">
					
					<label><?=$lng['Password']?></label>
					<input name="password" type="password" value="<?=$password?>">
					
					<div id="logMsg" style="color:#b00; font-weight:600; font-size:16px; display:none; text-align:center"></div>
					
					<button type="submit" class="btn btn-success btn-lg btn-block tac" style="margin-top:10px; font-weight:400; letter-spacing:1px"><?=$lng['Log-in']?> &nbsp;<i class="fa fa-sign-in fa-lg"></i></button>
					
				</form>
			</div>
			
			<div id="dump"></div>
		</div>

		<? if($lang=='en'){ ?>
			<a data-lng="th" class="langbutton <? if($lang=='th'){echo 'activ';} ?>"><img height="50px" src="../images/flag_th.png"></a>
		<? }else{ ?>
			<a data-lng="en" class="langbutton <? if($lang=='en'){echo 'activ';} ?>"><img height="50px" src="../images/flag_en.png"></a>
		<? } ?>
	
	</div>
	
	<script type="text/javascript">
		
	$(document).ready(function() {
		
		$('.langbutton').on('click', function(){
			$.ajax({
				url: "ajax/change_lang.php",
				data: {lng: $(this).data('lng')},
				success: function(ajaxresult){
					//alert(ajaxresult)
					location.reload();
				}
			});
		});
		
		$("#log_form").submit(function (e) {
			e.preventDefault();
			if($('input[name="clientID"]').val() == '' || $('input[name="password"]').val() == ''){
				$('#logMsg').html('<?=$lng['Please fill in required fields']?>').fadeIn(200);
				return false;
			}
			var formData = $(this).serialize();
			$.ajax({
				url: "ajax/ajax_login.php",
				data: formData,
				success: function(response){
					//$("#dump").html(response); return false;
					if($.trim(response) == 'wrong'){
						$('#logMsg').html('Wrong Client ID or Password').fadeIn(200);
					}
					if($.trim(response) == 'connect'){
						$('#logMsg').html("This Client ID don't exist<? //=$lng['This user is suspended']?>").fadeIn(200);
					}
					if($.trim(response) == 'success'){
						window.location.href = 'index.php';
					}
				},
				error:function (xhr, ajaxOptions, thrownError){
					alert('<?=$lng['Error']?> : '+thrownError);
				}
			});
		});
		
	 })
		
	</script>	

</body>

</html>















