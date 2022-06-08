<?php

	if(session_id()==''){session_start();} 
	ob_start();
	
	include('../../dbconnect/db_connect.php');
	// include('../../scan/db_connect.php');

// $_SESSION['lang'] = 'en';
// $_SESSION['scan']['lang'] = 'en';

	$goback =  ROOT.'mob/index.php?mn=16';





	function array_flatten($array) { 
	  if (!is_array($array)) { 
	    return false; 
	  } 
	  $result = array(); 
	  
	  foreach ($array as $key => $value) { 

	    if (is_array($value)) { 
	      $result = array_merge($result, array_flatten($value)); 
	    } else { 
	      $result = array_merge($result, array($key => $value));
	    } 
	  } 

	  $resultsss = array_combine(range(1, count($result)), array_values($result));
	  return $resultsss; 
	}



	$emp_id_common = $_SESSION['rego']['username'];

	if(isset($_GET['c'])){$clock = $_GET['c'];}else{$clock = 'in';}
	
	$qrcode = '';
	$perimeter = '';
	if($res = $dbc->query("SELECT * FROM ".$cid."_leave_time_settings")){
		$row = $res->fetch_assoc();
		$qrcode = $row['qrcode'];
		$perimeter = $row['perimeter'];
	}
	
	function getEmployeeName($id){
		global $dbc;
		global $cid;
		$data = array();
		$sql = "SELECT emp_id, th_name, en_name, selfie FROM ".$cid."_employees WHERE emp_id = '".$id."'";
		if($res = $dbc->query($sql)){
			if($row = $res->fetch_assoc()){
				$data = $row;
			}
		}
		return $data;
	}	

	function getEmployeeAllDetails($id){
		global $dbc;
		global $cid;
		$data = array();
		$sql = "SELECT * FROM ".$cid."_employees WHERE emp_id = '".$id."'";
		if($res = $dbc->query($sql)){
			if($row = $res->fetch_assoc()){
				$data = $row;
			}
		}
		return $data;
	}

	$employee = getEmployeeName($_SESSION['rego']['emp_id']);
	$employeeAllDetails = getEmployeeAllDetails($_SESSION['rego']['emp_id']);
	
	$locations = array();
	$scanloc = array();
	if($res = $dbc->query("SELECT scan_locations FROM ".$cid."_leave_time_settings")){
		$row = $res->fetch_assoc();
		$locations = unserialize($row['scan_locations']);
	}
	// foreach($locations as $k=>$v){
	// 	if(!empty($v['name']) && !empty($v['code']) && !empty($v['perimeter']) && !empty($v['latitude']) && !empty($v['longitude'])){
	// 		$scanloc[$v['code']] = array($v['name'],$v['perimeter'],$v['latitude'],$v['longitude']);
	// 	}
	// }
	//echo json_encode($scanloc);
	//var_dump($scanloc); exit;
	//var_dump($clock); exit;
	
	$gps = 1;
	if($res = $dbc->query("SELECT gps FROM ".$cid."_leave_time_settings")){
		$xrow = $res->fetch_assoc();
		if($xrow['gps'] == 1){$gps = 0;}
	}



	
	$empLatitude = $employeeAllDetails['latitude'];
	$empLongitude = $employeeAllDetails['longitude'];



?>

