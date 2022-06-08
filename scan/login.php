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

	<div class="header" style="background: rgba(0,0,0,0.7); text-align:center">
		REGO Thailand <?=$lng['Time registration']?> 
	</div>			

	<div class="log-wrapper">

		<div class="log-body">
			
			<div class="log-title"><?=$lng['Login to our secure server']?></div>

			<div id="logForm">	
				<form id="log_form" style="padding:0 0 10px 0">
					
					<label><?=$lng['Username']?></label>
					<input id="username" name="username" type="text" value="<?=$username?>">
					
					<label><?=$lng['Password']?></label>
					<input id="password" name="password" type="password" value="<?=$password?>">
					
					<div id="logMsg"></div>
					
					<button id="logButton" type="submit" class="btn btn-success btn-lg btn-block tac" style="margin-top:10px; font-weight:400; letter-spacing:1px"><?=$lng['Log-in']?> &nbsp;<i class="fa fa-sign-in fa-lg"></i></button>
					
					<button id="reload" onClick="window.location.reload()" type="button" class="btn btn-info btn-lg btn-block tac" style="margin-top:10px; font-weight:400; letter-spacing:1px; display:none"><?=$lng['Reload location']?> &nbsp;<i class="fa fa-refresh xfa-lg"></i></button>
					
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
					<div id="twobtns" class="row col-md-12" style="display: none;">
						<div class="col-md-6">
								<button id="timeregister" type="submit" class="btn btn-success btn-lg btn-block tac" style="margin-top:10px; font-weight:400; letter-spacing:1px"><?=$lng['Time registration']?> &nbsp;<i class="fa fa-sign-in fa-lg"></i></button>
						</div>
					
						<div class="col-md-6">
								<button id="generateloc" type="submit" class="btn btn-danger btn-lg btn-block tac" style="margin-top:10px; font-weight:400; letter-spacing:1px"><?=$lng['Generate Location']?> &nbsp;<i class="fa fa-sign-in fa-lg"></i></button>
						</div>
						
					</div>

					<div id="selcomp" style="display:none">
						<label><?=$lng['Select Company']?></label>
						<select id="scancompany">
							<option value=""><?=$lng['Select']?></option>
						</select>
					</div>						
					<div id="selcomp2" style="display:none">
						<label><?=$lng['Select Company']?></label>
						<select id="scancompany2">
							<option value=""><?=$lng['Select']?></option>
						</select>
					</div>					
					<div id="selbranch" style="display:none">
						<label><?=$lng['Select Branch']?></label>
						<select id="scanbranch">
							<option value=""><?=$lng['Select']?></option>
						</select>
					</div>					
					<div id="selbranch2" style="display:none">
						<label><?=$lng['Select Branch']?></label>
						<select id="scanbranch2">
							<option value=""><?=$lng['Select']?></option>
						</select>
					</div>

					<div id="svloc" style="display:none">
					<label><?=$lng['Location name']?></label>
					<input name="locname" id="locname" type="text" value="">	

					<label><?=$lng['Latitude']?></label>
					<input name="latvalue" id="latvalue" type="text" value="">		

					<label><?=$lng['Longitude']?></label>
					<input name="longvalue" id="longvalue" type="text" value="">

					<label><?=$lng['Scan perimeter']?></label>
					<input name="perivalue" id="perivalue" type="text" value="">

					<button id="svButton" type="button" class="btn btn-success btn-lg btn-block tac" style="margin-top:10px; font-weight:400; letter-spacing:1px"><?=$lng['Update']?> &nbsp;<i class="fa fa-sign-in fa-lg"></i></button>
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
		
		$("#log_form").submit(function (e) {
			e.preventDefault();
			if($('input[name="username"]').val() == '' || $('input[name="password"]').val() == ''){
				$('#logMsg').html('<?=$lng['Please fill in required fields']?>').fadeIn(200);
				return false;
			}
			var formData = $(this).serialize();
			$.ajax({
				url: "ajax/ajax_login.php",
				data: formData,
				dataType: 'json',
				success: function(response){
					//alert(latitude+' - '+longitude+' :: '+response.lat+' - '+response.lon)
					//$("#dump").html(response); return false;
					//var distance = parseInt((getDistanceFromLatLonInKm(latitude,longitude,response.lat,response.lon))*1000);
					if($.trim(response) == 'scan'){
						$('#logMsg').html('<?=$lng['Please select "REGO Direct Time Registration" in Time settings']?>').fadeIn(200);
						return false;
					}else if($.trim(response) == 'wrong'){
						$('#logMsg').html('<?=$lng['Wrong Username or Password']?>').fadeIn(200);
					}else if($.trim(response) == 'suspended'){
						$('#logMsg').html("<?=$lng['This user is suspended']?>").fadeIn(200);
						return false;
					}else if($.trim(response) == 'type'){
						$('#logMsg').html("<?=$lng['Only system users can login']?>").fadeIn(200);
						return false;
					}else{
						//$("#dump").html(response); return false;
						// var distance;
						// var smdistance = 0;
						// var scanlocs = [];
						// $.each(response, function(index, i){
						// 	distance = parseInt((getDistanceFromLatLonInKm(latitude,longitude,response[index][1],response[index][2]))*1000);
						// 	if(smdistance == 0 || distance < smdistance){
						// 		smdistance = distance;
						// 	}
						// 	//alert(smdistance+' - '+distance+' - '+response[index][3])
						// 	if(distance < response[index][3]){
						// 		scanlocs.push(response[index][0]);
						// 	}
						// })
						// if(scanlocs.length == 0) {
						// 	$('#logMsg').html("<?=$lng['This device is out of range']?> ("+smdistance+"m)").fadeIn(200);
						// 	$('#reload').fadeIn(300);
						// 	return false;
						// }
						// $.each(scanlocs, function(){
						// 	var opt = document.createElement("option");
						// 	opt.value = this; //or i, depending on what you need to do
						// 	opt.innerHTML = this; 
						// 	$('#scanLocations').append(opt); //Chuck it into the dom here if you want							
						// })
						$('#logMsg').hide();
						$('#username').prop('disabled', true);
						$('#password').prop('disabled', true);
						$('#logButton').hide();
						// $('#selLoc').fadeIn(200);
						// $('#twobtns').fadeIn(200);
						$('#selcomp').fadeIn(200);

						$.ajax({
							url: "ajax/ajax_getlogin_company.php",
							data: formData,
							dataType: 'json',
							success: function(response){
				
				
								
									$.each(response, function(){
										var opt = document.createElement("option");
										opt.value = this; //or i, depending on what you need to do
										opt.innerHTML = this; 
										$('#scancompany').append(opt); //Chuck it into the dom here if you want							
									})		

									$.each(response, function(){
										var opt = document.createElement("option");
										opt.value = this; //or i, depending on what you need to do
										opt.innerHTML = this; 
										$('#scancompany2').append(opt); //Chuck it into the dom here if you want							
									})
									$('#logMsg').hide();
									// $('input').prop('disabled', true);
										$('#username').prop('disabled', true);
										$('#password').prop('disabled', true);
									$('#logButton').hide();
									// $('#selLoc').fadeIn(200);
									$( "#timeregister" ).on( "click", function() {
									   $('#timeregister').css('display','none');
									   $('#generateloc').css('display','none');
									   $('#selcomp').fadeIn(200);
										return false;		
									});							

									$( "#generateloc" ).on( "click", function() {
									   $('#generateloc').css('display','none');
									   $('#timeregister').css('display','none');
									   $('#selcomp2').fadeIn(200);
										return false;		
									});


												
							},
					
						});




						return false;
					}
					/*if(distance < parseInt(response.per)){
						$('#reload').fadeIn(300);
						$('#logMsg').html('This device is out of company range ('+distance+'m)').fadeIn(200);
						return false;
					}else{
						$.ajax({
							url: "ajax/ajax_inrange.php",
							success: function(response){
								window.location.href = 'index.php';
							}
						});
					}*/
				},
				error:function (xhr, ajaxOptions, thrownError){
					alert('<?=$lng['Error']?> : '+thrownError);
				}
			});
		});
		
		$(document).on('change', '#selLoc', function(){
			var scanloc = $('#selLoc option:selected').val();
			$.ajax({
				url: "ajax/ajax_inrange.php",
				data: {scanloc: scanloc},
				success: function(response){
					//$("#dump").html(response); return false;
					window.location.href = 'index.php';
				}
			});
		})			

		$(document).on('change', '#selLoc2', function(){
			var scanloc = $('#selLoc option:selected').val();
			$('#svloc').fadeIn(200);

			$('#latvalue').val(latitude);
			$('#longvalue').val(longitude);

		})		

		$(document).on('change', '#selcomp', function(){
			var selcomp = $('#selcomp option:selected').val();
			window.location.href = 'continue.php?comp='+selcomp;
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

			function getDistanceFromLatLonInKms(lat1,lon1,lat2,lon2) {
			var R = 6371; // Radius of the earth in km
			var dLat = deg2rads(lat2-lat1);  // deg2rad below
			var dLon = deg2rads(lon2-lon1); 
			var a = 
				Math.sin(dLat/2) * Math.sin(dLat/2) +
				Math.cos(deg2rads(lat1)) * Math.cos(deg2rads(lat2)) * 
				Math.sin(dLon/2) * Math.sin(dLon/2)
				; 
			var c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1-a)); 
			var d = R * c; // Distance in km
			return d;
		}
		
		function deg2rads(deg) {
			return deg * (Math.PI/180)
		}




		$(document).on('change', '#selbranch', function(){
			var selcomppp = $('#selcomp option:selected').val();
			var selbrancch = $('#scanbranch option:selected').val();

			$.ajax({
				url: "ajax/ajax_getloc.php",
				data: {selcomp: selcomppp,selbrancch: selbrancch},
				success: function(response){
					  var data = JSON.parse(response);

					$('#selbranch').css('display','none');

					navigator.geolocation.getCurrentPosition(showLocations, error); 
						var distance;
						var smdistance = 0;
						var scanlocs = [];
						$.each(data, function(index, i){
							distance = parseInt((getDistanceFromLatLonInKm(latitude,longitude,data[index][1],data[index][2]))*1000);
							if(smdistance == 0 || distance < smdistance){
								smdistance = distance;
							}
							//alert(smdistance+' - '+distance+' - '+response[index][3])
							if(distance < data[index][3]){
								scanlocs.push(data[index][0]);
							}
						})
						if(scanlocs.length == 0) {
							$('#logMsg').html("<?=$lng['This device is out of range']?> ("+smdistance+"m)").fadeIn(200);
							$('#reload').fadeIn(300);
							return false;
						}
						$.each(scanlocs, function(){
							var opt = document.createElement("option");
							opt.value = this; //or i, depending on what you need to do
							opt.innerHTML = this; 
							$('#scanLocations').append(opt); //Chuck it into the dom here if you want	

						})
			     	$('#selLoc').fadeIn(200);


				}
			});
		});		

		$(document).on('change', '#selbranch2', function(){
			var selcomppp = $('#selcomp2 option:selected').val();
			var selbrancch = $('#scanbranch2 option:selected').val();

			$.ajax({
				url: "ajax/ajax_getloc.php",
				data: {selcomp: selcomppp,selbrancch: selbrancch},
				success: function(response){
					  var data = JSON.parse(response);

					$('#selbranch2').css('display','none');

					// navigator.geolocation.getCurrentPosition(showLocations, error); 
					// 	var distance;
					// 	var smdistance = 0;
					// 	var scanlocs = [];
					// 	$.each(data, function(index, i){
					// 		distance = parseInt((getDistanceFromLatLonInKm(latitude,longitude,data[index][1],data[index][2]))*1000);
					// 		if(smdistance == 0 || distance < smdistance){
					// 			smdistance = distance;
					// 		}
					// 		//alert(smdistance+' - '+distance+' - '+response[index][3])
					// 		if(distance < data[index][3]){
					// 			scanlocs.push(data[index][0]);
					// 		}
					// 	})
					// 	if(scanlocs.length == 0) {
					// 		$('#logMsg').html("<?=$lng['This device is out of range']?> ("+smdistance+"m)").fadeIn(200);
					// 		$('#reload').fadeIn(300);
					// 		return false;
					// 	}
					// 	$.each(scanlocs, function(){
					// 		var opt = document.createElement("option");
					// 		opt.value = this; //or i, depending on what you need to do
					// 		opt.innerHTML = this; 
					// 		$('#scanLocations2').append(opt); //Chuck it into the dom here if you want	

					// 	})
			     	$('#selLoc2').fadeIn(200);


				}
			});
		});
		


			function getDistanceFromLatLonInKm(lat1,lon1,lat2,lon2) {
			var R = 6371; // Radius of the earth in km
			var dLat = deg2rad(lat2-lat1);  // deg2rad below
			var dLon = deg2rad(lon2-lon1); 
			var a = 
				Math.sin(dLat/2) * Math.sin(dLat/2) +
				Math.cos(deg2rad(lat1)) * Math.cos(deg2rad(lat2)) * 
				Math.sin(dLon/2) * Math.sin(dLon/2)
				; 
			var c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1-a)); 
			var d = R * c; // Distance in km
			return d;
		}
		
		function deg2rad(deg) {
			return deg * (Math.PI/180)
		}	



		

		function showLocation(loc) { 
			longitude = loc.coords.longitude;
			latitude = loc.coords.latitude;
		} 		
		function showLocations(loc) { 
			longitude = loc.coords.longitude;
			latitude = loc.coords.latitude;
		} 
		function error(loc) { 
			alert('Error in Geolocation!'); 
		} 
		navigator.geolocation.getCurrentPosition(showLocation, error); 







	 
	 
	 
})
		
	</script>	
	<script type="text/javascript">
		
		$('#svButton').on('click', function(){
			var selectoption  =  $('#scanLocations2').val();
			var locname  =  $('#locname').val();
			var latvalue  =  $('#latvalue').val();
			var longvalue  =  $('#longvalue').val();
			var perivalue  =  $('#perivalue').val();

			var selcomppp = $('#selcomp2 option:selected').val();
			var selbrancch = $('#scanbranch2 option:selected').val();

			$.ajax({
				url: "ajax/update_scan_loc.php",
				data: {selectoption: selectoption,locname: locname,latvalue: latvalue,longvalue: longvalue,perivalue: perivalue,selcomppp: selcomppp,selbrancch: selbrancch},
				success: function(response){
					if(response){
						$('.overhang').fadeIn(200);
						setTimeout(function(){location.reload();},1000);
					}
				}
			});
		});


	</script>

</body>

</html>















