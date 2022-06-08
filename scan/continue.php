<?php
	
	if(session_id()==''){session_start();}
	include('db_connect.php');
	
	$username = '';
	$password = '';
	if(isset($_COOKIE['scan'])) {
		$tmp = unserialize($_COOKIE['scan']);
		$username = $tmp['user'];
		$password = $tmp['pass'];
	}
	if(isset($_COOKIE['scanlang'])) {
		$lang = $_COOKIE['scanlang'];
		$_SESSION['scan']['lang'] = $lang;
	}else{
		$lang = 'th';
		$_SESSION['scan']['lang'] = $lang;
	}
	//exit;

	$companyValue =  $_GET['comp'];


// echo '<pre>';
// print_r($_SESSION);
// echo '</pre>';
// die();
	

?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Regothailand</title>
	
	<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700">
	<link rel="stylesheet" href="../assets/css/bootstrap.min.css">
	<link rel="stylesheet" href="../assets/css/font-awesome.min.css">
	<link rel="stylesheet" href="css/style.css?<?=time()?>">
	<link rel="stylesheet" href="../assets/css/overhang.min.css?<?=time()?>">


	<script type="text/javascript" src="js/jquery.min.js"></script>
	<script type="text/javascript" src="../assets/js/bootstrap.min.js"></script>
	<!-- <script type="text/javascript" src="../assets/js/overhang.min.js"></script> -->


	
</head>
<style>
	.log-wrapper {
		position:absolute;
		top:0;
		bottom:0;
		left:0;
		right:0;
		padding-top:110px;
		background:url(../images/mob_bg.jpg?123) no-repeat center center;
		background-size:cover;
	}
	.log-body {
		background:rgba(255,255,255,0.9);
		min-width:300px;
		max-width:90%;
		margin:0 auto;
		padding:80px 30px 20px;
		border-radius: 5px;
		box-shadow: 0 0 20px rgba(0, 0, 0, 0.5);
		position:relative;
	}
	.log-title {
		position:absolute;
		top:0;
		left:0;
		right:0;
		border-radius:5px 5px 0 0;
		background:#900;
		color:#fff;
		text-align:center;
		line-height:60px;
		font-size:26px;
	}
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
		font-size:20px;
		color:#000;
	}
	form input[type=text], 
	form input[type=password], 
	form select, 
	form textarea {
		width:100%;
		padding:10px 15px;
		border:1px #ddd solid;
		margin:3px 0 10px;
		line-height:normal;
		box-sizing: border-box;
		font-size:24px;
	}
	form select {
		padding:9px 13px;
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
	
	#logMsg {
		color:#b00;
		font-weight:600;
		font-size:20px;
		display:none;
		text-align:center;
	}

@media only screen and (max-width: 430px) {
	.log-wrapper {
		padding-top:80px;
	}
	.log-title {
		line-height:45px;
		font-size:18px;
	}
	.log-body {
		padding:60px 30px 20px;
	}
	form input[type=text], 
	form input[type=password], 
	form select, 
	form textarea {
		font-size:15px;
		padding:8px 12px;
		background:#fff;
	}
	form select {
		padding:8px 10px;
	}
	/*input[type=text]:disabled:hover, 
	form input[type=password]:disabled:hover{
		background:red !important;
	}*/
	form label {
		font-size:15px;
	}
	#logMsg {
		font-size:16px;
	}
}
	
</style>

