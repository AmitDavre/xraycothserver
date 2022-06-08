<?php

	if(session_id()==''){session_start();} 
	ob_start();
	
	include('db_connect.php');
	include(DIR.'files/arrays_'.$lang.'.php');
	//unset($_SESSION['scan']['cid']);
	
	if(!isset($_SESSION['scan']['cid'])){
		header('location: login.php');
	}
	//var_dump($_SESSION); exit;
	
	function getJsonIdsEmployees(){
		global $dbc;
		global $cid;
		global $lang;
		$data = array();
		$sql = "SELECT emp_id, ".$lang."_name, image FROM ".$cid."_employees WHERE emp_status = 1 ORDER BY emp_id ASC";
		if($res = $dbc->query($sql)){
			if($res->num_rows > 0){
				while($row = $res->fetch_assoc()){
					$image = $row['image'];
					if(empty($row['image'])){$image = 'images/profile_image.jpg';}
					$data[] = array('data'=>$row['emp_id'], 'value'=>$row['emp_id'].' - '.$row[$lang.'_name'], 'name'=>$row[$lang.'_name'], 'image'=>$image);
				}
			}
		}
		return $data;
		//return mysqli_error($dbc);
	}
	$emp_array = getJsonIdsEmployees();
	//var_dump($emp_array); exit;
	
	$myear = date('Y');
	if($lang == 'th'){$myear += 543;}
	$thai_date = $weekdays[date('N')].' '.date('d-m-').$myear;



		// CHECK IF CUSTOMER IS NOT SUSPENDED //////////////////////////////////////////////////////////////
		$sql = "SELECT * FROM rego_customers WHERE clientID = '".$cid."'";
		if($res = $dbx->query($sql)){
			if($row = $res->fetch_assoc()){
				$expire_date = $row['period_end'];
				$diff = strtotime($row['period_end']) - strtotime(date('d-m-Y'));
				$days_left = floor($diff / (60*60*24));
				if($days_left < 0){$days_left = 0;}
				$_SESSION['rego']['expire'] = $days_left;
				$_SESSION['rego']['status_val'] = $row['status'];
			}
		}


	if(($days_left <= 0) || ($_SESSION['rego']['status_val'] =='0') || ($_SESSION['rego']['status_val'] =='2') || ($_SESSION['rego']['status_val'] =='3'))
	{
		header('location: '.ROOT.'hr/scan/expired.php');
	}


?>


