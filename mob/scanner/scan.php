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

	$gps = 1;
	if($res = $dbc->query("SELECT gps FROM ".$cid."_leave_time_settings")){
		$xrow = $res->fetch_assoc();
		if($xrow['gps'] == 1){$gps = 0;}
	}


	// NEW SCAN LOCATIONS 


	$sql1 = "SELECT * FROM rego_all_users WHERE username = '".$emp_id_common."'";
	// die();
	if($res1 = $dbx->query($sql1)){


		if($res1->num_rows > 0){
			$row1 = $res1->fetch_assoc();

			 $my_dbcname = $prefix.$row1['emp_access'];


			if($resbb = $dbc->query("SELECT * FROM ".$row1['emp_access']."_branches")){

				
				$xrowbb = $resbb->fetch_assoc();


				$_branchesID = $xrowbb['id'];
				$series = range(1,100);

				$resasd = "'" . implode ( "', '", $series ) . "'";


				if($resbbdd = $dbc->query("SELECT * FROM ".$row1['emp_access']."_branches_data WHERE `ref` IN(".$resasd.") ")){
					while($xrowbbd = $resbbdd->fetch_assoc()){

						$myarray[] = array(

											"1"=> json_decode($xrowbbd['loc1']),
											"2"=> json_decode($xrowbbd['loc2']),
											"3"=> json_decode($xrowbbd['loc3']),
											"4"=> json_decode($xrowbbd['loc4']),
											"5"=> json_decode($xrowbbd['loc5']),

										);
					}
				}
			}


			$AllLocations = array_flatten($myarray);





			$validLocations = array();
			foreach ($AllLocations as $key => $value) {
				
				if($value->latitude !=''){
					$scanlocs[$value->code] = array($value->name,$value->perimeter,$value->latitude,$value->longitude,$value->code,'nocontact');
				}
			}

			// $Latestlocation = array_combine(range(1, count($validLocations)), array_values($validLocations));

			

		
		}else{
		}
	}else{
	}


	// 		GET EXTERNAL SCAN LOCATIONS

	if($externalResult = $dbc->query("SELECT * FROM ".$row1['emp_access']."_location ")){
	while($externalrow = $externalResult->fetch_assoc()){

		$extrenalArray[$externalrow['code']] = array (

			'0' => $externalrow['loc_name'] ,
			'1' => $externalrow['perimeter'] , 
			'2' => $externalrow['latitude'] ,
			'3' => $externalrow['longitude'] , 
			'4' => $externalrow['code'],  
			'5' => $externalrow['contact_email'],  

		);

		}
	}


		if((!empty($extrenalArray)) && (!empty($scanlocs)))
		{
			
			$newArraymerge = array_merge($scanlocs,$extrenalArray);
		}
		else if(!empty($extrenalArray))
		{
			$newArraymerge = $extrenalArray;
		}
		else if(!empty($scanlocs))
		{
			$newArraymerge = $scanlocs;
		}


		foreach ($newArraymerge as $key1 => $value1) {
			# code...

			if($value1['4'])
			{

			$scanloc[$value1['4']] = array(

				'0' => $value1['0'],
				'1' => $value1['1'],
				'2' => $value1['2'],
				'3' => $value1['3'],
				'4' => $value1['4'],
				'5' => $value1['5'],

			);
		}

		}

		



// echo '<pre>';
// print_r($scanloc);
// echo '</pre>';

