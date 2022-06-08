<?php

	if(session_id()==''){session_start();} 
	ob_start();
	
	include('../../dbconnect/db_connect.php');
	
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
	$employee = getEmployeeName($_SESSION['rego']['emp_id']);
	
	$locations = array();
	$scanloc = array();
	if($res = $dbc->query("SELECT scan_locations FROM ".$cid."_leave_time_settings")){
		$row = $res->fetch_assoc();
		$locations = unserialize($row['scan_locations']);
	}
	foreach($locations as $k=>$v){
		$scanloc[$v['code']] = array($v['name'],$v['perimeter'],$v['latitude'],$v['longitude']);
	}
	//echo json_encode($scanloc);
	//var_dump($scanloc); exit;
	//var_dump($clock); exit;
	

?>

<!DOCTYPE html>
<html>
  <head>
    <title><?=$lng['Time registration']?></title>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width; initial-scale=1.0; maximum-scale=1.0; user-scalable=0;" />
		<link rel="shortcut icon" href="favicon.ico" type="image/x-icon">
		<link rel="icon" href="favicon.ico" type="image/x-icon">

		<link href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700" rel="stylesheet" type="text/css" />
		<link href="../assets/css/bootstrap.min.css" rel="stylesheet" type="text/css">
		<link href="../assets/css/font-awesome.min.css" rel="stylesheet" type="text/css">
		<link href="../assets/css/style.css?<?=time()?>" rel="stylesheet">
		<link href="../assets/css/mobStyle.css?<?=time()?>" rel="stylesheet">
		
		<script src="qr_packed.js?<?=time()?>"></script>
		<script src="globalize.js?<?=time()?>"></script> 
		<script type="text/javascript" src="../assets/js/jquery.min.js"></script>
		<script type="text/javascript" src="../assets/js/popper.min.js"></script>
		<script type="text/javascript" src="../assets/js/bootstrap.min.js"></script>
	
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
			
			<h1 style="padding-bottom:0"><?=$lng['Your location']?></h1> 
			<p id=loc></p>
			<? //=var_dump($scanloc)?>
			<div style="position:absolute; bottom:20px; left:0; right:0; text-align:center">
				<button style="border-radius:3px" onClick="window.location.reload()" type="button" class="btn btn-success"><i class="fa fa-refresh"></i>&nbsp; <?=$lng['Reload']?></button>
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

	
	<script> 
		
		Date.prototype.today = function () { 
			return ((this.getDate() < 10)?"0":"") + this.getDate() +"-"+(((this.getMonth()+1) < 10)?"0":"") + (this.getMonth()+1) +"-"+ this.getFullYear();
		}
		Date.prototype.timeNow = function () {
			return ((this.getHours() < 10)?"0":"") + this.getHours() +":"+ ((this.getMinutes() < 10)?"0":"") + this.getMinutes() +":"+ ((this.getSeconds() < 10)?"0":"") + this.getSeconds();
		}
		//var datetime = new Date().today() + " @ " + new Date().timeNow();
		//alert(datetime)
			
	$(document).ready(function() {
			//$('#successModal').modal('toggle');
			
			var employee = <?=json_encode($employee)?>; // sometimes undefined ???
			var clock = <?=json_encode($clock)?>; // sometimes undefined ???
			var myqrcode = <?=json_encode($scanloc)?>;
			var perimeter = 1;
			var distance = 9999;
			var selfie = <?=json_encode($employee['selfie'])?>;
			var location = '';
			
			const video = document.createElement("video");
			const canvasElement = document.getElementById("qr-canvas");
			const canvas = canvasElement.getContext("2d");
			const qrResult = document.getElementById("qr-result");
			const outputData = document.getElementById("outputData");
			const btnScanQR = document.getElementById("btn-scan-qr");
			let scanning = false;
			
			$('#modalBtn').on('click', function(){
				window.location.href="../index.php";
			})

			qrcode.callback = res => {
				if (res) {
					
					if(res in myqrcode){
						location = myqrcode[res][0];
						//perimeter = myqrcode[res][1];
						//alert(location+' - '+perimeter)
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
					if(parseInt(distance) > parseInt(perimeter)){
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
					//alert(parseInt(distance));
					//alert(parseInt(perimeter));
					var date = new Date().today();
					var time = new Date().timeNow();
					var datetime = date + " @ " + time;
					
					$.ajax({
						url: "ajax/clock_in_out.php",
						type: "POST",
						dataType: 'json', 
						data: {emp_id: employee['emp_id'], date: date, time: time, clock: clock, location: location},
						success: function(response){
							//$('#dump').html(response['id']); return false;
							if(selfie == 1){
								window.location.href="selfie.php?id="+response['id']+"&img="+response['img'];	
							}else{
								$('#successMsg').html(employee['en_name'] + '<br>' + datetime + '<br><?=$lng['Time registered successfully']?>');
								$('#successModal').modal('toggle');
								qrResult.hidden = false;
								//setTimeout(function(){window.location.href="../index.php";}, 2000);
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
			
			btnScanQR.onclick = () => {
				//alert('QR code')
				
				/*var date = new Date().today();
				var time = new Date().timeNow();
				var datetime = date + " @ " + time;
				$.ajax({
					url: "ajax/clock_in_out.php",
					type: "POST",
					//dataType: 'json', 
					data: {emp_id: employee['emp_id'], date: date, time: time, clock: clock, location: location},
					success: function(response){
						$('#dump').html(response); return false;
						$('#successMsg').html(employee['en_name'] + '\n' + datetime + '\nTime registered successfully');
						$('#successModal').modal('toggle');
						qrResult.hidden = true;
						//setTimeout(function(){window.location.href="../index.php";}, 2000);
					},
					error:function (xhr, ajaxOptions, thrownError){
						alert(thrownError);
					}
				});
				return false;*/
				/*$('#modalBtn').on('click', function(){
					window.location.href="../index.php";
				})*/
				
				navigator.mediaDevices.getUserMedia({ video: {facingMode: "environment"}}).then(function(stream){
					scanning = true;
					qrResult.hidden = true;
					setTimeout(function(){
						btnScanQR.hidden = true;
						canvasElement.hidden = false;
					}, 50);
					video.setAttribute("playsinline", true); // required to tell iOS safari we don't want fullscreen
					video.srcObject = stream;
					video.play();
					tick();
					scan();
				});
			};
			
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
				//distance = parseInt((getDistanceFromLatLonInKm(loc.coords.latitude,loc.coords.longitude,sys_latitude,sys_longitude))*1000);
				var smdistance = 0;
				var scanlocation = 0;
				$.each(myqrcode, function(index, i){
					distance = parseInt((getDistanceFromLatLonInKm(loc.coords.latitude, loc.coords.longitude, i[2], i[3]))*1000);
					if(smdistance == 0 || distance < smdistance){
						smdistance = distance;
						scanlocation = i[0];
						perimeter = i[1];
					}
					//alert(smdistance+' - '+distance+' - '+i[1])
					//if(distance < i[1]){
						//scanlocs.push(response[index][0]);
					//}
				})
				distance = smdistance;
				//alert(distance)
				document.getElementById('loc').innerHTML = 
				'<?=$lng['Longitude']?> : ' + loc.coords.longitude + '<br>' + 
				'<?=$lng['Latitude']?> : ' + loc.coords.latitude + '<br>' + 
				'<?=$lng['Accuracy']?> : <b>' + Globalize.format(loc.coords.accuracy,'n0') + ' m.' + '</b><br>' +
				'<?=$lng['Distance']?> : <b>' + parseInt(distance) + ' m.<br>' + scanlocation + '</b>';
			} 
			
			function error(loc) { 
				alert('<?=$lng['Error in Geolocation']?>'); 
			} 
			navigator.geolocation.getCurrentPosition(showLocation, error); 
			
			
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
  </body>
</html>