<!DOCTYPE html>
<html>
  <head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<title><?=$www_title?></title>
		
		<meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate"/>
  	<meta http-equiv="Pragma" content="no-cache"/>
  	<meta http-equiv="Expires" content="0"/>

		<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700">
		<link rel="stylesheet" href="../assets/css/bootstrap.min.css">
		<link rel="stylesheet" href="../assets/css/font-awesome.min.css">
		<link rel="stylesheet" href="css/style.css?<?=time()?>">

		<script type="text/javascript" src="js/jquery.min.js"></script>
		<script type="text/javascript" src="../assets/js/bootstrap.min.js"></script>
		<script type="text/javascript" src="../assets/js/jquery.autocomplete.js"></script>
		<script type="text/javascript" src="js/adapter.js"></script>
	
	</head>

	<body style="background:#006666">
		
		<div class="time-wrapper">
			<div id="dump"></div>
			<h1 class="scan_clock"><span id="time">00:00:00</span></h1>
			<div style="font-size:24px; color:#fff; margin-top:-25px; padding-bottom:10px"><?=$thai_date?></div>
      
			<div class="body-wrapper">
				<input class="sFilter selEmployee" placeholder="<?=$lng['Employee filter']?> ... <?=$lng['Type for hints']?> ..." type="text" />
			<table border="0" class="btnTable">
				<tr>
					<td width="33%">
						<button disabled data-id="in" type="button" class="clockBtn btn btn-success btn-block"><?=$lng['Punch IN']?></button>
					</td>
					<td width="34%" style="padding:0 8px">
						<button id="clearSearchbox" type="button" class="btn btn-info btn-block"><?=$lng['Clear']?></button>
					</td>
					<td width="33%">
						<button disabled data-id="out" type="button" class="clockBtn btn btn-danger btn-block"><?=$lng['Punch OUT']?></button>
					</td>
				</tr>
			</table>
			<div class="video_wrapper">
				<video hidden="" playsinline autoplay style="min-width:100%; max-width:100%; border-radius:3px"></video>
				<canvas hidden="" style="min-width:100%; max-width:100%; border-radius:3px"></canvas>
				<div id="outputData" hidden=""></div>
				<img id="emp_image" />
			</div>
			
			</div>
				
    </div>
		
		<div class="header">
			<i class="fa fa-clock-o"></i>&nbsp; <?=$lng['Time registration']?>
			<a href="#" id="logout"><i class="fa fa-sign-out fa-lg"></i></a>
		</div>			
		
		<!-- Modal -->
		<div class="modal fade" id="successModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel5" aria-hidden="true" data-backdrop="static" data-keyboard="false" style="z-index:9999">
			<div class="modal-dialog" role="document">
				<div class="modal-content">
					<div class="modal-body" style="color:#333; text-align:center">
						<div id="successMsg" style="font-size:20px; line-height:160%; padding:10px 0 15px"></div>
						<button id="successBtn" data-dismiss="modal" class="btn btn-success btn-block"><?=$lng['OK']?></button>
					</div>
				</div>
			</div>
		</div>

		<!-- Modal -->
		<div class="modal fade" id="errorModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel5" aria-hidden="true" data-backdrop="static" data-keyboard="false" style="z-index:9999">
			<div class="modal-dialog" role="document">
				<div class="modal-content">
					<div class="modal-body" style="color:#333; text-align:center">
						<div id="errorMsg" style="font-size:20px; line-height:160%; padding:10px 0 15px"></div>
						<button id="errorBtn" data-dismiss="modal" class="btn btn-danger btn-block"><?=$lng['OK']?></button>
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
			
		$(document).ready(function() {
			
			var height = window.innerHeight - 350;
			var employees = <?=json_encode($emp_array)?>;
			var employee;
			var emp_id;
			
			const video = document.querySelector('video');
			const canvas = window.canvas = document.querySelector('canvas');
			canvas.width = 200;
			canvas.height = 300;
			
			$(".clockBtn").on('click', function(){
				var clock = $(this).data('id');
				$(".clockBtn").prop('disabled', true);
				canvas.width = video.videoWidth/2;
				canvas.height = video.videoHeight/2;
				canvas.getContext('2d').drawImage(video, 0, 0, canvas.width, canvas.height);
				video.hidden = true;
				canvas.hidden = false;
				var selfie =  canvas.toDataURL();
				var date = new Date().today();
				var time = new Date().timeNow();
				var datetime = date + " @ " + time;
				$.ajax({
					type: "POST",
					url: "ajax/save_clock_selfie.php",
					data: {emp_id: emp_id, date: date, time: time, image: selfie, clock: clock},
					success: function(response){
						//$('#dump').html(response); return false;
						if($.trim(response) == 'success'){
							$('#successMsg').html(employee + '<br>' + datetime + '<br><?=$lng['Time registered successfully']?>');
							$('#successModal').modal('toggle');
							//setTimeout(function(){$("#clearSearchbox").trigger('click');}, 5000);
						}else{
							$('#errorMsg').html('<?=$lng['Error']?> : ' + response);
							$('#errorModal').modal('toggle');
							//setTimeout(function(){$("#clearSearchbox").trigger('click');}, 5000);
						}
					},
					error:function (xhr, ajaxOptions, thrownError){
						$('#errorMsg').html('<?=$lng['Error']?> : ' + thrownError);
						$('#errorModal').modal('toggle');
						//setTimeout(function(){$("#clearSearchbox").trigger('click');}, 5000);
					}	
				})		
			});
			
			/*if(navigator.mediaDevices && navigator.mediaDevices.getUserMedia) {
				navigator.mediaDevices.getUserMedia({ audio: false, video: true })
					.then(function(stream) {
						if ("srcObject" in video) {
								video.srcObject = stream;
							} else {
								video.src = window.URL.createObjectURL(stream);
							}
							video.onloadedmetadata = function(e) {
								video.play();
							};
					});
			};*/			
			
			const constraints = {
				audio: false,
				video: true
			};
			function handleSuccess(stream) {
				window.stream = stream; // make stream available to browser console
				video.srcObject = stream;
			}
			function handleError(error) {
				alert('navigator.MediaDevices.getUserMedia error: ', error.message, error.name);
			}
			navigator.mediaDevices.getUserMedia(constraints).then(handleSuccess).catch(handleError);
			setTimeout(function(){video.hidden = false;}, 500);
			
			$('#successBtn, #errorBtn').on('click', function(){
				$("#clearSearchbox").trigger('click');
			})
			
			$('.selEmployee').devbridgeAutocomplete({
				 lookup: employees,
				 triggerSelectOnValidInput: false,
				 onSelect: function (suggestion) {
					employee = suggestion.value;
					emp_id = suggestion.data;
				 	$(".clockBtn").prop('disabled', false);
				 	$("#emp_image").attr('src', '../'+suggestion.image).fadeIn(200);
					//alert(suggestion.image)
				 }
			});	
			$(document).on("click", "#clearSearchbox", function(e) {
				$('.selEmployee').val('');
				$("#emp_image").fadeOut(200);
				$(".clockBtn").prop('disabled', true);
				video.hidden = false;
				canvas.hidden = true;
			})

			$("#logout").on('click', function(e) {
				$.ajax({
					url: "ajax/logout.php",
					success: function(response){
						location.reload();
					}	
				})		
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

	</body>
</html>