// die();

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
  	<input type="hidden" name="hidden_contact_email" id="hidden_contact_email" value="">
  	<input type="hidden" name="hidden_contact_qrcode" id="hidden_contact_qrcode" value="">
  	<input type = "hidden" name="hidden_mydistancevalue" id="hidden_mydistancevalue" value="" />
    	
		<div class="header page_header">
			<div><i class="fa fa-clock-o"></i>&nbsp; <?=$lng['Time registration']?></div>
			<a style="float:right" href="../index.php?mn=2"><i class="fa fa-home"></i></a>
		</div>			
	
		<div style="position:absolute; top:55px; bottom:0; left:0; right:0; padding:10px; text-align:center">
			<div id="dump"></div>
			<h1 class="scan_clock"><span id="time">00:00:00</span></h1>
      <center>
				<a href="#" id="btn-scan-qr">
        	<img src="1499401426qr_icon.svg">
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

		<!-- Modal -->
		<div class="modal fade" id="rangeModal1" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel5" aria-hidden="true" data-backdrop="static" data-keyboard="false">
			<div class="modal-dialog" role="document">
				<div class="modal-content">
					<div class="modal-body" style="color:#333; text-align:center">
						<div id="rangeMsg1" style="font-size:18px; line-height:140%; padding:10px 0 15px"><b style="font-size:22px"><?=$lng['QR code not available.']?></b><br><?=$lng['Please reload the page']?><br></div>
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
			
			var scanINOUT = <?=json_encode($_GET['c'])?>;

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
				var scanlocation = 'Unknown';
				var distanceArray = [];
				$.each(myqrcode, function(index, i){
					distance = parseInt((getDistanceFromLatLonInKm(latitude, longitude, i[2], i[3]))*1000);
					distanceArray.push(distance);
					
					if(distance < i[1]){
						mydistance = distance;
						scanlocation = i[0];
						perimeter = i[1];
						contact_email = i[5];
						qrcodevalue = i[4];
					}
					else
					{

						
						// get smallest  value
						mydistance = 'find';
					}
				})

				if(mydistance == 'find')
				{
					var mydistance = distanceArray.reduce((prev,next) => prev>next ? next : prev);
				}


				// alert(qrcodevalue) ; // 433

				document.getElementById('loc').innerHTML = 
				'<?=$lng['Longitude']?> : ' + longitude.toFixed(7) + '<br>' + 
				'<?=$lng['Latitude']?> : ' + latitude.toFixed(7) + '<br>' + 
				'<?=$lng['Accuracy']?> : <b>' + toFormat(accuracy) + ' M.</b><br>' +
				'<?=$lng['Distance']?> : <b>' + toFormat(mydistance) + ' M.<br>' + scanlocation + '</b>';

				$('#hidden_mydistancevalue').val(mydistance);

				if(mydistance > perimeter){
					$('#rangeModal').modal('toggle');
					$('#mydistance').html(toFormat(mydistance) + ' M.');
				}

				$('#hidden_contact_email').val(contact_email);
				$('#hidden_contact_qrcode').val(qrcodevalue);

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
			
			function scan() {
				try {
					qrcode.decode();
				} catch (e) {
					setTimeout(scan, 300);
				}
			}
			
			if(gps){
				watchLocation();
			}else{
				var mydistance = 0;
			}
			
			qrcode.callback = res => {

				console.log(res);
				console.log(myqrcode);


				// alert(res); // 466
				// alert(myqrcode);

				// return false;

				if (res) {


					// if(res in myqrcode){

					if(res == qrcodevalue )
					{
						location = myqrcode[res][0];
						perimeter = myqrcode[res][1];

						// alert(location);

					}else{

						outputData.innerText = '<?=$lng['Wrong QR code']?>';
						scanning = false;
						video.srcObject.getTracks().forEach(track => {
							track.stop();
						});
						qrResult.hidden = false;
						canvasElement.hidden = true;
						btnScanQR.hidden = false;
						return false;
					}

					var mydisatncehidden = $('#hidden_mydistancevalue').val();

					if(parseInt(mydisatncehidden) > parseInt(perimeter)){
						// alert(mydistance + ' - ' + perimeter)
						outputData.innerText = '<?=$lng['You are out of range']?>';
						scanning = false;
						video.srcObject.getTracks().forEach(track => {
							track.stop();
						});
						qrResult.hidden = false;
						canvasElement.hidden = true;
						btnScanQR.hidden = false;
						return false;
					}
					var date = new Date().today();
					var time = new Date().timeNow();
					var datetime = date + " @ " + time;
					var hidden_contact_email= $('#hidden_contact_email').val();
					var hidden_contact_qrcode= $('#hidden_contact_qrcode').val();

					$.ajax({
						url: "ajax/clock_in_out.php",
						type: "POST",
						dataType: 'json', 
						data: {emp_id: employee['emp_id'], date: date, time: time, clock: clock, location: location,hidden_contact_email:hidden_contact_email},
						success: function(response){
							//$('#dump').html(response); return false;
							if(selfie == 1){
								window.location.href="selfie.php?id="+response['id']+"&metaid="+response['metascandata']+"&img="+response['img']+"&date="+response['date']+"&contact="+hidden_contact_qrcode+"&scan="+scanINOUT;	
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
			};
			
			$('#btn-scan-qr').on('click', function(e){
				//alert('Scan button')
				setTimeout(function(){
					navigator.mediaDevices.getUserMedia({ video: {facingMode: "environment"}}).then(function(stream){
						scanning = true;
						qrResult.hidden = true;
						setTimeout(function(){
							btnScanQR.hidden = true;
							canvasElement.hidden = false;
						}, 100);
						video.setAttribute("playsinline", true); // required to tell iOS safari we don't want fullscreen
						video.srcObject = stream;
						video.play();
						tick();
						scan();
					});
				}, 50);
			});
			
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