<!DOCTYPE html>
<html>
  <head>
    <title><?=$lng['Time registration']?></title>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width; initial-scale=1.0; maximum-scale=1.0; user-scalable=0;" />
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate"/>
  	<meta http-equiv="Pragma" content="no-cache"/>
  	<meta http-equiv="Expires" content="0"/>
 	 	<meta name="apple-mobile-web-app-status-bar" content="#066" />
 		<meta name="theme-color" content="#066" />
		<link rel="shortcut icon" href="favicon.ico" type="image/x-icon">
		<link rel="icon" href="favicon.ico" type="image/x-icon">

		<link href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700" rel="stylesheet" type="text/css" />
		<link href="../assets/css/bootstrap.min.css" rel="stylesheet" type="text/css">
		<link href="../../assets/css/font-awesome.min.css" rel="stylesheet" type="text/css">
		<link href="../assets/css/style.css?<?=time()?>" rel="stylesheet">
		<link href="../assets/css/mobStyle.css?<?=time()?>" rel="stylesheet">
		
		<script type="text/javascript" src="../../assets/js/jquery-3.2.1.min.js"></script>

	</head>

  <body style="background:#006666">
    
		<div class="header page_header">
			<div><i class="fa fa-clock-o"></i>&nbsp; <?=$lng['Time registration']?></div>
			<a style="float:right" href="../index.php?mn=2"><i class="fa fa-home"></i></a>
		</div>			
	
		<div style="position:absolute; top:55px; bottom:0; left:0; right:0; padding:10px; text-align:center">
			<div id="dump"></div>
			<h1 class="scan_clock"><span id="time">00:00:00</span></h1>
      <center>
				<a href="#" id="btn-scan-qr">
        	<!-- <img src="1499401426qr_icon.svg"> -->
      	<a/>
			</center>
			
			<div class="canvas_wrapper">
      	<canvas hidden="" id="qr-canvas"></canvas>
				<div style="position:absolute; top:0; left:0; width:50%; border-right:1px solid rgba(255,0,0,0.5); height:100%"></div>
				<div style="position:absolute; top:0; left:0; width:100%; height:50%; border-bottom:1px solid rgba(255,0,0,0.5)"></div>
			</div>

      <div id="qr-result" hidden="">
        <span id="outputData"></span>
      </div>
			<? if($gps){ ?>
			<h1 style="padding-bottom:0"><?=$lng['Your location']?></h1> 
			<? } ?>
			<p id=loc></p>
			<? //=var_dump($scanloc)?>
			<div style="position:absolute; bottom:20px; left:0; right:0; text-align:center">
				<button style="border-radius:3px" onClick="window.location.reload()" type="button" class="btn btn-success"><i class="fa fa-refresh"></i>&nbsp; <?=$lng['Generate Location']?></button>
			</div>
			
			 
    </div>
		
		<!-- Modal -->
		<div class="modal fade" id="successModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel5" aria-hidden="true" data-backdrop="static" data-keyboard="false">
			<div class="modal-dialog" role="document">
				<div class="modal-content">
					<div class="modal-body" style="color:#333; text-align:center">
						<div id="successMsg" style="font-size:18px; line-height:140%; padding:10px 0 15px"></div>
						<button id="modalBtn" class="btn btn-success"><?=$lng['OK']?></button>
					</div>
				</div>
			</div>
		</div>

		<!-- Modal -->
		<div class="modal fade" id="rangeModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel5" aria-hidden="true" data-backdrop="static" data-keyboard="false">
			<div class="modal-dialog" role="document">
				<div class="modal-content">
					<div class="modal-body" style="color:#333; text-align:center">
						<div id="rangeMsg" style="font-size:18px; line-height:140%; padding:10px 0 15px"><b style="font-size:22px"><?=$lng['You are out of range']?></b><br><?=$lng['Please reload the page']?><br><?=$lng['Distance']?> = <span id="mydistance"></span></div>
						<button onClick="window.location.reload()" class="btn btn-success"><i class="fa fa-refresh"></i>&nbsp; <?=$lng['Reload']?></button>
						<button onclick="goback();" class="btn btn-success"><i class="fa fa-arrow-left"></i>&nbsp; <?=$lng['Go Back']?></button>
					</div>
				</div>
			</div>
		</div>

	<script type="text/javascript" src="../../assets/js/popper.min.js"></script>
	<script type="text/javascript" src="../../assets/js/bootstrap.min.js"></script>
	<script src="qr_packed.js?<?=time()?>"></script>

	<script> 
		
		Date.prototype.today = function () { 
		return ((this.getDate() < 10)?"0":"") + this.getDate() +"-"+(((this.getMonth()+1) < 10)?"0":"") + (this.getMonth()+1) +"-"+ this.getFullYear();
		}
		Date.prototype.timeNow = function () {
		return ((this.getHours() < 10)?"0":"") + this.getHours() +":"+ ((this.getMinutes() < 10)?"0":"") + this.getMinutes() +":"+ ((this.getSeconds() < 10)?"0":"") + this.getSeconds();
		}
		function toFormat(x) {
			return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
		}				
			
		$(document).ready(function() {
			//$('#successModal').modal('toggle');
			//window.location.reload();
			
			var empLatitude ='<?php echo $empLatitude?>';
			var empLongitude ='<?php echo $empLongitude?>';

			var myqrcode = <?=json_encode($scanloc)?>;
			var employee = <?=json_encode($employee)?>; // sometimes undefined ???
			var clock = <?=json_encode($clock)?>; // sometimes undefined ???
			var perimeter = 1;
			var mydistance = 9999;
			var selfie = <?=json_encode($employee['selfie'])?>;
			var gps = <?=json_encode($gps)?>;
			var location = '';
			
			const video = document.createElement("video");
			const canvasElement = document.getElementById("qr-canvas");
			const canvas = canvasElement.getContext("2d");
			const qrResult = document.getElementById("qr-result");
			const outputData = document.getElementById("outputData");
			const btnScanQR = document.getElementById("btn-scan-qr");
			let scanning = false;
		
			// function getLocation() {
			// 	 var geolocation = navigator.geolocation;
			// 	 geolocation.getCurrentPosition(showLocation, errorHandler, {enableHighAccuracy: true, timeout: 10000, maximumAge: 10000});
			// }		

			
			function watchLocation()
			{
			  watchID = navigator.geolocation.watchPosition(showLocation);
			}	
			
			function showLocation (position) {
				var latitude = (position.coords.latitude);
				var longitude = (position.coords.longitude);
				var accuracy = parseInt(position.coords.accuracy);
				//var timestamp = position.timestamp;
				//var d = new Date(timestamp);
				
				var distance = 0;
				var smalest_distance = 0;
				var smalest_distances = 100;
				var scanlocation = 'Unknown';
					distance = parseInt((getDistanceFromLatLonInKm(latitude, longitude, empLatitude, empLongitude))*1000);

					if( distance < smalest_distances){
						mydistance = distance;
						scanlocation = 'Home Location';
						perimeter = 100;
					}
				mydistance = distance;

				document.getElementById('loc').innerHTML = 
				'<?=$lng['Longitude']?> : ' + longitude.toFixed(7) + '<br>' + 
				'<?=$lng['Latitude']?> : ' + latitude.toFixed(7) + '<br>' + 
				'<?=$lng['Accuracy']?> : <b>' + toFormat(accuracy) + ' M.</b><br>' +
				'<?=$lng['Distance']?> : <b>' + toFormat(mydistance) + ' M.<br>' + scanlocation + '</b>';
				if(mydistance > perimeter)
				{
					$('#rangeModal').modal('toggle');
					$('#mydistance').html(toFormat(mydistance) + ' M.');


					if(parseInt(mydistance) > parseInt(perimeter))
					{
						// alert(mydistance + ' - ' + perimeter)
						outputData.innerText = '<?=$lng['You are out of range']?>';
						scanning = false;
						qrResult.hidden = false;
						canvasElement.hidden = true;
						btnScanQR.hidden = false;
						return false;
					}
					



				}
				else
				{

						var date = new Date().today();
						var time = new Date().timeNow();
						var datetime = date + " @ " + time;

						$.ajax({
							url: "ajax/clock_in_out.php",
							type: "POST",
							dataType: 'json', 
							data: {emp_id: employee['emp_id'], date: date, time: time, clock: clock, location: scanlocation},
							success: function(response){
								//$('#dump').html(response); return false;
								if(selfie == 1){
									window.location.href="selfie_home.php?id="+response['id']+"&metaid="+response['metascandata']+"&img="+response['img']+"&date="+response['date'];	
								}else{
									$('#successMsg').html(employee['en_name'] + '<br>' + datetime + '<br><?=$lng['Time registered successfully']?>');
									$('#successModal').modal('toggle');
									qrResult.hidden = false;
								}
							},
							error:function (xhr, ajaxOptions, thrownError){
								alert(thrownError);
							}
						});
						scanning = false;
						video.srcObject.getTracks().forEach(track => {
							track.stop();
						});
						//qrResult.hidden = false;
						canvasElement.hidden = true;
						btnScanQR.hidden = false;
					}


				// console.log(mydistance);


			}			
			
			function getDistanceFromLatLonInKm(lat1,lon1,lat2,lon2) {
				var R = 6371; // Radius of the earth in km
				var dLat = deg2rad(lat2-lat1);  // deg2rad below
				var dLon = deg2rad(lon2-lon1); 
				var a = 
					Math.sin(dLat/2) * Math.sin(dLat/2) +
					Math.cos(deg2rad(lat1)) * Math.cos(deg2rad(lat2)) * 
					Math.sin(dLon/2) * Math.sin(dLon/2); 
				var c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1-a)); 
				var d = R * c; // Distance in km
				return d;
			}
			
			function deg2rad(deg) {
				return deg * (Math.PI/180)
			}			
			
			function errorHandler(err) {
				if(err.code == 1) {
					alert('<?=$lng['Open your browser Location']?>');
				}
				if(err.code == 0 || err.code == 2) {
					alert('<?=$lng['The location of the device could not be determined']?>');
				}
				if(err.code == 3) {
					alert('<?=$lng['Unable to retrieve your location']?>');
				}
			}			
			
			function tick() {
				canvasElement.height = video.videoHeight;
				canvasElement.width = video.videoWidth;
				canvas.drawImage(video, 0, 0, canvasElement.width, canvasElement.height);
				scanning && requestAnimationFrame(tick);
			}
			
	
			
			if(gps){
				watchLocation();
			}else{
				var mydistance = 0;
			}
			
		
					
				
			
	
			
			$('#modalBtn').on('click', function(){
				window.location.href="../";
			})
			
			var span = document.getElementById('time');
			function time() {
				var d = new Date();
				var s = d.getSeconds();
				var m = d.getMinutes();
				var h = d.getHours();
				span.textContent = h + ":" + ('0'  + m).slice(-2) + ":" + ('0'  + s).slice(-2);
			}
			time();
			setInterval(time, 1000);		
		})
			
		</script>

		<script type="text/javascript">

			function goback(){
				var url = '<?php echo $goback ?>';
				window.location= url;
			}
		</script>
  </body>
</html>
