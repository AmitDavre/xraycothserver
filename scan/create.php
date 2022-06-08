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
	$tmp2 = unserialize($_COOKIE['scan']);


 


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
		#overlay {
  background: #ffffff;
  color: #666666;
  position: fixed;
  height: 100%;
  width: 100%;
  z-index: 5000;
  top: 0;
  left: 0;
  float: left;
  text-align: center;
  padding-top: 25%;
  opacity: .80;
}
button {
  margin: 40px;
  padding: 5px 20px;
  cursor: pointer;
}
.spinner {
    margin: 0 auto;
    height: 64px;
    width: 64px;
    animation: rotate 0.8s infinite linear;
    border: 5px solid firebrick;
    border-right-color: transparent;
    border-radius: 50%;
}
@keyframes rotate {
    0% {
        transform: rotate(0deg);
    }
    100% {
        transform: rotate(360deg);
    }
}
</style>

<body style="">

	<div id="overlay" style="display:none;">
	    <div class="spinner"></div>
	    <br/>
	    Loading...
	</div>


	<div class="overhang" style="margin-top: 80px;background-color: green; border-bottom: 0px solid rgb(39, 174, 96); display: none; overflow: hidden;"><span class="overhang-message" style="color: rgb(255, 255, 255);"><i class="fa fa-check"></i>&nbsp;&nbsp;Data updated successfuly</span></div>
	<input type="hidden" name="hidden_comp" id="hidden_comp" value="<?php echo $_GET['comp'];?>">
	<input type="hidden" name="hidden_type" id="hidden_type" value="<?php echo $_GET['type'];?>">

	<div class="header" style="background: rgba(0,0,0,0.7); text-align:center">
		REGO Thailand <?=$lng['Time registration']?> 
	</div>			

	<div class="log-wrapper">

		<div class="log-body">
			
			<div class="log-title"><span onclick="goback();" style="float: left;margin-left: 11px;"><a id="back"><i style="color: #fff;transform: rotate(180deg);" class="fa fa-sign-out fa-lg"></i></a> Go Back</span><span style="float: right;margin-right: 11px;"><?php echo strtoupper($companyValue) ; ?></span></div>

			<div id="logForm">	
				<form id="log_form" style="padding:0 0 10px 0">
					
					<div class="row">
						<div class="col-md-12">
							<div id="selbranch" >
							<label><?=$lng['Select Branch']?></label>
							<select id="scanbranch">
								<option value=""><?=$lng['Select']?></option>
							</select>
					</div>		
						</div>
					</div>					

					<div class="row">
						<div class="col-md-12">
							<div id="svloc" >
								<label><?=$lng['Location name']?></label>
								<input name="locname" id="locname" type="text" value="">	

								<label><?=$lng['Latitude']?></label>
								<input name="latvalue" id="latvalue" type="text" value="">		

								<label><?=$lng['Longitude']?></label>
								<input name="longvalue" id="longvalue" type="text" value="">

								<label><?=$lng['Scan perimeter']?></label>
								<input name="perivalue" id="perivalue" type="text" value="">

								
							</div>
						</div>
					</div>					
					<div class="row">
						<div class="col-md-12">
							<button id="generateloc" type="button" class="locationbutton btn btn-danger btn-lg btn-block tac" style="margin-top:10px!important;margin: 0px; font-weight:400; letter-spacing:1px"><?=$lng['Re-generate Location']?> &nbsp;<i class="fa fa-sign-in fa-lg"></i></button>
						</div>
					</div>					
					<div class="row">
						<div class="col-md-12">
							<button id="svButton" type="button" class="btn btn-success btn-lg btn-block tac" style="margin-top:10px!important;margin: 0px; font-weight:400; letter-spacing:1px"><?=$lng['Save']?> &nbsp;<i class="fa fa-sign-in fa-lg"></i></button>
						</div>
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
		var latitudes = '';
		var longitude = '';
		var longitudes = '';



		
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
		
		
		$(document).on('click', '#timeregister', function(){

			var scanloc = $('#selLoc option:selected').val();

			var hidden_type  = $('#hidden_type').val();

			if(hidden_type == 'log')
			{
				$.ajax({
					url: "ajax/ajax_inrange.php",
					data: {scanloc: scanloc},
					success: function(response){
						//$("#dump").html(response); return false;
						window.location.href = 'index.php';
					}
				});
			}
			else if(hidden_type == 'cre')
			{
				window.location.href = 'create.php';
			}

		})			

		

	$( document ).ready(function() {
			var selcomp2 = $('#hidden_comp').val();
			$('#overlay').fadeIn();
			$.ajax({
				url: "ajax/ajax_getbranch.php",
				data: {selcomp: selcomp2},
				success: function(response){
					  var data = JSON.parse(response);
				    $.each(data, function(index, value) {
	                	var optionSel= '<option value="'+value.id+'">'+value.name+'</option>';
	                	$('#scanbranch').append(optionSel);
	           		 });
					$('#scanbranch').val("<?php echo $_GET['bch']?>");

					// run ajax and get location name if exists
					var bchid= '<?php echo $_GET['bch']?>';
					var locid= '<?php echo $_GET['loc']?>';

					$.ajax({
						url: "ajax/ajax_getloc_name.php",
						data: {selcomp: selcomp2,bchid: bchid,locid: locid},
						success: function(response){
							  var data = JSON.parse(response);

							  $('#locname').val(data.name);
							  $('#latvalue').val(data.latitude);
							  $('#longvalue').val(data.longitude);


						}
					});





		

			     	$('#selbranch').fadeIn(200);

			     	// get location 
				    $('.locationbutton').click();

				    $('#overlay').fadeOut();


				}
			});
		});		

			$( "#generateloc" ).on( "click", function() {

				 $('#overlay').fadeIn();


				$('#latvalue').val(latitudes);
				$('#longvalue').val(longitudes);
				 $('#overlay').fadeOut();
			
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
 
		function error(loc) { 
			alert('Error in Geolocation!'); 
		} 	

		function errors(loc) { 
			alert('Error in Geolocation!'); 
		} 

		function showLocations(loc) { 
			longitudes = loc.coords.longitude;
			latitudes = loc.coords.latitude;
		}




		navigator.geolocation.getCurrentPosition(showLocation, error, { enableHighAccuracy: true, timeout: 20000, maximumAge: 0 }); 
		// navigator.geolocation.getCurrentPosition(showLocations, errors); 
		navigator.geolocation.getCurrentPosition(showLocations, errors, { enableHighAccuracy: true, timeout: 20000, maximumAge: 0 }); 


	 
	 
})
		
	</script>	

	<script type="text/javascript">
		
		function goback()
		{
			var hidden_comp = $('#hidden_comp').val();

			window.location.href = 'selbranch.php?comp='+hidden_comp+'&type=cre';

		}
	</script>


		<script type="text/javascript">
		
		$('#svButton').on('click', function(){
			var selectoption  =  '<?php echo $_GET['loc']?>';
			var locname  =  $('#locname').val();
			var latvalue  =  $('#latvalue').val();
			var longvalue  =  $('#longvalue').val();
			var perivalue  =  $('#perivalue').val();

			var selcomppp = $('#hidden_comp').val();
			var selbrancch = '<?php echo $_GET['bch']?>';

			$.ajax({
				url: "ajax/update_scan_loc.php",
				data: {selectoption: selectoption,locname: locname,latvalue: latvalue,longvalue: longvalue,perivalue: perivalue,selcomppp: selcomppp,selbrancch: selbrancch},
				success: function(response){
					if(response){
						$('.overhang').fadeIn(200);
						setTimeout(function(){
							window.location.href = 'continue.php?comp='+selcomppp;
						},1000);
					}
				}
			});
		});


	</script>



</body>

</html>















