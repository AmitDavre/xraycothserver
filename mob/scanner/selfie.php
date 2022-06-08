<?php

	if(session_id()==''){session_start();} 
	ob_start();
	
	include('../../dbconnect/db_connect.php');
	
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
		$sql = "SELECT emp_id, th_name, en_name FROM ".$cid."_employees WHERE emp_id = '".$id."'";
		if($res = $dbc->query($sql)){
			if($row = $res->fetch_assoc()){
				$data = $row;
			}
		}
		return $data;
	}
	$employee = getEmployeeName($_SESSION['rego']['emp_id']);
	
	//var_dump($qrcode); //exit;
	//var_dump($perimeter); exit;
	$urlRott = ROOT;


	// echo '<pre>';
	// print_r($_SESSION);
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
		<link href="../assets/css/font-awesome.min.css" rel="stylesheet" type="text/css">
		<link href="../assets/css/style.css?<?=time()?>" rel="stylesheet">
		<link href="../assets/css/mobStyle.css?<?=time()?>" rel="stylesheet">

		<script type="text/javascript" src="../assets/js/jquery.min.js"></script>
		<script type="text/javascript" src="../assets/js/popper.min.js"></script>
		<script type="text/javascript" src="../assets/js/bootstrap.min.js"></script>
		<script src="adapter.js"></script>
		
		<style>
			.video_wrapper {
				width:60%;
				margin:0 auto;
				xmargin-top:50px;
				border:1px solid #fff;
				display:block;
				padding:5px;
				border:1px solid rgba(255,255,255,0.7);
			}
			video, canvas {
				width:100%;
				min-height:300px;
				display:block;
				background: url(../../images/profile.png) center center no-repeat;
				background-size:cover;
			}
			#msg {
				line-height:50px;
				text-align:center;
				color:#fff;
				font-size:20px;
				font-weight:600;
			}
		</style>
		
	</head>

  <body style="background:#006666">
    
		<div class="header page_header">
			<div><i class="fa fa-clock-o"></i>&nbsp; Time Registration<? //=$lng['Contact']?></div>
			<a style="float:right" href="../index.php?mn=2"><i class="fa fa-home"></i></a>
		</div>			
	
		<div id="selfie_wrapper" style="position:absolute; top:50px; bottom:0; left:0; right:0; display:none">
			<div id="dump"></div>
			<div id="msg">&nbsp;</div>
			
			<div class="video_wrapper">
				<video playsinline autoplay></video>
				<canvas hidden=""></canvas>
			</div>
			<center>
				<button style="border-radius:3px; width:60%; margin-top:20px; text-align:center" type="button" class="btn-selfie btn btn-success"><?=$lng['Take selfie']?></button>
				<button onclick ="goBacktab();"style="border-radius:3px; width:60%; margin-top:20px; text-align:center" type="button" class="btn-selfie btn btn-success"><?=$lng['Go Back']?></button>
			</center>
				
    </div>
		
		<!-- Modal -->
		<div class="modal fade" id="successModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel5" aria-hidden="true" data-backdrop="static" data-keyboard="false">
			<div class="modal-dialog" role="document">
				<div class="modal-content">
					<div class="modal-body" style="color:#333; text-align:center">
						<div id="successMsg" style="font-size:18px; line-height:140%; padding:10px 0 15px"></div>
						<button id="modalBtn" class="btn btn-success">OK</button>
					</div>
				</div>
			</div>
		</div>

		<script> 
		
	$(document).ready(function() {
			
			var id = <?=json_encode($_GET['id'])?>;
			var img = <?=json_encode($_GET['img'])?>;
			var date = <?=json_encode($_GET['date'])?>;
			var metaid = <?=json_encode($_GET['metaid'])?>;
			var contact = <?=json_encode($_GET['contact'])?>;
			var scan_value = <?=json_encode($_GET['scan'])?>;


			const video = document.querySelector('video');
			const canvas = window.canvas = document.querySelector('canvas');
			canvas.width = 200;
			canvas.height = 300;
			
			const button = document.querySelector('.btn-selfie');
			button.onclick = function() {
				button.hidden = true;
				canvas.width = video.videoWidth/2;
				canvas.height = video.videoHeight/2;
				canvas.getContext('2d').drawImage(video, 0, 0, canvas.width, canvas.height);
				video.hidden = true;
				canvas.hidden = false;
				var selfie =  canvas.toDataURL();
				$.ajax({
					type: "POST",
					url: "ajax/save_external_selfie.php",
					data: {image: selfie, id: id, img: img,metaid: metaid,contact:contact,date:date,scan_value:scan_value},
					success: function(response){
						//$('#dump').html(response);
						// alert(response);
						$('#successMsg').html('Time registered successfully<br>' + date);
						$('#successModal').modal('toggle');
					},
					error:function (xhr, ajaxOptions, thrownError){
						alert(thrownError);
					}	
				})		
			};
			
			$('#modalBtn').on('click', function(){

				window.location.href="../";


			})
			
			const constraints = {
				audio: false,
				video: true
			};
			
			function handleSuccess(stream) {
				window.stream = stream;
				video.srcObject = stream;
			}
			
			function handleError(error) {
				alert('Allow camera access');
			}
			
			navigator.mediaDevices.getUserMedia(constraints).then(handleSuccess).catch(handleError);
			
			setTimeout(function(){$('#selfie_wrapper').fadeIn(200);}, 500);
			
			
		})

		</script>

		<script type="text/javascript">
			
			function goBacktab(){

				var urlval = '<?php echo $urlRott ?>';
				window.location.href=urlval+'mob/index.php?mn=16';
			}
		</script>
  </body>
</html>


















