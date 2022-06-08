<?php

	if(session_id()==''){session_start();} 
	ob_start();
	
	include('../dbconnect/db_connect.php');
	
	/*$data = array();
	$sql = "SELECT emp_id, sid, th_name, en_name, selfie FROM ".$cid."_employees ORDER BY emp_id ASC";
	if($res = $dbc->query($sql)){
		while($row = $res->fetch_assoc()){
			$employees[$row['emp_id']] = $row;
		}
	}*/
	
	function getJsonIdsEmployees(){
		global $dbc;
		global $cid;
		global $lang;
		$data = array();
		$sql = "SELECT emp_id, ".$lang."_name, personal_phone, image FROM ".$cid."_employees WHERE emp_status = 1 ORDER BY emp_id ASC";
		if($res = $dbc->query($sql)){
			if($res->num_rows > 0){
				while($row = $res->fetch_assoc()){
					$image = $row['image'];
					if(empty($row['image'])){$image = 'images/profile_image.jpg';}
					$data[] = array('data'=>$row['emp_id'], 'value'=>$row['emp_id'].' - '.$row[$lang.'_name'], 'name'=>$row[$lang.'_name'], 'phone'=>$row['personal_phone'], 'image'=>$image);
				}
			}
		}
		return $data;
		//return mysqli_error($dbc);
	}
	$emp_array = getJsonIdsEmployees();
	//var_dump($emp_array); exit;

?>

<!DOCTYPE html>
<html>
  <head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<title>REGO HR</title>

		<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700">
		<link rel="stylesheet" href="../assets/css/bootstrap.min.css" rel="stylesheet">
		<link rel="stylesheet" href="../assets/css/font-awesome.min.css" type="text/css">
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
      
			<div style="max-width:1000px; margin:0 auto">
					
			<table border="0" class="searchFilter" cellpadding="4px">
				<tr>
					<td colspan="3">
						<input style="width:100%" class="sFilter selEmployee" placeholder="<?=$lng['Employee filter']?> ... <?=$lng['Type for hints']?> ..." type="text" />
					</td>
				</tr>
				<tr>
					<td>
						<button id="clearSearchbox" type="button" class="btn btn-danger btn-block">Clear</button>
					</td>
					<td>
						<button id="btn-selfie" disabled type="button" class="btn btn-success btn-block">Take selfie<? //=$lng['Submit']?></button>

					</td>
					<td>
						<button onClick="window.location.reload()" type="button" class="btn btn-primary btn-block">Reload<? //=$lng['Submit']?></button>
					</td>
				</tr>
				<tr>
					<td colspan="3">
						<div class="video_wrapper" style="max-width:100%; position:relative">
							<video playsinline autoplay style="min-width:100%; max-width:100%"></video>
							<canvas hidden="" style="min-width:100%; max-width:100%"></canvas>
							<div id="outputData" hidden=""></div>
						</div>
					</td>
				</tr>
			</table>
			
			</div>
				
    </div>
		
		<div class="header">
			<i class="fa fa-clock-o"></i>&nbsp; Time Registration<? //=$lng['Contact']?>
		</div>			

	<script> 
		
		Date.prototype.today = function () { 
			return ((this.getDate() < 10)?"0":"") + this.getDate() +"-"+(((this.getMonth()+1) < 10)?"0":"") + (this.getMonth()+1) +"-"+ this.getFullYear();
		}
		Date.prototype.timeNow = function () {
			return ((this.getHours() < 10)?"0":"") + this.getHours() +":"+ ((this.getMinutes() < 10)?"0":"") + this.getMinutes() +":"+ ((this.getSeconds() < 10)?"0":"") + this.getSeconds();
		}
			
		$(document).ready(function() {
			
			var employees = <?=json_encode($emp_array)?>;
			var employee;
			var emp_id;
			
			const video = document.querySelector('video');
			const canvas = window.canvas = document.querySelector('canvas');
			const button = document.getElementById("btn-selfie");
			const outputData = document.getElementById("outputData");
			canvas.width = 200;
			canvas.height = 300;
			
			button.onclick = function() {
				canvas.width = video.videoWidth/2;
				canvas.height = video.videoHeight/2;
				canvas.getContext('2d').drawImage(video, 0, 0, canvas.width, canvas.height);
				video.hidden = true;
				canvas.hidden = false;
				button.disabled = true;
				var selfie =  canvas.toDataURL();
				var date = new Date().today();
				var time = new Date().timeNow();
				var datetime = date + " @ " + time;
				

				$.ajax({
					type: "POST",
					url: "ajax/save_clock_selfie.php",
					data: {emp_id: emp_id, date: date, time: time, image: selfie},
					success: function(response){
						//$('#dump').html(response);
						outputData.innerText = employee + '\n' + datetime + '\nTime registered successfully';
						outputData.hidden = false;
						setTimeout(function(){$("#clearSearchbox").trigger('click');}, 5000);
					},
					error:function (xhr, ajaxOptions, thrownError){
						alert(thrownError);
					}	
				})		
			};
			
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
			
			setTimeout(function(){$('#video_wrapper').fadeIn(200);}, 500);
			
			$('.selEmployee').devbridgeAutocomplete({
				 lookup: employees,
				 triggerSelectOnValidInput: false,
				 onSelect: function (suggestion) {
					employee = suggestion.value;
					emp_id = suggestion.data;
				 	$("#btn-selfie").prop('disabled', false);
				 }
			});	
			$(document).on("click", "#clearSearchbox", function(e) {
				$('.selEmployee').val('');
				button.disabled = true;
				outputData.hidden = true;
				video.hidden = false;
				canvas.hidden = true;
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