<body style="">
	<div class="overhang" style="margin-top: 80px;background-color: green; border-bottom: 0px solid rgb(39, 174, 96); display: none; overflow: hidden;"><span class="overhang-message" style="color: rgb(255, 255, 255);"><i class="fa fa-check"></i>&nbsp;&nbsp;Data updated successfuly</span></div>
	<input type="hidden" name="hidden_comp" id="hidden_comp" value="<?php echo $_GET['comp'];?>">

	<div class="header" style="background: rgba(0,0,0,0.7); text-align:center">
		REGO Thailand <?=$lng['Time registration']?> 
	</div>			

	<div class="log-wrapper">

		<div class="log-body">
			
			<div class="log-title"><span style="float: left;margin-left: 11px;"><a href="login.php" id="back"><i style="color: #fff;transform: rotate(180deg);" class="fa fa-sign-out fa-lg"></i></a> Go Back</span><span style="float: right;margin-right: 11px;"><?php echo strtoupper($companyValue) ; ?></span></div>

			<div id="logForm">	
				<form id="log_form" style="padding:0 0 10px 0">
					
					<div class="row">
						<div class="col-md-12">
							<button id="timeregister" type="button" class="btn btn-success btn-lg btn-block tac" style="margin-top:10px; font-weight:400; letter-spacing:1px"><?=$lng['Time registration']?> &nbsp;<i class="fa fa-sign-in fa-lg"></i></button>
						</div>
					</div>					

					<div class="row">
						<div class="col-md-12">
							<button id="generateloc" type="button" class="btn btn-danger btn-lg btn-block tac" style="margin-top:10px; font-weight:400; letter-spacing:1px"><?=$lng['Generate Location']?> &nbsp;<i class="fa fa-sign-in fa-lg"></i></button>
						</div>
					</div>
					
					<div id="selLoc" style="display:none">
						<label><?=$lng['Select location']?></label>
						<select id="scanLocations">
							<option value=""><?=$lng['Select']?></option>
						</select>
					</div>					
					<div id="selLoc2" style="display:none">
						<label><?=$lng['Select location']?></label>
						<select id="scanLocations2">
							<option value=""><?=$lng['Select']?></option>
							<option value="1"><?=$lng['Location 1']?></option>
							<option value="2"><?=$lng['Location 2']?></option>
							<option value="3"><?=$lng['Location 3']?></option>
							<option value="4"><?=$lng['Location 4']?></option>
							<option value="5"><?=$lng['Location 5']?></option>
						</select>
					</div>

					<div id="selcomp" style="display:none">
						<label><?=$lng['Select Company']?></label>
						<select id="scancompany">
							<option value=""><?=$lng['Select']?></option>
						</select>
					</div>					
				
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
	
	<script src="../mob/scanner/globalize.js?<?=time()?>"></script> 

	<script type="text/javascript">
		



	</script>
	<script type="text/javascript">
		
	$(document).ready(function() {
		
		var latitude = '';
		var longitude = '';
		
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
		

		


		$( "#timeregister" ).on( "click", function() {

			var hidden_comp = $('#hidden_comp').val();
			  window.location.href = 'selbranch.php?comp='+hidden_comp+'&type=log';
			return false;		
		});		


		$( "#generateloc" ).on( "click", function() {
		   var hidden_comp = $('#hidden_comp').val();
			  window.location.href = 'selbranch.php?comp='+hidden_comp+'&type=cre';
			return false;			
		});					



		


		$(document).on('change', '#selcomp', function(){
			var selcomp = $('#selcomp option:selected').val();
			$.ajax({
				url: "ajax/ajax_getbranch.php",
				data: {selcomp: selcomp},
				success: function(response){
					  var data = JSON.parse(response);
					  window.location.href = 'continue.php';
				}
			});
		});			

		$(document).on('change', '#selcomp2', function(){
			var selcomp2 = $('#selcomp2 option:selected').val();
			$.ajax({
				url: "ajax/ajax_getbranch.php",
				data: {selcomp: selcomp2},
				success: function(response){
					  var data = JSON.parse(response);
					$('#selcomp2').css('display','none');

				    $.each(data, function(index, value) {
	                	var optionSel= '<option value="'+value.id+'">'+value.name+'</option>';
	                	$('#scanbranch2').append(optionSel);
	           		 });
			     	$('#selbranch2').fadeIn(200);
					 // return false;
				}
			});
		});		




		
 
		function error(loc) { 
			alert('Error in Geolocation!'); 
		} 


	 
	 
	 
})
		
</script>	


</body>

</html>















